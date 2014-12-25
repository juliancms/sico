
{{ content() }}
<div class="no-imprimir">
<h1>Acta de Conteo No. {{ acta_datos.id_actaconteo }} </h1>
{{ link_to("cob_periodo/recorrido/"~acta_datos.id_periodo~"/"~acta_datos.recorrido, '<i class="glyphicon glyphicon-chevron-left"></i> Regresar', "class": "btn btn-primary menu-tab") }}
</div>
{{ acta_html }} 