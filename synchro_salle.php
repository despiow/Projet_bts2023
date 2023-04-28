<?php
session_start();
require('bdd.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Récupérer la liste des fichiers dans le répertoire
$dir    = '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/calendriers';
$files = scandir($dir, SCANDIR_SORT_DESCENDING);
$files = array_filter($files,static function ($element)
    {
    	$check_a = $element !== '.';
    	$check_b = $element !== '..';
    	return $check_a && $check_b;
    });

// Boucler sur la liste des fichiers
$sql_query = 'INSERT IGNORE INTO `salle` (`nom`, `ics_filename`) VALUES ';
foreach ($files as $ics_filname) {
  // Insérer le nom de la salle et le nom du fichier dans une base de données ou un fichier texte
  $nom_salle = str_replace("Emploi_du_Temps_","",$ics_filname);
  $nom_salle = str_replace(".ics","",$nom_salle);
  $nom_fichier = $ics_filname;
  $sql = "('".$nom_salle."', '".$nom_fichier."'),";
  $sql_query .= $sql;
}
$sql_query = substr($sql_query,0,-1);
$sql_query .= ';';
$result = query($sql_query);
  if(mysqli_affected_rows($conn)>=1){
      echo "Ajout réussi de ".mysqli_affected_rows($conn)." salle(s)";
  }else if(mysqli_errno($conn)==1062){
      echo "Identifiant déjà utilisé";
  }else{
      echo "Ajout échoué : ".mysqli_errno($conn);
  }
  conn_end($conn);
?>