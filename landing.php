<?php
    $title = "Accueil";
    require 'commons/header.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<div class="flex justify-center items-end">
    <?php    
        if((isset($_SESSION['connected']) && $_SESSION['connected'])){
            echo "<h1 class=\"uppercase text-2xl text-transparent bg-clip-text bg-red-700 text-2xl font-bold text-center pt-0 select-none\">";
            echo "Bienvenue ".$_SESSION['prenom']." ".$_SESSION['nom'];
            echo "</h1>";
        }
    ?> 
</div>
<div class="flex flex-grow items-center mx-auto">
    <div id="rectangle" class="bg-transparent shadow-2xl w-48 h-36 rounded-2xl mr-20 p-4 flex items-center justify-center font-bold opacity-0"></div>
    <div class='w-full max-w-lg p-6 mx-auto bg-white rounded-2xl shadow-xl flex flex-col pt-10'>
        <div class="flex justify-between pb-4">
            <div id="previous_month" class="-rotate-90 cursor-pointer p-2 select-none">
                <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.001 6L6.00098 1L1.00098 6" stroke="black" stroke-opacity="0.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        <span id="month_year" class="uppercase text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-800 via-red-500 to-amber-500"></span>
            <div id="next_month" class="rotate-90 cursor-pointer p-2 select-none">
                <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.001 6L6.00098 1L1.00098 6" stroke="black" stroke-opacity="0.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <button id="bouton_script" class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4" onclick="synchroSalles()">Appuyer pour mettre à jour</button>
        <select class="text-bold border-2 border-red-400 rounded-xl shadow-2xl p-2 mt-4" id="select_salle">
            <option selected value="undefined">Selectionnez une salle</option>
            <?php
                $dir   = '/var/www/html/projet_sn_bts_anthony/Projet_bts2023/calendriers';
                $files = scandir($dir, SCANDIR_SORT_ASCENDING);
                $files = array_filter($files,static function ($element){
                    $check_a = $element !== '.';
                    $check_b = $element !== '..';
                    return $check_a && $check_b;
                });
                foreach($files as $file){
                    $nom_salle = str_replace("Emploi_du_Temps_","",$file);
                    $nom_salle = str_replace(".ics","",$nom_salle);
                    echo "<option value=\"".$file."\">".$nom_salle."</option>";
                }
            ?>
        </select>
    <div id="dayNames" class="flex justify-between font-medium uppercase text-xs pt-4 pb-2 border-t"></div>
    <div id="weeks"></div>
</div>
</div>
<div id="availabilities" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-md md:h-auto">                 
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button id="close_modal" type="button" class="absolute top-3 right-2.5 text-red-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="availabilities">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                <span class="sr-only">Close modal</span>
            </button>
           
            <div class="px-4 py-2.5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                    Sélectionner la/les plages horaires: 
                </h3>
            </div>
            <?php
                if(isset($_SESSION['connected']) && $_SESSION['connected'])
                {
                    echo '<div class="p-6">';
                    echo '<ul class="my-2 space-y-1" id="hours"></ul>';
                    echo '</div>';
                }
            ?>
        </div>
        <div class="login-form">
            <?php 
                if(isset($_GET['login_err']))
                {
                    $err = htmlspecialchars($_GET['login_err']);
                    switch($err)
                    {
                        case 'mot_de_passe':
            ?>
                    <div class="alert alert-danger">
                        <strong>Erreur</strong> mot de passe incorrect
                    </div>
            <?php
                break;
                case 'email':
            ?>
                    <div class="alert alert-danger">
                        <strong>Erreur</strong> email incorrect
                    </div>
            <?php
                break;
                case 'already':
            ?>
                <div class="alert alert-danger">
                    <strong>Erreur</strong> compte non existant
                </div>
            <?php
                break;
                }
            }
            ?> 
        </div>
    </div> 
</div>
<div class="flex justify-center items-end">
    <a href="deconnexion.php" class="bg-red-400 text-red-700 font-bold border-red-900 border-2 text-justify bottom-6 h-9 w12 px-4 rounded-lg">Deconnexion</a>
</div>
<script src="script.js"></script>
<?php require 'commons/footer.php'; ?>