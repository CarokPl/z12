<?php

function getUserIp()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

if (isset($_POST['submit'])) {
	$login = $_POST['login'];
	$password = $_POST['password'];
	if (empty($login) || empty($password)) {
		$error = "Proszę uzupełnić wszystkie pola.";
	} else {
		$sql = "SELECT * FROM `user` WHERE `login` = '$login' LIMIT 1";
		$user = $conn->query($sql)->fetch_assoc();
		if ($user) {
			if ($user['fail_count'] >= 3) {
				$error = "Twoje konto zostało zablokowane z powodu zbyt dużej ilości błędnych logowań.";
			} elseif ($user['password'] != sha1($password)) {
				$conn->query("UPDATE `user` SET `fail_date` = NOW(), `fail_count` = `fail_count` + 1  WHERE `id` = '$user[id]'");
				$error = "Błędny login lub hasło";
			} else {
				$conn->query("UPDATE `user` SET `success_date` = NOW(), `fail_count` = 0 WHERE `id` = '$user[id]'");
				$_SESSION['login'] = $user['login'];
				$_SESSION['role'] = $user['role'];
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['fail_date'] = $user['fail_count'] > 0 ? $user['fail_date'] : null;
				header('Location: index.php');
			}
		} else {
			$error = "Błędny login lub hasło";
		}

	}
}

?>

<header class="page-header">
	<h1>Logowanie</h1>
</header>

<section class="col-sm-6 col-sm-offset-3">
	<? if (isset($error)): ?>
		<div class="alert alert-danger">
			<?= $error ?>
		</div>
	<? endif; ?>
	<form method="post">
		<div class="form-group">
			<label for="input_login">Login</label>
			<input type="text" class="form-control" id="input_login" name="login" placeholder="Login...">
		</div>
		<div class="form-group">
			<label for="input_password">Hasło</label>
			<input type="password" class="form-control" id="input_password" name="password" placeholder="Hasło...">
		</div>
		<button type="submit" class="btn btn-primary" name="submit">Zaloguj</button>
	</form>
</section>