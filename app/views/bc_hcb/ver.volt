
{{ content() }}
<h1>{{ mes }} - Cronograma Hogares Comunitarios</h1>
{{ elements.getcronogramahcbMenu() }}
<ol class="breadcrumb">
  <li>{{ link_to("bc_hcb/", 'Periodos') }}</li>
  <li class="active">{{ mes }}</li>
</ol>
{% if (not(sedes is empty)) %}
<table class="table table-bordered table-hover" id="recorrido">
    <thead>
        <tr><th>#</th>
            <th>No. Contrato</th>
            {% if (nivel <= 2 ) %}<th>Prestador</th>{% endif %}
            <th>Sede</th>
            <th>Madre Comunitaria</th>
         </tr>
    </thead>
    <tbody>
    {% for sede in sedes %}
        <tr>
        	<td>{{ loop.index }}</td>
            <td>{{ link_to("bc_hcb/cronograma/"~periodo.id_hcbperiodo~"/"~sede.id_sede_contrato, sede.id_contrato) }}</td>
            {% if (nivel <= 2 ) %}<td>{{ link_to("bc_hcb/cronograma/"~periodo.id_hcbperiodo~"/"~sede.id_sede_contrato, sede.oferente_nombre) }}{{ sede.oferente_nombre }}</td>{% endif %}
            <td>{{ link_to("bc_hcb/cronograma/"~periodo.id_hcbperiodo~"/"~sede.id_sede_contrato, sede.sede_nombre) }}</td>
            <td>{{ link_to("bc_hcb/cronograma/"~periodo.id_hcbperiodo~"/"~sede.id_sede_contrato, sede.CobActaconteo.CobActaconteoMcb.getNombrecompleto()) }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}
