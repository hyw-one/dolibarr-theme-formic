#!/usr/bin/env bash

set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
runtime_dir="${repo_root}/.test-env"
dolibarr_version="${DOLIBARR_VERSION:-23.0.0}"
dolibarr_dir="${runtime_dir}/dolibarr-${dolibarr_version}"
htdocs_dir="${dolibarr_dir}/htdocs"
documents_dir="${runtime_dir}/dolibarr-documents"
archive_path="${runtime_dir}/dolibarr-${dolibarr_version}.tar.gz"
download_url="https://github.com/Dolibarr/dolibarr/archive/refs/tags/${dolibarr_version}.tar.gz"

db_name="${FORMIC_DB_NAME:-formic_dolibarr_test}"
db_user="${FORMIC_DB_USER:-formic_test}"
db_password="${FORMIC_DB_PASSWORD:-formic_test}"
db_host="${FORMIC_DB_HOST:-127.0.0.1}"
db_port="${FORMIC_DB_PORT:-3306}"
db_root_user="${FORMIC_DB_ROOT_USER:-root}"
db_root_password="${FORMIC_DB_ROOT_PASSWORD:-}"
test_url="${FORMIC_TEST_URL:-http://127.0.0.1:18090}"
admin_login="${FORMIC_ADMIN_LOGIN:-admin}"
admin_password="${FORMIC_ADMIN_PASSWORD:-admin}"

for command_name in curl tar php mysql mysqladmin rsync; do
	if ! command -v "${command_name}" >/dev/null 2>&1; then
		echo "Missing required command: ${command_name}" >&2
		exit 1
	fi
done

mysql_root_args=(-u "${db_root_user}")
if [[ -n "${db_root_password}" ]]; then
	mysql_root_args+=("-p${db_root_password}")
fi

mysql_app_args=(-h "${db_host}" -P "${db_port}" -u "${db_user}" "-p${db_password}")

if ! mysqladmin "${mysql_root_args[@]}" ping >/dev/null 2>&1; then
	echo "MySQL is not running or the root credentials are invalid." >&2
	echo "On Homebrew macOS, start it with: brew services start mysql" >&2
	exit 1
fi

mkdir -p "${runtime_dir}" "${documents_dir}"

if [[ ! -d "${htdocs_dir}" ]]; then
	if [[ ! -f "${archive_path}" ]]; then
		echo "Downloading Dolibarr ${dolibarr_version}..."
		curl -fL --retry 3 -o "${archive_path}" "${download_url}"
	fi
	echo "Extracting Dolibarr ${dolibarr_version}..."
	tar -xzf "${archive_path}" -C "${runtime_dir}"
fi

"${repo_root}/dev/sync-test-theme.sh"

database_exists="$(mysql "${mysql_root_args[@]}" -NBe "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='${db_name}'")"
if [[ -z "${database_exists}" ]]; then
	echo "Creating test database and user..."
	mysql "${mysql_root_args[@]}" -e "
		CREATE DATABASE \`${db_name}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
		CREATE USER IF NOT EXISTS '${db_user}'@'localhost' IDENTIFIED BY '${db_password}';
		CREATE USER IF NOT EXISTS '${db_user}'@'127.0.0.1' IDENTIFIED BY '${db_password}';
		GRANT ALL PRIVILEGES ON \`${db_name}\`.* TO '${db_user}'@'localhost';
		GRANT ALL PRIVILEGES ON \`${db_name}\`.* TO '${db_user}'@'127.0.0.1';
		FLUSH PRIVILEGES;
	"
fi

conf_file="${htdocs_dir}/conf/conf.php"
if [[ ! -s "${conf_file}" ]]; then
	table_count="$(mysql "${mysql_app_args[@]}" -NBe "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${db_name}'")"
	if [[ "${table_count}" != "0" ]]; then
		echo "The test database contains tables but Dolibarr has no configuration file." >&2
		echo "Use a fresh FORMIC_DB_NAME or remove the incomplete test database first." >&2
		exit 1
	fi

	touch "${conf_file}"

	# Dolibarr 23.0.0 creates an unbounded index on a TEXT column. MySQL 9
	# rejects it, so patch only the downloaded test copy before installation.
	blockedlog_key_file="${htdocs_dir}/install/mysql/tables/llx_blockedlog.key.sql"
	perl -0pi -e 's/idx_linktoref \(linktoref\)/idx_linktoref (linktoref(255))/' "${blockedlog_key_file}"

	echo "Installing Dolibarr ${dolibarr_version}..."
	(
		cd "${htdocs_dir}/install"
		php step1.php set en_US "${htdocs_dir}" "${documents_dir}" "${test_url}" \
			"" "" mysqli "${db_host}" "${db_name}" "${db_user}" "${db_password}" \
			"${db_port}" llx_ "" "" > "${dolibarr_dir}/install-step1.html"
		php step2.php set en_US > "${dolibarr_dir}/install-step2.html"
		php step5.php "" "" en_US set "${admin_login}" "${admin_password}" \
			"${admin_password}" 1 > "${dolibarr_dir}/install-step5.html"
	)
fi

echo "Installing Formic settings and demo orders..."
php "${repo_root}/dev/seed-orders.php" "${htdocs_dir}" "${admin_login}"

echo
echo "Dolibarr test environment is ready."
echo "URL:      ${test_url}"
echo "Login:    ${admin_login}"
echo "Password: ${admin_password}"
echo "Start:    ./dev/start-test-env.sh"
