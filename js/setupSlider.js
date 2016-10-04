/**
 * 
 */

$('.nstSlider').nstSlider({
      "left_grip_selector": ".leftGrip",
      "right_grip_selector": ".rightGrip",
      "value_bar_selector": ".bar",
      "highlight": {
      "panel_selector": ".highlightPanel"
    },
    "value_changed_callback": function(cause, leftValue, rightValue) {
      var ld = new Date();
      var rd = new Date();
      ld.setTime(leftValue*1000);
      rd.setTime(rightValue*1000);
      $('.leftLabel').text(ld.toDateString());
      $('.rightLabel').text(rd.toDateString());
      /*
      var params=new Array();
      params['leftValue'] = leftValue;
      params['rightValue'] = rightValue;
      Ajax('loadGantt', params);
      */
      alignAllLanes(ld.toDateString(), rd.toDateString());
    },
});

// Call methods and such...
var highlightMin = Math.random() * 20,
    highlightMax = highlightMin + Math.random() * 80;
$('.nstSlider').nstSlider('highlight_range', highlightMin, highlightMax);
