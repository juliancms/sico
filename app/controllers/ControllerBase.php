<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	/**
	 * Definición del título y el archivo de plantilla base
	 */
    protected function initialize()
    {
        $this->tag->prependTitle('SICO | ');
        $this->view->setTemplateAfter('main');
    }
}
