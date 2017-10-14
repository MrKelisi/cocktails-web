<?php
require(__DIR__ . '/../includes/functions.inc.php');
require(__DIR__ . '/../includes/user_functions.php');
init();

$login_error = '';
$password_error = '';

if(!isset($_GET['login'])) {
	$login_error = "Login non défini";
}

if(!isset($_GET['password'])) {
	$password_error = "Mot de passe non défini";
}

if($login_error == '' && $password_error == '') {
	try {
		if(verifyPassword($_GET['login'], $_GET['password'])) {
			$_SESSION['login'] = $_GET['login'];
		}
		else {
			$password_error = 'Mauvais mot de passe';
		}
	}
	catch(Exception $e) {
		$login_error = $e->getMessage();
	}
}

echo json_encode([
	'login_error' => $login_error,
	'password_error' => $password_error
]);