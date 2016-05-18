// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 

// detect browser
var browser=navigator.appName
var b_version=navigator.appVersion
var version=parseFloat(b_version)
var winW;
var winH;

window.addEventListener('load', Load, false);

function rowHasChanged(el) { 
  // split camelCase string to words via regex 				insert spaces in-between
  FieldName = el.id.replace(/([a-z](?=[A-Z]))/g, '$1 '); 	
  // get the first word from the string						BUG! there are tables with names from more words
  TableName = FieldName.substr(0, FieldName.indexOf(' '));	 	
  hide(TableName+"Delete");									
  show(TableName+"Ok");										
  el.style.border = "1px solid red;";
}

function Load() {
  // determine size of browser window
  if (parseInt(navigator.appVersion)>3) {
    if (browser=="Netscape") {
      winW = window.innerWidth;
      winH = window.innerHeight;
    }
    if (browser.indexOf("Microsoft")!=-1) {
      winW = document.body.offsetWidth;
      winH = document.body.offsetHeight;
      setInterval("document.recalc()",100);
    }
  }
  // swapOkDel();
}


function elementById(id) {
  return document.getElementById(id);
}

function show(id) {
  if (el=elementById(id)) el.style.display="block";
}

function hide(id) {
  if (el=elementById(id)) el.style.display="none";
}

function getAbsolutePosition(element) {
  if (element) {
	  var coord={x:element.offsetLeft, y:element.offsetTop};
	  if (element.offsetParent) {
	    var tmp=getAbsolutePosition(element.offsetParent);
	    coord.x+=tmp.x;
	    coord.y+=tmp.y;
	  }
	  return coord;
	}
}