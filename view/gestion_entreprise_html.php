<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
        include_once('head.php');
    ?>
</head>
<body id="gestionEntreprise">
    <div id="message" class='hidden'>
        <span class="text"></span>
        <button>
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php
        include_once('navadmin.php');
    ?>
    <main>
    <?php
        include_once('formlogo.php');
    ?>
    <?php
        include_once('formtitre.php');
    ?>
    <?php
        include_once('formphrase.php');
    ?>
    <?php
        include_once('formtel.php');
    ?>
    <?php
        include_once('formadresse.php');
    ?>
    </main>
</body>
</html>