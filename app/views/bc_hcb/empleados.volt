
{{ content() }}
<h1>Empleados - Cronograma Hogares Comunitarios</h1>
{{ elements.getcronogramahcbMenu() }}
<h3>Para modificar un empleado haz clic en el nombre</h3>
{% if (not(empleados is empty)) %}
<table class="table table-bordered table-hover">
    <thead>
        <tr><th>#</th>
            {% if (nivel <= 2 ) %}<th>Prestador</th>{% endif %}
            <th>Número de Documento</th>
            <th>Nombre Completo</th>
         </tr>
    </thead>
    <tbody>
    {% for empleado in empleados %}
        <tr>
        	<td>{{ loop.index }}</td>
            {% if (nivel <= 2 ) %}<td>{{ link_to("bc_hcb/editarempleado/"~empleado.id_hcbempleado, empleado.BcSedeContrato.oferente_nombre) }}</td>{% endif %}
            <td>{{ link_to("bc_hcb/editarempleado/"~empleado.id_hcbempleado, empleado.numDocumento) }}</td>
            <td>{{ link_to("bc_hcb/editarempleado/"~empleado.id_hcbempleado, empleado.getNombrecompleto()) }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
{% endif %}
