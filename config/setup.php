<?php
require 'database.php';

// Connexion mysql
try {
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e) {
	die('Erreur lors de la connexion a mysql : ' . $e->getMessage());
}

// Creation bdd
try {
	$sql = "CREATE DATABASE IF NOT EXISTS " . $DB_NAME . ";";
	$bdd->prepare($sql)->execute();
}
catch (Exception $e) {
	die('Erreur lors de la creation de la bdd : ' . $e->getMessage());
}

// Close bdd
$bdd = null;
$DB_DSN .= ";dbname=" . $DB_NAME;

// Connexion bdd
try {
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e) {
	die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
}

// Creation table users
try {
	$sql = "CREATE TABLE IF NOT EXISTS `users` (
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`login` VARCHAR(100) NOT NULL,
		`password` VARCHAR(100) NOT NULL,
		`email` VARCHAR(150) NOT NULL,
		`cle` VARCHAR(32) NOT NULL,
		`actif` INT NOT NULL,
		`cle_pass` VARCHAR(32) NOT NULL,
		`actif_mail` INT NOT NULL);";
	$bdd->prepare($sql)->execute();
}
catch (Exception $e) {
	die('Erreur lors de la creation de la table users : ' . $e->getMessage());
}

// Creation table posts
try {
	$sql = "CREATE TABLE IF NOT EXISTS `posts` (
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`user_id` INT NOT NULL,
		`img_id` VARCHAR(32) NOT NULL,
		`creation_time` DATETIME NOT NULL);";
		$bdd->prepare($sql)->execute();
}
catch (Exception $e) {
	die('Erreur lors de la creation de la table posts : ' . $e->getMessage());
}

// Creation table comments
try {
	$sql = "CREATE TABLE IF NOT EXISTS `comments` (
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`user_id` INT NOT NULL,
		`post_id` INT NOT NULL,
		`message` VARCHAR(255) NOT NULL,
		`creation_time` DATETIME NOT NULL);";
		$bdd->prepare($sql)->execute();
}
catch (Exception $e) {
	die('Erreur lors de la creation de la table comments : ' . $e->getMessage());
}

// Creation table likes
try {
	$sql = "CREATE TABLE IF NOT EXISTS `users_likes` (
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`user_id` INT NOT NULL,
		`post_id` INT NOT NULL);";
		$bdd->prepare($sql)->execute();
}
catch (Exception $e) {
	die('Erreur lors de la creation de la table likes : ' . $e->getMessage());
}
