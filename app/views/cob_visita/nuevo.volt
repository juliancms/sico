
{{ content() }}
<h1>Nueva Visita</h1>
{{ link_to("cob_visita/", '<i class="glyphicon glyphicon-chevron-left"></i> Regresar', "class": "btn btn-primary menu-tab") }}
{{ form("cob_visita/crear", "method":"post", "class":"form-container form-horizontal", "parsley-validate" : "") }}
    <div class="form-group">
        <label class="col-sm-2 control-label" for="tipo">Tipo de Visita</label>
        <div class="col-sm-10">
        	<select id="tipo" name="tipo" class="form-control">
					<option value="1">Revisión de Carpetas</option>
					<option value="2">Equipo de Cómputo</option>
			</select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">Fecha de Visita</label>
        <div class="col-sm-10">
                {{ text_field("fecha", "type" : "date", "class" : "form-control tipo-fecha", "placeholder" : "dd/mm/aaaa", "parsley-type" : "dateIso", "data-date-format" : "dd/mm/yyyy") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="nombre">Título</label>
        <div class="col-sm-10">
                {{ text_field("nombre", "class" : "form-control", "placeholder" : "Título visita") }}
        </div>
    </div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
          {{ submit_button("Guardar", "class" : "btn btn-default") }}
    </div>
</div>
</form>