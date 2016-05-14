// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 


var xmlHttp;
var timeOn;

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
//            case "suggest":
//              if(searchType == "fullsearch") {
//				document.getElementById(params['datalistId']).innerHTML = request.responseText;
//			  }
//			  if(searchType == "idsearch") {
//				var cutOfLastChar = String(request.responseText);
//				cutOfLastChar = cutOfLastChar.substr(0,cutOfLastChar.length-1);
//				document.getElementById(callerId).setAttribute("value", cutOfLastChar);
//				document.getElementById(callerId).value = cutOfLastChar;
//			  }
//			  break;
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