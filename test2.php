<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/vendor/autoload.php';
$config = array('directory' => '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/calendriers/');

use ICal\ICal;

// Ouvrir le dossier
if ($handle = opendir($config['directory'])) 
{

  // Boucler sur tous les fichiers du dossier
  while (false !== ($entry = readdir($handle))) 
  {

    // Vérifier que le fichier est un fichier ICS
    if (strpos($entry, '.ics') !== false) 
    {
      // Lire le contenu du fichier
      $filename = $config['directory'] . $entry;
     
      $ical = new ICal($filename);
     
      // Afficher le contenu du calendrier
      echo "<h2>".$ical->calendarName()."</h2>\n";
      foreach ($ical->events() as $event) 
      {
        echo "<p>".$event->summary()." ".$event->dtstart()." - ".$event->dtend()."</p>\n";
      }
    }
  }


  // Fermer le dossier
  closedir($handle);
}
?>
