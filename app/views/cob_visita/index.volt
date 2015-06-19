
{{ content() }}
<h1>Visitas</h1>
{% if (nivel <= 1) %}
{{ link_to("cob_visita/nuevo", '<i class="glyphicon glyphicon-plus"></i> Nueva Visita', "class": "btn btn-primary menu-tab") }}
{% endif %}
{% if (not(cob_visita is empty)) %}
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
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Nombre</th>
         </tr>
    </thead>
    <tbody>
    {% for cob_visita in cob_visita %}
        <tr>
        {% if (nivel <= 1) %}
        <td>{{ link_to("cob_visita/ver/"~cob_visita.id_visita, '<i class="glyphicon glyphicon-list-alt"></i> ', "rel": "tooltip", "title":"Ver") }}{{ link_to("cob_visita/editar/"~cob_visita.id_visita, '<i class="glyphicon glyphicon-pencil"></i> ', "rel": "tooltip", "title":"Editar") }}<a href="#eliminar_elemento" rel="tooltip" title="Eliminar" class="eliminar_fila" data-toggle = "modal" id="{{ url("cob_visita/eliminar/"~cob_visita.id_visita) }}"><i class="glyphicon glyphicon-trash"></i></a></td>
		{% endif %}
            <td>{{ link_to("cob_visita/ver/"~cob_visita.id_visita, cob_visita.fecha) }}</td>
            <td>{{ link_to("cob_visita/ver/"~cob_visita.id_visita, cob_visita.getTipo()) }}</td>
            <td>{{ link_to("cob_visita/ver/"~cob_visita.id_visita, cob_visita.nombre) }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}