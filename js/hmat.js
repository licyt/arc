// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 

// detect browser
var browser=navigator.appName
var b_version=navigator.appVersion
var version=parseFloat(b_version)
var winW;
var winH;

function myLoad() {
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