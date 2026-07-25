#!/usr/bin/env bash

set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
dolibarr_version="${DOLIBARR_VERSION:-23.0.0}"
htdocs_dir="${repo_root}/.test-env/dolibarr-${dolibarr_version}/htdocs"
theme_target="${htdocs_dir}/theme/formic"

if [[ ! -d "${htdocs_dir}/theme" ]]; then
	echo "Dolibarr ${dolibarr_version} is not installed under .test-env." >&2
	echo "Run ./dev/setup-test-env.sh first." >&2
	exit 1
fi

if [[ -L "${theme_target}" ]]; then
	rm "${theme_target}"
elif [[ -e "${theme_target}" && ! -d "${theme_target}" ]]; then
	echo "Refusing to replace non-directory theme path: ${theme_target}" >&2
	exit 1
fi

mkdir -p "${theme_target}"
rsync -a --delete "${repo_root}/formic/" "${theme_target}/"
echo "Synced Formic theme to ${theme_target}"
