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
        $id_korisnika = $_SESSION["id"];

        $upit = "SELECT s.iznos, v.naziv, s.valuta_id FROM sredstva s, valuta v 
        WHERE s.valuta_id=v.valuta_id AND korisnik_id='{$id_korisnika}'";
        $rezultat = izvrsiUpit($veza, $upit);
        $i = 0;
        while ($red = mysqli_fetch_array($rezultat)) {
            $iznos[$i] = $red['iznos'];
            $naziv[$i] = $red['naziv'];
            $val_id[$i]= $red['valuta_id'];
            $i++;
        }
        $i = 0;

        $upit = "SELECT * FROM valuta";
        $rezultat_val = izvrsiUpit($veza, $upit);
        while ($rezultat_valuta = mysqli_fetch_array($rezultat_val)) {
            $valuta_naziv[$i] = $rezultat_valuta['naziv'];
            $valuta_id[$i] = $rezultat_valuta['valuta_id'];
            $i++;
        }
        $i = 0;
        $id_novi_zahtjev = "";
        $zahtjev_moguc = '';
        if (isset($_POST["submit"])) {
            $s_iznos = $_POST["iznos"];
            $s_kupi = $_POST["kupnja"];
            $s_prodaj = $_POST["prodaja"];
            $datum_vrijeme = date("Y-m-d H:i:s");
            $prihvacen = "2";
          
            while ($i<count($iznos)) {
                if($s_iznos <= $iznos[$i] && $s_prodaj == $naziv[$i] && $s_prodaj != $s_kupi){
                  $zahtjev_moguc = 'da';
                
                    echo "$zahtjev_moguc<br>$id_korisnika <br>$s_iznos<br>$s_prodaj<br>$s_kupi<br>$datum_vrijeme "; 
                    break;}
             elseif($s_iznos > $iznos[$i]){$greska = "Nedovoljno sredstava!";}
            elseif($s_prodaj == $s_kupi){$greska = "Nedopuštena kombinacija valuta!";}
             $i++;
             
            }
           
            $i=0;
          
        }
            if(isset($greska)){echo "$greska";}

            if($zahtjev_moguc == 'da'){
                $upit = "SELECT valuta_id FROM `valuta` WHERE naziv = '{$s_prodaj}'";
                $rezultat = izvrsiUpit($veza, $upit);
                $valuta_id = mysqli_fetch_array($rezultat);
                $s_prodaj = $valuta_id[0];
                
                $upit = "SELECT valuta_id FROM `valuta` WHERE naziv = '{$s_kupi}'";
                $rezultat = izvrsiUpit($veza, $upit);
                $valuta_id = mysqli_fetch_array($rezultat);
                $s_kupi = $valuta_id[0];

                $upit = "INSERT INTO zahtjev(`korisnik_id`,`iznos`,`prodajem_valuta_id`,`kupujem_valuta_id`,`datum_vrijeme_kreiranja`,`prihvacen` ) 
            VALUES( '$id_korisnika','$s_iznos','$s_prodaj','$s_kupi','$datum_vrijeme','$prihvacen')";
            izvrsiUpit($veza, $upit);
            header("location: zahtjevi.php");


            }
        
        ?>
    </header>
    <h1><?php echo "Slanje zahtjeva korisnika {$_SESSION['ime']} {$_SESSION['prezime']}"; ?></h1>

    <div>
        <form name="unos_stanje" id="unos_stanje" method="POST" action="salji_zahtjev.php">
            <label for="iznos">Iznos za prodaju: </label>
            <input type="number" name="iznos" min="1" ><br>
            <label for="prodaja">Valuta za prodaju: </label>
            <select name="prodaja" id="prodaja">
                <?php

                while ($i < count($naziv)) {
                    echo "<option value='{$naziv[$i]}'>{$naziv[$i]}</option>";
                    $i++;
                }
                ?>
            </select><br>
            <label for="kupnja">Valuta za kupnju: </label>
            <select name="kupnja" id="kupnja">
                <?php
                $i = 0;
                while ($i < count($valuta_naziv)) {
                    echo "<option value='{$valuta_naziv[$i]}'>{$valuta_naziv[$i]}</option>";
                    $i++;
                }
                ?>
            </select><br>
            <input type="submit" name="submit" id="submit" value="Pošalji zahtjev" />
        </form>
    </div>

    <?php
    zatvoriVezuNaBazu($veza);
    ?>
</body>

</html>