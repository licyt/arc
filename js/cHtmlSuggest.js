// Hides and shows options in the datalist according to the value in the visible input
function updateSuggestList(hiddenId,visibleId,listId) {
	var visiblevalue = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName("options"+visibleId);

	// If INPUT field has no characters do fast show/hide
	if( visiblevalue == "" ) {
		for( i = 0; i < dlchildren.length; i++ ) {
			dlchildren[i].disabled = false;
//			dlchildren[i].style.display = 'inline';
		}
	} else { // slow show/hide
		for( i = 0; i < dlchildren.length; i++ ) {
			if( String(dlchildren[i].innerHTML).search(visiblevalue) == 0 ) {
				dlchildren[i].disabled = false;
				document.getElementById(hiddenId).setAttribute("value",dlchildren[i].value);
			} else {
				dlchildren[i].disabled = true;
				document.getElementById(hiddenId).setAttribute("value","-1");
			}
		}
	}
}

// Use for OnFocus event to check all values and to configure 3 tags accordingly
function setupSuggestList(hiddenId,visibleId,listId)
{
	var hiddenvalue = document.getElementById(hiddenId).getAttribute("value");
	var dlchildren = document.getElementsByName("options"+visibleId);
	var visibleText = document.getElementById(visibleId).value;
	
	// clean up "" and make it equal to -1
	if( hiddenvalue == "" ) {
		document.getElementById(hiddenId).setAttribute("value", "-1");
		hiddenvalue = document.getElementById(hiddenId).getAttribute("value");
	}
	
	// enable/disable datalist options according to hiddenvalue
	if( hiddenvalue == -1 ) {
		for( i = 0; i < dlchildren.length; i++ ) {
			dlchildren[i].disable = false;
//			dlchildren[i].style.display = 'inline';
		}
	} else {
		for( i = 0; i < dlchildren.length; i++ ) {
			if( dlchildren[i].value == hiddenvalue ) {
				dlchildren[i].disable = false;
//				dlchildren[i].style.display = 'inline';
				document.getElementById(visibleId).value = dlchildren[i].innerHTML; 
			} else {
				dlchildren[i].disable = true;
//				dlchildren[i].style.display = 'none';
			}
		}
	}
}

// Changes the value in the VISIBLE INPUT from idCompany to companyName   
function sanitizeSuggestValues( visibleId, hiddenId ) {
	var val = document.getElementById(visibleId).value;
	var dlchildren = document.getElementsByName("options"+visibleId);

	for( i = 0; i < dlchildren.length; i++ ) {
		if( dlchildren[i].value == val ) {
			document.getElementById(hiddenId).setAttribute("value",val);
			document.getElementById(visibleId).value = dlchildren[i].innerHTML;
		}
	}
}