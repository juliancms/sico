
{{ content() }}
<div class="container form-signin form-container-login">

	<h2>Iniciar Sesión</h2>
	{{ form('session/start', 'role': 'form') }}
	<fieldset>
	<div class="btn-block btn-group" data-toggle="buttons">
	  <label style="width: 50%" id="prestador_radio" class="btn btn-primary active">
	    <input type="radio" name="tipo_usuario" value="1" autocomplete="off" checked> Prestador
	  </label>
	  <label style="width: 50%" class="btn-block btn btn-primary" id="usuario_radio">
	    <input type="radio" name="tipo_usuario" value="2" autocomplete="off"> Usuario
	  </label>
	</div>
	<select id="id_oferente" name="id_oferente" class="form-control selectpicker" data-live-search="true">
	{% for oferente in oferentes %}
			<option value="{{ oferente.id_oferente }}">{{ oferente.nombre }}</option>
	{% endfor  %}
	{{ text_field('usuario', 'class': "form-control hide", 'disabled' : "disabled", 'placeholder' : "Usuario o Email") }}
	{{ password_field('password', 'class': "form-control", 'placeholder' : "Contraseña") }}
	{{ submit_button('Enviar', 'class': 'btn btn-lg btn-primary btn-block') }}
	</fieldset>
	</form>

</div>