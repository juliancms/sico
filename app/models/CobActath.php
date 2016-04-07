<?php
use Phalcon\DI\FactoryDefault;

class CobActath extends \Phalcon\Mvc\Model
{

	 /**
     *
     * @var integer
     */
    public $id_actath;

    /**
     *
     * @var integer
     */
    public $id_verificacion;

    /**
     *
     * @var integer
     */
    public $id_mes;

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

    //Virtual Foreign Key para poder acceder a la fecha de corte del acta
    public function initialize()
    {
    	$this->belongsTo('id_verificacion', 'CobVerificacion', 'id_verificacion', array(
    			'reusable' => true
    	));
    	$this->belongsTo('id_actath', 'CobActathDatos', 'id_actath', array(
    			'reusable' => true
    	));
    	$this->belongsTo('id_usuario', 'IbcUsuario', 'id_usuario', array(
    			'reusable' => true
    	));
    	$this->hasMany('id_actath', 'CobActathPersona', 'id_actath', array(
    			'foreignKey' => array(
    					'message' => 'El acta no puede ser eliminada porque existen empleados asociados a ésta'
    			)
    	));
    }

    public function generarActa($id_actath){
    	$acta = CobActath::findFirstByid_actath($id_actath);
    	if(!$acta || $acta == NULL){
    		return FALSE;
    	}
    	$acta_id = "ATH-03-". date("Y") . sprintf('%05d', $acta->id_actath);
    	$encabezado = "<div class='seccion encabezado'>
    	<div class='fila center'><div>ACTA DE VERIFICACIÓN FÍSICA DE LA ATENCIÓN DEL 100% DEL TALENTO HUMANO REPORTADO EN EL SIST. INFORMACIÓN DELFI<br>INTERVENTORÍA BUEN COMIENZO</div></div>
    	<div class='fila col3 center'><div>Código: F-ITBC-GC-001</div><div></div><div></div></div>
    	<div class='fila col3e'>
    	<div>ACTA: <span style='font-weight: normal;'>$acta_id</span></div>
    	<div class='col2da'>NÚMERO DE CONTRATO: <span style='font-weight: normal;'>$acta->id_contrato</span></div>
    	<div>MODALIDAD: <span style='font-weight: normal;'>$acta->modalidad_nombre</span></div>
    	</div>
    	<div class='fila col3e'>
    	<div>RUTA: <span style='font-weight: normal;'>".$acta->IbcUsuario->usuario."</span></div>
    	<div class='col2da'>PRESTADOR: <span style='font-weight: normal;'>".substr($acta->oferente_nombre, 0, 34)."</span></div>
    	<div>SEDE: <span style='font-weight: normal;'>$acta->sede_nombre</span></div>
    	</div>
    	<div class='fila col3e'>
    	<div>TELÉFONO: <span style='font-weight: normal;'>$acta->sede_telefono</span></div>
    	<div class='col2da'>DIRECCIÓN: <span style='font-weight: normal;'>$acta->sede_direccion</span></div>
    	<div>BARRIO/VEREDA: <span style='font-weight: normal;'>$acta->sede_barrio</span></div>
    	</div>
    	<div class='clear'></div>
    	</div>";
    	$pie_pagina = "<div id='pie_pagina'>
    	<div class='pull-left' style='padding-left: 60px; width: 300px; text-align: center; float: left;'>________________________________________________<br>FIRMA PERSONA ENCARGADA DE LA SEDE</div>
	    	<div class='pull-right' style='padding-right: 60px; width: 300px; text-align: center; float: left;'>________________________________________________<br>FIRMA PERSONA ENCARGADA DE INTERVENTORÍA<br></div>
    		<div class='clear'></div>
    	</div>";
      $totalizacion_asistencia = "<div class='seccion' id='totalizacion_asistencia'>
    		<div class='fila center bold'><div style='border:none; width: 100%'>1. TOTALIZACIÓN DE ASISTENCIA</div></div>
	    	<div class='fila'><div>1.1 ASISTE</div></div>
	    	<div class='fila'><div>1.2 AUSENTE CON EXCUSA FÍSICA</div></div>
	    	<div class='fila'><div>1.3 AUSENTE CON EXCUSA TELEFÓNICA</div></div>
	    	<div class='fila'><div>1.4 RETIRADO ANTES DEL DÍA DE CORTE DE PERIODO</div></div>
	    	<div class='fila'><div>1.5 RETIRADO DESPUES DEL DÍA DE CORTE DE PERIODO</div></div>
	    	<div class='fila'><div>1.6 AUSENTE QUE NO PRESENTA EXCUSA EL DÍA DEL REPORTE</div></div>
	    	<div class='fila'><div>1.7 CON EXCUSA MÉDICA MAYOR O IGUAL A 15 DIAS</div></div>
	    	<div class='fila'><div>1.8 CON EXCUSA MÉDICA MENOR A 15 DIAS</div></div>
    		<div class='clear'></div>
    	</div>";
    	$datos_acta = array();
    	$datos_acta['datos'] = $acta;
    	$html = "";
    	$html .= "<div id='imprimir'>"; // <acta>
      //Página 1
      $html .= $encabezado;
      $html .= $totalizacion_asistencia;
    	$html .= "
        	<div class='seccion' id='datos_generales'>
        	<div class='fila center bold'><div style='border:none; width: 100%'>2. DATOS GENERALES</div></div>
	    	<div class='fila col3'>
    			<div>2.1 FECHA INTERVENTORÍA:</div>
    			<div>2.2 HORA INICIO INTERVENTORÍA:</div>
    			<div>2.3 HORA FIN INTERVENTORÍA:</div>
    		</div>
    		<div class='fila col2'>
    			<div>2.4 NOMBRE ENCARGADO DE LA SEDE:</div>
    			<div>2.5 NOMBRE INTERVENTOR:</div>
    		</div>
    		<div class='clear'></div>
    	</div>
    	<div class='seccion' id='observaciones'>
    		<div class='fila center bold'><div style='border:none; width: 100%'>3. OBSERVACIONES AL MOMENTO DE LA INTERVENTORÍA</div></div>
    		<div class='fila observacion2'><div>3.1 OBSERVACIÓN DEL INTERVENTOR:</div></div>
    		<div class='fila observacion2'><div>3.2 OBSERVACIÓN DEL ENCARGADO DE LA SEDE:</div></div>
    		<div class='clear'></div>
    	</div>";
    	$html .= $pie_pagina;
    	$p = 1;
    	$html .= "<div class='paginacion'>PÁGINA $p</div>";
        $i = 1;
        $j = 1;
      			$encabezado_talentohumano = "<div class='seccion'>
      			<div class='fila center bold'><div style='border:none; width: 100%'>4. LISTADO DE TALENTO HUMANO REPORTADO EN EL SIST. DE INFORMACIÓN DELFI</div></div>
      			<div class='fila colb2 encabezadodoc'><div style='width: 20px;'>#</div><div style='width: 60px;'>4.1 DOCUMENTO</div><div style='width: 60px'>4.2 NOMBRE COMPLETO</div><div style='width: 70px'>4.3 FORMACIÓN ACADÉMICA</div><div style='width: 46px'>4.4 CARGO</div><div style='width: 46px'>4.5 TIPO CONTR.</div><div style='width: 46px'>4.6 BASE SALARIO</div><div style='width: 46px'>4.7  PCT DEDIC.</div><div style='width: 46px;'>4.8  FECHA INGRESO</div><div style='width: 40px;'>4.9 FECHA RETIRO</div><div style='width: 30px;'>4.10 ASISTE</div><div style='width: 100px'>4.11 OBSERVACIONES</div><div style='width: 60px'>4.12 FIRMA</div></div>";
      			$html .= $encabezado;
      			$html .= $encabezado_talentohumano;
      			foreach($acta->getCobActathPersona(['order' => 'id_sede asc']) as $row){
      			$nombre_completo = array($row->primerNombre, $row->segundoNombre, $row->primerApellido, $row->segundoApellido);
      			$nombre_completo = implode(" ", $nombre_completo);
      			$i = ($i<10) ? "0" .$i : $i;
      			if($j == 7){
      			$j = 1;
      					$p++;
      					$html .= "<div class='clear'></div></div>VL: Vinculación laboral - Salario<br>PS: Prestación de servicios - Honorarios" . $pie_pagina;
      					$html .= "<div class='paginacion'>PÁGINA $p</div>";
  				      $html .= $encabezado;
      					$html .= $encabezado_talentohumano;
      			}
      					$html .="<div class='fila colb2'><div style='width: 20px;'>$i</div><div style='width: 60px;'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>$row->numDocumento</div><div style='width: 60px'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>$nombre_completo</div><div style='width: 70px;'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>$row->formacionAcademica</div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>$row->cargo</div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>$row->tipoContrato</div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important;'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>$ ".number_format ($row->baseSalario, 0, ',', '.')."</div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>".$row->porcentajeDedicacion * 100 ." %</div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p>".$this->conversiones->fecha(2, $row->fechaIngreso)."</div><div style='width: 40px'></div><div style='width: 30px'></div><div style='width: 100px'></div><div style='width: 60px'></div></div>";
      					$i++;
      					$j++;
      			}
      			$p++;
      			$html .= "<div class='clear'></div></div>VL: Vinculación laboral - Salario<br>PS: Prestación de servicios - Honorarios" . $pie_pagina;
            $html .= "<div class='paginacion'>PÁGINA $p</div>";
            $i = 1;
            $j = 1;
            $encabezado_talentohumano_adicional = "<div class='seccion'>
            <div class='fila center bold'><div style='border:none; width: 100%'>4. LISTADO DE TALENTO HUMANO ADICIONAL AL REPORTADO EN EL SIST. DE INFORMACIÓN DELFI</div></div>
            <div class='fila colb2 encabezadodoc'><div style='width: 20px;'>#</div><div style='width: 60px;'>4.1 DOCUMENTO</div><div style='width: 60px'>4.2 NOMBRE COMPLETO</div><div style='width: 70px'>4.3 FORMACIÓN ACADÉMICA</div><div style='width: 46px'>4.4 CARGO</div><div style='width: 46px'>4.5 TIPO CONTR.</div><div style='width: 46px'>4.6 BASE SALARIO</div><div style='width: 46px'>4.7  PCT DEDIC.</div><div style='width: 46px;'>4.8  FECHA INGRESO</div><div style='width: 40px;'>4.9 FECHA RETIRO</div><div style='width: 30px;'>4.10 ASISTE</div><div style='width: 100px'>4.11 OBSERVACIONES</div><div style='width: 60px'>4.12 FIRMA</div></div>";
            $html .= $encabezado;
            $html .= $encabezado_talentohumano_adicional;
            $i = ($i<10) ? "0" .$i : $i;
            for($i = 1; $i <= 6; $i++){
  					$html .="<div class='fila colb2'><div style='width: 20px;'>$i</div><div style='width: 60px;'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 60px'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 70px;'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important;'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 46px; padding-left: 0 !important; padding-right: 0 !important'><p><span class='label label-default'>SÍ</span><span class='label label-default'>NO</span><span class='label label-default'>N/A</span></p></div><div style='width: 40px'></div><div style='width: 30px'></div><div style='width: 100px'></div><div style='width: 60px'></div></div>";
            }
            $p++;
          	$html .= "<div class='clear'></div></div>" . $pie_pagina;
            $html .= "<div class='paginacion'>PÁGINA $p</div>";
          	$html .= "<div class='clear'></div>"; // </acta>
        	  $datos_acta['html'] = $html;
            return $datos_acta;
    }

