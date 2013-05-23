//login function
/*function login(lname, pass) {
	alert("login called with name&pass: " + lname + " & " + pass);
}*/

//Init function for the screen startup
/*function init() {
	getEvents();
	getPlayers();
}*/

//Get users name & team name
function getLoginInformation(playerID, teamID) {
	//alert("showUser() gets called.");
	if (playerID == "" || teamID == "") {
		document.getElementById("txtHint").innerHTML = "";
		return;
	}
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("userlogin").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET ge7ts called.");
	var variables = "playerid=" + playerID + "&teamid=" + teamID;
	//alert(variables);
	xmlhttp.open("GET", "logininfo.php?", variables, false);
	xmlhttp.send();
}

//Get full player table data
function showUser(str) {
	//alert("showUser() gets called.");
	if (str == "") {
		document.getElementById("txtHint").innerHTML = "";
		return;
	}
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("atable").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET gets called.");
	xmlhttp.open("GET", "database.php?q=" + str, true);
	xmlhttp.send();
}

//Getting player image & name
function getPlayers() {
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("players_short").innerHTML = xmlhttp.responseText;
			//document.getElementById("players_short2").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET gets called.");
	xmlhttp.open("GET", "players_short.php", false);
	xmlhttp.send();
}

//Get events with players for the team
function getEvents(str) {
	if (str == "") {
		document.getElementById("txtHint").innerHTML = "";
		return;
	}	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("event_content_id").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET gets called.");
	xmlhttp.open("GET", "event_list.php?teamid=" + str, false);
	xmlhttp.send();
}

//Parse URL parameters by name
function gup( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}
