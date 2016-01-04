<?php
 
use Phalcon\Mvc\Model\Criteria;

class BcPermisoController extends ControllerBase
{    
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Permisos");
        $this->user = $this->session->get('auth');
        $this->id_usuario = $this->user['id_usuario'];
        parent::initialize();
    }

    /**
     * index action
     */
    public function indexAction()
    {
    	/*	Si el nivel de usuario corresponde a un oferente (4), carga los permisos de ese oferente,
    	 * de lo contrario cargará la lista completa de permisos
    	*/
    	if($this->user['nivel'] == 4){
    		$oferente = IbcUsuarioOferente::findFirstByid_usuario($this->id_usuario);
    		if(!$oferente){
    			$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    			return $this->response->redirect("/");
    		}
    		$permisos = BcPermiso::find(array("id_oferente = $oferente->id_oferente"));
    	} else {
    		$permisos = BcPermiso::find();
    	}
        if (count($permisos) == 0) {
        	$this->flash->notice("No se ha agregado ningún permiso hasta el momento");
        	$permisos = null;
        }
        $this->view->permisos = $permisos;
    }

    /**
     * Formulario para la reación deuna carga
     */
    public function nuevoAction($id_categoria, $accion2)
    {
    	/*	Si el nivel de usuario corresponde a un oferente (4), carga las sedes de ese oferente,
    	 * de lo contrario cargará la lista completa de contratos
    	 */
    	if($this->user['nivel'] == 4){
    		$oferente = IbcUsuarioOferente::findFirstByid_usuario($this->id_usuario);
    		if(!$oferente){
    			$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    			return $this->response->redirect("/");
    		}
    		$sedes = BcSedeContrato::find(array("id_oferente = $oferente->id_oferente", "group" => "id_sede_contrato"));
    	} else {
    		$sedes = BcSedeContrato::find(array("group" => "id_sede_contrato"));
    	}
    	//Cuando no existen contratos se coloca mensaje de alerta
    	if (count($sedes) == 0) {
    		$this->flash->notice("No se ha agregado ningún contrato, por lo tanto no puede crear permisos");
    		$sedes = null;
    	}
    	$this->view->sedes = $sedes;
    	if($id_categoria == 'incidente'){
    		if(!$accion2){
    			$this->assets
    			->addJs('js/multifilter.min.js');
    			$this->view->titulo = "Nuevo Permiso - Incidente";
    			$this->view->enlace = "incidente";
    			$this->view->pick('bc_permiso/seleccionar_sede');
    		} else {
    			$sede = BcSedeContrato::findFirstByid_sede_contrato($accion2);
    			if(!$sede){
    				$this->flash->notice("No se encontró la sede, por favor inténtelo nuevamente o contacte con el administrador");
    				return $this->response->redirect("/bc_permiso/");
    			}
    			$this->view->sede = $sede;
    			$this->view->id_sede_contrato = $accion2;
    			$this->assets
    			->addJs('js/parsley.min.js')
    			->addJs('js/parsley.extend.js');
    			$this->view->pick('bc_permiso/nuevo_incidente');
    		}
    		
    	} else if($id_categoria == 'jornada_planeacion'){
    		if(!$accion2){
    			$this->assets
    			->addJs('js/multifilter.min.js');
    			$this->view->titulo = "Nuevo Permiso - Jornada de Planeación";
    			$this->view->enlace = "jornada_planeacion";
    			$this->view->pick('bc_permiso/seleccionar_sede');
    		} else {
    			$sede = BcSedeContrato::findFirstByid_sede_contrato($accion2);
    			if(!$sede){
    				$this->flash->notice("No se encontró la sede, por favor inténtelo nuevamente o contacte con el administrador");
    				return $this->response->redirect("/bc_permiso/");
    			}
    			$this->view->sede = $sede;
    			$this->view->id_sede_contrato = $accion2;
    			$this->assets
    			->addJs('js/parsley.min.js')
    			->addJs('js/parsley.extend.js')
    			->addJs('js/jquery.autoNumeric.js')
    			->addJs('js/jquery.timepicker.min.js')
    			->addJs('js/bootstrap-datepicker.min.js')
    			->addJs('js/bootstrap-datepicker.es.min.js')
    			->addJs('js/jquery.datepair.min.js')
    			->addCss('css/jquery.timepicker.css')
    			->addCss('css/bootstrap-datepicker.min.css')
    			->addJs('js/jornada_planeacion.js');
    			$this->view->pick('bc_permiso/nuevo_jornada_planeacion');
    			
    		}
    		
    	} else if($id_categoria == "salida_pedagogica" || $id_categoria == "movilizacion_social" || $id_categoria == "salida_ludoteka"){
    		$this->view->titulo = $this->elements->getCategoriaPermiso($id_categoria)['titulo'];
    		$this->view->enlace = $this->elements->getCategoriaPermiso($id_categoria)['enlace'];
    		if(!$accion2){
    			$this->assets
    			->addJs('js/multifilter.min.js');
    			$this->view->pick('bc_permiso/seleccionar_sede');
    		} else {
    			$sede = BcSedeContrato::findFirstByid_sede_contrato($accion2);
    			if(!$sede){
    				$this->flash->notice("No se encontró la sede, por favor inténtelo nuevamente o contacte con el administrador");
    				return $this->response->redirect("/bc_permiso/");
    			}
    			$this->view->sede = $sede;
    			$this->view->id_sede_contrato = $accion2;
    			$this->view->id_categoria = $this->elements->getCategoriaPermiso($id_categoria)['id'];
    			$this->assets
    			->addJs('js/parsley.min.js')
    			->addJs('js/parsley.extend.js')
    			->addJs('js/jquery.autoNumeric.js')
    			->addJs('js/jquery.timepicker.min.js')
    			->addJs('js/bootstrap-datepicker.min.js')
    			->addJs('js/bootstrap-datepicker.es.min.js')
    			->addJs('js/jquery.datepair.min.js')
    			->addCss('css/jquery.timepicker.css')
    			->addCss('css/bootstrap-datepicker.min.css')
    			->addJs('js/bootstrap-filestyle.min.js')
    			->addJs('js/permiso_general.js');
    			$this->view->pick('bc_permiso/nuevo_general');
    			
    		}
    	}
    }
    
    public function crear_incidenteAction($id_sede_contrato){
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_permiso/nuevo");
    	}
    	$sede = BcSedeContrato::findFirstByid_sede_contrato($id_sede_contrato);
    	if(!$sede){
    		$this->flash->notice("No se encontró la sede, por favor inténtelo nuevamente o contacte con el administrador");
    		return $this->response->redirect("/bc_permiso/nuevo");
    	}
    	$bc_permiso = new BcPermiso();
    	$bc_permiso->id_oferente = $sede->id_oferente;
    	$bc_permiso->categoria = 1;
    	$bc_permiso->id_sede_contrato = $sede->id_sede_contrato;
    	$bc_permiso->titulo = $this->request->getPost("titulo");
    	$bc_permiso->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    	$bc_permiso->observaciones = $this->request->getPost("observaciones");
    	if (!$bc_permiso->save()) {
    		foreach ($bc_permiso->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("bc_permiso/nuevo/incidente");
    	}
    	$this->flash->success("El permiso se creó exitosamente.");
    	return $this->response->redirect("bc_permiso");
    }
    
    public function crear_jornada_planeacionAction($id_sede_contrato){
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_permiso/nuevo");
    	}
    	$sede = BcSedeContrato::findFirstByid_sede_contrato($id_sede_contrato);
    	if(!$sede){
    		$this->flash->notice("No se encontró la sede, por favor inténtelo nuevamente o contacte con el administrador");
    		return $this->response->redirect("/bc_permiso/nuevo");
    	}
    	$error = array();
    	$meses = array("0");
    	foreach($this->request->getPost("fecha") as $row){
    		$parts = explode('/', $row);
    		$mes = intval($parts[1]);
    		$sede2 = BcPermiso::find("MONTH(fecha) = $mes AND categoria = 5 AND id_sede_contrato = $id_sede_contrato");
    		//Primero que no sea menor a la fecha actual + 10 días
    		if(strtotime($this->conversiones->fecha(1, $row)) < strtotime('+9 days')){
    			$error[] = 1;
    		} else if(count($sede2)){
    			$error[] = 2;
    		} else if(array_search($mes, $meses)){
    			$error[] = 3;
    		} else {
    			$error[] = 0;
    		}
    		$meses[] = $mes;
    	}
    	if(max($error) > 0){
    		$this->view->fechas = $this->request->getPost("fecha");
    		$this->view->horasInicio = $this->request->getPost("horaInicio");
    		$this->view->horasFin = $this->request->getPost("horaFin");
    		$this->view->error = $error;
    		$this->dispatcher->forward(
    				array(
    						"controller" => "bc_permiso",
    						"action" => "nuevo",
    						"params" => array("jornada_planeacion", $id_sede_contrato)
    				)
    		);
    		return;
    	}
    	$fechas = $this->conversiones->array_fechas(1, $this->request->getPost("fecha"));
    	$elementos = array(
    			'fecha' => $fechas,
    			'id_oferente' => $sede->id_oferente,
    			'categoria' => '5',
    			'id_sede_contrato' => $sede->id_sede_contrato,
	    		'horaInicio' => $this->request->getPost("horaInicio"),
	    		'horaFin' => $this->request->getPost("horaFin")    			
	    );
    	$db = $this->getDI()->getDb();
    	$sql = $this->conversiones->multipleinsert("bc_permiso", $elementos);
    	$query = $db->query($sql);
    	if (!$query) {
    		foreach ($query->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("bc_permiso/nuevo");
    	}
    	$this->flash->success("Los permisos se crearon exitosamente");
    	return $this->response->redirect("bc_permiso/");
    }
    
    public function crear_generalAction($id_sede_contrato, $id_categoria){
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_permiso/nuevo");
    	}
    	$sede = BcSedeContrato::findFirstByid_sede_contrato($id_sede_contrato);
    	if(!$sede || $id_categoria == NULL){
    		$this->flash->notice("No se encontró la sede, por favor inténtelo nuevamente o contacte con el administrador");
    		return $this->response->redirect("/bc_permiso/nuevo");
    	}
    	$bc_permiso = new BcPermiso();
    	$bc_permiso->id_oferente = $sede->id_oferente;
    	$bc_permiso->categoria = $id_categoria;
    	$bc_permiso->id_sede_contrato = $sede->id_sede_contrato;
    	$bc_permiso->titulo = $this->request->getPost("titulo");
    	$bc_permiso->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    	$bc_permiso->horaInicio = $this->request->getPost("horaInicio");
    	$bc_permiso->horaFin = $this->request->getPost("horaFin");
    	$bc_permiso->observaciones = $this->request->getPost("observaciones");
    	if (!$bc_permiso->save()) {
    		foreach ($bc_permiso->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("bc_permiso/nuevo");
    	}
    	$bc_permiso_general = new BcPermisoGeneral();
    	$bc_permiso_general->id_permiso = $bc_permiso->id_permiso;
    	$bc_permiso_general->listadoNinios = $this->request->getPost("listadoNinios");
    	$bc_permiso_general->consentimientoInformado = $this->request->getPost("consentimientoInformado");
    	$bc_permiso_general->actores = $this->request->getPost("actores");
    	$bc_permiso_general->direccionEvento = $this->request->getPost("direccionEvento");
    	$bc_permiso_general->personaContactoEvento = $this->request->getPost("personaContactoEvento");
    	$bc_permiso_general->telefonoContactoEvento = $this->request->getPost("telefonoContactoEvento");
    	$bc_permiso_general->emailContactoEvento = $this->request->getPost("emailContactoEvento");
    	$bc_permiso_general->requiereTransporte = $this->request->getPost("requiereTransporte");
    	if (!$bc_permiso_general->save()) {
    		foreach ($bc_permiso->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		$bc_permiso->delete();
    		return $this->response->redirect("bc_permiso/nuevo");
    	}
    	if($this->request->getPost("requiereTransporte") == 1){
    		
    		$bc_permiso_general_transporte = new BcPermisoGeneralTransporte();
    		$bc_permiso_general_transporte->id_permiso = $bc_permiso->id_permiso;
    		$bc_permiso_general_transporte->contratoTransporte = $this->request->getPost("contratoTransporte");
    		$bc_permiso_general_transporte->runtConductor = $this->request->getPost("runtConductor");
    		$bc_permiso_general_transporte->runtVehiculo = $this->request->getPost("runtVehiculo");
    		$bc_permiso_general_transporte->soat = $this->request->getPost("soat");
    		$bc_permiso_general_transporte->tarjetaOperacionVehiculo = $this->request->getPost("tarjetaOperacionVehiculo");
    		if (!$bc_permiso_general_transporte->save()) {
    			foreach ($bc_permiso->getMessages() as $message) {
    				$this->flash->error($message);
    			}
    			$bc_permiso->delete();
    			$bc_permiso_general->delete();
    			return $this->response->redirect("bc_permiso/nuevo");
    		}
    	}
    	$this->flash->success("El permiso fue creado exitosamente");
    	return $this->response->redirect("bc_permiso/");
    }
    
    /**
     * Subir adicional
     *
     */
    public function subir_archivoAction($id_sede_contrato) {
    	
    	$this->view->disable();
    	$tipos = array("image/png", "image/jpeg", "image/jpg", "image/bmp", "image/gif", "application/pdf");
    	if ($this->request->isPost()) {
    		if ($this->request->hasFiles() == true) {
    			$uploads = $this->request->getUploadedFiles();
    			$isUploaded = false;
    			foreach($uploads as $upload){
    				if(in_array($upload->gettype(), $tipos)){
    					$nombre = $id_sede_contrato.date("ymdHis").".".$upload->getextension ();
    					$path = "files/permisos/".$nombre;
    					($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
    				} else {
    					echo "Tipo";
    					exit;
    				}
    			}
    			if($isUploaded){
    				chmod($path, 0777);
    				echo $nombre;
    
    			} else {
    				echo "Error";
    			}
    		}else {
    			return "Error";
    		}
    	}
    }
    
    /**
     * Para crear una carga, aquí es a donde se dirige el formulario de nuevoAction
     */
    public function crearAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_carga/");
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
    			
    				return $this->response->redirect("bc_carga/nuevo");
    			}
    			
    			$this->flash->success("La carga fue realizada exitosamente.");
    			
    			return $this->response->redirect("bc_carga/");
    		} else {
    			$this->flash->error("Ocurrió un error al cargar los archivos");
    			return $this->response->redirect("bc_carga/nuevo");
    		}
    	}else{
    	    	$this->flash->error("Debes de seleccionar los archivos");
    			return $this->response->redirect("bc_carga/nuevo");
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
            return $this->response->redirect("bc_carga/");
        }

        if (!$bc_carga->delete()) {

            foreach ($bc_carga->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("bc_carga/");
        }

        $this->flash->success("La carga fue eliminada exitosamente");
        return $this->response->redirect("bc_carga/");
    }

}
