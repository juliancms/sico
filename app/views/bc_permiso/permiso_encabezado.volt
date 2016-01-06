<h1>Permiso ID {{ permiso.id_permiso }}</h1>
<a href='/sico/bc_permiso' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Lista de Permisos</a><br><br>
<table class='table table-bordered table-hover'>
	<thead>
		<tr>
			<th colspan="4" style="text-align:center;">INFORMACIÓN SEDE</th>
		</tr>
		<tr>
			<th>Contrato - Modalidad</th>
			<th>ID Sede - Nombre Sede</th>
			<th>Dirección Sede</th>
			<th>Teléfono Sede</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{ permiso.BcSedeContrato.id_contrato }} - {{ permiso.BcSedeContrato.modalidad_nombre }}</td>
			<td>{{ permiso.BcSedeContrato.id_sede }} - {{ permiso.BcSedeContrato.sede_nombre }}</td>
			<td>{{ permiso.BcSedeContrato.sede_direccion }} ({{ permiso.BcSedeContrato.sede_barrio }})</td>
			<td>{{ permiso.BcSedeContrato.sede_telefono }}</td>
		</tr>
	</tbody>
</table>