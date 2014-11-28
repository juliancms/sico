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
            $this->flash->notice("No se ha agregado ningún cob_periodo hasta el momento");
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
     * Editar
     *
     * @param string $id_periodo
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

        $this->flash->success("cob_periodo fue creado exitosamente.");

        return $this->dispatcher->forward(array(
            "controller" => "cob_periodo",
            "action" => "index"
        ));

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
     * @param string $id_periodo
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
