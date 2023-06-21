<?php
    $title = "Inscription";
    require 'commons/header.php';
?>
<div class="h-screen flex items-center justify-center">
    <form method="POST" class="bg-white p-8 rounded-2xl shadow-2xl text-xl">
        <h1 class="text-center text-bold">Inscription</h1>
        <label>Nom</label>
        <div>
            <input type="text" placeholder="Entrez votre nom" name="nom" required class="w-96 border-2 rounded-md border-red-400 p-2">
        </div>
        <label>Prénom</label>
        <div>
            <input type="text" placeholder="Entrez votre prénom" name="prenom" required class="w-96 border-2 rounded-md border-red-400 p-2">
        </div>
        <label>Identifiant</label>
        <div>
            <input type="text" placeholder="Entrez un identifiant" name="identifiant" required class="w-96 border-2 rounded-md border-red-400 p-2">
        </div>
        <label>Mail</label>
        <div>
            <input type="email" placeholder="Entrez l'adresse mail du lycée" name="mail" required class="w-96 border-2 rounded-md border-red-400 p-2">
        </div>
        <label>Mot de passe</label>
        <div>
            <input type="password" placeholder="Entrer votre mot de passe" name="mot_de_passe" required class="w-96 border-2 rounded-md border-red-400 p-2">
        </div>
        <div class="flex w-full justify-around">
            <button type="submit" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4">Inscription</button>
            <button type="button" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4" onclick="window.location.href='connexion.php'"> Connexion</button>
        </div>
        <button type="button" onclick="window.history.back()" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4 w-full">Retour</button>
    </form>
</div>
<?php
    session_start();
    require_once("bdd.php");

    // Récupérer les données du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $identifiant = $_POST["identifiant"];
        $mail = $_POST["mail"];
        $mot_de_passe = $_POST["mot_de_passe"];

        // Requête SQL pour insérer les données dans la base de données
        $sql = "INSERT INTO utilisateur (nom, prenom, identifiant, mail, mot_de_passe) VALUES ('$nom', '$prenom', '$identifiant', '$mail', '$mot_de_passe')";

        if ($conn->query($sql) === TRUE) {
            // fire swal alert popup success
            echo "<script>Swal.fire({
                icon: 'success',
                title: 'Inscription réussie',
                text: 'Vous pouvez désormais vous connecter',
                confirmButtonText: `Ok`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'connexion.php';
                    }
                })</script>";
            
        } else {
            echo "<script>Swal.fire({
                icon: 'error',
                title: 'Inscription échouée',
                text: '".$conn->error."',
                confirmButtonText: `Ok`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'inscription.php';
                    }
                })</script>";
        }
    }

    $conn->close();
?>

<?php require 'commons/footer.php'; ?>
