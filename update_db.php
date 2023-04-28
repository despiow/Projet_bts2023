<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Salle </title>
  </head>
  <body>
    <button id="bouton_script" onclick="executerScript()">Salles</button>

    <script>
      function executerScript() {
        // Créer un objet XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Définir le script PHP à appeler
        var url = "http://172.16.108.120/projet_sn_bts_anthony/synchro_salle.php";

        // Ouvrir une connexion avec le script PHP
        xhr.open("GET", url, true);

        // Gérer la réponse du script PHP
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Afficher la réponse dans la console
            console.log(xhr.responseText);
          } else if (xhr.readyState === XMLHttpRequest.DONE) {
            // Afficher une erreur en cas d'échec de la requête
            console.error("Une erreur est survenue.");
          }
        };

        // Envoyer la demande
        xhr.send();
      }
    </script>
  </body>
</html>
