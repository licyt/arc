// Hides and shows options in the datalist according to the value in the visible input
function updateSuggestList(hiddenId,visibleId,listId) {
	var visiblevalue = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName(visibleId+"Options");

	// If INPUT field has no characters do fast show/hide
	if( visiblevalue == "" ) {
		for( i = 0; i < dlchildren.length; i++ ) {
			dlchildren[i].style.display = 'inline';
		}
	} else { // slow show/hide
		for( i = 0; i < dlchildren.length; i++ ) {
			if( String(dlchildren[i].text).search(visiblevalue) == 0 ) {
				dlchildren[i].style.display = 'inline';
				document.getElementById(hiddenId).setAttribute("value",dlchildren[i].getAttribute("data-value"));
			} else {
				dlchildren[i].style.display = 'none';
				document.getElementById(hiddenId).setAttribute("value","-1");
			}
		}
	}
	document.getElementById(visibleId).setAttribute("value",visiblevalue);
	sanitizeSuggestValues(hiddenId,visibleId,listId);
}

// Use for OnClick event to check all values and to configure 3 tags accordingly
function setupSuggestList(hiddenId,visibleId,listId)
{
	var hiddenvalue = document.getElementById(hiddenId).getAttribute("value");
	var dlchildren = document.getElementsByName(visibleId+"Options");
	var visibleText = document.getElementById(visibleId).value;
	
	// clean up "" and make it equal to -1
	if( hiddenvalue == "" ) {
		document.getElementById(hiddenId).setAttribute("value", "-1");
		hiddenvalue = document.getElementById(hiddenId).getAttribute("value");
	}
	
	// enable/disable datalist options according to hiddenvalue
	if( hiddenvalue == -1 ) {
		for( i = 0; i < dlchildren.length; i++ ) {
			dlchildren[i].style.display = 'inline';
		}
	} else {
		for( i = 0; i < dlchildren.length; i++ ) {
			if( dlchildren[i].value == hiddenvalue ) {
				dlchildren[i].style.display = 'inline';
				document.getElementById(visibleId).value = dlchildren[i].text; 
			} else {
				dlchildren[i].style.display = 'none';
			}
		}
	}
}

// Changes the value in the VISIBLE INPUT from idCompany to companyName   
function sanitizeSuggestValues( hiddenId , visibleId ,listId ) {
  value = elementById(listId).getAttribute("data-value");
  elementById(hiddenId).setAttribute("value", value);
  /* 
	var val = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName(listId+"Options");

	for( i = 0; i < dlchildren.length; i++ ) {
		if( dlchildren[i].text == val ) {
			document.getElementById(hiddenId).setAttribute("value",dlchildren[i].getAttribute("data-value"));
//			document.getElementById(visibleId).setAttribute("value",dlchildren[i].text);
		}
	}
  */
}

function sanitizeSuggestList( listId ) {
  document.getElementById(listId).innerHTML = "";
}