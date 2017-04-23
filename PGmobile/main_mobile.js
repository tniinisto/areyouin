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
                    $('#' + moreid).scrollintoview({ duration: 1000 });
                    
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
// function updateAYI(eventplayerid, ayi, eventid, switchid)
// {
// 	//alert("updateAYI() gets called.");
//     //alert(switchid);
//     if (eventplayerid == "" || ayi == "") {
// 		document.getElementById("userlogin").innerHTML = "updateAYI()";
// 		return;
// 	}
// 	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
// 		xmlhttp = new XMLHttpRequest();
// 	}
// 	else {// code for IE6, IE5
// 		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
// 	}
// 	xmlhttp.onreadystatechange = function () {
// 	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
// 	        //alert(xmlhttp.responseText);
// 	        //getEvents(); //Update events
// 	    }
// 	}
// 	//Update the summary count to client when in/out switch is clicked///////////////////////////
// 	var summary_id = "id_summary" + eventid;
// 	var th = document.getElementById(summary_id).innerHTML;
// 	//alert(th);
// 	var start = th.indexOf(":")
// 	var end = th.indexOf("/")
// 	var value = th.substring(start + 1, end);
// 	value = value.trim();
// 	//alert(value);
// 	//alert(ayi);
//     var switch_id = "myonoffswitch" + switchid;
// 	//var sw = document.getElementById(switch_id).innerHTML;
// 	//if (ayi == 0) {
//     if(document.getElementById(switch_id).checked == false) {
// 	    //alert("ayi 0");
//         //document.getElementById(switch_id).checked = false;
//         ayi = 0;
//         value--;
// 	}
// 	else {
//         //alert("ayi 1");
// 	    //document.getElementById(switch_id).checked = true;
//         ayi = 1;
//         value++;
// 	}
// 	var start2 = th.indexOf("/")
// 	var value2 = th.substr(start2 + 1);
// 	value2 = value2.trim();
// 	document.getElementById(summary_id).innerHTML = "Players IN: " + value + " / " + value2;
//     //////////////////////////////////////////////////////////////////////////////////////////////////
// 	var variables = "event=" + eventplayerid + "&ayi=" + ayi;
// 	//alert(variables);
// 	xmlhttp.open("GET", "update_inout.php?" + variables, true);
// 	xmlhttp.send();
// }

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

    //var eventarticle = "event_article_" + eventid;
    
    if (box.hasClass('noshow')) {
    
        box.removeClass('noshow');
        $(id).scrollintoview({duration: 300});
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
    //var start = document.getElementById("gamestart_id");
    //var end = document.getElementById("gameend_id");
    ////alert(start.value);
    ////Create date object & add 2 hours
    //var dt; //Check chrome, iphone & firefox date differences
    //if(start.value.indexOf("T")){
    //    dt = start.value.split("T"); //Split date&time        
    //}
    //else {
    //    dt = start.value.split(" "); //Split date&time        
    //}
    //var d = dt[0].split("-"); //Spilit year, month, day
    //var t = dt[1].split(":"); //Split hour,minute
    //var datetime = new Date(d[0], d[1] - 1, d[2], t[0], t[1], 0); //Create date object
    //datetime.setHours(datetime.getHours() + 2); //Add 2 hours
    ////alert(datetime);
    ////Convert Date object back to string, check values below 10 and insert 0 before (Month: January=0...)
    //var m, d, h, mm;
    //if ((datetime.getMonth() + 1) < 10) m = "0" + (datetime.getMonth() + 1); else m = (datetime.getMonth() + 1);
    //if (datetime.getDate() < 10) d = "0" + datetime.getDate(); else d = datetime.getDate();
    //if (datetime.getHours() < 10) h = "0" + datetime.getHours(); else h = datetime.getHours();
    //if (datetime.getMinutes() < 10) mm = "0" + datetime.getMinutes(); else mm = datetime.getMinutes();
    //var dstring = datetime.getFullYear() + "-" + m + "-" + d + " " + h + ":" + mm;
    ////alert(dstring);
    //end.value = dstring;
}

