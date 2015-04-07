{{ content() }}
{{ elements.getActamenu(acta) }}
{{ form("cob_actaconteo/guardaradicionalescapturas/"~id_actaconteo, "method":"post", "parsley-validate" : "", "id" : "adicionales_form", "enctype" : "multipart/form-data" ) }}
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
         </tr>
    </thead>
    <tbody>
    {% for empleado in empleados %}
    <?php $fecha = $this->conversiones->fecha(2, $empleado->fecha); ?>
    	<tr>
        	<td><span class="number">{{ loop.index }}</span></td>
        	<td>{{ text_field("numDocumento[]", "value" : adicional.numDocumento, "placeholder" : "Número de documento", "class" : "form-control required") }}</td>
        	<td>{{ text_field("nombre[]", "value" : adicional.nombre, "placeholder" : "Nombre completo", "class" : "form-control required") }}</td>
        	<td>{{ select("cargo[]", "value" : cargo, "class" : "form-control required") }}</td>
        	<td>{{ select("asistencia[]", "value" : asistencia, "class" : "form-control required") }}</td>
        	<td>{{ select("dotacion[]", "value" : dotacion, "class" : "form-control required") }}</td>
            <td>{{ text_field("fecha[]", "value" : fecha, "type" : "date", "class" : "form-control tipo-fecha required fecha", "placeholder" : "dd/mm/aaaa", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}</td>                      
        </tr>
    {% endfor %}
    <?php for ($i = 1; $i <= 20; $i++) { ?>
        <tr style='display: none;'>
        	<td><span class="number"><?php echo $i; ?></span></td>
            <td style="text-align:center;"><a class='btn btn-default eliminar_valor'><i class='glyphicon glyphicon-remove'></i></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="row container alert alert-danger alerta_lote" style="margin-top: 10px; display: none;"></div>    
<div class="row container" style="padding-top: 10px;">
  	<a class="btn btn-default pull-right submit_adicionales">Guardar</a>
</div>
</form>
<div class='clear'></div>