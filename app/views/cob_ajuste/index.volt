
{{ content() }}
<h1>Ajustes</h1>
{{ link_to("cob_ajuste/buscar", '<i class="glyphicon glyphicon-plus"></i> Nuevo ajuste', "class": "btn btn-primary menu-tab") }}
{% if (not(cob_ajuste is empty)) %}
<!-- Modal -->
<div class="modal fade" id="eliminar_elemento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Eliminar</h4>
      </div>
      <div class="modal-body">
          <p>¿Estás seguro de que deseas eliminar el elemento con ID <span class="fila_eliminar"></span> de la base de datos?</p>
          <p><div class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <strong>Atención: </strong>Después de eliminado no podrá ser recuperado y la información asociada se perderá.</div></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="boton_eliminar">Eliminar</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>{% if (nivel <= 1) %}<th>Acciones</th>{% endif %}
            <th>Periodo</th>
            <th>Contrato</th>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Certificar</th>
            <th>Observación</th>
            <th>Fecha</th>
            <th>Usuario</th>
         </tr>
    </thead>
    <tbody>
    {% for cob_ajuste in cob_ajuste %}
    	{% set nombre = {cob_ajuste.CobActaconteoPersonaFacturacion.primerNombre, cob_ajuste.CobActaconteoPersonaFacturacion.segundoNombre, cob_ajuste.CobActaconteoPersonaFacturacion.primerApellido, cob_ajuste.CobActaconteoPersonaFacturacion.segundoApellido} %}
        <tr>
        {% if (nivel <= 1) %}
        <td><a href="#eliminar_elemento" rel="tooltip" title="Eliminar" class="eliminar_fila" data-toggle = "modal" id="{{ url("cob_ajuste/eliminar/"~cob_ajuste.id_ajuste) }}"><i class="glyphicon glyphicon-trash"></i></a></td>
		{% endif %}
		<td><?php echo $this->conversiones->fecha(5, $cob_ajuste->CobPeriodo->fecha); ?></td>
        <td>{{ cob_ajuste.CobActaconteoPersonaFacturacion.id_contrato }}</td>
        <td>{{ cob_ajuste.CobActaconteoPersonaFacturacion.numDocumento }}</td>
        <td>{{ nombre|join(' ') }}</td>
        <td>{{ cob_ajuste.getCertificarDetail() }}</td>
        <td>{{ cob_ajuste.observacion }}</td>
        <td>{{ cob_ajuste.datetime }}</td>
        <td>{{ cob_ajuste.IbcUsuario.usuario }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}