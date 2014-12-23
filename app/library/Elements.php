<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Ayudan para la construcción de los elementos UI de la aplicación
 */
class Elements extends Component
{

    private $_headerMenu = array(
        'navbar-left' => array(
            'index' => array(
                'caption' => 'Inicio',
                'action' => 'index'
            ),
            'cob_periodo' => array(
                'caption' => 'Periodos',
                'action' => 'index'
            ),
            'bc_carga' => array(
                'caption' => 'Cargas',
                'action' => 'index'
            ),
        ),
        'navbar-right' => array(
            'session' => array(
                'caption' => 'Iniciar Sesión',
                'action' => 'index'
            ),
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
    
    

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->_headerMenu['navbar-right']['session'] = array(
                'caption' => 'Log Out',
                'action' => 'end'
            );
        } else {
            //unset($this->_headerMenu['navbar-left']['controller']);
        }

        $controllerName = $this->view->getControllerName();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<div class="nav-collapse">';
            echo '<ul class="nav navbar-nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                echo $this->tag->linkTo($controller . '/' . $option['action'], $option['caption']);
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
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
     * Returns a select
     */
    public function getSelect($select)
    {
    	switch ($select) {
    		case "asistencia":
    			return array (
    			'1' => '1',
    			'2' => '2',
    			'3' => '3',
    			'4' => '4',
    			'5' => '5',
    			'6' => '6',
    			'7' => '7',
    			'8' => '8',
    			'9' => '9',
    			'10' => '10');
    			break;
    		case "datos_valla":
    			return array (
    			'1' => 'Si cuenta con valla, según manual del Programa Buen Comienzo',
    			'2' => 'No cuenta con valla, según manual del Programa Buen Comienzo',
    			'3' => 'No cuenta con ningún tipo de valla de identificación');
    			break;
    		case "meses":
    			return array("Enero" => "Enero", "Febrero" => "Febrero", "Marzo" => "Marzo", "Abril" => "Abril", "Mayo" => "Mayo", "Junio" => "Junio", "Agosto" => "Agosto", "Septiembre" => "Septiembre", "Octubre" => "Octubre", "Noviembre" => "Noviembre", "Diciembre" => "Diciembre");
    			break;
    		case "sino":
    			return array("1" => "Sí", "2" => "No");
    			break;
    		default:
    			return array();
    	}
    }
}
