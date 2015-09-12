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

        alert("FIRST ROW year/month: " + chartdata[0].month + " Your games: " + chartdata[0].participated + " Games set: " + chartdata[0].games +
        " SECOND ROW year/month: " + chartdata[1].month + " Your games: " + chartdata[1].participated + " Games set: " + chartdata[1].games);

        var games_chart_array = new array();

        for (i = 0; i < chartdata.length; i++) {
            games_chart_array[i][0] = chartdata[i].month;
            games_chart_array[i][1] = chartdata[i].participated;
            games_chart_array[i][1] = chartdata[i].games;
        }

        alert(games_chart_array);

        return games_chart_array;

    });

}
