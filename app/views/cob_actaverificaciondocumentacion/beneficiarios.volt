{{ content() }}
{{ elements.getActaverificacionmenu(acta) }}
{{ form("cob_actaverificaciondocumentacion/guardarbeneficiarios/"~acta.id_acta, "method":"post", "parsley-validate" : "", "id" : "beneficiarios_form") }}
<table class="table table-bordered table-hover">
    <thead>
        <tr>
        	<th>#</th>
            <th>Documento</th>
            <th>Nombre Completo</th>
            <th>Grupo</th>
            <th>Nombre y Nuip SIBC</th>
            <th>Tel. SIBC</th>
            <th>Cert. SGS</th>
            <th>Cert. Sisbén</th>
            <th>Matr. Firmada</th>
            <th>Fecha Matr.</th>
         </tr>
    </thead>
    <tbody>
    {% for beneficiario in beneficiarios %}  
	{% set nombre = {beneficiario.primerNombre, beneficiario.segundoNombre, beneficiario.primerApellido, beneficiario.segundoApellido} %}
	<?php $fecha = $this->conversiones->fecha(2, $beneficiario->fechaInterventoria); ?>
        <tr>
        	<td>{{ loop.index }}</td>
        	<td><input type="hidden" name="id_actaverificaciondocumentacion_persona[]" value="{{ beneficiario.id_actaverificaciondocumentacion_persona }}">{{ beneficiario.numDocumento }}</td>
            <td>{{ nombre|join(' ') }}</td>
            <td>{{ beneficiario.grupo }}</td>
            <td>{{ select("nombreCedulaSibc[]", sinonare, "value" : beneficiario.nombreCedulaSibc, "class" : "form-control sinonare required") }}</td>
            <td>{{ select("telefonoSibc[]", sinonare, "value" : beneficiario.telefonoSibc, "class" : "form-control sinonare required") }}</td>
            <td>{{ select("certificadoSgs[]", sinonare, "value" : beneficiario.certificadoSgs, "class" : "form-control sinonare required") }}</td>
            <td>{{ select("certificadoSisben[]", sinonare, "value" : beneficiario.certificadoSisben, "class" : "form-control sinonare required") }}</td>
            <td>{{ select("matriculaFirmada[]", sinonare, "value" : beneficiario.matriculaFirmada, "class" : "form-control sinonare required") }}</td>
            <td>{{ select("fechaMatricula[]", sinonare, "value" : beneficiario.fechaMatricula, "class" : "form-control sinonare required") }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
{{ submit_button("Guardar", "class" : "btn btn-default pull-right") }}
</form>
<div class='clear'></div>

