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
  show(TableName+"Cancel");
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
  // this will prevent the form submit on keypress
  $(document).on("keypress", 'form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
  });
  // synchronize columns in header and data tables
  $(".header").each(function() {
    var tableName = $(this).attr("id").substring(11);
    alignDataToHeader(tableName);
  })
  $(".tabBody").on("scroll", function(e) {
    var tableName = $(this).attr("id").substring(7);
    var left = $(this).scrollLeft();
    elementById("tabHead"+tableName).scrollLeft = left;
  })
  //dynamic height with window resize
  $(window).resize(function() {
    var el = elementById("tabBodyAdmin");
    el.style.height = (window.innerHeight-100)+"px";
  })
  $(window).trigger('resize');
}
