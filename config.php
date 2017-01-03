<?

define('DB_NAME', 'm1359_z12');
define('DB_USER', 'm1359_z12');
define('DB_HOST', 'mysql11.mydevil.net');
define('DB_PASS', 'aaa');

define('SITE_TITLE', 'Dysk');
define('MAX_FILE_SIZE', 1000);

function show($text)
{
	echo '<pre>';
	var_dump($text);
	echo '</pre>';
}

function formatDate($date)
{
	return date('H:i:s d.m.Y', strtotime($date));
}

function isAdmin()
{
	return isset($_SESSION['role']) && $_SESSION['role'] == 'ADMIN';
}

function onlyAdmin()
{
	if (!isAdmin()) {
		header('Location: index.php');
		die();
	}
}

function onlyLogged()
{
	if (!isset($_SESSION['login']) || $_SESSION['login'] == '') {

	}
}
