<?
session_start();
include_once('config.php');
include_once('db_connect.php');

$action = isset($_GET['action']) ? $_GET['action'] : 'home';
?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= SITE_TITLE ?></title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	<div class="container">
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php"><?= SITE_TITLE ?></a>
				</div>
				<ul class="nav navbar-nav">
					<li <? if ($action == 'home'): ?>class="active"<? endif; ?>>
						<a href="index.php?action=home">Home</a>
					</li>

					<? if (!isset($_SESSION['login'])): ?>
						<li <? if ($action == 'login'): ?>class="active"<? endif; ?>>
							<a href="index.php?action=login">Logowanie</a>
						</li>
						<li <? if ($action == 'register'): ?>class="active"<? endif; ?>>
							<a href="index.php?action=register">Rejestracja</a>
						</li>
					<? endif; ?>
				</ul>
				<? if (isset($_SESSION['login'])): ?>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
							   aria-haspopup="true" aria-expanded="false">
								Witaj <?= $_SESSION['login'] ?> <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="index.php?action=logout">Wyloguj</a></li>
							</ul>
						</li>
					</ul>
				<? endif; ?>
			</div>
		</nav>

		<? if (!isset($_SESSION['login'])): ?>
			<div class="alert alert-info">
				<p>Aby korzystać z dysku musisz się zalogować.</p>
			</div>
			<? include('login.php') ?>
		<? else: ?>
			<? include($action . '.php') ?>
		<? endif; ?>

	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	</body>
	</html>

<? $conn->close(); ?>