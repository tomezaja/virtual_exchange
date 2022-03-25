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
    <h1>Korisnici</h1>

    <section>
        <table border=1>
            <thead>
                <tr>
                    <td>ID korisnika</td>
                    <td>Tip korisnika</td>
                    <td>Korisničko ime</td>
                    <td>Lozinka</td>
                    <td>Ime</td>
                    <td>Prezime</td>
                    <td>E-mail</td>
                    <td>Slika</td>
                    <td>Ažuriranje</td>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once("baza.php");
                $veza = spojiSeNaBazu();
                $id_novi_korisnik = "";
                if (isset($_POST["submit"])) {
                    $tip_korisnik = $_POST["tip_korisnik"];
                    $korime = $_POST["korime"];
                    $lozinka = $_POST["lozinka"];
                    $ime = $_POST["ime"];
                    $prezime = $_POST["prezime"];
                    $mail = $_POST["mail"];
                    $slika = $_POST["ime"];
                    if (!empty($tip_korisnik) && !empty($korime) && !empty($lozinka) && !empty($ime) && !empty($prezime) && !empty($mail) && !empty($slika)) {
                        $upit = "INSERT INTO korisnik(`tip_korisnika_id`,`korisnicko_ime`,`lozinka`,`ime`,`prezime`,`email`, `slika` ) 
                            VALUES( '$tip_korisnik','$korime','$lozinka','$ime','$prezime','$mail','$slika')";
                        izvrsiUpit($veza, $upit);
                        $id_novi_korisnik = mysqli_insert_id($veza);
                    }
                }

                $upit = "SELECT * FROM `korisnik`";

                $rezultat = izvrsiUpit($veza, $upit);

                while ($korisnik = mysqli_fetch_array($rezultat)) {
                    echo "<tr>";
                    echo "<td>$korisnik[0]</td>";
                    echo "<td>$korisnik[1]</td>";
                    echo "<td>$korisnik[2]</td>";
                    echo "<td>$korisnik[3]</td>";
                    echo "<td>$korisnik[4]</td>";
                    echo "<td>$korisnik[5]</td>";
                    echo "<td>$korisnik[6]</td>";
                    echo "<td><img src = '$korisnik[7]'></td>";
                    echo "<td><a href='editiranje_korisnika.php?id={$korisnik[0]}'>Ažuriraj</a></td>";
                    echo "</tr>";
                }
                echo "<tr>";
                echo    "<td>Unesi novog korisnika</td>";

                echo "</tr>";
                echo "<tr>";
                echo '<form name="unos_korisnik" id="unos_korisnik" method="POST" 
			                action="korisnici.php" >';

                echo "<td>-automatski-</td>";
                echo '<td><select name="tip_korisnik" id="tip_korisnik"  placeholder="Tip korisnika" required/>
                            <option value="0">Administrator</option>
                            <option value="1">Moderator</option>
                            <option value="2">Korisnik</option>
                            </select></td>';
                echo '<td><input name="korime" id="korime" type="text" placeholder="Korisničko ime" required/></td>';
                echo '<td><input name="lozinka" id="lozinka" type="password" placeholder="Lozinka" required/></td>';
                echo '<td><input name="ime" id="ime" type="text" placeholder="Ime" required/></td>';
                echo '<td><input name="prezime" id="prezime" type="text" placeholder="Prezime" required/></td>';
                echo '<td><input name="mail" id="mail" type="text" placeholder="E-mail" required/></td>';
                echo '<td><input name="slika" id="slika" type="text" placeholder="Slika" required/></td>';
                echo '<td><input type="submit" name="submit" id="submit" 
                    value="Dodaj" /></td>';
                echo "</tr>";
                zatvoriVezuNaBazu($veza);
                ?>
            </tbody>
        </table>
    </section>



</body>

</html>