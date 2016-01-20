var options1 = { clearFiltersControls: [$('#cleanfilters')] };
$('#permisos_lista').tableFilter(options1);
$(".buscar-permiso-btn").click (
		function(){
			var url = window.location.protocol + "//" + window.location.host + "/sico/" + "bc_permiso/permiso/" + $(".buscar-permiso-input").val();
			window.location.replace(url);
})