$(function() {
	$(".isDatePick").each(function() {
		var $indicator = $(this).prop("disabled");
		var $date = $(this).attr("value");
		if ($indicator == false) {
			$(this).datepicker({
				showOn : "both",
				buttonImage : "img/calendar.gif",
				buttonImageOnly : true,
				buttonText : "Calendar",
				showAnim : "slide",
			});
			$(this).datepicker("option", "dateFormat", "yy-mm-dd");
			$(this).datepicker( "setDate", $date );
		}
	});
	$(".isDateTimePick").each(function() {
		var $indicator = $(this).prop("disabled");
		var $datetime = $(this).attr("value");
		if ($indicator == false) {
			$(this).datetimepicker({
				showOn : "both",
				buttonImage : "img/calendar.gif",
				buttonImageOnly : true,
				buttonText : "Calendar",
				showAnim : "slide",
				timeFormat: 'HH:mm:ss',
				showMillisec: false,
				timeInput: true,
				addSliderAccess: true,
				sliderAccessArgs: { touchonly: false }
			});
			$(this).datetimepicker("option", "dateFormat", "yy-mm-dd");
			$(this).datetimepicker( "setDate", $datetime );
			$(this).datetimepicker( "setTime", $datetime );
		}
	});
});