<?php
    require_once '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/vendor/autoload.php';
    require_once("bdd.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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
    if($_GET['no_salle'] == null || $_GET['temperature'] == null){
        echo "Erreur : paramètres manquants";
        die();
    }else{
        $id_salle = find_id_salle_by_name($_GET['no_salle'], $ids_salles);
        $temperature = $_GET['temperature'];
        $temperature = floatval($temperature);
        $valeur = 1 ? $temperature > 19 : 0;
        $sql = "INSERT IGNORE INTO mesure (id_salle, valeur) VALUES (?, ?);";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $id_salle, $valeur);
        $status = $stmt->execute();
        if ($mysqli->affected_rows == 1) {
            echo "Ajout réussi de la mesure";
        } else if ($mysqli->errno == 1062) {
            echo "Identifiant déjà utilisé";
        } else {
            echo "Ajout échoué : " . $mysqli->error;
        }
    }
?>