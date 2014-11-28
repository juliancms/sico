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
    public $id_sede_contrato;

    /**
     *
     * @var integer
     */
    public $id_permiso_vinculado;

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

}
