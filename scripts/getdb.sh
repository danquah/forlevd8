#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

TMPDIR=$(mktemp -d)
FILE_NAME=100-forlevdk_$(date --iso-8601=seconds).sql.gz
DESTINATION_DIR="${SCRIPT_DIR}/../docker/db/initdb.d"
DESTINATION="${DESTINATION_DIR}/${FILE_NAME}"
TARGET_HOST="ny.flab.dk"
DOCROOT="/home/hostroot/sites/danquah/forlevdk/forlevd8/web"

cleanup() {
    rm -rf "${TMPDIR}"
    cd
    /usr/bin/env ssh -q ${TARGET_HOST} "rm /tmp/${FILE_NAME}"
}
trap cleanup EXIT

/usr/bin/env ssh -q ${TARGET_HOST} "drush --quiet sql-dump --root=${DOCROOT} --structure-tables-list='cache_*,semaphore,sessions,watchdog' --ordered-dump --gzip --result-file=/tmp/${FILE_NAME%\.gz}"

cd "${TMPDIR}"

TMP_DESTINATION="${TMPDIR}/${FILE_NAME}"

/usr/bin/env scp -q ${TARGET_HOST}:/tmp/${FILE_NAME} "${TMP_DESTINATION}"

if [ ! -s "${TMP_DESTINATION}" ]; then
    >&2 echo "forlev.dk: Database dump could not be fetched or file empty."
    exit 1
fi

files=("${DESTINATION_DIR}"/100*)
if [ -e "${files[0]}" ];
then
    echo "Moving existing dumps to /tmp"
    mv -vf "${DESTINATION_DIR}"/100* /tmp
fi

mv "${TMP_DESTINATION}" "${DESTINATION}"
echo ""
echo "${FILE_NAME} written to docker/db/initdb.d/"
