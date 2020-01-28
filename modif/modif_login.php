<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Modifie ton login</title>
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
			<h2 class="modification">Modification du login</h2>
			<div class="form">
				<form action="" method="post">
					Old login : <input type="text" name="old_login" required><br />
					New login : <input type="text" name="new_login" required><br />
					<input type="submit" name="submit" value="Change login">
				</form>
				<?php
					// Check si connecter
					if (isset($_SESSION["login"]))
					{
						$login = $_SESSION["login"];
						if (isset($_POST['submit']) && ($_POST['submit'] === "Change login"))
						{
							$old_log = htmlspecialchars($_POST["old_login"]);
							$new_log = htmlspecialchars($_POST["new_login"]);
							if ($old_log === $login)
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
								// Check si nouveau login pas deja pris
								$take = $bdd->prepare('SELECT * FROM users WHERE login = :new_login');
								$take->execute(array('new_login' => $new_log));
								$resultat_login = $take->fetch();

								if (!$resultat_login)
								{
									// On modifie la db
									$req = $bdd->prepare('UPDATE users SET login = :new_login WHERE login = :login');
									$req->execute(array('login' => $login, 'new_login' => $new_log));
									setcookie('login', $new_log, time() + (15 * 60), null, null, false, true);

									header('Location: http://localhost:8083/camagru/logs/message_login.php');
									exit();
								}
								else
									echo "Attention, ce login est déjà utilisé !";
							}
							else if ($old_log != $login)
								echo "Attention, l'ancien login est erroné !";
							else if ($old_log === NULL || $login === NULL)
							{
								header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
								exit();
							}
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
