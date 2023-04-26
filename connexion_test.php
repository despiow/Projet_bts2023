<?php
    $title = "Page de connexion";
    require 'commons/header.php';
?>
    <div class="h-screen flex items-center justify-center">
        <form  method="POST" class="bg-white p-8 rounded-2xl shadow-2xl text-xl">
            <h1 class="text-bold text-center">Connexion</h1>
            <label>Mail</label>
            <div>
                <input type="mail" placeholder="adresse mail" name="mail" required class="w-96 border-2 rounded-md border-red-400 p-2">
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
    require('bdd.php'); // On inclut la connexion à la base de données
   
    if (isset($_POST['mail']) && isset($_POST['mot_de_passe'])) 
    { // Si le mail, le mot de passe et qu'il ne sont pas vident
        // On récupère les données du formulaire
        //sanitize inputs
        $mail = filter_var($_POST['mail'],FILTER_SANITIZE_EMAIL);
        $mot_de_passe = filter_var($_POST['mot_de_passe'],FILTER_SANITIZE_STRING);
        $erreur = "" ;

        //requete pour selectionner  l'utilisateur qui a pour email et mot de passe les identifiants qui ont été entrées
        $result = mysqli_query($conn, 'SELECT * FROM utilisateur WHERE mail = "'.$mail.'" AND mot_de_passe = "'.$mot_de_passe.'" LIMIT 1;');

        $num_ligne = mysqli_num_rows($result) ;//Compter le nombre de ligne ayant rapport a la requette SQL
        if ($num_ligne == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['mail'] = $user['mail'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['identifiant'] = $user['identifiant'];
            $_SESSION['connected'] = true;
            header("Location:landing.php") ; //Si le nombre de ligne est > 0 , on sera redirigé vers la page bienvenue
            // Nous allons créer une variable de type session qui vas contenir l'email de l'utilisateur
        } else {//si non
            $erreur = "Adresse Mail ou Mot de passe incorrecte !";
        }
    }
?>
<? require 'commons/footer.php'; ?>