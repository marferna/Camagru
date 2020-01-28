<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Confirmation de l'email</title>
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
					document.location.href="http://localhost:8083/camagru";
				}
			</script>
		</header>
		<main>
			<h2 class="titre">Bienvenue sur Camagru<h2>
			<div class="message">
				<?php
				$login = $_GET["log"];
				$cle = $_GET["cle"];
				$clebdd = '0';
				$actifbdd = '0';

				if (isset($login) && isset($cle))
				{
					$DB_DSN .= ";dbname=" . $DB_NAME;
					// Co bdd
					try {
						$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
						$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					} catch (Exception $e) {
						die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
					}
					$activation = $bdd->prepare('SELECT cle, actif FROM users WHERE login like :login');
					if ($activation->execute(array('login' => $login)) && $row = $activation->fetch())
					{
						$clebdd = $row["cle"];
						$actifbdd = $row["actif"];
					}
					if ($actifbdd == '1')
						echo "<p class=\"styleecho\">Votre compte est déjà activé !</p>";
					else
					{
						if ($cle == $clebdd)
						{
							// On passe actif de 0 a 1
							$activation = $bdd->prepare('UPDATE `users` SET actif = 1 WHERE login like :login');
							$activation->execute(array('login' => $login));
							echo "<p class=\"styleecho\">Votre compte a bien été activé !</p>";
						}
						else
						{
							header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
							exit();
						}
					}
				}
				else
				{
					header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
					exit();
				}
				$bdd = NULL;
				?>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
