	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			
			stopSpinner();
			
			document.getElementById("timezones").innerHTML = xmlhttp.responseText;
			
			document.getElementById("submit_third").style.visibility="visible";

		}
	}


	startSpinner();
    xmlhttp.open("GET", "js/timezones.php", false);
	xmlhttp.send();
