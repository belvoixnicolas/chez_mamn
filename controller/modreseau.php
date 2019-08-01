<?php
    session_start();

    require_once('../model/reseaux.php');

    if (isset($_SESSION['profil']['admin'], $_POST['action']) && $_SESSION['profil']['admin'] == 1) {
        switch ($_POST['action']) {
            case 'addreseau':
                $reseaux = new reseaux;

                if (isset($_POST['titre'], $_POST['url'], $_FILES['image']) && $_POST['titre'] != '' && $_POST['url'] != '' && $_FILES['image']['size'] > 0) {
                    echo json_encode($reseaux->addReseau($_FILES['image'], $_POST['titre'], $_POST['url']));
                }elseif (isset($_POST['titre']) && $_POST['titre'] == '') {
                    echo json_encode(array(
                        'result' => false,
                        'text' => 'Le titre n\'a pas été renseigner'
                    ));
                }elseif (isset($_POST['url']) && $_POST['url'] == '') {
                    echo json_encode(array(
                        'result' => false,
                        'text' => 'L\'url n\'a pas été renseigner'
                    ));
                }elseif (isset($_FILES['image']) && $_FILES['image']['size'] == 0) {
                    echo json_encode(array(
                        'result' => false,
                        'text' => 'Aucun fichier a été envoier'
                    ));
                }else {
                    echo json_encode(array(
                        'result' => false,
                        'text' => 'Une erreur c\'est produit'
                    ));
                }
                break;

            default:
                echo json_encode(array(
                    'result' => false,
                    'text' => 'Une erreur c\'est produit'
                ));
                break;
        }
    }else {
        echo json_encode(array(
            'result' => false,
            'text' => 'Vous n\'éte pas autoriser a faire cette action'
        ));
    }
?>