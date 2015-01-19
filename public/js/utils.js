$("[rel='tooltip']").tooltip();
$(".eliminar_fila").click (
    function(){
    	var enlace = $(this).attr("id");
    	
    	$("#boton_eliminar").attr("href", enlace);
    }
);