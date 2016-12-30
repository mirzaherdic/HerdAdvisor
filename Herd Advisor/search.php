<?php
$xmlKorisnici=new DOMDocument();
$xmlKorisnici->load("korisnici.xml");

$xmlRecenzije=new DOMDocument();
$xmlRecenzije->load("recenzije.xml");

$q=$_GET["q"];

$k=$xmlKorisnici->getElementsByTagName('korisnik');
$r=$xmlRecenzije->getElementsByTagName('recenzija');

if (strlen($q)>0) {
  $hint="";
  for($i = 0; $i < ($k->length); $i++)
  {
  	$x = $k->item($i)->getElementsByTagName('nickname');
  	if($x->item(0)->nodeType == 1)
  	{
  		if(stristr($x->item(0)->childNodes->item(0)->nodeValue, $q))
  		{
  			if($hint == "")
  				$hint= $x->item(0)->childNodes->item(0)->nodeValue;
  			else
  				$hint = $hint . "<br>" . $x->item(0)->childNodes->item(0)->nodeValue;
  		}
  	}
  }
  for($i = 0; $i < ($r->length); $i++)
  {
    $x = $r->item($i)->getElementsByTagName('naslov');
    if($x->item(0)->nodeType == 1)
    {
      if(stristr($x->item(0)->childNodes->item(0)->nodeValue, $q))
      {
        if($hint == "")
          $hint= $x->item(0)->childNodes->item(0)->nodeValue;
        else
          $hint = $hint . "<br>" . $x->item(0)->childNodes->item(0)->nodeValue;
      }
    }
  }
}

if ($hint=="") {
  $response="Nema rezultata";
} else {
  $response=$hint;
}

echo $response;

?>