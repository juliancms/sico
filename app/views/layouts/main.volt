<nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">IBC <span class="caret"></span></a>
			<div class="dropdown-menu list-group" role="menu">
				{{ elements.getMenuInicio() }}
			</div>
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
        <p>Interventoría Buen Comienzo</p>
    </footer>
</div>
