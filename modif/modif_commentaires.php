<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Modifie tes préférences</title>
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
				<button class="button" onclick="renvoie()">Retour</button>
			</div>
			<script type="text/javascript">
				function renvoie() {
					document.location.href="http://localhost:8083/camagru/modif/modif.php";
				}
			</script>
		</header>
		<main>
			<h2 class="modification">Modification des préférences</h2>
			<h3 class="sur">Souhaites-tu recevoir un email quand quelqu'un commente une de tes photos ?<h3>
			<form action="" method="post">
				<div class="confirmer">
					<input type="radio" id="oui" name="com" value="oui" required>
					<label for="oui">Oui</label>
					<input type="radio" id="non" name="com" value="non" required>
					<label for="non">Non</label>
					<input type="submit" class="conf" name="submit" value="Confirmation">
				</div>
				<?php
					// Check si connecter
					if (isset($_SESSION["login"]))
					{
						$login = $_SESSION["login"];
						if (isset($_POST['submit']) && ($_POST['submit'] === "Confirmation"))
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
							$req = $bdd->prepare('SELECT `actif_mail` FROM `users` WHERE login = :login');
							$req->execute(array('login' => $login));
							$actif_verif = $req->fetch();
							if ($actif_verif['actif_mail'] == '0')
							{
								if ($_POST['com'] == 'oui')
								{
									?>
										<p class="phrase"><?php echo "Tes préférences n'ont pas été modifié"; ?></p>
									<?php
								}
								else if ($_POST['com'] == 'non')
								{
									$actif_mail_non = $bdd->prepare('UPDATE `users` SET actif_mail = 1 WHERE login like :login');
									$actif_mail_non->execute(array('login' => $login));
									?>
										<p class="phrase"><?php echo "Ta demande a bien été prise en compte, tu ne recevras plus d'email"; ?></p>
									<?php
								}
							}
							else if ($actif_verif['actif_mail'] == '1')
							{
								if ($_POST['com'] == 'oui')
								{
									$actif_mail = $bdd->prepare('UPDATE `users` SET actif_mail = 0 WHERE login like :login');
									$actif_mail->execute(array('login' => $login));
									?>
										<p class="phrase"><?php echo "Tu recevras bien un email si tu as un nouveau commentaire !"; ?></p>
									<?php
								}
								else if ($_POST['com'] == 'non')
								{
									?>
										<p class="phrase"><?php echo "Tes préférences n'ont pas été modifié"; ?></p>
									<?php
								}
							}
						}
					}
					// Si personne pas connecter
					else
					{
						header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
						exit();
					}
				?>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
