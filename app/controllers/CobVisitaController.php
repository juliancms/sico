<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CobVisitaController extends ControllerBase
{    
	public $user;
    public function initialize()
    {
        $this->tag->setTitle("Visitas");
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
        $cob_visita = CobVisita::find(['order' => 'fecha, tipo asc']);
        if (count($cob_visita) == 0) {
            $this->flash->notice("No se ha agregado ninguna visita hasta el momento");
            $cob_visita = null;
        }
        $this->view->nivel = $this->user['nivel'];
        $this->view->cob_visita = $cob_visita;
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
    public function verAction($id_visita)
    {
    	$cob_visita = CobVisita::findFirstByid_visita($id_visita);
    	if (!$cob_visita) {
    		$this->flash->error("La visita no fue encontrada");
    		return $this->response->redirect("cob_periodo/");
    	}
    	if($cob_periodo->tipo == 2) {
    		$recorridos = CobActamuestreo::find(array(
    				"id_periodo = $id_periodo",
    				"group" => "recorrido"
    		));
    	} else {
    		$recorridos = CobActaconteo::find(array(
    				"id_periodo = $id_periodo",
    				"group" => "recorrido"
    		));
    	}
    	$this->view->id_periodo = $cob_periodo->id_periodo;
    	$this->view->fecha_periodo = $cob_periodo->getFechaDetail();
    	$this->view->fecha_cierre = $cob_periodo->fechaCierre;
    	$this->view->recorridos = $recorridos;
    	$this->view->crear_recorrido = count($recorridos) + 1;
    	$this->view->nivel = $this->user['nivel'];
    }

    /**
     * Editar
     *
     * @param int $id_periodo
     */
    public function editarAction($id_visita)
    {
        if (!$this->request->isPost()) {

            $cob_visita = CobVisita::findFirstByid_visita($id_visita);
            if (!$cob_visita) {
                $this->flash->error("La visita no fue encontrada");

                return $this->response->redirect("cob_visita/");
            }
            $this->assets
            ->addJs('js/parsley.min.js')
            ->addJs('js/parsley.extend.js');
            $this->view->id_visita = $cob_visita->id_visita;
            $this->tag->setDefault("id_visita", $cob_visita->id_visita);
            $this->tag->setDefault("nombre", $cob_visita->nombre);
            $this->tag->setDefault("fecha", $this->conversiones->fecha(2, $cob_visita->fecha));
        }
    }
    
    /**
     * Creación de una nueva cob_visita
     */
    public function crearAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->response->redirect("cob_visita/nuevo");
    	}
    	$cob_visita = new CobVisita();
    	$cob_visita->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
    	$cob_visita->tipo = $this->request->getPost("tipo");
    	$cob_visita->nombre = $this->request->getPost("nombre");
    
    	if (!$cob_visita->save()) {
    		foreach ($cob_visita->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    		return $this->response->redirect("cob_visita/nuevo");
    	}
    	$this->flash->success("La visita fue creada exitosamente.");
    	return $this->response->redirect("cob_visita/");
    }   
 
    /**
     * Guarda el cob_visita editado
     *
     */
    public function guardarAction()
    {

        if (!$this->request->isPost()) {
            return $this->response->redirect("cob_visita/");
        }

        $id_visita = $this->request->getPost("id_visita");

        $cob_visita = CobVisita::findFirstByid_visita($id_visita);
        if (!$cob_visita) {
            $this->flash->error("La visita no existe " . $id_visita);
            return $this->response->redirect("cob_visita/");
        }

        $cob_visita->fecha = $this->conversiones->fecha(1, $this->request->getPost("fecha"));
        $cob_visita->nombre = $this->request->getPost("nombre");
        

        if (!$cob_visita->save()) {

            foreach ($cob_visita->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->response->redirect("cob_visita/ver/$id_visita");
        }

        $this->flash->success("La visita fue actualizada exitosamente");

        return $this->response->redirect("cob_visita/");

    }

    /**
     * Elimina un cob_visita
     *
     * @param int $id_visita
     */
    public function eliminarAction($id_visita)
    {
        $cob_visita = CobVisita::findFirstByid_visita($id_visita);
        if (!$cob_visita) {
            $this->flash->error("La visita no fue encontrada");

            return $this->response->redirect("cob_visita/");
        }
        if (!$cob_visita->delete()) {
            foreach ($cob_visita->getMessages() as $message) {
                $this->flash->error($message);
            }
           	return $this->response->redirect("cob_visita/ver/$id_visita");
        }
        $this->flash->success("La visita fue eliminada correctamente");
        return $this->response->redirect("cob_visita/");
    }

}
