/**
 * @author tniinis
 */

localStorage['serviceURL'] = "http://localhost:18502/json/";
var serviceURL = localStorage['serviceURL'];

//localStorage['azureserviceURL'] = "http://dev-areyouin.azurewebsites.net/json/";
//var serviceURL = localStorage['azureserviceURL'];

//scroll = new iScroll('wrapper', { vScrollbar: false, hScrollbar:false, hScroll: false });

//var playerinfo; -> global main.js

$(window).load(function() {
	setTimeout(getPlayerInfo, 100);
});

$(document).ajaxError(function(event, request, settings) {
	//$('#busy').hide();
	alert("Error accessing the server");
});

function getPlayerInfo() {
	//$('#busy').show();

    $.getJSON(serviceURL + 'getplayerinfo.php', function (data) {

        playerinfo = data.items;
        
        $.each(playerinfo, function(index, player) {
            alert("playerinfo, name: " + player.name);
        });


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

