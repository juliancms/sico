<?php
 
use Phalcon\Mvc\Model\Criteria;

class MtCargaController extends ControllerBase
{    
    public function initialize()
    {
        $this->tag->setTitle("Carga Metrosalud");
        $auth = $this->session->get('auth');
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $mt_carga = MtCarga::find();
        if (count($mt_carga) == 0) {
        	$this->flash->notice("No se ha agregado ninguna carga de Metrosalud hasta el momento");
        	$mt_carga = null;
        }
        $bc_carga = BcCarga::find();
        if (count($bc_carga) == 0) {
            $this->flash->notice("No se ha agregado ninguna carga General hasta el momento");
            $bc_carga = null;
        }
        $this->view->bc_carga = $bc_carga;
        $this->view->mt_carga = $mt_carga;
    }

    /**
     * Formulario para la adición de una carga
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
    		return $this->response->redirect("mt_carga/");
    	}
    	
    	$mt_carga = new MtCarga();
    	$mt_carga->mes = $this->request->getPost("mes");
    	$bc_carga->fecha = date('Y-m-d H:i:s');

    	if($this->request->hasFiles() == true){
    		$uploads = $this->request->getUploadedFiles();
    		$isUploaded = false;
    		$doc = array("archivoNinos", "archivoMadres", "archivoProgramacion");
    		$i = 0;
    		foreach($uploads as $upload){
    			$path = "files/bc_bd/".$upload->getname();
    			$mt_carga->$doc[$i] = $upload->getname();
    			($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
    			$i++;
    		}
    		if($isUploaded){
    			if (!$mt_carga->save()) {
    				foreach ($mt_carga->getMessages() as $message) {
    					$this->flash->error($message);
    				}
    			
    				return $this->response->redirect("mt_carga/nuevo");
    			}
    			
    			$this->flash->success("La carga fue realizada exitosamente.");
    			
    			return $this->response->redirect("mt_carga/");
    		} else {
    			$this->flash->error("Ocurrió un error al cargar los archivos");
    			return $this->response->redirect("mt_carga/nuevo");
    		}
    	}else{
    	    	$this->flash->error("Debes de seleccionar los archivos");
    			return $this->response->redirect("mt_carga/nuevo");
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

        $mt_carga = MtCarga::findFirstByid_carga($id_carga);
        if (!$mt_carga) {
            $this->flash->error("Esta carga no fue encontrada");
            return $this->response->redirect("mt_carga/");
        }

        if (!$mt_carga->delete()) {

            foreach ($mt_carga->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("mt_carga/");
        }

        $this->flash->success("La carga fue eliminada exitosamente");
        return $this->response->redirect("mt_carga/");
    }

}