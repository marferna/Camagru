<?php
require_once '../config/setup.php';
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Réinitialisation du mot de passe</title>
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
				<button class="button" onclick="renvoie()">Accueil</button>
			</div>
			<script type="text/javascript">
				function renvoie() {
					document.location.href="http://localhost:8083/camagru/index.php";
				}
			</script>
		</header>
		<main>
			<h2 class="modification">Réinitialisation du mot de passe</h2>
			<div class="form">
				<form action="" method="post">
					Login : <input type="text" name="login" required><br />
					Email : <input type="email" name="email" required><br />
					<input type="submit" name="submit" value="Reset password">
				</form>
				<?php
					if (isset($_POST['submit']) && $_POST['submit'] === "Reset password")
					{
						$login = $_POST['login'];
						$email = $_POST['email'];
						$DB_DSN .= ";dbname=" . $DB_NAME;
						// Co bdd
						try {
							$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
							$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
							$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						} catch (Exception $e) {
							die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
						}
						// On recupere login et email dans la db
						$req = $bdd->prepare('SELECT * FROM `users` WHERE `login` = :login AND `email` = :email');
						$req->execute(array('login' => $login, 'email' => $email));
						$correspondance = $req->fetch();

						if ($correspondance['login'] == $login && $correspondance['email'] == $email)
						{
							echo "Email envoyé à l'adresse : " . $email;
							$cle_password = md5(microtime(TRUE)*100000); // Clé généré aleatoirement
							$cle_password_bdd = $bdd->prepare('UPDATE `users` SET `cle_pass` = :cle_pass WHERE `login` like :login');
							$cle_password_bdd-> execute(array('cle_pass' => $cle_password, 'login' => $login));
							// Mail
							$dest = $email;
							$sujet = "Réinitialisation du mot de passe Camagru";
							$entete = "From : staff-camagru@camagru.fr";
							$message = 'Bonjour,

Pour réinitialiser le mot de passe de votre compte Camagru,
veuillez cliquer sur le lien ci dessous, ou le copier/coller
dans votre navigateur web.

http://localhost:8083/camagru/modif/init_password.php?log='.urlencode($login).'&pass='.urlencode($cle_password).'

				-----------------------------
Ceci est un email automatique, merci de ne pas y répondre.';
							mail($dest, $sujet, $message, $entete);
						}
						else
							echo "Le login ou l'email est incorrecte";
						$bdd = NULL;
					}
					else
						echo "Un email vous sera envoyé pour réinitialiser votre mot de passe";
				?>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
