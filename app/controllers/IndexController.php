<?php

use Phalcon\Mvc\Model\Criteria;

class IndexController extends ControllerBase
{
	public $user;
	
    public function initialize()
    {
        $this->user = $this->session->get('auth');
        if (!$this->user) {
        	return $this->response->redirect('session/index');
        }
        return $this->response->redirect('ibc_mensaje/index');
        parent::initialize();
    }

    public function indexAction()
    {
    	$this->persistent->parameters = null;
    }
}
