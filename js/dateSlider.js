var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
$("#dateSlider").dateRangeSlider(
  { 
    bounds: {
      min: new Date(2016, 4, 1),
      max: new Date(2016, 11, 1)  
    },
    defaultValues: {
      min: new Date(2016, 9, 1), 
      max: new Date(2016, 10, 15)
    }, 
    scales: [{
      first: function(value){ return value; },
      end: function(value) {return value; },
      next: function(value){
        var next = new Date(value);
        return new Date(next.setMonth(value.getMonth() + 1));
      },
      label: function(value){
        return months[value.getMonth()];
      }
    }]
  }
);
$("#dateSlider").on("valuesChanging", function(e, data){
  //console.log("Something moved. min: " + data.values.min + " max: " + data.values.max);
  alignAllLanes(data.values.min, data.values.max);
});