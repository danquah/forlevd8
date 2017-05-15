#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SLEEP_TIME=20
HOST="localhost"
WEB_CONTAINER="web"

echoc () {
    GREEN=$(tput setaf 2)
    RESET=$(tput sgr 0)
	echo -e "${GREEN}$1${RESET}"
}

# Start off at the root of the project.
cd $SCRIPT_DIR/../

# Preemptive sudo lease - to let you go out and grab a coffee while the script
# runs.
sudo echo ""

# Clear all running containers.
echoc "*** Removing existing containers"
docker-compose kill && docker-compose rm -v -f

# Composer silently kills any valid sudo leases, to avoid elevation-exploits in
# scripts - we disable this to make sure we only have to give sudo a password
# once.
echoc "*** Composer installing"
COMPOSER_ALLOW_SUPERUSER=1 composer install

# Build theme assets.
pushd web/themes/forlev2016

echoc "*** NPM installing"
npm install

echoc "*** Doing an initial gulp build"
grunt

popd

# Start up containers in the background and continue immediately
echoc "*** Starting new containers"
docker-compose up --remove-orphans -d

# Sleep while containers are starting up then perform a reset
echoc "*** Waiting ${SLEEP_TIME} seconds for the containers to come up and database to be imported"
sleep $SLEEP_TIME

# Perform the drupal-specific reset
echoc "*** Resetting Drupal"
"${SCRIPT_DIR}/site-reset.sh"

echoc "*** Requesting ${HOST} in ${WEB_CONTAINER}"
docker-compose exec ${WEB_CONTAINER} curl -H "Host: ${HOST}" localhost

# Done, bring the background docker-compose logs back into foreground
echoc "*** Done, watching logs"
docker-compose logs -f
