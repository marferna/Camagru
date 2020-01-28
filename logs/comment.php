<?php
require_once '../config/setup.php';
session_start();
$id = $_SESSION['id'];
$login = $_SESSION['login'];
$image = $_POST['img'];
$insert_comment = htmlspecialchars($_POST['insert_comment']);
date_default_timezone_set('Europe/Paris');
$time = date_create()->format('Y-m-d H:i:s');
$insert_com = $bdd->prepare('INSERT INTO `comments`(`user_id`, `post_id`, `message`, `creation_time`) VALUES (:id, :post_id, :message, :creation_time)');
$insert_com->execute(array('id' => $id, 'post_id' => $image, 'message' => $insert_comment, 'creation_time' => $time));
// On recupere le login du post pour envoie d'email
// Table posts
$login_1 = $bdd->prepare('SELECT `user_id` FROM `posts` WHERE id = :post_id_comment');
$login_1->execute(array('post_id_comment' => $image));
$login_2 = $login_1->fetch();
$login_3 = $login_2['user_id'];
// Table users
$login_4 = $bdd->prepare('SELECT `login` FROM `users` WHERE id = :post_user_id');
$login_4->execute(array('post_user_id' => $login_3));
$login_5 = $login_4->fetch();
$login_6 = $login_5['login'];
// Verif que reception mail soit active
$req_actif_mail = $bdd->prepare('SELECT `actif_mail` FROM `users` WHERE login = :login');
$req_actif_mail->execute(array('login' => $login_6));
$actif_mail = $req_actif_mail->fetch();
if ($actif_mail['actif_mail'] == '0')
{
	$email = $bdd->prepare('SELECT `email` FROM `users` WHERE login = :login');
	$email->execute(array('login' => $login_6));
	$email_actif = $email->fetch();
	// Envoie d'un email pour chaque commentaire
	$dest = $email_actif['email'];
	$sujet = "Vous avez reçu un nouveau commentaire";
	$entete = "From : staff-camagru@camagru.fr";
	$message = 'Quelqu\'un vous a écrit sur Camagru,
Connectez-vous pour voir qui a commenté votre photo :

http://localhost:8083/camagru/logs/page.php?page=0

				-----------------------------
Ceci est un email automatique, merci de ne pas y répondre.';
	mail($dest, $sujet, $message, $entete);
}
header('Location: http://localhost:8083/camagru/logs/page.php?page=0');
exit();
?>
