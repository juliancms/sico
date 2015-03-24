
{{ content() }}
<h1>Reporte de Contratos Periodo</h1>
{{ link_to("bc_carga/nuevo", '<i class="glyphicon glyphicon-plus"></i> Nueva Carga General', "class": "btn btn-primary menu-tab-first") }}
{{ link_to("mt_carga/nuevo", '<i class="glyphicon glyphicon-plus"></i> Nueva Carga Entorno Familiar', "class": "btn btn-primary menu-tab") }}
{% if (not(contratos is empty)) %}
<table class="table table-bordered table-hover">
    <thead>
    	 <tr>
            <th>Periodo Verificado</th>
            <th>Entidad Prestadora</th>
            <th>Número de Contrato</th>
            <th>Modalidad de Atención</th>
            <th>Cupos de Ampliación Contratados</th>
            <th>Cupos de Sostenibilidad Contratados</th>
            <th>Total de cupos Contratados</th>
            <th>Total de cupos en el SIBC</th>
         </tr>
    </thead>
    <tbody>
    {% for contrato in contratos %}
        <tr>
            <td>{{ contrato. }}</td>
            <td>{{ link_to("files/bc_bd/"~bc_carga.nombreSedes, bc_carga.nombreSedes, "target" : "_blank") }}</td>
            <td>{{ bc_carga.mes }}</td>
            <td>{{ bc_carga.fecha }}</td>         
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}