<?php
require_once 'config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Bienvenue sur Camagru</title>
		<link rel="stylesheet" type="text/css" href="css/mise-en-page.css" />
		<link rel="shortcut icon" type="image/png" href="favicon.ico" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<!-- Corps de la page -->
		<header>
			<div class="left">
				<img class="logo" src="resources/logo.png" alt="logoinsta" />
				<h1 class="camagru">Camagru</h1>
			</div>
			<div class="right">
				<button id="button1" class="button" onclick="formulaire()">Register</button>
				<button id="button2" class="button" onclick="register()">Sign In</button>
			</div>
			<script type="text/javascript">
				function formulaire() {
					var actuel = document.getElementById("cache");
					var form = document.getElementById("divform");
					var registre = document.getElementById("reg");
					var but = document.getElementById("low");
					if (form.style.display == 'none')
					{
						form.style.display = 'block';
						actuel.style.display = 'none';
						registre.style.display = 'none';
						but.style.display = 'none';
					}
					else
					{
						form.style.display = 'none';
						actuel.style.display = 'block';
						registre.style.display = 'none';
						but.style.display = '';
					}
				}
				function register() {
					var actuel = document.getElementById("cache");
					var registre = document.getElementById("reg");
					var form = document.getElementById("divform");
					var but = document.getElementById("low");
					if (registre.style.display == 'none')
					{
						registre.style.display = 'block';
						actuel.style.display = 'none';
						form.style.display = 'none';
						but.style.display = 'none';
					}
					else
					{
						registre.style.display = 'none';
						actuel.style.display = 'block';
						form.style.display = 'none';
						but.style.display = '';
					}
				}
			</script>
		</header>
		<main>
			<h2 class="titre">Bienvenue sur Camagru<h2>
			<div id="cache" style="display: block">
				<img class="image" src="resources/caroussel/img.jpg" style="display: block;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img1.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img2.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img3.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img4.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img5.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img6.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img7.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img8.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img9.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img10.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img11.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img12.jpg" style="display: none;" alt="photosinsta" />
				<img class="image" src="resources/caroussel/img13.jpg" style="display: none;" alt="photosinsta" />
				<script type="text/javascript">
					var i = 0;
					let img = document.getElementsByClassName("image");
					setTimeout(suivante, 1500);
					function suivante() {
						img[i].style.display = "none";
						if (i < img.length - 1)
							i++;
						else
							i = 0;
						img[i].style.display = "block";
						setTimeout(suivante, 1500);
					}
				</script>
			</div>
			<div class="form" style="font-family: Cursive; font-size: 2vw; text-align: center;">
				<div id="divform" style="display: none;">
					<form action="" method="post">
						Login : <input type="text" name="login" required><br />
						Password : <input type="password" name="password" required><br />
						Confirm Password : <input type="password" name="password2" required><br />
						Email : <input type="email" name="email" required><br />
						<input type="submit" name="submit" value="Create Account">
						<?php
							if (isset($_POST['submit']) && ($_POST['submit'] === "Create Account"))
							{
								if (!isset($_POST["login"]) || $_POST["login"] === NULL)
								echo '<script>alert("Veuillez entrer un login")</script>';
								else if (!isset($_POST["password"]) || $_POST["password"] === NULL || strlen($_POST["password"]) < 6)
								echo '<script>alert("Veuillez entrer un mot de passe d\'au moins six caractères")</script>';
								else if (!isset($_POST["password2"]) || $_POST["password2"] === NULL)
								echo '<script>alert("Veuillez confirmer votre mot de passe")</script>';
								else if (!isset($_POST["email"]) || $_POST["email"] === NULL || !preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['email']))
								echo '<script>alert("Veuillez entrer un e-mail valide")</script>';
								else if ($_POST["password"] != $_POST["password2"])
								echo '<script>alert("Confirmation du mot de passe erroné")</script>';
								else
								{
									$login = htmlspecialchars($_POST["login"]);
									$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
									$email_notsafe = $_POST["email"];
									$email = str_replace(array("\n","\r", PHP_EOL), '', $email_notsafe);
									$DB_DSN .= ";dbname=" . $DB_NAME;
									// Co bdd
									try {
										$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
										$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
										$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									} catch (Exception $e) {
										die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
									}
									// Check si login déjà pris
									$req = $bdd->prepare('SELECT * FROM users WHERE login = :login');
									$req->execute(array('login' => $login));
									$resultat_login = $req->fetch();
									// Check si email déjà pris
									$req_email = $bdd->prepare('SELECT * FROM users WHERE email = :email');
									$req_email->execute(array('email' => $email));
									$resultat_email = $req_email->fetch();
									if (!$resultat_login && !$resultat_email) // Ajoute entrée
									{
										$add = $bdd->prepare('INSERT INTO users(login, password, email) VALUES(:login, :password, :email)');
										$add->execute(array('login' => $login, 'password' => $password, 'email' => $email));
										echo '<script>alert("Création de compte réussie, un email vous a été envoyé !")</script>';
										// Confirmation du compte par envoie d'un email
										$cle = md5(microtime(TRUE)*100000); // Clé généré aleatoirement
										$cle_bdd = $bdd->prepare('UPDATE users SET cle=:cle WHERE login like :login');
										$cle_bdd->execute(array('cle' => $cle, 'login' => $login));
										// Mail
										$dest = $email;
										$sujet = "Activation de votre compte Camagru";
										$entete = "From : staff-camagru@camagru.fr";
										$message = 'Bienvenue sur Camagru,

Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou le copier/coller dans votre navigateur web.

http://localhost:8083/camagru/logs/validation_email.php?log='.urlencode($login).'&cle='.urlencode($cle).'

				-----------------------------
Ceci est un email automatique, merci de ne pas y répondre.';
										mail($dest, $sujet, $message, $entete);
									}
									else if ($resultat_login)
									echo '<script>alert("Login déjà utilisé")</script>';
									else if ($resultat_email)
									echo '<script>alert("Email déjà utilisé")</script>';
									else
									{
										header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
										exit();
									}
								}
							}
							?>
					</form>
				</div>
				<div id="reg" style="display: none;">
					<form action="logs/login.php" method="post">
						Login : <input type="text" name="login" required><br />
						Password : <input type="password" name="password" required><br />
						<input type="submit" name="submit" value="Connection">
					</form>
					<p style="text-align: center; font-family: Calibri, Arial; font-size: 1.5vw;">Forgot password ? <input type="submit" name="submit" value="Click Here" onclick="forgot()"></p>
					<script type="text/javascript">
						function forgot() {
							document.location.href="http://localhost:8083/camagru/logs/forgot.php";
						}
						</script>
				</div>
				<div class="low">
					<button id="low" class="button" style="display: ;" onclick='publicate()'>All publications</button>
				</div>
				<script type="text/javascript">
					function publicate() {
						document.location.href="http://localhost:8083/camagru/logs/page.php?page=0";
					}
				</script>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
