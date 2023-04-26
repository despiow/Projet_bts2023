<?php
 require ('bdd.php');
session_start();
if(isset($_POST['identifiant']) && isset($_POST['mot_de_passe']))
{
 // on applique les deux fonctions mysqli_real_escape_string et htmlspecialchars
 // pour éliminer toute attaque de type injection SQL et XSS
 $identifiant = mysqli_real_escape_string($db,htmlspecialchars($_POST['identifiant'])); 
 $mot_de_passe = mysqli_real_escape_string($db,htmlspecialchars($_POST['mot_de_passe']));
 
 if($identifiant !== "" && $mot_de_passe !== "")
 {
 $requete = "SELECT count(*) FROM utilisateur where identifiant = '".$identifiant."' and mot_de_passe = '".$mot_de_passe."' ";
 $exec_requete = mysqli_query($db,$requete);
 $reponse = mysqli_fetch_array($exec_requete);
 $count = $reponse['count(*)'];
 if($count!=0) // nom d'utilisateur et mot de passe correctes
 {
 $_SESSION["location: identifiant"] = $identifiant;
 header(' index.html');
 }
 else
 {
 header("location: connexion_test.php? erreur=1"); // utilisateur ou mot de passe incorrect
 echo "erreur";
 }
 }
 else
 {
 header("location: connexion_test.php? erreur=2"); // utilisateur ou mot de passe vide
 echo"mot de passe vide";
 }
}
mysqli_close($db); // fermer la connexion
?>