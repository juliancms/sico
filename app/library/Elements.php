<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Ayudan para la construcción de los elementos UI de la aplicación
 */
class Elements extends Component
{
	private $_actaMenu = array(
		'acta' => array(
				'caption' => 'Acta',
				'action' => 'ver',
				'icon' => 'glyphicon-list-alt'
		),
		'datos' => array(
				'caption' => 'Datos',
				'action' => 'datos',
				'icon' => 'glyphicon-file'
		),
		'beneficiarios' => array(
				'caption' => 'Beneficiarios',
				'action' => 'beneficiarios',
				'icon' => 'glyphicon-saved'
		),
		'adicionales' => array(
				'caption' => 'Adicionales',
				'action' => 'adicionales',
				'icon' => 'glyphicon-open'
		),
		'adicionalescapturas' => array(
				'caption' => 'Capturas Adicionales',
				'action' => 'adicionalescapturas',
				'icon' => 'glyphicon-upload'
		),
		'empleados' => array(
				'caption' => 'Empleados',
				'action' => 'empleados',
				'icon' => 'glyphicon-briefcase'
		)
	);
	
	private $_actametroMenu = array(
			'acta' => array(
					'caption' => 'Acta',
					'action' => 'ver',
					'icon' => 'glyphicon-list-alt'
			),
			'datos' => array(
					'caption' => 'Datos',
					'action' => 'datos',
					'icon' => 'glyphicon-file'
			),
			'beneficiarios' => array(
					'caption' => 'Beneficiarios',
					'action' => 'beneficiarios',
					'icon' => 'glyphicon-saved'
			)
	);
	
	private $_actacomputoMenu = array(
			'acta' => array(
					'caption' => 'Acta',
					'action' => 'ver',
					'icon' => 'glyphicon-list-alt'
			),
			'datos' => array(
					'caption' => 'Datos',
					'action' => 'datos',
					'icon' => 'glyphicon-file'
			)
	);
	
	private $_mensajeMenu = array(
			'anuncios' => array(
					'caption' => 'Anuncios',
					'action' => 'anuncios'
			),
			'mensajes' => array(
					'caption' => 'Mensajes',
					'action' => 'mensajes'
			)
			
	);
    private $_headerMenu = array(
            'ibc_mensaje' => array(
                'caption' => 'Comunicaciones',
                'action' => 'anuncios'
            ),
            'cob_periodo' => array(
                'caption' => 'Periodos',
                'action' => 'index'
            ),
    		'cob_verificacion' => array(
    				'caption' => 'Verificaciones',
    				'action' => 'index'
    		)
    );
    
    private $_headerMenuOferente = array(
    		'ibc_mensaje' => array(
    				'caption' => 'Comunicaciones',
    				'action' => 'anuncios'
    		),
    		'ibc_archivo_digital' => array(
    				'caption' => 'Archivo Digital',
    				'action' => 'index'
    		),
    		'ibc_instrumentos' => array(
    				'caption' => 'Instrumentos',
    				'action' => 'index'
    		),
    		'bc_reporte' => array(
    				'caption' => 'Reportes',
    				'action' => 'oferente_contratos'
    		)
    );
    
    private $_headerMenuComponente = array(
    		'ibc_mensaje' => array(
    				'caption' => 'Comunicaciones',
    				'action' => 'anuncios'
    		)
    );

    private $_tabs = array(
        'Periodos' => array(
            'controller' => 'cob_periodo',
            'action' => 'index',
            'any' => false
        ),
        'Nuevo Periodo' => array(
            'controller' => 'cob_periodo',
            'action' => 'nuevo',
            'any' => false
        )
    );
    
