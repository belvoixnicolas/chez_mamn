<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
        include_once('head.php');
    ?>
</head>
<body>
    <?php
        include_once('navadmin.php');
    ?>
    <main>
        <form action="#" method="post">
            <fieldset>
                <legend>
                    changer mail
                </legend>

                <input type="email" name="mail" id="mail" placeholder="changer d'email">
                <input type="email" name="verifmail" id="verifmail" placeholder="changer d'email">
            </fieldset>
            <fieldset>
                <legend>
                    changer de mot de passe
                </legend>

                <input type="password" name="pwd" id="pwd" placeholder="changer de mot de passe">
                <input type="password" name="verifpwd" id="verifpwd" placeholder="réécriver votre mot de passe">
            </fieldset>

            <input type="submit" value="Changer">
        </form>
    </main>
</body>
</html>