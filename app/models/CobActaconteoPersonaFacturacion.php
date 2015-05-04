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
    	$ninos = CobActaconteoPersonaFacturacion::find("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato AND certificacion = 1");
    	return count($ninos);
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
    	$ninos = CobActaconteoPersonaFacturacion::find("id_periodo = $id_periodo AND id_sede_contrato = $id_sede_contrato");
    	$asiste1 = 0;
    	$asiste4 = 0;
    	$asiste5 = 0;
    	$asiste6 = 0;
    	$asiste7 = 0;
    	$asiste8 = 0;
    	$asiste10 = 0;
    	$asiste11 = 0;
    	foreach($ninos as $nino){
    		switch ($nino->asistenciaFinal) {
    			case "1":
    				$asiste1++;
    				break;
    			case "4":
    				$asiste4++;
    				break;
    			case "5":
    				$asiste5++;
    				break;
    			case "6":
    				$asiste6++;
    				break;
    			case "7":
    				$asiste7++;
    				break;
    			case "8":
    				$asiste8++;
    				break;
    			case "10":
    				$asiste10++;
    				break;
    			case "11":
    				$asiste11++;
    				break;
    			default:
    				break;
    		}
    	}
    	return array("asiste1" => $asiste1, "asiste4" => $asiste4, "asiste5" => $asiste5, "asiste6" => $asiste6, "asiste7" => $asiste7, "asiste8" => $asiste8, "asiste10" => $asiste10, "asiste11" => $asiste11);
    }
}
