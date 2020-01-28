<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Modification réussie</title>
		<link rel="stylesheet" type="text/css" href="../css/modif_all.css" />
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
				<button class="button" onclick="renvoie()">Sign Out</button>
			</div>
			<script type="text/javascript">
				function renvoie() {
					document.location.href="http://localhost:8083/camagru/logs/logout.php";
				}
			</script>
		</header>
		<main>
			<h2 class="modification">Modification du login réussie</h2>
			<div class="message">
				<?php
					echo "Pour que le changement de login prenne effet, veuillez vous reconnecter.";
				?>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
