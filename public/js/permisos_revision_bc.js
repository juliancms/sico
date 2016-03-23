var checked;
$(".permiso_check").click (
	function(){
		//permisos_seleccionados.push($(this).val());
		checked = $('input:checkbox:checked').length;
		$(this).parent().parent().toggleClass("warning");
		$("#num_check").html(checked);
	}
);
$("#btn_salidas").click (
	function(){
		$(".salidas").removeAttr("disabled");
		$(".salidas").fadeIn();
		$(".jornadas").attr("disabled", "disabled");
		$(".jornadas").fadeOut();
	}
);
$("#btn_jornadas").click (
	function(){
		$(".jornadas").removeAttr("disabled");
		$(".jornadas").fadeIn();
		$(".salidas").attr("disabled", "disabled");
		$(".salidas").fadeOut();
	}
);
$(".checkall").click (
		function(){
			$('input:checkbox:visible').prop('checked', true);
			$( '.permiso_check:visible' ).parent().parent().addClass( "warning" );
			checked = $('input:checkbox:checked').length;
			$("#num_check").html(checked);
		}
	);
$(".uncheck").click (
		function(){
			$('input:checkbox:visible').prop('checked', false);
			checked = $('input:checkbox:checked').length;
			$("#num_check").html(checked);
			$( '.permiso_check:visible' ).parent().parent().removeClass( "warning" );
		}
	);

$("select#estado_bc").change(function(){
    var estado_select = $("select#estado_bc").val();
    if(estado_select == 4){
        $('.campo_motivo').fadeIn();
        $(".campo_motivo input").removeAttr("disabled");
        $('.campo_motivo span').css("color", "#d9534f");
        $('.campo_motivo span').html("anular");
    }
    else if(estado_select == 2) {
    	$('.campo_motivo').fadeIn();
        $(".campo_motivo input").removeAttr("disabled");
        $('.campo_motivo span').css("color", "#5cb85c");
        $('.campo_motivo span').html("aprobar");
        $('.campo_motivo input').val("Se recuerda a la entidad contar con los procedimientos de seguridad para estas salidas y garantizar la alimentación de los niños y las niñas como lo establece la minuta.");

    } else {
    	$('.campo_motivo').fadeOut();
    	$(".campo_motivo input").attr("disabled", "disabled");
    }
});
$('.submit').click(function() {
	submit();
});
function submit() {
	var estado_select = $("select#estado_bc").val();
	var error = 0;
	var num_check = $("#num_check").html();
	if(num_check == "0"){
		$(".error_permisos").html("<ul class='parsley-error-list'><li class='required' style='display: list-item;'>Debes de seleccionar al menos un permiso.</li></ul>");
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		error = 1;
	}
	if(estado_select == "0"){
		$(".error_permisos").html("<ul class='parsley-error-list'><li class='required' style='display: list-item;'>Debes de seleccionar el estado por el cual deseas cambiar los permisos.</li></ul>");
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		$(".error_permisos").fadeOut();
		$(".error_permisos").fadeIn();
		error = 1;
	}
	if(error == 0){
		if($( '#agregar_permisos' ).parsley( 'validate' )){
			$('#agregar_permisos').submit();
		}
	}
}
