/**
 * 
 */

function elementById(id) {
  return document.getElementById(id);
}

function show(id) {
  if (el=elementById(id)) el.style.display="inline-block";
}

function hide(id) {
  if (el=elementById(id)) el.style.display="none";
}

function toggleDisplay(id) {
  if (el=elementById(id)) {
    if (el.style.display=='') {
      el.style.display='none';
    } else {
      el.style.display='';
    }
  }
}

function stopEvent (event) {
  event = event || window.event // cross-browser event
  if (event.stopPropagation) { // W3C standard variant
    event.stopPropagation()
  } else { // IE variant
    event.cancelBubble = true
  }
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
