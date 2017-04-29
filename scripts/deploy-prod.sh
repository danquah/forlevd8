#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

CLONE_DIR="/home/hostroot/sites/danquah/forlevdk/forlevd8"

read -p "Pull latest release to PRODUCTION? (y/n)" -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
  echo "Aborted"
  exit 1
fi
ssh -A forlevdk_login@anne.flab.dk "cd ${CLONE_DIR}; bash -s" < "${SCRIPT_DIR}/general-deploy.sh"
