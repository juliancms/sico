
{{ content() }}
<h1>Permisos - {{ titulo }}</h1>
<div class="pull-right form-inline">
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
{{ link_to("bc_permiso/nuevo", '<i class="glyphicon glyphicon-plus"></i> Nuevo Permiso', "class": "btn btn-primary menu-tab-first") }}
{% if (not(permisos is empty)) %}
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID Permiso</th>
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
            <td>{{ permiso.id_permiso }}</td>
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