    public function cargarBeneficiarios($carga, $modalidades, $id_verificacion)
    {
    	$db = $this->getDI()->getDb();
    	$config = $this->getDI()->getConfig();
    	$timestamp = new DateTime();
    	$tabla_mat = "m" . $timestamp->getTimestamp();
    	$archivo_mat = $config->application->basePath . "public/files/bc_bd/" . $carga->nombreMat;
    	$db->query("CREATE TEMPORARY TABLE $tabla_mat (fechaRetiro DATE, id_sede_contrato BIGINT, id_contrato BIGINT, id_modalidad INT, modalidad_nombre VARCHAR(50), id_sede INT, sede_nombre VARCHAR(80), sede_barrio VARCHAR(80), sede_direccion VARCHAR(80), sede_telefono VARCHAR(80), id_oferente INT, oferente_nombre VARCHAR(100), id_persona INT, numDocumento VARCHAR(100), primerNombre VARCHAR(20), segundoNombre VARCHAR(20), primerApellido VARCHAR(20), segundoApellido VARCHAR(20), telefonoBeneficiario VARCHAR(50), celularBeneficiario VARCHAR(50), grupo VARCHAR(80)) CHARACTER SET utf8 COLLATE utf8_bin");
    	$db->query("LOAD DATA INFILE '$archivo_mat' IGNORE INTO TABLE $tabla_mat FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ID_MATRICULA, @FECHA_INICIO_ATENCION, @FECHA_RETIRO, @MOTIVO_RETIRO, @FECHA_REGISTRO_MATRICULA, @ID_PRESTADOR, @PRESTADOR_SERVICIO, @NUMERO_CONTRATO, @ID_MODALIDAD_ORIGEN, @NOMBRE_MODALIDAD, @ID_SEDE, @NOMBRE_SEDE, @ID_BARRIO_SEDE, @NOMBRE_BARRIO_SEDE, @DIRECCION_SEDE, @TELEFONO_SEDE, @ID_SEDE_CONTRATO, @COORDINADOR_MODALIDAD, @ID_GRUPO, @NOMBRE_GRUPO, @AGENTE_EDUCATIVO, @ID_PERSONA, @TIPO_DOCUMENTO, @NUMERO_DOCUMENTO, @PRIMER_NOMBRE, @SEGUNDO_NOMBRE, @PRIMER_APELLIDO, @SEGUNDO_APELLIDO, @FECHA_NACIMIENTO, @GENERO, @ZONA_BENEFICIARIO, @DIRECCION_BENEFICIARIO, @ID_BARRIO_BENEFICIARIO, @NOMBRE_BARRIO_BENEFICIARIO, @TELEFONO_BENEFICIARIO, @CELULAR_BENEFICIARIO, @PUNTAJE_SISBEN, @NUMERO_FICHA, @VICTIMA_CA, @ESQUEMA_VACUNACION, @TIPO_DISCAPACIDAD, @CAPACIDAD_EXCEPCIONAL, @AFILIACION_SGSSS, @ENTIDAD_SALUD, @ASISTE_CXD, @NOMBRE_ETNIA, @OTROS_BENEFICIOS, @RADICADO, @AUTORIZADO, @FECHA_RADICADO, @CICLO_VITAL_MADRE, @EDAD_GESTACIONAL, @PESO, @ESTATURA, @FECHA_CONTROL, @OBSERVACION, @FECHA_DIGITACION_SEG, @FECHA_MODIFICACION_SEG, @USUARIO_REGISTRO_SEG, @TIPO_BENEFICIARIO, @FECHA_REGISTRO_BENEFICIARIO, @ID_CIERRE_GRUPO, @EN_EDUCATIVO, @FECHA_CIERRE_GRUPO, @CODIGO_HCB, @NOMBRE_HCB, @DOCUMENTO_MCB, @PRIMER_NOMBRE_MCB, @SEGUNDO_NOMBRE_MCB, @PRIMER_APELLIDO_MCB, @SEGUNDO_APELLIDO_MCB, @DIRECCION_HCB, @BARRIO_VEREDA_HCB, @COMUNA_CORREGIMIENTO_HCB, @ZONA_HCB, @CENTRO_ZONAL_HCB, @NOMBRE_ASOCIACION, @CUARTOUPA_JI) SET fechaRetiro = @FECHA_RETIRO, id_sede_contrato = @ID_SEDE_CONTRATO, id_contrato = @NUMERO_CONTRATO, id_modalidad = @ID_MODALIDAD_ORIGEN, modalidad_nombre = @NOMBRE_MODALIDAD, id_sede = @ID_SEDE, sede_nombre = REPLACE(@NOMBRE_SEDE, '\"',\"\"), sede_barrio = @NOMBRE_BARRIO_SEDE, sede_direccion = @DIRECCION_SEDE, sede_telefono = @TELEFONO_SEDE, id_oferente = @ID_PRESTADOR, oferente_nombre = REPLACE(@PRESTADOR_SERVICIO, '\"',\"\"), id_persona = @ID_PERSONA, numDocumento = @NUMERO_DOCUMENTO, primerNombre = TRIM(REPLACE(@PRIMER_NOMBRE, '\"',\"\")), segundoNombre = TRIM(REPLACE(@SEGUNDO_NOMBRE, '\"',\"\")), primerApellido = TRIM(REPLACE(@PRIMER_APELLIDO, '\"',\"\")), segundoApellido = TRIM(REPLACE(@SEGUNDO_APELLIDO, '\"',\"\")), telefonoBeneficiario = @TELEFONO_BENEFICIARIO, celularBeneficiario = @CELULAR_BENEFICIARIO, grupo = REPLACE(@NOMBRE_GRUPO, '\"',\"\")");
    	$db->query("DELETE FROM $tabla_mat WHERE id_modalidad NOT IN ($modalidades)");
    	if (in_array(8, $modalidades)) {
    		//Actualizar si cambia contrato de Mundo Mejor
    		$id_contrato_mundomejor = 4600058508;
    		$id_oferente_mundomejor = 9;
    		$oferente_nombre_mundomejor = "MUNDO MEJOR - FUNDACION";
    		$id_modalidad_mundomejor = 8;
    		$modalidad_nombre_mundomejor = "PRESUPUESTO PARTICIPATIVO";
    		$tabla_pp = "pp" . $timestamp->getTimestamp();
    		$db->query("CREATE TEMPORARY TABLE $tabla_pp (certificacionRecorridos INT, fechaInicioAtencion DATE, fechaRetiro DATE, fechaRegistro DATE, id_sede_contrato BIGINT, id_contrato BIGINT, id_modalidad INT, modalidad_nombre VARCHAR(50), id_sede INT, sede_nombre VARCHAR(80), sede_barrio VARCHAR(80), sede_direccion VARCHAR(80), sede_telefono VARCHAR(80), id_oferente INT, oferente_nombre VARCHAR(100), id_persona INT, numDocumento VARCHAR(100), primerNombre VARCHAR(20), segundoNombre VARCHAR(20), primerApellido VARCHAR(20), segundoApellido VARCHAR(20), id_grupo BIGINT, grupo VARCHAR(80), fechaNacimiento DATE, peso VARCHAR(10), estatura VARCHAR(10), fechaControl DATE, otrosBeneficios VARCHAR(50), telefonoBeneficiario VARCHAR(50), celularBeneficiario VARCHAR(50)) CHARACTER SET utf8 COLLATE utf8_bin");
    		$db->query("LOAD DATA INFILE '$archivo_mat' IGNORE INTO TABLE $tabla_pp FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ID_MATRICULA, @FECHA_INICIO_ATENCION, @FECHA_RETIRO, @MOTIVO_RETIRO, @FECHA_REGISTRO_MATRICULA, @ID_PRESTADOR, @PRESTADOR_SERVICIO, @NUMERO_CONTRATO, @ID_MODALIDAD_ORIGEN, @NOMBRE_MODALIDAD, @ID_SEDE, @NOMBRE_SEDE, @ID_BARRIO_SEDE, @NOMBRE_BARRIO_SEDE, @DIRECCION_SEDE, @TELEFONO_SEDE, @ID_SEDE_CONTRATO, @COORDINADOR_MODALIDAD, @ID_GRUPO, @NOMBRE_GRUPO, @AGENTE_EDUCATIVO, @ID_PERSONA, @TIPO_DOCUMENTO, @NUMERO_DOCUMENTO, @PRIMER_NOMBRE, @SEGUNDO_NOMBRE, @PRIMER_APELLIDO, @SEGUNDO_APELLIDO, @FECHA_NACIMIENTO, @GENERO, @ZONA_BENEFICIARIO, @DIRECCION_BENEFICIARIO, @ID_BARRIO_BENEFICIARIO, @NOMBRE_BARRIO_BENEFICIARIO, @TELEFONO_BENEFICIARIO, @CELULAR_BENEFICIARIO, @PUNTAJE_SISBEN, @NUMERO_FICHA, @VICTIMA_CA, @ESQUEMA_VACUNACION, @TIPO_DISCAPACIDAD, @CAPACIDAD_EXCEPCIONAL, @AFILIACION_SGSSS, @ENTIDAD_SALUD, @ASISTE_CXD, @NOMBRE_ETNIA, @OTROS_BENEFICIOS, @RADICADO, @AUTORIZADO, @FECHA_RADICADO, @CICLO_VITAL_MADRE, @EDAD_GESTACIONAL, @PESO, @ESTATURA, @FECHA_CONTROL, @OBSERVACION, @FECHA_DIGITACION_SEG, @FECHA_MODIFICACION_SEG, @USUARIO_REGISTRO_SEG, @TIPO_BENEFICIARIO, @FECHA_REGISTRO_BENEFICIARIO, @ID_CIERRE_GRUPO, @EN_EDUCATIVO, @FECHA_CIERRE_GRUPO, @CODIGO_HCB, @NOMBRE_HCB, @DOCUMENTO_MCB, @PRIMER_NOMBRE_MCB, @SEGUNDO_NOMBRE_MCB, @PRIMER_APELLIDO_MCB, @SEGUNDO_APELLIDO_MCB, @DIRECCION_HCB, @BARRIO_VEREDA_HCB, @COMUNA_CORREGIMIENTO_HCB, @ZONA_HCB, @CENTRO_ZONAL_HCB, @NOMBRE_ASOCIACION, @CUARTOUPA_JI) SET id_sede_contrato = @ID_SEDE_CONTRATO, id_contrato = @NUMERO_CONTRATO, id_modalidad = @ID_MODALIDAD_ORIGEN, modalidad_nombre = @NOMBRE_MODALIDAD, id_sede = @ID_SEDE, sede_nombre = REPLACE(@NOMBRE_SEDE, '\"',\"\"), sede_barrio = @NOMBRE_BARRIO_SEDE, sede_direccion = @DIRECCION_SEDE, sede_telefono = @TELEFONO_SEDE, id_oferente = @ID_PRESTADOR, oferente_nombre = REPLACE(@PRESTADOR_SERVICIO, '\"',\"\"), id_persona = @ID_PERSONA, numDocumento = @NUMERO_DOCUMENTO, primerNombre = TRIM(REPLACE(@PRIMER_NOMBRE, '\"',\"\")), segundoNombre = TRIM(REPLACE(@SEGUNDO_NOMBRE, '\"',\"\")), primerApellido = TRIM(REPLACE(@PRIMER_APELLIDO, '\"',\"\")), segundoApellido = TRIM(REPLACE(@SEGUNDO_APELLIDO, '\"',\"\")), telefonoBeneficiario = @TELEFONO_BENEFICIARIO, celularBeneficiario = @CELULAR_BENEFICIARIO, id_grupo = @ID_GRUPO, grupo = REPLACE(@NOMBRE_GRUPO, '\"',\"\"), fechaInicioAtencion = @FECHA_INICIO_ATENCION, fechaRegistro = @FECHA_REGISTRO_MATRICULA, fechaRetiro = @FECHA_RETIRO, fechaNacimiento = @FECHA_NACIMIENTO, peso = @PESO, estatura = @ESTATURA, fechaControl = @FECHA_CONTROL, otrosBeneficios = @OTROS_BENEFICIOS");
    		$db->query("DELETE FROM $tabla_pp WHERE otrosBeneficios NOT LIKE 'PP%'");
    		$db->query("DELETE FROM $tabla_pp WHERE otrosBeneficios LIKE '%R%'");
    		$db->query("DELETE FROM $tabla_pp WHERE otrosBeneficios NOT LIKE '%ID%'");
    		$db->query("UPDATE $tabla_pp SET id_sede = SUBSTRING_INDEX(otrosBeneficios,'ID',-1) WHERE 1");
    		$db->query("UPDATE $tabla_pp, bc_sede_contrato SET $tabla_pp.id_sede_contrato = bc_sede_contrato.id_sede_contrato, $tabla_pp.sede_nombre = bc_sede_contrato.sede_nombre, $tabla_pp.sede_barrio = bc_sede_contrato.sede_barrio, $tabla_pp.sede_direccion = bc_sede_contrato.sede_direccion, $tabla_pp.sede_telefono = bc_sede_contrato.sede_telefono WHERE $tabla_pp.id_sede = bc_sede_contrato.id_sede AND bc_sede_contrato.id_contrato = $id_contrato_mundomejor");
    		$db->query("INSERT IGNORE INTO $tabla_mat (id_contrato, id_sede_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, telefonoBeneficiario, celularBeneficiario) SELECT $id_contrato_mundomejor, id_sede_contrato, $id_modalidad_mundomejor, '$modalidad_nombre_mundomejor', id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, $id_oferente_mundomejor, '$oferente_nombre_mundomejor', id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, telefonoBeneficiario, celularBeneficiario FROM $tabla_pp");
    	}
    	$db->query("DELETE FROM $tabla_mat WHERE fechaRetiro > 0000-00-00");
    	$db->query("INSERT IGNORE INTO cob_actaverificaciondocumentacion (id_verificacion, id_carga, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre) SELECT $id_verificacion, $carga->id_carga, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre FROM $tabla_mat");
    	$db->query("INSERT IGNORE INTO cob_actaverificaciondocumentacion_persona (id_actaverificaciondocumentacion, id_verificacion, grupo, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, beneficiarioTelefono, beneficiarioCelular) SELECT (SELECT id_actaverificaciondocumentacion FROM cob_actaverificaciondocumentacion WHERE cob_actaverificaciondocumentacion.id_sede_contrato = $tabla_mat.id_sede_contrato AND cob_actaverificaciondocumentacion.id_verificacion = $id_verificacion), $id_verificacion, grupo, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, telefonoBeneficiario, celularBeneficiario FROM $tabla_mat");
    	$db->query("DROP TABLE $tabla_mat");
    }

