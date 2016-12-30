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

$naslov = $tekst = "";
$greskaRecenzija = "";
$xml = null;

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
			if(file_exists('recenzije.xml'))
			{
				$xml = simplexml_load_file('recenzije.xml');

				$recenzija = $xml->addChild('recenzija');
				$recenzija->addChild('recenzent', $_SESSION["name"]);
				$recenzija->addChild('naslov', $naslov);
				$recenzija->addChild('tekst', $tekst);


				$dom = new DOMDocument('1.0');
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->loadXML($xml->asXML());
				$dom->save('recenzije.xml');

				$greskaRecenzija = "Recenzija uspjesno unesena";
			}
			else
			{
				$greskaRecenzija = "Recenzija nije unesena";
			}
		}
	}
	elseif(!empty($_POST['download']))
	{
		if(file_exists('recenzije.xml'))
			$xml = simplexml_load_file('recenzije.xml');
		$csvfile = fopen('recenzije.csv', 'w');
		$temp = array("Recenzent", "Naslov", "Tekst");
		fputcsv($csvfile, $temp);
		if($xml)
		{
			foreach($xml as $recenzija)
			{
				$temp = array($recenzija->recenzent, $recenzija->naslov, $recenzija->tekst);
				fputcsv($csvfile, $temp);
			}
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
		$xml = null;
		if(file_exists('recenzije.xml'))
			$xml = simplexml_load_file('recenzije.xml');
		if($xml)
		{
			foreach($xml as $recenzija)
			{
				$pdf->Cell(50,10,$recenzija->recenzent);
				$pdf->Cell(50,10,$recenzija->naslov);
				$pdf->Cell(50,10,$recenzija->tekst);
				$pdf->Ln(16);
			}
		}
		$pdf->Output();
	}
	else
	{
		if(file_exists('recenzije.xml'))
			$xml = simplexml_load_file('recenzije.xml');
		if($xml)
		{
			for($i = 0; $i < count($xml); $i++)
			{
				if(!empty($_POST['brisanje' . $i]))
				{
					$j = 0;
					foreach($xml as $recenzija)
					{
						if($i == $j)
						{
    						$cvor = dom_import_simplexml($recenzija);
        					$cvor->parentNode->removeChild($cvor);

						    $dom = new DOMDocument('1.0');
							$dom->preserveWhiteSpace = false;
							$dom->formatOutput = true;
							$dom->loadXML($xml->asXML());
							$dom->save('recenzije.xml');
        					break;
						}
						$j += 1;
					}
				}
			}
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

if(file_exists('recenzije.xml'))
{
	$xml = simplexml_load_file('recenzije.xml');
	$brojac = 0;
	foreach ($xml as $recenzija) 
	{
		?>
		<div class="Red"><div class="Kol-1"></div><div class="Kol-2"><p class="ONama">
		<?php echo $recenzija->recenzent; ?>
		</p></div>
		<div class="Kol-2"><p class="ONama">
		<?php echo $recenzija->naslov; ?>
		</p></div>
		<div class="Kol-5"><p class="ONama">
		<?php echo $recenzija->tekst; ?>
		</p></div>
		<?php if(isset($_SESSION["permissions"]) && $_SESSION["permissions"] == "admin")
		{?>
			<div class="Kol-1"><form class="Forma" name="FormaZaBrisanje" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"><input type="submit" name="brisanje<?php echo $brojac; ?>" value="Obriši"></form><br><form class="Forma" name="FormaZaIzmjenu" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"><input type="submit" name="izmjena<?php echo $brojac; ?>" value="Izmjeni"></form></div>
		<?php }else ?>
			<div class="Kol-1"></div>
		</div>
		<?php $brojac += 1;
	}
}
?>

<?php

for($i = 0; $i < count($xml); $i++)
{
	if(!empty($_POST['izmjena' . $i]))
	{
		$j = 0;
		foreach($xml as $recenzija)
		{
			if($i == $j)
			{
				?>
				<div id="testbroj" style="display: none;">
				<?php 
				echo htmlspecialchars($k);
				?>
				</div>
				<div id="testnaslov" style="display: none;">
				<?php 
				echo htmlspecialchars($recenzija->autor);
				?>
				</div>
				<div id="testtekst" style="display: none;">
				<?php 
				echo htmlspecialchars($recenzija->tekst);
				?>
				</div>
				
				<div class="Red">
					<div class="Kol-1"></div>
					<div class="Kol-10">
						<form class="Forma" name="FormaIzmjena" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
							<input type="hidden" name="broj_" value="<?php echo $j; ?>">
							<input type="text" name="naslov_" value="<?php echo $recenzija->naslov;?>">
							<input type="text" name="tekst_" value="<?php echo $recenzija->tekst;?>">
							<input type="submit" name="promjena" value="Izmjeni">
						</form>
					</div>
					<div class="Kol-1"></div>
				</div>
				<?php
				break;
			}
			$j += 1;
		}
	}
}
if(!empty($_POST["promjena"]))
{
	$i = 0;
	$broj_ = test_input($_POST["broj_"]);
	$naslov_ = test_input($_POST["naslov_"]);
	$tekst_ = test_input($_POST["tekst_"]);
	foreach ($xml as $recenzija) 
	{
		if($broj_ == $i)
		{
			$recenzija->autor = $_SESSION["name"];
			$recenzija->naslov = $naslov_;
			$recenzija->tekst = $tekst_;

			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($xml->asXML());
			$dom->save('recenzije.xml');
			

			header("Refresh:0");
			break;
		}
		$i++;
	}
}
?>


<script src="Scripts.js"></script>
</body>
</html>