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
session_start();

include 'DB.php';

$nickname = $password = "";
$greskaNickname = $greskaPassword  = $greska = "";
$korisnikPostoji = false;

$connection = new PDO("mysql:dbname=HerdAdvisorDB;host=localhost;charset=utf8", "Herda", "DBPassword");
$connection->exec("set names utf8");

if(isset($_SESSION["name"]))
{
	$greska = "Ne možete praviti novi profil dok ste prijavljeni. Prvo se odjavite";
}
else
{
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		$nickname = test_input($_POST["nickname"]);
		if (!preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{5,31}$/', $nickname))
		{
			$greskaNickname = "Nickname mora biti između 6 i 32 karaktera dug i smije sadržavati samo slova i brojeve";
			$greska = "Registracija neuspjela";
		}
		else
		{
			$sql = "SELECT COUNT(*) AS Postoji FROM Korisnici WHERE Nickname = '" . $nickname . "';";
			$result = dajRezultate($GLOBALS['connection'], $sql);
			foreach($result as $postoji)
				if ($postoji['Postoji'] > 0)
				{
					$korisnikPostoji = true;
					break;
				}
			$password = test_input($_POST["password"]);
		  	if(!preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{7,31}$/', $password))
		  	{
				$greskaPassword = "Password mora biti dug između 8 i 32 karaktera i smije sadržavati samo slova i brojeve";
				$greska = "Registracija neuspjela";
		  	}
			else
			{
				if($korisnikPostoji)
				{
					$greskaNickname = "Korisnik vec postoji";
					$greska = "Registracija neuspjela";
				}
				else
				{
					$sql = "INSERT INTO Korisnici (ID, Nickname, Password, TipID) VALUES (NULL, '" . $nickname . "', '" . $password . "', '2');";
					$count = upisi($GLOBALS['connection'], $sql);
					$greska = "Uspjesno registrovan korisnik: " . $nickname;
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

<h1 class="Podaci">Registracija: </h1>
<form class="Forma" name="FormaZaRegistraciju" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<span class="greskaUnos"><?php echo $greska;?></span><br>
	Nickname:<br>
	<input type="text" name="nickname">
	<span class="greskaUnos"><?php echo $greskaNickname;?></span><br>
	Password:<br>
	<input type="password" name="password">
	<span class="greskaUnos"><?php echo $greskaPassword;?></span><br>
	<input type="submit" value="Registriraj se">
</form>

</div>


<div class="Kol-1"></div>

</div>

<script src="Scripts.js"></script>
</body>
</html>