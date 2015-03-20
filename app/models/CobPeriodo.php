<?php
use Phalcon\DI\FactoryDefault;
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
    
    /**
     * Returns a human representation of 'fecha'
     *
     * @return string
     */
    public function getFechaDetail()
    {
    	$conversiones = $this->getDI()->getConversiones();
    	return $conversiones->fecha(5, $this->fecha);
    }
    
    /**
     * Returns a human representation of 'fecha'
     *
     * @return string
     */
    public function getFechacierreDetail()
    {
    	$conversiones = $this->getDI()->getConversiones();
    	return $conversiones->fecha(3, $this->fechacierre);
    }
    
    /**
     * Returns a human representation of 'fecha'
     *
     * @return string
     */
    public function getTipoperiodoDetail()
    {
    	if($this->tipo == 1) {
    		return "Conteo";
    	} else if($this->tipo == 2) {
    		return "Muestreo";
    	}
    }
    
}
