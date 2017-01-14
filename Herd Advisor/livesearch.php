<?php

include 'DB.php';

$connection = new PDO("mysql:dbname=HerdAdvisorDB;host=localhost;charset=utf8", "Herda", "DBPassword");
$connection->exec("set names utf8");

$q=$_GET["q"];
$broj = 0;

if(strlen($q) > 0)
{
    $hint="";

    $sql = "SELECT Nickname FROM Korisnici WHERE Nickname LIKE '%" . $q . "%';";
    $result = dajRezultate($connection, $sql);
    foreach ($result as $korisnik) 
    {
        if($broj >= 10)
            break;
        if($hint == "")
            $hint = $hint . $korisnik['Nickname'];
        else
            $hint = $hint . "<br>" . $korisnik['Nickname'];
        $broj++;
    }

    $sql = "SELECT Naslov FROM Recenzije WHERE Naslov LIKE '%" . $q . "%';";
    $result = dajRezultate($connection, $sql);
    foreach ($result as $recenzija) 
    {
        if($broj >= 10)
            break;
        if($hint == "")
            $hint = $hint . $recenzija['Naslov'];
        else
            $hint = $hint . "<br>" . $recenzija['Naslov'];
        $broj++;
    }
}

if ($hint=="") {
  $response="Nema rezultata";
} else {
  $response=$hint;
}

echo $response;

?>