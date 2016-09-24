
function clear_field (field) {
    $(field).val('');
}

function previousStep () {
    $('#first_step').slideUp();  
    $('#second_step').slideDown();
}

function getTimezones() {
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("timezones").innerHTML = xmlhttp.responseText;
		}
	}

	//alert("GET ge7ts called.");
	//var variables = "p=" + playerID + "&t=" + teamID;
	//var variables = "p=1&t=1";
	//alert(variables);
	//xmlhttp.open("GET", "logininfo.php?" + variables, false);
	//alert("jou");
    xmlhttp.open("GET", "timezones.php", false);
	xmlhttp.send();
}
