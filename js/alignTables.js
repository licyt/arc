/*
// Reposition the content to be below the fixed header
var heightHeader = $(".header").outerHeight();
$(".content").css("margin-top",heightHeader);
*/

function alignDataToHeader(tableName) {
  // This is the final layout for the table (biggest column wins)
  var arrLayout = [];
  
  // Get the column widths in the first row in the "header" table
  $("#tableHeader"+tableName+" tr:first").find("th").each(function() {
      var index = $(this).index();
      var width = $(this).outerWidth(true);
      arrLayout[index] = width;
  });
  
  // Get the column widths in the first row in the "data" table
  $("#table"+tableName+" tr:first").find("td").each(function() {
      var index = $(this).index();
      var width = parseInt(this.style.width);
      if (width) {
        arrLayout[index] = width;
      } else {
        width = $(this).outerWidth(true);
        // Override the final layout if this column is bigger
        if(width > arrLayout[index]) {
            arrLayout[index] = width;
        }
      }
  });

  // Summarize the final table width
  var widthSum = 0;
  for(var i=0; i < arrLayout.length; i++) {
      widthSum += arrLayout[i];
  }
          
  // Set the new width to the two tables        
  $("#tableHeader"+tableName).css({"width":widthSum});
  $("#table"+tableName).css({"width":widthSum});
  
  // Set the new widths on the columns (both tables)
  for(var i=0; i < arrLayout.length; i++) {
      $("#tableHeader"+tableName+" tr:first th:eq("+i+")").css({"width":arrLayout[i]});
      $("#table"+tableName+" tr:first td:eq("+i+")").css({"min-width":arrLayout[i], "width":arrLayout[i], "max-width":arrLayout[i]});
  }
}

function alignColumns() {
  // align columns in header and data tables
  $(".header").each(function() {
    var tableName = $(this).attr("id").substring(11);
    alignDataToHeader(tableName);
  })
  $(".tabBody").on("scroll", function(e) {
    var tableName = $(this).attr("id").substring(7);
    var left = $(this).scrollLeft();
    elementById("tabHead"+tableName).scrollLeft = left;
  })
}
