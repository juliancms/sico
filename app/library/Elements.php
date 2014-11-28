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
}
