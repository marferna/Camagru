<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Supprimer ton compte</title>
		<link rel="stylesheet" type="text/css" href="../css/delete_account.css" />
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
			<h2 class="titre">Tu es sur le point de supprimer ton compte Camagru !</h2>
			<h3 class="sur">Es-tu sûr ? Cela est irréversible<h3>
			<form action="" method="post">
				<div class="confirmer">
					<input type="radio" id="oui" name="sup" value="oui" required>
					<label for="oui">Oui</label>
					<input type="radio" id="non" name="sup" value="non" required>
					<label for="non">Non</label>
					<input type="submit" class="conf" name="submit" value="Confirmation">
				</div>
				<?php
					if (isset($_SESSION["login"]))
					{
						$login = $_SESSION["login"];
						$id = $_SESSION["id"];
						if (isset($_POST['submit']) && ($_POST['submit'] === "Confirmation"))
						{
							if ($_POST['sup'] == 'oui')
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
								// Supprime variable session
								$_SESSION = array();
								session_destroy();
								unset($_SESSION["login"]);
								// Supprime cookies
								setcookie('login', NULL, -1);
								setcookie('password', NULL, -1);
								unset($_COOKIE['login']);
								unset($_COOKIE['password']);

								$check_com = $bdd->prepare('SELECT `id` FROM `posts` WHERE `user_id` = :id');
								$check_com->execute(array('id' => $id));
								$value_com = $check_com->fetch();
								$com = $value_com['id'];
								// Supprime posts
								$delete_posts = $bdd->prepare('DELETE FROM `posts` WHERE `user_id` = :id');
								$delete_posts->execute(array('id' => $id));
								// Supprime commentaires
								$delete_com = $bdd->prepare('DELETE FROM `comments` WHERE `user_id` = :id');
								$delete_com->execute(array('id' => $id));
								// Supprime likes
								$delete_like = $bdd->prepare('DELETE FROM `users_likes` WHERE `user_id` = :id');
								$delete_like->execute(array('id' => $id));
								// Supprime de la db
								$req = $bdd->prepare('DELETE FROM `users` WHERE login = :login');
								$req->execute(array('login' => $login));

								header('Location: http://localhost:8083/camagru/erreur/page_sup_account.php');
								exit();
							}
							else if ($_POST['sup'] == 'non')
							{
								header('Location: http://localhost:8083/camagru/erreur/page_delete_err.php');
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
				?>
			</form>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
<html>
