var tipo;
$("select#tipo_permiso").change(function(){
    var select = $("select#tipo_permiso").val();
    if(select == 0){
        $('#fecha').parent().parent().fadeIn();
        $('#fecha').removeAttr("disabled");
        $('#fecha_inicio_permiso').parent().parent().fadeOut();
        $('#fecha_fin_permiso').parent().parent().fadeOut();
        $('#fecha_inicio_permiso').attr("disabled", "disabled");
        $('#fecha_fin_permiso').attr("disabled", "disabled");
        $('.dias_permiso').parent().fadeOut();
        $('.dia').attr("disabled", "disabled");
    } else {
    	$('#fecha').parent().parent().fadeOut();
    	$('#fecha').attr("disabled", "disabled");
        $('#fecha_inicio_permiso').parent().parent().fadeIn();
        $('#fecha_inicio_permiso').removeAttr("disabled");
        $('#fecha_fin_permiso').parent().parent().fadeIn();
        $('#fecha_fin_permiso').removeAttr("disabled");
        $('.dias_permiso').parent().fadeIn();
        $('.dia').removeAttr("disabled");
    }
});
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
var festivos = $("#festivos").html().split(',');
$('#permiso_general_form .tipo-fecha').datepicker({
    format: "dd/mm/yyyy",
    datesDisabled: festivos,
    weekStart: 0,
    autoclose: true,
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
	tipo =  $(archivo).attr("data-tipo");
	var url = window.location.protocol + "//" + window.location.host + "/sico/" + "bc_permiso/subir_archivo/" + $("table").attr("id") + "/" + tipo;
	$(archivo).parent().find('#progress .progress-bar').css(
            "width", "0"
        );
	$(archivo).parent().find('.captura').remove();
	var formData = new FormData();
	formData.append( 'file', $(archivo).get(0).files[0] );
	    $.ajax( {
	      url: url,
	      type: 'POST',
	      data: formData,
	      processData: false,
	      contentType: false,
	      success: function (data) {
	    	  if(data == "imgpdf"){
	    		  alert("Error: El tipo de archivo debe de ser jpg, png, bmp, gif o pdf")
	    	  } else if(data == "xls"){
	    		  alert("Error: El tipo de archivo debe de ser xls o xlsx (archivo de Excel)")
	    	  } else if(data == "Error"){
	    		  alert("Ocurrió un error la subir la imagen");
	    	  } else {
	    		  $(archivo).parent().find('#progress .progress-bar').css(
  	                "width", "100%"
  	              );
	    		  $(archivo).parent().append("<a class='captura' target='_blank' href='" + window.location.protocol + "//" + window.location.host + "/sico/files/permisos/" + data + "'>Clic para ver</a>");
  				  $(archivo).parent().find(".urlArchivo").val(data);
  		        }
	    	  }
	    });
});