<?php
 
use Phalcon\Mvc\Model\Criteria;

class BcCargaController extends ControllerBase
{    
    public function initialize()
    {
        $this->tag->setTitle("Carga");
        $auth = $this->session->get('auth');
        if (!$auth || $auth->nivel > 1) {
        	return $this->response->redirect('session/index');
        }
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $bc_carga = BcCarga::find();
        if (count($bc_carga) == 0) {
            $this->flash->notice("No se ha agregado ninguna carga hasta el momento");
            $bc_carga = null;
        }
        $this->view->bc_carga = $bc_carga;
    }

    /**
     * Formulario para la reación deuna carga
     */
    public function nuevoAction()
    {
    	$this->view->meses = $this->elements->getSelect("meses");
    }
    
    /**
     * Para crear una carga, aquí es a donde se dirige el formulario de nuevoAction
     */
    public function crearAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(array(
    				"controller" => "bc_carga",
    				"action" => "index"
    		));
    	}
    	
    	$bc_carga = new BcCarga();
    	$bc_carga->mes = $this->request->getPost("mes");
    	$bc_carga->fecha = date('Y-m-d H:i:s');
    	
    	if($this->request->hasFiles() == true){
    		$uploads = $this->request->getUploadedFiles();
    		$isUploaded = false;
    		$i = 1;
    		foreach($uploads as $upload){
    			$path = "files/bc_bd/".$upload->getname();
    			if($i == 1){
    				$bc_carga->nombreMat = $upload->getname();
    			} else {
    				$bc_carga->nombreSedes = $upload->getname();
    			}
    			($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
    			$i++;
    		}
    		if($isUploaded){
    			if (!$bc_carga->save()) {
    				foreach ($bc_carga->getMessages() as $message) {
    					$this->flash->error($message);
    				}
    			
    				return $this->dispatcher->forward(array(
    						"controller" => "bc_carga",
    						"action" => "nuevo"
    				));
    			}
    			
    			$this->flash->success("La carga fue realizada exitosamente.");
    			
    			return $this->dispatcher->forward(array(
    					"controller" => "bc_carga",
    					"action" => "index"
    			));
    		} else {
    			$this->flash->error("Ocurrió un error al cargar los archivos");
    			return $this->dispatcher->forward(array(
    					"controller" => "bc_carga",
    					"action" => "nuevo"
    			));
    		}
    	}else{
    	    	$this->flash->error("Debes de seleccionar los archivos");
    			return $this->dispatcher->forward(array(
    					"controller" => "bc_carga",
    					"action" => "nuevo"
    			));
    	}
    }
    
    /**
     * Elimina una carga
     * 
     *
     * @param string $id_carga
     */
    public function eliminarAction($id_carga)
    {

        $bc_carga = BcCarga::findFirstByid_carga($id_carga);
        if (!$bc_carga) {
            $this->flash->error("Esta carga no fue encontrada");

            return $this->dispatcher->forward(array(
                "controller" => "bc_carga",
                "action" => "index"
            ));
        }

        if (!$bc_carga->delete()) {

            foreach ($bc_carga->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "bc_carga",
                "action" => "index"
            ));
        }

        $this->flash->success("La carga fue eliminada exitosamente");

        return $this->dispatcher->forward(array(
            "controller" => "bc_carga",
            "action" => "index"
        ));
    }

}
