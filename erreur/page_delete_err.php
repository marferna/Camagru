<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Erreur - Suppression</title>
		<link rel="stylesheet" type="text/css" href="../css/valid_deco.css" />
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
			<h2 class="titre">Bienvenue sur Camagru<h2>
			<div class="message">
				<?php
					echo "<p class=\"styleecho\">Ton compte n'a pas été supprimé</p>";
				?>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
