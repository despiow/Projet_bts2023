<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="description" content="Page de connexion pour accéder à l'application">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="connexion.css" media="screen" type="text/css"> -->
</head>
<body>
    <div id="container">
        <h1>Connexion</h1>
        <form action="verification.php" method="POST">
            <ul>
                <li><strong>Identifiant</strong></li>
                <input type="text" placeholder="Entrer un nom d'utilisateur" name="identifiant" required>
                <br><br>
                <li><strong>Mot de passe</strong></li>
                <input type="password" placeholder="Entrer votre mot de passe" name="mot_de_passe" required>
                <br><br>
                <button type="submit"><strong>Connexion</strong></button>
                <button class="mon-bouton" type="button" onclick="window.location.href='inscription.php'"><strong>Inscription</strong></button>
            </ul>
            <button type="button" onclick="window.history.back()"><strong>Retour</strong></button>
        </form>
    </div>

<?php
//à mettre tout en haut du fichier .php, cette fonction propre à PHP servira à maintenir la $_SESSION
session_start();
//si le bouton "Connexion" est cliqué
if(isset($_POST['connexion'])){
    // on vérifie que le champ "Pseudo" n'est pas vide
    // empty vérifie à la fois si le champ est vide et si le champ existe belle et bien (is set)
    if(empty($_POST['identifiant'])){
        echo "Le champ Pseudo est vide.";
    } else {
        // on vérifie maintenant si le champ "Mot de passe" n'est pas vide"
        if(empty($_POST['mot_de_passe'])){
            echo "Le champ Mot de passe est vide.";
        } else {
            // les champs identifiant & mot_de_passe sont bien postés et pas vides, on sécurise les données entrées par l'utilisateur
            //le htmlentities() passera les guillemets en entités HTML, ce qui empêchera en partie, les injections SQL
            $Pseudo = htmlentities($_POST['identifiant'], ENT_QUOTES, "UTF-8"); 
            $MotDePasse = htmlentities($_POST['mot_de_passe'], ENT_QUOTES, "UTF-8");
            //on se connecte à la base de données:
            $conn = mysqli_connect($serveur,$identifiant,$motdepasse,$bdd);
            //on vérifie que la connexion s'effectue correctement:
            if(!$mysqli){
                echo "Erreur de connexion à la base de données.";
            } else {
                //on fait maintenant la requête dans la base de données pour rechercher si ces données existent et correspondent:
                //si vous avez enregistré le mot de passe en md5() il vous faudra faire la vérification en mettant mdp = '".md5($MotDePasse)."' au lieu de mdp = '".$MotDePasse."'
                $Requete = mysqli_query($mysqli,"SELECT * FROM utilisateur WHERE identifiant = '".$identifiant."' AND mot_de_passe = '".$mot_de_passe."'");
                //si il y a un résultat, mysqli_num_rows() nous donnera alors 1
                //si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
                if(mysqli_num_rows($Requete) == 0) {
                    echo "Le pseudo ou le mot de passe est incorrect, le compte n'a pas été trouvé.";
                } else {
                    //on ouvre la session avec $_SESSION:
                    //la session peut être appelée différemment et son contenu aussi peut être autre chose que le pseudo
                    $_SESSION['identifiant'] = $identifiant;
                    echo "Vous êtes à présent connecté !";
                }
            }
        }
    }
}
?>
</body>
</html>