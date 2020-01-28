<?php
require_once '../config/setup.php';
session_start();
$id = $_SESSION['id'];
$login = $_SESSION['login'];
$image = $_POST['img'];
// Supprime posts
$delete_posts = $bdd->prepare('DELETE FROM `posts` WHERE `user_id` = :id AND `id` = :id_image');
$delete_posts->execute(array('id' => $id, 'id_image' => $image));
header('Location: http://localhost:8083/camagru/logs/page.php');
exit();
?>
