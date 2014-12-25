<?php
 
use Phalcon\Mvc\Model\Criteria;

class CobActaconteoController extends ControllerBase
{    
    public function initialize()
    {
        $this->tag->setTitle("Acta de Conteo");
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    	));
    }
    
    /**
     * Ver
     *
     * @param int $id_periodo
     */
    public function verAction($id_actaconteo)
    {
    	$this->assets
    	->addCss('css/acta-impresion.css');
    	$acta = CobActaconteo::generarActa($id_actaconteo);
    	if (!$acta) {
    		$this->flash->error("El acta no fue encontrada");
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$this->view->acta_html = $acta['html'];
    	$this->view->acta_datos = $acta['datos'];
    }

    /**
     * Datos
     *
     * @param int $id_actaconteo
     */
    public function datosAction($id_actaconteo)
    {
        if (!$this->request->isPost()) {

            $acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
            if (!$acta) {
                $this->flash->error("El acta no fue encontrada");

                return $this->dispatcher->forward(array(
                    "controller" => "cob_periodo",
                    "action" => "index"
                ));
            }
            $this->assets
            ->addJs('js/parsley.min.js')
            ->addJs('js/parsley.extend.js');
            $this->view->id_actaconteo = $id_actaconteo;
            $this->view->valla_sede = $this->elements->getSelect("datos_valla");
            $this->view->sino = $this->elements->getSelect("sino");
            if($acta->CobActaconteoDatos){
            	$this->tag->setDefault("fecha", $this->conversiones->fecha(2, $acta->CobActaconteoDatos->fecha));
            	$this->tag->setDefault("horaInicio", $acta->CobActaconteoDatos->horaInicio);
            	$this->tag->setDefault("horaFin", $acta->CobActaconteoDatos->horaFin);
            	$this->tag->setDefault("nombreEncargado", $acta->CobActaconteoDatos->nombreEncargado);
            	$this->tag->setDefault("vallaClasificacion", $acta->CobActaconteoDatos->vallaClasificacion);
            	$this->tag->setDefault("correccionDireccion", $acta->CobActaconteoDatos->correccionDireccion);
            	$this->tag->setDefault("mosaicoFisico", $acta->CobActaconteoDatos->mosaicoFisico);
            	$this->tag->setDefault("mosaicoDigital", $acta->CobActaconteoDatos->mosaicoDigital);
            	$this->tag->setDefault("observacionEncargado", $acta->CobActaconteoDatos->observacionEncargado);
            	$this->tag->setDefault("observacionUsuario", $acta->CobActaconteoDatos->observacionUsuario);
            }           
        }
    }
    
    /**
     * Guardar Datos
     *  
     */
    public function guardardatosAction($id_actaconteo)
    {
    	if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
        }
        $acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
        if (!$acta) {
            $this->flash->error("El acta $id_actaconteo no existe ");
            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
        }
        $dato = new CobActaconteoDatos();
        $dato->id_actaconteo = $id_actaconteo;
        $dato->id_usuario = $this->session->auth['id'];
        $dato->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
        $dato->horaInicio = $this->request->getPost("horaInicio");
        $dato->horaFin = $this->request->getPost("horaFin");
        $dato->nombreEncargado = $this->request->getPost("nombreEncargado");
        $dato->vallaClasificacion = $this->request->getPost("vallaClasificacion");
        $dato->correccionDireccion = $this->request->getPost("correccionDireccion");
        $dato->mosaicoFisico = $this->request->getPost("mosaicoFisico");
        $dato->mosaicoDigital = $this->request->getPost("mosaicoDigital");
        $dato->observacionEncargado = $this->request->getPost("observacionEncargado");
        $dato->observacionUsuario = $this->request->getPost("observacionUsuario");
        if (!$dato->save()) {
            foreach ($dato->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("cob_actaconteo/datos/$id_actaconteo");
        }
        $this->flash->success("Los Datos Generales fueron actualizados exitosamente");
        return $this->response->redirect("cob_actaconteo/datos/$id_actaconteo");
    }
    
    /**
     * Guardar Beneficiarios
     *
     */
    public function guardarbeneficiariosAction($id_actaconteo)
    {
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$db = $this->getDI()->getDb();
    	$acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
    	if (!$acta) {
    		$this->flash->error("El acta $id_actaconteo no existe");
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$persona = new CobActaconteoPersona();
    	$i = 0;
    	$elementos = array(
    			'id_actaconteo_persona' => $this->request->getPost("id_actaconteo_persona"),
    			'asistencia' => $this->request->getPost("asistencia")
    	);
    	$fechas = $this->request->getPost("fecha");
    	if(count($fechas) > 0) {
    		$fechas = $this->conversiones->array_fechas(1, $fechas);
    		$elementos['fechaInterventoria'] = $fechas;
    	}
    	$sql = $this->conversiones->multipleupdate("cob_actaconteo_persona", $elementos, "id_actaconteo_persona");
    	$query = $db->query($sql);
    	if (!$query) {
    		foreach ($query->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_actaconteo/datos/$id_actaconteo");
    	}
    	$fechas = $this->request->getPost("fecha_excusa");
    	if($fechas){
    		$fechas = $this->conversiones->array_fechas(1, $fechas);
	    	$elementos = array(
	    			'id_actaconteo_persona' => $this->request->getPost("id_actaconteo_persona2"),
	    			'motivo' => $this->request->getPost("motivo"),
	    			'fecha' => $fechas,
	    			'acudiente' => $this->request->getPost("acudiente"),    			
	    			'telefono' => $this->request->getPost("telefono")    			
	    	);
	    	$sql = $this->conversiones->multipleinsert("cob_actaconteo_persona_excusa", $elementos);
	    	$query = $db->query($sql);
	    	if (!$query) {
	    		foreach ($query->getMessages() as $message) {
	    			$this->flash->error($message);
	    		}
	    		return $this->response->redirect("cob_actaconteo/adicionales/$id_actaconteo");
	    	}
    	}
    	//Eliminar las excusas que ya no tienen clasificación de excusa
    	$db->query("DELETE FROM cob_actaconteo_persona_excusa WHERE id_actaconteo_persona IN (SELECT id_actaconteo_persona FROM cob_actaconteo_persona WHERE asistencia != 2 AND asistencia != 3)");
    	$this->flash->success("Los beneficiarios fueron actualizados exitosamente");
    	return $this->response->redirect("cob_actaconteo/beneficiarios/$id_actaconteo");
    }
    /**
     * Guardar Adicionales
     *
     */
    public function guardaradicionalesAction($id_actaconteo)
    {
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$db = $this->getDI()->getDb();
    	$acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
    	if (!$acta) {
    		$this->flash->error("El acta $id_actaconteo no existe");
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$eliminar_adicionales = $this->request->getPost("eliminar_adicionales");
    	if($eliminar_adicionales){
	    	$sql = $this->conversiones->multipledelete("cob_actaconteo_persona", "id_actaconteo_persona", $eliminar_adicionales);
	    	$query = $db->query($sql);
    	}
    	if($this->request->getPost("num_documento")){
    		$persona = new CobActaconteoPersona();
    		$elementos = array(
    				'numDocumento' => $this->request->getPost("num_documento"),
    				'primerNombre' => $this->request->getPost("primerNombre"),
    				'segundoNombre' => $this->request->getPost("segundoNombre"),
    				'primerApellido' => $this->request->getPost("primerApellido"),
    				'segundoApellido' => $this->request->getPost("segundoApellido"),
    				'grupo' => $this->request->getPost("grupo"),
    				'asistencia' => $this->request->getPost("asistencia"),
    				'urlAdicional' => $this->request->getPost("urlAdicional"),
    				'observacionAdicional' => $this->request->getPost("observacion"),
    				'tipoPersona' => '1',
    				'id_actaconteo' => $id_actaconteo
    		);
    		$fechas = $this->request->getPost("fecha");
    		if(count($fechas) > 0) {
    			$fechas = $this->conversiones->array_fechas(1, $fechas);
    			$elementos['fechaInterventoria'] = $fechas;
    		}
    		$sql = $this->conversiones->multipleinsert("cob_actaconteo_persona", $elementos);
    		$query = $db->query($sql);
    		if (!$query) {
    			foreach ($query->getMessages() as $message) {
    				$this->flash->error($message);
    			}
    			return $this->response->redirect("cob_actaconteo/adicionales/$id_actaconteo");
    		}
    	}
    	$this->flash->success("Los adicionales fueron actualizados exitosamente");
    	return $this->response->redirect("cob_actaconteo/adicionales/$id_actaconteo");
    }
    
    /**
     * Beneficiarios
     *
     * @param int $id_actaconteo
     */
    public function beneficiariosAction($id_actaconteo) {
    	if (!$this->request->isPost()) {
    		$acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
    		if (!$acta) {
    			$this->flash->error("El acta no fue encontrada");
    
    			return $this->dispatcher->forward(array(
    					"controller" => "cob_periodo",
    					"action" => "index"
    			));
    		}
    		$this->assets
    		->addJs('js/parsley.min.js')
    		->addJs('js/parsley.extend.js')
    		->addJs('js/beneficiarios.js');
    		$this->view->nombre = array();
    		$this->view->acta = $acta;
    		$this->view->beneficiarios = $acta->getCobActaconteoPersona(['order' => 'id_grupo, primerNombre asc']);
    		$beneficiario_grupos = $acta->getCobActaconteoPersona(['group' => 'id_grupo']);
    		$grupos = array();
    		foreach($beneficiario_grupos as $row){
    			$grupos[] = array("id_grupo" => $row->id_grupo, "nombre_grupo" => $row->grupo);
    		}
    		$this->view->grupos = $grupos;
    		$this->view->id_actaconteo = $id_actaconteo;
    		$this->view->asistencia = $this->elements->getSelect("asistencia");
    	}
    }
    
    /**
     * Adicionales
     *
     * @param int $id_actaconteo
     */
    public function adicionalesAction($id_actaconteo) {
    	if (!$this->request->isPost()) {
    		$acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
    		if (!$acta) {
    			$this->flash->error("El acta no fue encontrada");
    
    			return $this->dispatcher->forward(array(
    					"controller" => "cob_periodo",
    					"action" => "index"
    			));
    		}
    		$this->assets
//     		->addJs('js/fileupload/jquery.ui.widget.js')
//     		->addJs('js/fileupload/load-image.all.min.js')
//     		->addJs('js/fileupload/canvas-to-blob.min.js')
//     		->addJs('js/fileupload/jquery.iframe-transport.js')
//     		->addJs('js/fileupload/jquery.fileupload.js')
//     		->addJs('js/fileupload/jquery.fileupload-process.js')
//     		->addJs('js/fileupload/jquery.fileupload-image.js')
//     		->addJs('js/fileupload/jquery.fileupload-validate.js')
    		->addJs('js/bootstrap-filestyle.min.js')
    		->addJs('js/parsley.min.js')
    		->addJs('js/parsley.extend.js')
    		->addJs('js/jquery.autoNumeric.js')
    		->addJs('js/adicionales.js');
    		$ninos = $acta->getCobActaconteoPersona(['tipoPersona = 0', 'order' => 'grupo asc']);
    		$array_ninos = array();
    		foreach($ninos as $row){
    			$array_ninos[] = $row->numDocumento;
    		}
    		$this->view->adicionales = $acta->getCobActaconteoPersona(['tipoPersona = 1', 'order' => 'grupo asc']);
    		$this->view->listado_ninos = implode(",", $array_ninos);
    		$this->view->acta = $acta;
    		$this->view->id_actaconteo = $id_actaconteo;
    		$this->view->asistencia = $this->elements->getSelect("asistencia");
    	}
    }
    
    /**
     * Subir adicional
     *
     */
    public function subiradicionalAction($id_actaconteo) {
    	$this->view->disable();
    	$tipos = array("image/png", "image/jpeg", "image/jpg", "image/bmp", "image/gif");
    	if ($this->request->isPost()) {
    		if ($this->request->hasFiles() == true) {
	    		$uploads = $this->request->getUploadedFiles();
	    		$isUploaded = false;
	    		foreach($uploads as $upload){
	    			if(in_array($upload->gettype(), $tipos)){
	    				$nombre = $id_actaconteo.date("ymdHis").".".$upload->getextension ();
		    			$path = "files/adicionales/".$nombre;
		    			($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
	    			} else {
	    				echo "Tipo";
	    				exit;
	    			}
	    		}
	    		if($isUploaded){
	    			echo $nombre;
	    			
	    		} else {
	    			echo "Error";
	    		}
    		}
        }
    }
 
    /**
     * Guarda el cob_periodo editado
     *
     */
    public function guardarAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
        }

        $id_periodo = $this->request->getPost("id_periodo");

        $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
        if (!$cob_periodo) {
            $this->flash->error("cob_periodo no existe " . $id_periodo);

            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
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

        return $this->dispatcher->forward(array(
            "controller" => "cob_periodo",
            "action" => "index"
        ));

    }

    /**
     * Elimina un cob_periodo
     *
     * @param int $id_periodo
     */
    public function eliminarAction($id_periodo)
    {

        $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
        if (!$cob_periodo) {
            $this->flash->error("cob_periodo no fue encontrado");

            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
        }

        if (!$cob_periodo->delete()) {

            foreach ($cob_periodo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
        }

        $this->flash->success("El periodo fue eliminado correctamente");

        return $this->dispatcher->forward(array(
            "controller" => "cob_periodo",
            "action" => "index"
        ));
    }

}
