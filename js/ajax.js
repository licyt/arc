// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 


var xmlHttp;
var timeOn;

function Unpack(params) {
  var result="";
  for (name in params) {
    //alert(name+"="+params[name]); // trace parameters
    result=result+"&param"+name+"="+params[name];
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
              // params[0] - id of element which will get focus after alert
              alert(xmlHttp.responseText);
              elementById(params[0]).focus();
              break;
            case "browseFile": // =============================================== file browser
              // params[0] - id of element which is being browsed
              // params[1] - path
              var fileBrowser=elementById("fileBrowser");
              fileBrowser.innerHTML=xmlHttp.responseText;
              if (element=elementById(params[0])) {
	            if (pos=getAbsolutePosition(element)) {
		          if (pos.x>winW/2) {
		          	fileBrowser.style.left=(pos.x+element.offsetWidth-
		           	fileBrowser.offsetWidth)+"px";
		           	fileBrowser.style.textAlign="right";
		          } else {
		           	fileBrowser.style.left=pos.x+"px";
		            fileBrowser.style.textAlign="left";
		          }
		              /*
		              if (pos.y>winH/2) {
		                fileBrowser.style.top=(pos.y-help.offsetHeight-3)+"px";
		              } else {
		                fileBrowser.style.top=(pos.y+element.offsetHeight+3)+"px";
		              }
		              */
		          fileBrowser.style.top=(pos.y+element.offsetHeight+3)+"px";
		        }
	          }
              show(fileBrowser.id);
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





function browseFile(element) {
  //clearTimeout(timeHelp);
  var params=new Array();
  params[0]=element.id;
  params[1]=element.value;
  Ajax("browseFile", params);
}
