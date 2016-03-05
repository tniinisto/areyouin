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
function getEvents(more) {    
    if (eventFetchPause == 0) { //Don't run, if pause is on
        startSpinner();
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        more = typeof more !== 'undefined' ? more : 0;
        var moreid = "more_events_content" + more;

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (more != 0) {                    
                    document.getElementById("more_events_content" + more).innerHTML = xmlhttp.responseText;
                    stopSpinner();
                    $('#' + moreid).scrollintoview({ duration: 500 });
                    
                }
                else {
                    stopSpinner();
                    document.getElementById("event_content_id").innerHTML = xmlhttp.responseText;                    
                }
            }            
        }

        //alert("GET gets called.");
        var variables = "more=" + more;
        xmlhttp.open("GET", "event_list.php?" + variables, false);
        //xmlhttp.open("GET", "event_list.php", false);
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

	document.getElementById(summary_id).innerHTML = "Event status: " + value + " / " + value2;
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

    //var eventarticle = "#event_article_" + 1 2 3 jne.;
    //eventarticle = $(eventarticle);

    if (box.hasClass('noshow')) {
    
        //eventarticle.addClass('event_article_animate');
        box.removeClass('noshow');
        
        $(id).scrollintoview({duration: 300});
        setTimeout(function () {
            box.removeClass('visuallynoshow');
        }, 20);

    } else {
    
        box.addClass('visuallynoshow');
    
        box.one('transitionend', function(e) {
            //eventarticle.removeClass('event_article_animate')
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

//Check password change
function check_pass() {

    if (document.getElementById("dialog_password1").value != document.getElementById("dialog_password2").value) {
        document.getElementById("dialog_password2").value = "";
    }

}

//Clear password fields when form opened
function initPassForm() {
    document.getElementById("dialog_password1").value = '';
    document.getElementById("dialog_password2").value = '';
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

    //This is not synchronous
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

	    }
	}

	var variables = "comment=" + comment;
	//alert(variables);
	//xmlhttp.open("GET", "update_inout.php?" + variables, true);

	xmlhttp.open("GET", "insertComment.php?" + variables, false);

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
	        scroll = new iScroll('chatdiv', { vScrollbar: false, hScrollbar: false, hScroll: false });
	        setTimeout(function () {
	            scroll.refresh();
	        });

	        //Update the message icon
	        checkMsgStatus();
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
    var playedGamesForTeam= 100;

    var serviceURL = window.location.href;
    serviceURL = serviceURL.replace("index.html", "/json/");
    serviceURL = serviceURL.replace("#", '');

    //alert("getPlayerStats called...url: " + serviceURL);

    $.getJSON(serviceURL + 'TeamsGames.php', function (data) {

        playedgames = data.items;
        playedGamesForTeam = playedgames[0].gamecount;      
        $('#GamesAmount').text('Total of ' + playedGamesForTeam + ' events set');

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
                        "<span class='gameamountheader'>In for " + player.games + " events</span>" +
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
                            "<span class='gameamountheader'>In for " + player.games + " event</span>" +
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
            document.getElementById("team_timezone_value").innerHTML = timezone;   
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

        xmlhttp.open("GET", "chart_data.php", true);
        xmlhttp.send();

}

//callback function
function createChart(animate) {
   
        chartdata= $.ajax({
                url: "json/getChartData.php",
                dataType: "json",
                async: false
            }).responseText;


        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('string', 'Month');
        dataTable.addColumn('number', 'You');
        dataTable.addColumn('number', 'Events');

        //dataTable.addRow(['Elokuu', 1, 1]);

        var dataArray = $.parseJSON(chartdata);
        //alert(dataArray[0].month);

        for (i = 0; i < dataArray.length; i++) {
            //var arr = new Array(chartdata[i].month, chartdata[i].participated, chartdata[i].participated);

            dataTable.addRow([dataArray[i].month, ((dataArray[i].participated != null) ? Number(dataArray[i].participated) : 0) , ((dataArray[i].games != null) ? Number(dataArray[i].games) : 0)]);

            //dataTable.addRow(arr);
        }

    //dataTable.addRows([
    //  ['CN', 1324, 9640821],
    //  ['IN', 1133, 3287263],
    //  ['US', 304, 9629091],
    //  ['ID', 232, 1904569],
    //  ['BR', 187, 8514877]
    //]);

    //Template
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
            title: 'Events & Your activity',
            vAxis: { title: 'Events' },
            hAxis: { title: 'Month',
                     slantedText: 'true', slantedTextAngle: 75 },
            seriesType: 'bars',
            series: { 0: { type: 'line'} },
            legend: { position: 'right' },
            animation: {
                duration: 1000,
                easing: 'out',
                startup: 'true'
            },
            colors:['blue','orange']
        };
    } else {
        var options = {
            //width: 600,
            //height: 500,
            is3D: true,
            title: 'Events & Your activity',
            vAxis: { title: 'Events' },
            hAxis: { title: 'Month',
                     slantedText: 'true', slantedTextAngle: 75 },
            seriesType: 'bars',
            series: { 0: { type: 'line'} },
            legend: { position: 'rigth' },
            animation: false,
            colors:['blue','orange']
        }
    }


    //draw our chart
    chart.draw(dataTable, options);

}


//Draw the chart with animation
function drawChart() {
    document.getElementById('profile_chart_content_id').innerHTML = "";

    setTimeout(function () {
        createChart(1);
    }, 200);
}

//Rain/////////////////////////////////////////////////////////////////
// number of drops created.
var nbDrop = 258; 

// function to generate a random number range.
function randRange( minNum, maxNum) {
  return (Math.floor(Math.random() * (maxNum - minNum + 1)) + minNum);
}

// function to generate drops
function createRain() {

	for( i=1;i<nbDrop;i++) {
	var dropLeft = randRange(0, screen.width - 150);
	var dropTop = randRange(-40000, 0);

	$('.rain').append('<div class="drop" id="drop'+i+'"></div>');
	$('#drop'+i).css('left',dropLeft);
	$('#drop'+i).css('top',dropTop);
	}

}

//Spinner/////////////////////////////////////////////////////////////////////////////////////
var spinner;

function initSpinner() {
            
    var opts = {
        lines: 15 // The number of lines to draw
        , length: 2 // The length of each line
        , width: 4 // The line thickness
        , radius: 10 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#fff' // #rgb or #rrggbb or array of colors
        , opacity: 0.25 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , top: '50%' // Top position relative to parent
        , left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'fixed' // Element positioning
    }
        
    spinner = new Spinner(opts);
}

function startSpinner() {
    var target = document.getElementById('spinner_id');
    spinner.spin(target);
}

function stopSpinner() {
    spinner.stop();
}

//Spinner/////////////////////////////////////////////////////////////////////////////////////


function getWeather() {

        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("weather_content_id").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "weather.php", true);
        xmlhttp.send();

}


