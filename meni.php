<nav id="navigacija">
	<div class="navi"><a href="index.php" class="veza">Valute</a></div>
	<?php if (isset($_SESSION['tip']) && $_SESSION['tip'] == 0) {
		echo "<div class='navi'><a href='korisnici.php' class='veza'>Korisnici</a></div>";
	} else {
		echo "<div style = 'visibility : hidden' class='navi'><a href='korisnici.php' class='veza'>Korisnici</a></div>";
	}
	if (isset($_SESSION['tip']) && ($_SESSION['tip'] == 0 || $_SESSION['tip'] == 1)) {
		echo "<div class='navi'><a href='tecaj.php' class='veza' >Tečaj</a></div>";
	} else {
		echo "<div style = 'visibility : hidden' class='navi'><a href='tecaj.php' class='veza' >Tečaj</a></div>";
	}
	if (!isset($_SESSION['tip'])) {
		echo "<div style = 'visibility : hidden' class='navi'><a href='zahtjevi.php' class='veza' >Zahtjevi</a></div>";
		echo "<div style = 'visibility : hidden' class='navi'><a href='novcanik.php' class='veza' >Novčanik</a></div>";
	} else {
		echo "<div class='navi'><a href='zahtjevi.php' class='veza' >Zahtjevi</a></div>";
		echo "<div class='navi'><a href='novcanik.php' class='veza' >Novčanik</a></div>";
	} ?>
	<div class="navi"><a href="o_autoru.html" class="veza">O autoru</a></div>
	<?php if (!isset($_SESSION['id'])) { ?>
		<div class="navi"><a href="prijava.php" class="veza">Prijava</a></div>
	<?php } else { ?>
		<div class="navi"><a href="prijava.php?odjava=da" class="veza">Odjava</a></div>
	<?php }	?>
</nav>