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
        $this->assets
        ->addJs('js/multifilter.min.js');
        $this->view->nivel = $this->user['nivel'];
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
     * Formulario para agregar fecha de cierre
     */
    public function cierreAction()
    {
    	$this->persistent->parameters = null;
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js');
    	$this->view->ajustes = CobAjuste::find(["id_ajuste_cierre IS NULL", 'order' => 'datetime DESC']);
    	$this->view->fechas = CobAjusteCierre::find(['order' => 'fecha DESC']);
    }
    
    /**
     * Formulario para agregar fecha de cierre
     */
    public function nuevafechacierreAction()
    {
    	$this->view->fechas = CobAjusteCierre::find(['order' => 'fecha DESC']);
    }
    
    /**
     * Guarda el fecha de cierre
     *
     */
    public function guardarfechacierreAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_ajuste/nuevafechacierre");
    	}
    	$cob_ajuste_cierre = new CobAjusteCierre();
    	$cob_ajuste_cierre->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    
    	if (!$cob_ajuste_cierre->save()) {
    		foreach ($cob_ajuste_cierre->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_periodo/nuevo");
    	}
    	$this->flash->success("La fecha de cierre fue creada exitosamente.");
    	return $this->response->redirect("cob_ajuste/nuevafechacierre");
    }
    
    /**
     * Elimina una fecha de cierre
     *
     * @param int $id_periodo
     */
    public function eliminarfechacierreAction($id_ajuste_cierre)
    {
    	$cob_ajuste_cierre = CobAjusteCierre::findFirstByid_ajuste_cierre($id_ajuste_cierre);
    	if (!$cob_ajuste_cierre) {
    		$this->flash->error("La fecha no fue encontrada");
    
    		return $this->response->redirect("cob_ajuste/nuevafechacierre");
    	}
    	if (!$cob_ajuste_cierre->delete()) {
    		foreach ($cob_ajuste_cierre->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_ajuste/nuevafechacierre");
    	}
    	$this->flash->success("La fecha fue eliminada correctamente");
    	return $this->response->redirect("cob_ajuste/nuevafechacierre");
    }
    
    /**
     * Reportes de los ajustes
     */
    public function reportesAction()
    {
    	$this->view->fechas = CobAjusteCierre::find(['order' => 'fecha DESC']);
    }
    
    /**
     * Reportes de los ajustes
     */
    public function reporteAction($id_ajuste_cierre)
    {
    	$cob_ajuste = CobAjuste::find(array("id_ajuste_cierre = $id_ajuste_cierre", "group" => "id_sede_contrato"));
    	if (count($cob_ajuste) == 0) {
    		$this->flash->error("No se encontraron ajustes con esta fecha de cierre");
    		return $this->response->redirect("cob_ajuste/reportes");
    	}
    	$this->view->cob_ajuste = $cob_ajuste;
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
    public function guardarcierreAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_ajuste/cierre");
    	}
    	$elementos = array(
    			'id_ajuste' => $this->request->getPost("id_ajuste"),
    			'id_ajuste_cierre' => $this->request->getPost("fechaCierre")
    	);
    	$sql = $this->conversiones->multipleupdate("cob_ajuste", $elementos, "id_ajuste");
    	$db = $this->getDI()->getDb();
    	$query = $db->query($sql);
    	if (!$query) {
    		foreach ($query->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_ajuste/cierre");
    	}
    	$this->flash->success("Las fechas de cierre han sido actualizadas correctamente");
    	return $this->response->redirect("cob_ajuste");
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
    	$ajuste->id_periodo = $beneficiario->id_periodo;
    	$ajuste->id_sede_contrato = $beneficiario->id_sede_contrato;
    	$ajuste->id_contrato = $beneficiario->id_contrato;
    	$ajuste->id_actaconteo_persona_facturacion = $id_actaconteo_persona_facturacion;
    	$ajuste->certificar = $this->request->getPost("certificar");
    	$ajuste->datetime = date('Y-m-d H:i:s');
    	$ajuste->observacion = $this->request->getPost("observacion");
    	$ajuste->radicado = $this->request->getPost("radicado");
    	$ajuste->id_usuario = $this->user['id_usuario'];	
    	if (!$ajuste->save()) {
    		foreach ($ajuste->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_ajuste/nuevo/$id_actaconteo_persona_facturacion");
    	}
    	$ninofac = CobActaconteoPersonaFacturacion::findFirstByid_actaconteo_persona_facturacion($id_actaconteo_persona_facturacion);
    	if (!$ninofac) {
    		$this->flash->error("El niño no existe en la base de datos de facturación pero sí se guardó el ajuste, favor informar esto al administrador inmediatamente");
    		return $this->response->redirect("cob_ajuste");
    	}
    	$ninofac->certificacion = $this->request->getPost("certificar");
    	if($this->request->getPost("certificar") == 1){
    		$ninofac->asistenciaFinal = 10;
    	} else if($this->request->getPost("certificar") == 3) {
    		$ninofac->asistenciaFinal = 11;
    	}
    	if (!$ninofac->save()) {
    		foreach ($ninofac->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_ajuste");
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
