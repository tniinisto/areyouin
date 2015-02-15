/**
 * @author tniinis
 */

//LocalStorage
var serviceURL;

//LOCAL site, localhost
//localStorage['serviceURL'] = "http://localhost:18502/json/";
//var serviceURL = localStorage['serviceURL'];

//MOBILE site, m-areyouin
//localStorage['azureserviceURL'] = "http://m-areyouin.azurewebsites.net/json/";
//var serviceURL = localStorage['azureserviceURL'];

//DEV site, dev-areyouin
//localStorage['azureserviceURL'] = "http://dev-areyouin.azurewebsites.net/json/";
//var serviceURL = localStorage['azureserviceURL'];

//scroll = new iScroll('wrapper', { vScrollbar: false, hScrollbar:false, hScroll: false });

var playerinfo;

$(window).load(function () {
    serviceURL = window.location.href;
    serviceURL = serviceURL.replace("index.html#", "json/");
    localStorage['serviceURL'] = serviceURL;

    //localStorage['serviceURL'] = "http://localhost:18502/json/";
    alert(serviceURL);

    setTimeout(getPlayerInfo, 100);
});

$(document).ajaxError(function(event, request, settings) {
	//$('#busy').hide();
	//alert("Error accessing the server");
});

function getPlayerInfo() {
	//$('#busy').show();

    $.getJSON(serviceURL + 'getplayerinfo.php', function (data) {

        playerinfo = data.items;

        alert("playerinfo, name: " + playerinfo[0].name);

        sessionStorage['playerID'] = playerinfo[0].playerID;
        sessionStorage['playerName'] = playerinfo[0].name;
        sessionStorage['photoURL'] = playerinfo[0].photourl;
        sessionStorage['teamName'] = playerinfo[0].teamName;
        
        document.getElementById("userlogin1").innerHTML = "Welcome " + sessionStorage['playerName'] + " team " + sessionStorage['teamName'];

        //$.each(playerinfo, function(index, player) {
        //    alert("playerinfo, name: " + player.name);
        //});

        //$('#busy').hide();
        //      $('#playerList li').remove();
        //players = data.items;
        //$.each(players, function(index, player) {
        //	$('#playerList').append(
        //			'<li>' +
        //			'<img src="pics/' + player.photourl + '" class="list-icon"/>' +
        //			// '<p class="line1">' + player.playerID + '</p>' +
        //			'<p class="line1">' + player.name + '</p>' +
        //			'</li>');
        //});

        //setTimeout(function(){
        //	scroll.refresh();
        //});

    });

}

