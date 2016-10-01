
	var spinner3;        
    var opts = {
        lines: 15 // The number of lines to draw
        , length: 8 // The length of each line
        , width: 4 // The line thickness
        , radius: 15 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#fff' // #rgb or #rrggbb or array of colors
        , opacity: 0.50 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        //, top: '20px' // Top position relative to parent
        //, left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'fixed' // Element positioning
    }
        
    spinner3 = new Spinner(opts);

	// if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	// 	xmlhttp = new XMLHttpRequest();
	// }
	// else {// code for IE6, IE5
	// 	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	// }

	// xmlhttp.onreadystatechange = function () {
	// 	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			
	// 		stopSpinner3();
    //         document.getElementById("mailcheck_info").style.visibility="hidden";	
	//     }
	// }

	document.getElementById("mailcheck_info").style.visibility="visible";
    startSpinner3();

    var variables = "mail=" + document.getElementById("email").value;
    $.getJSON("js/checkmail.php?" + variables, function (data) {

        playerinfo = data.items;

        alert("mailcheck count" + playerinfo[0].count);
        
    });

    //document.getElementById("userlogin1").innerHTML = values...

    //xmlhttp.open("GET", "js/checkmail.php?" + variables, false);
	//xmlhttp.send();

function startSpinner3() {
    var target = document.getElementById('spinner_id3');
    spinner3.spin(target);
}

function stopSpinner3() {
    spinner3.stop();
}


	
