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
    <h1>Stanje na računu korisnika <?php echo "{$_SESSION['ime']} {$_SESSION['prezime']}"; ?></h1>

    <section>
        <table border=1>
            <thead>
                <tr>
                    <td>Količina sredstava</td>
                    <td>Valuta</td>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                include_once("baza.php");
                $veza = spojiSeNaBazu();

                $id_nova_sredstva = "";
                if (isset($_POST["submit"])) {
                    $korisnik_id = $_SESSION["id"];
                    $iznos = $_POST["iznos"];
                    $valuta = $_POST["valuta"];
                    $upit = "SELECT s.sredstva_id, s.iznos, v.naziv, v.valuta_id FROM sredstva s, valuta v WHERE s.valuta_id=v.valuta_id AND korisnik_id = {$_SESSION['id']}";

                    $rezultat = izvrsiUpit($veza, $upit);
                   
                    $azuriraj = '';
                    while($ima_sredstva = mysqli_fetch_array($rezultat)){
                        if($ima_sredstva[3] == $valuta){
                            $azuriraj = 'da'; 
                            echo "upis u postojeci {$ima_sredstva[2]}";
                            $upit = "UPDATE sredstva SET
                            iznos = '{$iznos}'
                              WHERE sredstva_id = '{$ima_sredstva[0]}'";
               
                           izvrsiUpit($veza, $upit);
                        break;
                    }else{
                        $azuriraj = "ne";
                        
                    }
                    
                    }
                    if($azuriraj == 'ne'){
                    echo $azuriraj;
                   if (!empty($korisnik_id) && !empty($iznos) && !empty($valuta)) {
                        $upit = "INSERT INTO sredstva(`korisnik_id`,`valuta_id`,`iznos`) 
                            VALUES( '$korisnik_id','$valuta','$iznos')";
                        izvrsiUpit($veza, $upit);
                        $id_nova_sredstva = mysqli_insert_id($veza);
                   }
                }
                    
                    }   
    
                  
               
                $i = 0;
        $upit = "SELECT * FROM valuta";
        $rezultat_val = izvrsiUpit($veza, $upit);
        while ($rezultat_valuta = mysqli_fetch_array($rezultat_val)) {
            $valuta_naziv[$i] = $rezultat_valuta['naziv'];
            $valuta_id[$i] = $rezultat_valuta['valuta_id'];
            $i++;
        }

                $upit = "SELECT s.sredstva_id, s.iznos, v.naziv FROM sredstva s, valuta v WHERE s.valuta_id=v.valuta_id AND korisnik_id = {$_SESSION['id']}";

                $rezultat = izvrsiUpit($veza, $upit);

               
                while ($stanje = mysqli_fetch_array($rezultat)) {
                    echo "<tr>";
                    echo "<td>$stanje[1]</td>";
                    echo "<td>$stanje[2]</td>";
                    echo "</tr>";
                }
                
                ?>
                <tr>
                <td style = 'border : 0' ><h4>Dodaj/ažuriraj postojeća sredstva</h4></td>
                </tr>
                <tr>
                <form name="unos_sredstva" id="unos_sredstva" method="POST" action="novcanik.php" >
                <td><input name="iznos" id="iznos" type="number" placeholder="Iznos"/></td>
                <td><select name="valuta" id="valuta">
                <?php
                
                $i = 0;
                while ($i < count($valuta_naziv)) {
                    echo "<option value='{$valuta_id[$i]}'>{$valuta_naziv[$i]}</option>";
                    $i++;
                }
                ?>
                </select></td>
                <td><button type="submit" name="submit">Dodaj drugu valutu</button></td>
                
            </tbody>
        </table>
        
        <div>
            <a href="salji_zahtjev.php">Šalji zahtjev</a>
        </div>
        <?php
        zatvoriVezuNaBazu($veza);
        ?>
</body>

</html>