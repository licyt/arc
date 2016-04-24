$(function() {
	$(".isDatePick").datepicker({
		showOn : "button",
		buttonImage : "img/calendar.gif",
		buttonImageOnly : true,
		buttonText : "Select date"
	});
	//	$( ".isDatePick" ).datepicker( "option", "showAnim", $( this ).val() );
	$(".isDatePick").datepicker("option", "dateFormat", "yy-mm-dd");
});