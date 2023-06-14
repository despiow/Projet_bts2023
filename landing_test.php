<!DOCTYPE html>
<html>
<head>
    <title>Calendrier des salles</title>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 200px;
            background-color: #f1f1f1;
            padding: 20px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h1>Calendrier des salles</h1>
    <table>
        <tr>
            <th>Lundi</th>
            <th>Mardi</th>
            <th>Mercredi</th>
            <th>Jeudi</th>
            <th>Vendredi</th>
        </tr>
        <tr>
            <td><button onclick="showPopup('Lundi')">Lundi</button></td>
            <td><button onclick="showPopup('Mardi')">Mardi</button></td>
            <td><button onclick="showPopup('Mercredi')">Mercredi</button></td>
            <td><button onclick="showPopup('Jeudi')">Jeudi</button></td>
            <td><button onclick="showPopup('Vendredi')">Vendredi</button></td>
        </tr>
    </table>

    <div id="popup" class="popup">
        <h2 id="popup-day"></h2>
        <p id="popup-info"></p>
        <button onclick="hidePopup()">Fermer</button>
    </div>

    <script>
        function showPopup(day) {
            var popup = document.getElementById('popup');
            var popupDay = document.getElementById('popup-day');
            var popupInfo = document.getElementById('popup-info');

            // Récupérer les informations des salles correspondantes au jour cliqué
            // Remplacez le contenu de ces variables par vos propres informations
            var salle1 = "Salle 1";
            var salle2 = "Salle 2";
            var salle3 = "Salle 3";

            // Afficher les informations des salles dans la pop-up
            popupDay.textContent = day;
            popupInfo.innerHTML = "Salles disponibles :<br>" + salle1 + "<br>" + salle2 + "<br>" + salle3;

            // Afficher la pop-up
            popup.style.display = "block";
        }

        function hidePopup() {
            var popup = document.getElementById('popup');

            // Cacher la pop-up
            popup.style.display = "none";
        }
    </script>
</body>
</html>
