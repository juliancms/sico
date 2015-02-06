<?php

class ErroresController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Ocurrió un error');
        parent::initialize();
    }

    public function error404Action()
    {

    }

    public function error401Action()
    {

    }

    public function error500Action()
    {

    }
}