    /**
     * Returns a human representation of 'estado'
     *
     * @return string
     */
    public function getEstadoDetail()
    {
    	switch ($this->estado) {
    		case 0:
    			return "Inactiva";
    			break;
    		case 1:
    			return "Activa";
    			break;
    		case 2:
    			return "Cerrada por Interventor";
    			break;
    		case 3:
    			return "Cerrada por Auxiliar";
    			break;
    		case 4:
    			return "Consolidada";
    			break;
    		case 5:
    			return "Verificación cerrada";
    			break;
    	}
    }

    /**
     * Returns a human representation of 'url'
     *
     * @return string
     */
    public function getUrlDetail()
    {
    	return "cob_actath/ver/$this->id_actath";
    }

    /**
     * Returns a human representation of 'id_actath'
     *
     * @return string
     */
    public function getIdDetail()
    {
    	return "ATH-03-". date("Y") . sprintf('%05d', $this->id_actath);
    }

    /**
     * Returns a human representation of 'id_actath'
     *
     * @return string
     */
    public function getId()
    {
    	return $this->id_actath;
    }

    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function countBeneficiarios()
    {
    	return count($this->CobActathPersona);
    }

    /**
     * Returns a human representation of 'estado'
     *
     * @return string
     */
    public function getsinonareDetail($value)
    {
    	switch ($value) {
    		case 2:
    			return " class='warning'";
    			break;
    		case 3:
    			return " class='warning'";
    			break;
    		case 4:
    			return " class='warning'";
    			break;
    		default:
    			return "";
    			break;
    	}
    }

}
