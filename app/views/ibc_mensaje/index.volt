
{{ content() }}
<h1>Mensajes</h1>

<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#anuncios" role="tab" data-toggle="tab">Anuncios</a></li>
  <li role="presentation" class=""><a href="#mensajes" role="tab" data-toggle="tab">Mensajes</a></li>
  
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="anuncios">Anuncios...</div>
    <div role="tabpanel" class="tab-pane" id="mensajes">Mensajes...</div>
  </div>
{% if (not(usuarios is empty)) %}
{% endif %}