<?php

class CobActaconteo extends \Phalcon\Mvc\Model
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
    public $id_periodo;

    /**
     *
     * @var integer
     */
    public $recorrido;

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
    public $id_modalidad;

    /**
     *
     * @var string
     */
    public $modalidad_nombre;

    /**
     *
     * @var integer
     */
    public $id_sede;

    /**
     *
     * @var string
     */
    public $sede_nombre;

    /**
     *
     * @var string
     */
    public $sede_barrio;

    /**
     *
     * @var string
     */
    public $sede_comuna;

    /**
     *
     * @var string
     */
    public $sede_direccion;

    /**
     *
     * @var string
     */
    public $sede_telefono;

    /**
     *
     * @var integer
     */
    public $id_oferente;

    /**
     *
     * @var string
     */
    public $oferente_nombre;

    /**
     *
     * @var integer
     */
    public $id_usuario;

    /**
     *
     * @var integer
     */
    public $estado;

}
