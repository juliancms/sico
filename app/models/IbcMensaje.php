<?php

class IbcMensaje extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_mensaje;
    
    /**
     *
     * @var integer
     */
    public $id_usuario;

    /**
     *
     * @var string
     */
    public $mensaje;
    
    /**
     *
     * @var integer
     */
    public $tipo;

}
