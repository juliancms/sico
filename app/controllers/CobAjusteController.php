<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CobAjusteController extends ControllerBase
{    
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Ajustes");
        $this->user = $this->session->get('auth');
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $cob_ajuste = CobAjuste::find();
        if (count($cob_ajuste) == 0) {
            $this->flash->notice("No se ha agregado ningún ajuste hasta el momento");
            $cob_ajuste = null;
        }
        $this->view->cob_ajuste = $cob_ajuste;
    }
    
    /**
     * Formulario para buscar
     */
    public function buscarAction()
    {
    	$this->persistent->parameters = null;
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js');
    	$this->view->periodos = CobPeriodo::find(['order' => 'fecha DESC']);
    }

    /**
     * Formulario para creación
     */
    public function nuevoAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_ajuste/buscar");
    	}
    	$id_periodo = $this->request->getPost("id_periodo");
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no existe ");
    		return $this->response->redirect("cob_ajuste/buscar");
    	}  	
    	$id_contrato = $this->request->getPost("id_contrato");
    	$numDocumento = $this->request->getPost("numDocumento");
    	$beneficiario = CobActaconteoPersonaFacturacion::findFirst(array("id_periodo = $id_periodo AND id_contrato = $id_contrato AND numDocumento = $numDocumento"));
    	if (!$beneficiario) {
    		$this->flash->error("El beneficiario con número de documento <strong>$numDocumento</strong> no fue encontrado en el contrato <strong>$id_contrato</strong> para el periodo <strong>$cob_periodo->fecha</strong>");
    		return $this->response->redirect("cob_ajuste/buscar");
    	}
    	$this->flash->notice("<i class='glyphicon glyphicon-exclamation-sign'></i> Por favor, antes de ingresar la información verifique que el ajuste corresponde al periodo y la información del beneficiario.");
    	$this->assets
    	->addJs('js/ajuste.js')
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js');
    	$acta = CobActaconteo::findFirst(array("id_periodo = $id_periodo AND id_sede_contrato = $beneficiario->id_sede_contrato"));
    	$this->view->sino = CobAjuste::CertificarSelect();
    	$this->view->acta = $acta;
    	$this->view->periodo = $this->conversiones->fecha(5, $cob_periodo->fecha);
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
    		return $this->response->redirect("cob_ajuste/buscar");    	}
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_ajuste/buscar");
    	}
    	$ajuste = new CobAjuste();
    	$ajuste->id_actaconteo_persona_facturacion = $id_actaconteo_persona_facturacion;
    	$ajuste->certificar = $this->request->getPost("certificar");
    	$ajuste->datetime = date('Y-m-d H:i:s');
    	$ajuste->observacion = $this->request->getPost("observacion");
    	$ajuste->id_usuario = $this->user['id_usuario'];	
    	if (!$ajuste->save()) {
    		foreach ($ajuste->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_ajuste/nuevo/$id_actaconteo_persona_facturacion");
    	}
    	$this->flash->success("El ajuste fue realizado exitosamente.");
    	return $this->response->redirect("cob_ajuste/buscar");
    }
    
    /**
     * Elimina un ajuste
     *
     * @param int $id_ajuste
     */
    public function eliminarAction($id_ajuste)
    {
    
    	$cob_ajuste = CobAjuste::findFirstByid_ajuste($id_ajuste);
    	if (!$cob_ajuste) {
    		$this->flash->error("El ajuste no fue encontrado");
    
    		return $this->response->redirect("cob_ajuste/");
    	}
    
    	if (!$cob_ajuste->delete()) {
    
    		foreach ($cob_ajuste->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    
    		return $this->response->redirect("cob_ajuste/");
    	}
    
    	$this->flash->success("El ajuste fue eliminado correctamente");
    
    	return $this->response->redirect("cob_ajuste/");
    }

}
