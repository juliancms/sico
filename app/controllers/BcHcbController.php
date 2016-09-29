<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class BcHcbController extends ControllerBase
{
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Periodos");
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
        $BcHcbperiodo = BcHcbperiodo::find(['order' => 'id_hcbperiodo asc']);
        if (count($BcHcbperiodo) == 0) {
						$this->flash->notice("No se ha agregado ningún periodo hasta el momento");
            $BcHcbperiodo = null;
        }
        $this->view->periodos = $BcHcbperiodo;
    }

    /**
     * Formulario para creación de empleado
     */
    public function nuevoempleadoAction()
    {
			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
			if(!$oferente){
				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
				return $this->response->redirect("/");
			}
			$id_oferente = $oferente->IbcUsuarioOferente->id_oferente;
			$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
			->addJs('js/nuevoempleadohcb.js');
			$this->view->cargo = $this->elements->getSelect("cargoitinerante");
    }

		/**
     * Formulario para creación de empleado
     */
    public function empleadosAction()
    {
			switch ($this->user['id_componente']) {
				case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$id_oferente = $oferente->IbcUsuarioOferente->id_oferente;
    			$empleados = BcHcbempleado::find(array("id_oferente = $id_oferente"));
    			break;
    		case 1:
    			if($this->user['nivel'] <= 2){
						$empleados = BcHcbempleado::find();
    			} else {
						$empleados = null;
					}
    			break;
				default:
					$empleados = null;
			}
			$this->view->empleados = $empleados;
			$this->assets
    	->addJs('js/picnet.table.filter.min.js')
			->addJs('js/nuevoempleadohcb.js');
    }

		/**
     * Acción de guardar nuevo empleado
     */
    public function guardarempleadoAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_hcb/nuevoempleado");
    	}
			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
			if(!$oferente){
				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
				return $this->response->redirect("/");
			}
			$id_oferente = $oferente->IbcUsuarioOferente->id_oferente;
    	$empleado = new BcHcbempleado();
    	$empleado->numDocumento = $this->request->getPost("numDocumento");
    	$empleado->primerNombre = $this->request->getPost("primerNombre");
			$empleado->segundoNombre = $this->request->getPost("segundoNombre");
			$empleado->primerApellido = $this->request->getPost("primerApellido");
			$empleado->segundoApellido = $this->request->getPost("segundoApellido");
			$empleado->id_cargo = $this->request->getPost("cargo");
			$empleado->id_oferente = $id_oferente;
    	if (!$empleado->save()) {
    		foreach ($empleado->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("bc_hcb/nuevoempleado");
    	}
    	$this->flash->success("El empleado fue creado exitosamente.");
    	return $this->response->redirect("bc_hcb/nuevoempleado");
    }

		/**
     * Formulario para creación de empleado
     */
    public function editarempleadoAction($id_hcbempleado)
    {
			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
			if(!$oferente){
				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
				return $this->response->redirect("/");
			}
			$id_oferente = $oferente->IbcUsuarioOferente->id_oferente;
			$empleado = BcHcbempleado::findFirstByid_hcbempleado($id_hcbempleado);
			if (!$empleado) {
					$this->flash->error("El empleado no fue encontrado");

					return $this->response->redirect("bc_hcb/empleados");
			}
			$this->view->empleado = $empleado;
			$this->assets
    	->addJs('js/parsley.min.js')
    	->addJs('js/parsley.extend.js')
    	->addJs('js/picnet.table.filter.min.js')
			->addJs('js/nuevoempleadohcb.js');
			$this->view->cargo = $this->elements->getSelect("cargoitinerante");
    }

		/**
     * Acción de guardar nuevo empleado
     */
    public function guardareditarempleadoAction($id_hcbempleado)
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_hcb/editarempleado/$id_hcbempleado");
    	}
			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
			if(!$oferente){
				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
				return $this->response->redirect("/");
			}
    	$empleado = BcHcbempleado::findFirstByid_hcbempleado($id_hcbempleado);
			$empleado->numDocumento = $this->request->getPost("numDocumento");
    	$empleado->primerNombre = $this->request->getPost("primerNombre");
			$empleado->segundoNombre = $this->request->getPost("segundoNombre");
			$empleado->primerApellido = $this->request->getPost("primerApellido");
			$empleado->segundoApellido = $this->request->getPost("segundoApellido");
			$empleado->id_cargo = $this->request->getPost("cargo");

    	if (!$empleado->save()) {
    		foreach ($empleado->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("bc_hcb/editarempleado/$id_hcbempleado");
    	}
    	$this->flash->success("El empleado fue actualizado exitosamente.");
    	return $this->response->redirect("bc_hcb/editarempleado/$id_hcbempleado");
    }

    /**
     * Recorrido
     *
     * @param int $id_hcbperiodo
     */
    public function verAction($id_hcbperiodo)
    {
    	$BcHcbperiodo = BcHcbperiodo::findFirstByid_hcbperiodo($id_hcbperiodo);
    	if (!$BcHcbperiodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
    	}
			switch ($this->user['id_componente']) {
				case 3:
    			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
    			if(!$oferente){
    				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
    				return $this->response->redirect("/");
    			}
    			$id_oferente = $oferente->IbcUsuarioOferente->id_oferente;
    			$sedes = BcSedeContrato::find(array("id_modalidad = 12 AND id_oferente = $id_oferente AND estado = 1"));
    			break;
    		case 1:
    			if($this->user['nivel'] <= 2){
						$sedes = BcSedeContrato::find(array("id_modalidad = 12 AND estado = 1"));
    			} else {
						$sedes = null;
					}
    			break;
				default:
					$sedes = null;
			}
    	$this->assets
    	->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js');
			if ($sedes == null){
				$this->flash->error("No tiene permisos para ver los hogares comunitarios");
			}
			$this->view->sedes = $sedes;
			$this->view->periodo = $BcHcbperiodo;
			$this->view->sedes = $sedes;
			$this->view->mes = $this->conversiones->fecha(11, $id_hcbperiodo);
    	$this->view->id_usuario = $this->id_usuario;
    	$this->view->nivel = $this->user['nivel'];
    }

		/**
     * Recorrido
     *
     * @param int $id_hcbperiodo
     */
    public function cronogramaAction($id_hcbperiodo, $id_sede_contrato)
    {

			if(!$id_hcbperiodo){
				$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
			}
    	$BcHcbperiodo = BcHcbperiodo::findFirstByid_hcbperiodo($id_hcbperiodo);
    	if (!$BcHcbperiodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
    	}

			if(!$id_sede_contrato){
				$this->flash->error("El hogar comunitario no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
			}

			$sede = BcSedeContrato::findFirstByid_sede_contrato($id_sede_contrato);
    	if (!$sede) {
    		$this->flash->error("El hogar comunitario no fue encontrado");
    		return $this->response->redirect("bc_hcb/ver/$id_hcbperiodo");
    	}
			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
			if(!$oferente){
				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
				return $this->response->redirect("/");
			}
			$id_oferente = $oferente->IbcUsuarioOferente->id_oferente;
			$empleados_periodo = BcHcbperiodoEmpleado::find(array("id_sede_contrato = $id_sede_contrato AND id_hcbperiodo = $id_hcbperiodo"));
			$empleados_agregados = array();
			foreach($empleados_periodo as $row){
				$empleados_agregados[] = $row->id_hcbempleado;
			}
			$empleados = BcHcbempleado::find(array("id_oferente = $id_oferente"));
			if(!$empleados){
				$this->flash->error("Antes de crear el cronograma debe de agregar empleados.");
				return $this->response->redirect("bc_hcb/nuevoempleado");
			}
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
			->addCss('css/tooltipster.css')
			->addJs('js/jquery.tooltipster.min.js')
			->addJs('js/jquery.tablesorter.min.js')
    	->addJs('js/jquery.tablesorter.widgets.js')
			->addJs('js/cronogramahcb.js');
			$fecha_inicio = date('d/m/Y', strtotime('first monday of '.date('Y').'-'.$id_hcbperiodo));
			$fecha_fin = date("d/m/Y", strtotime(date('Y-d-m', strtotime($fecha_inicio)). ' next Friday + 3 Weeks'));
			$this->view->fecha_inicio = $fecha_inicio;
			$this->view->fecha_fin = $fecha_fin;
			$this->view->empleados_periodo = $empleados_agregados;
			$this->view->empleados = $empleados;
			$this->view->sede = $sede;
			$this->view->periodo = $BcHcbperiodo;
			$this->view->mes = $this->conversiones->fecha(11, $id_hcbperiodo);
    	$this->view->id_usuario = $this->id_usuario;
    	$this->view->nivel = $this->user['nivel'];

    }

		/**
     * Acción de guardar cronograma
     */
    public function guardarcronogramaAction($id_hcbperiodo, $id_sede_contrato)
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("bc_hcb/editarempleado/$id_hcbempleado");
    	}
			if(!$id_hcbperiodo){
				$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
			}
    	$BcHcbperiodo = BcHcbperiodo::findFirstByid_hcbperiodo($id_hcbperiodo);
    	if (!$BcHcbperiodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
    	}

			if(!$id_sede_contrato){
				$this->flash->error("El hogar comunitario no fue encontrado");
    		return $this->response->redirect("bc_hcb/");
			}
			$oferente = IbcUsuario::findFirstByid_usuario($this->id_usuario);
			if(!$oferente){
				$this->flash->error("Este usuario no fue encontrado en la base de datos de prestadores.");
				return $this->response->redirect("/");
			}
			$db = $this->getDI()->getDb();
			$db->query("DELETE FROM bc_hcbperiodo_empleado WHERE id_sede_contrato = $id_sede_contrato AND id_hcbperiodo = $id_hcbperiodo");
			$db->query("DELETE FROM bc_hcbperiodo_empleado_fecha WHERE id_sede_contrato = $id_sede_contrato AND id_hcbperiodo = $id_hcbperiodo");
			$i = 0;
			$fechamaniana = $this->request->getPost("fechamaniana");
			$fechatarde = $this->request->getPost("fechatarde");
			$ids_hcbempleado = $this->request->getPost("id_hcbempleado");
			foreach($ids_hcbempleado as $id_hcbempleado){
				$periodoempleado = new BcHcbperiodoEmpleado();
				$periodoempleado->id_hcbperiodo = $id_hcbperiodo;
	    	$periodoempleado->id_hcbempleado = $id_hcbempleado;
				$periodoempleado->id_sede_contrato = $id_sede_contrato;
				$periodoempleado->save();
				if($fechamaniana[$i]){
					$fecha1 = explode(",", $fechamaniana[$i]);
					$elementos = array(
							'fecha' => $this->conversiones->array_fechas(1, $fecha1),
							'jornada' => 1,
							'id_hcbperiodo_empleado' => $periodoempleado->id_hcbperiodo_empleado,
							'id_hcbperiodo' => $id_hcbperiodo,
							'id_sede_contrato' => $id_sede_contrato
					);
					$sql = $this->conversiones->multipleinsert("bc_hcbperiodo_empleado_fecha", $elementos);
					$query = $db->query($sql);
				}
				if($fechatarde[$i]){
					$fecha2 = explode(",", $fechatarde[$i]);
					$elementos = array(
							'fecha' => $this->conversiones->array_fechas(1, $fecha2),
							'jornada' => 2,
							'id_hcbperiodo_empleado' => $periodoempleado->id_hcbperiodo_empleado,
							'id_hcbperiodo' => $id_hcbperiodo,
							'id_sede_contrato' => $id_sede_contrato
					);
					$sql = $this->conversiones->multipleinsert("bc_hcbperiodo_empleado_fecha", $elementos);
					$query = $db->query($sql);
				}
				$i++;
			}
    	$this->flash->success("El hogar comunitario fue actualizado exitosamente.");
    	return $this->response->redirect("bc_hcb/cronograma/$id_hcbperiodo/$id_sede_contrato");
    }

}
