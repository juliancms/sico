{{ content() }}
<h1>Cronograma {{ mes }} {{ sede.sede_nombre }}</h1>
{{ elements.getcronogramahcbMenu() }}
<ol class="breadcrumb">
  <li>{{ link_to("bc_hcb/", 'Periodos') }}</li>
  <li>{{ link_to("bc_hcb/ver/"~periodo.id_hcbperiodo, mes) }}</li>
  <li class="active">{{ sede.sede_nombre }}</li>
</ol>
<h3>Seleccione los empleados para asignar fechas del mes</h3>
{{ form("bc_hcb/guardarcronograma/"~periodo.id_hcbperiodo~"/"~sede.id_sede_contrato, "method":"post", "parsley-validate" : "", "id" : "cronogramahcb_form", "class" : "form-container form-horizontal", "enctype" : "multipart/form-data" ) }}
<table class="table table-bordered table-hover">
    <thead>
        <tr><th>#</th>
            <th>Número de Documento</th>
            <th>Nombre Completo</th>
            <th>Jornada Mañana</th>
            <th>Jornada Tarde</th>
         </tr>
    </thead>
    <tbody>
    {% for empleado in empleados %}
        <tr>
        	  <?php
            if(in_array($empleado->id_hcbempleado, $empleados_periodo)){ ?>
              <td><input type="checkbox" class="empleadocheck" checked="checked"></td>
              <td>{{ empleado.numDocumento }}</td>
              <td>{{ empleado.getNombrecompleto() }}</td>
              <td><input type="hidden" name="id_hcbempleado[]" value="{{ empleado.id_hcbempleado }}">{{ text_field("fechamaniana[]", "value" : empleado.getFechasmaniana(empleado.id_hcbempleado, periodo.id_hcbperiodo, sede.id_sede_contrato), "type" : "date", "class" : "form-control tipo-fecha required", "placeholder" : "dd/mm/aaaa", "data-date-format" : "dd/mm/yyyy") }}</td>
              <td>{{ text_field("fechatarde[]", "value" : empleado.getFechastarde(empleado.id_hcbempleado, periodo.id_hcbperiodo, sede.id_sede_contrato), "type" : "date", "class" : "form-control tipo-fecha", "placeholder" : "dd/mm/aaaa", "data-date-format" : "dd/mm/yyyy") }}</td>
            <?php } else { ?>
              <td><input type="checkbox" class="empleadocheck"></td>
              <td>{{ empleado.numDocumento }}</td>
              <td>{{ empleado.getNombrecompleto() }}</td>
              <td><input type="hidden" name="id_hcbempleado[]" value="{{ empleado.id_hcbempleado }}" class="tipo-fecha" disabled="disabled">{{ text_field("fechamaniana[]", "type" : "date", "class" : "form-control tipo-fecha required", "placeholder" : "dd/mm/aaaa", "data-date-format" : "dd/mm/yyyy", "disabled" : "disabled") }}</td>
              <td>{{ text_field("fechatarde[]", "type" : "date", "class" : "form-control tipo-fecha", "placeholder" : "dd/mm/aaaa", "data-date-format" : "dd/mm/yyyy", "disabled" : "disabled") }}</td>
            <?php } ?>

        </tr>
    {% endfor %}
    </tbody>
</table>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
          {{ submit_button("Guardar", "class" : "btn btn-default") }}
    </div>
</div>
<div id="festivos" style="display:none"><?php echo $this->elements->festivos(); ?></div>
<input type="hidden" name="fecha_inicio" id="fecha_inicio" value="{{ fecha_inicio }}">
<input type="hidden" name="fecha_fin" id="fecha_fin" value="{{ fecha_fin }}">
</form>
<div class='clear'></div>
