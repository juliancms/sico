
{{ content() }}
<h1>Revisión de Permisos</h1>
<a href='/sico/bc_permiso/mes' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Lista de Permisos</a>
<a style="margin-left: 3px;" href='/sico/bc_permiso/reportes' class='btn btn-primary'><i class='glyphicon glyphicon-list-alt'></i> Reporte Permisos</a><br>
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
{{ form("bc_permiso/modificar_permisos/", "method":"post", "class":"", "id":"agregar_permisos", "parsley-validate" : "", "enctype" : "multipart/form-data") }}
<table class="table table-bordered table-hover" id="permisos_lista">
    <thead>
        <tr>
        	<th><i class="glyphicon glyphicon-remove-sign uncheck" style="text-align: center; cursor:pointer; width: 100%"></i><br><i class="glyphicon glyphicon-ok-sign checkall" style="text-align: center; cursor:pointer; width: 100%"></i></th>
            <th>ID Permiso</th>
            <th filter-type='ddl'>Estado</th>
            <th filter-type='ddl'>Categoría</th>
            <th>Prestador</th>
            <th>Contrato-Modalidad</th>
            <th>ID-Sede</th>
            <th>Título</th>
            <th>Fecha</th>
            <th>Horas</th>
         </tr>
    </thead>
    <thead>
    	<tr><th style="margin: 0; padding: 0; border: 0" colspan="10"><a id='cleanfilters' class='btn btn-primary btn-sm btn-block'>Limpiar Filtros</a></th></tr>
    </thead>
    <tbody>
    <?php
    $fecha_limite = strtotime(date('Y-m-d'). ' +1 days');
    ?>
    {% for permiso in permisos %}
        <tr>
        	<td><input type="checkbox" class="permiso_check" name="id_permiso[]" value="{{ permiso.id_permiso }}"></td>
            <td><a rel="tooltip" title="Ver Detalles del Permiso" href="{{ url("bc_permiso/permiso/"~permiso.id_permiso) }}"><?php if ($permiso->estado == 0 || $permiso->estado == 3){ ?><a href="/sico/bc_permiso/aprobar/<?php echo $permiso->id_permiso; ?>" rel="tooltip" title="Pre Aprobar"><i class="glyphicon glyphicon-ok"></i></a> <?php } ?><?php if ($permiso->estado == 0 || $permiso->estado == 3){ ?><a href="#eliminar_elemento" rel="tooltip" title="Anular" class="eliminar_fila" data-id = "{{ permiso.id_permiso }}" data-toggle = "modal" id="{{ url("bc_permiso/eliminar/"~permiso.id_permiso) }}"><i class="glyphicon glyphicon-remove"></i></a> <?php } ?>{{ permiso.id_permiso }}</a></td>
            <td><a rel="tooltip" title="Ver Detalles del Permiso" href="{{ url("bc_permiso/permiso/"~permiso.id_permiso) }}">{{ permiso.getEstado() }}</a></td>
            <td>{{ permiso.getCategoria() }}</td>
            <td>{{ permiso.BcSedeContrato.oferente_nombre }}</td>
            <td>{{ permiso.BcSedeContrato.id_contrato }} - {{ permiso.BcSedeContrato.modalidad_nombre }}</td>
            <td>{{ permiso.BcSedeContrato.id_sede }} - {{ permiso.BcSedeContrato.sede_nombre }}</td>
            <td>{{ permiso.titulo }}</td>
            <td><?php echo $this->conversiones->fecha(4, $permiso->fecha); ?></td>
            <td>{{ permiso.horaInicio }} - {{ permiso.horaFin }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
<!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-bottom" role="navigation">
      <div class="container">
      	<div class="row">
      		<div class="col-md-12 error_permisos"></div>
      	</div>
      	<div class="row">
      		<div class="col-md-12 campo_motivo" style="display: none;">Escribe el motivo por el cual vas a <span style='color: #d9534f; font-weight: bold' class='motivo'>anular</span> los permisos:<input type="text" name="observacion" class="form-control required" disabled="disabled"></div>
      	</div>
      	<div class="row">
	      <div class="col-lg-12">
	      	<div class="input-group">
			  <span class="input-group-addon"><span id="num_check">0</span> permisos seleccionados</span>
			  <select name="estado" id="estado_interventor" class="form-control required">
	        	<option value="0" selected="selected">Cambiar estado...</option>
				<option value="1">Pre Aprobar</option>
				<option value="3">Anular</option>
            </select>
            <span class="input-group-btn">
            	<a class="btn btn-primary submit">Guardar</a>
           	</span>

			</div>
	      </div>
	      </form>
	    </div>
      </div>
    </div>
{% endif %}
