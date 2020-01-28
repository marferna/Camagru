<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Compte personnel Camagru</title>
		<link rel="stylesheet" type="text/css" href="../css/galerie_style.css" />
		<link rel="shortcut icon" type="image/png" href="../favicon.ico" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=yes">
	</head>
	<body>
		<!-- Corps de la page -->
		<header>
			<div class="left">
				<img class="logo" src="../resources/logo.png" alt="logoinsta" />
				<h1 class="camagru">Camagru</h1>
			</div>
			<div class="right">
				<button class="button" onclick="modif_account()">Edit Account</button>
				<button class="button" onclick="delete_account()">Delete Account</button>
				<button class="button" onclick="out()">Sign Out</button>
			</div>
			<script type="text/javascript">
				function modif_account() {
					document.location.href="http://localhost:8083/camagru/modif/modif.php";
				}
				function delete_account() {
					document.location.href="http://localhost:8083/camagru/logs/delete.php";
				}
				function out() {
					document.location.href="http://localhost:8083/camagru/logs/logout.php";
				}
			</script>
		</header>
		<main>
			<h2 class="titre"><?php
				if (isset($_SESSION["login"]))
					echo "Bienvenue " . htmlspecialchars(ucwords($_SESSION["login"])) . ", sur ton compte Camagru !";
				else
				{
					$_SESSION = array();
					session_destroy();
					header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
					exit();
				}
			?></h2>
			<!-- CAMERA PHOTO -->
			<div class="png-filter">
				<img class="img-png" src="../resources/emoji/arc.png" alt="">
				<img class="img-png" src="../resources/emoji/ok.png" alt="">
				<img class="img-png" src="../resources/emoji/couronne.png" alt="">
				<img class="img-png" src="../resources/emoji/mdr.png" alt="">
				<img class="img-png" src="../resources/emoji/poop.png" alt="">
				<img class="img-png" src="../resources/emoji/vomis.png" alt="">
			</div>
			<div id="video-canvas">
				<video class="hide" id="camera" autoplay="true" ></video>
				<canvas class="" id="canvas"></canvas>
			</div>
			<div id="allButtons" class="hide">
				<button id="Take_Pic" class="button2">Take Picture</button>
				<button id="saveBtn" class="button2">Save Picture</button>
				<form method="post">
					<button id="publish" class="button2 hide" type="submit">Publish</button>
					<textarea class="hide" name="base64" id="base64"></textarea>
				</form>
			</div>
			<div class="low">
				<button class="button3" onclick='publicate()'>All publications</button>
			</div>
			<?php
				// Check si connecter
				if (isset($_SESSION["login"]))
				{
					$login = $_SESSION["login"];
					$id = $_SESSION["id"];
					if (isset($_POST['base64']) AND $_POST['base64'])
					{
						// Protection + nom unique de l'image
						$img = htmlspecialchars($_POST['base64']);
						$img = str_replace('data:image/png;base64,', '', $img);
						$img = str_replace(' ', '+', $img);
						$img = basename($img);
						$unique = $id . '_img_' . uniqid() . '.png';
						$img = $unique;
						// Envoie de l'image dans download
						$base = htmlspecialchars($_POST['base64']);
						$base_to_php = explode(',', $base);
						$data = base64_decode($base_to_php[1]);
						$chemin = "../download/" . $img;
						file_put_contents($chemin, $data);
						$DB_DSN .= ";dbname=" . $DB_NAME;
						// Co bdd
						try {
							$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
							$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
							$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						} catch (Exception $e) {
							die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
						}
						date_default_timezone_set('Europe/Paris');
						$time = date_create()->format('Y-m-d H:i:s');
						// Enregistre nom de l'image fini dans db
						$add = $bdd->prepare('INSERT INTO posts(user_id, img_id, creation_time) VALUES (:user_id, :img_id, :creation_time)');
						$add->execute(array('user_id' => $id, 'img_id' => $img, 'creation_time' => $time));
						$_POST['base64'] = "";
						$data = "";
						$img = "";
						header('Location: http://localhost:8083/camagru/logs/page.php');
						exit();
					}
				}
				// Si personne pas connecter
				else
				{
					header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
					exit();
				}
			?>
			<div id="noCamUpload" class="upload" style="display: none;">
			<p class="pan" >Pas de webcam ? Upload une photo : </p>
				<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="100000">
					<input class="upl" type="file" name="photo" id="imgInp">
				</form>
			</div>
			<script type="text/javascript">
				function publicate() {
					document.location.href="http://localhost:8083/camagru/logs/page.php?page=0";
				}
			</script>
			<script src="../js/function_webcam.js"></script>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
</html>
