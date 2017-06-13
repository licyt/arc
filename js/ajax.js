// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 


var xmlHttp;
var timeOn;

function Unpack(params) {
  var result="";
  for (name in params) {
    //alert(name+"="+params[name]); // trace parameters
    result=result+(params[name] ? "&"+name+"="+params[name] : "");
  }
  return result;
}

function httpRequest(request, params) {
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
                var rect = element.getBoundingClientRect();
                fileBrowser.style.left=rect.left;
  		          fileBrowser.style.top=rect.top+20;
  		        }
              show(fileBrowser.id);
              break;
            case "suggestSearch":
    			  	document.getElementById(params['destinationId']).innerHTML = xmlHttp.responseText;
              document.getElementById(params['hiddenId']).setAttribute("value","-1");
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
              if (sbRowIndex >= 0) table.deleteRow(sbRowIndex);
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
             //newSbRow.style.display = "none";
              currentRowId = params["newRowId"];
              elementById("id"+params["tableName"]).value = currentRowId;
              //$("#id"+params["tableName"]).val(currentRowId);
              addDatePickers();
              if (input=document.getElementById("StatusColor")) {
                var picker = new jscolor(input);
              }
              alignColumns();
              break;
            case "switchTab":
              //alert(xmlHttp.responseText);
              response = JSON.parse(xmlHttp.responseText);
              currentRowId = response.currentRecordId;
              var table=elementById("table"+params["tableName"]);
              var sbRowName = params["tableName"]+"Sb"+currentRowId;
              table.rows.namedItem(sbRowName).innerHTML = response.subBrowser;
              addDatePickers();
              alignColumns();
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
  return -1;
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
  httpRequest("browseFile", params);
}

// suggest list functions

function loadSuggestList(event, searchType, searchString, tableName, columnName, hiddenId, visibleId, destinationId) {
  dlchildren = document.getElementsByName(destinationId+"Options");
  if (dlchildren.length>0) return true;
  
  var params = new Array();
  
  params['searchType'] = "suggestSearch";
  params['searchString'] = searchString;
  params['tableName'] = tableName;
  params['columnName'] = columnName;
  params['hiddenId'] = hiddenId;
  params['visibleId'] = visibleId;
  params['destinationId'] = destinationId;
 
  httpRequest("suggestSearch", params);
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
  httpRequest("loadTable", params);
}

function loadParameters() {
  var params=new Array();
  params['command']=elementById("ActionCommand").value;
  params['table']=elementById("ActionTable").value;
  httpRequest("loadParameters", params);
}

function loadRightRows() {
  var params=new Array();
  params['table']=elementById("RelationRObject").value;
  httpRequest("loadRightRows", params);
}

function loadLeftRows() {
  var params=new Array();
  params['table']=elementById("RelationLObject").value;
  httpRequest("loadLeftRows", params);
}




var currentRowId = -1;

function loadRow(parentName, tableName, newRowId) {
  //alert(parentName+" "+tableName+" "+oldRowId+" "+newRowId);
  CancelEdit(tableName);
  var params=new Array();
  params['parentName']=parentName;
  params['tableName']=tableName;
  params['newRowId']=newRowId;
  httpRequest("loadRow", params);
}

function switchTab(tableName, tabName) {
  //alert(tableName+" "+tabName);
  var params=new Array();
  params['tableName']=tableName;
  params['tabName']=tabName;
  httpRequest("switchTab", params);
}









function ajaxPost(tableName, parentName) {
  $.post(
      "ajax.php?submitRow&tableName="+tableName+"&parentName="+parentName, 
      $("#browseForm"+tableName).serialize(),
      function (result) {
        response = JSON.parse(result);
        var newRowId = response.newRowId;
        var newRowName = tableName+"Row"+newRowId;
        if (response.oldRowId==-1) {
          $("#id"+tableName).val(newRowId);
          elementById(tableName+"Row-1").id = newRowName;
          show(tableName+"Insert");
          hide(tableName+"Cancel");
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
        alignDataToHeader(tableName);
      }
  );
}

function ajaxInsert(tableName, parentName) {
  var newRow = elementById("table"+tableName).insertRow(0);
  newRow.id = tableName+"Row-1";
  $.post(
      "ajax.php?insertRow&tableName="+tableName+"&parentName="+parentName, 
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
        if (sbRowIndex >= 0) table.deleteRow(sbRowIndex);
        // new row for insert
        $("#"+tableName+"Row"+$("#id"+tableName).val()).html(response.oldRow);
        newRow.innerHTML = response.newRow;
        newRow.setAttribute('onkeypress', response.onKeyPress);
        $("#id"+tableName).val(-1);
        hide(tableName+"Insert");
        show(tableName+"Ok");
        show(tableName+"Cancel");
        addDatePickers();
        if (input=document.getElementById("StatusColor")) {
          var picker = new jscolor(input);
        }
        alignDataToHeader(tableName);
      }
  );
}

