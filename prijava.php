<?php
session_start();
if (isset($_GET['odjava'])) {
    session_destroy();
    header("location: prijava.php");
}
include_once("baza.php");
$veza = spojiSeNaBazu();

if (isset($_POST["submit"])) {
    if (isset($_POST["korime"]) && !empty($_POST["korime"]) && isset($_POST["lozinka"]) && !empty($_POST["lozinka"])) {
        $upit = "SELECT * FROM korisnik
                WHERE korisnicko_ime = '{$_POST["korime"]}'
                AND lozinka = '{$_POST["lozinka"]}'";

        $rezultat = izvrsiUpit($veza, $upit);

        while ($red = mysqli_fetch_array($rezultat)) {
            $_SESSION['id'] = $red[0];
            $_SESSION['tip'] = $red[1];
            $_SESSION['ime'] = $red[4];
            $_SESSION['prezime'] = $red[5];
            header("location: index.php");
            exit();
        }
    } else {
        $greska = "Korisnicko ime i lozinka se ne podudaraju!";
    }
} else {
    $greska = "Niste unijeli korisničko ime i lozinku!";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Prijava</title>
    <meta charset="UTF-8" />
    <link href="tzaja.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <header>
        <?php
        include "meni.php";
        ?>
    </header>
    <h1>Prijava</h1>

    <form name="forma" id="forma" method="POST" action="prijava.php">
        <label for="korime">Korisničko ime: </label>
        <input name="korime" id="korime" type="text" />
        <br />
        <label for="lozinka">Lozinka: </label>
        <input name="lozinka" id="lozinka" type="password" />
        <br />
        <input type="submit" name="submit" id="submit" value="Unesi" />
    </form>

    <?php
    if (isset($greska)) {
        echo "$greska";
    }
    zatvoriVezuNaBazu($veza);
    ?>
</body>

</html>