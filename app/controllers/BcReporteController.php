<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class BcReporteController extends ControllerBase
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
        $bc_reporte = CobPeriodo::find();
        if (count($bc_informe) == 0) {
            $this->flash->notice("No se ha agregado ningún informe hasta el momento");
            $bc_reporte = null;
        }
        $this->view->nivel = $this->user['nivel'];
        $this->view->$bc_reporte = $bc_reporte;
    }

    /**
     * Reporte general de Cobertura de Contratos
     */
    public function cob_contratosAction($id_periodo, $tipo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$this->assets
    	->addJs('js/jquery.table2excel.min.js')
    	->addJs('js/reporte_exportar.js');
    	$reporte_contratos = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo", "group" => "id_contrato"));
    	$this->view->contratos = $reporte_contratos;
    	$this->view->nombre_reporte = "Rpt_" . $cob_periodo->getPeriodoReporte . "_contratos_" . date("YmdHis") . ".xlsx";
    	if($tipo == 1){
    		$this->view->setTemplateAfter('../bc_reporte/cob_contratos_general');
    	} else if($tipo == 3) {
    		$this->view->setTemplateAfter('../bc_reporte/cob_contratos_comunitario');
    	} else if($tipo == 4) {
    		$this->view->setTemplateAfter('../bc_reporte/cob_contratos_itinerante');
    	} else if($tipo == 5) {
    		$this->view->setTemplateAfter('../bc_reporte/cob_contratos_jardines');
    	}

    }

    /**
     * Reporte general de Cobertura de Sedes
     */
    public function cob_sedesAction($id_periodo, $tipo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$reporte_sedes = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo", "group" => "id_sede_contrato", "order" => "id_contrato ASC"));
    	$this->view->sedes = $reporte_sedes;
    	if($tipo == 1){
    		$this->view->setTemplateAfter('../bc_reporte/cob_sedes_general');
    	} else if($tipo == 3) {
    		$this->view->setTemplateAfter('../bc_reporte/cob_sedes_comunitario');
    	} else if($tipo == 4) {
    		$this->view->setTemplateAfter('../bc_reporte/cob_sedes_itinerante');
    	} else if($tipo == 5) {
    		$this->view->setTemplateAfter('../bc_reporte/cob_sedes_jardines');
    	}
    }

    /**
     * Reporte general de Cobertura de Sedes (niño a niño R1 y R2)
     */
    public function beneficiarios_contratoparcialAction($id_periodo, $id_contrato)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_reporte/oferente_contratos");
    	}
    	if($cob_periodo->id_carga_facturacion == NULL || $cob_periodo->id_carga_facturacion == 0){
    		$this->flash->error("La base de datos de facturación no ha sido cargada.");
    		return $this->response->redirect("bc_reporte/oferente_contratos");
    	}
    	if($cob_periodo->fecha < "2015-05-01"){
    		$this->flash->error("El reporte para este periodo se encuentra en el menú 'Archivo Digital' del sitio web de la Interventoría Buen Comiecnzo.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
    	->addJs('js/multifilter.min.js')
    	->addJs('js/reporte.js');
    	$reporte_contrato = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo AND id_contrato = $id_contrato"));
    	$this->view->periodo = $cob_periodo;
    	$this->view->beneficiarios = $reporte_contrato;
    	$this->view->contrato = $reporte_contrato[0];
    }

    /**
     * Reporte general de Cobertura de Sedes (niño a niño Final)
     */
    public function beneficiarios_contratofinalAction($id_periodo, $id_contrato)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_reporte/oferente_contratos");
    	}
    	if($cob_periodo->fechaCierre == NULL || $cob_periodo->fechaCierre == "0000-00-00"){
    		$this->flash->error("La base de datos de facturación no ha sido cargada o el periodo no ha sido cerrado.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	if($cob_periodo->fecha < "2015-05-01"){
    		$this->flash->error("El reporte para este periodo se encuentra en el menú 'Archivo Digital' del sitio web de la Interventoría Buen Comiecnzo.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
    	->addJs('js/multifilter.min.js')
    	->addJs('js/reporte.js');
    	$reporte_contrato = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo AND id_contrato = $id_contrato"));
    	$this->view->periodo = $cob_periodo;
    	$this->view->beneficiarios = $reporte_contrato;
    	$this->view->contrato = $reporte_contrato[0];
    }

    /**
     * Reporte general de Cobertura de Sedes (niño a niño Facturación)
     */
    public function beneficiarios_contratofacturacionAction($id_periodo, $id_contrato)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_reporte/oferente_contratos");
    	}
    	if($cob_periodo->fechaCierre == NULL || $cob_periodo->fechaCierre == "0000-00-00"){
    		$this->flash->error("La base de datos de facturación no ha sido cargada o el periodo no ha sido cerrado.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	if($cob_periodo->fecha < "2015-05-01"){
    		$this->flash->error("El reporte para este periodo se encuentra en el menú 'Archivo Digital' del sitio web de la Interventoría Buen Comiecnzo.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
    	->addJs('js/multifilter.min.js')
    	->addJs('js/reporte.js');
    	$reporte_contrato = CobActaconteoPersonaFacturacion::find(array("id_periodo = $id_periodo AND id_contrato = $id_contrato"));
    	$this->view->periodo = $cob_periodo;
    	$this->view->beneficiarios = $reporte_contrato;
    	$this->view->contrato = $reporte_contrato[0];
    }

    /**
     * Reporte general de Cobertura de Sedes (niño a niño Facturación)
     */
    public function beneficiarios_contratoajustesAction($id_periodo, $id_contrato)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_reporte/oferente_contratos");
    	}
    	if($cob_periodo->fechaCierre == NULL || $cob_periodo->fechaCierre == "0000-00-00"){
    		$this->flash->error("La base de datos de facturación no ha sido cargada o el periodo no ha sido cerrado.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	if($cob_periodo->fecha < "2015-05-01"){
    		$this->flash->error("El reporte para este periodo se encuentra en el menú 'Archivo Digital' del sitio web de la Interventoría Buen Comienzo.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	$reporte_contrato = CobAjuste::find(array("id_periodo = $id_periodo AND id_contrato = $id_contrato AND (certificar = 3 OR certificar = 4)", "group" => "fecha_ajuste_reportado"));
    	if(count($reporte_contrato) == 0){
    		$this->flash->error("No existen ajustes para este contrato.");
    		return $this->response->redirect("bc_reporte/oferente_periodos/$id_contrato");
    	}
    	$this->view->periodo = $cob_periodo;
    	$this->view->ajustes = $reporte_contrato;
    	$this->view->contrato = $reporte_contrato[0]->CobActaconteo;
    }

    /**
     * Reporte index oferente
     */
    public function oferente_contratosAction()
    {
    	$oferente = IbcUsuarioOferente::findFirstByid_usuario($this->id_usuario);
    	if(!$oferente){
    		$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    		return $this->response->redirect("/");
    	}
    	$contratos = BcSedeContrato::find(array("id_oferente = $oferente->id_oferente", "group" => "id_contrato" ));
    	if (!$contratos) {
    		$this->flash->error("No se encontraron contratos");
    		return $this->response->redirect("/");
    	}
    	$this->view->contratos = $contratos;
    }

    /**
     * Reporte index oferentes
     */
    public function oferentes_contratosAction()
    {
    	$contratos = BcSedeContrato::find(array("group" => "id_contrato", "order" => "id_oferente ASC"));
    	if (!$contratos) {
    		$this->flash->error("No se encontraron contratos");
    		return $this->response->redirect("/");
    	}
    	$this->view->contratos = $contratos;
    }

    /**
     * Reporte index oferente
     */
    public function oferente_periodosAction($id_contrato)
    {
    	if($this->user['nivel'] > 2){
    		$oferente = IbcUsuarioOferente::findFirstByid_usuario($this->id_usuario);
    		if(!$oferente){
    			$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    			return $this->response->redirect("/");
    		}
    		$periodos = CobActaconteo::find(array("id_contrato = $id_contrato AND id_oferente = $oferente->id_oferente", "group" => "id_periodo"));
    	} else {
    		$periodos = CobActaconteo::find(array("id_contrato = $id_contrato", "group" => "id_periodo"));
    	}
    	if (!$periodos) {
    		$this->flash->error("No se han encontrado periodos para este contrato.");
    		return $this->response->redirect("bc_reporte/oferente_contratos");
    	}
    	$this->view->contrato = $periodos[0];
    	$this->view->periodos = $periodos;
    }

    /**
     * Reporte de liquidación para un contrato
     */
    public function contrato_liquidacionAction($id_contrato)
    {
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
    	->addJs('js/multifilter.min.js')
    	->addJs('js/reporte.js');
    	$reporte_contrato = CobActaconteoPersonaFacturacion::find(array("id_contrato = $id_contrato", "order" => " id_periodo ASC"));
    	$this->view->beneficiarios = $reporte_contrato;
    	$this->view->contrato = $reporte_contrato[0];
    }
    /**
     * Reporte de liquidación para un contrato
     */
    public function contratos_liquidacionAction()
    {
    }

    /**
     * Reporte de liquidación para un contrato
     */
    public function buscar_contratoliquidacionAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_reporte/contratos_liquidacion");
    	}
    	$id_contrato = $this->request->getPost("id_contrato");
    	$buscar_contrato = CobActaconteoPersonaFacturacion::findFirstByid_contrato($id_contrato);
    	if (!$buscar_contrato) {
    		$this->flash->error("El contrato no existe");
    		return $this->response->redirect("bc_reporte/contratos_liquidacion");
    	}
    	return $this->response->redirect("bc_reporte/contrato_liquidacion/$id_contrato");
    }
}