function CancelEdit(tableName) {
  table = elementById("table"+tableName);
  var rowIndex = getRowIndex(table, tableName+"Row-1");
  if (rowIndex>=0) table.deleteRow(rowIndex);
  show(tableName+"Insert");
  show(tableName+"Delete");
  hide(tableName+"Erase");
  hide(tableName+"Cancel");
  hide(tableName+"Ok");
  $("#browseForm"+tableName)[0].reset();
  alignDataToHeader(tableName);
}

function ajaxDelete(tableName) {
  hide(tableName+"Delete");
  show(tableName+"Cancel");
  show(tableName+"Erase");
}

function ajaxErase(tableName) {
  $.post(
      "ajax.php?deleteRow&tableName="+tableName, 
      function (result) {
        response = JSON.parse(result);
        table = elementById("table"+tableName);
        // old row
        var oldRowName = tableName+"Row"+response.oldRowId;
        var oldRowIndex = getRowIndex(table, oldRowName);
        if (oldRowIndex >= 0) table.deleteRow(oldRowIndex);
        var sbRowName = tableName+"Sb"+response.oldRowId;
        var sbRowIndex = getRowIndex(table, sbRowName);
        if (sbRowIndex >= 0) table.deleteRow(sbRowIndex);
        show(tableName+"Delete");
        hide(tableName+"Cancel");
        hide(tableName+"Erase");
        alignDataToHeader(tableName);
      }
  );
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




// --------------------------------------------------- data structure manipulation, column Menu

function dbSelect($dbName) {
  if ($dbName == "Unknown") {
    elementById("dbName").style.display = "none";
    elementById("newDbName").style.display = "inline";
    elementById("createDb").style.display = "inline";
    elementById("cancelDb").style.display = "inline";
    elementById("newDbName").focus();
  } else {
    elementById("FORM_DB_SELECT").submit();
  }
}

function dbCancel() {
  hide('newDbName');
  hide('createDb');
  hide('cancelDb');
  show('dbName');
}

function dbCreate($dbName) {
  
}

function tableList(event) {
  $.post(
      "ajax.php?tableList",
      function (result) {
        response = JSON.parse(result);
        var list = elementById("popupMenu");
        list.innerHTML = response.tableList;
        list.style.left = event.clientX+"px";
        list.style.top = event.clientY+"px";
        show("popupMenu");
      }
  );
}

function tableDialog(event, tableName) {
  $.post(
    "ajax.php?tableDialog",
    {
      "tableName": tableName
    },
    function (result) {
      response = JSON.parse(result);
      var tableDialog = elementById("popupMenu");
      tableDialog.innerHTML = response.tableDialog;
      tableDialog.style.left = event.clientX+"px";
      tableDialog.style.top = event.clientY+"px";
      show("popupMenu");
      if (input=document.getElementById("tableColor")) {
        var picker = new jscolor(input);
      }   
    }
  ); 
}

function tableSave() {
  $.post(
    "ajax.php?tableSave",
    {
      "tableName": elementById("tableName").value,
      "level": elementById("buttonLevel").value,
      "sequence": elementById("buttonSequence").value,
      "tableColor": elementById("tableColor").value,
      "lookupField": elementById("lookupField").value
    },
    function (result) {
      response = JSON.parse(result);
      $("#tabSwitchAdmin").submit();
   }
  ); 
}

function columnMenu(event, tableName, columnName) {
  $.post(
      "ajax.php?columnMenu",
      {
        "tableName": tableName, 
        "columnName": columnName
      },
      function (result) {
        //alert(result);
        response = JSON.parse(result);
        var popupMenu = elementById("popupMenu");
        popupMenu.innerHTML = response.columnMenu;
        popupMenu.style.left = event.clientX+"px";
        popupMenu.style.top = event.clientY+"px";
        show("popupMenu");
     }
  ); 
}

function addColumn(event, tableName, columnName) {
  $.post(
      "ajax.php?addColumn",
      {
        "tableName": tableName, 
        "columnName": columnName
      },
      function (result) {
        response = JSON.parse(result);
        var popupMenu = elementById("popupMenu");
        popupMenu.innerHTML = response.columnEditor;
        popupMenu.style.left = event.clientX+"px";
        popupMenu.style.top = event.clientY+"px";
        show("popupMenu");
     }
  ); 
}

function addLookup(event, tableName, columnName) {
  $.post(
      "ajax.php?addLookup",
      {
        "tableName": tableName, 
        "columnName": columnName
      },
      function (result) {
        response = JSON.parse(result);
        var popupMenu = elementById("popupMenu");
        popupMenu.innerHTML = response.lookupEditor;
        popupMenu.style.left = event.clientX+"px";
        popupMenu.style.top = event.clientY+"px";
        show("popupMenu");
     }
  ); 
}

function changeColumn(event, tableName, columnName) {
  $.post(
    "ajax.php?changeColumn",
    {
      "tableName": tableName, 
      "columnName": columnName
    },
    function (result) {
      response = JSON.parse(result);
      var popupMenu = elementById("popupMenu");
      popupMenu.innerHTML = response.columnEditor;
      popupMenu.style.left = event.clientX+"px";
      popupMenu.style.top = event.clientY+"px";
      show("popupMenu");
   }
  ); 
}

function moveColumn(event, tableName, columnName) {
  $.post(
    "ajax.php?moveColumn",
    {
      "tableName": tableName, 
      "columnName": columnName
    },
    function (result) {
      response = JSON.parse(result);
      elementById("tabBodyAdmin").innerHTML = response.browser;
   }
  ); 
}

function deleteColumn(event, tableName, columnName) {
  hide("popupMenu");
  if (confirm("Permanently delete column "+columnName+" from table "+tableName+"?"+"\r\nWarning! Data will be lost!")) {
    $.post(
      "ajax.php?deleteColumn",
      {
        "tableName": tableName, 
        "columnName": columnName
      },
      function (result) {
        response = JSON.parse(result);
        elementById("tabBodyAdmin").innerHTML = response.browser;
      }
    ); 
  }
}

function confirmColumn() {
  hide('popupMenu');
  $.post(
      "ajax.php?confirmColumn",
      {
        "displayedName": elementById("displayedName").value,
        "columnName": elementById("columnName").value,
        "dataType": elementById("dataType").value,
        "columnWidth": elementById("columnWidth").value
      },
      function (result) {
        response = JSON.parse(result);
        elementById("tabBodyAdmin").innerHTML = response.browser;        
      }
  );
}

function confirmLookup() {
  hide('popupMenu');
  $.post(
      "ajax.php?confirmLookup",
      {
        "lookupTable": elementById("lookupTable").value,
      },
      function (result) {
        response = JSON.parse(result);
        elementById("tabBodyAdmin").innerHTML = response.browser;        
      }
  );
}

// drag and drop files 

function doDrop(tableName, parentName, event) {
  // process the drop
  event.preventDefault();
  console.log("drop to " + tableName + (parentName ? " @ " + parentName : ""));
  // If dropped items aren't files, reject them
  
  // START A LOADING SPINNER HERE

  // handle the file upload in its own AJAX request
  // Create a formdata object and add the files
  var files = event.dataTransfer.files;
  var data = new FormData();
  
  for (var i = 0; i < files.length; i++) {
    data.append(files[i].name, files[i]);
  }
  
//Display the values
  for (var value of data.values()) {
     console.log(value); 
  }
  
  // pass that data as a request to the server
  $.ajax({
      url: 'ajax.php?uploadFiles&tableName='+tableName+'&parentName='+parentName,
      type: 'POST',
      data: data,
      cache: false,
      dataType: 'json',  // response data type
      processData: false, // Don't process the files
      contentType: false, // Set content type to false as jQuery would tell the server its a query string request
      success: function(response, textStatus, jqXHR)
        {
          console.log('STATUS: ' + textStatus);
          table = elementById("table"+tableName);
          for (var property in response) {
            var row = table.insertRow(0);
            row.id = property;
            row.innerHTML = response[property]['html'];
            row.setAttribute('onclick', response[property]['onClick']);
          }
        },
      error: function(jqXHR, textStatus, errorThrown)
        {
          // Handle errors here
          console.log('ERRORS: ' + errorThrown);
          // STOP LOADING SPINNER
        }
  });
}

