<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class IbcMensajeController extends ControllerBase
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
        $ibc_mensaje = IbcMensaje::find();
        if (count($ibc_mensajes) == 0) {
            //$this->flash->notice("No se ha agregado ningún mensaje hasta el momento");
            $ibc_mensaje = null;
        }
        $this->view->ibc_mensaje = $ibc_mensaje;
    }

    /**
     * Formulario para creación
     */
    public function nuevoAction()
    {

    }
    
    /**
     * Ver
     *
     * @param int $id_periodo
     */
    public function verAction($id_periodo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->fecha_periodo = $cob_periodo->id_periodo;
    	$recorridos = CobActaconteo::find(array(
    			"id_periodo = $id_periodo",
    			"group" => "recorrido"
    	));
    	$this->view->recorridos = $recorridos;
    	$this->view->crear_recorrido = count($recorridos) + 1;
    }
    
    /**
     * Recorrido
     *
     * @param int $id_periodo
     * @param int $recorrido
     */
    public function recorridoAction($id_periodo, $recorrido)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	$recorrido = CobActaconteo::find(array(
    			"id_periodo = $id_periodo AND recorrido = $recorrido",
    			"group" => "id_actaconteo"
    	));
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if (!recorrido) {
    		$this->flash->error("El recorrido no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->fecha_periodo = $cob_periodo->id_periodo;
    	$this->view->actas = $recorrido;
    }
    
    /**
     * Recorrido
     *
     * @param int $id_periodo
     * @param int $recorrido
     */
    public function rutearAction($id_periodo, $recorrido)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	$actas = CobActaconteo::find(array(
    			"id_periodo = $id_periodo AND recorrido = $recorrido",
    			"group" => "id_actaconteo"
    	));
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if (!$actas) {
    		$this->flash->error("El recorrido no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
    	->addJs('js/rutear.js');
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->recorrido = $recorrido;
    	$this->view->fecha_periodo = $cob_periodo->id_periodo;
    	$this->view->actas = $actas;
    	$this->view->interventores = IbcUsuario::find(['id_usuario_cargo = 3', 'order' => 'usuario asc']);
    }
    
    /**
     * Guarda el cob_periodo editado
     *
     */
    public function ruteoguardarAction($id_periodo, $recorrido)
    {
    
    	if (!$this->request->isPost()) {
    		 return $this->response->redirect("ibc_usuario/");
    	}
   	 	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	$actas = CobActaconteo::find(array(
    			"id_periodo = $id_periodo AND recorrido = $recorrido",
    			"group" => "id_actaconteo"
    	));
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if (!$actas) {
    		$this->flash->error("El recorrido no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$db = $this->getDI()->getDb();
    	$estado = array();
    	foreach($this->request->getPost("contador_asignado") as $row){
    		if($row == "NULL")
    			$estado[] = 0;
    		else
    			$estado[] = 1;
    	}
    	$elementos = array(
    			'id_actaconteo' => $this->request->getPost("id_acta"),
    			'estado' => $estado,
    			'id_usuario' => $this->request->getPost("contador_asignado")
    	);
    	$sql = $this->conversiones->multipleupdate("cob_actaconteo", $elementos, "id_actaconteo");
    	$query = $db->execute($sql);
    	if (!$query) {
	    	foreach ($query->getMessages() as $message) {
	    			$this->flash->error($message);
	    		}
    		return $this->response->redirect("cob_periodo/rutear/$id_periodo/$recorrido");
    	}
    	$this->flash->success("El ruteo fue actualizado exitosamente");
    	return $this->response->redirect("cob_periodo/rutear/$id_periodo/$recorrido");
    }
    
    /**
     * nuevorecorrido
     *
     * @param int $id_periodo
     */
    public function nuevorecorridoAction($id_periodo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$recorridos = CobActaconteo::find(array(
    			"id_periodo = $id_periodo",
    			"group" => "recorrido"
    	));
    	$facturacion = CobActaconteoPersonaFacturacion::findFirstByid_periodo($id_periodo);
    	if($facturacion){
    		$recorrido_anterior = count($recorridos);
    		$actas = CobActaconteo::generarActasFacturacion($cob_periodo, $recorrido_anterior);
    		if($actas){
    			$this->flash->success("Se generaron exitosamente las actas");
    		}
    		return $this->response->redirect("cob_periodo/ver/$id_periodo");
    	}
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->fecha_corte = $this->conversiones->fecha(3, $cob_periodo->fecha);
    	$this->view->recorrido = count($recorridos) + 1;
    	$this->view->cargas = BcCarga::find(['order' => 'fecha DESC']);
    }
        
    /**
     * nuevorecorrido1
     *
     * @param int $id_periodo
     */
    public function nuevorecorrido1Action($id_periodo)
    {
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->fecha_corte = $this->conversiones->fecha(3, $cob_periodo->fecha);
    	$recorridos = CobActaconteo::find(array(
        	"id_periodo = $id_periodo",
    		"group" => "recorrido"
    	));
    	if (count($recorridos) == 0){
    		$this->view->recorridos = array("1" => "1");
    	} else if ($count($recorridos) > 1) {
    		$this->view->recorridos = $recorridos;
    	} else {
    		$this->view->recorridos = $recorridos;
    	}
    	$modalidades = BcModalidad::find();
    	$this->view->modalidades = $modalidades;
    	$this->view->cargas = BcCarga::find(['order' => 'fecha DESC']);
    }

    /**
     * Editar
     *
     * @param int $id_periodo
     */
    public function editarAction($id_periodo)
    {
        if (!$this->request->isPost()) {

            $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
            if (!$cob_periodo) {
                $this->flash->error("El periodo no fue encontrado");

                return $this->response->redirect("cob_periodo/");
            }
            $this->assets
            ->addJs('js/parsley.min.js')
            ->addJs('js/parsley.extend.js');
            $this->view->id_periodo = $cob_periodo->id_periodo;
            $this->tag->setDefault("id_periodo", $cob_periodo->id_periodo);
            $this->tag->setDefault("fecha", $this->conversiones->fecha(2, $cob_periodo->fecha));
        }
    }
    
    /**
     * Creación de un nuevo cob_periodo
     */
    public function crearAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_periodo/");
    	}
    	$cob_periodo = new CobPeriodo();
    	$cob_periodo->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    
    	if (!$cob_periodo->save()) {
    		foreach ($cob_periodo->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_periodo/nuevo");
    	}
    	$this->flash->success("El periodo fue creado exitosamente.");
    	return $this->response->redirect("cob_periodo/");
    }

    /**
     * Generar un nuevo recorrido
     */
    public function generarrecorrido1Action($id_periodo){
        if (!$this->request->isPost() || !$id_periodo) {
            return $this->response->redirect("cob_periodo/");
        }
        $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
        if (!$cob_periodo) {
        	$this->flash->error("El periodo no existe");
        
        	return $this->response->redirect("cob_periodo/");
        }
        $id_carga = $this->request->getPost("carga");
        $facturacion = $this->request->getPost("facturacion");
        $modalidades = implode(",", $this->request->getPost("modalidad"));
        $carga = BcCarga::findFirstByid_carga($id_carga);
        if (!carga) {
        	$this->flash->error("La carga no existe");
        	return $this->response->redirect("cob_periodo/nuevorecorrido1/$id_periodo");
        }
        $actas = CobActaconteo::generarActasR1($cob_periodo, $carga, $modalidades, $facturacion);
        if($actas){
        	$this->flash->success("Se generaron exitosamente las actas");
        }
        return $this->response->redirect("cob_periodo/ver/$id_periodo");
    }
    
    /**
     * Generar un nuevo recorrido
     */
    public function generarrecorridoAction($id_periodo){
    	if (!$this->request->isPost() || !$id_periodo) {
    		return $this->response->redirect("cob_periodo/");
    	}
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no existe");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$id_carga = $this->request->getPost("carga");
    	$facturacion = $this->request->getPost("facturacion");
    	$carga = BcCarga::findFirstByid_carga($id_carga);
    	if (!carga) {
    		$this->flash->error("La carga no existe");
    		return $this->response->redirect("cob_periodo/nuevorecorrido/$id_periodo");
    	}
    	$recorridos = CobActaconteo::find(array(
    			"id_periodo = $id_periodo",
    			"group" => "recorrido"
    	));
    	$actas = CobActaconteo::generarActasRcarga($cob_periodo, $carga, $facturacion, count($recorridos));
    	if($actas){
    		$this->flash->success("Se generaron exitosamente las actas");
    	}
    	return $this->response->redirect("cob_periodo/ver/$id_periodo");
    }
    
 
    /**
     * Guarda el cob_periodo editado
     *
     */
    public function guardarAction()
    {

        if (!$this->request->isPost()) {
            return $this->response->redirect("cob_periodo/");
        }

        $id_periodo = $this->request->getPost("id_periodo");

        $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
        if (!$cob_periodo) {
            $this->flash->error("cob_periodo no existe " . $id_periodo);
            return $this->response->redirect("cob_periodo/");
        }

        $cob_periodo->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
        

        if (!$cob_periodo->save()) {

            foreach ($cob_periodo->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "editar",
                "params" => array($cob_periodo->id_periodo)
            ));
        }

        $this->flash->success("cob_periodo fue actualizado exitosamente");

        return $this->response->redirect("cob_periodo/");

    }

    /**
     * Elimina un  cob_periodo
     *
     * @param int $id_periodo
     */
    public function eliminarAction($id_periodo)
    {

        $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
        if (!$cob_periodo) {
            $this->flash->error("cob_periodo no fue encontrado");

            return $this->response->redirect("cob_periodo/");
        }

        if (!$cob_periodo->delete()) {

            foreach ($cob_periodo->getMessages() as $message) {
                $this->flash->error($message);
            }

           	return $this->response->redirect("cob_periodo/");
        }

        $this->flash->success("El periodo fue eliminado correctamente");

        return $this->response->redirect("cob_periodo/");
    }

}
