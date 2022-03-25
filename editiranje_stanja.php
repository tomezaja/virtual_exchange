<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Naslovnica</title>
    <meta charset="UTF-8" />
    <link href="tzaja.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <header>
        <?php
        include "meni.php";

        include_once("baza.php");
        $veza = spojiSeNaBazu();
        $id_azuriranja_stanja = $_GET["id"];


        $upit = "SELECT * FROM sredstva WHERE sredstva_id = '{$id_azuriranja_stanja}'";
        $rezultat = izvrsiUpit($veza, $upit);
        $rezultat_ispis = mysqli_fetch_assoc($rezultat);
        $valuta_id = $rezultat_ispis['valuta_id'];

        $upit = "SELECT * FROM valuta WHERE valuta_id = '{$valuta_id}'";
        $rezultat = izvrsiUpit($veza, $upit);
        $rezultat_valuta = mysqli_fetch_assoc($rezultat);
        $valuta_naziv = $rezultat_valuta['naziv'];

        if (isset($_POST["submit"])) {
            $sredstva_id = $id_azuriranja_stanja;
            $iznos = $_POST["novo_stanje"];

            $upit = "UPDATE sredstva SET
             iznos = '{$iznos}'
               WHERE sredstva_id = '{$id_azuriranja_stanja}'";

            izvrsiUpit($veza, $upit);
            header("location: novcanik.php");
        }

        ?>
    </header>
    <h1><?php echo "Ažuriranje valute {$valuta_naziv} korisnika {$_SESSION['ime']} {$_SESSION['prezime']}"; ?></h1>

    <div>
        <form name="unos_stanje" id="unos_stanje" method="POST" action="<?php echo 'editiranje_stanja.php' . "?id=" . $id_azuriranja_stanja; ?>">
            <input type="number" name="novo_stanje" min="0">
            <input type="submit" name="submit" id="submit" value="Ažuriraj" />
        </form>
    </div>

    <?php
    zatvoriVezuNaBazu($veza);
    ?>
</body>

</html>