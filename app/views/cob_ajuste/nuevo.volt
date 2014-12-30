
{{ content() }}
{% set nombre = {beneficiario.primerNombre, beneficiario.segundoNombre, beneficiario.primerApellido, beneficiario.segundoApellido} %}
<h1>Nuevo Ajuste</h1>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Nombre Completo</th>
            <th>Prestador</th>
            <th>Contrato</th>
            <th>Sede</th>
         </tr>
    </thead>
    <tbody>
    <tr>
    	<td>{{ beneficiario.numDocumento }}</td>
    	<td>{{ nombre|join(' ') }}</td>
    	<td>{{ acta.oferente_nombre }}</td>
    	<td>{{ beneficiario.id_contrato }}</td>
    	<td>{{ acta.sede_nombre }}</td>
    </tr>
    </tbody>
</table>
{{ link_to("cob_ajuste/", '<i class="glyphicon glyphicon-chevron-left"></i> Regresar', "class": "btn btn-primary menu-tab") }}
{{ form("cob_ajuste/guardar/"~beneficiario.id_actaconteo_persona_facturacion, "method":"post", "class":"form-container form-horizontal", "parsley-validate" : "") }}
	<div class="form-group">
        <label class="col-sm-2 control-label" for="certificar">¿Certificar? *</label>
        <div class="col-sm-10">
                {{ select("certificar", sino, "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Observación</label>
        <div class="col-sm-10">
                {{ text_area("observacion", "rows" : "4", "class" : "form-control required") }}
        </div>
    </div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
          {{ submit_button("Guardar", "class" : "btn btn-default") }}
    </div>
</div>
</form>
