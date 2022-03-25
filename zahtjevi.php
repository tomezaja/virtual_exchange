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
        ?>
    </header>
    <h1>Pregled zahtjeva</h1>
    <?php
    if ($_SESSION['tip'] == 2) { ?>
        <section>
            <table border=1>
                <thead>
                    <tr>
                        <td>ID zahtjeva</td>
                        <td>Iznos</td>
                        <td>Prodajna valuta</td>
                        <td>Kupovna valuta</td>
                        <td>Datum i vrijeme kreiranja</td>
                        <td>Status</td>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $veza = spojiSeNaBazu();

                    $upit = "SELECT * FROM `zahtjev`";

                    $rezultat = izvrsiUpit($veza, $upit);

                    while ($zah = mysqli_fetch_array($rezultat)) {
                        if ($zah[1] == $_SESSION['id']) {
                            echo "<tr>";
                            echo "<td>$zah[0]</td>";
                            echo "<td>$zah[2]</td>";
                            echo "<td>$zah[3]</td>";
                            echo "<td>$zah[4]</td>";
                            $datum = date('d.m.Y H:i:s',strtotime($zah[5]));
                            echo "<td>$datum</td>";
                            echo "</tr>";
                        }
                    }

                    ?>
                </tbody>
            </table>
        </section>
    <?php
    } elseif ($_SESSION['tip'] == 1) {

    ?>
        <section>
            <table border=1>
                <thead>
                    <tr>
                        <td>ID zahtjeva</td>
                        <td>ID korisnika</td>
                        <td>Iznos</td>
                        <td>Prodajna valuta</td>
                        <td>Kupovna valuta</td>
                        <td>Datum i vrijeme kreiranja</td>
                       </tr>
                </thead>
                <tbody>
                    <?php
                    include_once("baza.php");
                    $veza = spojiSeNaBazu();

                    $upit = "SELECT * FROM `zahtjev`";

                    $rezultat = izvrsiUpit($veza, $upit);
                    echo "<h3>Moji zahtjevi</h3>";
                    while ($zah = mysqli_fetch_array($rezultat)) {
                        if ($zah[1] == $_SESSION['id']) {

                            echo "<tr>";
                            echo "<td>$zah[0]</td>";
                            echo "<td>$zah[1]</td>";
                            echo "<td>$zah[2]</td>";
                            echo "<td>$zah[3]</td>";
                            echo "<td>$zah[4]</td>";
                            $datum = date('d.m.Y H:i:s',strtotime($zah[5]));
                            echo "<td>$datum</td>";
                            echo "</tr>";
                        }
                    }
                    zatvoriVezuNaBazu($veza);

                    ?>
                </tbody>
            </table>
        </section>
        <section>
            <table border=1>
                <thead>
                    <h3>Zahtjevi u mojoj valuti</h3>
                    <tr>
                        <td>ID zahtjeva</td>
                        <td>ID korisnika</td>
                        <td>Iznos</td>
                        <td>Prodajna valuta</td>
                        <td>Kupovna valuta</td>
                        <td>Datum i vrijeme kreiranja</td>
                        <td>Aktivno od</td>
                        <td>Aktivno do</td>
                        <td>Mogućnosti</td>
                    </tr>
                </thead>
                <tbody>
                <?php
                include_once("baza.php");
                $veza = spojiSeNaBazu();

                $upit = "SELECT z.*, v.naziv, v.tecaj, v.aktivno_od, v.aktivno_do FROM zahtjev z, valuta v 
                WHERE z.prodajem_valuta_id = v.valuta_id AND moderator_id = {$_SESSION['id']}";

                $rezultat = izvrsiUpit($veza, $upit);

                while ($zah = mysqli_fetch_array($rezultat)) {
                    if ($zah[1] != $_SESSION['id']) {
                        
                        echo "<tr>";
                        echo "<td>$zah[0]</td>";
                        echo "<td>$zah[1]</td>";
                        echo "<td>$zah[2]</td>";
                        echo "<td>$zah[3]</td>";
                        echo "<td>$zah[4]</td>";
                        $datum = date('d.m.Y H:i:s',strtotime($zah[5]));
                            echo "<td>$datum</td>";
                        echo "<td>$zah[9]</td>";
                        echo "<td>$zah[10]</td>";
                        if($zah[6]=='0'){
                            echo "<td>Prihvaćen!</td>";
                        }
                        if($zah[6]=='1'){
                            echo "<td>Odbijen!</td>";
                        }
                        if($zah[6]=='2'){
                        echo "<td><form action='zahtjevi.php' method='POST'>
                                <input type='hidden' name='zahtjev_id' value='$zah[0]'>
                                <input type='hidden' name='status' value=1>
                                <button type='submit' value='1'>Prihvati</button></form>
                                <form action='zahtjevi.php' method='post'>
                                <input type='hidden' name='zahtjev_id' value='$zah[0]'>
                                <input type='hidden' name='status' value=0>
                                <button type='submit' value='0'>Odbij</button></td></form>";
                        }
                        echo "</tr>";
                    }
                }
                
                if(isset($_POST['status'])){
                   
                $upit = "UPDATE zahtjev SET prihvacen = {$_POST['status']} WHERE zahtjev.zahtjev_id = {$_POST['zahtjev_id']}";
                izvrsiUpit($veza, $upit);
                if($_POST['status'] == 1){
                    $upit = "SELECT iznos, prodajem_valuta_id, kupujem_valuta_id, korisnik_id FROM `zahtjev` WHERE zahtjev_id = {$_POST['zahtjev_id']} ";
                    $zahtjev = izvrsiUpit($veza, $upit);
                    $iz_zahtjeva = mysqli_fetch_array($zahtjev);
                    $iznos = $iz_zahtjeva[0];
                    $prodajna = $iz_zahtjeva[1];
                    $kupovna = $iz_zahtjeva[2];
                    $korisnik = $iz_zahtjeva[3];
                    
                    $upit = "SELECT tecaj FROM `valuta` WHERE valuta_id = {$prodajna} ";
                    $rezultat = izvrsiUpit($veza, $upit);
                    $prodajni_tecaj = mysqli_fetch_array($rezultat);
                    
                    $upit = "SELECT tecaj FROM `valuta` WHERE valuta_id = {$kupovna} ";
                    $rezultat = izvrsiUpit($veza, $upit);
                    $kupovni_tecaj = mysqli_fetch_array($rezultat);
                    $kupljena_valuta = ($iznos * (float)$prodajni_tecaj[0]) / (float)$kupovni_tecaj[0];
                    
                    $upit = "SELECT iznos FROM `sredstva` WHERE korisnik_id = $korisnik AND valuta_id = $prodajna";
                    $rezultat = izvrsiUpit($veza, $upit);
                    $prodano = mysqli_fetch_array($rezultat);
                    (float)$prodano[0] -= (float)$iznos;
                    
                    $upit = "SELECT iznos FROM `sredstva` WHERE korisnik_id = $korisnik AND valuta_id = $kupovna";
                    $rezultat = izvrsiUpit($veza, $upit);
                    $kupljeno = mysqli_fetch_array($rezultat);
                    (float)$kupljeno[0] += (float)$kupljena_valuta;
                    
                    $upit = "UPDATE sredstva SET iznos = {$prodano[0]} WHERE korisnik_id = $korisnik AND valuta_id = $prodajna";
                    izvrsiUpit($veza, $upit);
                    $upit = "UPDATE sredstva SET iznos = {$kupljeno[0]} WHERE korisnik_id = $korisnik AND valuta_id = $kupovna";
                    izvrsiUpit($veza, $upit);
                }
               
                }
                zatvoriVezuNaBazu($veza);
            }
                ?>
                </tbody>
            </table>
        </section>
        <?php
            if($_SESSION['tip'] == 1){
        ?>
        <section>
            <table border=1>
                <thead>
                    <h3>Ostali zahtjevi</h3>
                    <tr>
                        <td>ID zahtjeva</td>
                        <td>ID korisnika</td>
                        <td>Iznos</td>
                        <td>Prodajna valuta</td>
                        <td>Kupovna valuta</td>
                        <td>Datum i vrijeme kreiranja</td>
                        <td>Aktivno od</td>
                        <td>Aktivno do</td>
                        
                    </tr>
                </thead>
                <tbody>
                <?php
                include_once("baza.php");
                $veza = spojiSeNaBazu();

                $upit = "SELECT z.*, v.naziv, v.tecaj, v.aktivno_od, v.aktivno_do FROM zahtjev z, valuta v 
                WHERE z.prodajem_valuta_id = v.valuta_id AND moderator_id != {$_SESSION['id']}";

                $rezultat = izvrsiUpit($veza, $upit);

                while ($zah = mysqli_fetch_array($rezultat)) {
                    if ($zah[1] != $_SESSION['id']) {

                        echo "<tr>";
                        echo "<td>$zah[0]</td>";
                        echo "<td>$zah[1]</td>";
                        echo "<td>$zah[2]</td>";
                        echo "<td>$zah[3]</td>";
                        echo "<td>$zah[4]</td>";
                        $datum = date('d.m.Y H:i:s',strtotime($zah[5]));
                            echo "<td>$datum</td>";
                        echo "<td>$zah[9]</td>";
                        echo "<td>$zah[10]</td>";
                        
                        echo "</tr>";
                    }
                }
                zatvoriVezuNaBazu($veza);
            
                ?>
                </tbody>
            </table>
        </section>
        <?php
            }
       
     if ($_SESSION['tip'] == 0) {

    ?>

        <section>
            <table border=1>
                <thead>
                    <tr>
                        <td>ID zahtjeva</td>
                        <td>ID korisnika</td>
                        <td>Iznos</td>
                        <td>Prodajna valuta</td>
                        <td>Kupovna valuta</td>
                        <td>Datum i vrijeme kreiranja</td>
                        <td>Aktivno od</td>
                        <td>Aktivno do</td>
                        <td>Datum ažuriranja</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include_once("baza.php");
                    $veza = spojiSeNaBazu();

                    $upit = "SELECT * FROM `zahtjev`";

                    $rezultat = izvrsiUpit($veza, $upit);
                   
                    while ($zah = mysqli_fetch_array($rezultat)) {
                       

                            echo "<tr>";
                            echo "<td>$zah[0]</td>";
                            echo "<td>$zah[1]</td>";
                            echo "<td>$zah[2]</td>";
                            echo "<td>$zah[3]</td>";
                            echo "<td>$zah[4]</td>";
                            $datum = date('d.m.Y H:i:s',strtotime($zah[5]));
                            echo "<td>$datum</td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "</tr>";
                        
                    }
                    zatvoriVezuNaBazu($veza);
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <?php
        if ($_SESSION['tip'] == 0) {
        ?>
        <section>
            <h1>Statistika</h1>
            <form action='zahtjevi.php' method='POST'>
            <label for="moderator">Moderator: </label>
            <select name="moderator" id="moderator">
                <?php
                $veza = spojiSeNaBazu();
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
                ?>
            </select>
            <label for="vrijeme_od">Vrijeme od: </label>
            <input name="vrijeme_od" id="vrijeme_od" type="text" /><br>
            <label for="vrijeme_do">Vrijeme do: </label>
            <input name="vrijeme_do" id="vrijeme_do" type="text" /><br>
            <input type="submit" name="pretraga" id="pretraga" value="Pretraži" />
            </select>
            </form>
        </section>
        <?php
        if(isset($_POST['pretraga'])){
        $moderator = $_POST['moderator'];
        $vrijeme_od = date('Y-m-d H:i:s',strtotime($_POST['vrijeme_od']));
        $vrijeme_do = date('Y-m-d H:i:s',strtotime($_POST['vrijeme_do']));
        echo"<table><thead>";
        echo "<tr>";
        echo "<td>Naziv valute</td>";
        echo "<td>Ukupni iznos</td>";
        echo "</tr></thead><tbody>";
        $upit = "SELECT v.naziv, SUM(z.iznos) as ukupno_prodani_iznos FROM valuta v, zahtjev z 
        WHERE v.valuta_id=z.prodajem_valuta_id AND z.prihvacen=1 AND moderator_id = $moderator 
        AND datum_vrijeme_kreiranja BETWEEN '{$vrijeme_od}' AND '{$vrijeme_do}'
        GROUP BY v.valuta_id";
        $rezultat = izvrsiUpit($veza, $upit);
        while ($korisnik = mysqli_fetch_array($rezultat)) {
            echo "<tr>";
            echo "<td>$korisnik[0]</td>";
            echo "<td>$korisnik[1]</td>";
            echo "</tr>";
        }
    }
        echo "</tbody></table>";
     }
    
     ?>
</body>

</html>