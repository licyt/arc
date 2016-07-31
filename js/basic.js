/**
 * 
 */

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