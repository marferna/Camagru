<?php
require_once '../config/setup.php';
session_start();
$id = $_SESSION['id'];
$login = $_SESSION['login'];
$image = $_POST['img'];
// Verifie si login a deja liker
$login_already_like = $bdd->prepare('SELECT * FROM `users_likes` WHERE `user_id` = :id AND `post_id` = :post_id');
$login_already_like->execute(array('id' => $id, 'post_id' => $image));
$resultat_login = $login_already_like->fetch();
// Si y est on enleve le like
if ($resultat_login)
{
	$del_like = $bdd->prepare('DELETE FROM `users_likes` WHERE `user_id` = :id AND `post_id` = :post_id');
	$del_like->execute(array('id' => $id, 'post_id' => $image));
}
header('Location: http://localhost:8083/camagru/logs/page.php?page=0');
exit();
?>
