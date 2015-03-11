<?php

/**
 * SessionController
 *
 * Permite autenticar a los usuarios
 */
class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Iniciar Sesión');
        parent::initialize();
    }

    public function indexAction()
    {
    	$this->assets
    	->addCss('css/bootstrap-select.min.css')
    	->addJs('js/bootstrap-select.min.js')
    	->addJs('js/iniciar_sesion.js');
    	$oferentes = BcOferente::find();
    	$this->view->oferentes = $oferentes;
    }

    /**
     * Registra el usuario autenticado en los datos de la sesión (session)
     *
     * @param IbcUsuario $user
     */
    private function _registerSession($user, $tipo_usuario)
    {
    	//Si es oferente
    	if($tipo_usuario == 1){
    		$this->session->set('auth', array(
    				'id_usuario' => $user->id_oferente,
    				'abreviacion' => $user->abreviacion,
    				'email' => $user->email,
    				'nombre' => $user->nombre,
    				'id_usuario_cargo' => 6,
    				'nivel' => -1
    		));
    	} else {
    		$this->session->set('auth', array(
    				'id_usuario' => $user->id_usuario,
    				'id_componente' => $user->id_componente,
    				'componente' => $user->IbcComponente->nombre,
    				'usuario' => $user->usuario,
    				'email' => $user->email,
    				'nombre' => $user->nombre,
    				'id_usuario_cargo' => $user->id_usuario_cargo,
    				'foto' => $user->foto,
    				'estado' => $user->estado,
    				'nivel' => $user->IbcUsuarioCargo->nivelPermiso
    		));
    	}
    }

    /**
     * Autenticación y logueo del usuario en la aplicación
     *
     */
    
    public function startAction()
    {
    	if ($this->request->isPost()) {
    		$tipo_usuario = $this->request->getPost('tipo_usuario');
    		$password = $this->request->getPost('password');
    		if($tipo_usuario == 1){
    			$usuario = $this->request->getPost('id_oferente');
    			$user = BcOferente::findFirstByid_oferente($usuario);
    			if ($user) {
    				if ($this->security->checkHash($password, $user->password)) {
    					$this->_registerSession($user, $tipo_usuario);
    					$this->flash->success('Bienvenido ' . $user->nombre);
    					return $this->response->redirect('ibc_mensaje/anuncios');
    				}
    			}
    		} else {
    			$usuario = $this->request->getPost('usuario');
    			$user = IbcUsuario::findFirst(array("email='$usuario' OR usuario = '$usuario'"));
    			if ($user) {
    				if ($this->security->checkHash($password, $user->password)) {
    					$this->_registerSession($user, $tipo_usuario);
    					$this->flash->success('Bienvenido ' . $user->nombre);
    					return $this->response->redirect('ibc_mensaje/anuncios');
    				}
    			}
    		}
            $this->flash->error('Contraseña o usuario inválido');
        }
        return $this->response->redirect('session/index');
    }

    /**
     * Finalización de la sesión redireccionando al inicio
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('¡Hasta pronto!');
        return $this->response->redirect('session/index');
    }
}
