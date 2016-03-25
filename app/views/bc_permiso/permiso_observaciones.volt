<ul style="margin-bottom: 5px;" class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#historico" id="historico-tab" role="tab" data-toggle="tab" aria-controls="historico" aria-expanded="true">Histórico de Eventos</a></li>
	<li role="presentation" class=""><a href="#otrospermisos" id="otrospermisos-tab" role="tab" data-toggle="tab" aria-controls="otrospermisos" aria-expanded="false">Otros Permisos</a></li>
</ul>
<div class="tab-content">
	<div role="tabpanel" class="tab-pane fade active in" id="historico" aria-labelledby="historico-tab">
		<? foreach($permiso->BcPermisoObservacion as $row){ ?>
			<div class="observacion">
		   		<div class="header">
				  <div class="foto">
				    <img src="{{ row.IbcUsuario.foto }}" width="40px" height="40px">
				  </div>
				  <div>
				  	<h3 style="margin: 0px;">{{ row.IbcUsuario.nombre }} <span class="label label-{{ row.getEstadoStyle() }}">{{ row.getEstado() }}</span></h3>
		   			<div class="info_anuncio"><?php $date = date_create($row->fechahora); ?><i class="fa fa-calendar"></i> <?php echo date_format($date, 'd/m/Y'); ?> <i class="fa fa-clock-o"></i> <?php echo date_format($date, 'G:ia'); ?> <span class="label label-success"></span></div>
				  </div>
				</div>
		   		<div class="contenido">{{ row.observacion }}</div>
		   	</div>
		<?php } ?>
	</div>
	<div role="tabpanel" class="tab-pane fade" id="otrospermisos" aria-labelledby="otrospermisos-tab">
		{% if (not(permisos is empty)) %}
		<table class="table table-bordered table-hover" id="permisos_lista">
		    <thead>
		        <tr>
		            <th>ID Permiso</th>
								<th>Estado</th>
		            <th>Categoría</th>
		            <th>Prestador</th>
		            <th>Contrato-Modalidad</th>
		            <th>ID-Sede</th>
		            <th>Título</th>
		            <th>Fecha</th>
		            <th>Horas</th>
		         </tr>
		    </thead>
		    <tbody>
		    {% for permiso in permisos %}
		        <tr>
		            <td><a rel="tooltip" title="Ver Detalles del Permiso" href="{{ url("bc_permiso/permiso/"~permiso.id_permiso) }}">{{ permiso.id_permiso }}</a></td>
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
		{% endif %}
	</div>
</div>
