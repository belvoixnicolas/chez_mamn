<?php
    session_start();

    require_once('../model/profil.php');
    require_once('../model/menu.php');

    $profil = new profil;
    
    if (isset($_SESSION['profil']) && is_array($_SESSION['profil']) && $_SESSION['profil']['admin'] == 1 || $_SESSION['profil']['admin'] == 0) {
        $verifProfil = $profil->verifProfil($_SESSION['profil']);

        if ($verifProfil == false) {
            unset($_SESSION['profil']);

            header('Location: ../index.php');
            exit();
        }else {
            $menu = new menu;

            if (isset($_GET['menu']) && $menu->verifMenu((int)$_GET['menu'])) {
                $menuData = $menu->menus((int)$_GET['menu']);
                $produitHtml = $menu->produitsGestionHtml($_GET['menu']);
                $id = $_GET['menu'];
                
                include_once('../view/produit_html.php');
            }else {
                $menuGestion = $menu->menugestionhtml();

                include_once('../view/menugestion_html.php');
            }
        }
    }else {
        unset($_SESSION['profil']);
            
        header('Location: ../index.php');
        exit();
    }
?>