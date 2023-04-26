<html>
    <head>
        <meta charset="utf-8">
        <!-- <link rel="stylesheet" href="inscription.css" media="screen" type="text/css" /> -->
    </head>
    <body>
        <div id="container">
            <form method="POST">
                <h1>
                    <a href="index.html">Inscription</a>
                </h1>
                <ul>
                    <li>
                        <strong>NOM</strong>
                    </li>
                    <input type="text" placeholder="Entrer votre nom" name="nom" required>
                    <br>
                    <br>
                    <li>
                        <strong>Prénom</strong>
                    </li>
                    <input type="text" placeholder="Entrer votre prénom" name="prenom" required>
                    <br>
                    <br>
                    <li>
                        <strong>Identifiant</strong>
                    </li>
                    <input type="text" placeholder="Entrer un nom d'utilisateur" name="identifiant" required>
                    <br>
                    <br>
                    <li>
                        <strong>Mot de passe</strong>
                    </li>
                    <input type="password" placeholder="Entrer votre mot de passe" name="mot_de_passe" required>
                    <br>
                    <br>
                    <li>
                        <strong>Adresse Mail</strong>
                    </li>
                    <input type="email" placeholder="Entrer votre adresse mail scolaire" name="mail" required>
                    <br>
                    <button type="submit">
                        <strong>Inscription</strong>
                    </button>
                </ul>
            </form>
        </div>
        <?php
            require "bdd.php";
            // $check_mdp = password_verify($_POST['mot_de_passe'],$row["mot_de_passe"]);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $cleaned_data = [];
                $cleaned_data['nom'] = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
                $cleaned_data['prenom'] = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
                $cleaned_data['identifiant'] = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);
                $cleaned_data['mot_de_passe'] = filter_var($_POST['mot_de_passe'], FILTER_SANITIZE_STRING);
                $cleaned_data['mail'] = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
                $sql = "INSERT INTO utilisateur (nom, prenom, identifiant,mot_de_passe, mail ) VALUES ('".$cleaned_data['nom']."','".$cleaned_data['prenom']."', '".$cleaned_data['identifiant']."','".password_hash($cleaned_data['mot_de_passe'],PASSWORD_BCRYPT)."', '".$cleaned_data['mail']."')";                
                $result = query($sql);
                if(mysqli_affected_rows($conn)==1){
                    echo "Inscription réussie";
                }else if(mysqli_errno($conn)==1062){
                    echo "Identifiant déjà utilisé";
                }else{
                    echo "Inscription échouée : ".mysqli_errno($conn);
                }
                conn_end($conn);
            }
        ?>
    </body>
</html>