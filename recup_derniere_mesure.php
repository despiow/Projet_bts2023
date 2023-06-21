<?php
    require_once("bdd.php");
    if($_GET['salle_filename'] != null){
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
        if($id_salle != 0){
            $sql = "SELECT valeur FROM mesure WHERE id_salle = ? ORDER BY timestamp DESC LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $id_salle);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $valeur = $row['valeur'];
            $stmt->close();
            echo $valeur;
        }
    }
?>