<?php
require_once '../config/setup.php';

$login = htmlspecialchars($_POST["login"]);
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$actifbdd = '0';
$DB_DSN .= ";dbname=" . $DB_NAME;
// Co bdd
try {
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
	die('Erreur lors de la connexion a la bdd : ' . $e->getMessage());
}
// Récupération du champ actif pour le login
$activation = $bdd->prepare('SELECT `actif` FROM `users` WHERE `login` like :login');
if ($activation->execute(array('login' => $login)) && $row = $activation->fetch())
	$actifbdd = $row["actif"];
if (!empty($_POST["login"]) && !empty($_POST["password"]))
{
	if ($actifbdd == '1')
	{
		// Connexion au compte
		if (!empty($_POST["login"]) && !empty($_POST["password"]))
		{
			// Recupere password dans la db
			$req = $bdd->prepare('SELECT `id`, password FROM `users` WHERE `login` = :login');
			$req->execute(array('login' => $login));
			$pass_co = $req->fetch();
			// Check si login et password corresponde a la db
			$password_ok = password_verify($_POST["password"], $pass_co["password"]);
			if (!$pass_co)
				echo "Login ou Password incorrecte";
			else
			{
				if ($password_ok)
				{
					session_start();
					$_SESSION["id"] = $pass_co["id"];
					$_SESSION["login"] = $login;
					setcookie('login', $login, time() + (15 * 60));
					setcookie('password', $pass_co["password"], time() + (15 * 60));

					header('Location: http://localhost:8083/camagru/logs/galerie.php');
					exit();
				}
				else
				{
				?>
				<!DOCTYPE html>
				<html>
					<body>
					<script type="text/javascript">
						alert('Login ou Password incorrecte');
						document.location.href = 'http://localhost:8083/camagru';
					</script>
				<?php
				}
			}
		}
	}
	else
	{
		if (isset($login) || isset($password))
		{
			?>
			<script type="text/javascript">
				alert('Votre compte n\'a pas été activé ou n\'a pas été trouvé !');
				document.location.href = 'http://localhost:8083/camagru';
			</script>
			<?php
		}
		else
		{
			header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
			exit();
		}
	}
}
else
{
	header('Location: http://localhost:8083/camagru/erreur/page_erreur.php');
	exit();
}
?>
	</body>
</html>
