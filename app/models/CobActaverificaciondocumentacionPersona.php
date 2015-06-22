<?php

class CobActaverificaciondocumentacionPersona extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_actaverificaciondocumentacion_persona;

    /**
     *
     * @var integer
     */
    public $id_actaverificaciondocumentacion;
    
    /**
     *
     * @var integer
     */
    public $id_verificacion;
    
    /**
     *
     * @var string
     */
    public $grupo;

    /**
     *
     * @var integer
     */
    public $id_persona;

    /**
     *
     * @var string
     */
    public $numDocumento;

    /**
     *
     * @var string
     */
    public $primerNombre;

    /**
     *
     * @var string
     */
    public $segundoNombre;

    /**
     *
     * @var string
     */
    public $primerApellido;

    /**
     *
     * @var string
     */
    public $segundoApellido;

    /**
     *
     * @var string
     */
    public $beneficiarioTelefono;

    /**
     *
     * @var integer
     */
    public $nombreCedulaSibc;

    /**
     *
     * @var integer
     */
    public $telefonoSibc;
    
    /**
     *
     * @var integer
     */
    public $certificadoSgs;
    
    /**
     *
     * @var integer
     */
    public $certificadoSisben;
    
    /**
     *
     * @var integer
     */
    public $matriculaFirmada;
    
    /**
     *
     * @var integer
     */
    public $fechaMatricula;
    
    //Virtual Foreign Key para poder acceder a la fecha de corte del acta
    public function initialize()
    {
    	$this->belongsTo('id_actaverificaciondocumentacion', 'CobActaverificaciondocumentacion', 'id_actaverificaciondocumentacion', array(
    			'reusable' => true
    	));
    }
    
    /**
     * Returns a human representation of 'estado'
     *
     * @return string
     */
    public function getAsistenciaDetail()
    {
    	switch ($this->asistencia) {
    		case 6:
    			return " class='danger'";
    			break;
    		case 4:
    			return " class='warning'";
    			break;
    		case 5:
    			return " class='warning'";
    			break;
    		default:
    			return "";
    			break;
    	}
    }

}
