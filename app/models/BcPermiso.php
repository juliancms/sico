<?php

class BcPermiso extends \Phalcon\Mvc\Model
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
    public $id_oferente;
    
    /**
     *
     * @var string
     */
    public $categoria;

    /**
     *
     * @var integer
     */
    public $id_sede_contrato;


    /**
     *
     * @var string
     */
    public $fecha;

    /**
     *
     * @var string
     */
    public $horaInicio;

    /**
     *
     * @var string
     */
    public $horaFin;

    /**
     *
     * @var string
     */
    public $titulo;
    
    /**
     *
     * @var string
     */
    public $observaciones;
    
    //Virtual Foreign Key para poder acceder a la fecha de corte del acta
    public function initialize()
    {
    	$this->belongsTo('id_permiso', 'BcPermisoGeneral', 'id_permiso', array(
    			'reusable' => true
    	));
    	$this->belongsTo('id_permiso', 'BcPermisoGeneralTransporte', 'id_permiso', array(
    			'reusable' => true
    	));
    	$this->belongsTo('id_sede_contrato', 'BcSedeContrato', 'id_sede_contrato', array(
    			'reusable' => true
    	));
    }
    
    /**
     * Convierte en texto la categoría de los permisos
     *
     * @return string
     */
    public function getCategoria()
    {
    	switch ($this->categoria) {
    		case 1:
    			return "Permiso Prioritario";
    			break;
    		case 2:
    			return "Salida Pedagógica";
    			break;
    		case 3:
    			return "Movilizaciones Sociales";
    			break;
    		case 4:
    			return "Salida a Ludotekas";
    			break;
    		case 5:
    			return "Jornada de Planeación";
    			break;
    	}
    }

}
