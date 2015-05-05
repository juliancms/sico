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
    
    /**
     *
     * @var integer
     */
    public $asistenciaFinal;
    
    /**
     *
     * @var integer
     */
    public $certificacion;
    
    public function initialize()
    {
    	$this->hasMany("id_actaconteo_persona_facturacion", "CobActaconteoPersona", "id_actaconteo_persona_facturacion");
    	$this->belongsTo('id_periodo', 'CobPeriodo', 'id_periodo', array(
    			'reusable' => true
    	));
    	$this->belongsTo(array('id_sede_contrato', 'id_periodo'), 'CobActaconteo', array('id_sede_contrato', 'id_periodo'));
    	$this->belongsTo(array('id_sede_contrato', 'id_periodo'), 'CobPeriodoContratosedecupos', array('id_sede_contrato', 'id_periodo'));
    }
	
    /**
     * Returns a human representation of 'certificacion'
     *
     * @return string
     */
    public function getEstadoDetail()
    {
    	switch ($this->certificacion) {
    		case 0:
    			return "Pendiente de Certificación (0)";
    			break;
    		case 1:
    			return "Certificar atención (1)";
    			break;
    		case 2:
    			return "No certificar atención (2)";
    			break;
    		case 3:
    			return "Descontar atención (3)";
    			break;
    	}
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function countBeneficiarioscontrato($id_contrato, $id_periodo)
    {
    	return CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_contrato = $id_contrato");
    }
    
    /**
     * Contar grupos contrato
     *
     * @return string
     */
    public function countGruposcontrato($id_contrato, $id_periodo)
    {
    	return CobActaconteoPersonaFacturacion::count(array("id_periodo = $id_periodo AND id_contrato = $id_contrato", "group" => "id_grupo"));
    }
    
    /**
     * Contar grupos sede
     *
     * @return string
     */
    public function countGrupossede($id_sede_contrato, $id_periodo)
    {
    	return CobActaconteoPersonaFacturacion::count(array("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato", "group" => "id_grupo"));
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function countBeneficiariossede($id_sede, $id_periodo)
    {
    	return CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede = $id_sede");
    }
    
    /**
     * Contar beneficiarios certificados
     *
     * @return string
     */
    public function countBeneficiarioscertcontrato($id_contrato, $id_periodo)
    {
    	return CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_contrato = $id_contrato AND certificacion = 1");
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function getEdadesContrato($id_contrato, $id_periodo)
    {
    	$ninos = CobActaconteoPersonaFacturacion::find("id_periodo = $id_periodo AND id_contrato = $id_contrato AND certificacion = 1");
    	$menor2 = 0;
    	$mayorigual2menor4 = 0;
    	$mayorigual4menor6 = 0;
    	$mayorigual6 = 0;
    	foreach($ninos as $nino){
    		$edad_nacimiento = date_create($nino->fechaNacimiento);
    		$fecha_corte = date_create($nino->CobPeriodo->fecha);
    		$interval = date_diff($edad_nacimiento, $fecha_corte);
    		if($interval->format('%y') < 2){
    			$menor2++;
    		} else if ($interval->format('%y') >= 2 && $interval->format('%y') < 4){
    			$mayorigual2menor4++;
    		} else if ($interval->format('%y') >= 4 && $interval->format('%y') < 6){
    			$mayorigual4menor6++;
    		} else if ($interval->format('%y') >= 6){
    			$mayorigual6++;
    		}
    	}
    	return array("menor2" => $menor2, "mayorigual2menor4" => $mayorigual2menor4, "mayorigual4menor6" => $mayorigual4menor6, "mayorigual6" => $mayorigual6);
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function getEdadesSede($id_sede_contrato, $id_periodo)
    {
    	$ninos = CobActaconteoPersonaFacturacion::find("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND certificacion = 1");
    	$menor2 = 0;
    	$mayorigual2menor4 = 0;
    	$mayorigual4menor6 = 0;
    	$mayorigual6 = 0;
    	foreach($ninos as $nino){
    		$edad_nacimiento = date_create($nino->fechaNacimiento);
    		$fecha_corte = date_create($nino->CobPeriodo->fecha);
    		$interval = date_diff($edad_nacimiento, $fecha_corte);
    		if($interval->format('%y') < 2){
    			$menor2++;
    		} else if ($interval->format('%y') >= 2 && $interval->format('%y') < 4){
    			$mayorigual2menor4++;
    		} else if ($interval->format('%y') >= 4 && $interval->format('%y') < 6){
    			$mayorigual4menor6++;
    		} else if ($interval->format('%y') >= 6){
    			$mayorigual6++;
    		}
    	}
    	return array("menor2" => $menor2, "mayorigual2menor4" => $mayorigual2menor4, "mayorigual4menor6" => $mayorigual4menor6, "mayorigual6" => $mayorigual6);
    }
    
    public function getCertificarSede($id_sede_contrato, $id_periodo)
    {
    	$ninos = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND certificacion = 1");
    	return $ninos;
    }
    
    public function getCertificarContrato($id_contrato, $id_periodo)
    {
    	$ninos = CobActaconteoPersonaFacturacion::find("id_periodo = $id_periodo AND id_contrato = $id_contrato AND certificacion = 1");
    	return count($ninos);
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function getAsistenciaSede($id_sede_contrato, $id_periodo)
    {
    	$asiste1 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 1");
    	$asiste4 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 4");
    	$asiste5 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 5");
    	$asiste6 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 6");
    	$asiste7 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 7");
    	$asiste8 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 8");
    	$asiste10 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 10");
    	$asiste11 = CobActaconteoPersonaFacturacion::count("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND asistenciaFinal = 11");
    	return array("asiste1" => $asiste1, "asiste4" => $asiste4, "asiste5" => $asiste5, "asiste6" => $asiste6, "asiste7" => $asiste7, "asiste8" => $asiste8, "asiste10" => $asiste10, "asiste11" => $asiste11);
    }
}
