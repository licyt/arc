// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 


var xmlHttp;
var timeOn;
var suggestFireFlag;


function Unpack(params) {
  var result="";
  for (name in params) {
    //alert(name+"="+params[name]); // trace parameters
    result=result+(params[name] ? "&"+name+"="+params[name] : "");
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
            case "loadRightRows":
              var table=elementById('RelationRObject');
              var td0=table.parentElement;
              var td1=td0.nextSibling;
              td1.innerHTML=xmlHttp.responseText;  
              break;
            case "loadLeftRows":
              var table=elementById('RelationLObject');
              var td0=table.parentElement;
              var td1=td0.nextSibling;
              td1.innerHTML=xmlHttp.responseText;  
              break;
            case "loadGantt":
              var gantt=elementById('gantt');
              gantt.innerHTML=xmlHttp.responseText;
              break;
            case "loadRow":
              response = JSON.parse(xmlHttp.responseText);
              var table=elementById("table"+params["tableName"]);
              var oldRowName = params["tableName"]+"Row"+response.oldRowId;
              if (oldRow = table.rows.namedItem(oldRowName)) {
                oldRow.innerHTML = response.oldRow;
                oldRow.setAttribute('onclick', response.onClick);
              }
              var sbRowName = params["tableName"]+"Sb"+response.oldRowId;
              var sbRowIndex = getRowIndex(table, sbRowName)
              if (sbRowIndex > 0) table.deleteRow(sbRowIndex);
              var newRowName = params["tableName"]+"Row"+params["newRowId"];
              var newRowIndex = getRowIndex(table, newRowName);
              if (newRow = table.rows.namedItem(newRowName)) {
                newRow.innerHTML = response.newRow;
                newRow.setAttribute('onClick', response.onEditClick);
                newRow.setAttribute('onKeyPress', response.onKeyPress);
              }
              var newSbRow = table.insertRow(newRowIndex+1);
              newSbRow.id = params["tableName"]+"Sb"+params["newRowId"];
              newSbRow.setAttribute("class", "subBrowserRow");
              newSbRow.innerHTML = response.subBrowser;
              currentRowId = params["newRowId"];
              $("#id"+params["tableName"]).val(currentRowId);
              addDatePickers();
              break;
            case "switchTab":
              response = JSON.parse(xmlHttp.responseText);
              currentRowId = response.currentRecordId;
              var table=elementById("table"+params["tableName"]);
              var sbRowName = params["tableName"]+"Sb"+currentRowId;
              table.rows.namedItem(sbRowName).innerHTML = response.subBrowser;
              addDatePickers();
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

function getRowIndex(table, rowId) {
  var index=0;
  while (index < table.rows.length) {
    if (table.rows[index].id == rowId) return index;
    index++;
  }
  return null;
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

function loadRightRows() {
  var params=new Array();
  params['table']=elementById("RelationRObject").value;
  Ajax("loadRightRows", params);
}

function loadLeftRows() {
  var params=new Array();
  params['table']=elementById("RelationLObject").value;
  Ajax("loadLeftRows", params);
}

var currentRowId = -1;

function loadRow(parentName, tableName, newRowId) {
  //alert(parentName+" "+tableName+" "+oldRowId+" "+newRowId);
  CancelInsert(tableName);
  var params=new Array();
  params['parentName']=parentName;
  params['tableName']=tableName;
  params['newRowId']=newRowId;
  Ajax("loadRow", params);
}

function switchTab(tableName, tabName) {
  //alert(tableName+" "+tabName);
  var params=new Array();
  params['tableName']=tableName;
  params['tabName']=tabName;
  Ajax("switchTab", params);
}

function ajaxPost(tableName) {
  $.post(
      "ajax.php?submitRow&tableName="+tableName, 
      $("#browseForm"+tableName).serialize(),
      function (result) {
        response = JSON.parse(result);
        var newRowId = response.newRowId;
        var newRowName = tableName+"Row"+newRowId;
        if (response.oldRowId==-1) {
          $("#id"+tableName).val(newRowId);
          //$("#"+tableName+"Row-1").attr("id", newRowName); // does not work, replaced by next line
          elementById(tableName+"Row-1").id = newRowName;
          $("#"+tableName+"Insert").show();
          $("#"+tableName+"Cancel").hide();
          // subBrowsers of new row
          table = elementById("table"+tableName);
          var sbRowName = tableName+"Sb"+newRowId;
          if (!(sbRow=table.rows.namedItem(sbRowName))) {
            var newRowIndex = getRowIndex(table, newRowName);
            var sbRow = table.insertRow(newRowIndex+1);
            sbRow.id = sbRowName;
          }
          sbRow.innerHTML = response.subBrowser;
          $("#"+newRowName).attr("onclick", response.onEditClick);
        }
        $("#"+newRowName).html(response.newRow);
      }
  );
}

function ajaxInsert(tableName) {
  var newRow = elementById("table"+tableName).insertRow(1);
  newRow.id = tableName+"Row-1";
  $.post(
      "ajax.php?insertRow&tableName="+tableName, 
      function (result) {
        response = JSON.parse(result);
        table = elementById("table"+tableName);
        // old row
        var oldRowName = tableName+"Row"+response.oldRowId;
        if (oldRow = table.rows.namedItem(oldRowName)) {
          oldRow.innerHTML = response.oldRow;
          oldRow.setAttribute('onclick', response.onClick);
        }
        // subBrowsers of old row
        var sbRowName = tableName+"Sb"+response.oldRowId;
        var sbRowIndex = getRowIndex(table, sbRowName);
        if (sbRowIndex > 0) table.deleteRow(sbRowIndex);
        // new row for insert
        $("#"+tableName+"Row"+$("#id"+tableName).val()).html(response.oldRow);
        newRow.innerHTML = response.newRow;
        $("#id"+tableName).val(-1);
        $("#"+tableName+"Insert").hide();
        elementById(tableName+"Cancel").style.display="inline-block";
        //$("#"+tableName+"Cancel").show();
        addDatePickers();
      }
  );
}

function CancelInsert(tableName) {
  table = elementById("table"+tableName);
  var rowIndex = getRowIndex(table, tableName+"Row-1");
  if (rowIndex>0) table.deleteRow(rowIndex);
  $("#"+tableName+"Insert").show();
  $("#"+tableName+"Cancel").hide();
}

function ajaxDelete(tableName) {
  if (confirm("Delete?")) {
    $.post(
        "ajax.php?deleteRow&tableName="+tableName, 
        function (result) {
          response = JSON.parse(result);
          table = elementById("table"+tableName);
          // old row
          var oldRowName = tableName+"Row"+response.oldRowId;
          var oldRowIndex = getRowIndex(table, oldRowName);
          if (oldRowIndex>0) table.deleteRow(oldRowIndex);
          var sbRowName = tableName+"Sb"+response.oldRowId;
          var sbRowIndex = getRowIndex(table, sbRowName);
          if (sbRowIndex > 0) table.deleteRow(sbRowIndex);
        }
    );
  }
}

function jumpToRow(idRelation, relationDirection) {
  $.post(
      "ajax.php?jumpToRow",
      {
        "idRelation": idRelation, 
        "relationDirection": relationDirection
      },
      function (result) {
        //alert(result);
        response = JSON.parse(result);
        // new table browser!
        $("#tabBodyAdmin").html(response.browser); 
        $('#'+response.rowId)[0].scrollIntoView(true);
      }
  ); 
}

