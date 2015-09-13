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
            //document.getElementById("userlogin2").innerHTML = xmlhttp.responseText;
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
//function showUser(str) {
//	//alert("showUser() gets called.");
//	if (str == "") {
//		document.getElementById("userlogin").innerHTML = "showUser()";
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
//			document.getElementById("atable").innerHTML = xmlhttp.responseText;
//		}
//	}

//	//alert("GET gets called.");
//	xmlhttp.open("GET", "database.php?q=" + str, true);
//	xmlhttp.send();
//}

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

//Off&On for the event fetch
var eventFetchPause = 0;
function eventFetchOn() {
    //alert("eventFetchOn called...");
    eventFetchPause = 0;
}
function eventFetchOff() {
    //alert("eventFetchOff called...");
    eventFetchPause = 1;
}

//Get events with players for the team
function getEvents() {
    if (eventFetchPause == 0) { //Don't run, if pause is on

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
    //Set event updating on pause
    eventFetchPause = 1;

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
    var start_dt = document.getElementById("gamestart_id").value;
    var end_dt = document.getElementById("gameend_id").value;

    if ((start_dt > end_dt) && end_dt != "") {
        //alert("Game end time must be after game start...");
        $("#gametime_notify").removeClass("noshow");
        document.getElementById("gamestart_id").value = "";
    }
    else {
        $("#gametime_notify").addClass("noshow");
    }
    
}

//New game insert - Check game end time validity
function game_end() {
    var start_dt = document.getElementById("gamestart_id").value;
    var end_dt = document.getElementById("gameend_id").value;

    if ((start_dt > end_dt) && start_dt != "") {
        //alert("Game end time must be after game start...");
        $("#gametime_notify").removeClass("noshow");
        document.getElementById("gameend_id").value = "";
    }
    else {
        $("#gametime_notify").addClass("noshow");
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

    //This is no synchronous
	xmlhttp.open("GET", "chat.php", false); //Synchronous
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

function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

//Chat dynamic
function addRow() {

    var comment = document.getElementById("comment_input").value;
    comment = nl2br(comment, true);
    //alert("Text: " + comment);

    document.getElementById("comment_input").value = "";

    //alert("addRow(): photo: " + sessionStorage['photoURL'] + ", name: " + sessionStorage['playerName'] + ", comment " + comment);

    var table = document.getElementById("comments_table");

    var row = table.insertRow(0);
    row.className = "chatrow";

    row.innerHTML = "<td valign=\"top\">" +
        "<div>" +
            "<div class='chat-list-left'>" +
                "<img width='50' height='50' src='images/" + sessionStorage['photoURL'] + "'>" +
                "<br />" +
                "<div class='comment-name'>" + sessionStorage['playerName'] + "</div>" +
            "</div>" +
            "<br />" +
            "<div class='chat-list-right'>" +
                "<div class='comment-time'>Just now...</div>" +
                "<div class='comment-text'>" + comment + "</div>" +
            "</div>" +
        "</div>" +
    "</td>";

    //document.getElementById("comment_input").value = "";    

    //$("#chatdiv").scrollTop(0);

    setTimeout(insertComment(comment), 100);



}

//Clear chat input
//function clearComment() {
//    document.getElementById("comment_input").value = "";
//}


//Chat LongPolling////////////////
var parameter = null;
parameter = "1900-01-01 10:10:10";

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
            
            //Get comments only if php not timed out...
            if(json['timeout'] == 0) {
                //alert("success timeout false: " + json['timeout']);
                setTimeout('getChatComments()', 100);
            } 
            //else {
            //    alert("success timeout true: " + json['timeout']);
            //}

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
	xmlhttp.open("GET", "comments.php", true);
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
}

//function sendMail() {
//    alert("sendMail()");
//	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
//		xmlhttp = new XMLHttpRequest();
//	}
//	else {// code for IE6, IE5
//		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
//	}

//	xmlhttp.onreadystatechange = function () {
//	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
//	        //document.getElementById("comments_table").innerHTML = xmlhttp.responseText;
//	        //getChat();
//	    }
//	}

//	//var variables = "comment=" + comment;
//	//alert(variables);
//	
//	xmlhttp.open("POST", "mailer.php", false);

//	xmlhttp.send();
//}


//Combined into getPlayerStats() function
//var playedGamesForTeam = 0;
//function getAllPlayerGames() {

//    var serviceURL = window.location.href;
//    serviceURL = serviceURL.replace("index.html", "/json/");
//    serviceURL = serviceURL.replace("#", '');

//    //alert("getPlayerStats called...url: " + serviceURL);

//    $.getJSON(serviceURL + 'TeamsGames.php', function (data) {

//        playedgames = data.items;
//        playedGamesForTeam = playedgames[0].gamecount;      
//        $('#GamesAmount').text('Total of ' + playedGamesForTeam + ' games ');

//    });

//}


function getPlayerStats() {
    var playedGamesForTeam;

    var serviceURL = window.location.href;
    serviceURL = serviceURL.replace("index.html", "/json/");
    serviceURL = serviceURL.replace("#", '');

    //alert("getPlayerStats called...url: " + serviceURL);

    $.getJSON(serviceURL + 'TeamsGames.php', function (data) {

        playedgames = data.items;
        playedGamesForTeam = playedgames[0].gamecount;      
        $('#GamesAmount').text('Total of ' + playedGamesForTeam + ' games set');

    });

    var playerstats;

    $.getJSON(serviceURL + 'getPlayerStatistics.php', function (data) {

        playerstats = data.items;

        $.each(playerstats, function (index, player) {
            if (player.games > 1) {
                $('#playerwidget').append(
                "<div class='list-row'>" +
                    "<div class='list-left'>" +
                        "<img width='50' height='50' src='images/" + player.photourl + "'>" +
                    "</div>" +
                    "<div class='list-right'>" +
                        "<span class='list-title'>" + player.name + "</span>" +
                        "<br />" +
                        "<span class='gameamountheader'>" + player.games + " played games</span>" +
                        "<br />" +
                        "<meter class='gamemeter' value='" + player.games + "' min='0' max='" + playedGamesForTeam + "'></meter>" +
                        "<br>" +
                    "</div>" +
                "</div>"
                );
            } else {
                    $('#playerwidget').append(
                    "<div class='list-row'>" +
                        "<div class='list-left'>" +
                            "<img width='50' height='50' src='images/" + player.photourl + "'>" +
                        "</div>" +
                        "<div class='list-right'>" +
                            "<span class='list-title'>" + player.name + "</span>" +
                            "<br />" +
                            "<span class='gameamountheader'>" + player.games + " played game</span>" +
                            "<br />" +
                            "<meter class='gamemeter' value='" + player.games + "' min='0' max='" + playedGamesForTeam + "'></meter>" +
                            "<br>" +
                        "</div>" +
                    "</div>"
                );    
            }
        });

    });
}

function showTimezone(str) {
    alert("showTimezone(): " + str);

    if (str.length == 0) { 
        document.getElementById("txtZone").innerHTML = "No selection";
        return;
    } else {
        //var d = new Date()
        //var n = d.getTimezoneOffset();
        
        document.getElementById("txtZone").innerHTML = str;
        
        //UTC difference to local timezone: " + n/60 + " hours";        
        //document.getElementById("timezone_offset").innerHTML = n/60;

        return;
    }
}


//Update update timezone
function updateTimezone(timezone)
{
	//alert("updateTimezone() gets called.");

    if (timezone == "") {
		document.getElementById("txtZone").innerHTML = "updateTimezone() no parameters";
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
            //alert("updateTimezone() returned successfully.");
            //document.getElementById("txtZone").innerHTML = 'Timezone set succesfully: ' + timezone;            
            document.getElementById("team_timezone").innerHTML = "Team's current timezone is: " + timezone;   
	    }
	}


	var variables = "timezone=" + timezone;
	//alert(variables);
	xmlhttp.open("GET", "update_team.php?" + variables, true);
	xmlhttp.send();
}

//Check session expiration
function CheckForSession() {
    $.ajax({
        type: "GET",
        url: "check_session.php",
        async: true,
        cache: false,
        success: function (data) {
            if (data == "0") {
                //alert('Your session has been expired! ' + data);
                toLoginPage();
            }
            else {
                //alert('Session active! ' + data);
                setTimeout('CheckForSession()', 1000);
            }
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert("error: " + textStatus + " (" + errorThrown + ")");
            setTimeout('CheckForSession()', 1000);
        }
    });
}


/**********************************************************************************
Google Chart
**********************************************************************************/
//Google chart data
function getChartData() {

        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("event_content_id").innerHTML = xmlhttp.responseText;
                dataTable = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "chart_data.php", false);
        xmlhttp.send();

}

