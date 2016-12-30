function changeToWhite(image)
{
	image.src = "HAWhite.png";
}
function changeToBlack(image)
{
	image.src = "HABlack.png";
}
function validirajEmail()
{
	var regex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
	var x = document.forms["FormaZaMailIPoruku"]["email"].value;
	if(x == null || x == "" || !regex.test(x))
	{
		var y = document.getElementById("GreskaEmail");
		y.innerHTML = "E-mail nije validan.";
		return false;
	}
	localStorage.setItem("email", x);
	var poruka = document.forms["FormaZaMailIPoruku"]["TekstPoruke"].value;
	localStorage.setItem("poruka", poruka);
}
function validirajOcjenu()
{
	var x = document.forms["FormaZaOcjenu"]["ocjena"].value;
	if(x == null || (x != "1" && x != "2" && x != "3" && x != "4" && x != "5"))
	{
		var y = document.getElementById("GreskaOcjena");
		y.innerHTML = "Niste unijeli ocjenu";
		return false;
	}
	localStorage.setItem("ocjena", x);
}
function validirajSlusanje()
{
	var x = document.forms["FormaZaSlusanje"]["slusanje"].value;
	if(x == null || (x != "da" && x != "ne"))
	{
		var y = document.getElementById("GreskaSlusanje");
		y.innerHTML = "Niste unijeli da li slusate elektronsku muziku.";
		return false;
	}
	localStorage.setItem("slusanje", x);
}
function validirajRegistraciju()
{
	var nicknameRegex = /^[a-zA-Z0-9]+$/;
	var x = document.forms["FormaZaRegistraciju"]["nickname"].value;
	var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
	var y = document.forms["FormaZaRegistraciju"]["password"].value;

	var z = document.getElementById("GreskaRegistracija");
	if(x == null || x.length < 4)
	{
		z.innerHTML = "Nickname nije validan(Nickname mora biti duži od 3 karaktera).";
		return false;
	}
	else if(!nicknameRegex.test(x))
	{
		z.innerHTML = "Nickname nije validan(Nickname smije sadržavati samo slova i brojeve).";
		return false;
	}
	
	if(y == null || y.length < 8)
	{
		z.innerHTML = "Password nije validan(Password mora biti dug barem 8 karaktera).";
		return false;
	}
	else if(!passwordRegex.test(y))
	{
		z.innerHTML = "Password nije validan(Password mora sadržavati barem po jedno slovo i broj.).";
		return false;
	}
}
function ucitajPodatke()
{
	var email = localStorage.getItem("email");
	var poruka = localStorage.getItem("poruka");
	var ocjena = localStorage.getItem("ocjena");
	var slusanje = localStorage.getItem("slusanje");
	var x = document.forms["FormaZaMailIPoruku"]["email"];
	var y = document.forms["FormaZaMailIPoruku"]["TekstPoruke"];
	var z = document.forms["FormaZaOcjenu"]["ocjena"];
	var w = document.forms["FormaZaSlusanje"]["slusanje"];
	x.value = email;
	y.value = poruka;
	z.value = ocjena;
	w.value = slusanje;
}
function ucitajPocetnu()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    	document.getElementById("Content").innerHTML = xhttp.responseText;
	    	updateujMeni(0);
	    }
	  };
	xhttp.open("GET", "PocetnaContent.html", true);
	xhttp.send();
	return false;
}
function ucitajDJ()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    	document.getElementById("Content").innerHTML = xhttp.responseText;
	    	updateujMeni(1);
	    }
	  };
	xhttp.open("GET", "DJContent.html", true);
	xhttp.send();
	return false;
}
function ucitajFestival()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    	document.getElementById("Content").innerHTML = xhttp.responseText;
	    	updateujMeni(2);
	    }
	  };
	xhttp.open("GET", "FestivalContent.html", true);
	xhttp.send();
	return false;
}
function ucitajMuziku()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    	document.getElementById("Content").innerHTML = xhttp.responseText;
	    	updateujMeni(3);
	    }
	  };
	xhttp.open("GET", "MuzikaContent.html", true);
	xhttp.send();
	return false;
}
function ucitajONama()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    	document.getElementById("Content").innerHTML = xhttp.responseText;
	    	updateujMeni(4);
	    	ucitajPodatke();
	    }
	  };
	xhttp.open("GET", "ONamaContent.php", true);
	xhttp.send();
	return false;
}
function updateujMeni(x)
{
	document.getElementsByClassName("active")[0].className = "";
	document.getElementsByTagName("li")[x].className = "active";
}
function ucitaj()
{
	var x = document.getElementById("testnaslov");
	var y = document.getElementById("testtekst");

	document.getElementById("naslov").innerHTML = x;
	document.getElementById("tekst").innerHTML = y;
}