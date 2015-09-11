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

        var GameGraphArray  = JSON.parse(data.items);

        //alert("FIRST ROW year/month: " + chartdata[0].month + " Your games: " + chartdata[0].participated + " Games set: " + chartdata[0].games +
        //" SECOND ROW year/month: " + chartdata[1].month + " Your games: " + chartdata[1].participated + " Games set: " + chartdata[1].games);

        //var arr = eval(chartdata);
        //alert(arr);

        return GameGraphArray;
    });

}
