<?php
    require('bdd.php');
    //ici créer la requete
    $date = $_GET["date"];
    $date = filter_var($date, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $heure = $_GET["heure"];
    $heure = filter_var($heure, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id_salle = $_GET["id_salle"];
    $id_salle = filter_var($id_salle, FILTER_SANITIZE_NUMBER_INT);
    if (isset($id_salle)) {
        $sql1 = "SELECT valeur FROM mesure WHERE id_salle = ".$id_salle." ORDER BY timestamp DESC LIMIT 1;";
        $result1 = query($sql1);
        $valeur1 = 0;
        if (mysqli_num_rows($result1)>0) {
            while ($row1=mysqli_fetch_assoc($result1)) {
                $valeur1 = $row1["valeur"];
            }
        }
        if ($valeur1 == "1") {
            echo "La salle est occupée 1";
            die();
        } else {
            // convert date et heure to mysql timestamp
            $timestamp = date("Y-m-d H:i:s", strtotime($date." ".$heure));
            $sql2 = "SELECT id FROM reservation WHERE id_salle = '".$id_salle."' AND timestamp_start <= ".$timestamp." AND timestemp_end <= ".$timestamp." ORDER BY id DESC LIMIT 1;";
            $result2 = query($sql2);
            $id = 0;
            if (mysqli_num_rows($result2)>0) {
                while ($row2=mysqli_fetch_assoc($result2)) {
                    $id = $row2["id"];
                }
            }
            if ($id == 0) {
                echo "La salle est libre 1";
                //check if timestamp matches a cron pattern
                // */5 * * * *
                $timestamp = date("Y-m-d H:i:s", strtotime($date." ".$heure));
                $cron_pattern = '0 3 * * *'; // replace with your cron pattern

                // create a DateTime object from the timestamp
                $datetime = new DateTime();
                $datetime->setTimestamp($timestamp);

                // create a DatePeriod object for the cron pattern
                $interval = DateInterval::createFromDateString($cron_pattern);
                $start = new DateTime();
                $start->sub($interval);
                $end = new DateTime();
                $period = new DatePeriod($start, $interval, $end);

                // check if the datetime object matches any of the dates in the DatePeriod
                $matches = false;
                foreach ($period as $date) {
                    if ($date->getTimestamp() === $datetime->getTimestamp()) {
                        $matches = true;
                        break;
                    }
                }

                if ($matches) {
                    echo "The timestamp matches the cron pattern.";
                } else {
                    echo "The timestamp does not match the cron pattern.";
                }

                $sql3 = "SELECT id, cron FROM edt WHERE id_salle = ".$id_salle.";";
                $result3 = query($sql3);
                $id_edt = 0;
                $cron_patterns = [];
                if (mysqli_num_rows($result3)>0) {
                    while ($row3=mysqli_fetch_assoc($result3)) {
                        $cron_patterns[] = $row3["cron"];
                    }
                    foreach ($cron_patterns as $cron_pattern) {
                        // create a DateTime object from the timestamp
                        $datetime = new DateTime();
                        $datetime->setTimestamp($timestamp);

                        // create a DatePeriod object for the cron pattern
                        $interval = DateInterval::createFromDateString($cron_pattern);
                        $start = new DateTime();
                        $start->sub($interval);
                        $end = new DateTime();
                        $period = new DatePeriod($start, $interval, $end);

                        // check if the datetime object matches any of the dates in the DatePeriod
                        $matches = false;
                        foreach ($period as $date) {
                            if ($date->getTimestamp() === $datetime->getTimestamp()) {
                                $matches = true;
                                break;
                            }
                        }

                        if ($matches) {
                            echo "The timestamp matches the cron pattern.";
                        } else {
                            echo "The timestamp does not match the cron pattern.";
                        }
                    }
                }
            } else {
                echo "La salle est occupée";
                die();
            }
            //executer la requete et save le resultat
            // retourner le resultat
        }
    }else{
        echo "La salle n'existe pas";
    }
?>