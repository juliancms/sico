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
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
    	->addJs('js/permisos_lista.js');
    	$fecha_actual = date('Y-m-d');
    	$semana = $fecha_actual;
    	if(date("w", strtotime($fecha_actual)) != 0){
    		$semana = date("Y-m-d", strtotime($fecha_actual. ' next Sunday'));
    	}
    	$mes_actual = date('m');
    	$mes_siguiente = intval($mes_actual) + 1;
    	$mes_siguiente = sprintf("%02d", $mes_siguiente);
    	$mes_anterior = intval($mes_actual) - 1;
    	$mes_anterior = sprintf("%02d",$mes_anterior);
    	$permisos = BcPermiso::find(array("MONTH(fecha) = $mes_actual", "order" => "fecha ASC"));
    	$aprobar_permiso = "";
    	switch ($this->user['id_componente']) {
    		case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$permisos = BcPermiso::find(array("id_oferente = $oferente->id_oferente AND MONTH(fecha) = $mes_actual", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_prestador');
    			break;
    		case 4:
    			$permisos = BcPermiso::find(array("estado = 2", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_ibc');
    			break;
    		case 1:
    			if($this->user['nivel'] > 2){
    				$this->view->pick('bc_permiso/index_ibc');
    			} else {
    				$this->view->pick('bc_permiso/index_interventor');
    			}
    			break;
    		case 2:
    			$aprobar_texto = $this->elements->permiso("aprobar_bc");
    			$this->view->pick('bc_permiso/index_bc');
    			break;
    	}
        if (count($permisos) == 0) {
        	$this->flash->notice("No se existen permisos para este mes");
        	$permisos = null;
        }
        if($mes_actual == 1){
        	$this->view->btn_anterior = "<a disabled='disabled' class='btn btn-primary'>&lt;&lt; Anterior</a>";
        } else {
        	$this->view->btn_anterior = "<a href='/sico/bc_permiso/mes/". $mes_anterior ."'class='btn btn-primary'>&lt;&lt; Anterior</a>";
        }
        if($mes_actual == 12){
        	$this->view->btn_siguiente = "<a disabled='disabled' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
        } else {
        	$this->view->btn_siguiente = "<a href='/sico/bc_permiso/mes/". $mes_siguiente ."' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
        }
        $this->view->btn_anio = "<a href='/sico/bc_permiso/anio/' class='btn btn-warning'>Año</button>";
        $this->view->btn_mes = "<a class='btn btn-warning active'>Mes</a>";
        $this->view->btn_semana = "<a href='/sico/bc_permiso/semana/". $semana ."' class='btn btn-warning'>Semana</a>";
        $this->view->btn_dia = "<a href='/sico/bc_permiso/dia/". $fecha_actual ."' class='btn btn-warning'>Día</a>";
        $this->view->titulo = $this->conversiones->fecha(8, date('Y-m-d'));
        $this->view->permisos = $permisos;
        $this->view->aprobar_texto = $aprobar_texto;
    }
    
    /**
     * permiso action
     */
    public function permisoAction($id_permiso)
    {
    	if(!$id_permiso){
    		return $this->response->redirect("bc_permiso");
    	}
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/permisos_lista.js');
    	$fecha_limite = strtotime(date('Y-m-d'). ' +1 days');
    	$permiso = BcPermiso::find(array("id_permiso = $id_permiso"));
    	$texto_anular = "";
    	switch ($this->user['id_componente']) {
    		case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$permiso = BcPermiso::find(array("id_oferente = $oferente->id_oferente AND id_permiso = $id_permiso"));
	    		if(strtotime($permiso[0]->fecha) > $fecha_limite && $permiso[0]->estado < 3){
	    			$this->view->accion_permiso = "<a style='margin-left: 3px;' href='#eliminar_elemento' data-toggle = 'modal' class='btn btn-danger regresar eliminar_fila' data-id = '". $permiso[0]->id_permiso ."' id='bc_permiso/eliminar/".$permiso[0]->id_permiso."'><i class='glyphicon glyphicon-remove'></i> Anular Permiso</a>";
	    		}
    			break;
    		case 1:
    			if($permiso[0]->estado == 0){
    				if($this->user['nivel'] > 2){
    					$this->view->accion_permiso = "";
    				} else {
    					$this->view->accion_permiso = "<a style='margin-left: 3px;' href='/sico/bc_permiso/aprobar/".$permiso[0]->id_permiso."' class='btn btn-success regresar'><i class='glyphicon glyphicon-ok'></i> Pre Aprobar Permiso</a><a style='margin-left: 3px;' href='#eliminar_elemento' data-toggle = 'modal' class='btn btn-danger regresar eliminar_fila' data-id = '". $permiso[0]->id_permiso ."' id='/sico/bc_permiso/eliminar/".$permiso[0]->id_permiso."'><i class='glyphicon glyphicon-remove'></i> Anular Permiso</a>";
    				}
    			}
    			break;
    		case 2:
    			if($permiso[0]->estado == 1){
    				$this->view->accion_permiso = "<a style='margin-left: 3px;' href='/sico/bc_permiso/aprobar/".$permiso[0]->id_permiso."' class='btn btn-success regresar'><i class='glyphicon glyphicon-ok'></i> Aprobar Permiso</a><a style='margin-left: 3px;' href='#eliminar_elemento' data-toggle = 'modal' class='btn btn-danger regresar eliminar_fila' data-id = '". $permiso[0]->id_permiso ."' id='/sico/bc_permiso/eliminar/".$permiso[0]->id_permiso."'><i class='glyphicon glyphicon-remove'></i> Anular Permiso</a>";
    			}
    			$texto_anular = "Se recuerda a la entidad contar con los procedimientos de seguridad para estas salidas y garantizar la alimentación de los niños y las niñas como lo establece la minuta.";
    			break;
    		case 4:
    			$this->view->accion_permiso = "";
    			break;
    	}
    	if (count($permiso) == 0) {
    		$this->flash->notice("El permiso no fue encontrado en la base de datos");
    		return $this->response->redirect("bc_permiso");
    	}
    	$this->assets
    	->addCss('css/observaciones.css');
    	$this->view->permiso = $permiso[0];
    	$this->view->pick("bc_permiso/permiso_" . $this->elements->getCategoriaEnlace($permiso[0]->categoria));
    	$this->view->texto_anular = $texto_anular;
    }
    
    /**
     * permiso action
     */
    public function revisionAction()
    {
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
    	->addJs('js/permisos_lista.js');
    	$anular_permiso = "";
    	if($this->user['id_componente'] == 1){
    		$permisos = BcPermiso::find(array("estado = 0", "order" => "id_permiso ASC"));
    		$this->view->pick('bc_permiso/revision_interventor');
    	} else if($this->user['id_componente'] == 2) {
    		$permisos = BcPermiso::find(array("estado = 1", "order" => "id_permiso ASC"));
    		$anular_permiso = "Se recuerda a la entidad contar con los procedimientos de seguridad para estas salidas y garantizar la alimentación de los niños y las niñas como lo establece la minuta.";
    		$this->view->pick('bc_permiso/revision_bc');
    	}
    	if (count($permisos) == 0) {
    		$this->flash->notice("Felicitaciones: no se encontraron permisos para revisar.");
    		return $this->response->redirect("bc_permiso");
    	}
    	$this->view->permisos = $permisos;
    	$this->view->anular_permiso = $anular_permiso;
    }
    
    /**
     * semana action
     */
    public function diaAction($fecha)
    {
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
    	->addJs('js/permisos_lista.js');
    	$date = new DateTime($fecha);
    	$semana = $fecha;
    	$mes_actual = $date->format("m");
    	if(date("w", strtotime($fecha)) != 0){
    		$semana = date("Y-m-d", strtotime($fecha. ' next Sunday'));
    	}
    	$dia_anterior = date("Y-m-d", strtotime($fecha. ' - 1 day'));
    	$dia_siguiente = date("Y-m-d", strtotime($fecha. ' + 1 day'));
    	$permisos = BcPermiso::find(array("fecha = '$fecha'", "order" => "fecha ASC"));
    	switch ($this->user['id_componente']) {
    		case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$permisos = BcPermiso::find(array("id_oferente = $oferente->id_oferente AND fecha = '$fecha'", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_prestador');
    			break;
    		case 4:
    			$permisos = BcPermiso::find(array("estado = 2 AND fecha = '$fecha'", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_ibc');
    			break;
    		case 1:
    			if($this->user['nivel'] > 2){
    				$permisos = BcPermiso::find(array("estado = 2 AND fecha = '$fecha'", "order" => "fecha ASC"));
    				$this->view->pick('bc_permiso/index_ibc');
    			} else {
    				$this->view->pick('bc_permiso/index_interventor');
    			}
    			break;
    		case 2:
    			$this->view->pick('bc_permiso/index_bc');
    			break;
    	}
    	if (count($permisos) == 0) {
    		$this->flash->notice("No se existen permisos para esta semana");
    		$permisos = null;
    	}
    	if($fecha == date('Y') . "-01-01"){
    		$this->view->btn_anterior = "<a disabled='disabled' class='btn btn-primary'>&lt;&lt; Anterior</a>";
    	} else {
    		$this->view->btn_anterior = "<a href='/sico/bc_permiso/dia/". $dia_anterior ."'class='btn btn-primary'>&lt;&lt; Anterior</a>";
    	}
    	if($fecha == date('Y') . "-12-31"){
    		$this->view->btn_siguiente = "<a disabled='disabled' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
    	} else {
    		$this->view->btn_siguiente = "<a href='/sico/bc_permiso/dia/". $dia_siguiente ."' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
    	}
    	$this->view->btn_anio = "<a href='/sico/bc_permiso/anio/' class='btn btn-warning'>Año</button>";
    	$this->view->btn_mes = "<a href='/sico/bc_permiso/mes/" . $mes_actual . "' class='btn btn-warning'>Mes</a>";
    	$this->view->btn_semana = "<a href='/sico/bc_permiso/semana/" . $semana . "' class='btn btn-warning'>Semana</a>";
    	$this->view->btn_dia = "<a class='btn btn-warning active'>Día</a>";
    	$this->view->titulo = $this->conversiones->fecha(4, $fecha);
    	$this->view->permisos = $permisos;
    }
    
    /**
     * semana action
     */
    public function semanaAction($fecha_inicio)
    {
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
    	->addJs('js/permisos_lista.js');
    	$date = new DateTime($fecha_inicio);
		$fecha_inicio_comparacion = date("Y") . "-01-01";
    	if($fecha_inicio == $fecha_inicio_comparacion){
    		//Si es sabado fecha final sera la misma, de lo contrario será el próximo sábado
    		$this->view->btn_anterior = "<a disabled='disabled' class='btn btn-primary'>&lt;&lt; Anterior</a>";
    		if(date("w", strtotime("$fecha_inicio")) == 6){
    			$fecha_final = $fecha_inicio;
    		} else {
    			$fecha_final = date("Y-m-d", strtotime($fecha_inicio. ' next Saturday'));
    		}
    	} else {
    		$fecha_final = date("Y-m-d", strtotime($fecha_inicio. ' next Saturday'));
    		$semana_anterior = date("Y-m-d", strtotime($fecha_final. ' - 13 days'));
    		if(date("Y", strtotime($semana_anterior)) < date("Y")){
    			$semana_anterior = date("Y") . "-01-01";
    			
    		}
    		$this->view->btn_anterior = "<a href='/sico/bc_permiso/semana/" . $semana_anterior . "' class='btn btn-primary'>&lt;&lt; Anterior</a>";
    	}
    	$semana_siguiente = date("Y-m-d", strtotime($fecha_final. ' + 1 day'));
    	if(date("Y", strtotime($semana_siguiente)) > date("Y")){
    		$this->view->btn_siguiente = "<a disabled='disabled' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
    		 
    	} else {
    		$this->view->btn_siguiente = "<a href='/sico/bc_permiso/semana/". $semana_siguiente ."' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
    	}
    	
    	$permisos = BcPermiso::find(array("fecha >= '$fecha_inicio' AND fecha <= '$fecha_final'", "order" => "fecha ASC"));
    	switch ($this->user['id_componente']) {
    		case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$permisos = BcPermiso::find(array("id_oferente = $oferente->id_oferente AND fecha >= '$fecha_inicio' AND fecha <= '$fecha_final'", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_prestador');
    			break;
    		case 4:
    			$permisos = BcPermiso::find(array("estado = 2 AND fecha >= '$fecha_inicio' AND fecha <= '$fecha_final'", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_ibc');
    			break;
    		case 1:
    			if($this->user['nivel'] > 2){
    				$permisos = BcPermiso::find(array("estado = 2 AND fecha >= '$fecha_inicio' AND fecha <= '$fecha_final'", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_ibc');
    			} else {
    				$this->view->pick('bc_permiso/index_interventor');
    			}
    			break;
    		case 2:
    			$this->view->pick('bc_permiso/index_bc');
    			break;
    	}
    	if (count($permisos) == 0) {
    		$this->flash->notice("No se existen permisos para esta semana");
    		$permisos = null;
    	}
    	$this->view->btn_anio = "<a href='/sico/bc_permiso/anio/' class='btn btn-warning'>Año</button>";
    	$this->view->btn_mes = "<a href='/sico/bc_permiso/mes/" . $date->format("m") . "' class='btn btn-warning'>Mes</a>";
    	$this->view->btn_semana = "<a class='btn btn-warning active'>Semana</a>";
    	$this->view->btn_dia = "<a href='/sico/bc_permiso/dia/". $fecha_inicio ."' class='btn btn-warning'>Día</a>";
    	$this->view->titulo = $this->conversiones->fecha(10, $fecha_inicio) . " - " . $this->conversiones->fecha(10, $fecha_final);
    	$this->view->permisos = $permisos;
    }
    
    /**
     * mes action
     */
    public function mesAction($mes_actual)
    {
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
    	->addJs('js/permisos_lista.js');
    	$fecha_actual = date("Y-$mes_actual-01");
    	$date = new DateTime($fecha_actual);
    	$semana = $fecha_actual;
    	if(date("w", strtotime($fecha_actual)) !== 0 && $mes_actual > 1){
    		$semana = date("Y-m-d", strtotime($fecha_actual. ' next Sunday'));
    	}
    	$mes_siguiente = intval($mes_actual) + 1;
    	$mes_siguiente = sprintf("%02d", $mes_siguiente);
    	$mes_anterior = intval($mes_actual) - 1;
    	$mes_anterior = sprintf("%02d",$mes_anterior);
    	$permisos = BcPermiso::find(array("MONTH(fecha) = $mes_actual", "order" => "fecha ASC"));
    	switch ($this->user['id_componente']) {
    		case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$permisos = BcPermiso::find(array("id_oferente = $oferente->id_oferente AND MONTH(fecha) = $mes_actual", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_prestador');
    			break;
    		case 4:
    			$permisos = BcPermiso::find(array("estado = 2 AND MONTH(fecha) = $mes_actual", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_ibc');
    			break;
    		case 1:
    			if($this->user['nivel'] > 2){
    				$permisos = BcPermiso::find(array("estado = 2 AND MONTH(fecha) = $mes_actual", "order" => "fecha ASC"));
    				$this->view->pick('bc_permiso/index_ibc');
    			} else {
    				$this->view->pick('bc_permiso/index_interventor');
    			}
    			break;
    		case 2:
    			$this->view->pick('bc_permiso/index_bc');
    			break;
    	}
    	if (count($permisos) == 0) {
    		$this->flash->notice("No se existen permisos para este mes");
    		$permisos = null;
    	}
    	if($mes_actual == "01"){
    		$this->view->btn_anterior = "<a disabled='disabled' class='btn btn-primary'>&lt;&lt; Anterior</a>";
    	} else {
    		$this->view->btn_anterior = "<a href='/sico/bc_permiso/mes/". $mes_anterior ."'class='btn btn-primary'>&lt;&lt; Anterior</a>";
    	}
    	if($mes_actual == "12"){
    		$this->view->btn_siguiente = "<a disabled='disabled' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
    	} else {
    		$this->view->btn_siguiente = "<a href='/sico/bc_permiso/mes/". $mes_siguiente ."' class='btn btn-primary'>Siguiente &gt;&gt;</a>";
    	}
    	$this->view->btn_anio = "<a href='/sico/bc_permiso/anio/' class='btn btn-warning'>Año</button>";
    	$this->view->btn_mes = "<a class='btn btn-warning active'>Mes</a>";
    	$this->view->btn_semana = "<a href='/sico/bc_permiso/semana/". $semana ."' class='btn btn-warning'>Semana</a>";
    	$this->view->btn_dia = "<a href='/sico/bc_permiso/dia/". $semana ."' class='btn btn-warning'>Día</a>";
    	$this->view->titulo = $this->conversiones->fecha(8, $fecha_actual);
    	$this->view->permisos = $permisos;
    }
    
    /**
     * anio action
     */
    public function anioAction()
    {
    	$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
    	->addJs('js/permisos_lista.js');
    	$fecha_actual = date("Y-01-01");
    	$date = new DateTime($fecha_actual);
    	$permisos = BcPermiso::find(array("order" => "fecha ASC"));
    	switch ($this->user['id_componente']) {
    		case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$permisos = BcPermiso::find(array("id_oferente = $oferente->id_oferente", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_prestador');
    			break;
    		case 4:
    			$permisos = BcPermiso::find(array("estado = 2", "order" => "fecha ASC"));
    			$this->view->pick('bc_permiso/index_ibc');
    			break;
    		case 1:
    			if($this->user['nivel'] > 2){
    				$permisos = BcPermiso::find(array("estado = 2", "order" => "fecha ASC"));
    				$this->view->pick('bc_permiso/index_ibc');
    			} else {
    				$this->view->pick('bc_permiso/index_interventor');
    			}
    			break;
    		case 2:
    			$this->view->pick('bc_permiso/index_bc');
    			break;
    	}
    	if (count($permisos) == 0) {
    		$this->flash->notice("No se existen permisos para este año");
    		$permisos = null;
    	}
    	$this->view->btn_anterior = "";
    	$this->view->btn_siguiente = "";
    	$this->view->btn_anio = "<a class='btn btn-warning active'>Año</button>";
    	$this->view->btn_mes = "<a href='/sico/bc_permiso/mes/01' class='btn btn-warning'>Mes</a>";
    	$this->view->btn_semana = "<a href='/sico/bc_permiso/semana/". date("Y") ."-01-01' class='btn btn-warning'>Semana</a>";
    	$this->view->btn_dia = "<a href='/sico/bc_permiso/dia/". date("Y") ."-01-01' class='btn btn-warning'>Día</a>";
    	$this->view->titulo = "Año " . date('Y');
    	$this->view->permisos = $permisos;
    }

    /**
     * Formulario para la creación de un permiso
     */
    public function nuevoAction($id_categoria, $accion2)
    {
    	if($this->user['id_componente'] == 3){
    		$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
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
    				return $this->response->redirect("bc_permiso");
    			}
    			$this->view->sede = $sede;
    			$this->view->id_sede_contrato = $accion2;
    			$this->assets
    			->addJs('js/parsley.min.js')
    			->addJs('js/parsley.extend.js')
    			->addJs('js/bootstrap-datepicker.min.js')
    			->addJs('js/bootstrap-datepicker.es.min.js')
    			->addJs('js/jquery.datepair.min.js')
    			->addCss('css/bootstrap-datepicker.min.css')
    			->addJs('js/permiso_incidente.js');
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
    				return $this->response->redirect("bc_permiso");
    			}
    			if($sede->modalidad == 5 || $sede->id_modalidad == 12){
    				$limite_permisos = 2;
    			} else {
    				$limite_permisos = 1;
    			}
    			$this->view->permisos_anuales = 12 * $limite_permisos;
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
    				return $this->response->redirect("bc_permiso");
    			}
    			$this->view->sede = $sede;
    			$this->view->id_sede_contrato = $accion2;
    			$this->view->id_categoria = $this->elements->getCategoriaPermiso($id_categoria)['id'];
    			$this->view->categoria = $id_categoria;
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
    	//Verificamos que el permiso no sea menor o igual al día de ayer
    	if(strtotime($this->conversiones->fecha(1, $this->request->getPost("fecha"))) <= strtotime('-1 day')){
    		$this->view->fechas = $this->request->getPost("fecha");
    		$this->view->titulo = $this->request->getPost("titulo");
    		$this->view->observaciones = $this->request->getPost("observaciones");
    		$this->flash->error("No puedes crear un permiso con fecha anterior.");
    		$this->dispatcher->forward(
    				array(
    						"controller" => "bc_permiso",
    						"action" => "nuevo",
    						"params" => array("incidente", $id_sede_contrato)
    				)
    		);
    		return;
    	}
    	$bc_permiso = new BcPermiso();
    	$bc_permiso->id_oferente = $sede->id_oferente;
    	$bc_permiso->categoria = 1;
    	$bc_permiso->estado = 0;
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
    	if($sede->modalidad == 5 || $sede->id_modalidad == 12){
    		$limite_permisos = 2;
    	} else {
    		$limite_permisos = 1;
    	}
    	$error = array();
    	$meses = array("0");
    	foreach($this->request->getPost("fecha") as $row){
    		$parts = explode('/', $row);
    		$mes = intval($parts[1]);
    		$count_meses = array_count_values($meses);
    		$sede2 = BcPermiso::find("MONTH(fecha) = $mes AND categoria = 5 AND id_sede_contrato = $id_sede_contrato AND estado != 3");
    		
    		//Primero que no sea menor a la fecha actual + 10 días
    		if(strtotime($this->conversiones->fecha(1, $row)) < strtotime('+9 days')){
    			$error[] = 1;
    		} else if(count($sede2) >= $limite_permisos){
    			$error[] = 2;
    		} else if($count_meses["$mes"] >= $limite_permisos){
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
    		$this->view->limite = $limite_permisos;
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
    			'estado' => '0',
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
    	$bc_permiso->estado = '0';
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
    		$bc_permiso_general_transporte->runtConductor = $this->request->getPost("runtConductor");
    		$bc_permiso_general_transporte->runtVehiculo = $this->request->getPost("runtVehiculo");
    		$bc_permiso_general_transporte->polizaResponsabilidadCivil = $this->request->getPost("polizaResponsabilidadCivil");
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
    public function subir_archivoAction($id_sede_contrato, $tipo) {
    	
    	$this->view->disable();
    	if($tipo == "img_pdf"){
    		$tipos = array("image/png", "image/jpeg", "image/jpg", "image/bmp", "image/gif", "application/pdf");
    	} else if($tipo == "xls"){
    		$tipos = array("application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    	}
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
    					echo $tipo;
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
     * permiso action
     */
    public function aprobarAction($id_permiso)
    {
    	if($this->user['nivel'] > 2){
    		$this->flash->error("Usted no tiene suficiente privilegios para realizar esta acción.");
    		return $this->response->redirect("bc_permiso");
    	}
    	$permiso = BcPermiso::findFirstByid_permiso($id_permiso);
    	if (!$permiso) {
    		$this->flash->notice("El permiso no fue encontrado en la base de datos.");
    		return $this->response->redirect("bc_permiso");
    	}
    	$permiso_observacion = new BcPermisoObservacion();
    	if($this->user['id_componente'] == 1){
    		$permiso->estado = 1;
    		$permiso_observacion->estado = 1;
    	} else if($this->user['id_componente'] == 2) {
    		$permiso->estado = 2;
    		$permiso_observacion->estado = 2;
    	}
    	$permiso->save();
    	$permiso_observacion->id_permiso = $id_permiso;
    	$permiso_observacion->id_usuario = $this->id_usuario;
    	$permiso_observacion->fechahora = date("Y-m-d H:i:s");
    	$permiso_observacion->observacion = $this->request->getPost("observacion");
    	$permiso_observacion->save();
    	$this->flash->success("El permiso fue aprobado exitosamente");
    	return $this->response->redirect('bc_permiso');
    }
    
    /**
     * Aprobar permiso por parte de Buen Comienzo
     *
     *
     * @param string $id_carga
     */
    public function aprobarbcAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_permiso");
    	}
    	$id_permiso = $this->request->getPost("id_permiso");
    	$permiso = BcPermiso::findFirstByid_permiso($id_permiso);
    	if (!$permiso) {
    		$this->flash->error("El permiso no fue encontrado.");
    		return $this->response->redirect('bc_permiso');
    	}
    	$permiso_observacion = new BcPermisoObservacion();
    	if($this->user['id_componente'] == 1){
    		$permiso->estado = 1;
    		$permiso_observacion->estado = 1;
    	} else if($this->user['id_componente'] == 2) {
    		$permiso->estado = 2;
    		$permiso_observacion->estado = 2;
    	}
    	$permiso->save();
    	$permiso_observacion->id_permiso = $id_permiso;
    	$permiso_observacion->id_usuario = $this->id_usuario;
    	$permiso_observacion->fechahora = date("Y-m-d H:i:s");
    	$permiso_observacion->observacion = $this->request->getPost("observacion");
    	$permiso_observacion->save();
    	$this->flash->success("El permiso fue aprobado exitosamente");
        return $this->response->redirect('bc_permiso');
    }
    
    /**
     * Anular permiso
     * 
     *
     * @param string $id_carga
     */
    public function anularAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect('bc_permiso');
    	}
    	if($this->user['nivel'] > 2){
    		$this->flash->error("Usted no tiene suficiente privilegios para realizar esta acción.");
    		return $this->response->redirect("bc_permiso");
    	}
    	$id_permiso = $this->request->getPost("id_permiso");
    	if($this->user['id_componente'] == 3){
    		$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    		if(!$oferente){
    			$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    			return $this->response->redirect('bc_permiso');
    		}
    		$permiso = BcPermiso::findFirst(array("id_oferente = $oferente->id_oferente AND id_permiso = $id_permiso"));
    	} else {
    		$permiso = BcPermiso::findFirst(array("id_permiso = $id_permiso"));
    	}
   		if (!$permiso) {
            $this->flash->error("El permiso no fue encontrado o no tiene privilegios para anularlo");
            return $this->response->redirect("bc_permiso/");
        }
        $permiso->estado = 3;
        if (!$permiso->save()) {
        	foreach ($permiso->getMessages() as $message) {
        		$this->flash->error($message);
        	}
        	return $this->response->redirect("bc_permiso");
        }
        $permiso_observacion = new BcPermisoObservacion();
        $permiso_observacion->id_permiso = $id_permiso;
        $permiso_observacion->id_usuario = $this->id_usuario;
        $permiso_observacion->fechahora = date("Y-m-d H:i:s");
        $permiso_observacion->estado = 3;
        $permiso_observacion->observacion = $this->request->getPost("observacion");
        $permiso_observacion->save();
        $this->flash->success("El permiso fue anulado exitosamente");
        return $this->response->redirect('bc_permiso');
    }
}
