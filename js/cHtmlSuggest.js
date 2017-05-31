
var suggestFireFlag;

// lookup list search


// Hides and shows options in the datalist according to the value in the visible input
function updateSuggestList(hiddenId,visibleId) {
	var visiblevalue = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName(hiddenId+"ListOptions");

	for( i = 0; i < dlchildren.length; i++ ) {
	  if( visiblevalue == "" ) {
      dlchildren[i].style.display = 'inline';
    } else {
			if( String(dlchildren[i].text).search(visiblevalue) == 0 ) {
				dlchildren[i].style.display = 'inline';
				if (dlchildren[i].text == visiblevalue) {
  				document.getElementById(hiddenId).setAttribute("value",dlchildren[i].getAttribute("data-value"));
				}
			} else {
				dlchildren[i].style.display = 'none';
				//document.getElementById(hiddenId).setAttribute("value","-1");
			}
		}
	}
	//document.getElementById(visibleId).setAttribute("value",visiblevalue);
	//sanitizeSuggestValues(hiddenId,visibleId);
}

// Changes the value in the VISIBLE INPUT from idCompany to companyName   
function sanitizeSuggestValues( hiddenId , visibleId, listId  ) {
	var val = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName(hiddenId+"ListOptions");

	for( i = 0; i < dlchildren.length; i++ ) {
		if( dlchildren[i].text == val ) {
			document.getElementById(hiddenId).setAttribute("value",dlchildren[i].getAttribute("data-value"));
			//document.getElementById(visibleId).setAttribute("value",dlchildren[i].text);
		}
	}
}


function sanitizeSuggestList( listId ) {
  //document.getElementById(listId).innerHTML = "";
}