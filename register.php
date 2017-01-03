<?php

if (isset($_POST['submit'])) {
	$login = $_POST['login'];

	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	if (empty($login) || empty($password) || empty($password2)) {
		$error = "Proszę uzupełnić wszystkie pola.";
	} elseif ($password != $password2) {
		$error = "Proszę wpisać takie same hasła";
	} else {
		$sql = "SELECT * FROM `user` WHERE `login` = '$login' LIMIT 1";
		$user = $conn->query($sql)->fetch_assoc();
		if ($user) {
			$error = "Taki użytkownik już istnieje.";
		} else {
			$sql = "INSERT INTO `user` (`login`, `password`, `role`) VALUES ('$login', SHA1('$password'), 'USER')";
			if ($conn->query($sql) === TRUE) {
				$success = "Konto zostało utworzone pomyślnie. Możesz się teraz zalogować.";
				mkdir("files/" . $login, 0700);
			} else {
				$error = "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}
}

?>

<header class="page-header">
	<h1>Rejestracja</h1>
</header>

<section class="col-sm-6 col-sm-offset-3">
	<? if ($error): ?>
		<div class="alert alert-danger">
			<p><?= $error ?></p>
		</div>
	<? endif; ?>
	<? if ($success): ?>
		<div class="alert alert-success">
			<p><?= $success ?></p>
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
		<div class="form-group">
			<label for="input_password2">Powtórz hasło</label>
			<input type="password" class="form-control" id="input_password2" name="password2"
				   placeholder="Powtórz hasło...">
		</div>
		<button type="submit" class="btn btn-primary" name="submit">Zarejestruj</button>
	</form>
</section>