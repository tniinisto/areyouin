//$(window).load(function () {
//    //Already done in playerinfo.js
//    //serviceURL = window.location.href;
//    //serviceURL = serviceURL.replace("index.html", "json/");
//    //serviceURL = serviceURL.replace("#", '');
//    //localStorage['serviceURL'] = serviceURL;

//    setTimeout(getChartData, 100);
//});

$(document).ajaxError(function(event, request, settings) {
	//$('#busy').hide();
	//alert("Error accessing the server");
});

function getChartData() {
	//$('#busy').show();

    $.getJSON(serviceURL + 'getChartData.php', function (data) {

        chartdata = data.items;

        //alert("FIRST ROW month/year: " + chartdata[0].month + "/" + chartdata[0].year + " Your games: " + chartdata[0].participated  + " Games set: " + chartdata[0].games +
        //" SECOND ROW month/year: " + chartdata[1].month+ "/" + chartdata[1].year  + " Your games: " + chartdata[1].participated  + " Games set: " + chartdata[1].games);

        alert("FIRST ROW year/month: " + chartdata[0].month + " Your games: " + chartdata[0].participated + " Games set: " + chartdata[0].games +
        " SECOND ROW year/month: " + chartdata[1].month + " Your games: " + chartdata[1].participated + " Games set: " + chartdata[1].games);

        return chartdata;

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
