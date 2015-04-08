{{ content() }}
{{ elements.getActamenu(acta) }}
<!-- Modal -->
	<div class="modal fade" id="agregar_items" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Agregar ítems</h4>
	      </div>
	      <div class="modal-body">
	      <div class="alert alert-warning"><i class="glyphicon glyphicon-warning-sign"></i> El número de ítems debe de ser un número entre 1 y <span class="n2">30</span>, ya que sólo se permiten agregar hasta 30 empleados.</div>
	      	<div class="form-group">
			    <label for="n_items" class="col-sm-2 control-label">Ítems</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control required" id="n_items" name="n_items" placeholder="Número de ítems">
			    </div>
		  	</div>
		  	<br>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	        <button type="button" class="btn btn-primary agregar_varios_items" data-dismiss="modal">Agregar</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{ form("cob_actaconteo/guardarempleados/"~id_actaconteo, "method":"post", "parsley-validate" : "", "id" : "adicionales_form", "enctype" : "multipart/form-data" ) }}
<table class="table table-bordered table-hover" id="{{ id_actaconteo }}">
    <thead>
        <tr>
        	<th>#</th>
            <th>Documento</th>
            <th>Nombre completo</th>
            <th>Cargo</th>
            <th>Asistencia</th>
            <th>Dotación</th>
            <th>Fecha</th>
            <th>X</th>         
         </tr>
    </thead>
    <tbody>
    {% for empleado in empleados %}
    <?php $fecha = $this->conversiones->fecha(2, $empleado->fecha); ?>
    	<tr>
        	<td><span class="number">{{ loop.index }}</span></td>
        	<td>{{ text_field("numDocumento[]", "value" : empleado.numDocumento, "placeholder" : "Número de documento", "class" : "num_documento form-control required") }}<div class="error_documento"></div></td>
        	<td>{{ text_field("nombre[]", "value" : empleado.nombre, "placeholder" : "Nombre completo", "class" : "form-control required") }}</td>
        	<td>{{ select("cargo[]", cargoempleados, "value" : empleado.cargo, "class" : "form-control required") }}</td>
        	<td>{{ select("asistencia[]", asistenciaempleados, "value" : empleado.asistencia, "class" : "form-control required") }}</td>
        	<td>{{ select("dotacion[]", dotacion, "value" : empleado.dotacion, "class" : "form-control required") }}</td>
            <td>{{ text_field("fecha[]", "value" : fecha, "type" : "date", "class" : "form-control tipo-fecha required fecha", "placeholder" : "dd/mm/aaaa", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}</td>
            <td style="text-align:center;"><a id='{{ empleado.id_actaconteo_empleado }}' class='btn btn-default eliminar_guardado'><i class='glyphicon glyphicon-remove'></i></a></td>                      
        </tr>
    {% endfor %}
    <?php for ($i = 1; $i <= 30; $i++) { ?>
        <tr style='display: none;'>
        	<td><span class="number"><?php echo $i; ?></span></td>
        	<td>{{ text_field("numDocumento[]", "disabled" : "disabled", "placeholder" : "Número de documento", "class" : "num_documento form-control required") }}<div class="error_documento"></div></td>
        	<td>{{ text_field("nombre[]", "disabled" : "disabled", "placeholder" : "Nombre completo", "class" : "form-control required") }}</td>
        	<td>{{ select("cargo[]", cargoempleados, "disabled" : "disabled", "class" : "form-control required") }}</td>
        	<td>{{ select("asistencia[]", asistenciaempleados, "disabled" : "disabled", "class" : "form-control required") }}</td>
        	<td>{{ select("dotacion[]", dotacion, "disabled" : "disabled", "class" : "form-control required") }}</td>
            <td>{{ text_field("fecha[]", "disabled" : "disabled", "type" : "date", "class" : "form-control tipo-fecha required fecha", "placeholder" : "dd/mm/aaaa", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}</td>
            <td style="text-align:center;"><a class='btn btn-default eliminar_valor'><i class='glyphicon glyphicon-remove'></i></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="row container" style="margin-top: 10px;">
	<a id="agregar_item_adicional" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar 1 ítem</a>
	<a id="btn_varios_items" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar varios ítems</a>
</div>
<div class="row container alert alert-danger alerta_lote" style="margin-top: 10px; display: none;"></div>    
<div class="row container" style="padding-top: 10px;">
	<input type="hidden" name="eliminar_adicionales" id="eliminar_adicionales">
  	<a class="btn btn-default pull-right submit_adicionales">Guardar</a>
</div>
</form>
<div class='clear'></div>