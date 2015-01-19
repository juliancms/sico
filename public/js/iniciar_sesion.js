$("#prestador_radio").click(function() {
	$("#usuario").attr("disabled", "disabled");
	$("#usuario").addClass("hide");
	$(".bootstrap-select").removeClass("hide");
});
$("#usuario_radio").click(function() {
	$("#usuario").removeAttr("disabled", "disabled");
	$("#usuario").removeClass("hide");
	$(".bootstrap-select").addClass("hide");
});