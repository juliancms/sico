<?php
 
use Phalcon\Mvc\Model\Criteria;

class IbcInstrumentosController extends ControllerBase
{    
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Periodos");
        $this->user = $this->session->get('auth');
        $this->id_usuario = $this->user['id_usuario'];
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        if (substr($this->conversiones->get_client_ip(), 0, 7) == "192.168"){
        	$this->view->url = "http://192.168.2.79/owncloud/public.php?service=files&t=74c4f9e9e6fd060752681184b4d0fa32";
        } else {
        	$this->view->url = "http://190.248.150.222:347/owncloud/public.php?service=files&t=74c4f9e9e6fd060752681184b4d0fa32";
        }
    }

}
