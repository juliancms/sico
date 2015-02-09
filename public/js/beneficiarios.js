$(document).ready(function () {
$('.asistencia').each(function() {
	var asistencia = $(this).val();
	if(asistencia == 7 || asistencia == 8){
		$(this).parent().parent().find(".excusa").removeClass("hidden");
		$(this).parent().parent().find(".excusa").removeAttr("disabled");
		$(this).parent().parent().find(".excusa").addClass("required");
	} else {
		$(this).parent().parent().find(".excusa").addClass("hidden");
		$(this).parent().parent().find(".excusa").attr("disabled", "disabled");
		$(this).parent().parent().find(".excusa").removeClass("required");
	}
});
$( '#beneficiarios_form' ).parsley( 'destroy' );
$( '#beneficiarios_form' ).parsley();
});
if($(".fecha_visita_header").html() == null){
	$( ".fecha" ).parent().remove();
}
$('.asistencia').change(function() {
	var asistencia = $(this).val();
	if(asistencia == 7 || asistencia == 8){
		$(this).parent().parent().find(".excusa").removeClass("hidden").addClass("required");
		$(this).parent().parent().find(".excusa").removeAttr("disabled");
	} else {
		$(this).parent().parent().find(".excusa").attr("disabled", "disabled");
		$(this).parent().parent().find(".excusa").removeClass("required").addClass("hidden");
	}
	
});
$("#boton_duplicar").click(function() {
	var fecha = $(".fecha_duplicar").val();
	$('.modal-body input:checkbox:checked').each(function(){
		var id_grupo = $(this).val();
		$('.id_grupo').each(function(){
			if(id_grupo == $(this).html()){
				$(this).parent().parent().find(".tipo-fecha").val(fecha);
			}
		});
	});
});
$(".sel-todos").click(function() {
	$('.modal-body input:checkbox').attr('checked', true); 
});