{{ content() }}
{{ elements.getActamenu(acta) }}
<table class="table table-bordered table-striped">
		<tbody>
			<tr>
				<td>1. TOTAL DE NIÑOS Y NIÑAS QUE EFECTIVAMENTE ASISTIERON</td>
				<td>{{ asiste1 }}</td>
			</tr>
			<tr>
				<td>2. TOTAL DE NIÑOS Y NIÑAS AUSENTES CON EXCUSA FISICA VALIDA</td>
				<td>{{ asiste2 }}</td>
			</tr>
			<tr>
				<td>3. TOTAL DE NIÑOS Y NIÑAS AUSENTES CON EXCUSA TELEFONICA VALIDA</td>
				<td>{{ asiste3 }}</td>
			</tr>
			<tr>
				<td>4. TOTAL DE NIÑOS Y NIÑAS RETIRADOS ANTES DEL DIA DE CORTE DE PERIODO</td>
				<td>{{ asiste4 }}</td>
			</tr>
			<tr>
				<td>5. TOTAL DE NIÑOS Y NIÑAS RETIRADOS DESPUES DEL DIA DE CORTE DE PERIODO</td>
				<td>{{ asiste5 }}</td>
			</tr>
			<tr>
				<td>6. TOTAL DE NIÑOS Y NIÑAS AUSENTES QUE NO PRESENTAN EXCUSA EL DIA DEL REPORTE</td>
				<td>{{ asiste6 }}</td>
			</tr>
			<tr>
				<td>7. TOTAL DE NIÑOS Y NIÑAS CON EXCUSA MEDICA MAYOR O IGUAL A 15 DÍAS</td>
				<td>{{ asiste7 }}</td>
			</tr>
			<tr>
				<td>8. TOTAL DE NIÑOS Y NIÑAS CON EXCUSA MEDICA MENOR A 15 DÍAS</td>
				<td>{{ asiste8 }}</td>
			</tr>
			<tr>
				<td><strong>TOTAL LISTADO DE NIÑOS Y NIÑAS REPORTADOS EN EL SIBC</strong></td>
				<td>{{ asistetotal }}</td>
			</tr>
			<tr>
				<td><strong>TOTAL NIÑOS ADICIONALES INGRESADOS</strong></td>
				<td>{{ asisteadicionales }}</td>
			</tr>
		</tbody>
	</table>
{{ form("cob_actaconteo/guardardatos/"~id_actaconteo, "method":"post", "class":"form-container form-horizontal", "parsley-validate" : "", "enctype" : "multipart/form-data") }}
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Fecha Interventoría</label>
        <div class="col-sm-10">
                {{ text_field("fecha", "type" : "date", "class" : "form-control tipo-fecha required", "placeholder" : "dd/mm/aaaa", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Hora Inicio</label>
        <div class="col-sm-10">
                {{ text_field("horaInicio", "placeholder": "Ej: 08:30 am", "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Hora Fin</label>
        <div class="col-sm-10">
                {{ text_field("horaFin", "placeholder": "Ej: 09:00 am", "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Nombre Encargado de la Sede</label>
        <div class="col-sm-10">
                {{ text_field("nombreEncargado", "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Valla de Identificación</label>
        <div class="col-sm-10">
                {{ select("vallaClasificacion", valla_sede, "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">Corrección Dirección Sede</label>
        <div class="col-sm-10">
                {{ text_field("correccionDireccion", "class" : "form-control") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Cuenta con Mosaico Físico</label>
        <div class="col-sm-10">
                {{ select("mosaicoFisico", sino, "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Cuenta con Mosaico Digital</label>
        <div class="col-sm-10">
                {{ select("mosaicoDigital", sino, "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">Observación Interventor</label>
        <div class="col-sm-10">
                {{ text_area("observacionUsuario", "rows" : "4", "class" : "form-control") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">Observación Encargado Sede</label>
        <div class="col-sm-10">
                {{ text_area("observacionEncargado", "rows" : "4", "class" : "form-control") }}
        </div>
    </div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
          {{ submit_button("Guardar", "class" : "btn btn-default") }}
    </div>
</div>
</form>