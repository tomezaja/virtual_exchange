<?php
session_start();
include_once("baza.php");
$veza = spojiSeNaBazu();
$id_novi_korisnik = "";
$id_update_korisnik = $_GET['id'];

$upit = "SELECT * FROM korisnik WHERE korisnik_id = " . $id_update_korisnik;
$rezultat = izvrsiUpit($veza, $upit);
$rezultat_ispis = mysqli_fetch_assoc($rezultat);

$upit = "SELECT * FROM tip_korisnika";
$tipovi = izvrsiUpit($veza, $upit);
$i = 0;
while ($rezultat_tipovi = mysqli_fetch_array($tipovi)) {
    $tip_naziv[$i] = $rezultat_tipovi['naziv'];
    $tip_id[$i] = $rezultat_tipovi['tip_korisnika_id'];
    $i++;
}

if (isset($_POST["submit"])) {
    $tip_korisnik = $_POST["tip_korisnik"];
    $korime = $_POST["korime"];
    $lozinka = $_POST["lozinka"];
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $mail = $_POST["mail"];
    $slika = $_POST["ime"];
    $upit = "UPDATE `korisnik` SET
     `tip_korisnika_id` = '{$tip_korisnik}', `korisnicko_ime` = '{$korime}', `lozinka` ='{$lozinka}',
      `ime` = '{$ime}', `prezime` ='{$prezime}', `email` = '{$mail}', `slika` = '{$slika}'
       WHERE `korisnik`.`korisnik_id` = '{$id_update_korisnik}'";

    izvrsiUpit($veza, $upit);
    header("location: korisnici.php");
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
    echo "<h1>Ažuriranje korisnika $id_update_korisnik</h1>";
    ?>
    <section>
        <form name="unos_korisnik" id="unos_korisnik" method="POST" action="<?php echo 'editiranje_korisnika.php' . "?id=" . $id_update_korisnik; ?>">

            <label for="tip_korisnik">Tip korisnika: </label>
            <select name="tip_korisnik" id="tip_korisnik">
                <?php
                
                $i = 0;
                while ($i < count($tip_naziv)) {
                    echo "<option value='{$tip_id[$i]}'";
                    if($tip_id[$i] == $rezultat_ispis['tip_korisnika_id'] ){
                            echo "selected = 'selected'";
                    }
                    echo ">{$tip_naziv[$i]}</option>";
                    $i++;
                }
                ?>
                </select><br>
            <label for="korime">Korisničko ime: </label>
            <input name="korime" id="korime" type="text" value="<?php echo $rezultat_ispis['korisnicko_ime']; ?>"required /><br>
            <label for="lozinka">Lozinka: </label>
            <input name="lozinka" id="lozinka" type="text" value="<?php echo $rezultat_ispis['lozinka']; ?>" required/><br>
            <label for="ime">Ime: </label>           
            <input name="ime" id="ime" type="text" value="<?php echo $rezultat_ispis['ime']; ?>" required /><br>
            <label for="prezime">Prezime: </label>
            <input name="prezime" id="prezime" type="text" value="<?php echo $rezultat_ispis['prezime']; ?>" required /><br>
            <label for="mail">E-mail: </label>
            <input name="mail" id="mail" type="text" value="<?php echo $rezultat_ispis['email']; ?>" required/><br>
            <label for="slika">Slika: </label>
            <input name="slika" id="slika" type="text" value="<?php echo $rezultat_ispis['slika']; ?>" required /><br>
            <input type="submit" name="submit" id="submit" value="Ažuriraj" /><br>

        </form>
        

    </section>
</body>

</html>