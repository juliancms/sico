
{{ content() }}
<h1>Base de Datos Niño a Niño Consolidado Final {{ periodo.getFechaAnioDetail() }} <br><small>CONTRATO {{ contrato.id_contrato }} MODALIDAD {{ contrato.BcSedeContrato.modalidad_nombre }}</small></h1>
{{ link_to("bc_reporte/oferente_periodos/"~contrato.id_contrato, '<i class="glyphicon glyphicon-chevron-left"></i> Regresar', "class": "btn btn-primary menu-tab") }}
<table class="table table-bordered table-hover">
	<thead>
    	 <tr>
            <th>Nombre Sede</th>
            <th>Nombre Grupo</th>
            <th>ID Persona</th>
            <th>Número documento</th>
            <th>Primer Nombre</th>
            <th>Segundo Nombre</th>
            <th>Primer Apellido</th>
            <th>Segundo Apellido</th>
            <th>Fecha Registro</th>
            <th>Acta R3</th>
            <th>Asistencia R3</th>
            <th>Certificación R3</th>
            <th>Observación R3</th>
            <th>Certificación Final</th>
         </tr>
    </thead>
    <tbody>
    {% for beneficiario in beneficiarios %}
        <tr>
            <td>{{ beneficiario.CobActaconteo.sede_nombre }}</td>
            <td>{{ beneficiario.grupo }}</td>
            <td>{{ beneficiario.id_persona }}</td>
            <td>{{ beneficiario.numDocumento }}</td>
            <td>{{ beneficiario.primerNombre }}</td>
            <td>{{ beneficiario.segundoNombre }}</td>
            <td>{{ beneficiario.primerApellido }}</td>
            <td>{{ beneficiario.segundoApellido }}</td>
            <td>{{ beneficiario.fechaRegistro }}</td>
            <td>{{ beneficiario.acta3 }}</td>
            <td>{{ beneficiario.asistencia3 }}</td>
            <td>{{ beneficiario.getCertificacion3() }}</td>
            <td>{{ beneficiario.getObservacion3() }}</td>
            <td>{{ beneficiario.getCertificacionFinalFija() }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>