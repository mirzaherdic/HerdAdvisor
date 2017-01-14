<!DOCTYPE html>
<html lang="hr">
<head>
	<title>Herd Advisor: O nama</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="Stil.css">
	<link rel="stylesheet" media="screen and (max-width: 768px)" href="StilMobilni.css">
</head>

<body>

<?php
session_start();

include 'DB.php';

$naslov = $tekst = "";
$greskaRecenzija = "";
$connection = new PDO("mysql:dbname=HerdAdvisorDB;host=localhost;charset=utf8", "Herda", "DBPassword");
$connection->exec("set names utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if (!empty($_POST['recenzije']))
	{
		$naslov = test_input($_POST["naslov"]);
		$tekst = test_input($_POST["tekst"]);
		
		if(!isset($_SESSION["permissions"]) || $_SESSION["permissions"] != "admin")
		{
			$greskaRecenzija = "Greska. Ne možete slati recenziju ako niste prijavljeni ili  niste admin";
		}
		else
		{
			$autorID = null;
			$sql = "SELECT ID FROM Korisnici WHERE Nickname = '" . $_SESSION["name"] . "';";
			$result = dajRezultate($GLOBALS['connection'], $sql);
			foreach($result as $korisnik)
				$autorID = $korisnik['ID'];
			
			$sql = "INSERT INTO Recenzije(ID, AutorID, Naslov, Tekst) VALUES(NULL, '" . $autorID . "', '" . $naslov . "', '" . $tekst . "');";
			$count = upisi($GLOBALS['connection'], $sql);

			$greskaRecenzija = "Recenzija uspjesno unesena";
		}
	}
	elseif(!empty($_POST['download']))
	{
		$csvfile = fopen('recenzije.csv', 'w');
		$temp = array("Recenzent", "Naslov", "Tekst");
		fputcsv($csvfile, $temp);
		
		$sql = "SELECT K.Nickname Nickname, R.Naslov Naslov, R.Tekst Tekst, R.ID ID FROM Korisnici K, Recenzije R WHERE R.AutorID = K.ID ORDER BY R.ID ASC;";
		$result = dajRezultate($GLOBALS['connection'], $sql);
		foreach($result as $recenzija)
		{
			$temp = array($recenzija['Nickname'], $recenzija['Naslov'], $recenzija['Tekst']);
			fputcsv($csvfile, $temp);
		}
		fclose($csvfile);

		error_reporting(0); 
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="recenzije.csv"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		ob_clean();   
		ob_end_flush(); 
		readfile("recenzije.csv"); 
		exit;
	}
	elseif(!empty($_POST['pdf']))
	{
		require('../HerdAdvisor/fpdf1.81/fpdf.php');

		while (ob_get_level())
			ob_end_clean();
		header("Content-Encoding: None", true);
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',20);
		$pdf->Cell(40,10,'Lista recenzija:');
		$pdf->Ln(20);
		$pdf->Cell(50,10,'Recenzent');
		$pdf->Cell(50,10,'Naslov');
		$pdf->Cell(50,10,'Tekst');
		$pdf->Ln(20);
		$pdf->SetFont('Arial','B',12);

		$sql = "SELECT K.Nickname Nickname, R.Naslov Naslov, R.Tekst Tekst, R.ID ID FROM Korisnici K, Recenzije R WHERE R.AutorID = K.ID ORDER BY R.ID ASC;";
		$result = dajRezultate($GLOBALS['connection'], $sql);
		foreach($result as $recenzija)
		{
			$pdf->Cell(50, 10, $recenzija['Nickname']);
			$pdf->Cell(50, 10, $recenzija['Naslov']);
			$pdf->Cell(50, 10, $recenzija['Tekst']);
			$pdf->Ln(16);
		}
		$pdf->Output();
	}
	else
	{
		$sql = "SELECT ID FROM Recenzije ORDER BY ID ASC;";
		$result = dajRezultate($GLOBALS['connection'], $sql);
		$i = 1;
		foreach($result as $recenzija)
		{
			if(!empty($_POST['brisanje' . $i]))
			{
				$sql = "DELETE FROM Recenzije WHERE ID = '" . $recenzija['ID'] . "';";
				izbrisi($GLOBALS['connection'], $sql);
				header("Refresh:0");
				break;
			}
			$i++;
		}
	}
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!-- Meni -->
<div class="Red">
<div class="Kol-1"></div>
<div class="Kol-10">
<ul class="Meni">
	<li><a href="Pocetna.php">Početna</a></li>
	<li><a href="DJ.php">DJ-evi</a></li>
	<li><a href="Festivali.php">Festivali</a></li>
	<li><a href="Muzika.php">Muzika</a></li>
	<li class="active"><a href="ONama.php">O nama</a></li>
</ul>
</div>
<div class="Kol-1"></div>
<div class="Meni"></div>
</div>

<div class="Red-10"></div>

<!-- O Nama -->
<div class="Red">

<div class="Kol-1"></div>

<div class="Kol-10">

<p class="ONama">
<?php
if(isset($_SESSION["name"]))
{
	echo "Prijavljeni korisnik: " . $_SESSION["name"]."<br>";
}
else
{
	echo "Niste prijavljeni. Možete samo da gledate recenzije.";
}
?>
</p>

<h1 class="Podaci"></h1>
<form class="Forma" id="FormaZaRecenzije" name="FormaZaRecenzije" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	Naslov:<br>
	<input type="text" id="naslov" name="naslov"><br>
	Tekst recenzije:<br>
	<textarea name="tekst" id="text" cols="60" rows="5"></textarea><br>
	<span class="greskaRecenzija"><?php echo $greskaRecenzija;?></span><br>
	<input type="submit" name="recenzije" value="Pošalji">
</form><br>

<form class="Forma" name="FormaZaDownload" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<input type="submit" name="download" value="Download liste recenzija u .csv">
</form><br>

<form class="Forma" name="FormaZaPDF" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<input type="submit" name="pdf" value="Kreiraj pdf izvjestaj">
</form>

</div>

<div class="Kol-1"></div>

</div>

<div class="Red">

<div class="Kol-1"></div>

<div class="Kol-10">

</div>

<div class="Kol-1"></div>

</div>


<div class="Red">
<div class="Kol-1"></div>
<div class="Kol-2">
<p class="ONamaVeliko">Recenzent</p>
</div>
<div class="Kol-2">
<p class="ONamaVeliko">Naslov</p>
</div>
<div class="Kol-6">
<p class="ONamaVeliko">Tekst</p>
</div>
</div>

<?php

$sql = "SELECT K.Nickname Nickname, R.Naslov Naslov, R.Tekst Tekst, R.ID ID FROM Korisnici K, Recenzije R WHERE R.AutorID = K.ID ORDER BY R.ID ASC;";
$result = dajRezultate($GLOBALS['connection'], $sql);
$brojac = 1;
foreach($result as $recenzija)
{
	?>
	<div class="Red"><div class="Kol-1"></div><div class="Kol-2"><p class="ONama">
	<?php echo $recenzija['Nickname']; ?>
	</p></div>
	<div class="Kol-2"><p class="ONama">
	<?php echo $recenzija['Naslov']; ?>
	</p></div>
	<div class="Kol-5"><p class="ONama">
	<?php echo $recenzija['Tekst']; ?>
	</p></div>
	<?php if(isset($_SESSION["permissions"]) && $_SESSION["permissions"] == "admin")
	{ ?>
		<div class="Kol-1"><form class="Forma" name="FormaZaBrisanje" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"><input type="submit" name="brisanje<?php echo $brojac; ?>" value="Obriši"></form><br><form class="Forma" name="FormaZaIzmjenu" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"><input type="submit" name="izmjena<?php echo $brojac; ?>" value="Izmjeni"></form></div>
	<?php }else ?>
		<div class="Kol-1"></div>
	</div>
	<?php $brojac++;
}
?>	

<?php

$i = 1;
$sql = "SELECT ID, Naslov, Tekst FROM Recenzije R ORDER BY R.ID ASC;";
$result = dajRezultate($GLOBALS['connection'], $sql);
foreach($result as $recenzija)
{
	if(!empty($_POST['izmjena' . $i]))
	{
		?>
		<div class="Red">
			<div class="Kol-1"></div>
			<div class="Kol-10">
				<form class="Forma" name="FormaIzmjena" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
					<input type="hidden" name="broj_" value="<?php echo $recenzija['ID']; ?>">
					<input type="text" name="naslov_" value="<?php echo $recenzija['Naslov'];?>">
					<input type="text" name="tekst_" value="<?php echo $recenzija['Tekst'];?>">
					<input type="submit" name="promjena" value="Izmjeni">
				</form>
			</div>
			<div class="Kol-1"></div>
		</div>
		<?php
	}
	$i++;
}

if(!empty($_POST["promjena"]))
{
	$ID = test_input($_POST["broj_"]);
	$naslov_ = test_input($_POST["naslov_"]);
	$tekst_ = test_input($_POST["tekst_"]);
	
	$sql = "UPDATE Recenzije SET Naslov = '" . $naslov_ . "', Tekst = '" . $tekst_ . "' WHERE ID = '" . $ID . "';";
	//promjeni($connection, $sql);
	promjeni($GLOBALS['connection'], $sql);
	echo("<meta http-equiv='refresh' content='1'>");
}
?>


<script src="Scripts.js"></script>
</body>
</html>