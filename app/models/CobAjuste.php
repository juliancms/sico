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
    	return array("1" => "Certificar Atención del periodo por ajuste", "3" => "Descontar Atención del periodo por ajuste", "2" => "No certificar");
    }
    
    /**
     * Total Ajuste Sede
     *
     * @return string
     */
    public function totalAjustesede($id_ajuste_cierre, $id_sede_contrato)
    {
    	$pagos = CobAjuste::count("id_ajuste_cierre = $id_ajuste_cierre AND id_sede_contrato = $id_sede_contrato AND certificar = 1");
    	$descuentos = CobAjuste::count("id_ajuste_cierre = $id_ajuste_cierre AND id_sede_contrato = $id_sede_contrato AND certificar = 3");
    	$total = $pagos - $descuentos;
    	return array("pagos" => $pagos, "descuentos" => $descuentos, "total" => $total );
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function getEdadesSede($id_ajuste_cierre, $id_sede_contrato)
    {
    	$ninos = CobAjuste::find("id_ajuste_cierre = $id_ajuste_cierre AND id_sede_contrato = $id_sede_contrato AND certificar != 2");
    	$menor2 = 0;
    	$mayorigual2menor4 = 0;
    	$mayorigual4menor6 = 0;
    	$mayorigual6 = 0;
    	foreach($ninos as $nino){
    		if($nino->certificar == 1){
    			$adicion = 1;
    		} else {
    			$adicion = -1;
    		}
    		$edad_nacimiento = date_create($nino->CobActaconteoPersonaFacturacion->fechaNacimiento);
    		$fecha_corte = date_create($nino->CobPeriodo->fecha);
    		$interval = date_diff($edad_nacimiento, $fecha_corte);
    		if($interval->format('%y') < 2){
    			$menor2 = $menor2 + ($adicion);
    		} else if ($interval->format('%y') >= 2 && $interval->format('%y') < 4){
    			$mayorigual2menor4 = $mayorigual2menor4 + ($adicion);
    		} else if ($interval->format('%y') >= 4 && $interval->format('%y') < 6){
    			$mayorigual4menor6 = $mayorigual4menor6 + ($adicion);
    		} else if ($interval->format('%y') >= 6){
    			$mayorigual6 = $mayorigual6 + ($adicion);
    		}
    	}
    	return array("menor2" => $menor2, "mayorigual2menor4" => $mayorigual2menor4, "mayorigual4menor6" => $mayorigual4menor6, "mayorigual6" => $mayorigual6);
    }

}
