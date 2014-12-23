<?php

class CobPeriodo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_periodo;

    /**
     *
     * @var string
     */
    public $fecha;
    
    public function initialize()
    {
    	$this->hasMany('id_periodo', 'CobActaconteo', 'id_periodo', array(
    			'foreignKey' => array(
    					'message' => 'El periodo no puede ser eliminado porque está siendo utilizado en algún Acta de Conteo'
    			)
    	));
    }
}
