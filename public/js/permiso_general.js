$(".transporte").hide();
$(".requiere_transporte").click (
		function(){
			$('.transporte').find("input").removeAttr("disabled");
			$('.transporte').show();
})
$(".no_requiere_transporte").click (
		function(){
			$('.transporte').find("input").attr("disabled", "disabled");
			$('.transporte').hide();
})
var url = window.location.protocol + "//" + window.location.host + "/sico/" + "bc_permiso/subir_archivo/" + $("table").attr("id");
$('#permiso_general_form .tipo-fecha').datepicker({
    format: "dd/mm/yyyy",
    weekStart: 0,
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

$(".fileupload").change(function() {
	
	var archivo = $(this);
	$(archivo).parent().find('#progress .progress-bar').css(
            "width", "0%"
        );																														
	var formData = new FormData($('#permiso_general_form')[0]);
	    $.ajax( {
	      url: url,
	      type: 'POST',
	      data: formData,
	      processData: false,
	      contentType: false,
	      success: function (data) {
	    	  if(data == "Tipo"){
	    		  alert("El tipo de archivo debe de ser jpg, png, bmp, gif o pdf")
	    	  } else if(data == "Error"){
	    		  alert("Ocurrió un error la subir la imagen");
	    	  } else {
	    		  $(archivo).parent().find('#progress .progress-bar').css(
  	                "width", "100%"
  	            );
	    		  $(archivo).parent().find(".captura").html("Clic para ver");
	    		  $(archivo).parent().find("href").html(window.location.protocol + "//" + window.location.host + "/sico/files/permisos/" + data);
  				  $(archivo).parent().find(".urlArchivo").val(data);
  		        }
	    	  }
	    });
});