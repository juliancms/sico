var festivos = $("#festivos").html().split(',');
$('#cronogramahcb_form .tipo-fecha').datepicker({
    format: "dd/mm/yyyy",
    datesDisabled: festivos,
    weekStart: 0,
    autoclose: false,
		multidate: true,
    startDate: $('#fecha_inicio').val(),
    endDate: $('#fecha_fin').val(),
    language: "es",
    daysOfWeekDisabled: "0,6"
});

//initialize input widgets first
$('.hora input').timepicker({
    'showDuration': true,
    'timeFormat': 'g:iA',
    'minTime': '8:00am',
    'maxTime': '6:00pm',
    'step': 15
});

// initialize datepair
$('form').datepair();
$(".empleadocheck").change(function() {
		 var ischecked= $(this).is(':checked');
		 if(!ischecked) {
			 $(this).parent().parent().find(".tipo-fecha").attr("disabled", "disabled");
		 } else {
			 $(this).parent().parent().find(".tipo-fecha").removeAttr("disabled");
		 }

});
