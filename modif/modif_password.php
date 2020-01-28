<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Modifie ton password</title>
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
			<h2 class="modification">Modification du password</h2>
			<div class="form">
				<form action="" method="post">
					Old password : <input type="password" name="old_password" required><br />
					New password : <input type="password" name="new_password" required><br />
					Confirm new password : <input type="password" name="new_password2" required><br />
					<input type="submit" name="submit" value="Change password">
				</form>
				<?php
					// Check si connecter
					if (isset($_SESSION["login"]))
					{
						$login = $_SESSION["login"];
						if (isset($_POST['submit']) && ($_POST['submit'] === "Change password"))
						{
							$old_password = $_POST["old_password"];
							if (strlen($_POST["new_password"]) > 5)
							{
								$new_password = $_POST["new_password"];
								$new_password2 = $_POST["new_password2"];

								if ($new_password === $new_password2)
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
									// Recupere ancien password dans la db
									$take = $bdd->prepare('SELECT password FROM users WHERE login = :login');
									$take->execute(array('login' => $login));
									$resultat_password = $take->fetch();
									// On verifie si ancien mdp correspond a celui hashe de la db
									$res = password_verify($old_password, $resultat_password[0]);
									if ($res === TRUE)
									{
										$new_passhash = password_hash($new_password2, PASSWORD_DEFAULT);
										// On modifie la db
										$req = $bdd->prepare('UPDATE users SET password = :new_passhash WHERE login = :login');
										$req->execute(array('new_passhash' => $new_passhash, 'login' => $login));
										setcookie('password', $new_passhash, time() + (15 * 60), null, null, false, true);

										echo "Votre mot de passe a bien été modifié !";
									}
									else if ($res === FALSE)
										echo "Attention, l'ancien mot de passe est erroné !";
								}
								else if ($new_password != $new_password2)
									echo "Attention, la confirmation du mot de passe est erroné !";
								else if ($old_password === NULL || $new_password === NULL || $new_password2 === NULL)
								{
									header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
									exit();
								}
							}
							else
								echo "Veuillez entrer un mot de passe d'au moins six caractères";
						}
					}
					// Si personne pas connecter
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
