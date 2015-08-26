<?php

class CobActaconteoEmpleado extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_actaconteo;
    
    /**
     *
     * @var integer
     */
    public $id_contrato;
    
    /**
     *
     * @var integer
     */
    public $id_periodo;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var integer
     */
    public $numDocumento;

    /**
     *
     * @var string
     */
    public $cargo;

    /**
     *
     * @var integer
     */
    public $asistencia;

    /**
     *
     * @var integer
     */
    public $dotacion;
    
    /**
     *
     * @var string
     */
    public $fecha;

}
