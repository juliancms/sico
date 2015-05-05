<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class BcInformeController extends ControllerBase
{    
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Informes");
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
        $bc_informe = CobPeriodo::find();
        if (count($bc_informe) == 0) {
            $this->flash->notice("No se ha agregado ningún informe hasta el momento");
            $bc_informe = null;
        }
        $this->view->nivel = $this->user['nivel'];
        $this->view->bc_informe = $bc_informe;
    }

    /**
     * Reporte general de Cobertura de Contratos
     */
    public function reportecontratosAction($id_periodo, $tipo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_informe/");
    	}
    	$this->assets
    	->addJs('js/jquery.table2excel.min.js')
    	->addJs('js/reporte_exportar.js');
    	$reporte_contratos = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo", "group" => "id_contrato"));
    	$this->view->contratos = $reporte_contratos;
    	$this->view->nombre_reporte = "Rpt_" . $cob_periodo->getPeriodoReporte . "_contratos_" . date("YmdHis") . ".xlsx";
    	if($tipo == 1){
    		$this->view->setTemplateAfter('rpt_contratos_general');
    	} else if($tipo == 3) {
    		$this->view->setTemplateAfter('rpt_contratos_comunitario');
    	} else if($tipo == 4) {
    		$this->view->setTemplateAfter('rpt_contratos_itinerante');
    	} else if($tipo == 5) {   		
    		$this->view->setTemplateAfter('rpt_contratos_jardines');
    	}
    	
    }
    
    /**
     * Reporte general de Cobertura de Sedes
     */
    public function reportesedesAction($id_periodo, $tipo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_informe/");
    	}
    	$reporte_sedes = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo", "group" => "id_sede"));
    	$this->view->sedes = $reporte_sedes;
    	if($tipo == 1){
    		$this->view->setTemplateAfter('rpt_sedes_general');
    	} else if($tipo == 3) {
    		$this->view->setTemplateAfter('rpt_sedes_comunitario');
    	} else if($tipo == 4) {
    		$this->view->setTemplateAfter('rpt_sedes_itinerante');
    	} else if($tipo == 5) {
    		$this->view->setTemplateAfter('rpt_sedes_jardines');
    	}
    }
    
}
