<?php
 
use Phalcon\Mvc\Model\Criteria;

class IbcArchivoDigitalController extends ControllerBase
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
        $menu = BcOferenteMenu::findFirstByid_usuario($this->id_usuario);
        if (substr($this->conversiones->get_client_ip(), 0, 7) == "192.168"){
        	$this->view->url = "http://192.168.2.4/owncloud/" . $menu->menu;
        } else {
        	$this->view->url = "http://190.248.150.222:842/owncloud/" . $menu->menu;
        }
    }

}
