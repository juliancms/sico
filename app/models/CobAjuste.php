<?php

class CobAjuste extends \Phalcon\Mvc\Model
{
	/**
	 *
	 * @var integer
	 */
	public $id_ajuste;

    /**
     *
     * @var integer
     */
    public $id_actaconteo_persona_facturacion;

    /**
     *
     * @var integer
     */
    public $certificar;

    /**
     *
     * @var string
     */
    public $observacion;

    /**
     *
     * @var string
     */
    public $datetime;
    
    /**
     *
     * @var integer
     */
    public $id_usuario;
    
    //Virtual Foreign Key para poder acceder a la fecha de corte del acta
    public function initialize()
    {
    	$this->belongsTo('id_usuario', 'IbcUsuario', 'id_usuario', array(
    			'reusable' => true
    	));
    	$this->belongsTo('id_periodo', 'CobPeriodo', 'id_periodo', array(
    			'reusable' => true
    	));
    	$this->belongsTo('id_actaconteo_persona_facturacion', 'CobActaconteoPersonaFacturacion', 'id_actaconteo_persona_facturacion', array(
    			'reusable' => true
    	));
    }
    
    /**
     * Returns a human representation of 'certificar'
     *
     * @return string
     */
    public function getCertificarDetail()
    {
    	if ($this->certificar == 0) {
    		return 'Pendiente de Certificación';
    	} else if($this->certificar == 1) {
    		return 'Certificar Atención del periodo por ajuste';
    	}
    	return 'Descontar Atención del periodo por ajuste';
    }
    
    /**
     * Returns a select
     */
    public function CertificarSelect()
    {
    	return array("1" => "Certificar Atención del periodo por ajuste", "3" => "Descontar Atención del periodo por ajuste");
    }

}
