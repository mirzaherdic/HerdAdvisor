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

<h1 class="Podaci">Registracija: </h1>
<form class="Forma" name="FormaZaRegistraciju" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	Nickname:<br>
	<input type="text" name="nickname"><br>
	Password:<br>
	<input type="password" name="password"><br>
	<p class="TekstGreske" id="GreskaRegistracija"></p>
	<input type="submit" value="Registriraj se">
</form>

<?php
// define variables and set to empty values
$nickname = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nickname = test_input($_POST["nickname"]);
  $password = test_input($_POST["password"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<?php
echo "<h2>Your Input:</h2>";
echo $nickname;
echo "<br>";
echo $password;
?>

</div>


<div class="Kol-1"></div>

</div>