/**
 * 
 */

function alignAllLanes() {
  var lanes = document.getElementsByClassName("ganttLane");
  var i;
  for (i = 0; i < lanes.length; i++) {
    alignLane(lanes[i]);
  }
}

function alignLane(lane) {
  var firstTitle = lane.firstChild.title;
  var lastTitle = lane.lastChild.title;
  var startDate = new Date(firstTitle.slice(0, firstTitle.indexOf("->")));
  var endDate = new Date(lastTitle.slice(lastTitle.lastIndexOf("->")+2));
  var iDuration = endDate.getTime() - startDate.getTime();
  var iRatio = lane.clientWidth / iDuration;
  var i;
  for (i = 0; i < lane.children.length; i++) {
    var title = lane.children[i].title;
    var startTime = new Date(title.slice(0, title.indexOf("->")));
    var endTime = new Date(title.slice(title.lastIndexOf("->")+2));
    var duration = endTime.getTime() - startTime.getTime();
    lane.children[i].style.left = Math.round(iRatio * (startTime-startDate.getTime()));
    lane.children[i].style.width = Math.round(iRatio * duration);
  }
}