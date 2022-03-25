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
    <h1>Pojedinosti o valutama</h1>
    <section>
        <table border=1>
            <thead>
                <tr>
                    <td>ID valute</td>
                    <td>ID moderatora</td>
                    <td>Naziv</td>
                    <td>Tečaj</td>
                    <td>Slika</td>
                    <td>Zvuk</td>
                    <td>Aktivno od</td>
                    <td>Aktivno do</td>
                    <td>Datum ažuriranja</td>
                    <td>Ažuriraj</td>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once("baza.php");
                $veza = spojiSeNaBazu();
                $id_nova_valuta = "";
                if (isset($_POST["submit"])) {
                    $moderator = $_POST["moderator"];
                    $naziv = $_POST["naziv"];
                    $tecaj = $_POST["tecaj"];
                    $slika = $_POST["slika"];
                    $zvuk = $_POST["zvuk"];
                    $aktivno_od = $_POST["aktivno_od"];
                    $aktivno_do = $_POST["aktivno_do"];
                    $datum_azuriranja = date("Y-m-d H:i:s");

                    if (!empty($naziv) && !empty($tecaj) && !empty($moderator) && !empty($slika)) {
                        $upit = "INSERT INTO valuta(`moderator_id`,`naziv`,`tecaj`,`slika`,`zvuk`,`aktivno_od`,`aktivno_do`, `datum_azuriranja` ) 
                            VALUES( '$moderator','$naziv','$tecaj','$slika','$zvuk','$aktivno_od','$aktivno_do', '$datum_azuriranja')";
                        izvrsiUpit($veza, $upit);
                        $id_nova_valuta = mysqli_insert_id($veza);
                    }
                }

                $upit = "SELECT * FROM `valuta`";

                $rezultat = izvrsiUpit($veza, $upit);
                if( $_SESSION['tip'] == 0){
                while ($val = mysqli_fetch_array($rezultat)) {
                    
                    echo "<tr>";
                    echo "<td>$val[0]</td>";
                    echo "<td>$val[1]</td>";
                    echo "<td>$val[2]</td>";
                    echo "<td>$val[3]</td>";
                    echo "<td style = 'width : 50px;'><img class='zastava' src='{$val[4]}'></td>";
                    echo "<td> <audio controls src='$val[5]'></audio></td>";
                    echo "<td>$val[6]</td>";
                    echo "<td>$val[7]</td>";
                    $datum = date('d.m.Y',strtotime($val[8]));
                    echo "<td>$datum</td>";
                    echo "<td><a href='editiranje_valuta.php?id={$val[0]}'>Ažuriraj</a></td>";
                    echo "</tr>";
                    
                }
                echo "<tr>";
                echo    "<td>Unesi novu valutu</td>";

                echo "</tr>";
                echo "<tr>";
                echo '<form name="unos_valuta" id="unos_valuta" method="POST" 
                                action="tecaj.php" >';

                echo "<td>-automatski-</td>";
                echo '<td><select name="tip_korisnik" id="tip_korisnik">';
                $upit = "SELECT korisnik_id, ime, prezime FROM korisnik WHERE tip_korisnika_id = 1";
                $rezultat = izvrsiUpit($veza, $upit);
                $i = 0;
                while ($moderatori = mysqli_fetch_array($rezultat)) {
                    $m_id[$i] = $moderatori['korisnik_id'];
                    $m_ime[$i] = $moderatori['ime'];
                    $m_prezime[$i] = $moderatori['prezime'];
                    $i++;
                }
                $i = 0;
                while ($i < count($m_id)) {
                    echo "<option value='{$m_id[$i]}'>{$m_ime[$i]} {$m_prezime[$i]}</option>";
                    $i++;
                }
                
                echo '</select></td>';
                echo '<td><input name="naziv" id="naziv" type="text" placeholder="Naziv"required/></td>';
                echo '<td><input name="tecaj" id="tecaj" type="text" placeholder="Tečaj" required/></td>';
                echo '<td><input name="slika" id="slika" type="text" placeholder="Slika"/ required></td>';
                echo '<td><input name="zvuk" id="zvuk" type="text" placeholder="Zvuk"/></td>';
                echo '<td><input name="aktivno_od" id="aktivno_od" type="text" placeholder="Aktivno od"/></td>';
                echo '<td><input name="aktivno_do" id="aktivno_do" type="text" placeholder="Aktivno do"/></td>';
                echo '<td><input name="datum_azuriranja" id="datum_azuriranja" type="text" placeholder="Datum ažuriranja"/></td>';
                echo '<td><input type="submit" name="submit" id="submit" 
                        value="Dodaj" /></td>';
                echo "</tr>";
            }else{
                while ($val = mysqli_fetch_array($rezultat)) {
                    if($val[1] == $_SESSION['id']){
                    echo "<tr>";
                    echo "<td>$val[0]</td>";
                    echo "<td>$val[1]</td>";
                    echo "<td>$val[2]</td>";
                    echo "<td>$val[3]</td>";
                    echo "<td style = 'width : 50px;'><img class='zastava' src='{$val[4]}'></td>";
                    echo "<td><audio controls src='$val[5]'></audio></td>";
                    echo "<td>$val[6]</td>";
                    echo "<td>$val[7]</td>";
                    $datum = date('d.m.Y',strtotime($val[8]));
                    echo "<td>$datum</td>";
                    $danasnji_datum = date('Y-m-d');
                    if($val[8] != $danasnji_datum){
                    echo "<td><a href='editiranje_valuta.php?id={$val[0]}'>Ažuriraj</a></td>";
                    }
                    echo "</tr>";
                    }
                }
            }
                zatvoriVezuNaBazu($veza);
                ?>
            </tbody>
        </table>
    </section>


</body>

</html>