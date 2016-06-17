<?php
use Phalcon\DI\FactoryDefault;
class PersonalContratado extends \Phalcon\Mvc\Model
{
  /**
   *
   * @var integer
   */
  public $id;

  /**
   *
   * @var integer
   */
  public $id_contrato;

  /**
   *
   * @var integer
   */
  public $id_prestador;

  /**
   *
   * @var integer
   */
  public $id_modalidad;

  /**
   *
   * @var integer
   */
  public $cedula;

  /**
   *
   * @var string
   */
  public $primer_nombre;

  /**
   *
   * @var string
   */
  public $segundo_nombre;

  /**
   *
   * @var string
   */
  public $primer_apellido;

  /**
   *
   * @var string
   */
  public $segundo_apellido;

  /**
   *
   * @var string
   */
  public $formacion_academica;

  /**
   *
   * @var integer
   */
  public $id_cargo;

  /**
   *
   * @var integer
   */
  public $id_sede;

  /**
   *
   * @var string
   */
  public $fecha_ingreso;



  public function initialize()
  {
    $this->setConnectionService('db_delfi');
    $this->belongsTo('id_cargo', 'Cargo', 'id_cargo', array(
			'reusable' => true
		));
  }
}