//callback function
function createChart(animate) {

    //create data table object
    //var dataTable = new google.visualization.DataTable();

    //define columns
    //dataTable.addColumn('string', 'Quarters 2009');
    //dataTable.addColumn('number', 'Earnings');

    //define rows of data
    //dataTable.addRows([['Q1', 308], ['Q2', 257], ['Q3', 375], ['Q4', 123]]);

    var arr = new Array();
    arr = getChartData();
    //arr = typeof arr == 'string' ? [arr] : arr;

    dataTable = new google.visualization.arrayToDataTable(arr);

    ////Template
    //dataTable = google.visualization.arrayToDataTable([
    //        ['Month', 'Your games', 'Games set'],
    //        ['2004/05', 565, 614.6],
    //        ['2005/06', 635, 652],
    //        ['2006/07', 557, 623],
    //        ['2007/08', 539, 609.4],
    //        ['2008/09', 536, 569.6],
    //        ['2008/10', 536, 569.6],
    //        ['2008/11', 536, 569.6],
    //        ['2008/12', 536, 569.6]
    //    ]);

    //instantiate our chart object
    var chart = new google.visualization.ComboChart(document.getElementById('profile_chart_content_id'));

    //define options for visualization
    if (animate == 1) {
        var options = {
            //width: 600,
            //height: 500,
            is3D: true,
            title: 'Your game history',
            vAxis: { title: 'Games' },
            hAxis: { title: 'Month', showTextEvery: '2' },
            seriesType: 'bars',
            series: { 0: { type: 'line'} },
            legend: { position: 'right' },
            animation: {
                duration: 1000,
                easing: 'out',
                startup: 'true'
            }
        };
    } else {
        var options = {
            //width: 600,
            //height: 500,
            is3D: true,
            title: 'Your game history',
            vAxis: { title: 'Games' },
            hAxis: { title: 'Month', showTextEvery: '2' },
            seriesType: 'bars',
            series: { 0: { type: 'line'} },
            legend: { position: 'rigth' },
            animation: false
        }
    }


    //draw our chart
    chart.draw(dataTable, options);

}


//Draw the chart with animation
function drawChart() {
    setTimeout(function () {
        createChart(1);
    }, 200);
}

