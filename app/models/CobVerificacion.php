<?php
use Phalcon\DI\FactoryDefault;
class CobVerificacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_verificacion;
    
    /**
     *
     * @var integer
     */
    public $tipo;

    /**
     *
     * @var string
     */
    public $fecha;
    
    /**
     *
     * @var string
     */
    public $nombre;
    
    public function initialize()
    {
//     	$this->hasMany('id_periodo', 'CobActaconteo', 'id_periodo', array(
//     			'foreignKey' => array(
//     					'message' => 'El periodo no puede ser eliminado porque está siendo utilizado en algún Acta de Conteo'
//     			)
//     	));
//     	$this->belongsTo('id_periodo', 'CobActaconteo', 'id_periodo', array(
//     			'reusable' => true
//     	));
    }
    
    /**
     * Returns a human representation of 'tipo'
     *
     * @return string
     */
    public function getTipo()
    {   	 
    	if ($this->tipo == 1) {
    		return 'Revisión de Carpetas';
    	} else if($this->tipo == 2) {
    		return 'Equipo de Cómputo';
    	} else if($this->tipo == 3) {
    		return 'Telefónica';
    	}
    }
    
}
