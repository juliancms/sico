<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CobPeriodoController extends ControllerBase
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
        $cob_periodo = CobPeriodo::find();
        if (count($cob_periodo) == 0) {
            $this->flash->notice("No se ha agregado ningún periodo hasta el momento");
            $cob_periodo = null;
        }
        $this->view->cob_periodo = $cob_periodo;
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
    		 
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
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
    			"id_periodo = $id_periodo",
    			"recorrido = $recorrido",
    			"group" => "id_actaconteo"
    	));
    	if (!$cob_periodo) {
    		$this->flash->error("El periodo no fue encontrado");
    		 
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	if (!recorrido) {
    		$this->flash->error("El recorrido no fue encontrado");
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->fecha_periodo = $cob_periodo->id_periodo;
    	$this->view->actas = $recorrido;
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
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
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
    	$this->dispatcher->setParams(array("id_periodo" => $id_periodo));
    	$modalidades = BcModalidad::find();
    	$this->view->modalidades = $modalidades;
    	$this->view->cargas = BcCarga::find();
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

                return $this->dispatcher->forward(array(
                    "controller" => "cob_periodo",
                    "action" => "index"
                ));
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
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "index"
    		));
    	}
    	$cob_periodo = new CobPeriodo();
    	$cob_periodo->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    
    	if (!$cob_periodo->save()) {
    		foreach ($cob_periodo->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->dispatcher->forward(array(
    				"controller" => "cob_periodo",
    				"action" => "nuevo"
    		));
    	}
    	$this->flash->success("El periodo fue creado exitosamente.");
    	return $this->dispatcher->forward(array(
    			"controller" => "cob_periodo",
    			"action" => "index"
    	));
    }

    /**
     * Generar un nuevo recorrido
     */
    public function generarrecorrido1Action($id_periodo){
        if (!$this->request->isPost() || !$id_periodo) {
            return $this->dispatcher->forward(array(
                "controller" => "cob_periodo",
                "action" => "index"
            ));
        }
        $cob_periodo = CobPeriodo::findFirstByid_periodo($id_periodo);
        if (!$cob_periodo) {
        	$this->flash->error("El periodo no existe");
        
        	return $this->dispatcher->forward(array(
        			"controller" => "cob_periodo",
        			"action" => "index"
        	));
        }
        $id_carga = $this->request->getPost("carga");
        $facturacion = $this->request->getPost("facturacion");
        $modalidades = implode(",", $this->request->getPost("modalidad"));
        $carga = BcCarga::findFirstByid_carga($id_carga);
        if (!carga) {
        	$this->flash->error("La carga no existe");
        	return $this->forward("cob_periodo/nuevorecorrido1/$id_periodo");
        }
        $actas = CobActaconteo::generarActasR1($cob_periodo, $carga, $modalidades, $facturacion);
        if($actas){
        	$this->flash->success("Se generaron exitosamente las actas");
        }
        return $this->forward("cob_periodo/ver/$id_periodo");
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
     * Elimina un  cob_periodo
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
