
{{ content() }}
<h1>Periodos - Cronograma Hogares Comunitarios</h1>
{{ elements.getcronogramahcbMenu() }}
{% if (not(periodos is empty)) %}
{% for periodo in periodos %}
{{ link_to("bc_hcb/ver/"~periodo.id_hcbperiodo, periodo.getMes(), "class": "btn btn-default btn-lg btn-block") }}
{% endfor %}
{% endif %}
