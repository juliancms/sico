<?php

use Phalcon\Events\Event,
Phalcon\Mvc\Dispatcher,
Phalcon\Mvc\User\Plugin;

/**
 * Security
 *
 * Gestiona los niveles de permisos de toda la aplicación
 */
class SecurityPlugin extends Plugin
{
    private $_permiso = array(
        'ibc_mensaje' => array(
            'index' => array(
                'nivelPermiso' => '-1'
            ),
            'mensajes' => array(
                'nivelPermiso' => '-1'
            ),
        	'anuncios' => array(
        		'nivelPermiso' => '-1'
        	),
            'crear' => array(
                'nivelPermiso' => '3'
            ),
        	'comentario' => array(
        		'nivelPermiso' => '-1'
        	)
        ),
        'navbar-right' => array(
            'session' => array(
                'caption' => 'Iniciar Sesión',
                'action' => 'index'
            ),
        )
    );

    /**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
    	$controlador = $dispatcher->getControllerName();
    	$accion = $dispatcher->getActionName();
        $user = $this->session->get('auth');
        if ($user) {
            if($user['nivel'] >= $this->_permiso[$controlador][$accion]['nivelPermiso']){
            	return TRUE;
            } else {
            	return $this->response->redirect('errores/error401');
            }
        } else if($controlador !== "session") {
        	return $this->response->redirect('session/index');
        } else {
        	return TRUE;
        }
    }
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function permiso($controlador = NULL)
    {
    	return TRUE;
    	if($controlador == NULL){
    		$controlador = $dispatcher->getControllerName();
    	}
    	$accion = $dispatcher->getActionName();
    	$user = $this->session->get('auth');
    	if ($user) {
    		if($user['nivel'] >= $this->_permiso[$controlador][$accion]['nivelPermiso']){
    			return TRUE;
    		} else {
    			return $this->response->redirect('errores/error401');
    		}
    	} else if($controlador !== "session") {
    		return $this->response->redirect('session/index');
    	} else {
    		return TRUE;
    	}
    }
}
