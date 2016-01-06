<?php

class BcPermisoObservacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_permiso;
    
    /**
     *
     * @var integer
     */
    public $id_usuario;

    /**
     *
     * @var string
     */
    public $fecha_hora;


    /**
     *
     * @var integer
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $observacion;
    
    public function initialize()
    {
    	$this->belongsTo('id_usuario', 'IbcUsuario', 'id_usuario', array(
    			'reusable' => true
    	));
    }

}
