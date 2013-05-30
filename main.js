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
		document.getElementById("userlogin").innerHTML = "getLoginInformation";
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
	var variables = "p=" + playerID + "&t=" + teamID;
	//var variables = "p=1&t=1";
	//alert(variables);
	xmlhttp.open("GET", "logininfo.php?" + variables, false);
	xmlhttp.send();
}

//Get full player table data
function showUser(str) {
	//alert("showUser() gets called.");
	if (str == "") {
		document.getElementById("userlogin").innerHTML = "showUser()";
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
		}
	}

	//alert("GET gets called.");
	xmlhttp.open("GET", "players_short.php", false);
	xmlhttp.send();
}

//Getting player list for inserting new event
function getPlayersInsert() {
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("admin_content_id").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET gets called.");
	xmlhttp.open("GET", "players_insert.php", false);
	xmlhttp.send();
}

//Get events with players for the team
function getEvents(str, str2) {
	if (str == "" || str2 == "") {
		document.getElementById("userlogin").innerHTML = "getEvents()";
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
	var variables = "teamid=" + str + "&playerid=" + str2;
	xmlhttp.open("GET", "event_list.php?" + variables, false);
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

//Update AYI status
function updateAYI(eventplayerid, ayi)
{
	//alert("updateAYI() gets called.");
	if (eventplayerid == "" || ayi == "") {
		document.getElementById("userlogin").innerHTML = "updateAYI()";
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
			//alert(xmlhttp.responseText);
			//getEvents(gup('t'), gup('p')); //Update events to be sure...
		}
	}

	var variables = "event=" + eventplayerid + "&ayi=" + ayi;
	//alert(variables);
	xmlhttp.open("GET", "update_inout.php?" + variables, true);
	xmlhttp.send();
}

//SSE
function setSSE()
{
	if(typeof(EventSource)!=="undefined")
	{
		var source=new EventSource("events_sse.php");
		
		source.addEventListener("ayi", function(event) {
			var data = event.data;
			//var origin = event.origin;
			//var lastEventId = event.lastEventId;
			
			// handle message
			//console.log("AYI:" + event.data);
			getEvents(gup('t'), gup('p'));			
			//getEvents(gup('t'), gup('p'));			
		}, false);
		
		/*source.onmessage=function(event)
		{
			//document.getElementById("result").innerHTML+=event.data + "<br>";
			//console.log("\nsse message: " + event.data);
			//source.close();
		}*/
	}
	else
	{
		//document.getElementById("result").innerHTML="Sorry, your browser does not support server-sent events...";
		//console.log("No SSE");
	}
}

function copyStart()
{
	var start = document.getElementById("gamesstart_id").value;
	document.getElementById("gamesend_id").setAttribute("value", start);
}
