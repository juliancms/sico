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
                'nivelPermiso' => '3'
            ),
            'mensajes' => array(
                'nivelPermiso' => '3'
            ),
        	'anuncios' => array(
        		'nivelPermiso' => '3'
        	),
            'crear' => array(
                'nivelPermiso' => '3'
            ),
        	'comentario' => array(
        		'nivelPermiso' => '3'
        	)
        ),
    	'cob_periodo' => array(
    		'index' => array(
    				'nivelPermiso' => '3'
    		),
    		'nuevo' => array(
    				'nivelPermiso' => '1'
    		),
    		'ver' => array(
    				'nivelPermiso' => '3'
    		),
    		'recorrido' => array(
    				'nivelPermiso' => '3'
    		),
    		'rutear' => array(
    				'nivelPermiso' => '1'
    		),
    		'ruteoguardar' => array(
    				'nivelPermiso' => '1'
    		),
    		'nuevorecorrido' => array(
    				'nivelPermiso' => '1'
    		),
    		'nuevorecorrido1' => array(
    				'nivelPermiso' => '1'
    		),
    		'editar' => array(
    				'nivelPermiso' => '1'
    		),
    		'crear' => array(
    				'nivelPermiso' => '1'
    		),
    		'generarrecorrido1' => array(
    				'nivelPermiso' => '1'
    		),
    		'generarrecorrido' => array(
    				'nivelPermiso' => '1'
    		),
    		'guardar' => array(
    				'nivelPermiso' => '1'
    		),
    		'eliminar' => array(
    				'nivelPermiso' => '1'
    		)
    	),
    	'bc_carga' => array(
    		'index' => array(
    				'nivelPermiso' => '1'
    		),
    		'nuevo' => array(
    				'nivelPermiso' => '1'
    		),
    		'crear' => array(
    				'nivelPermiso' => '1'
    		),
    		'eliminar' => array(
    				'nivelPermiso' => '1'
    		)
    	),
    	'ibc_usuario' => array(
    		'index' => array(
    				'nivelPermiso' => '3'
    		),
    		'nuevo' => array(
    				'nivelPermiso' => '1'
    		),
    		'ver' => array(
    				'nivelPermiso' => '1'
    		),
    		'editar' => array(
    				'nivelPermiso' => '2'
    		),
    		'crear' => array(
    				'nivelPermiso' => '2'
    		),
    		'guardar' => array(
    				'nivelPermiso' => '2'
    		),
    		'eliminar' => array(
    				'nivelPermiso' => '1'
    		),
    		'interventores' => array(
    				'nivelPermiso' => '1'
    		),
    		'editarperfil' => array(
    				'nivelPermiso' => '3'
    		)	
    	),
    	'errores' => array(
    		'error401' => array(
    				'nivelPermiso' => '-2'
    		),
    		'error404' => array(
    				'nivelPermiso' => '-2'
    		),
    		'error500' => array(
    				'nivelPermiso' => '-2'
    		)	
    	),
    	'cob_actaconteo' => array(
    		'index' => array(
    				'nivelPermiso' => '3'
    		),'ver' => array(
    				'nivelPermiso' => '3'
    		),'datos' => array(
    				'nivelPermiso' => '3'
    		),'guardardatos' => array(
    				'nivelPermiso' => '3'
    		),'guardarbeneficiarios' => array(
    				'nivelPermiso' => '3'
    		),'guardaradicionales' => array(
    				'nivelPermiso' => '3'
    		),'beneficiarios' => array(
    				'nivelPermiso' => '3'
    		),'adicionales' => array(
    				'nivelPermiso' => '3'
    		),'subiradicional' => array(
    				'nivelPermiso' => '3'
    		),'cerrar' => array(
    				'nivelPermiso' => '3'
    		),'abrir' => array(
    				'nivelPermiso' => '2'
    		)
    			
    	),
    	'session' => array(
    		'start' => array(
    				'nivelPermiso' => '-2'
    		),'end' => array(
    				'nivelPermiso' => '-2'
    		)
    	),
    	'index' => array(
    		'index' => array(
    				'nivelPermiso' => '-2'
    		),'end' => array(
    				'nivelPermiso' => '-2'
    		)
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
            if($user['nivel'] <= $this->_permiso[$controlador][$accion]['nivelPermiso'] || $this->_permiso[$controlador][$accion]['nivelPermiso'] == -2){
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
