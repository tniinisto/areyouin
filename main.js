//Get users name & team name
function getLoginInformation() {
	//alert("showUser() gets called.");
	//if (playerID == "" || teamID == "") {
	//	document.getElementById("userlogin").innerHTML = "getLoginInformation";
	//	return;
	//}
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("userlogin1").innerHTML = xmlhttp.responseText;
            document.getElementById("userlogin2").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET ge7ts called.");
	//var variables = "p=" + playerID + "&t=" + teamID;
	//var variables = "p=1&t=1";
	//alert(variables);
	//xmlhttp.open("GET", "logininfo.php?" + variables, false);
	//alert("jou");
    xmlhttp.open("GET", "logininfo.php", false);
	xmlhttp.send();
}

//Get admin status
function getAdminStatus() {
	//alert("showUser() gets called.");
	//if (playerID == "" || teamID == "") {
	//	document.getElementById("userlogin").innerHTML = "getLoginInformation";
	//	return;
	//}
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("linkadmin").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET ge7ts called.");
	//var variables = "p=" + playerID + "&t=" + teamID;
	//var variables = "p=1&t=1";
	//alert(variables);
	//xmlhttp.open("GET", "logininfo.php?" + variables, false);
	//alert("jou");
    xmlhttp.open("GET", "adminstatus.php", false);
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
//function getPlayers(teamid) {
//	if (teamid == "") {
//		document.getElementById("userlogin").innerHTML = "getPlayers()";
//		return;
//	}		
//	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
//		xmlhttp = new XMLHttpRequest();
//	}
//	else {// code for IE6, IE5
//		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
//	}

//	xmlhttp.onreadystatechange = function () {
//		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
//			document.getElementById("players_short").innerHTML = xmlhttp.responseText;
//		}
//	}

//	//alert("GET gets called.");
//    var variables = "teamid=" + teamid;
//	xmlhttp.open("GET", "players_short.php?" + variables, false);
//	xmlhttp.send();
//}

//Getting player list for inserting new event
function getPlayersInsert() {
	//if (teamid == "") {
	//	document.getElementById("userlogin").innerHTML = "getPlayersInsertents()";
	//	return;
	//}	
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
	//var variables = "teamid=" + teamid;
	xmlhttp.open("GET", "players_insert.php", false);
	xmlhttp.send();
}

//Get events with players for the team
function getEvents() {
	//if (str == "" || str2 == "") {
	//	document.getElementById("userlogin").innerHTML = "getEvents()";
	//	return;
	//}	
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
	//var variables = "teamid=" + str + "&playerid=" + str2;
	//xmlhttp.open("GET", "event_list.php?" + variables, false);
    xmlhttp.open("GET", "event_list.php", false);
	xmlhttp.send();
}

//Parse URL parameters by name
//function gup( name )
//{
//  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
//  var regexS = "[\\?&]"+name+"=([^&#]*)";
//  var regex = new RegExp( regexS );
//  var results = regex.exec( window.location.href );
//  if( results == null )
//    return "";
//  else
//    return results[1];
//}

//Update AYI status
function updateAYI(eventplayerid, ayi, eventid, switchid)
{
	//alert("updateAYI() gets called.");
    //alert(switchid);

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

	        //getEvents(); //Update events
	    }
	}

	//Update the summary count to client when in/out switch is clicked///////////////////////////
	var summary_id = "id_summary" + eventid;
	var th = document.getElementById(summary_id).innerHTML;
	//alert(th);

	var start = th.indexOf(":")
	var end = th.indexOf("/")
	var value = th.substring(start + 1, end);
	value = value.trim();
	//alert(value);

	//alert(ayi);
    var switch_id = "myonoffswitch" + switchid;
	//var sw = document.getElementById(switch_id).innerHTML;
	//if (ayi == 0) {
    if(document.getElementById(switch_id).checked == false) {
	    //alert("ayi 0");
        //document.getElementById(switch_id).checked = false;
        ayi = 0;
        value--;
	}
	else {
        //alert("ayi 1");
	    //document.getElementById(switch_id).checked = true;
        ayi = 1;
        value++;
	}

	var start2 = th.indexOf("/")
	var value2 = th.substr(start2 + 1);
	value2 = value2.trim();

	document.getElementById(summary_id).innerHTML = "Players IN: " + value + " / " + value2;
    //////////////////////////////////////////////////////////////////////////////////////////////////

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

		source.addEventListener("ayi", function (event) {
		    var data = event.data;
		    //var origin = event.origin;
		    //var lastEventId = event.lastEventId;

		    //Update chat
		    //getChat();

		    // handle message
		    //console.log("AYI:" + event.data);
		    //getEvents(gup('t'), gup('p'));
		    //getEvents();

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

//Update selected event's content
function updateEvent(eventID)
{
	//alert("updateEvent(eventID) gets called: eventID=" + eventID);
	if (eventID == "") {
		document.getElementById("userlogin").innerHTML = "updateEvent(eventID)";
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
	var variables = "eventid=" + eventID;
	xmlhttp.open("GET", "update_event.php?" + variables, false);
	xmlhttp.send();	
}

//Show&hide events players in event list
function showPlayers(eventid) {
    //alert(eventid);
    //var id = "#id_playersfull_" + eventid;
    ////alert(id);
    //if ($(id).hasClass('noshow'))
    //    $(id).removeClass("noshow");
    //else
    //    $(id).addClass("noshow");
    
    var id = "#id_playersfull_" + eventid;
    var box = $(id);

    if (box.hasClass('noshow')) {
    
        box.removeClass('noshow');
        setTimeout(function () {
            box.removeClass('visuallynoshow');
        }, 20);

    } else {
    
        box.addClass('visuallynoshow');
    
        box.one('transitionend', function(e) {

            box.addClass('noshow');

        });
    }
}

//New game insert - Set game end time from after start time is set
function game_start() {
    var start = document.getElementById("gamestart_id");
    var end = document.getElementById("gameend_id");

    //alert(start.value);

    //Create date object & add 2 hours
    var dt; //Check chrome, iphone & firefox date differences
    if(start.value.indexOf("T")){
        dt = start.value.split("T"); //Split date&time        
    }
    else {        dt = start.value.split(" "); //Split date&time            }

    var d = dt[0].split("-"); //Spilit year, month, day
    var t = dt[1].split(":"); //Split hour,minute
    var datetime = new Date(d[0], d[1] - 1, d[2], t[0], t[1], 0); //Create date object
    datetime.setHours(datetime.getHours() + 2); //Add 2 hours
    //alert(datetime);

    //Convert Date object back to string, check values below 10 and insert 0 before (Month: January=0...)
    var m, d, h, mm;
    if ((datetime.getMonth() + 1) < 10) m = "0" + (datetime.getMonth() + 1); else m = (datetime.getMonth() + 1);
    if (datetime.getDate() < 10) d = "0" + datetime.getDate(); else d = datetime.getDate();
    if (datetime.getHours() < 10) h = "0" + datetime.getHours(); else h = datetime.getHours();
    if (datetime.getMinutes() < 10) mm = "0" + datetime.getMinutes(); else mm = datetime.getMinutes();
    var dstring = datetime.getFullYear() + "-" + m + "-" + d + " " + h + ":" + mm;

    //alert(dstring);

    end.value = dstring;
}

//New game insert - Check game end time validity
function game_end() {
    //alert("test end");
    var start = document.getElementById("gamestart_id")
    var end = document.getElementById("gameend_id");
    if (start.value > end.value) {
        end.value = start.value;
        //alert("Game's end time must be after start time...");
    }
}

//Player profile
function getPlayerProfile() {

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("profile_content_id").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET gets called.");
	//var variables = "teamid=" + teamid;
	xmlhttp.open("GET", "player_profile.php", false);
	xmlhttp.send();
}

//Chat
function getChat() {
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("chat_content_id").innerHTML = xmlhttp.responseText;

      //      scroll = new iScroll('chatdiv', { vScrollbar: false, hScrollbar:false, hScroll: false });
      //      setTimeout(function(){
			   // scroll.refresh();
		    //});
		}

	}

	//alert("GET gets called.");
	//var variables = "teamid=" + teamid;
	xmlhttp.open("GET", "chat.php", false);
	xmlhttp.send();
}

//Insert comment
function insertComment(comment) {
    //alert("insertComment");
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	        //document.getElementById("comments_table").innerHTML = xmlhttp.responseText;
	        //getChat();
	    }
	}


	var variables = "comment=" + comment;
	//alert(variables);
	//xmlhttp.open("GET", "update_inout.php?" + variables, true);

	xmlhttp.open("GET", "insertComment.php?" + variables, true);

	xmlhttp.send();
}

