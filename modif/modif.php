<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Modifie ton compte</title>
		<link rel="stylesheet" type="text/css" href="../css/modif_account.css" />
		<link rel="shortcut icon" type="image/png" href="../favicon.ico" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<!-- Corps de la page -->
		<header>
			<div class="left">
				<img class="logo" src="../resources/logo.png" alt="logoinsta" />
				<h1 class="camagru">Camagru</h1>
			</div>
			<div class="right">
				<button id="button1" class="button" onclick="renvoie()">Accueil</button>
			</div>
			<script type="text/javascript">
				function renvoie() {
					document.location.href="http://localhost:8083/camagru/logs/galerie.php";
				}
			</script>
		</header>
		<main>
			<h2 class="titre"><?php
				if (isset($_SESSION["login"]))
					echo "Hey " . htmlspecialchars(ucwords($_SESSION["login"])) . ", tu es sur le point de modifier ton compte Camagru !";
				else
				{
					$_SESSION = array();
					session_destroy();
					header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
					exit();
				}
			?></h2>
			<h3 class="modification">Que souhaites-tu modifier ?</h3>
			<div id="login">
				<button class="button" onclick="login_change()">Login</button>
			</div>
			<div id="password">
				<button class="button" onclick="password_change()">Password</button>
			</div>
			<div id="email">
				<button class="button" onclick="email_change()">Email</button>
			</div>
			<div id="commentaire">
				<button class="button" onclick="desactiv_email()">Comments</button>
			</div>
			<script type="text/javascript">
				// Fonctions pour chacun des boutons
				function login_change() {
					document.location.href="http://localhost:8083/camagru/modif/modif_login.php";
				}
				function password_change() {
					document.location.href="http://localhost:8083/camagru/modif/modif_password.php";
				}
				function email_change() {
					document.location.href="http://localhost:8083/camagru/modif/modif_email.php";
				}
				function desactiv_email() {
					document.location.href="http://localhost:8083/camagru/modif/modif_commentaires.php";
				}
			</script>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
