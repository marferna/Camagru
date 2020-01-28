<?php
require_once '../config/setup.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		 <!-- En-tête de la page -->
		<title>Publications Camagru</title>
		<link rel="stylesheet" type="text/css" href="../css/galerie_style.css" />
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
			<?php
				if (isset($_SESSION["login"]))
				{
					?>
					<div class="right">
						<button class="button" onclick="galerie()">Take Picture</button>
						<button class="button" onclick="modif_account()">Edit Account</button>
						<button class="button" onclick="delete_account()">Delete Account</button>
						<button class="button" onclick="out()">Sign Out</button>
					</div>
					<?php
				}
				else
				{
					$_SESSION = array();
					session_destroy();
					?>
					<div class="right">
						<button class="button" onclick="sign_in()">Sign In</button>
					</div>
					<?php
				}
			?>
			<script type="text/javascript">
				function galerie() {
					document.location.href="http://localhost:8083/camagru/logs/galerie.php";
				}
				function modif_account() {
					document.location.href="http://localhost:8083/camagru/modif/modif.php";
				}
				function delete_account() {
					document.location.href="http://localhost:8083/camagru/logs/delete.php";
				}
				function out() {
					document.location.href="http://localhost:8083/camagru/logs/logout.php";
				}
				function sign_in() {
					document.location.href="http://localhost:8083/camagru/";
				}
			</script>
		</header>
			<?php
				if (isset($_SESSION["login"]))
				{
					?><h2 class="titre"><?php
					echo htmlspecialchars(ucwords($_SESSION["login"])) . ", tu peux retrouver ici toutes les publications des membres !";
					?></h2><?php
				}
				else
				{
					?><h2 class="titre"><?php
					echo "Vous devez être connecté pour commenter et liker les publications !";
					?></h2><?php
				}
			?>
		<main>
			<?php
				$DB_DSN .= ";dbname=" . $DB_NAME;
				// Co bdd
				try {
					$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
					$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (Exception $e) {
					die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
				}
				$page = (!empty($_GET['page']) ? $_GET['page'] : 0);
				$offset = $page * 6;
				// Requete posts
				$req_post = $bdd->prepare('SELECT * FROM `posts` ORDER BY `creation_time` DESC LIMIT :offset, 6');
				$req_post->execute(array('offset' => $offset));
				// Si connecter
				if (isset($_SESSION['login']))
				{
					$id = $_SESSION['id'];
					$login = $_SESSION['login'];
					while ($post = $req_post->fetch())
					{
						$post_id = $post['id'];
						$post_user_id = $post['user_id'];
						$post_img_id = $post['img_id'];
						$post_date = $post['creation_time'];
						?>
							<img class="post" src="/camagru/download/<?= $post_img_id ?>" alt="photos" />
							<div class="in_like">
								<form method="post" action="like.php">
									<input type="hidden" name="img" value="<?php echo $post_id; ?>"/>
									<input class="button_like" type="submit" name="button_like" value="Like" />
								</form>
						<?php
						$image = $post_id;
						// Affiche likes
						$req_like = $bdd->prepare('SELECT * FROM `users_likes` WHERE `post_id` = :post_id');
						$req_like->execute(array('post_id' => $image));
						$nbr_like = $req_like->rowcount();
						if ($nbr_like > 1)
							echo $nbr_like . " Likes";
						else
							echo $nbr_like . " Like";
						?>
								<form method="post" action="dislike.php">
									<input type="hidden" name="img" value="<?php echo $post_id; ?>"/>
									<input class="button_dislike" type="submit" name="button_dislike" value="Dislike" />
								</form>
							</div>
						<?php
						// Affiches commentaires
						$req_com = $bdd->prepare('SELECT `user_id`, `message`, `creation_time` FROM `comments` WHERE `post_id` = :post_id ORDER BY `creation_time` DESC LIMIT 0, 3');
						$req_com->execute(array('post_id' => $post_id));
						while ($comment_affichage = $req_com->fetch())
						{
							$user_id_comment = $comment_affichage['user_id'];
							$message_comment = $comment_affichage['message'];
							$creation_time_comment = $comment_affichage['creation_time'];
							// Recup login
							$recup_login = $bdd->prepare('SELECT `login` FROM `users` WHERE `id` = :user_id_comment');
							$recup_login->execute(array('user_id_comment' => $user_id_comment));
							$login_commentaire = $recup_login->fetch();
							$login_comments = $login_commentaire['login'];
							?>
							<div class="in_comment">
								<div class="auth"><?= $login_comments ?></div>
								<div class="date"><?= $creation_time_comment ?></div><br />
								<div class="msg"><?= $message_comment ?></div>
							</div>
						<?php
						}
						?>
							<form method="post" action="comment.php">
								<input type="hidden" name="img" value="<?php echo $post_id; ?>">
								<textarea name="insert_comment" rows="10" class="insert_comment" placeholder="Splendid..."></textarea>
								<input class='button_com' type="submit" name="button_comment" value="Send Comment" />
							</form>
						<?php
						if ($id === $post_user_id)
						{
							?>
								<form method="post" action="delete_post.php">
									<input type="hidden" name="img" value="<?php echo $post_id; ?>">
									<input type="submit" name="submit" class="but" value="Delete Post" />
								</form>
							<?php
						}
					}
				}
				else
				{
					while ($post = $req_post->fetch())
					{
						$post_id = $post['id'];
						$post_user_id = $post['user_id'];
						$post_img_id = $post['img_id'];
						$post_date = $post['creation_time'];
						?>
							<img class="post" src="/camagru/download/<?= $post_img_id ?>" alt="photos" />
						<?php
						$image = $post_id;
						// Affiche likes
						$req_like = $bdd->prepare('SELECT * FROM `users_likes` WHERE `post_id` = :post_id');
						$req_like->execute(array('post_id' => $image));
						$nbr_like = $req_like->rowcount();
						if ($nbr_like > 1)
						{
							?>
								<div class="like"><?php echo $nbr_like . " Likes"; ?></div>
							<?php
						}
						else
						{
							?>
								<div class="like"><?php echo $nbr_like . " Like"; ?></div>
							<?php
						}
						// Affiche commentaires
						$req_com = $bdd->prepare('SELECT `user_id`, `message`, `creation_time` FROM `comments` WHERE `post_id` = :post_id ORDER BY `creation_time` DESC LIMIT 0, 3');
						$req_com->execute(array('post_id' => $post_id));
						while ($comment_affichage = $req_com->fetch())
						{
							$user_id_comment = $comment_affichage['user_id'];
							$message_comment = $comment_affichage['message'];
							$creation_time_comment = $comment_affichage['creation_time'];
							// Recup login
							$recup_login = $bdd->prepare('SELECT `login` FROM `users` WHERE `id` = :user_id_comment');
							$recup_login->execute(array('user_id_comment' => $user_id_comment));
							$login_commentaire = $recup_login->fetch();
							$login_comments = $login_commentaire['login'];
							?>
							<div class="in_comment">
								<div class="auth"><?= $login_comments ?></div>
								<div class="date"><?= $creation_time_comment ?></div><br />
								<div class="msg"><?= $message_comment ?></div>
							</div>
							<?php
						}
					}
				}
			?>
			<br />
			<div class="page">
				<a class="pagination" href="?page=<?php echo $page - 1; ?>"><- Before</a>
				<a class="pagination" href="?page=<?php echo $page + 1; ?>">After -></a>
			</div>
		</main>
		<footer class="footer">
			<p>© marferna 2019</p>
		</footer>
	</body>
</html>
