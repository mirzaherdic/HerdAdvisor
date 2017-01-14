<?php

include 'DB.php';

$connection = new PDO("mysql:dbname=HerdAdvisorDB;host=localhost;charset=utf8", "Herda", "DBPassword");
$connection->exec("set names utf8");

function ubaciUBazu()
{
	ubaciTipUBazu("1", "admin");
	ubaciTipUBazu("2", "korisnik");

	if(file_exists('korisnici.xml'))
		$xml = simplexml_load_file('korisnici.xml');
	if($xml)
	{
		foreach($xml as $korisnik)
		{
			$nickname = $korisnik->nickname;
			$password = $korisnik->password;
			$tip = $korisnik->tip;

			if(!postojiKorisnik($nickname))
			{
				ubaciUBazu($nickname, $password, $tip);
			}
		}
	}

	if(file_exists('Recenzije.xml'))
		$xml = simplexml_load_file('Recenzije.xml');
	if($xml)
	{
		foreach($xml as $recenzija)
		{
			$recenzent = $recenzija->recenzent;
			$naslov = $recenzija->naslov;
			$tekst = $recenzija->tekst;

			if(!postojiRecenzija($naslov))
			{
				ubaciRecenzijuUBazu($recenzent, $naslov, $tekst);
			}
		}
	}
}
function postojiKorisnik($nickname)
{
	$sql = "SELECT COUNT(*) AS Postoji FROM Korisnici WHERE Nickname = '" . $nickname . "';";
	$result = dajRezultate($GLOBALS['connection'], $sql);
	foreach($result as $postoji)
		if ($postoji['Postoji'] == 0)
			return false;
	return true;
}

function postojiRecenzija($naslov)
{
	$sql = "SELECT COUNT(*) AS Postoji FROM Recenzije WHERE Naslov = '" . $naslov . "';";
	$result = dajRezultate($GLOBALS['connection'], $sql);
	foreach($result as $postoji)
		if ($postoji['Postoji'] == 0)
			return false;
	return true;
}

function ubaciTipUBazu($ID, $Tip)
{
	$sql = "SELECT ID FROM TipoviKorisnika WHERE Tip = '" . $Tip . "';";
	$result = dajRezultate($GLOBALS['connection'], $sql);
	if($result->rowCount() < 1)
	{
		$sql = "INSERT INTO TipoviKorisnika(ID, Tip) VALUES ('" . $ID . "', '" . $Tip . "');";
		$count = upisi($GLOBALS['connection'], $sql);
	}
}

function ubaciKorisnikaUBazu($nickname, $password, $tip)
{
	$sql = "SELECT ID FROM TipoviKorisnika WHERE Tip = '" . $tip . "';";
	$result = dajRezultate($GLOBALS['connection'], $sql);
	if($result->rowCount() < 1)
	{
		echo "Nepoznati tip korisnika";
		exit();
	}

	foreach ($result as $res)
		$tipID = $res['ID'];

	$sql = "INSERT INTO Korisnici (ID, Nickname, Password, TipID) VALUES (NULL, '" . $nickname . "', '" . $password . "', '" . $tipID . "');";
	$count = upisi($GLOBALS['connection'], $sql);
}

function ubaciRecenzijuUBazu($recenzent, $naslov, $tekst)
{
	$sql = "SELECT ID FROM Korisnici WHERE Nickname = '" . $recenzent . "';";
	$result = dajRezultate($GLOBALS['connection'], $sql);
	if($result->rowCount() < 1)
	{
		echo "Korisnik ne postoji";
		exit();
	}

	foreach ($result as $res)
		$korisnikID = $res['ID'];

	$sql = "INSERT INTO Recenzije (ID, AutorID, Naslov, Tekst) VALUES (NULL, '" . $korisnikID . "', '" . $naslov . "', '" . $tekst . "');";
	$count = upisi($connection, $sql);
}

?>