<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Bienvenido(a)');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {
            $this->flash->notice('Los datos aquí presentados son a manera de ejemplo, y actualmente esta versión de SICO no ha sido publicada.');
        }
    }
}
