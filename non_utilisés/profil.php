<!DOCTYPE html>
<html>
<head>
	<title>Profil de l'utilisateur</title>
	<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
</head>
<body>
	<?php
		// Vérifie si le nom d'utilisateur a été soumis par le formulaire de connexion
		if(isset($_POST['identifiant'])) {
			$identifiant = $_POST['identifiant'];
			echo "<h2> Bienvenue, " . $identifiant . "!</h2>";
		} else {
			echo "<h2> Erreur: Nom d'utilisateur non spécifié </h2>";
		}
	?>
</body>
</html>
