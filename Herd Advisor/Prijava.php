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

$nickname = $password = $greskaPrijava = $greskaOdjava = "";
$xml = null;
$uspio = false;

if(file_exists('korisnici.xml'))
	$xml = simplexml_load_file('korisnici.xml');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if(!empty($_POST['prijava']))
	{
		if(isset($_SESSION["name"]))
		{
			$greskaPrijava = "Morate se prvo odjaviti da biste se prijavili";
		}
		else
		{
			$nickname = test_input($_POST["nickname"]);
			$password = test_input($_POST["password"]);
			if($xml)
			{
				foreach($xml->korisnik as $korisnik)
					if($korisnik->nickname == $nickname && $korisnik->password == $password)
					{
						session_destroy();
						session_start();
						$_SESSION["name"] = $nickname;
						$_SESSION["permissions"] = (string)$korisnik->tip;
						$uspio = true;
						break;
					}
			}
			if(!$uspio)
				$greskaPrijava = "Pogrešan nickname ili password";
		}
	}
	elseif(!empty($_POST['odjava']))
	{
		if(!isset($_SESSION['name']))
			$greskaOdjava = "Ne mozete se odjaviti ako niste prijavljeni.";
		else
		{
			session_destroy();
			header("Refresh:0");
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

<h1 class="Podaci">Prijava: </h1>
<form class="Forma" name="FormaZaRegistraciju" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	Nickname:<br>
	<input type="text" name="nickname"><br>
	Password:<br>
	<input type="password" name="password"><br>
	<span class="greskaUnos"><?php echo $greskaPrijava;?></span><br>
	<input type="submit" name="prijava" value="Prijavi se">
</form>

<form class="Forma" name="FormaZaOdjavu" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<span class="greskaOdjava"><?php echo $greskaOdjava;?></span><br>
	<input type="submit" name="odjava" value="Odjavi se">
</form>

<p class="ONama">
<?php
if(isset($_SESSION["name"]))
{
	echo "Prijavljeni korisnik: " . $_SESSION["name"]."<br>";
}
?>
</p>
</div>

<div class="Kol-1"></div>

</div>

<script src="Scripts.js"></script>
</body>
</html>