<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CobVerificacionController extends ControllerBase
{    
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Verificaciones");
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
        $cob_verificacion = CobVerificacion::find(['order' => 'fecha, tipo asc']);
        if (count($cob_verificacion) == 0) {
            $this->flash->notice("No se ha agregado ninguna verificación hasta el momento");
            $cob_verificacion = null;
        }
        $this->view->nivel = $this->user['nivel'];
        $this->view->cob_verificacion = $cob_verificacion;
    }

    /**
     * Formulario para creación
     */
    public function nuevoAction()
    {
    	$modalidades = BcModalidad::find();
    	$this->view->modalidades = $modalidades;
    	$this->view->cargas = BcCarga::find(['order' => 'fecha DESC']);
    }
    
    /**
     * Ver
     *
     * @param int $id_periodo
     */
    public function verAction($id_verificacion)
    {
    	$cob_verificacion = CobVerificacion::findFirstByid_verificacion($id_verificacion);
    	if (!$cob_verificacion) {
    		$this->flash->error("La verificacion no fue encontrada");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if($cob_verificacion->tipo == 2) {
    		$actas = CobActaverificacioncomputo::find(array(
    				"id_verificacion = $id_verificacion"
    		));
    	} else {
    		$actas = CobActaverificaciondocumentacion::find(array(
    				"id_verificacion = $id_verificacion"
    		));
    	}
    	$this->view->id_usuario = $this->id_usuario;
    	$this->view->verificacion = $cob_verificacion;
    	$this->view->id_verificacion = $id_verificacion;
    	$this->view->actas = $actas;
    	$this->view->nivel = $this->user['nivel'];
    }

    /**
     * Editar
     *
     * @param int $id_periodo
     */
    public function editarAction($id_verificacion)
    {
        if (!$this->request->isPost()) {

            $cob_verificacion = CobVerificacion::findFirstByid_verificacion($id_verificacion);
            if (!$cob_verificacion) {
                $this->flash->error("La verificacion no fue encontrada");

                return $this->response->redirect("cob_verificacion/");
            }
            $this->assets
            ->addJs('js/parsley.min.js')
            ->addJs('js/parsley.extend.js');
            $this->view->id_verificacion = $cob_verificacion->id_verificacion;
            $this->tag->setDefault("id_verificacion", $cob_verificacion->id_verificacion);
            $this->tag->setDefault("nombre", $cob_verificacion->nombre);
            $this->tag->setDefault("fecha", $this->conversiones->fecha(2, $cob_verificacion->fecha));
        }
    }
    
    /**
     * Creación de una nueva cob_verificacion
     */
    public function crearAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_verificacion/nuevo");
    	}
    	$cob_verificacion = new CobVerificacion();
    	$cob_verificacion->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    	$tipo = $this->request->getPost("tipo");
    	$cob_verificacion->tipo = $tipo;
    	$cob_verificacion->nombre = $this->request->getPost("nombre");
    	$id_carga = $this->request->getPost("carga");
    	$modalidades = implode(",", $this->request->getPost("modalidad"));
    	$carga = BcCarga::findFirstByid_carga($id_carga);
    	if (!$carga) {
    		$this->flash->error("La carga no existe");
    		return $this->response->redirect("cob_periodo/nuevorecorrido1/$id_periodo");
    	}
    	if (!$cob_verificacion->save()) {
    		foreach ($cob_verificacion->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_verificacion/nuevo");
    	}
    	if($tipo == 1){
    		$actas = CobActaverificaciondocumentacion::cargarBeneficiarios($carga, $modalidades, $cob_verificacion->id_verificacion);
    		if($actas){
    			$this->flash->success("La verificación fue creada exitosamente.");
    		}
    	} else if ($tipo == 2){
    		$actas = CobActaverificacioncomputo::cargarBeneficiarios($carga, $modalidades, $cob_verificacion->id_verificacion);
    		if($actas){
    			$this->flash->success("La verificación fue creada exitosamente.");
    		}
    	}
    	return $this->response->redirect("cob_verificacion/");
    }   
 
    /**
     * Guarda el cob_verificacion editado
     *
     */
    public function guardarAction()
    {

        if (!$this->request->isPost()) {
            return $this->response->redirect("cob_verificacion/");
        }

        $id_verificacion = $this->request->getPost("id_verificacion");

        $cob_verificacion = CobVerificacion::findFirstByid_verificacion($id_verificacion);
        if (!$cob_verificacion) {
            $this->flash->error("La verificacion no existe " . $id_verificacion);
            return $this->response->redirect("cob_verificacion/");
        }

        $cob_verificacion->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
        $cob_verificacion->nombre = $this->request->getPost("nombre");
        

        if (!$cob_verificacion->save()) {

            foreach ($cob_verificacion->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("cob_verificacion/ver/$id_verificacion");
        }

        $this->flash->success("La verificacion fue actualizada exitosamente");

        return $this->response->redirect("cob_verificacion/");

    }

    /**
     * Elimina un cob_verificacion
     *
     * @param int $id_verificacion
     */
    public function eliminarAction($id_verificacion)
    {
        $cob_verificacion = CobVerificacion::findFirstByid_verificacion($id_verificacion);
        if (!$cob_verificacion) {
            $this->flash->error("La verificacion no fue encontrada");

            return $this->response->redirect("cob_verificacion/");
        }
        if (!$cob_verificacion->delete()) {
            foreach ($cob_verificacion->getMessages() as $message) {
                $this->flash->error($message);
            }
           	return $this->response->redirect("cob_verificacion/ver/$id_verificacion");
        }
        $this->flash->success("La verificacion fue eliminada correctamente");
        return $this->response->redirect("cob_verificacion/");
    }
    
    /**
     * Recorrido
     *
     * @param int $id_periodo
     * @param int $recorrido
     */
    public function rutearAction($id_verificacion)
    {
    	$cob_verificacion = CobVerificacion::findFirstByid_verificacion($id_verificacion);
    	if (!$cob_verificacion) {
    		$this->flash->error("La verificación no fue encontrada");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if($cob_verificacion->tipo == 2) {
    		$actas = CobActaverificacioncomputo::find(array(
    				"id_verificacion = $id_verificacion"
    		));
    	} else {
    		$actas = CobActaverificaciondocumentacion::find(array(
    				"id_verificacion = $id_verificacion"
    		));
    	}
    	if (!$actas) {
    		$this->flash->error("No se encontraron actas");
    		return $this->response->redirect("cob_verificacion/");
    	}
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
    	->addJs('js/rutear.js');
    	$this->view->id_verificacion = $cob_verificacion->id_verificacion;
    	$this->view->fecha_periodo = $cob_verificacion->id_verificacion;
    	$this->view->actas = $actas;
    	$this->view->interventores = IbcUsuario::find(['id_usuario_cargo = 3', 'order' => 'usuario asc']);
    }
    
    /**
     * Guarda el cob_periodo editado
     *
     */
    public function ruteoguardarAction($id_verificacion)
    {
    
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("/");
    	}
    	$cob_verificacion = CobVerificacion::findFirstByid_verificacion($id_verificacion);
    	if (!$cob_verificacion) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if($cob_verificacion->tipo == 2){
    		$actas = CobActaverificacioncomputo::find(array(
    				"id_verificacion = $id_verificacion"
    		));
    		$db = $this->getDI()->getDb();
    		$estado = array();
    		foreach($this->request->getPost("contador_asignado") as $row){
    			if($row == "NULL")
    				$estado[] = 0;
    			else
    				$estado[] = 1;
    		}
    		$elementos = array(
    				'id_actaverificacioncomputo' => $this->request->getPost("id_acta"),
    				'estado' => $estado,
    				'id_usuario' => $this->request->getPost("contador_asignado")
    		);
    		$sql = $this->conversiones->multipleupdate("cob_actaverificacioncomputo", $elementos, "id_actaverificacioncomputo");
    	} else {
    		$actas = CobActaverificaciondocumentacion::find(array(
    				"id_verificacion = $id_verificacion"
    		));
    		$db = $this->getDI()->getDb();
    		$estado = array();
    		foreach($this->request->getPost("contador_asignado") as $row){
    			if($row == "NULL")
    				$estado[] = 0;
    			else
    				$estado[] = 1;
    		}
    		$elementos = array(
    				'id_actaverificaciondocumentacion' => $this->request->getPost("id_acta"),
    				'estado' => $estado,
    				'id_usuario' => $this->request->getPost("contador_asignado")
    		);
    		$sql = $this->conversiones->multipleupdate("cob_actaverificaciondocumentacion", $elementos, "id_actaverificaciondocumentacion");
    	}
    	if (!$actas) {
    		$this->flash->error("No se encontraron actas");
    		return $this->response->redirect("cob_verificacion/");
    	}
    	$query = $db->execute($sql);
    	if (!$query) {
    		foreach ($query->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_verificacion/rutear/$id_verificacion");
    	}
    	$this->flash->success("El ruteo fue actualizado exitosamente");
    	return $this->response->redirect("cob_verificacion/rutear/$id_verificacion");
    }
    
    /**
     * Rutea desde otro recorrido
     *
     */
    public function ruteodesdeotroguardarAction($id_periodo, $recorrido)
    {
    
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("rutear/$id_periodo/$recorrido");
    	}
    	$id_periodo_actualizar = $this->request->getPost("id_periodo_actualizar");
    	$recorrido_actualizar = $this->request->getPost("recorrido_actualizar");
    	$cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo_actualizar);
    	if($cob_periodo->tipo == 2){
    		$actas = CobActamuestreo::find(array(
    				"id_periodo = $id_periodo_actualizar AND recorrido = $recorrido_actualizar",
    				"group" => "id_actamuestreo"
    		));
    		$tabla_acta = "cob_actamuestreo";
    	} else {
    		$actas = CobActaconteo::find(array(
    				"id_periodo = $id_periodo_actualizar AND recorrido = $recorrido_actualizar",
    				"group" => "id_actaconteo"
    		));
    		$tabla_acta = "cob_actaconteo";
    	}
    
    
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if (!$actas) {
    		$this->flash->error("El recorrido no fue encontrado");
    		return $this->response->redirect("cob_periodo/");
    	}
    	$db = $this->getDI()->getDb();
    	foreach($actas as $row){
    		$id_usuario = $row->id_usuario;
    		$id_contrato = $row->id_contrato;
    		$id_sede = $row->id_sede;
    		$query = $db->execute("UPDATE $tabla_acta SET id_usuario = $id_usuario WHERE id_periodo = $id_periodo AND recorrido = $recorrido AND id_contrato = $id_contrato AND id_sede = $id_sede");
    	}
    	$this->flash->success("El ruteo fue actualizado exitosamente");
    	return $this->response->redirect("cob_periodo/rutear/$id_periodo/$recorrido");
    }

}
