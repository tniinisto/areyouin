$(window).load(function () {
    serviceURL = window.location.href;
    serviceURL = serviceURL.replace("index.html", "json/");
    serviceURL = serviceURL.replace("#", '');

    setTimeout(getChartData, 100);
});

$(document).ajaxError(function(event, request, settings) {
	//$('#busy').hide();
	//alert("Error accessing the server");
});

function getChartData() {
	//$('#busy').show();

    $.getJSON(serviceURL + 'getChartData.php', function (data) {

        chartdata = data.items;


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


    });

}
