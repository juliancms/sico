<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CobAjusteController extends ControllerBase
{    
    public function initialize()
    {
        $this->tag->setTitle("Periodos");
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $cob_periodo = CobPeriodo::find();
        if (count($cob_periodo) == 0) {
            $this->flash->notice("No se ha agregado ningún periodo hasta el momento");
            $cob_periodo = null;
        }
        $this->view->cob_periodo = $cob_periodo;
    }

    /**
     * Formulario para creación
     */
    public function nuevoAction($id_actaconteo_persona_facturacion)
    {
    	$beneficiario = CobActaconteoPersonaFacturacion::findFirstByid_actaconteo_persona_facturacion($id_actaconteo_persona_facturacion);
    	if (!$beneficiario) {
    		$this->flash->error("La persona no fue encontrada");
    		 
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$acta = CobActaconteo::findFirst(array("id_periodo = $beneficiario->id_periodo AND id_sede_contrato = $beneficiario->id_sede_contrato"));
    	$this->view->sino = $this->elements->getSelect("sino");
    	$this->view->acta = $acta;
    	$this->view->beneficiario = $beneficiario;

    }
    
    /**
     * Guardar ajuste
     */
    public function guardarAction($id_actaconteo_persona_facturacion)
    {
    	
    	$beneficiario = CobActaconteoPersonaFacturacion::findFirstByid_actaconteo_persona_facturacion($id_actaconteo_persona_facturacion);
    	if (!$beneficiario) {
    		$this->flash->error("La persona no fue encontrada");
    		 
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$ajuste = new CobAjuste();
    	$ajuste->id_actaconteo_persona_facturacion = $id_actaconteo_persona_facturacion;
    	$ajuste->certificar = $this->request->getPost("certificar");
    	$ajuste->datetime = date('Y-m-d H:i:s');
    	$ajuste->observacion = $this->request->getPost("observacion");
    	
    	if (!$ajuste->save()) {
    		foreach ($ajuste->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_ajuste/nuevo/$id_actaconteo_persona_facturacion");
    	}
    	$this->flash->success("El ajuste fue realizado exitosamente.");
    	return $this->response->redirect("cob_periodo/");
    }

}
