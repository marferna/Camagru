<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Modifie ton email</title>
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
			<h2 class="modification">Modification de l'email</h2>
			<div class="form">
				<form action="" method="post">
					Old email : <input type="email" name="old_email" required><br />
					New email : <input type="email" name="new_email" required><br />
					<input type="submit" name="submit" value="Change email">
				</form>
				<?php
					// Check si connecter
					if (isset($_SESSION["login"]))
					{
						$login = $_SESSION["login"];
						if (isset($_POST['submit']) && ($_POST['submit'] === "Change email"))
						{
							$old_email = $_POST["old_email"];
							if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['new_email']))
								echo "Attention, l'email n'est pas valide !";
							else
							{
								$new_email_notsafe = $_POST["new_email"];
								$new_email = str_replace(array("\n","\r", PHP_EOL), '', $new_email_notsafe);
								$DB_DSN .= ";dbname=" . $DB_NAME;
								// Co bdd
								try {
									$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
									$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
									$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								} catch (Exception $e) {
									die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
								}
								// Recupere ancien email
								$recup = $bdd->prepare('SELECT email FROM users WHERE login = :login');
								$recup->execute(array('login' => $login));
								$email_bdd = $recup->fetch();

								if ($old_email === $email_bdd[0])
								{
									// Puis check si nouveau email pas deja pris
									$take = $bdd->prepare('SELECT * FROM users WHERE email = :email_pris');
									$take->execute(array('email_pris' => $new_email));
									$resultat_email = $take->fetch();

									if (!$resultat_email)
									{
										// On modifie la db
										$req = $bdd->prepare('UPDATE users SET email = :new_email WHERE login = :login');
										$req->execute(array('new_email' => $new_email, 'login' => $login));
										echo "Votre email a bien été modifié !";
									}
									else
										echo "Attention, cet email est déjà utilisé !";
								}
								else if ($old_email != $email_bdd[0])
									echo "Attention, l'ancien email est erroné !";
								else if ($old_email === NULL || $new_email === NULL)
								{
									header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
									exit();
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
					$bdd = NULL;
				?>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
