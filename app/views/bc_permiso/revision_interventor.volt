
{{ content() }}
<h1>Revisión de Permisos</h1>
<a href='/sico/bc_permiso' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Lista de Permisos</a><br>
<br>
{% if (not(permisos is empty)) %}
<!-- Modal -->
<div class="modal fade" id="eliminar_elemento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Anulando Permiso ID <span class="fila_eliminar"></span></h4>
      </div>
      <div class="modal-body">
      {{ form("bc_permiso/anular/", "method":"post", "class":"", "id":"anular_permiso", "parsley-validate" : "") }}
          <p>Escribe el motivo por el cual vas a <span style='color: #d9534f; font-weight: bold'>anular</span> el permiso ID <span class="fila_eliminar"></span>:</p>
          <p>{{ text_area("observacion", "maxlength" : "400", "parsley-maxlength" : "150", "rows" : "4", "class" : "form-control required", "value" : anular_permiso) }}</p>
          <input type="hidden" name="id_permiso" class="id_elemento">
      </div>
      <div class="modal-footer">
        {{ submit_button("Anular", "class" : "btn btn-primary") }}
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<table class="table table-bordered table-hover" id="permisos_lista">
    <thead>
        <tr>
            <th>ID Permiso</th>
            <th filter-type='ddl'>Estado</th>
            <th filter-type='ddl'>Categoría</th>
            <th>Prestador</th>
            <th>Contrato-Modalidad</th>
            <th filter-type='ddl'>ID-Sede</th>
            <th>Título</th>
            <th>Fecha</th>
            <th>Horas</th>
         </tr>
    </thead>
    <thead>
    	<tr><th style="margin: 0; padding: 0; border: 0" colspan="9"><a id='cleanfilters' class='btn btn-primary btn-sm btn-block'>Limpiar Filtros</a></th></tr>
    </thead>
    <tbody>
    <?php
    $fecha_limite = strtotime(date('Y-m-d'). ' +1 days');
    ?>
    {% for permiso in permisos %}
        <tr>
            <td><a rel="tooltip" title="Ver Detalles del Permiso" href="{{ url("bc_permiso/permiso/"~permiso.id_permiso) }}"><a href="/sico/bc_permiso/aprobar/<?php echo $permiso->id_permiso; ?>" rel="tooltip" title="Pre Aprobar"><i class="glyphicon glyphicon-ok"></i></a> <a href="#eliminar_elemento" rel="tooltip" title="Anular" class="eliminar_fila" data-id = "{{ permiso.id_permiso }}" data-toggle = "modal" id="{{ url("bc_permiso/eliminar/"~permiso.id_permiso) }}"><i class="glyphicon glyphicon-remove"></i></a> {{ permiso.id_permiso }}</a></td>
            <td><a rel="tooltip" title="Ver Detalles del Permiso" href="{{ url("bc_permiso/permiso/"~permiso.id_permiso) }}">{{ permiso.getEstado() }}</a></td>
            <td>{{ permiso.getCategoria() }}</td>
            <td>{{ permiso.BcSedeContrato.oferente_nombre }}</td>
            <td>{{ permiso.BcSedeContrato.id_contrato }} - {{ permiso.BcSedeContrato.modalidad_nombre }}</td>
            <td>{{ permiso.BcSedeContrato.id_sede }} - {{ permiso.BcSedeContrato.sede_nombre }}</td>
            <td>{{ permiso.titulo }}</td>
            <td>{{ permiso.fecha }}</td>
            <td>{{ permiso.horaInicio }} - {{ permiso.horaFin }}</td>         
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}