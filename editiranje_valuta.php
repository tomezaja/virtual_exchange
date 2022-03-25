<?php
session_start();
include_once("baza.php");
$veza = spojiSeNaBazu();
$id_nova_valuta = "";
$id_update_valuta = $_GET['id'];

$upit = "SELECT * FROM valuta WHERE valuta_id = " . $id_update_valuta;
$rezultat = izvrsiUpit($veza, $upit);
$rezultat_ispis = mysqli_fetch_assoc($rezultat);

$upit = "SELECT korisnik_id, ime, prezime FROM korisnik WHERE tip_korisnika_id = 1";
$rezultat = izvrsiUpit($veza, $upit);
$i = 0;
while ($moderatori = mysqli_fetch_array($rezultat)) {
    $m_id[$i] = $moderatori['korisnik_id'];
    $m_ime[$i] = $moderatori['ime'];
    $m_prezime[$i] = $moderatori['prezime'];
    $i++;
}


if (isset($_POST["submit"])) {
    $moderator = $_POST["moderator"];
    $naziv = $_POST["naziv"];
    $tecaj = $_POST["tecaj"];
    $slika = $_POST["slika"];
    $zvuk = $_POST["zvuk"];
    $aktivno_od = $_POST["aktivno_od"];
    $aktivno_do = $_POST["aktivno_do"];
    $datum_azuriranja = date("Y-m-d");
    echo "$moderator $naziv" ;

    $upit = "UPDATE valuta SET
    moderator_id = '{$moderator}',
     naziv = '{$naziv}', 
     tecaj = '{$tecaj}', 
     slika = '{$slika}', 
     zvuk = '{$zvuk}', 
     aktivno_od = '{$aktivno_od}', 
     aktivno_do = '{$aktivno_do}', 
     datum_azuriranja = '{$datum_azuriranja}'
      WHERE valuta_id = '{$id_update_valuta}'";
    izvrsiUpit($veza, $upit);

    header("location: tecaj.php");
}
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

    <?php
    echo "<h1>Ažuriranje valute $id_update_valuta</h1>";
    ?>
    <section>

        <form name="unos_valuta" id="unos_valuta" method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?id={$id_update_valuta}"; ?>">
            <label for="moderator">Moderator: </label>
            <select name="moderator" id="moderator">
                <?php
                
                $i = 0;
                while ($i < count($m_id)) {
                    echo "<option value='{$m_id[$i]}'";
                    if($m_id[$i] == $rezultat_ispis['moderator_id'] ){
                            echo "selected = 'selected'";
                    }
                    echo ">{$m_ime[$i]} {$m_prezime[$i]}</option>";
                    $i++;
                }
                ?>
            </select><br>
            <label for="naziv">Naziv: </label>
            <input name="naziv" id="naziv" type="text" value="<?php echo $rezultat_ispis['naziv']; ?>" /><br>
            <label for="tecaj">Tečaj: </label>
            <input name="tecaj" id="tecaj" type="text" value="<?php echo $rezultat_ispis['tecaj']; ?>" /><br>
            <label for="slika">Slika: </label>
            <input name="slika" id="slika" type="text" value="<?php echo $rezultat_ispis['slika']; ?>" /><br>
            <label for="zvuk">Zvuk: </label>
            <input name="zvuk" id="zvuk" type="text" value="<?php echo $rezultat_ispis['zvuk']; ?>" /><br>
            <label for="aktivno_od">Aktivno od: </label>
            <input name="aktivno_od" id="aktivno_od" type="text" value="<?php echo $rezultat_ispis['aktivno_od']; ?>" /><br>
            <label for="aktivno_do">Aktivno do: </label>
            <input name="aktivno_do" id="aktivno_do" type="text" value="<?php echo $rezultat_ispis['aktivno_do']; ?>" /><br>
            <input type="submit" name="submit" id="submit" value="Ažuriraj" />

        </form>

    </section>
</body>

</html>