    private $_MenuInicio = array(
    		'index' => array(
    				'caption' => 'Inicio',
    				'controller' => 'index'
    		),
    		'quienessomos' => array(
    				'caption' => 'Quiénes Somos',
    				'controller' => 'index'
    		),
    		'directorio' => array(
    				'caption' => 'Directorio Telefónico',
    				'controller' => 'index'
    		),
    		'contacto' => array(
    				'caption' => 'Contacto',
    				'controller' => 'index'
    		)
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {
        $user = $this->session->get('auth');
        if ($user) {
        	$menu_usuario = "";
            $controllerName = $this->view->getControllerName();
            echo '<div class="nav-collapse">';
            echo '<ul class="nav navbar-nav navbar-left">';
            if($user['id_usuario_cargo'] == 6){
            	$menu = $this->_headerMenuOferente;
            	
            } else if($user['id_usuario_cargo'] == 7){
            	$menu = $this->_headerMenuComponente;
            } else {
            	$menu = $this->_headerMenu;
            	
            	if($user['nivel'] <= 1){
            		$menu ['bc_carga'] = array ('caption' => 'Cargas', 'action' => 'index');
            	}
            	if($user['nivel'] <= 2){
            		$menu ['cob_ajuste'] = array ('caption' => 'Ajustes', 'action' => 'index');
            		$menu ['bc_reporte'] = array ('caption' => 'Reportes', 'action' => '');
            		$menu ['ibc_usuario'] = array ('caption' => 'Usuarios', 'action' => 'index');
            	}
            	$menu_usuario .= '<li role="presentation" class="divider"></li>';
            	$menu_usuario .= '<li><a target="_blank" href="http://interventoriabuencomienzo.org/redirect_server2.php?sico">Permisos</a></li>';
            	$menu_usuario .= '<li><a target="_blank" href="http://www.asesoriayconsultoria.pascualbravo.org/index.php?option=com_content&amp;view=article&amp;id=314&amp;Itemid=183">Reporte de Pago</a></li>';
            	$menu_usuario .= '<li><a target="_blank" href="http://www.interventoriabuencomienzo.org:2095">Correo Institucional</a></li>';
            	$menu_usuario .= '<li><a target="_blank" href="http://interventoriabuencomienzo.org/redirect_owncloud.php">Owncloud</a></li>';
            }
            foreach ($menu as $controller => $option) {
            	if($controller == "bc_reporte"){
            		if ($controllerName == $controller) {
            			echo '<li class="dropdown bc_reporte active">';
            		} else {
            			echo '<li class="dropdown bc_reporte">';
            		}
            		echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">Reportes <b class="caret"></b></a>';
            		echo '<ul class="dropdown-menu">';
            		echo '<li>'.$this->tag->linkTo("bc_reporte/contratos_liquidacion", "Generar Reporte Liquidación").'</li>';
            		echo '<li>'.$this->tag->linkTo("bc_reporte/oferentes_contratos", "Reportes Prestadores").'</li>';
            		echo '</ul>';
            		echo '</li>';
            	} else {
            		if ($controllerName == $controller) {
            			echo '<li class="active">';
            		} else {
            			echo '<li>';
            		}
            		echo $this->tag->linkTo($controller . '/' . $option['action'], $option['caption']);
            		echo '</li>';
            	}
            }
            echo '</ul>';
            echo '</div>';
            echo '<div class="nav-collapse">';
            echo '<ul class="nav navbar-nav navbar-right">';
            echo '<li>';
            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="glyphicon glyphicon-globe"></b></a>';
            echo '</li>';
	        echo '<li class="dropdown usuario">';
	        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 10px !important; padding-bottom: 10px !important;"><img class="foto" src="'.$user['foto'].'" width="30px" height="30px"> '.explode(" ", $user['nombre'])[0].' <b class="caret"></b></a>';
	        echo '<ul class="dropdown-menu">';
	            echo '<li>'.$this->tag->linkTo("ibc_usuario/editarperfil", "Editar Perfil").'</li>';
	            echo $menu_usuario;
	            echo '<li role="presentation" class="divider"></li>';
	            echo '<li>'.$this->tag->linkTo("session/end", "Cerrar Sesión").'</li>';
	        echo '</ul>';
	        echo '</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<form action="/sico/session/start" class="navbar-form navbar-right" role="form" method="post">
	        <div class="form-group">
	          <input type="text" name="usuario" placeholder="Usuario o Email" class="form-control">
	        </div>
	        <div class="form-group">
	          <input type="password" name="password" placeholder="Contraseña" class="form-control">
	        </div>
	        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
      		</form>';
        }

    }
    
    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenuInicio()
    {
   		$controllerName = $this->view->getControllerName();
   		$actionName = $this->view->getActionName();
   		foreach ($this->_MenuInicio as $action => $option) {
   			if ($actionName == $action) {
   				echo $this->tag->linkTo(array($option['controller'] . '/' . $action, $option['caption'], 'class' => 'list-group-item active'));
   			} else {
   				echo $this->tag->linkTo(array($option['controller'] . '/' . $action, $option['caption'], 'class' => 'list-group-item'));
   			}
   		}    		
    }

    /**
     * Returns menu tabs
     */
    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
                echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption), '</li>';
            } else if($option['controller'] == $controllerName) {
                echo '<li>';
                echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption), '</li>';
            }
        }
        echo '</ul>';
    }
    
    /**
     * Construye el menú superior de las actas
     *
     * @return string
     */
    public function getMensajeMenu()
    {
    	$user = $this->session->get('auth');
    	$actionName = $this->view->getActionName();
    	echo "<ul style='margin-bottom: 10px;' class='nav nav-tabs' role='tablist'>";
    	foreach ($this->_mensajeMenu as $menu) {
        	$action = $menu['action'];
        	$caption = $menu['caption'];
    		if($actionName == $menu['action']){
    			echo "<li role='presentation' class='active'><a>$caption</a></li>";
    		} else {
    			echo "<li role='presentation'><a href='/sico/ibc_mensaje/$action/'>$caption</a></li>";
    		}
    	}
    	echo "</ul>";
    }
    
    /**
     * Construye el menú superior de las actas
     *
     * @return string
     */
    public function getActamenu($acta)
    {
    	$user = $this->session->get('auth');
    	$actionName = $this->view->getActionName();
    	echo "<div class='no-imprimir'><h1>".ucfirst($actionName)." <small><span style='cursor:pointer;' data-toggle='collapse' data-target='#info_acta'>Acta No. $acta->id_actaconteo <b class='caret'></b></span></small></h1>";
    	echo "<div id='info_acta' class='collapse'>";
    	echo "<table class='table table-bordered table-hover'>";
    	echo "<thead><tr>";
    	echo "<th>Prestador</th>";
    	echo "<th>Modalidad</th>";
    	echo "<th>Sede</th>";
    	echo "<th>Dirección</th>";
    	echo "<th>Teléfono</th>";
    	echo "<th>Interventor</th>";
    	echo "</tr></thead><tbody><tr>";
    	echo "<td>".$acta->oferente_nombre."</td>";
    	echo "<td>".$acta->modalidad_nombre."</td>";
    	echo "<td>".$acta->sede_nombre."</td>";
    	echo "<td>".$acta->sede_direccion."</td>";
    	echo "<td>".$acta->sede_telefono."</td>";
    	echo "<td>".$acta->id_usuario."</td>";
    	echo "</tr></tbody></table>";
    	echo "</div>";
    	echo "<a href='/sico/cob_periodo/recorrido/$acta->id_periodo/$acta->recorrido' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Regresar</a>";
    	//Si no es el recorrido 1 quita el menú de adicionales
    	if($acta->recorrido > 1){
    		unset($this->_actaMenu['adicionales']);
    		unset($this->_actaMenu['adicionalescapturas']);
    		unset($this->_actaMenu['empleados']);
    	}
    	foreach ($this->_actaMenu as $menu) {
    		$action = $menu['action'];
    		$caption = $menu['caption'];
    		$icon = $menu['icon'];
    		if($actionName == $menu['action']){
    			echo "<a class='btn btn-primary menu-tab disabled'><i class='glyphicon $icon'></i> $caption</a>";
    		} else {
    			echo "<a href='/sico/cob_actaconteo/$action/$acta->id_actaconteo' class='btn btn-primary menu-tab'><i class='glyphicon $icon'></i> $caption</a>";
    		}
    	}
    	$uri = str_replace($this->url->getBaseUri(), '', str_replace($_SERVER["SCRIPT_NAME"], '', $_SERVER["REQUEST_URI"]));
    	//SI el acta pertenece al interventor o auxiliar y no está cerrada
    	if($acta->id_usuario != 0 && (($acta->id_usuario == $user['id_usuario'] && $acta->estado < 2) || ($acta->IbcUsuario->id_usuario_lider == $user['id_usuario'] && $acta->estado < 3))){
    		echo "<form class='menu-tab' action='/sico/cob_actaconteo/cerrar/$acta->id_actaconteo' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-danger' value='Cerrar Acta'></form>";
    	}
    	if($acta->estado == 2 && $acta->IbcUsuario->id_usuario_lider == $user['id_usuario']){
    		echo "<form class='menu-tab' action='/sico/cob_actaconteo/abrir/$acta->id_actaconteo' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-info' value='Abrir Acta'></form>";
    	}
		echo "</div><div class='clear'></clear>";
    }
    
    /**
     * Construye el menú superior de las actas
     *
     * @return string
     */
    public function getActametrosaludmenu($acta)
    {
    	$user = $this->session->get('auth');
    	$actionName = $this->view->getActionName();
    	echo "<div class='no-imprimir'><h1>".ucfirst($actionName)." <small><span style='cursor:pointer;' data-toggle='collapse' data-target='#info_acta'>Acta No. $acta->id_actamuestreo <b class='caret'></b></span></small></h1>";
    	echo "<div id='info_acta' class='collapse'>";
    	echo "<table class='table table-bordered table-hover'>";
    	echo "<thead><tr>";
    	echo "<th>Prestador</th>";
    	echo "<th>Modalidad</th>";
    	echo "<th>Sede</th>";
    	echo "<th>Dirección</th>";
    	echo "<th>Teléfono</th>";
    	echo "<th>Interventor</th>";
    	echo "</tr></thead><tbody><tr>";
    	echo "<td>".$acta->oferente_nombre."</td>";
    	echo "<td>".$acta->modalidad_nombre."</td>";
    	echo "<td>".$acta->sede_nombre."</td>";
    	echo "<td>".$acta->sede_direccion."</td>";
    	echo "<td>".$acta->sede_telefono."</td>";
    	echo "<td>".$acta->id_usuario."</td>";
    	echo "</tr></tbody></table>";
    	echo "</div>";
    	echo "<a href='/sico/cob_periodo/recorrido/$acta->id_periodo/$acta->recorrido' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Regresar</a>";
    	//Si no es el recorrido 1 quita el menú de adicionales
    	if($acta->recorrido > 1){
    	unset($this->_actaMenu['adicionales']);
    	}
    	foreach ($this->_actametroMenu as $menu) {
        	$action = $menu['action'];
        	$caption = $menu['caption'];
        	$icon = $menu['icon'];
    	if($actionName == $menu['action']){
    	echo "<a class='btn btn-primary menu-tab disabled'><i class='glyphicon $icon'></i> $caption</a>";
    	} else {
    	echo "<a href='/sico/cob_actamuestreo/$action/$acta->id_actamuestreo' class='btn btn-primary menu-tab'><i class='glyphicon $icon'></i> $caption</a>";
    	}
    	}
    	$uri = str_replace($this->url->getBaseUri(), '', str_replace($_SERVER["SCRIPT_NAME"], '', $_SERVER["REQUEST_URI"]));
    	//SI el acta pertenece al interventor o auxiliar y no está cerrada
    	if($acta->id_usuario != 0 && (($acta->id_usuario == $user['id_usuario'] && $acta->estado < 2) || ($acta->IbcUsuario->id_usuario_lider == $user['id_usuario'] && $acta->estado < 3))){
    	echo "<form class='menu-tab' action='/sico/cob_actamuestreo/cerrar/$acta->id_actamuestreo' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-danger' value='Cerrar Acta'></form>";
    	}
    	if($acta->estado == 2 && $acta->IbcUsuario->id_usuario_lider == $user['id_usuario']){
    	echo "<form class='menu-tab' action='/sico/cob_actamuestreo/abrir/$acta->id_actamuestreo' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-info' value='Abrir Acta'></form>";
    	}
    	echo "</div><div class='clear'></clear>";
    }
    
    /**
     * Construye el menú superior de las actas
     *
     * @return string
     */
    public function getActaverificacionmenu($acta)
    {
    	$user = $this->session->get('auth');
    	$actionName = $this->view->getActionName();
    	$controllerName = $this->view->getControllerName();
    	echo "<div class='no-imprimir'><h1>".ucfirst($actionName)." <small><span style='cursor:pointer;' data-toggle='collapse' data-target='#info_acta'>Acta No. $acta->id_acta <b class='caret'></b></span></small></h1>";
    	echo "<div id='info_acta' class='collapse'>";
    	echo "<table class='table table-bordered table-hover'>";
    	echo "<thead><tr>";
    	echo "<th>Prestador</th>";
    	echo "<th>Modalidad</th>";
    	echo "<th>Sede</th>";
    	echo "<th>Dirección</th>";
    	echo "<th>Teléfono</th>";
    	echo "<th>Interventor</th>";
    	echo "</tr></thead><tbody><tr>";
    	echo "<td>".$acta->oferente_nombre."</td>";
    	echo "<td>".$acta->modalidad_nombre."</td>";
    	echo "<td>".$acta->sede_nombre."</td>";
    	echo "<td>".$acta->sede_direccion."</td>";
    	echo "<td>".$acta->sede_telefono."</td>";
    	echo "<td>".$acta->id_usuario."</td>";
    	echo "</tr></tbody></table>";
    	echo "</div>";
    	echo "<a href='/sico/cob_verificacion/ver/$acta->id_verificacion' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Regresar</a>";
    	foreach ($this->_actametroMenu as $menu) {
    		$action = $menu['action'];
    		$caption = $menu['caption'];
    		$icon = $menu['icon'];
    		if($actionName == $menu['action']){
		    	echo "<a class='btn btn-primary menu-tab disabled'><i class='glyphicon $icon'></i> $caption</a>";
		    } else {
		    	echo "<a href='/sico/$controllerName/$action/$acta->id_acta' class='btn btn-primary menu-tab'><i class='glyphicon $icon'></i> $caption</a>";
		    }
    	}
    	$uri = str_replace($this->url->getBaseUri(), '', str_replace($_SERVER["SCRIPT_NAME"], '', $_SERVER["REQUEST_URI"]));
    	//SI el acta pertenece al interventor o auxiliar y no está cerrada
    	if($acta->id_usuario != 0 && (($acta->id_usuario == $user['id_usuario'] && $acta->estado < 2) || ($acta->IbcUsuario->id_usuario_lider == $user['id_usuario'] && $acta->estado < 3))){
    	echo "<form class='menu-tab' action='/sico/$controllerName/cerrar/$acta->id_acta' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-danger' value='Cerrar Acta'></form>";
    	}
    	if($acta->estado == 2 && $acta->IbcUsuario->id_usuario_lider == $user['id_usuario']){
    	echo "<form class='menu-tab' action='/sico/$controllerName/abrir/$acta->id_acta' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-info' value='Abrir Acta'></form>";
    	}
    	echo "</div><div class='clear'></clear>";
    }
    
    /**
     * Construye el menú superior de las actas
     *
     * @return string
     */
    public function getActacomputomenu($acta)
    {
    	$user = $this->session->get('auth');
    	$actionName = $this->view->getActionName();
    	$controllerName = $this->view->getControllerName();
    	echo "<div class='no-imprimir'><h1>".ucfirst($actionName)." <small><span style='cursor:pointer;' data-toggle='collapse' data-target='#info_acta'>Acta No. $acta->id_acta <b class='caret'></b></span></small></h1>";
    	echo "<div id='info_acta' class='collapse'>";
    	echo "<table class='table table-bordered table-hover'>";
    	echo "<thead><tr>";
    	echo "<th>Prestador</th>";
    	echo "<th>Modalidad</th>";
    	echo "<th>Sede</th>";
    	echo "<th>Dirección</th>";
    	echo "<th>Teléfono</th>";
    	echo "<th>Interventor</th>";
    	echo "</tr></thead><tbody><tr>";
    	echo "<td>".$acta->oferente_nombre."</td>";
    	echo "<td>".$acta->modalidad_nombre."</td>";
    	echo "<td>".$acta->sede_nombre."</td>";
    	echo "<td>".$acta->sede_direccion."</td>";
    	echo "<td>".$acta->sede_telefono."</td>";
    	echo "<td>".$acta->id_usuario."</td>";
    	echo "</tr></tbody></table>";
    	echo "</div>";
    	echo "<a href='/sico/cob_verificacion/ver/$acta->id_verificacion' class='btn btn-primary regresar'><i class='glyphicon glyphicon-chevron-left'></i> Regresar</a>";
    	foreach ($this->_actacomputoMenu as $menu) {
    	$action = $menu['action'];
    			$caption = $menu['caption'];
    		$icon = $menu['icon'];
        		if($actionName == $menu['action']){
        				echo "<a class='btn btn-primary menu-tab disabled'><i class='glyphicon $icon'></i> $caption</a>";
    	} else {
    	echo "<a href='/sico/$controllerName/$action/$acta->id_acta' class='btn btn-primary menu-tab'><i class='glyphicon $icon'></i> $caption</a>";
    	}
    	}
    	$uri = str_replace($this->url->getBaseUri(), '', str_replace($_SERVER["SCRIPT_NAME"], '', $_SERVER["REQUEST_URI"]));
    			//SI el acta pertenece al interventor o auxiliar y no está cerrada
    	if($acta->id_usuario != 0 && (($acta->id_usuario == $user['id_usuario'] && $acta->estado < 2) || ($acta->IbcUsuario->id_usuario_lider == $user['id_usuario'] && $acta->estado < 3))){
    	echo "<form class='menu-tab' action='/sico/$controllerName/cerrar/$acta->id_acta' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-danger' value='Cerrar Acta'></form>";
    	}
    	if($acta->estado == 2 && $acta->IbcUsuario->id_usuario_lider == $user['id_usuario']){
    	echo "<form class='menu-tab' action='/sico/$controllerName/abrir/$acta->id_acta' method='post'><input type='hidden' name='uri' value='$uri'><input type='submit' class='btn btn-info' value='Abrir Acta'></form>";
    	}
    	echo "</div><div class='clear'></clear>";
    	}
    
    /**
     * Returns a select
     */
    public function getSelect($select)
    {
    	switch ($select) {
    		case "asistencia":
    			return array (
    			'1' => '1',
    			'4' => '4',
    			'5' => '5',
    			'6' => '6',
    			'7' => '7',
    			'8' => '8');
    			break;
    		case "asistenciaempleados":
    			return array (
    			'1' => '1',
    			'6' => '6');
    			break;
    		case "cargoempleados":
    			return array (
    			'1' => 'Auxiliar Educativo',
    			'2' => 'Docente');
    			break;
    		case "datos_valla":
    			return array (
    			'1' => '1. Si cuenta con valla, según manual del Programa Buen Comienzo',
    			'2' => '2. No cuenta con valla, según manual del Programa Buen Comienzo',
    			'3' => '3. No cuenta con ningún tipo de valla de identificación');
    			break;
    		case "dotacion":
    			return array (
    			'1' => '1. Sí cuenta con dotación, según manual del Programa Buen Comienzo',
    			'2' => '2. Sí cuenta con dotación, pero no según manual del Programa Buen Comienzo',
    			'3' => '3. No cuenta con ningún tipo de dotación');
    			break;
    		case "meses":
    			return array("Enero" => "Enero", "Febrero" => "Febrero", "Marzo" => "Marzo", "Abril" => "Abril", "Mayo" => "Mayo", "Junio" => "Junio", "Julio" => "Julio", "Agosto" => "Agosto", "Septiembre" => "Septiembre", "Octubre" => "Octubre", "Noviembre" => "Noviembre", "Diciembre" => "Diciembre");
    			break;
    		case "sino":
    			return array("1" => "Sí", "2" => "No");
    			break;
    		case "sinona":
    			return array("1" => "Sí", "2" => "No", "3" => "N/A");
    			break;
    		case "sinonare":
    			return array("1" => "Sí", "2" => "No", "3" => "N/A", "4" => "Retirado");
    			break;
    		case "ciclovital":
    			return array("1" => "G", "2" => "L", "4" => "N", "5" => "NM", "6" => "NG", "0" => "N/A");
    			break;
    		default:
    			return array();
    	}
    }
}
