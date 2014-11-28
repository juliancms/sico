var SignUp = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("name") == false) {
            return false;
        }
        if (SignUp.check("username") == false) {
            return false;
        }
        if (SignUp.check("email") == false) {
            return false;
        }
        if (SignUp.check("password") == false) {
            return false;
        }
        if ($("#password")[0].value != $("#repeatPassword")[0].value) {
            $("#repeatPassword")[0].focus();
            $("#repeatPassword_alert").show();

            return false;
        }
        $("#registerForm")[0].submit();
    }
}
$(document).ready(function () {
    $("#registerForm .alert").hide();
    $("div.profile .alert").hide();
});
$("[rel='tooltip']").tooltip();
$(".eliminar_fila").click (
    function(){
    	var enlace = $(this).attr("id");
    	
    	$("#boton_eliminar").attr("href", enlace);
    }
);