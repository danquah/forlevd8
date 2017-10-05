#!/usr/bin/env bash
# Prepares a site for development.
# The assumption is that we're bootstrapping with a database-dump from
# another environment, and need to import configuration and run updb.

set -euo pipefail
IFS=$'\n\t'
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Chmod to 777 if the file is not owned by www-data
cd "${SCRIPT_DIR}/../"
find web/sites/default/files \! -user $USER  \! -print0 -name .gitkeep | sudo xargs -0 chmod 777

# Make sites/default read-only and executable
sudo chmod 555 web/sites/default

time docker-compose exec fpm sh -c "cd /var/www && composer install "
time docker-compose exec --user www-data fpm sh -c "\
  cd /var/www/web && \
  echo 'Importing configuration' && \
  drush cim --preview=diff -y && \
  echo 'Updating database' && \
  drush updb -y && \
  drush -y entup && \
  echo 'Clearing cache' && \
  drush cr
  "
