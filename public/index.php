<?php
/*
 * Front Controller de la gestion du livre d'or
 */

/*
* Chargement des dépendances
*/
// chargement de configuration
require_once "../config.php";
// chargement du modèle de la table livreor
require_once "../model/livreorModel.php";

/*
* Connexion à la base de données en utilisant PDO
* Avec un try catch pour gérer les erreurs de connexion
*/
try {
    $db = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET . ";port=" . DB_PORT, DB_LOGIN, DB_PWD);
} catch (Exception $e) {
    die($e->getMessage());
}
$messageError = "";
/*
* Si le formulaire a été soumis
*/
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['usermail'], $_POST['message'])) {

    
    // on appelle la fonction d'insertion dans la DB (addLivreOr())
    $insert = addLivreOr($db,$_POST['firstname'],$_POST['lastname'],$_POST['usermail'],$_POST['message']);
    
    // si l'insertion a réussi
    if ($insert) {
        // on redirige vers la page actuelle
        header("Location: ./"); 
        exit();
    } else {
        // sinon, on affiche un message d'erreur
        $messageError = "Erreur avec l'insertion";
    }

}

/*
 * On récupère les messages du livre d'or
 */


$messageCount = intval(countMessages($db));
// $messages = getAllLivreOr($db);

if (!empty($_GET[PAGINATION_GET_NAME]) && ctype_digit($_GET[PAGINATION_GET_NAME])) {
    $page = (int) $_GET[PAGINATION_GET_NAME];
} else {
    $page = 1;
}

$showMessages = getPaginationInformations($db, $page, PAGINATION_NB_PAGE);

$pagination = paginationModel("./", PAGINATION_GET_NAME, $messageCount, $page, PAGINATION_NB_PAGE);
// var_dump($messageCount);
// on appelle la fonction de récupération de la DB (getAllLivreOr())

// fermeture de la connexion
$db =null;
// Appel de la vue

include "../view/livreorView.php";


