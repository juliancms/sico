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
	 *  7 = aaaa-mm-dd -> 02BC_Febrero
	 *  8 = aaaa-mm-dd -> Agosto de 2015
	 *  9 = aaaa-mm-dd -> MARZO_2015
	 * @return string
	 * @author Julián Camilo Marín Sánchez
	 */
	public function fecha($tipo_formato, $fecha) {
		if(!$fecha || $fecha == NULL || $fecha == "00/00/0000" || $fecha == "0000-00-00"){
			return "";
		}
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$siglas_meses = array("EN","FE","MR","AB","MY","JN","JL","AG","SE","OC","NO","DI");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		if($tipo_formato == 1) { 
			$parts = explode('/', $fecha);
			$nueva_fecha = "$parts[2]-$parts[1]-$parts[0]";
			return $nueva_fecha;
		}
		elseif($tipo_formato == 2){
			$parts = explode('-', $fecha);
			$nueva_fecha = "$parts[2]/$parts[1]/$parts[0]";
			return $nueva_fecha;
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
		elseif($tipo_formato == 7){
			$parts = explode('-', $fecha);
			return $parts[1] . "BC_" . $meses[$parts[1]-1];
		}
		elseif($tipo_formato == 8){
			$parts = explode('-', $fecha);
			return $meses[$parts[1]-1] . " de " . $parts[0];
		}
		elseif($tipo_formato == 9){
			$parts = explode('-', $fecha);
			return strtoupper($meses[$parts[1]-1]) . "_" . $parts[0];
		}
	}
	
	/**
	 * array_fechas
	 *
	 * @return string
	 * @author Julián Camilo Marín Sánchez
	 */
	public function array_fechas($tipo_formato, $fechas) {
		$nuevas_fechas = array();
		foreach($fechas as $row){
			$nuevas_fechas[] = $this->fecha($tipo_formato, $row);
		}
		return $nuevas_fechas;
	}
	
	
	/**
	 * multipleupdate
	 * @param $tabla String
	 * @param $elementos Array of Arrays
	 * @param $id_columna 
	 * 
	 * El array $elementos debe de tener el siguiente formato:
	 * $elementos = array ("id_columna" => array (id1, id2, id3...), "nombre_col2" => array(elemento1, elemento2, elemento3...));
	 * NOTA: Todas las columnas deben de tener la misma cantidad de elementos
	 * @return string
	 * Produce un string similar a este:
	 * UPDATE categories SET
		    	display_order = CASE id
		    	WHEN 1 THEN 32
		    	WHEN 2 THEN 33
		    	WHEN 3 THEN 34
		    	END,
		    	title = CASE id
		    	WHEN 1 THEN 'New Title 1'
		    	WHEN 2 THEN 'New Title 2'
		    	WHEN 3 THEN 'New Title 3'
		    	END
		    	WHERE id IN (1,2,3)
	 * @author Julián Camilo Marín Sánchez
	 */
	
	public function multipleupdate($tabla, $elementos, $id_columna){
		$sql = "";
		$sql .= "UPDATE $tabla SET ";
		for($i = 1; $i < count($elementos); $i++){
			$sql .= array_keys($elementos)[$i] . " = CASE " . $id_columna;
			$j = 0;
			foreach($elementos[array_keys($elementos)[$i]] as $row){
				$sql .= " WHEN " . $elementos[array_keys($elementos)[0]][$j] . " THEN '" . $row . "'";
				$j++;
			}
			$sql .= " END, ";
		}
		//Eliminamos la última coma
		$sql = substr($sql, 0, -2);
		$sql .= " WHERE " . $id_columna . " IN (" . implode(',', $elementos[array_keys($elementos)[0]]) . ")";
		return $sql;
	}
	
	/**
	 * multipleinsert
	 * @param $tabla String
	 * @param $elementos Array in Array
	 *
	 * @return string
	 * NOTA: El primer elemento debe de ser Array
	 * Produce un string similar a este:
	 * 	INSERT INTO example
		  (example_id, name, value, other_value)
		VALUES
		  (100, 'Name 1', 'Value 1', 'Other 1'),
		  (101, 'Name 2', 'Value 2', 'Other 2'),
		  (102, 'Name 3', 'Value 3', 'Other 3'),
		  (103, 'Name 4', 'Value 4', 'Other 4');
	 * @author Julián Camilo Marín Sánchez
	 */
	
	public function multipleinsert($tabla, $elementos){
		$sql = "REPLACE INTO $tabla (".implode(",", array_keys($elementos)).") VALUES ";
		$i = 0;
		foreach($elementos[array_keys($elementos)[0]] as $row){
			$array = array();
			foreach(array_keys($elementos) as $row){
				if(is_array($elementos[$row])){
					$array[] = "'".$elementos[$row][$i]."'";
				} else {
					$array[] = "'".$elementos[$row]."'";
				}
				
			}
			$sql .= "(".implode(",", $array)."),";
			$i++;
		}
		return substr($sql, 0, -1);
	}
	
	/**
	 * multipledelete
	 * @param $tabla String
	 * @param id_columna String
	 * @param $elementos Array
	 *
	 * El parámetro $elementoso debe de enviarse en string separado por comas
	 * @return string
	 * @author Julián Camilo Marín Sánchez
	 */
	
	public function multipledelete($tabla, $id_columna, $elementos){
		return "DELETE FROM $tabla WHERE $id_columna IN (" . $elementos . ")";
	}
	
	/**
	 * get_client_ip
	 *
	 * Retorna la direcicón IP del cliente
	 * @return string
	 * @author Julián Camilo Marín Sánchez
	 */
	
	public function get_client_ip() {
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
}
