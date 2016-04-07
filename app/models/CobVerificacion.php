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
    	} else if($this->tipo == 4) {
    		return 'Talento Humano';
    	}
    }

}
