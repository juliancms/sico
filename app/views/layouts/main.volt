<nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">SICO</a>
        </div>
        {{ elements.getMenu() }}
    </div>
</nav>
<div class="container">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    <footer>
    	{{ image("img/footer_logos.jpg", "alt": "Buen Comienzo") }}
        <p>&copy; 2014 Sistema de Información de Cobertura (SICO)</p>
    </footer>
</div>
