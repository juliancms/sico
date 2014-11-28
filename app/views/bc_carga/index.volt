
{{ content() }}
<h1>Cargas</h1>
{{ link_to("bc_carga/nuevo", '<i class="glyphicon glyphicon-plus"></i> Nueva Carga', "class": "btn btn-primary menu-tab") }}
{% if (not(bc_carga is empty)) %}
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Generar Actas</th>
            <th>Archivo Mat</th>
            <th>Archivo Sedes</th>
            <th>Mes</th>
            <th>Fecha</th>
         </tr>
    </thead>
    <tbody>
    {% for bc_carga in bc_carga %}
        <tr>
        	<td>{{ link_to("bc_carga/generar/"~bc_carga.id_carga, '<i class="glyphicon glyphicon-import"></i> ', "rel": "tooltip", "title":"Generar Actas") }}</td>
            <td>{{ link_to("files/bc_bd/"~bc_carga.nombreMat, bc_carga.nombreMat, "target" : "_blank") }}</td>
            <td>{{ link_to("files/bc_bd/"~bc_carga.nombreSedes, bc_carga.nombreSedes, "target" : "_blank") }}</td>
            <td>{{ bc_carga.mes }}</td>
            <td>{{ bc_carga.fecha }}</td>         
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}