#!/usr/bin/env bash
# Run remotely via deploy-<env>.sh
set -euo pipefail
IFS=$'\n\t'

TMPDIR=$(mktemp -d)
cleanup() {
    rm -rf "${TMPDIR}"
}
trap cleanup EXIT


# Pre-pull check.
if [ ! -z "$(git status --porcelain)" ];
then
    echo "*** Dirty repository detected ***"
    git status
    exit 1
fi

CONFIG_DIR="configuration"
cd web
echo "Checking for overridden configuration"
drush cex --destination="${TMPDIR}"
set +e
diff -x .htaccess -r "../${CONFIG_DIR}" "${TMPDIR}"
if [ ! $? -eq 0 ]; then
    echo "Overridden configuration detected, please update the codebase"
    exit 1
fi
set -e

cd ..

# Fetch latest from current branch.
git pull

# Update dependencies.
echo "Composer installing"
composer install

# build assets
echo "NPM installing"
cd web/themes/forlev2016
npm install

# Import configuration from latest revision - overwrite the current state.
drush cim --preview=diff -y

# Perform any updates required by updated modules.
drush updb -y

drush -y entup

# Clear cache.
drush cr
