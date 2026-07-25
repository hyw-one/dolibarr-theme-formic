#!/usr/bin/env bash

set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
dolibarr_version="${DOLIBARR_VERSION:-23.0.0}"
htdocs_dir="${repo_root}/.test-env/dolibarr-${dolibarr_version}/htdocs"
listen_address="${FORMIC_TEST_LISTEN:-127.0.0.1:18090}"

if [[ ! -s "${htdocs_dir}/conf/conf.php" ]]; then
	echo "The test environment is not installed. Run ./dev/setup-test-env.sh first." >&2
	exit 1
fi

"${repo_root}/dev/sync-test-theme.sh"

echo "Formic test environment: http://${listen_address}"
echo "Press Ctrl-C to stop."
exec php -S "${listen_address}" -t "${htdocs_dir}"
