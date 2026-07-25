<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
	fwrite(STDERR, "This fixture script must be run from the command line.\n");
	exit(1);
}

$htdocs = $argv[1] ?? '';
$adminLogin = $argv[2] ?? 'admin';

if ($htdocs === '' || !is_file($htdocs.'/main.inc.php')) {
	fwrite(STDERR, "Usage: php dev/seed-orders.php /path/to/dolibarr/htdocs [admin-login]\n");
	exit(1);
}

define('NOLOGIN', 1);
define('NOREQUIREMENU', 1);
define('NOREQUIREHTML', 1);
define('NOREQUIREAJAX', 1);
define('NOTOKENRENEWAL', 1);

$_SERVER['SERVER_NAME'] = '127.0.0.1';
$_SERVER['SERVER_PORT'] = '18090';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_NAME'] = '/dev/seed-orders.php';

require $htdocs.'/main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/commande/class/commande.class.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/user.class.php';

/** @var DoliDB $db */
/** @var Conf $conf */
global $db, $conf, $user;

function failFixture(string $message, ?CommonObject $object = null): never
{
	if ($object !== null) {
		$details = trim($object->error.' '.implode(' ', $object->errors));
		if ($details !== '') {
			$message .= ': '.$details;
		}
	}
	fwrite(STDERR, $message."\n");
	exit(1);
}

function setFixtureConstant(DoliDB $db, Conf $conf, string $name, string $value): void
{
	$result = dolibarr_set_const($db, $name, $value, 'chaine', 0, '', $conf->entity);
	if ($result < 0) {
		failFixture("Unable to set Dolibarr constant {$name}");
	}
	$conf->global->{$name} = $value;
}

function findRowId(DoliDB $db, string $table, string $where, string $idColumn = 'rowid'): int
{
	$result = $db->query('SELECT '.$idColumn.' AS fixture_id FROM '.MAIN_DB_PREFIX.$table.' WHERE '.$where.' LIMIT 1');
	if (!$result) {
		failFixture('Database lookup failed: '.$db->lasterror());
	}
	$row = $db->fetch_object($result);
	return $row ? (int) $row->fixture_id : 0;
}

$admin = new User($db);
if ($admin->fetch(0, $adminLogin) <= 0) {
	failFixture("Admin user '{$adminLogin}' was not found");
}
$user = $admin;

$countryId = findRowId($db, 'c_country', "code = 'CN'");
setFixtureConstant($db, $conf, 'MAIN_INFO_SOCIETE_NOM', 'Formic Theme Lab');
setFixtureConstant($db, $conf, 'MAIN_INFO_SOCIETE_COUNTRY', (string) $countryId);
setFixtureConstant($db, $conf, 'MAIN_INFO_SOCIETE_TOWN', 'Shanghai');
setFixtureConstant($db, $conf, 'MAIN_MONNAIE', 'CNY');
setFixtureConstant($db, $conf, 'MAIN_LANG_DEFAULT', 'zh_CN');
setFixtureConstant($db, $conf, 'MAIN_THEME', 'formic');
setFixtureConstant($db, $conf, 'MAIN_FORCETHEME', 'formic');
setFixtureConstant($db, $conf, 'ALLOW_THEME_JS', '1');
setFixtureConstant($db, $conf, 'THEME_DARKMODEENABLED', '0');

foreach (array('modSociete', 'modProduct', 'modCommande') as $moduleName) {
	$result = activateModule($moduleName, 1);
	if (!empty($result['errors'])) {
		failFixture("Unable to activate {$moduleName}: ".implode('; ', $result['errors']));
	}
}

$admin->loadRights('', 1);
$user = $admin;

$thirdPartyId = findRowId($db, 'societe', "ref_ext = 'FORMIC-DEMO-CUSTOMER'");
if ($thirdPartyId === 0) {
	$thirdParty = new Societe($db);
	$thirdParty->name = '蚁巢科技（上海）有限公司';
	$thirdParty->ref_ext = 'FORMIC-DEMO-CUSTOMER';
	$thirdParty->client = 1;
	$thirdParty->code_client = 'auto';
	$thirdParty->status = 1;
	$thirdParty->address = '浦东新区世纪大道 100 号';
	$thirdParty->zip = '200120';
	$thirdParty->town = '上海';
	$thirdParty->country_id = $countryId;
	$thirdParty->country_code = 'CN';
	$thirdParty->email = 'orders@example.test';
	$thirdPartyId = $thirdParty->create($admin);
	if ($thirdPartyId <= 0) {
		failFixture('Unable to create demo customer', $thirdParty);
	}
}

