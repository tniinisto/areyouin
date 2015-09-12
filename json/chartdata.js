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

        //alert("FIRST ROW year/month: " + chartdata[0].month + " Your games: " + chartdata[0].participated + " Games set: " + chartdata[0].games +
        //" SECOND ROW year/month: " + chartdata[1].month + " Your games: " + chartdata[1].participated + " Games set: " + chartdata[1].games);

        var games_chart_array = new Array([]);
        //games_chart_array[0][0] = 'Month';
        //games_chart_array[0][1] = 'Your games';
        //games_chart_array[0][2] = 'Games set';

        //games_chart_array.push([]);
        //games_chart_array[1][0] = '2015/09';
        //games_chart_array[1][1] = '1';
        //games_chart_array[1][2] = '10';

        for (i = 0; i < chartdata.length; i++) {
            games_chart_array.push([]);

            if (i == 0)
                games_chart_array[i][0] = '[[' + chartdata[i].month;
            else
                games_chart_array[i][0] = '[' + chartdata[i].month;

            if (chartdata[i].participated == null)
                games_chart_array[i][1] = 0;
            else
                games_chart_array[i][1] = chartdata[i].participated;

            if (chartdata[i].games == null)
                games_chart_array[i][2] = 0 + ']';
            else
                games_chart_array[i][2] = chartdata[i].games + ']';
        }
        games_chart_array = games_chart_array + ']';

        alert(games_chart_array);

        return games_chart_array;

    });

}
