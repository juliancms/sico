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
    }

    /**
     * Registra el usuario autenticado en los datos de la sesión (session)
     *
     * @param IbcUsuario $user
     */
    private function _registerSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id_usuario,
        	'usuario' => $user->usuario,
        	'email' => $user->email,
            'nombre' => $user->nombre
        ));
    }

    /**
     * Autenticación y logueo del usuario en la aplicación
     *
     */
    
    public function startAction()
    {
    	if ($this->request->isPost()) {
            $usuario = $this->request->getPost('usuario');
            $password = $this->request->getPost('password');
            $user = IbcUsuario::findFirst(array("email='$usuario' OR usuario = '$usuario'"));
            if ($user) {            	
            	if ($this->security->checkHash($password, $user->password)) {
            		$this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->nombre);
                return $this->response->redirect('index/index');
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