//New game insert - Check game end time validity
function game_end() {
    ////alert("test end");
    //var start = document.getElementById("gamestart_id")
    //var end = document.getElementById("gameend_id");
    //if (start.value > end.value) {
    //    end.value = start.value;
    //    //alert("Game's end time must be after start time...");
    //}
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

function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

//Chat dynamic
function addRow() {
    var comment = document.getElementById("comment_input").value;
    comment = nl2br(comment, true);
    //alert("addRow(): photo: " + sessionStorage['photoURL'] + ", name: " + sessionStorage['playerName'] + ", comment " + comment);
    var table = document.getElementById("comments_table");
    var row = table.insertRow(0);
    row.className = "chatrow";
    //row.innerHTML = "<td width=\"80px\" height=\"auto\" align=\"center\"><img width=\"50\" height=\"50\"\" class=\"seen\" src=\"images/" +
    //sessionStorage['photoURL'] + "\"><br><text style=\"color: white;\">" +
    //sessionStorage['playerName'] + "</text></td>" +
    //"<td width=\"500px\" height=\"auto\"><text class=\"commentArea1\">Just now...</text><text  maxlength=\"500\" class=\"commentArea2\">" + comment + "</text></td>";
                                    row.innerHTML = "<td valign=\"top\">" +
                                        "<div>" +
                                            "<div class='chat-list-left'>" +
                                                "<img width='50' height='50' src='http://areyouin.azurewebsites.net/images/" + sessionStorage['photoURL'] + "'>" +
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
    $("#backpacker").trigger("click");
    setTimeout(insertComment(comment), 100);
}

function clickBack() {
    //alert("jou");
    //document.getElementById('backpacker').click();
    //$("#backpacker").trigger("click");
    $('#areyouin-modal-page').dialog('close');
    //$("#areyouin-modal-page").dialog('destroy').remove();
    //$( "#modal_comment" ).dialog( "close" );
    //$(":mobile-pagecontainer").pagecontainer("change", "#areyouin-chat-page", { options });
    //$.mobile.pageContainer.pagecontainer ("change", "#areyouin-chat-page", {reloadPage: true});
    //$.mobile.changePage("#areyouin-chat-page");
    //$.mobile.pageContainer.pagecontainer ("change", "#areyouin-chat-page", {reloadPage: false});
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

            //Update the message icon
	        checkMsgStatus();
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

    if (navigator.app && navigator.app.exitApp) {
        navigator.app.exitApp();
    } else if (navigator.device && navigator.device.exitApp) {
        navigator.device.exitApp();
    } else {

        var loginURL = window.location.href;
        loginURL = loginURL.substring(0, loginURL.lastIndexOf('/') + 1);
        loginURL = loginURL + "default.php";
    
        window.location.assign(loginURL);      
    }  

    // var loginURL = window.location.href;
    // loginURL = loginURL.substring(0, loginURL.lastIndexOf('/') + 1);
    // loginURL = loginURL + "default.php";
    
    // //alert(loginURL);

    // window.location.assign(loginURL);
    // //window.location.assign("http://m-areyouin.azurewebsites.net/default.php");
    // //window.location.assign("http://localhost:18502/default.php")    
}

function toEvents() {
    //window.location.assign("<a href=\"#areyouin-events-page\"></>");
    //$("#main-nav").children().removeClass("current");
    //$("#linkgames").addClass("current");
    //$("body").pagecontainer("change", "#areyouin-events-page", {reloadPage: true});
    //window.location.assign("<a getEvents();\"></a>");
    //$.mobile.changePage(index.html#areyouin-events-page);
    getEvents();
    window.location.assign("index.html");
    //$("body").pagecontainer("change", "#areyouin-events-page", {reloadPage: true});
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
    if((msgdatetime <= seenmsgdatetime) || ($.mobile.activePage.attr('id') == 'areyouin-chat-page')) {
        $("#msg_icon1").addClass("noshow");
        $("#msg_icon2").addClass("noshow");
    }
    else {       
        $("#msg_icon1").removeClass("noshow");
        $("#msg_icon2").removeClass("noshow");
    }             
}

//Clear icon
function clearIcon() {
    $("#msg_icon1").addClass("noshow");
    $("#msg_icon2").addClass("noshow");
}
