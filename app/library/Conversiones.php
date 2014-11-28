<?php

use Phalcon\Mvc\User\Component;

/**
 * Conversiones
 *
 * Conversión de caracteres de diferentes tipos
 */
class Conversiones extends Component
{

	/**
	 * fecha
	 * 
	 * Tipos de formato de fecha:
	 * 	1 = dd/mm/aaaa -> aaaa-mm-dd
	 *  2 = aaaa-mm-dd -> dd/mm/aaaa
	 *  3 = aaaa-mm-dd -> 16 de Mayo de 2014
	 *  4 = aaaa-mm-dd -> Sábado 17 de Mayo de 2014
	 *  5 = aaaa-mm-dd -> AGOSTO
	 *  6 = aaaa-mm-dd -> AG
	 * @return string
	 * @author Julián Camilo Marín Sánchez
	 */
	public function fecha($tipo_formato, $fecha) {
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$siglas_meses = array("EN","FE","MR","AB","MY","JN","JL","AG","SE","OC","NO","DI");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		if($tipo_formato == 1) { // Convertir de dd/mm/aaaa -> aaaa-mm-dd
			$parts = explode('/', $fecha);
			$nueva_fecha = "$parts[2]-$parts[1]-$parts[0]";
			return $nueva_fecha; //return aaaa-mm-dd (formato MySQL)
		}
		elseif($tipo_formato == 2){ // Convertir de aaaa-mm-dd -> dd/mm/aaaa
			$parts = explode('-', $fecha);
			$nueva_fecha = "$parts[2]/$parts[1]/$parts[0]";
			return $nueva_fecha; //return dd/mm/aaa
		}
		elseif($tipo_formato == 3){
			$parts = explode('-', $fecha);
			return $parts[2] . " de " . $meses[$parts[1]-1] . " de " . $parts[0];
		}
		elseif($tipo_formato == 4){
			$parts = explode('-', $fecha);
			return $dias[date('w', strtotime($fecha))] . " " . $parts[2] . " de " . $meses[$parts[1]-1] . " de " . $parts[0];
		}
		elseif($tipo_formato == 5){
			$parts = explode('-', $fecha);
			return strtoupper($meses[$parts[1]-1]);
		}
		elseif($tipo_formato == 6){
			$parts = explode('-', $fecha);
			return strtoupper($siglas_meses[$parts[1]-1]);
		}
	}
}
