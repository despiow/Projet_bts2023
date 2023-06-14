<?php
    require('bdd.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $date = $_GET["date"];
    $date = filter_var($date, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date = date_create_from_format("d/m/Y", $date);
    $date = $date->format("Y-m-d");
    $salle_filename = $_GET["salle_filename"];
    $salle_filename = filter_var($salle_filename, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id_salle = 0;
    $sql = "SELECT id FROM salle WHERE ics_filename = ?;";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $salle_filename);
    $status = $stmt->execute();
    if($status){
        $stmt->bind_result($id_salle);
        $stmt->fetch();
        $stmt->close();
    }else{
        echo "La salle n'existe pas";
        die();
    }
    if (isset($id_salle)) {
        $cours = [];
        $heures = [8, 9, 10, 11, 12, 13, 14, 15, 16];
        $dispo = [];
        foreach ($heures as $heure) {
            $heure_debut_search = str_pad($heure, 2, "0", STR_PAD_LEFT).":00:00";
            $heure_fin_search = str_pad($heure+1, 2, "0", STR_PAD_LEFT).":00:00";
            $sql1 = "SELECT id FROM cours WHERE id_salle = ? AND jour = ? AND NOT (heure_fin <= ? OR heure_debut >= ?) ORDER BY id DESC LIMIT 1;";
            $stmt1 = $mysqli->prepare($sql1);
            $stmt1->bind_param("isss", $id_salle, $date, $heure_debut_search, $heure_fin_search);
            $status1 = $stmt1->execute();
            $temp = 0;
            if($status1){
                $stmt1->bind_result($temp);
                $stmt1->fetch();
                if($temp >= 1){
                    $dispo[] = 1;
                }else{
                    $dispo[] = 0;
                }
                $stmt1->close();
            }
            else{
                echo "Aucun cours pour cette salle ce jour";
                die();
            }
        }
        echo json_encode($dispo);
    }else{
        echo "La salle n'existe pas";
    }
    
?>