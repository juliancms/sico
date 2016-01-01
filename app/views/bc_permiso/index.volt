
{{ content() }}
<h1>Permisos</h1>
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
            <td>{{ permiso.BcSedeContrato.titulo }}</td>
            <td>{{ permiso.BcSedeContrato.fecha }}</td>
            <td>{{ permiso.BcSedeContrato.horaInicio }} - {{ permiso.BcSedeContrato.horaFin }}</td>         
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}