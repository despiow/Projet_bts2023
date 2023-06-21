<?php
    session_start();
    require_once '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/vendor/autoload.php';
    require_once("bdd.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $sql = "SELECT id, ics_filename FROM salle";
    $result = query($sql);
    $ids_salles = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ids_salles[] = $row;
        }
    }
    conn_end($conn);
    function find_id_salle_by_ics_filename($icsfilename, $array)
    {
        foreach ($array as $item) {
            if ($item['ics_filename'] == $icsfilename) {
                return $item['id'];
            }
        }
        return null; // Returns null if the name is not found
    }
    if($_GET['date'] == null || $_GET['heure'] == null || $_GET['salle_filename'] == null || $_SESSION['id'] == null){
        echo "Erreur : paramètres manquants";
        die();
    }else{
        $id_salle = find_id_salle_by_ics_filename($_GET['salle_filename'], $ids_salles);
        $date = $_GET['date'];
        $heure = $_GET['heure'];
        $salle_filename = $_GET['salle_filename'];
        $user = $_SESSION['id'];
        $date = filter_var($date, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $heure = filter_var($heure, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $salle_filename = filter_var($salle_filename, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user = filter_var($user, FILTER_SANITIZE_NUMBER_INT);
        // $timestamp_start = format to "Y-m-d H:i:s" $date $heure
        //convert date from d/m/Y to Y-m-d
        $date = DateTime::createFromFormat('d/m/Y', $date);
        $date = $date->format('Y-m-d');
        $timestamp_start = $date . " " . $heure . ":00";
        $timestamp_end = $date . " " . (intval($heure)+1) . ":00";
        $sql = "INSERT IGNORE INTO reservation (id_salle, id_utilisateur, timestamp_start, timestamp_end) VALUES (?, ?, ?, ?);";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiss", $id_salle, $user, $timestamp_start, $timestamp_end);
        $status = $stmt->execute();
        if ($mysqli->affected_rows == 1) {
            echo "success";
        } else if ($mysqli->errno == 1062) {
            echo "error";
        } else {
            echo "error2";
        }
    }
?>