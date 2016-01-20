
{{ content() }}
<h1>Permisos - {{ titulo }}</h1>
<div class="pull-left form-inline">
	<div class="input-group">
      <input name="buscar" type="text" class="form-control buscar-permiso-input" placeholder="Buscar por ID...">
      <span class="input-group-btn">
        <a class="btn btn-primary buscar-permiso-btn" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar</a>
      </span>
    </div><!-- /input-group -->
	<div class="btn-group">
		{{ btn_anterior }}
		<a class="btn btn-default" data-calendar-nav="today">{{ titulo }}</a>
		{{ btn_siguiente }}
	</div>
	<div class="btn-group">
		{{ btn_anio }}
		{{ btn_mes }}
		{{ btn_semana }}
		{{ btn_dia }}
	</div>
</div>
<br><br>
{% if (not(permisos is empty)) %}
<table class="table table-bordered table-hover" id="permisos_lista">
    <thead>
        <tr>
            <th>ID Permiso</th>
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
            <td><a rel="tooltip" title="Ver Detalles del Permiso" href="{{ url("bc_permiso/permiso/"~permiso.id_permiso) }}">{{ permiso.id_permiso }}</a></td>
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