//Location, google maps////////////////////////////////////////////////////////////////////////////////////

function initializeMap() {

//Geolocation/////////////////////////////////////////////////
var nlat = 0, nlon = 0;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
    } 
    else {
      //alert('geolocation not supported');
    }

    function success(position) {
        alert(position.coords.latitude + ', ' + position.coords.longitude);
        nlat = position.coords.latitude;
        nlon = position.coords.longitude;
    }

    function error(msg) {
      alert('Geolocation error: ' + msg);
    }


    //Google maps/////////////////////////////////////////////////
    var mapCanvas = document.getElementById('Location_map');

    if (nlat != 0) {
        var mapOptions = {
            center: new google.maps.LatLng(nlat, nlon),
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    } else {
        var mapOptions = {
            center: new google.maps.LatLng(60,387, 23,134),
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    }

    var map = new google.maps.Map(mapCanvas, mapOptions);

    //Touch functionality for Maps//
    function MapTouch() {
        return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
    }
    
    if (MapTouch() === true) {
        navigator = navigator || {};
        navigator.msMaxTouchPoints = navigator.msMaxTouchPoints || 2;
    }
    ///////////////////////////////

    $('#Location_map').on('pageshow', function(){
        google.maps.event.trigger(canvas-map, "resize");
    });

    google.maps.event.addListener(map, 'click', function(event) {
       placeMarker(event.latLng);
       alert('<p>Marker dropped: Current Lat: ' + event.latLng.lat().toFixed(3) +
        ' Current Lng: ' + event.latLng.lng().toFixed(3) + '</p>');
    });


    function placeMarker(location) {
        var marker = new google.maps.Marker({
            position: location, 
            map: map
        });
    }

}


//Message icon, update latest message time to db/////////////////////////////////////////////
function updateLastMsgTime() {

	 //Clear msg notification icon
     clearIcon();

    //Latest message date on list
    var msgdatetime = document.getElementById("latestMsg").textContent;
    
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

	    }
	}

    var variables = "datetime=" + msgdatetime;
    //alert(variables);
	xmlhttp.open("GET", "UpdateLastMsgDate.php?" + variables, true);
	xmlhttp.send();	

}

//Showing the chat message icon /////////////////////////////////////////////////
function checkMsgStatus() {
    
    //Latest message datetime on list
    //var msgdatetime = new Date(document.getElementById("latestMsg").textContent).getTime();
    //var msgdatetime = new Date(document.getElementById("latestMsg").textContent);
    var arr = document.getElementById("latestMsg").textContent.split(/[- :]/);
    var msgdatetime = new Date(arr[0], arr[1], arr[2], arr[3], arr[4], arr[5]);
    
    //Latest message datetine user has seen
    //var seenmsgdatetime = new Date(document.getElementById("latestSeenMsg").textContent).getTime();
    //var seenmsgdatetime = new Date(document.getElementById("latestSeenMsg").textContent);
    arr = document.getElementById("latestSeenMsg").textContent.split(/[- :]/);
    var seenmsgdatetime = new Date(arr[0], arr[1], arr[2], arr[3], arr[4], arr[5]);

    //alert("latest on list:" + msgdatetime + "\nlast seen:" + seenmsgdatetime);
    //alert("latest on list: " + document.getElementById("latestMsg").textContent + 
    //      "\nlast seen: " + document.getElementById("latestSeenMsg").textContent);

    //Show icon if there are newer messages and chat view is not active
    if((msgdatetime <= seenmsgdatetime) || $("#linkchat").hasClass("current")) {
    //if((msgdatetime.localeCompare(seenmsgdatetime) == 0) || $("#linkchat").hasClass("current")) {
        $("#msg_icon").addClass("noshow");
    }
    else {       
        $("#msg_icon").removeClass("noshow");
    }             
}

//Clear icon
function clearIcon() {
    $("#msg_icon").addClass("noshow");
}

//Email validation////////////////////////////////////////////////////////////////////////////////
function validateEmail(mail) {
    //alert(mail);

    //Get the current mail address, return it to field if new one is invalid
    var currentMail = document.getElementById("latestMsg").textContent;
    
    //Validate entered mail address
    if(checkEmail(mail)) {
        alert("email valid");
    }
    else {
        alert("email not valid");
    }

}

//Validate address 
function checkEmail(mail) {
    var patt = new RegExp("/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;");
    return patt.test(mail);
}
