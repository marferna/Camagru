<?php
require_once '../config/setup.php';
session_start();

if (isset($_SESSION["login"]))
{
	// Supprime variable session
	$_SESSION = array();
	session_destroy();
	unset($_SESSION["login"]);

	// Supprime cookies
	setcookie('login', NULL, -1);
	setcookie('password', NULL, -1);
	unset($_COOKIE['login']);
	unset($_COOKIE['password']);

	header('Location: http://localhost:8083/camagru/erreur/page_deco.php');
	exit();
}
else
{
	header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
	exit();
}
?>
