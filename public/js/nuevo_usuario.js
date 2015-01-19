$('#id_usuario_cargo').change(function() {
	var cargo = $(this).val();
	if(cargo == 3){
		$("#id_usuario_lider").parent().parent().removeClass("hide");
		$("#id_usuario_lider").removeAttr("disabled");
		$("#id_usuario_lider").addClass("required");
	} else {
		$("#id_usuario_lider").parent().parent().addClass("hide");
		$("#id_usuario_lider").attr("disabled", "disabled");
		$("#id_usuario_lider").removeClass("required");
	}
	$( '#nuevo_form' ).parsley( 'destroy' );
	$( '#nuevo_form' ).parsley();
});
if($("#id_usuario_cargo").val() == 3){
	$("#id_usuario_lider").parent().parent().removeClass("hide");
	$("#id_usuario_lider").removeAttr("disabled");
	$("#id_usuario_lider").addClass("required");
} else {
	$("#id_usuario_lider").parent().parent().addClass("hide");
	$("#id_usuario_lider").attr("disabled", "disabled");
	$("#id_usuario_lider").removeClass("required");
}