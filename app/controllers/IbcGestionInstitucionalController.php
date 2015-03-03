<?php
 
use Phalcon\Mvc\Model\Criteria;

class IbcGestionInstitucionalController extends ControllerBase
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
        echo $_SERVER["REMOTE_ADDR"]; break;
        $menu = GinOferenteMenu::findFirstByid_usuario($this->id_usuario);
        if ($_SERVER["REMOTE_ADDR"] == "http://190.248.150.222"){
        	$this->view->url = "http://190.248.150.222:347/owncloud/" . $menu->menu;
        } else {
        	$this->view->url = "http://192.168.2.79/owncloud/" . $menu->menu;
        }
    }

}
