<?php
    session_start();

    require_once('../model/profil.php');
    require_once('../model/entreprise.php');
    require_once('../model/profil.php');
    require_once('../model/reseaux.php');

    $profil = new profil;
    
    if (isset($_SESSION['profil']) && is_array($_SESSION['profil'])) {
        $verifProfil = $profil->verifProfil($_SESSION['profil']);

        if ($verifProfil == false) {
            unset($_SESSION['profil']);

            header('Location: ../index.php');
            exit();
        }else {
            $session = $_SESSION['profil'];

            if ($session['admin'] == 0) {
                include_once('../view/gestion_perso_html.php');
            }elseif ($session['admin'] == 1) {
                if (isset($_GET['precis']) && $_GET['precis'] == 'perso' && $session['id'] != '1') {
                    include_once('../view/gestion_perso_html.php');
                }else {
                    $entreprise = new entreprise;

                    $logo = $entreprise->logo();
                    $titre = $entreprise->titre();
                    $phrase = $entreprise->phrase();
                    $video = $entreprise->video();
                    $tel = $entreprise->numero();
                    $address = $entreprise->address();

                    $profil = new profil;

                    $profils = $profil->profils((int) $_SESSION['profil']['id'], true);

                    $reseau = new reseaux;

                    $reseaux = $reseau->reseauxGestion();
                    $model = file('../view/reseaumodel.html');
                    
                    include_once('../view/gestion_entreprise_html.php');
                }
            }else {
                unset($_SESSION['profil']);
            
                header('Location: ../index.php');
                exit();
            }
        }
    }else {
        unset($_SESSION['profil']);
            
        header('Location: ../index.php');
        exit();
    }
?>