//Chat dynamic
function addRow() {

    var comment = document.getElementById("comment_input").value;

    //alert("addRow(): photo: " + sessionStorage['photoURL'] + ", name: " + sessionStorage['playerName'] + ", comment " + comment);


    var table = document.getElementById("comments_table");

    var row = table.insertRow(0);
    row.className = "chatrow";
    row.innerHTML = "<td width=\"80px\" height=\"auto\" align=\"center\"><img width=\"50\" height=\"50\"\" class=\"seen\" src=\"images/" +
    sessionStorage['photoURL'] + "\"><br><text style=\"color: white;\">" +
    sessionStorage['playerName'] + "</text></td>" +
    "<td width=\"500px\" height=\"auto\"><text class=\"commentArea1\">Just now...</text><text  maxlength=\"500\" class=\"commentArea2\">" + comment + "</text></td>";

    //document.getElementById("comment_input").value = "";    

    //$("#chatdiv").scrollTop(0);

    setTimeout(insertComment(comment), 1000);

}

//Clear chat input
//function clearComment() {
//    document.getElementById("comment_input").value = "";
//}


//Chat LongPolling////////////////
var parameter = null;

function waitForChat(){

    
    //if(timestamp != null) {
    //    // Split timestamp into [ Y, M, D, h, m, s ]
    //    //var t = timestamp.split(/[- :]/);
    //    // Apply each element to the Date function
    //    //php_datetime = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
    //    //alert("1: timestamp: " + timestamp + ", formatted: " + php_datetime);
    //    
    //    //timestamp.toString();
    //    //timestamp.replace('%20', "T");
    //    //timestamp = timestamp.split(' ').join('T');
    //    //alert("1: timestamp: " + timestamp);
    //}

    //var param = 'timestamp=' + timestamp;
    
    $.ajax({
        type: "GET",
        //url: "getChat.php?timestamp=" + parameter,
        url: "getChat.php",
        data: { timestamp:  JSON.stringify(parameter) },
        async: true,
        cache: false,
        //timeout: 40000,
        //dataType: 'json',
        //processData: false,
        success: function (data) {
            var json = eval('(' + data + ')');

            //Testing
            //if (json['timestamp'] != "") {
            //    //alert("jep: " + json['msg']);
            //alert("success param timestamp: " + timestamp);
            //alert("success timestamp: " + json['timestamp']);
            //}

            //alert("success...");
            setTimeout('getChatComments()', 1000);
            parameter = json['timestamp'];
            setTimeout('waitForChat()', 15000);
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert("error: " + textStatus + " (" + errorThrown + ")");
            setTimeout('waitForChat()', 15000);
        }
    });
            
}

function getChatComments() {
    
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("chatdiv").innerHTML = xmlhttp.responseText;
            scroll = new iScroll('chatdiv', { vScrollbar: false, hScrollbar:false, hScroll: false });
            setTimeout(function(){
			    scroll.refresh();
		    });
		}
	}

	//alert("GET gets called.");
	//var variables = "teamid=" + teamid;
	xmlhttp.open("GET", "comments.php", false);
	xmlhttp.send();
}

function refreshScroll() {
    setTimeout(function(){
	    scroll.refresh();
    });    
}

function toLoginPage() {
    var loginURL = window.location.href;
    loginURL = loginURL.substring(0, loginURL.lastIndexOf('/') + 1);
    loginURL = loginURL + "default.html";
    //alert(loginURL);
    window.location.assign(loginURL);
    
    //window.location.assign("http://m-areyouin.azurewebsites.net/default.html");
    //window.location.assign("http://localhost:18502/default.html")    
}
