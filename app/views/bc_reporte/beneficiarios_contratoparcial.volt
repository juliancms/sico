
{{ content() }}
<h1>Base de Datos Niño a Niño Consolidado Parcial 1er y 2do Recorridos {{ periodo.getFechaAnioDetail() }} <br><small>CONTRATO {{ contrato.id_contrato }} MODALIDAD {{ contrato.BcSedeContrato.modalidad_nombre }}</small></h1>
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
            <th>Acta R1</th>
            <th>Asistencia R1</th>
            <th>Observación R1</th>
            <th>Certificación R1</th>
            <th>Acta R2</th>
            <th>Asistencia R2</th>
            <th>Observación R2</th>
            <th>Certificación R2</th>
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
            <td>{{ beneficiario.acta1 }}</td>
            <td>{{ beneficiario.asistencia1 }}</td>
            <td>{{ beneficiario.getObservacion1() }}</td>
            <td>{{ beneficiario.getCertificacion1() }}</td>
            <td>{{ beneficiario.acta2 }}</td>
            <td>{{ beneficiario.asistencia2 }}</td>
            <td>{{ beneficiario.getObservacion2() }}</td>
            <td>{{ beneficiario.getCertificacion2() }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>