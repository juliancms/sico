
{{ content() }}
<!-- Modal -->
	<div class="modal fade" id="agregar_items" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Agregar ítems</h4>
	      </div>
	      <div class="modal-body">
	      <div class="alert alert-warning"><i class="glyphicon glyphicon-warning-sign"></i> El número de ítems debe de ser un número entre 1 y <span class="n2">24</span>, ya que sólo se permiten agregar un máximo de 24 permisos por sede en todo el año.</div>
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
<h1>Nuevo Permiso - Jornada de Planeación</h1>
<table class='table table-bordered table-hover'>
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
{{ form("bc_permiso/crear_jornada_planeacion/"~sede.id_sede_contrato, "method":"post", "parsley-validate" : "", "id" : "jornada_planeacion_form", "enctype" : "multipart/form-data" ) }}
<table class="table table-bordered table-hover" id="{{ sede.id_sede_contrato }}">
    <thead>
        <tr>
        	<th>#</th>
            <th>Fecha (dd/mm/aaaa)</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>X</th>
         </tr>
    </thead>
    <tbody>
    	<tr>
    		<td><span class="number">1</span></td>
        	<td>{{ text_field("fecha[]", "type" : "date", "parsley-beforedate" : "#fecha_fin", "class" : "form-control required tipo-fecha", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}</td>
        	<td>{{ text_field("horaInicio[]", "class" : "form-control required time start") }}</td>
        	<td>{{ text_field("horaFin[]", "class" : "form-control required time end") }}</td>
        	<td style="text-align:center;"><a class='btn btn-default eliminar_valor'><i class='glyphicon glyphicon-remove'></i></a></td>
    	</tr>
    <?php for ($i = 2; $i <= 24; $i++) { ?>
    	<tr style='display: none;'>
    		<td><span class="number"><?php echo $i; ?></span></td>
        	<td>{{ text_field("fecha[]", "type" : "date", "class" : "form-control tipo-fecha required", "parsley-type" : "dateIso", "beforedate": "#fecha_fin", "data-date-format" : "dd/mm/yyyy", "disabled" : "disabled") }}</td>
        	<td>{{ text_field("horaInicio[]", "class" : "form-control required time start", "disabled" : "disabled") }}</td>
        	<td>{{ text_field("horaFin[]", "class" : "form-control required time end", "disabled" : "disabled") }}</td>
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
	<input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo date('d/m/Y',strtotime('+10 days')); ?>">
	<input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo "01/01/" . date('Y',strtotime('+1 year')); ?>"">
	{{ submit_button("Guardar", "class" : "btn btn-default pull-right") }}
</div>
</form>