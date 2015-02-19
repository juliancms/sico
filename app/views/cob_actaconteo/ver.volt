
{{ content() }}
{{ elements.getActamenu(acta) }}
{% if (nivel <= 1 and acta.recorrido == recorridos) %}
{{ link_to("cob_actaconteo/duplicaracta/"~acta.id_actaconteo, '<i class="glyphicon glyphicon-retweet"></i> Duplicar', "class": "btn btn-primary menu-tab") }}
{% endif %}
{{ acta_html }} 