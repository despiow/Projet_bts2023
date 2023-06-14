<?php
 require_once("bdd.php");
 $result = mysqli_query($conn, $sql); // Assurez-vous d'avoir une connexion $conn établie
    $sql = "SELECT * FROM cours";
    $result = query($sql);
    $coursTitre = "Cours";
    while ($row = mysqli_fetch_assoc($result)) {
        // Extraire les données de chaque ligne
        $coursId = $row['id'];
        $cour_Id_salle = $row['id_salle'];
        $cours_nom = $row['nom'];
        $cours_jours = $row['jours'];
        $cours_hd = $row['heure_debut'];
        $cours_hf = $row['heure_fin'];
        
        //echo "<div>".$cours_Id."".$cours_nom."".$cours_jours."".$cours_hd."".$cours_hf."</div>"; // Afficher les données de chaque ligne dans une balise div
        echo "<div>";
        echo $cours_Id."<br>";
        echo $cour_Id_salle."<br>";
        echo $cours_nom."<br>";
        echo $cours_jours."<br>";
        echo $cours_hd."<br>";
        echo $cours_hf."<br>";
        echo "</div>";  

    }
?>
