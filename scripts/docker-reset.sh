#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SLEEP_TIME=50

echoc () {
    GREEN=$(tput setaf 2)
    RESET=$(tput sgr 0)
	echo -e "${GREEN}$1${RESET}"
}

# Preemptive sudo lease - to let you go out and grab a coffee while the script
# runs.
sudo echo ""

# Clear all running containers.
echoc "*** Removing existing containers"
docker-compose kill && docker-compose rm -v -f

# Start up containers in the background and continue immediately
echoc "*** Starting new containers"
docker-compose up --remove-orphans -d

# Sleep while containers are starting up then perform a reset
echoc "*** Waiting ${SLEEP_TIME} seconds for the containers to come up and database to be imported"
sleep $SLEEP_TIME

# Perform the drupal-specific reset
echoc "*** Resetting Drupal"
"${SCRIPT_DIR}/site-reset.sh"

# Done, bring the background docker-compose logs back into foreground
echoc "*** Done, watching logs"
docker-compose logs -f
