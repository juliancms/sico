<?php

class CobActaconteoPersonaFacturacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_periodo;

    /**
     *
     * @var integer
     */
    public $id_sede_contrato;

    /**
     *
     * @var integer
     */
    public $id_contrato;

    /**
     *
     * @var integer
     */
    public $id_sede;

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
     * @var integer
     */
    public $id_grupo;

    /**
     *
     * @var string
     */
    public $grupo;

    /**
     *
     * @var string
     */
    public $fechaInicioAtencion;

    /**
     *
     * @var string
     */
    public $fechaRegistro;

    /**
     *
     * @var string
     */
    public $fechaRetiro;

    /**
     *
     * @var string
     */
    public $fechaNacimiento;

    /**
     *
     * @var string
     */
    public $peso;

    /**
     *
     * @var string
     */
    public $estatura;

    /**
     *
     * @var string
     */
    public $fechaControl;
    
    public function initialize()
    {
    	$this->hasMany("id_actaconteo_persona_facturacion", "CobActaconteoPersona", "id_actaconteo_persona_facturacion");
    }

}
