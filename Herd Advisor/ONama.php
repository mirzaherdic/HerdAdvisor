<!DOCTYPE html>
<html lang="hr">
<head>
	<title>Herd Advisor: O nama</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="Stil.css">
	<link rel="stylesheet" media="screen and (max-width: 768px)" href="StilMobilni.css">
</head>

<body onload="ucitajPodatke()">

<?php
$nickname = $password = "";
$greskaNickname = $greskaPassword = $greskaPrijava = "";
$xml = null;
$korisnikPostoji = false;

if(file_exists('korisnici.xml'))
{
	$xml = simplexml_load_file('korisnici.xml');
}

//$xml = simplexml_load_file("korisnici.xml") or die("Gresla: Ne moze se kreirati objekat");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$nickname = test_input($_POST["nickname"]);
	if (!preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{5,31}$/', $nickname))
		$greskaNickname = "Nickname mora biti između 6 i 32 karaktera dug i smije sadržavati samo slova i brojeve";
	else
	{
		if($xml)
		{
			foreach($xml->korisnik as $korisnik)
				if($korisnik->nickname == $nickname)
				{
					$korisnikPostoji = true;
					break;
				}
		}
	}
	$password = test_input($_POST["password"]);
  	if(!preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{7,31}$/', $password))
  	{
		$greskaPassword = "Password mora biti dug između 8 i 32 karaktera i smije sadržavati samo slova i brojeve";
  	}
	else
	{
		if($korisnikPostoji)
		$greskaNickname = "Korisnik vec postoji";
		else
		{
			$korisnik = $xml->addChild('korisnik');
			$korisnik->addChild('nickname', $nickname);
			$korisnik->addChild('password', $password);
			$korisnik->addChild('tip', 'korisnik');
			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($xml->asXML());
			$dom->save('korisnici.xml');
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

<div class="Kol-ONama">
<p class="ONama">Herd Advisor je stranica koja je nastala kao rezultat želje da se domaćoj(bosansko-hercegovačkoj) publici približi Elektronska muzika kao najbrže rastući žanr muzike na svijetu. Na ovim prostorima ovaj žanr nije toliko popularan kao u većini zemalja razvijenog svijeta ili je eventualno popularna veoma komercijalizovana elektronska muzika koja ni u kom slučaju ne predstavlja cijeli žanr. Naš cilj je da kroz ovu stranicu edukujemo publiku o raznim podžanrovima elektronske muzike, umjetnicima koji se bave produkcijom, festivala i raznih drugih stvari koje su vezane za ovu vrstu muzike.</p>
</div>

<p class="TekstZaFormu">Ostavite nam poruku:</p>
<form class="Forma" name="FormaZaMailIPoruku" onsubmit="return validirajEmail()" method="post">
	E-mail:<br>
	<input type="text" name="email"><br>
	Poruka:<br>
	<textarea name="TekstPoruke" cols="60" rows="5"></textarea><br>
	<p class="TekstGreske" id="GreskaEmail"></p>
	<input type="submit" value="Pošalji">
</form>

<div class="Red-10"></div>

<p class="TekstZaFormu">Kako vam se sviđa stranica?</p>
<form class="Forma" name="FormaZaOcjenu" onsubmit="return validirajOcjenu()" method="post">
	<input type="radio" name="ocjena" value="1"> 1<br>
	<input type="radio" name="ocjena" value="2"> 2<br>
	<input type="radio" name="ocjena" value="3"> 3<br>
	<input type="radio" name="ocjena" value="4"> 4<br>
	<input type="radio" name="ocjena" value="5"> 5<br>
	<p class="TekstGreske" id="GreskaOcjena"></p>
	<input type="submit" value="Ocjeni">
</form>

<div class="Red-10"></div>

<p class="TekstZaFormu">Da li slušate elektronsku muziku?</p>
<form class="Forma" name="FormaZaSlusanje" onsubmit="return validirajSlusanje()" method="post">
	<input type="radio" name="slusanje" value="da"> Da<br>
	<input type="radio" name="slusanje" value="ne"> Ne<br>
	<p class="TekstGreske" id="GreskaSlusanje"></p>
	<input type="submit" value="Pošalji">
</form>

<div class="Red-10"></div>

<div class="Novost"><a class="LinkONama" href="Registracija.php">Registracija</a></div>

<div class="Red-10"></div>

<div class="Novost"><a class="LinkONama" href="Prijava.php">Prijava</a></div>

<div class="Red-10"></div>

<div class="Novost"><a class="LinkONama" href="Recenzije.php">Recenzije</a></div>

<div class="Red-10"></div>

<div class="Novost"><a class="LinkONama" href="Pretraga.html">Pretraga</a></div>

</div>


<div class="Kol-1"></div>

</div>

<!-- Dno stranice -->
<div class="RedNaKraju">
<div class="Kol-3"></div>

<div class="Kol-2"><a href="Pocetna.html"><img class="Logo" src="HAWhite.png" alt="Logo" onmouseover="changeToBlack(this);" onmouseout="changeToWhite(this);"></a></div>

<div class="Kol-1"></div>

<div class="Kol-1">
	<div class="Red-5"></div>
	<div class="Red">
		<a class="Link" href="Pocetna.html">Pocetna</a>
	</div>
	<div class="Red">
		<a class="Link" href="DJ.html">DJ-evi</a>
	</div>
	<div class="Red">
		<a class="Link" href="Festivali.html">Festivali</a>
	</div>
	<div class="Red-5"></div>
</div>

<div class="Kol-1">
	<div class="Red-5"></div>
	<div class="Red">
		<a class="Link" href="Muzika.html">Muzika</a>
	</div>
	<div class="Red">
		<a class="Link" href="ONama.html">O Nama</a>
	</div>
	<div class="Red-5"></div>
</div>

<div class="Kol-1">
    <div class="Red-5"></div>
	<div class="Red">
		<a class="Link" href="Uskoro.html">Herd Advisor na engleskom jeziku (Uskoro)</a>
	</div>
	<div class="Red-5"></div>
</div>

<div class="Kol-3"></div>
</div>
<div class="Red-5"></div>

<div class="Red">
<div class="Kol-9"></div>
<div class="Kol-3"><p class="Autor">Autor: Mirza Herdić<br>e-mail: mirza.herdic@gmail.com</p>
</div>
</div>

<script src="Scripts.js"></script>
</body>
</html>