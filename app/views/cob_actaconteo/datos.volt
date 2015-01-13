{{ content() }}
{{ elements.getActamenu(acta) }}
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
                {{ text_field("horaInicio", "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Hora Fin</label>
        <div class="col-sm-10">
                {{ text_field("horaFin", "class" : "form-control required") }}
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
        <label class="col-sm-2 control-label" for="fecha">* Observación Encargado Sede</label>
        <div class="col-sm-10">
                {{ text_area("observacionEncargado", "rows" : "4", "class" : "form-control required") }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="fecha">* Observación Interventor</label>
        <div class="col-sm-10">
                {{ text_area("observacionUsuario", "rows" : "4", "class" : "form-control required") }}
        </div>
    </div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
          {{ submit_button("Guardar", "class" : "btn btn-default") }}
    </div>
</div>
</form>