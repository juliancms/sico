
{{ content() }}
<h1>{{ titulo }}</h1>
<table class='table table-bordered table-hover nuevo_general' id="{{ id_sede_contrato }}">
	<thead>
		<tr>
			<th>Contrato - Modalidad</th>
			<th>ID Sede - Nombre Sede</th>
			<th>Dirección</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{ sede.id_contrato }} - {{ sede.modalidad_nombre }}</td>
			<td>{{ sede.id_sede }} - {{ sede.sede_nombre }}</td>
			<td>{{ sede.sede_direccion }} ({{ sede.sede_barrio }})</td>
		</tr>
	</tbody>
</table>
<h3>3. Ingresa los campos del permiso</h3>
{{ form("bc_permiso/crear_general/"~id_sede_contrato~"/"~id_categoria, "id":"permiso_general_form", "method":"post", "class":"form-container form-horizontal", "parsley-validate" : "") }}
	<div class="form-group">
        <label class="col-sm-2 control-label" for="titulo">Nombre Evento</label>
        <div class="col-sm-10">
                {{ text_field("titulo", "maxlength" : "25", "parsley-maxlength" : "25", "class" : "form-control required") }}
                <div class="max">25 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">Fecha (dd/mm/aaaa)</label>
        <div class="col-sm-10">
                {{ text_field("fecha", "type" : "date", "class" : "form-control tipo-fecha required", "placeholder" : "dd/mm/aaaa", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="horaInicio">Hora Inicio</label>
        <div class="col-sm-10 hora">
                {{ text_field("horaInicio", "class" : "form-control required time start") }}
        </div>
    </div>
    <div class="form-group hora">
        <label class="col-sm-2 control-label" for="horaFin">Hora Fin</label>
        <div class="col-sm-10">
                {{ text_field("horaFin", "class" : "form-control required time end") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="observaciones">Objetivo de la salida</label>
        <div class="col-sm-10">
                {{ text_area("observaciones", "maxlength" : "150", "parsley-maxlength" : "150", "rows" : "4", "class" : "form-control required") }}
                <div class="max">150 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="actores">Actores que Apoyan el Evento</label>
        <div class="col-sm-10">
                {{ text_field("actores", "maxlength" : "50", "parsley-maxlength" : "50", "class" : "form-control required") }}
                <div class="max">50 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="direccionEvento">Dirección y Lugar del Evento</label>
        <div class="col-sm-10">
                {{ text_field("direccionEvento", "maxlength" : "80", "parsley-maxlength" : "80", "class" : "form-control required") }}
                <div class="max">80 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="personaContactoEvento">Persona Contacto del Escenario</label>
        <div class="col-sm-10">
                {{ text_field("personaContactoEvento", "maxlength" : "80", "parsley-maxlength" : "80", "class" : "form-control required") }}
                <div class="max">80 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="telefonoContactoEvento">Teléfonos de la Persona de Contacto</label>
        <div class="col-sm-10">
                {{ text_field("telefonoContactoEvento", "maxlength" : "80", "parsley-maxlength" : "80", "class" : "form-control required") }}
                <div class="max">80 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="emailContactoEvento">Email de la Persona de Contacto</label>
        <div class="col-sm-10">
                {{ text_field("emailContactoEvento", "maxlength" : "80", "parsley-maxlength" : "80", "class" : "form-control required") }}
                <div class="max">80 caracteres máximo</div>
        </div>
    </div>
    <div class="form-group">
    	<label class="col-sm-2 control-label" for="listadoNinios">Listado de Niños Participantes</label>
		<div class="col-sm-10 imagen_imppnt">
			<input class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input style="display:none" type='text' class='urlArchivo required' name='listadoNinios' value=''>
		</div>
	</div>
	<div class="form-group">
    	<label class="col-sm-2 control-label" for="consentimientoInformado">Consentimiento Informado</label>
		<div class="col-sm-10 imagen_imppnt">
			<input class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input style="display:none" type='text' class='urlArchivo required' name='consentimientoInformado' value=''>
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-2 control-label" for="requiereTransporte">¿Requiere Transporte?</label>
        <div class="col-sm-10">
        	<div class="btn-group" data-toggle="buttons">
			  <label class="btn btn-primary requiere_transporte">
			    <input type="radio" name="requiereTransporte" id="option1" autocomplete="off" value="1"> Sí
			  </label>
			  <label class="btn btn-primary no_requiere_transporte">
			    <input type="radio" name="requiereTransporte" id="option2" autocomplete="off" value="2" class="required"> No
			  </label>
			</div>
        </div>
    </div>
	<div class="form-group transporte">
    	<label class="col-sm-2 control-label" for="contratoTransporte">Contrato Transporte</label>
		<div class="col-sm-10 imagen_imppnt">
			<input disabled="disabled" class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input disabled="disabled" style="display:none" type='text' class='urlArchivo required' name='contratoTransporte' value=''>
		</div>
	</div>
	<div class="form-group transporte">
    	<label class="col-sm-2 control-label" for="runtConductor">RUNT del Conductor</label>
		<div class="col-sm-10 imagen_imppnt">
			<input disabled="disabled" class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input disabled="disabled" style="display:none" type='text' class='urlArchivo required' name='runtConductor' value=''>
		</div>
	</div>
	<div class="form-group transporte">
    	<label class="col-sm-2 control-label" for="runtVehiculo">RUNT del Vehículo</label>
		<div class="col-sm-10 imagen_imppnt">
			<input disabled="disabled" class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input disabled="disabled" style="display:none" type='text' class='urlArchivo required' name='runtVehiculo' value=''>
		</div>
	</div>
	<div class="form-group transporte">
    	<label class="col-sm-2 control-label" for="soat">Póliza de Responsabilidad (SOAT)</label>
		<div class="col-sm-10 imagen_imppnt">
			<input disabled="disabled" class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input disabled="disabled" style="display:none" type='text' class='urlArchivo required' name='soat' value=''>
		</div>
	</div>
	<div class="form-group transporte">
    	<label class="col-sm-2 control-label" for="tarjetaOperacionVehiculo">Tarjeta de Operación del Vehículo</label>
		<div class="col-sm-10 imagen_imppnt">
			<input disabled="disabled" class="fileupload filestyle" data-input="false" data-badge="false" type="file" name="archivo[]" multiple>
		    <div id="progress" class="progress" style="margin: 0 !important;">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>
		    <input disabled="disabled" style="display:none" type='text' class='urlArchivo required' name='tarjetaOperacionVehiculo' value=''>
		</div>
	</div>
	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    	  <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo date('d/m/Y',strtotime('+10 days')); ?>">
		  <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo "01/01/" . date('Y',strtotime('+1 year')); ?>"">
          {{ submit_button("Guardar", "class" : "btn btn-default") }}
    </div>
</div>
</form>