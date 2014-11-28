<?php

class CobActadocumentacionPersona extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_actadocumentacion_persona;

    /**
     *
     * @var integer
     */
    public $id_actadocumentacion;

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
     * @var integer
     */
    public $docnombreCoincide;

    /**
     *
     * @var integer
     */
    public $telCoincide;

    /**
     *
     * @var integer
     */
    public $certsgsCoincide;

    /**
     *
     * @var integer
     */
    public $certsisbenCoincide;

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

}