$productId = findRowId($db, 'product', "ref = 'FORMIC-SUPPORT'");
if ($productId === 0) {
	$product = new Product($db);
	$product->ref = 'FORMIC-SUPPORT';
	$product->label = 'Formic 主题实施与支持';
	$product->description = '用于订单列表、金额和状态视觉检查的测试服务。';
	$product->type = Product::TYPE_SERVICE;
	$product->status = 1;
	$product->status_buy = 0;
	$product->price = 1280;
	$product->price_ttc = 1446.40;
	$product->price_base_type = 'HT';
	$product->tva_tx = 13;
	$productId = $product->create($admin);
	if ($productId <= 0) {
		failFixture('Unable to create demo product', $product);
	}
}

$paymentTermId = findRowId($db, 'c_payment_term', "code = '30D' AND active = 1");
$paymentModeId = findRowId($db, 'c_paiement', "code = 'VIR' AND active = 1", 'id');

$fixtures = array(
	array('key' => 'FORMIC-DRAFT', 'label' => '草稿订单', 'status' => Commande::STATUS_DRAFT, 'qty' => 1, 'price' => 1280.00, 'days_ago' => 0),
	array('key' => 'FORMIC-VALIDATED', 'label' => '已确认订单', 'status' => Commande::STATUS_VALIDATED, 'qty' => 2, 'price' => 1680.00, 'days_ago' => 2),
	array('key' => 'FORMIC-PROCESSING', 'label' => '处理中订单', 'status' => Commande::STATUS_SHIPMENTONPROCESS, 'qty' => 3, 'price' => 1980.00, 'days_ago' => 5),
	array('key' => 'FORMIC-CLOSED', 'label' => '已完成订单', 'status' => Commande::STATUS_CLOSED, 'qty' => 5, 'price' => 2280.00, 'days_ago' => 12),
	array('key' => 'FORMIC-CANCELED', 'label' => '已取消订单', 'status' => Commande::STATUS_CANCELED, 'qty' => 1, 'price' => 980.00, 'days_ago' => 20),
);

$created = 0;
foreach ($fixtures as $fixture) {
	$key = $db->escape($fixture['key']);
	if (findRowId($db, 'commande', "ref_client = '{$key}'") > 0) {
		continue;
	}

	$order = new Commande($db);
	$order->socid = $thirdPartyId;
	$order->date = dol_now() - ((int) $fixture['days_ago'] * 86400);
	$order->date_commande = $order->date;
	$order->delivery_date = $order->date + (7 * 86400);
	$order->source = 0;
	$order->ref_client = $fixture['key'];
	$order->note_public = $fixture['label'].'——Formic 主题状态测试数据';
	$order->cond_reglement_id = $paymentTermId;
	$order->mode_reglement_id = $paymentModeId;

	$orderId = $order->create($admin, 1);
	if ($orderId <= 0) {
		failFixture('Unable to create '.$fixture['label'], $order);
	}

	$lineResult = $order->addline(
		'Formic 主题实施与支持',
		(float) $fixture['price'],
		(float) $fixture['qty'],
		13,
		0,
		0,
		$productId
	);
	if ($lineResult <= 0) {
		failFixture('Unable to add an order line to '.$fixture['label'], $order);
	}

	if ($fixture['status'] !== Commande::STATUS_DRAFT) {
		$order->fetch($orderId);
		if ($order->valid($admin, 0, 1) < 0) {
			failFixture('Unable to validate '.$fixture['label'], $order);
		}
	}

	if ($fixture['status'] === Commande::STATUS_SHIPMENTONPROCESS) {
		$result = $db->query(
			'UPDATE '.MAIN_DB_PREFIX.'commande SET fk_statut = '.Commande::STATUS_SHIPMENTONPROCESS.
			' WHERE rowid = '.((int) $orderId)
		);
		if (!$result) {
			failFixture('Unable to mark the processing order: '.$db->lasterror());
		}
	} elseif ($fixture['status'] === Commande::STATUS_CLOSED) {
		if ($order->cloture($admin, 1) < 0) {
			failFixture('Unable to close '.$fixture['label'], $order);
		}
	} elseif ($fixture['status'] === Commande::STATUS_CANCELED) {
		if ($order->cancel($admin) < 0) {
			failFixture('Unable to cancel '.$fixture['label'], $order);
		}
	}

	$created++;
}

echo "Demo customer: {$thirdPartyId}\n";
echo "Demo product:  {$productId}\n";
echo "Orders created: {$created}; total fixtures: ".count($fixtures)."\n";
