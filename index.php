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
        ?>
    </header>
    <h1>Valute</h1>
    
    <section>
        <?php
        include_once("baza.php");
        $veza = spojiSeNaBazu();

        $upit = "SELECT * FROM `valuta`";

        $rezultat = izvrsiUpit($veza, $upit);

        while ($drzava = mysqli_fetch_array($rezultat)) {
            echo "<div class = 'galerija' ><img  class='zastava' src = '$drzava[4]' ><br>
            <div style= 'visibility : hidden' class='tecaj_zvuk'>Tečaj = $drzava[3]<br>
            <audio controls src='$drzava[5]'></audio></div>
            </div>";
        }

        zatvoriVezuNaBazu($veza);
        ?>
    </section>
    <script src="tzaja.js" type="text/javascript"></script>
</body>

</html>