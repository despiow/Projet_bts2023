<?php
require_once '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/vendor/autoload.php';
require_once("bdd.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use ICal\ICal;
$dir    = '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/calendriers/';
$files = scandir($dir, SCANDIR_SORT_ASCENDING);
$files = array_filter($files,static function ($element)
{
	$check_a = $element !== '.';
	$check_b = $element !== '..';
	return $check_a && $check_b;
});
$sql = "SELECT id,nom FROM salle";
$result = query($sql);
$ids_salles = array();
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		$ids_salles[] = $row;
	}
}
conn_end($conn);
function find_id_salle_by_name($name, $array) {
    foreach ($array as $item) {
        if ($item['nom'] == $name)
		{
            return $item['id'];
        }
    }
    return null; // Returns null if the name is not found
}

echo "<pre>".var_dump($ids_salles)."</pre>";
// echo "<pre>".find_id_salle_by_name("110",$ids_salles)."</pre>";
foreach($files as $file)
{
	echo "<h1>".$file."</h1>";
	try
	{
		$ical = new ICal($dir.$file,array(
			// $ical = new ICal($dir.$files[26],array(
			'defaultSpan'                 => 2,     // Default value
			'defaultTimeZone'             => 'UTC',
			'defaultWeekStart'            => 'MO',  // Default value
			'disableCharacterReplacement' => false, // Default value
			'filterDaysAfter'             => null,  // Default value
			'filterDaysBefore'            => null,  // Default value
			'httpUserAgent'               => null,  // Default value
			'skipRecurrence'              => false, // Default value
		));
	} catch (\Exception $e)
	{
		echo $e;
		die();
	}
	foreach ($ical->events() as $event) 
	{
		if(isset($event->summary)){
			$summary_elements = count(explode(" - ",$event->summary));
			$info_cours = array();
			if($summary_elements > 1){
				foreach (explode("\n",$event->description) as $item) 
				{
					if(strpos($item,"Matière") !== false)
					{
						$colonPos = strpos($item, ':');
						$modifiedItem = substr($item, $colonPos + 2);
						$matiere = $modifiedItem;
					}
				}

				$matiere = $matiere;
				$nom_salle = str_replace(" ","_",explode(",",$event->location)[0]);
				$jour = date("Y-m-d",$event->dtstart_array[2]);
				$heure_debut = date("H:i:s",$event->dtstart_array[2]);
				$heure_fin = date("H:i:s",$event->dtend_array[2]);
				$id_salle = find_id_salle_by_name($nom_salle,$ids_salles);
				echo "matière:".$matiere."<br>"."nom_salle:".$nom_salle."<br>"."id_salle:".$id_salle."<br>"."jour:".$jour."<br>"."heure_debut:".$heure_debut."<br>"."heure_fin:".$heure_fin."<br><br>";
			}
		}
	}
  $sql_query = 'INSERT IGNORE INTO `cours`($id_salle,$nom,$jour,$heure_debut,$heure_fin)VALUES ';

  foreach ($files as $ics_filname) 
  {
    // Insérer le nom de la salle et le nom du fichier dans une base de données ou un fichier texte
    $id_salle = str_replace(" ","_",explode(",",$ics_filname)[0]);
    $nom_salle = str_replace("Emploi_du_Temps_","",$ics_filname);
    $nom_salle = str_replace(".ics","",$nom_salle);
    $nom_salle = str_replace(" ","_",$nom_salle);
    $nom_salle=$nom;
    $heure_debut = date("H:i:s",$event->dtstart_array[2]);
    $heure_fin = date("H:i:s",$event->dtend_array[2]);
   
    $sql = "('".$id_salle."', '".$nom."''".$jour."','".$heure_debut."','".$heure_fin."');";
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

}
?>