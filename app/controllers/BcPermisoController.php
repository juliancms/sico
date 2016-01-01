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
    		
    	} else if($id_categoria == 3){
    		$this->view->setTemplateAfter('../bc_permiso/nuevo_incidente');
    	} else if($id_categoria == 4){
    		$this->view->setTemplateAfter('../bc_permiso/nuevo_incidente');
    	} else if($id_categoria == 5){
    		$this->view->setTemplateAfter('../bc_permiso/nuevo_incidente');
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
    	$i = 0;
    	foreach($this->request->getPost("fecha") as $row){
    		//Primero que no sea menor a la fecha actual + 10 días
    		if(strtotime($this->conversiones->fecha(1, $row)) < strtotime('+9 days')){
    			echo "Error"; break;
    		}
    		//Después que no exista otra fecha para el mismo mes en la misma sede
    		$parts = explode('/', $row);
    		$mes = intval($parts[1]);
    		$sede = BcPermiso::find("MONTH(fecha) = 2 AND categoria = 5 AND id_sede_contrato = $id_sede_contrato");
    		if(count($sede)){
    			echo "Error2"; break;
    		}
    	}
    	echo "No error"; break;
//     	$bc_permiso = new BcPermiso();
//     	$bc_permiso->id_oferente = $sede->id_oferente;
//     	$bc_permiso->categoria = 1;
//     	$bc_permiso->id_sede_contrato = $sede->id_sede_contrato;
//     	$bc_permiso->titulo = $this->request->getPost("titulo");
//     	$bc_permiso->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
//     	$bc_permiso->observaciones = $this->request->getPost("observaciones");
//     	if (!$bc_permiso->save()) {
//     		foreach ($bc_permiso->getMessages() as $message) {
//     			$this->flash->error($message);
//     		}
//     		return $this->response->redirect("bc_permiso/nuevo/incidente");
//     	}
//     	$this->flash->success("El permiso se creó exitosamente.");
//     	return $this->response->redirect("bc_permiso");
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