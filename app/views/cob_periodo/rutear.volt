
{{ content() }}
<h1>Rutear Recorrido</h1>
{{ link_to("cob_periodo/ver/"~id_periodo, '<i class="glyphicon glyphicon-chevron-left"></i> Regresar', "class": "btn btn-primary menu-tab") }}
{{ form("cob_periodo/ruteoguardar/"~id_periodo~"/"~recorrido, "method":"post", "name":"ruteo") }}
<table class="table table-bordered table-hover" id="ruteo">
    <thead>
        <tr><th>#</th>
            <th><i class="glyphicon glyphicon-remove uncheck" style="text-align: center; cursor:pointer; width: 100%"></i></th>
            <th>Interventor</th>
            <th>No. Acta</th>
            <th>Modalidad</th>
            <th>Prestador</th>
            <th>Sede</th>
            <th>Barrio</th>
            <th>Dirección</th>
            <th>Beneficiarios</th>
            
         </tr>
    </thead>
    <tbody>
    {% for acta in actas %}
   		{% if ((acta.id_usuario is empty)) %}
        <tr>
        	<td>{{ loop.index }}</td>
        	<td><input type="checkbox" class="acta_check" id="{{ acta.id_actaconteo }}" value="{{ acta.id_actaconteo }}"><input type="hidden" name="id_acta[]" class="id_acta" value="{{ acta.id_actaconteo }}" disabled="disabled"><input type="hidden" name="contador_asignado[]" class="contador_asignado" disabled="disabled"></td>
        	<td class='interventor'><span class="no_asignado">No asignado</span></td>
        	{% else %}
        <tr class="success">
        	<td>{{ loop.index }}</td>
        	<td><input type="checkbox" class="acta_check" id="{{ acta.id_actaconteo }}" value="{{ acta.id_actaconteo }}"><input type="hidden" name="id_acta[]" class="id_acta" value="{{ acta.id_actaconteo }}" disabled="disabled"><input type="hidden" name="contador_asignado[]" class="contador_asignado" disabled="disabled"></td>
        	<td class='interventor'><span class="asignado">{{ acta.IbcUsuario.usuario }}</span></td>
        	{% endif %}
            <td>{{ link_to("cob_actaconteo/ver/"~acta.id_actaconteo, '<span class="nombre_lista" id="'~acta.id_actaconteo~'">'~acta.id_actaconteo~'</span>') }}</td>
            <td>{{ acta.modalidad_nombre }}</td>
            <td>{{ acta.oferente_nombre }}</td>
            <td>{{ acta.id_sede }} - {{ acta.sede_nombre }}</td>
            <td>{{ acta.sede_barrio }}</td>
            <td>{{ acta.sede_direccion }}</td>
            <td><?php echo count($acta->getCobActaconteoPersona()); ?></td>
                      
        </tr>
    {% endfor %}
    </tbody>
</table>
 <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-bottom" role="navigation">
      <div class="container">
      	<div class="row">
	      <div class="col-lg-6" style="padding-top: 7px;">
	      	<div class="input-group">
			  <span class="input-group-addon"><span id="num_check">0</span> actas seleccionadas</span>
			  <select name="contador" id="contador" class="form-control">
	        	<option value="" selected="selected">Seleccionar...</option>
				{% for interventor in interventores %}
					<option value='{{ interventor.id_usuario }}'>{{ interventor.usuario }}</option>
				{% endfor %}
				<option value="NULL">Inhabilitar</option>
            </select>
            <span class="input-group-btn">
            	<span class="btn btn-default disabled" id="asignar_contador">Asignar</span>
           	</span>
           	
			</div>
	      </div>
	      <div class="col-lg-3" style="padding-top: 7px;">
		      <div class="btn-group" style="margin-left: 20px;">
	            	<span class="btn btn-default quitar_select" disabled="disabled">Limpiar</span>
		            <button type="submit" class="btn btn-primary guardar_ruteo" disabled="disabled">Guardar</button>
	           	</div>
	      </div>
	      <div class="col-lg-3" style="padding-top: 12px;">
	      	<div class="input-group navbar-right">
			  <span class="actas_ruteadas">0</span> actas asignadas | <span class="actas_por_rutear">0</span> actas sin asignar
			</div>
	      </div>
	      </form>
	    </div>
      </div>
    </div>