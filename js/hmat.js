// JavaScript Document
// 2016 (C) Patrick SiR El Khatim, zayko5@gmail.com 

// detect browser
var browser=navigator.appName
var b_version=navigator.appVersion
var version=parseFloat(b_version)
var winW;
var winH;

window.addEventListener('load', Load, false);

function rowHasChanged(TableName) { 
  hide(TableName+"Delete");									
  show(TableName+"Ok");										
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
  alignAllLanes();
  addDatePickers();
}
