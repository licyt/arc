// example of how to create stylesheet using CSS DOM
var sheet = document.createElement('style')
sheet.innerHTML = "div {border: 2px solid black; background-color: blue;}";
document.body.appendChild(sheet);

// remove the stylesheet
var sheetToBeRemoved = document.getElementById('styleSheetId');
var sheetParent = sheetToBeRemoved.parentNode;
sheetParent.removeChild(sheetToBeRemoved);

// get stylesheet by title
function getStyleSheet(unique_title) {
  for(var i=0; i<document.styleSheets.length; i++) {
    var sheet = document.styleSheets[i];
    if(sheet.title == unique_title) {
      return sheet;
    }
  }
}

// add or remove rule
stylesheet.insertRule(".have-border { border: 1px solid black;}", 0);