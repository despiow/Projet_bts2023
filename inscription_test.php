<?php
    $title = "Inscription";
    require 'commons/header.php';
?>
<div class="h-screen flex items-center justify-center">
        <form  method="POST" class="bg-white p-8 rounded-2xl shadow-2xl text-xl">
            <h1 class="text-center text-bold">Inscription</h1>
            <label>Nom</label>
            <div>
                <input type="text" placeholder="Entrez votre nom" name="nom" required class="w-96 border-2 rounded-md border-red-400 p-2">
            </div>
            <label>Prenom</label>
            <div>
                <input type="text" placeholder="Entrez votre prénom" name="prenom" required class="w-96 border-2 rounded-md border-red-400 p-2">
            </div>
            <label>Identifiant</label>
            <div>
                <input type="text" placeholder="Entrez un identifiant" name="identifiant" required class="w-96 border-2 rounded-md border-red-400 p-2">
            </div>          
            <label>Mail</label>
            <div>
                <input type="mail" placeholder="Entrez l'adresse mail du lycée" name="mail" required class="w-96 border-2 rounded-md border-red-400 p-2">
            </div>
            <label>Mot de passe</label>
            <div>
                <input type="password" placeholder="Entrer votre mot de passe" name="mot_de_passe" required class="w-96 border-2 rounded-md border-red-400 p-2">
            </div>
            <div class="flex w-full justify-around">
                <button type="submit" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4">Connexion</button>
                <button type="button" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4" onclick="window.location.href='inscription_test.php'">Inscription</button>
            </div>
            <button type="button" onclick="window.history.back()" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4 w-full">Retour</button>
        </form>
    </div>
    <?php
        session_start(); // Démarrage de la session
        require('bdd.php');
        if ($conn ['REQUEST_METHOD'] == 'POST') 
        {
            // Récupération des données du formulaire
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $identifiant = filter_var($_POST['identifiant'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mot_de_passe = filter_var($_POST['mot_de_passe'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);

            // Requête SQL pour insérer les données dans la base de données
            $sql = "INSERT IGNORE INTO utilisateur (nom, prenom, identifiant, mot_de_passe, mail) VALUES ('".$nom."','".$prenom."', '".$identifiant."','".$mot_de_passe."', '".$mail."')";
            $result = mysqli_query($connexion, $sql);

            if ($result) 
            {
                if (mysqli_affected_rows($connexion) == 1)
                {
                    echo "Inscription réussie !";
                } 
                elseif (mysqli_affected_rows($connexion) == 0) 
                {
                    echo "Déjà inscrit !";
                }
            }
        }
        else 
        {
            echo "Erreur : " . mysqli_error($connexion);
        }
    ?>
<?php require 'commons/footer.php'; ?>