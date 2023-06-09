<?php
    require_once '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/vendor/autoload.php';
    require_once("bdd.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    use ICal\ICal;
    $dir = '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/calendriers/';
    $files = scandir($dir, SCANDIR_SORT_ASCENDING);
    $files = array_filter($files, static function ($element) {
        $check_a = $element !== '.';
        $check_b = $element !== '..';
        return $check_a && $check_b;
    });
    $sql = "SELECT id, nom FROM salle";
    $result = query($sql);
    $ids_salles = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ids_salles[] = $row;
        }
    }
    conn_end($conn);

    function find_id_salle_by_name($name, $array)
    {
        foreach ($array as $item) {
            if ($item['nom'] == $name) {
                return $item['id'];
            }
        }
        return null; // Returns null if the name is not found
    }
    foreach ($files as $file) {
        try {
            $ical = new ICal($dir . $file, array(
                'defaultSpan' => 2,     // Default value
                'defaultTimeZone' => 'UTC',
                'defaultWeekStart' => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'filterDaysAfter' => null,  // Default value
                'filterDaysBefore' => null,  // Default value
                'httpUserAgent' => null,  // Default value
                'skipRecurrence' => false, // Default value
            ));
        } catch (\Exception $e) {
            echo $e;
            die();
        }
        echo "events : ".$ical->eventCount;
        $status_failed = 0;
        $status_success = 0;
        foreach ($ical->events() as $event) {
            if (isset($event->summary)) {
                $summary_elements = count(explode(" - ", $event->summary));
                $info_cours = array();
                if ($summary_elements > 1) {
                    $matiere = null;
                    foreach (explode("\n", $event->description) as $item) {
                        if (strpos($item, "Matière") !== false) {
                            $colonPos = strpos($item, ':');
                            $modifiedItem = substr($item, $colonPos + 2);
                            $matiere = $modifiedItem;
                        }
                    }
                    $matiere = $matiere;
                    $nom_salle = str_replace(" ", "_", explode(",", $event->location)[0]);
                    $jour = date("Y-m-d", $event->dtstart_array[2]);
                    $heure_debut = date("H:i:s", $event->dtstart_array[2]);
                    $heure_fin = date("H:i:s", $event->dtend_array[2]);
                    $id_salle = find_id_salle_by_name($nom_salle, $ids_salles);
                    $sql = "INSERT IGNORE INTO cours (id_salle, nom, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?);";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("issss", $id_salle, $matiere, $jour, $heure_debut, $heure_fin);
                    $status = $stmt->execute();
                    if($status){
                        $status_success++;
                    }else{
                        $status_failed++;
                    }
                }
            }
        }
        if ($mysqli->affected_rows >= 1) {
            echo "Ajout réussi de " . $status_success . " cours(s)";
            echo "Ajout échoué de " . $status_failed . " cours(s)";
        } else if ($mysqli->errno == 1062) {
            echo "Identifiant déjà utilisé";
        } else {
            echo "Ajout échoué : " . $mysqli->error;
        }
    }
?>