// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 


var xmlHttp;
var timeOn;
var suggestFireFlag;

function Unpack(params) {
  var result="";
  for (name in params) {
    //alert(name+"="+params[name]); // trace parameters
    result=result+"&"+name+"="+params[name];
  }
  return result;
}

function Ajax(request, params) {
  //alert("ajax - "+request); // trace request
  
  // initialize xmlHttp object depending on browser
  // Internet Explorer
  try {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    //alert("Microsoft");
  } catch (e) {
    try {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      //alert("MSXML2");
    } catch (e) {
      try {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
        //alert("Firefox");
      } catch (e) {
        alert("Your browser does not support AJAX!");
        return false;
      }
    }
  }
  
  // assign xmlHttp.onreadystatechange function
  if (xmlHttp) {
    xmlHttp.onreadystatechange=function() {
      switch (xmlHttp.readyState) {
        case 0: //The request is not initialized
          break;
        case 1: // The request has been set up
          switch (request) {
            default:
              break;
          }
          break;
        case 2: // The request has been sent
          break;
        case 3: // The request is in process
          break;
        case 4: // The request is complete
          switch (request) {
            case "alert": // ========================================== alert
              // params[elementId] - id of element which will get focus after alert
              alert(xmlHttp.responseText);
              elementById(params['elementId']).focus();
              break;
            case "browseFile": // =============================================== file browser
              // params[elementId] - id of element which is being browsed
              // params[filePath] - path
              var fileBrowser=elementById("fileBrowser");
              fileBrowser.innerHTML=xmlHttp.responseText;
              if (element=elementById(params['elementId'])) {
	            if (pos=getAbsolutePosition(element)) {
    	          fileBrowser.style.left=(pos.x-8)+"px";
		          fileBrowser.style.top=(pos.y+element.offsetHeight-5)+"px";
		        }
	          }
              show(fileBrowser.id);
              break;
            case "suggestSearch":
            	document.getElementById(params['destinationId']).innerHTML = xmlHttp.responseText;
			  	var dlchildren = document.getElementsByName(params['destinationId']+"Options");
			  	var flag = 0;
			  	for( i = 0; i < dlchildren.length; i++ ) {
				  if( dlchildren[i].text == params['searchString'] ) {
					document.getElementById(params['hiddenId']).setAttribute("value",dlchildren[i].getAttribute("data-value"));
				  	flag = 1;
				  }
			  	}
			  	if( flag == 0 ) {
				  document.getElementById(params['hiddenId']).setAttribute("value","-1");
			  	}
			  break;
            case "loadTable":
              var table=elementById('ActionTable');
              var td0=table.parentElement;
              var td1=td0.nextSibling;
              var td2=td1.nextSibling;
              var pos=xmlHttp.responseText.indexOf('|');
              td1.innerHTML=xmlHttp.responseText.substr(0, pos);  // list of fields
              td2.innerHTML=xmlHttp.responseText.substr(pos+1);   // list of available commands
              break;
            case "loadParameters":
              var par1=elementById('ActionParam1');
              var td1=par1.parentElement;
              var td2=td1.nextSibling;
              var pos=xmlHttp.responseText.indexOf('|');
              td1.innerHTML=xmlHttp.responseText.substr(0, pos);
              td2.innerHTML=xmlHttp.responseText.substr(pos+1);
              break;
            case "loadParam2":
              var par1=elementById('ActionParam1');
              var td1=par1.parentElement;
              var td2=td1.nextSibling;
              td2.innerHTML=xmlHttp.responseText;
              break;
        } // switch request
      } // switch readyState
    } // function
  } // xmlHttp

  // set up and send request via xmlHttp
  URI="ajax.php?"+request+Unpack(params);
  //alert(URI); // trace ajax lauch line
  xmlHttp.open("GET", URI, true);
  xmlHttp.setRequestHeader('Content-Type', 'text/xml; charset=utf-8');
  xmlHttp.send(null);
}

// file browser functions

function updatePath(elementId, path) {
  input = elementById(elementId);
  a = elementById(elementId+"Link");
  oldtext = a.text;
  if (path=="..") {
	lastSlashPos = oldtext.lastIndexOf('/')
	if (lastSlashPos > -1) {
	  a.text = oldtext.slice(0, lastSlashPos);
	} else {
	  a.text = "";
	}
  } else {
	a.text = (oldtext ? oldtext+"/" : "")+path;
  }
  a.href = './datafiles/'+a.text;
  input.value = a.text;
}

function browseFile(element) {
  var params=new Array();
  params['elementId']=element.id;
  params['filePath']=element.value;
  Ajax("browseFile", params);
}

// suggest list functions by tomcat

function suggestList(event, searchType, searchString, tableName, columnName, hiddenId, visibleId, destinationId) {
  var params = new Array();
  
  params['searchType'] = "suggestSearch";
  params['searchString'] = searchString;
  params['tableName'] = tableName;
  params['columnName'] = columnName;
  params['hiddenId'] = hiddenId;
  params['visibleId'] = visibleId;
  params['destinationId'] = destinationId;
 
  // check FireFlag if set means onFocus fired and the onKeyUp should not (avoids flicking)
  if( event.type == "focus" ) {
	suggestFireFlag = 1;
    Ajax("suggestSearch", params);
    document.getElementById(visibleId).select();    
  } 
  if( event.type == "keyup" ) {
    if( suggestFireFlag == 0 && ( isValidKey(event.keyCode) ) ) {
      Ajax("suggestSearch", params);
    } else {
      suggestFireFlag = 0;
    }
  } else {} // needs to be here javascript does not support incomplete if/else statements
}

function isValidKey(key) {
  // SEE KEYCODE TABLE: https://css-tricks.com/snippets/javascript/javascript-keycodes/
  // 8 = backspace, 16 = shift, 32 = space, 46 = delete, 20 = caps lock, 27 = ESC
  // 48-59 = NUMBERS, 65-90 small alphabet, 96-105 numpad
  if( key == 8 || key == 32 || key == 46 ) {
	  return true;
  }
  if( key > 47 && key < 59 ) {
	  return true;
  }
  if( key > 65 && key < 90 ) {
	  return true;
  }
  if( key > 96 && key < 105 ) {
	  return true;
  } 
}

// Action list editor functions

function loadTable() {
  var params=new Array();
  params['table']=elementById("ActionTable").value;
  Ajax("loadTable", params);
}

function loadParameters() {
	  var params=new Array();
	  params['command']=elementById("ActionCommand").value;
	  params['table']=elementById("ActionTable").value;
	  Ajax("loadParameters", params);
	}

function loadParam2() {
	  var params=new Array();
	  params['param1']=elementById("ActionParam1").value;
	  Ajax("loadParam2", params);
	}

