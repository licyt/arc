
var suggestFireFlag;

// lookup list search


// Changes the value in the VISIBLE INPUT from idCompany to companyName   
function sanitizeSuggestValues( hiddenId , visibleId) {
	var val = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName(hiddenId+"Option");

	for( i = 0; i < dlchildren.length; i++ ) {
		if( dlchildren[i].text == val ) {
			document.getElementById(hiddenId).setAttribute("value",dlchildren[i].getAttribute("data-value"));
		}
	}
}

