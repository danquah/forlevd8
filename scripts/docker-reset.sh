#!/usr/bin/env bash
# Removes all containers and starts up a clean environment
# Invokes site-reset.sh after container startup.

set -euo pipefail
IFS=$'\n\t'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
# The number of seconds it takes for docker-compose up to get up and running
# often translates to how long it takes the database-container to come up.
SLEEP_TIME=10
# Hostname to send a request to to warm up the cache-cleared site.
HOST="localhost"
WEB_CONTAINER="web"

echoc () {
    GREEN=$(tput setaf 2)
    RESET=$(tput sgr 0)
	echo -e "${GREEN}$1${RESET}"
}

# Determine if we have a docker-sync config file and docker-sync in path.
DOCKER_SYNC=
if [[ $(type -P "docker-sync") && -f "${SCRIPT_DIR}/../docker-sync.yml" ]]; then
    DOCKER_SYNC=1
fi


# Start off at the root of the project.
cd $SCRIPT_DIR/../

# Preemptive sudo lease - to let you go out and grab a coffee while the script
# runs.
sudo echo ""

if [[ $DOCKER_SYNC ]]; then
    echoc "*** Performing Initial docker sync"
    docker-sync start || true
    docker-sync sync
fi

# Clear all running containers.
echoc "*** Removing existing containers"
docker-compose kill && docker-compose rm -v -f

# Start up containers in the background and continue imidiately
echoc "*** Starting new containers"

COMPOSER_OVERRIDE=
[ -f "docker-compose.override.yml" ] && COMPOSER_OVERRIDE="-f docker-compose.override.yml"
if [[ $DOCKER_SYNC ]]; then
    cmd="docker-compose -f docker-compose.yml -f docker-compose-dev.yml ${COMPOSER_OVERRIDE} up --remove-orphans -d"
else
    cmd="docker-compose up --remove-orphans -d"
fi
eval $cmd


# Sleep while containers are starting up then perform a reset
echoc "*** Waiting ${SLEEP_TIME} seconds for the containers to come up and database to be imported"
sleep $SLEEP_TIME

# Perform the drupal-specific reset
echoc "*** Resetting Drupal"
"${SCRIPT_DIR}/site-reset.sh"

echoc "*** Requesting ${HOST} in ${WEB_CONTAINER}"
docker-compose exec ${WEB_CONTAINER} curl --silent --output /dev/null -H "Host: ${HOST}" localhost

# Done, bring the background docker-compose logs back into foreground
echoc "*** Done, watching logs"
docker-compose logs -f
