# Dolibarr test environment

This local environment installs Dolibarr 23.0.0, links the repository's
`formic` directory into Dolibarr's theme directory, enables the required
modules, and creates five customer orders with different statuses.

## Requirements

- macOS with Homebrew PHP and MySQL
- PHP extensions: `curl`, `gd`, `intl`, `mbstring`, `mysqli`, and `zip`
- A local MySQL root user without a password, or credentials supplied through
  `FORMIC_DB_ROOT_USER` and `FORMIC_DB_ROOT_PASSWORD`

Start Homebrew MySQL if needed:

```bash
brew services start mysql
```

## Install and seed

```bash
./dev/setup-test-env.sh
```

The setup is idempotent. Re-running it preserves the database, refreshes the
installed theme copy, reapplies Formic settings, and adds any missing fixtures.

## Run

```bash
./dev/start-test-env.sh
```

Open <http://127.0.0.1:18090> and sign in with `admin` / `admin`.

The customer order list is:

```text
http://127.0.0.1:18090/commande/list.php?mainmenu=commercial&leftmenu=orders
```

Force dark mode for a review page with:

```text
&THEME_DARKMODEENABLED=2&dol_resetcache=1&dol_nocache=1
```

## Seed data

The fixture customer is `蚁巢科技（上海）有限公司`. Its orders cover:

- Draft
- Validated
- Shipment in process
- Closed
- Canceled

Runtime files and downloaded Dolibarr sources live under `.test-env/` and are
ignored by Git. After editing the Formic working tree, sync it into the running
test installation with:

```bash
./dev/sync-test-theme.sh
```

Starting the test server also performs this sync automatically.
