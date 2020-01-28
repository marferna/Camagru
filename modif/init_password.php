<?php
require_once '../config/setup.php';
session_start();
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
					document.location.href="http://localhost:8083/camagru";
				}
			</script>
		</header>
		<main>
		<h2 class="modification">Modification du password</h2>
			<div class="form">
				<form action="" method="post">
					Login : <input type="text" name="login" required><br />
					New password : <input type="password" name="new_password" required><br />
					Confirm new password : <input type="password" name="new_password2" required><br />
					<input type="submit" name="submit" value="Reset password">
				</form>
			<div class="message">
			<?php
				// Recupere variable de l'url
				$login = $_GET["log"];
				$pass = $_GET["pass"];

				if (isset($login) && isset($pass))
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
					// On recupere Pass pour verifier qu'il correspond bien a la db
					$req = $bdd->prepare('SELECT cle_pass FROM users WHERE login = :login');
					$req->execute(array('login' => $login));
					$pass_bdd = $req->fetch();

					if ($pass_bdd[0] === $pass)
					{
						if (isset($_POST['submit']) && $_POST['submit'] === "Reset password")
						{
							$login_check = $_POST['login'];
							$new_password = $_POST['new_password'];
							$new_password2 = $_POST['new_password2'];

							if ($login_check === $login)
							{
								if ($new_password === $new_password2)
								{
									$new_passhash = password_hash($new_password2, PASSWORD_DEFAULT);
									// On modifie la db
									$req = $bdd->prepare('UPDATE users SET password = :new_passhash WHERE login = :login');
									$req->execute(array('new_passhash' => $new_passhash, 'login' => $login));
									setcookie('password', $new_passhash, time() + (15 * 60), null, null, false, true);
									echo "Votre mot de passe a bien été modifié !";
								}
								else if ($new_password != $new_password2)
									echo "Attention, la confirmation du mot de passe est erroné !";
								else if ($login_check === NULL || $new_password === NULL || $new_password2 === NULL)
								{
									header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
									exit();
								}

							}
							else
								echo "Attention, le login est incorrecte !";
						}
					}
					else
					{
						header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
						exit();
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
