<?php
use Phalcon\DI\FactoryDefault;

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
    public $id_carga;

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
    
    
    //Virtual Foreign Key para poder acceder a la fecha de corte del acta
	public function initialize()
	{
		$this->belongsTo('id_periodo', 'CobPeriodo', 'id_periodo', array(
			'reusable' => true
		));
		$this->belongsTo('id_actaconteo', 'CobActaconteoDatos', 'id_actaconteo', array(
				'reusable' => true
		));
		$this->belongsTo('id_usuario', 'IbcUsuario', 'id_usuario', array(
				'reusable' => true
		));
		$this->belongsTo('estado', 'IbcReferencia', 'id_referencia', array(
				'reusable' => true
		));
		$this->hasMany("id_sede_contrato", "CobActaconteoPersonaFacturacion", "id_sede_contrato");
		$this->hasMany('id_actaconteo', 'CobActaconteoPersona', 'id_actaconteo', array(
				'foreignKey' => array(
						'message' => 'El acta no puede ser eliminada porque existen beneficiarios asociados a ésta'
				)
		));
	}
    
    public function generarActasRcarga($cob_periodo, $carga, $facturacion, $recorrido_anterior) {
    	$recorrido = $recorrido_anterior + 1;
    	$db = $this->getDI()->getDb();
    	$config = $this->getDI()->getConfig();
    	$timestamp = new DateTime();
        $tabla_mat = "m" . $timestamp->getTimestamp();
        $archivo_mat = $config->application->basePath . "public/files/bc_bd/" . $carga->nombreMat;
        $db->query("CREATE TEMPORARY TABLE $tabla_mat (certificacion INT, fechaInicioAtencion DATE, fechaRetiro DATE, fechaRegistro DATE, id_sede_contrato BIGINT, id_contrato BIGINT, id_modalidad INT, modalidad_nombre VARCHAR(50), id_sede INT, sede_nombre VARCHAR(80), sede_barrio VARCHAR(80), sede_direccion VARCHAR(80), sede_telefono VARCHAR(80), id_oferente INT, oferente_nombre VARCHAR(100), id_persona INT, numDocumento VARCHAR(100), primerNombre VARCHAR(20), segundoNombre VARCHAR(20), primerApellido VARCHAR(20), segundoApellido VARCHAR(20), id_grupo BIGINT, grupo VARCHAR(80), fechaNacimiento DATE, peso VARCHAR(10), estatura VARCHAR(10), fechaControl DATE) CHARACTER SET utf8 COLLATE utf8_bin");
        $db->query("LOAD DATA INFILE '$archivo_mat' IGNORE INTO TABLE $tabla_mat FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ID_MATRICULA, @FECHA_INICIO_ATENCION, @FECHA_RETIRO, @MOTIVO_RETIRO, @FECHA_REGISTRO_MATRICULA, @ID_PRESTADOR, @PRESTADOR_SERVICIO, @NUMERO_CONTRATO, @ID_MODALIDAD_ORIGEN, @NOMBRE_MODALIDAD, @ID_SEDE, @NOMBRE_SEDE, @ID_BARRIO_SEDE, @NOMBRE_BARRIO_SEDE, @DIRECCION_SEDE, @TELEFONO_SEDE, @ID_SEDE_CONTRATO, @COORDINADOR_MODALIDAD, @ID_GRUPO, @NOMBRE_GRUPO, @AGENTE_EDUCATIVO, @ID_PERSONA, @TIPO_DOCUMENTO, @NUMERO_DOCUMENTO, @PRIMER_NOMBRE, @SEGUNDO_NOMBRE, @PRIMER_APELLIDO, @SEGUNDO_APELLIDO, @FECHA_NACIMIENTO, @GENERO, @ZONA_BENEFICIARIO, @DIRECCION_BENEFICIARIO, @ID_BARRIO_BENEFICIARIO, @NOMBRE_BARRIO_BENEFICIARIO, @TELEFONO_BENEFICIARIO, @CELULAR_BENEFICIARIO, @PUNTAJE_SISBEN, @NUMERO_FICHA, @VICTIMA_CA, @ESQUEMA_VACUNACION, @TIPO_DISCAPACIDAD, @CAPACIDAD_EXCEPCIONAL, @AFILIACION_SGSSS, @ENTIDAD_SALUD, @ASISTE_CXD, @NOMBRE_ETNIA, @OTROS_BENEFICIOS, @RADICADO, @AUTORIZADO, @FECHA_RADICADO, @CICLO_VITAL_MADRE, @EDAD_GESTACIONAL, @PESO, @ESTATURA, @FECHA_CONTROL, @OBSERVACION, @FECHA_DIGITACION_SEG, @FECHA_MODIFICACION_SEG, @USUARIO_REGISTRO_SEG, @TIPO_BENEFICIARIO, @FECHA_REGISTRO_BENEFICIARIO) SET id_sede_contrato = @ID_SEDE_CONTRATO, id_contrato = @NUMERO_CONTRATO, id_modalidad = @ID_MODALIDAD_ORIGEN, modalidad_nombre = @NOMBRE_MODALIDAD, id_sede = @ID_SEDE, sede_nombre = REPLACE(@NOMBRE_SEDE, '\"',\"\"), sede_barrio = @NOMBRE_BARRIO_SEDE, sede_direccion = @DIRECCION_SEDE, sede_telefono = @TELEFONO_SEDE, id_oferente = @ID_PRESTADOR, oferente_nombre = REPLACE(@PRESTADOR_SERVICIO, '\"',\"\"), id_persona = @ID_PERSONA, numDocumento = @NUMERO_DOCUMENTO, primerNombre = TRIM(REPLACE(@PRIMER_NOMBRE, '\"',\"\")), segundoNombre = TRIM(REPLACE(@SEGUNDO_NOMBRE, '\"',\"\")), primerApellido = TRIM(REPLACE(@PRIMER_APELLIDO, '\"',\"\")), segundoApellido = TRIM(REPLACE(@SEGUNDO_APELLIDO, '\"',\"\")), id_grupo = @ID_GRUPO, grupo = REPLACE(@NOMBRE_GRUPO, '\"',\"\"), fechaInicioAtencion = @FECHA_INICIO_ATENCION, fechaRegistro = @FECHA_REGISTRO_MATRICULA, fechaRetiro = @FECHA_RETIRO, fechaNacimiento = @FECHA_NACIMIENTO, peso = @PESO, estatura = @ESTATURA, fechaControl = @FECHA_CONTROL");
        $modalidades = "";
        foreach(CobActaconteo::find(array("id_periodo = $cob_periodo->id_periodo AND recorrido = $recorrido_anterior", "columns" => "id_modalidad", "group" => "id_modalidad"))->toArray() as $row){
        	$modalidades = $modalidades . $row['id_modalidad'] . ","; 
        }
        $modalidades = substr($modalidades, 0, -1);        
        $db->query("DELETE FROM $tabla_mat WHERE id_modalidad NOT IN ($modalidades)");
        if($facturacion == 1){
        	$db->query("DELETE FROM $tabla_mat WHERE fechaRetiro > 0000-00-00 AND DATE_SUB('$cob_periodo->fecha',INTERVAL 30 DAY) > fechaRetiro OR '$cob_periodo->fecha' < fechaRetiro");
        	$db->query("UPDATE $tabla_mat SET certificacion = 2 WHERE fechaRetiro > 0000-00-00");
        	$archivo_sed = $config->application->basePath . "public/files/bc_bd/" . $carga->nombreSedes;
        	$db->query("LOAD DATA INFILE '$archivo_sed' IGNORE INTO TABLE cob_periodo_contratosedecupos FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ID_SEDE_CONTRATO, @ID_OFERENTE, @RAZON_SOCIAL, @ID_SEDE, @NOMBRE_SEDE, @TELEFONO, @DIRECCION, @ID_BARRIO_VEREDA, @NOMBRE_BARRIO_VEREDA, @ID_COMUNA, @NOMBRE_COMUNA_CORREGIMIENTO, @ZONA_UBICACION, @FECHA_INICIO_ATENCION, @NUMERO_CONTRATO, @ID_MODALIDAD_ORIGEN, @NOMBRE_MODALIDAD, @CUPOS_SEDE, @CUPOS_EN_USO, @CUPOS_SOSTENIBILIDAD, @CUPOS_AMPLIACION, @TOTAL_CUPOS_CONTRATO, @COORDX, @COORDY, @COORDINADOR, @PROPIEDAD_INMUEBLE, @AREA, @CAPACIDAD_ATENCION, @GRUPOS_ACTIVOS, @FECHA_FIN_CONTRATO, @FECHA_ADICION_CONTRATO) SET id_periodo = $cob_periodo->id_periodo, id_sede_contrato = @ID_SEDE_CONTRATO, cuposSede = @CUPOS_SEDE, cuposSostenibilidad = @CUPOS_SOSTENIBILIDAD, cuposAmpliacion = @CUPOS_AMPLIACION, cuposTotal = @TOTAL_CUPOS_CONTRATO");
        	$db->query("INSERT IGNORE INTO cob_actaconteo_persona_facturacion (id_periodo, id_sede_contrato, id_contrato, id_sede, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo, fechaInicioAtencion, fechaRegistro, fechaRetiro, fechaNacimiento, peso, estatura, fechaControl, certificacion) SELECT $cob_periodo->id_periodo, id_sede_contrato, id_contrato, id_sede, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo, fechaInicioAtencion, fechaRegistro, fechaRetiro, fechaNacimiento, peso, estatura, fechaControl, certificacion FROM $tabla_mat");
        	$db->query("UPDATE cob_periodo SET id_carga_facturacion = $carga->id_carga WHERE id_periodo = $cob_periodo->id_periodo");
        }
        $db->query("DELETE FROM $tabla_mat WHERE fechaRetiro > 0000-00-00");
        $eliminar = CobActaconteoPersona::find(["id_periodo = $cob_periodo->id_periodo AND recorrido < $recorrido AND (asistencia = 1 OR asistencia = 7 OR asistencia = 9 OR asistencia = 10)"]);
        if(count($eliminar) > 0){
        	$sql = "DELETE FROM $tabla_mat WHERE CONCAT_WS('-',id_contrato,numDocumento) IN (";
        	foreach($eliminar as $row){
        		$sql .= "'$row->id_contrato-$row->numDocumento',";
        	}
        	$sql = substr($sql, 0, -1);
        	$sql .= ")";
        	$db->query($sql);
        }
        $db->query("INSERT IGNORE INTO bc_contrato (id_contrato, id_oferente, id_modalidad) SELECT id_contrato, id_oferente, id_modalidad FROM $tabla_mat");
        $db->query("INSERT IGNORE INTO cob_actaconteo (id_periodo, id_carga, recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre) SELECT $cob_periodo->id_periodo, $carga->id_carga, $recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre FROM $tabla_mat");
        $db->query("REPLACE INTO bc_sede_contrato (id_sede_contrato, id_oferente, oferente_nombre, id_contrato, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_modalidad, modalidad_nombre, estado) SELECT id_sede_contrato, id_oferente, oferente_nombre, id_contrato, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_modalidad, modalidad_nombre, '1' FROM $tabla_mat");
        $db->query("INSERT IGNORE INTO cob_actaconteo_persona (id_actaconteo, id_periodo, recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo) SELECT (SELECT id_actaconteo FROM cob_actaconteo WHERE cob_actaconteo.id_sede_contrato = $tabla_mat.id_sede_contrato AND cob_actaconteo.id_periodo = $cob_periodo->id_periodo AND cob_actaconteo.recorrido = $recorrido), $cob_periodo->id_periodo, $recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo FROM $tabla_mat");
        $db->query("DROP TABLE $tabla_mat");
    	return TRUE;
    }
        
    public function generarActasR1($cob_periodo, $carga, $modalidades, $facturacion) {
    	$db = $this->getDI()->getDb();
    	$config = $this->getDI()->getConfig();
    	$timestamp = new DateTime();
    	$tabla_mat = "m" . $timestamp->getTimestamp();
    	$archivo_mat = $config->application->basePath . "public/files/bc_bd/" . $carga->nombreMat;
    	$db->query("CREATE TEMPORARY TABLE $tabla_mat (certificacion INT, fechaInicioAtencion DATE, fechaRetiro DATE, fechaRegistro DATE, id_sede_contrato BIGINT, id_contrato BIGINT, id_modalidad INT, modalidad_nombre VARCHAR(50), id_sede INT, sede_nombre VARCHAR(80), sede_barrio VARCHAR(80), sede_direccion VARCHAR(80), sede_telefono VARCHAR(80), id_oferente INT, oferente_nombre VARCHAR(100), id_persona INT, numDocumento VARCHAR(100), primerNombre VARCHAR(20), segundoNombre VARCHAR(20), primerApellido VARCHAR(20), segundoApellido VARCHAR(20), id_grupo BIGINT, grupo VARCHAR(80), fechaNacimiento DATE, peso VARCHAR(10), estatura VARCHAR(10), fechaControl DATE) CHARACTER SET utf8 COLLATE utf8_bin");
    	$db->query("LOAD DATA INFILE '$archivo_mat' IGNORE INTO TABLE $tabla_mat FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ID_MATRICULA, @FECHA_INICIO_ATENCION, @FECHA_RETIRO, @MOTIVO_RETIRO, @FECHA_REGISTRO_MATRICULA, @ID_PRESTADOR, @PRESTADOR_SERVICIO, @NUMERO_CONTRATO, @ID_MODALIDAD_ORIGEN, @NOMBRE_MODALIDAD, @ID_SEDE, @NOMBRE_SEDE, @ID_BARRIO_SEDE, @NOMBRE_BARRIO_SEDE, @DIRECCION_SEDE, @TELEFONO_SEDE, @ID_SEDE_CONTRATO, @COORDINADOR_MODALIDAD, @ID_GRUPO, @NOMBRE_GRUPO, @AGENTE_EDUCATIVO, @ID_PERSONA, @TIPO_DOCUMENTO, @NUMERO_DOCUMENTO, @PRIMER_NOMBRE, @SEGUNDO_NOMBRE, @PRIMER_APELLIDO, @SEGUNDO_APELLIDO, @FECHA_NACIMIENTO, @GENERO, @ZONA_BENEFICIARIO, @DIRECCION_BENEFICIARIO, @ID_BARRIO_BENEFICIARIO, @NOMBRE_BARRIO_BENEFICIARIO, @TELEFONO_BENEFICIARIO, @CELULAR_BENEFICIARIO, @PUNTAJE_SISBEN, @NUMERO_FICHA, @VICTIMA_CA, @ESQUEMA_VACUNACION, @TIPO_DISCAPACIDAD, @CAPACIDAD_EXCEPCIONAL, @AFILIACION_SGSSS, @ENTIDAD_SALUD, @ASISTE_CXD, @NOMBRE_ETNIA, @OTROS_BENEFICIOS, @RADICADO, @AUTORIZADO, @FECHA_RADICADO, @CICLO_VITAL_MADRE, @EDAD_GESTACIONAL, @PESO, @ESTATURA, @FECHA_CONTROL, @OBSERVACION, @FECHA_DIGITACION_SEG, @FECHA_MODIFICACION_SEG, @USUARIO_REGISTRO_SEG, @TIPO_BENEFICIARIO, @FECHA_REGISTRO_BENEFICIARIO) SET id_sede_contrato = @ID_SEDE_CONTRATO, id_contrato = @NUMERO_CONTRATO, id_modalidad = @ID_MODALIDAD_ORIGEN, modalidad_nombre = @NOMBRE_MODALIDAD, id_sede = @ID_SEDE, sede_nombre = REPLACE(@NOMBRE_SEDE, '\"',\"\"), sede_barrio = @NOMBRE_BARRIO_SEDE, sede_direccion = @DIRECCION_SEDE, sede_telefono = @TELEFONO_SEDE, id_oferente = @ID_PRESTADOR, oferente_nombre = REPLACE(@PRESTADOR_SERVICIO, '\"',\"\"), id_persona = @ID_PERSONA, numDocumento = @NUMERO_DOCUMENTO, primerNombre = TRIM(REPLACE(@PRIMER_NOMBRE, '\"',\"\")), segundoNombre = TRIM(REPLACE(@SEGUNDO_NOMBRE, '\"',\"\")), primerApellido = TRIM(REPLACE(@PRIMER_APELLIDO, '\"',\"\")), segundoApellido = TRIM(REPLACE(@SEGUNDO_APELLIDO, '\"',\"\")), id_grupo = @ID_GRUPO, grupo = REPLACE(@NOMBRE_GRUPO, '\"',\"\"), fechaInicioAtencion = @FECHA_INICIO_ATENCION, fechaRegistro = @FECHA_REGISTRO_MATRICULA, fechaRetiro = @FECHA_RETIRO, fechaNacimiento = @FECHA_NACIMIENTO, peso = @PESO, estatura = @ESTATURA, fechaControl = @FECHA_CONTROL");
    	$db->query("DELETE FROM $tabla_mat WHERE id_modalidad NOT IN ($modalidades)");
    	
    	if($facturacion == 1){
    		$db->query("DELETE FROM $tabla_mat WHERE fechaRetiro > 0000-00-00 AND DATE_SUB('$cob_periodo->fecha',INTERVAL 30 DAY) > fechaRetiro OR '$cob_periodo->fecha' < fechaRetiro");
    		$db->query("UPDATE $tabla_mat SET certificacion = 2 WHERE fechaRetiro > 0000-00-00");
    		$archivo_sed = $config->application->basePath . "public/files/bc_bd/" . $carga->nombreSedes;
    		$db->query("LOAD DATA INFILE '$archivo_sed' IGNORE INTO TABLE cob_periodo_contratosedecupos FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (@ID_SEDE_CONTRATO, @ID_OFERENTE, @RAZON_SOCIAL, @ID_SEDE, @NOMBRE_SEDE, @TELEFONO, @DIRECCION, @ID_BARRIO_VEREDA, @NOMBRE_BARRIO_VEREDA, @ID_COMUNA, @NOMBRE_COMUNA_CORREGIMIENTO, @ZONA_UBICACION, @FECHA_INICIO_ATENCION, @NUMERO_CONTRATO, @ID_MODALIDAD_ORIGEN, @NOMBRE_MODALIDAD, @CUPOS_SEDE, @CUPOS_EN_USO, @CUPOS_SOSTENIBILIDAD, @CUPOS_AMPLIACION, @TOTAL_CUPOS_CONTRATO, @COORDX, @COORDY, @COORDINADOR, @PROPIEDAD_INMUEBLE, @AREA, @CAPACIDAD_ATENCION, @GRUPOS_ACTIVOS, @FECHA_FIN_CONTRATO, @FECHA_ADICION_CONTRATO) SET id_periodo = $cob_periodo->id_periodo, id_sede_contrato = @ID_SEDE_CONTRATO, cuposSede = @CUPOS_SEDE, cuposSostenibilidad = @CUPOS_SOSTENIBILIDAD, cuposAmpliacion = @CUPOS_AMPLIACION, cuposTotal = @TOTAL_CUPOS_CONTRATO");
    		$db->query("INSERT IGNORE INTO cob_actaconteo_persona_facturacion (id_periodo, id_sede_contrato, id_contrato, id_sede, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo, fechaInicioAtencion, fechaRegistro, fechaRetiro, fechaNacimiento, peso, estatura, fechaControl, certificacion) SELECT $cob_periodo->id_periodo, id_sede_contrato, id_contrato, id_sede, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo, fechaInicioAtencion, fechaRegistro, fechaRetiro, fechaNacimiento, peso, estatura, fechaControl, certificacion FROM $tabla_mat");
    		$db->query("UPDATE cob_periodo SET id_carga_facturacion = $carga->id_carga WHERE id_periodo = $cob_periodo->id_periodo");
    	}
    	$db->query("DELETE FROM $tabla_mat WHERE fechaRetiro > 0000-00-00");
    	$db->query("INSERT IGNORE INTO bc_contrato (id_contrato, id_oferente, id_modalidad) SELECT id_contrato, id_oferente, id_modalidad FROM $tabla_mat");
    	$db->query("REPLACE INTO bc_sede_contrato (id_sede_contrato, id_oferente, oferente_nombre, id_contrato, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_modalidad, modalidad_nombre, estado) SELECT id_sede_contrato, id_oferente, oferente_nombre, id_contrato, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_modalidad, modalidad_nombre, '1' FROM $tabla_mat");
    	$db->query("INSERT IGNORE INTO cob_actaconteo (id_periodo, id_carga, recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre) SELECT $cob_periodo->id_periodo, $carga->id_carga, '1', id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre FROM $tabla_mat");
    	$db->query("INSERT IGNORE INTO cob_actaconteo_persona (id_actaconteo, id_periodo, recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo) SELECT (SELECT id_actaconteo FROM cob_actaconteo WHERE cob_actaconteo.id_sede_contrato = $tabla_mat.id_sede_contrato AND cob_actaconteo.id_periodo = $cob_periodo->id_periodo AND cob_actaconteo.recorrido = 1), $cob_periodo->id_periodo, 1, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo FROM $tabla_mat");
    	$db->query("DROP TABLE $tabla_mat");
    	return TRUE;
    }
    
    //Verificar este proceso
    public function generarActasFacturacion($cob_periodo, $recorrido_anterior) {
    	$recorrido = $recorrido_anterior + 1;
    	$carga = BcCarga::findFirstByid_carga($cob_periodo->id_carga_facturacion);
    	$db = $this->getDI()->getDb();
    	$timestamp = new DateTime();
    	$tabla_mat = "m" . $timestamp->getTimestamp();
    	$db->query("CREATE TEMPORARY TABLE $tabla_mat (fechaInicioAtencion DATE, fechaRetiro DATE, fechaRegistro DATE, id_sede_contrato BIGINT, id_contrato BIGINT, id_modalidad INT, modalidad_nombre VARCHAR(50), id_sede INT, sede_nombre VARCHAR(80), sede_barrio VARCHAR(80), sede_direccion VARCHAR(80), sede_telefono VARCHAR(80), id_oferente INT, oferente_nombre VARCHAR(100), id_persona INT, numDocumento VARCHAR(100), primerNombre VARCHAR(20), segundoNombre VARCHAR(20), primerApellido VARCHAR(20), segundoApellido VARCHAR(20), id_grupo BIGINT, grupo VARCHAR(80), fechaNacimiento DATE, peso VARCHAR(10), estatura VARCHAR(10), fechaControl DATE) CHARACTER SET utf8 COLLATE utf8_bin");
    	$rows = CobActaconteoPersona::find(["id_periodo = $cob_periodo->id_periodo AND recorrido = $recorrido_anterior AND (asistencia = 2 OR asistencia = 3 OR asistencia = 4 OR asistencia = 6 OR asistencia = 8)"]);
    	if(count($rows) > 0){
    	$sql = "INSERT INTO $tabla_mat (id_sede_contrato,id_contrato,id_modalidad,modalidad_nombre,id_sede,sede_nombre,sede_barrio,sede_direccion,sede_telefono,id_oferente,oferente_nombre,id_persona,numDocumento,primerNombre,segundoNombre,primerApellido,segundoApellido,id_grupo,grupo) VALUES ";
    		foreach ($rows as $row) {
    			$sql .= "(\"". $row->CobActaconteo->id_sede_contrato. "\",\"".$row->CobActaconteo->id_contrato."\",\"".$row->CobActaconteo->id_modalidad."\",\"".$row->CobActaconteo->modalidad_nombre."\",\"".$row->CobActaconteo->id_sede."\",\"".$row->CobActaconteo->sede_nombre."\",\"".$row->CobActaconteo->sede_barrio."\",\"".$row->CobActaconteo->sede_direccion."\",\"".$row->CobActaconteo->sede_telefono."\",\"".$row->CobActaconteo->id_oferente."\",\"".$row->CobActaconteo->oferente_nombre."\",\"".$row->id_persona."\",\"".$row->numDocumento."\",\"".$row->primerNombre."\",\"".$row->segundoNombre."\",\"".$row->primerApellido."\",\"".$row->segundoApellido."\",\"".$row->id_grupo."\",\"".$row->grupo."\"),";
    		}
    		$sql = substr($sql, 0, -1);
    		$db->query($sql);
    	}
    	$db->query("INSERT IGNORE INTO cob_actaconteo (id_periodo, id_carga, recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre) SELECT $cob_periodo->id_periodo, $carga->id_carga, $recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre FROM $tabla_mat");
    	$db->query("INSERT IGNORE INTO cob_actaconteo_persona (id_actaconteo, id_periodo, recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo) SELECT (SELECT id_actaconteo FROM cob_actaconteo WHERE cob_actaconteo.id_sede_contrato = $tabla_mat.id_sede_contrato AND cob_actaconteo.id_periodo = $cob_periodo->id_periodo AND cob_actaconteo.recorrido = $recorrido), $cob_periodo->id_periodo, $recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo FROM $tabla_mat");
    	$db->query("DROP TABLE $tabla_mat");
    	return TRUE;
    }
    //Verificar este proceso
    public function duplicarActa($acta, $cob_periodo) {
    	$recorrido = $acta->recorrido + 1;
    	$carga = BcCarga::findFirstByid_carga($cob_periodo->id_carga_facturacion);
    	$db = $this->getDI()->getDb();
    	$timestamp = new DateTime();
    	$tabla_mat = "m" . $timestamp->getTimestamp();
    	$db->query("CREATE TEMPORARY TABLE $tabla_mat (fechaInicioAtencion DATE, fechaRetiro DATE, fechaRegistro DATE, id_sede_contrato BIGINT, id_contrato BIGINT, id_modalidad INT, modalidad_nombre VARCHAR(50), id_sede INT, sede_nombre VARCHAR(80), sede_barrio VARCHAR(80), sede_direccion VARCHAR(80), sede_telefono VARCHAR(80), id_oferente INT, oferente_nombre VARCHAR(100), id_persona INT, numDocumento VARCHAR(100), primerNombre VARCHAR(20), segundoNombre VARCHAR(20), primerApellido VARCHAR(20), segundoApellido VARCHAR(20), id_grupo BIGINT, grupo VARCHAR(80), fechaNacimiento DATE, peso VARCHAR(10), estatura VARCHAR(10), fechaControl DATE) CHARACTER SET utf8 COLLATE utf8_bin");
    	$rows = CobActaconteoPersona::find(["id_actaconteo = $acta->id_actaconteo AND tipoPersona = 0 AND (asistencia = 2 OR asistencia = 3 OR asistencia = 4 OR asistencia = 5 OR asistencia = 6 OR asistencia = 8)"]);
    	if(count($rows) > 0){
    		$sql = "INSERT INTO $tabla_mat (id_sede_contrato,id_contrato,id_modalidad,modalidad_nombre,id_sede,sede_nombre,sede_barrio,sede_direccion,sede_telefono,id_oferente,oferente_nombre,id_persona,numDocumento,primerNombre,segundoNombre,primerApellido,segundoApellido,id_grupo,grupo) VALUES ";
    		foreach ($rows as $row) {
    			$sql .= "(\"". $row->CobActaconteo->id_sede_contrato. "\",\"".$row->CobActaconteo->id_contrato."\",\"".$row->CobActaconteo->id_modalidad."\",\"".$row->CobActaconteo->modalidad_nombre."\",\"".$row->CobActaconteo->id_sede."\",\"".$row->CobActaconteo->sede_nombre."\",\"".$row->CobActaconteo->sede_barrio."\",\"".$row->CobActaconteo->sede_direccion."\",\"".$row->CobActaconteo->sede_telefono."\",\"".$row->CobActaconteo->id_oferente."\",\"".$row->CobActaconteo->oferente_nombre."\",\"".$row->id_persona."\",\"".$row->numDocumento."\",\"".$row->primerNombre."\",\"".$row->segundoNombre."\",\"".$row->primerApellido."\",\"".$row->segundoApellido."\",\"".$row->id_grupo."\",\"".$row->grupo."\"),";
    		}
    		$sql = substr($sql, 0, -1);
    		$db->query($sql);
    	}
    	$db->query("INSERT IGNORE INTO cob_actaconteo (id_periodo, id_carga, recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre) SELECT $cob_periodo->id_periodo, $carga->id_carga, $recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre FROM $tabla_mat");
    	$db->query("INSERT IGNORE INTO cob_actaconteo_persona (id_actaconteo, id_periodo, recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo) SELECT (SELECT id_actaconteo FROM cob_actaconteo WHERE cob_actaconteo.id_sede_contrato = $tabla_mat.id_sede_contrato AND cob_actaconteo.id_periodo = $cob_periodo->id_periodo AND cob_actaconteo.recorrido = $recorrido), $cob_periodo->id_periodo, $recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo FROM $tabla_mat");
    	$db->query("DROP TABLE $tabla_mat");
    	return TRUE;
    }
    
    public function cerrarPeriodo($id_periodo) {
    	$rows = CobActaconteoPersona::find(["id_periodo = $id_periodo AND (asistencia = 1 OR asistencia = 7)"]);
    	if(count($rows) > 0){
    		$sql = "INSERT INTO $tabla_mat (id_sede_contrato,id_contrato,id_modalidad,modalidad_nombre,id_sede,sede_nombre,sede_barrio,sede_direccion,sede_telefono,id_oferente,oferente_nombre,id_persona,numDocumento,primerNombre,segundoNombre,primerApellido,segundoApellido,id_grupo,grupo) VALUES ";
    		foreach ($rows as $row) {
    			$sql .= "(\"". $row->CobActaconteo->id_sede_contrato. "\",\"".$row->CobActaconteo->id_contrato."\",\"".$row->CobActaconteo->id_modalidad."\",\"".$row->CobActaconteo->modalidad_nombre."\",\"".$row->CobActaconteo->id_sede."\",\"".$row->CobActaconteo->sede_nombre."\",\"".$row->CobActaconteo->sede_barrio."\",\"".$row->CobActaconteo->sede_direccion."\",\"".$row->CobActaconteo->sede_telefono."\",\"".$row->CobActaconteo->id_oferente."\",\"".$row->CobActaconteo->oferente_nombre."\",\"".$row->id_persona."\",\"".$row->numDocumento."\",\"".$row->primerNombre."\",\"".$row->segundoNombre."\",\"".$row->primerApellido."\",\"".$row->segundoApellido."\",\"".$row->id_grupo."\",\"".$row->grupo."\"),";
    		}
    		$sql = substr($sql, 0, -1);
    		$db->query($sql);
    	}
    	$db->query("INSERT IGNORE INTO cob_actaconteo (id_periodo, id_carga, recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre) SELECT $cob_periodo->id_periodo, $carga->id_carga, $recorrido, id_sede_contrato, id_contrato, id_modalidad, modalidad_nombre, id_sede, sede_nombre, sede_barrio, sede_direccion, sede_telefono, id_oferente, oferente_nombre FROM $tabla_mat");
    	$db->query("INSERT IGNORE INTO cob_actaconteo_persona (id_actaconteo, id_periodo, recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo) SELECT (SELECT id_actaconteo FROM cob_actaconteo WHERE cob_actaconteo.id_sede_contrato = $tabla_mat.id_sede_contrato AND cob_actaconteo.id_periodo = $cob_periodo->id_periodo AND cob_actaconteo.recorrido = $recorrido), $cob_periodo->id_periodo, $recorrido, id_contrato, id_persona, numDocumento, primerNombre, segundoNombre, primerApellido, segundoApellido, id_grupo, grupo FROM $tabla_mat");
    	$db->query("DROP TABLE $tabla_mat");
    	return TRUE;
    }
    
    public function generarActa($id_actaconteo){
    	$acta = CobActaconteo::findFirstByid_actaconteo($id_actaconteo);
    	if(!$acta || $acta == NULL){
    		return FALSE;
    	}
    	$acta_id = "ACO-03-". date("Y") . sprintf('%05d', $acta->id_actaconteo);
    	$encabezado = "<div class='seccion encabezado'>
    		<div class='fila center'><div>ACTA DE CONTEO VERIFICACIÓN FÍSICA DE LA ATENCIÓN DEL 100% DE LOS BENEFICIARIOS REPORTADOS EN EL SIBC<br>INTERVENTORÍA BUEN COMIENZO - <em>(RECORRIDO $acta->recorrido)</em></div></div>
    		<div class='fila col3 center'><div>Código: F-ITBC-GC-001</div><div></div><div></div></div>
    		<div class='fila col3e'>
    			<div>ACTA: <span style='font-weight: normal;'>$acta_id</span></div>
    			<div class='col2da'>NÚMERO DE CONTRATO: <span style='font-weight: normal;'>$acta->id_contrato</span></div>
    			<div>MODALIDAD: <span style='font-weight: normal;'>$acta->modalidad_nombre</span></div>
    		</div>
    		<div class='fila col3e'>
    			<div>RUTA: <span style='font-weight: normal;'>".$acta->IbcUsuario->usuario."</span></div>
    			<div class='col2da'>PRESTADOR: <span style='font-weight: normal;'>".substr($acta->oferente_nombre, 0, 35)."</span></div>
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
    	//Página Prestador
    	$html .= $encabezado;
    	$html .= $totalizacion_asistencia;
    	$html .= "
    	<div class='seccion' id='datos_generales'>
    		<div class='fila center bold'><div style='border:none; width: 100%'>2. DATOS GENERALES</div></div>
	    	<div class='fila col3'><div>2.1 FECHA INTERVENTORÍA:</div><div>2.2 HORA INICIO INTERVENTORÍA:</div><div>2.3 HORA FIN INTERVENTORÍA:</div></div>
    		<div class='clear'></div>
    	</div>";
    	$html .= $pie_pagina;
    	$html .= "<div class='paginacion'>PÁGINA DEL PRESTADOR</div>";
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
    		<div class='fila col2'>
    			<div>2.6 CUENTA CON VALLA DE IDENTIFICACIÓN:</div>
    			<div>2.7 CORRECCIÓN DIRECCIÓN:</div>
    		</div>
    		<div class='fila col2'>
    			<div>2.8 CUENTA CON MOSAICO FÍSICO:</div>
    			<div>2.9 CUENTA CON MOSAICO DIGITAL:</div>
    		</div>
    		<div class='clear'></div>
    	</div>
    	<div class='seccion' id='observaciones'>
    		<div class='fila center bold'><div style='border:none; width: 100%'>3. OBSERVACIONES AL MOMENTO DE LA INTERVENTORÍA</div></div>
    		<div class='fila observacion'><div>3.1 OBSERVACIÓN DEL INTERVENTOR:</div></div>
    		<div class='fila observacion'><div>3.2 OBSERVACIÓN DEL ENCARGADO DE LA SEDE:</div></div>
    		<div class='clear'></div>
    	</div>";
    	$html .= $pie_pagina;
    	$p = 1;
    	$html .= "<div class='paginacion'>PÁGINA $p</div>";
  		$i = 1;
  		$j = 1;
  		/*
  		 * Si el acta está en la modalidad Entorno Comunitario, Entorno Familiar o Jardines Infantiles
  		 * se imprimen las actas con la casilla de fecha de visita, de lo contrario la fecha se omite
  		 */ 
  		$fecha_lista = "";
  		$fecha_encabezado = "";
  		$fecha_encabezado2 = "";
  		if($acta->id_modalidad == 3 || $acta->id_modalidad == 5 || $acta->id_modalidad == 7){
  			$fecha_encabezado = "<div>4.5 FECHA VISITA</div>";
  			$fecha_encabezado2 = "<div>5.5 FECHA VISITA</div>";
  			$fecha_lista =  "<div></div>";
  		}
  		$encabezado_beneficiarios = "<div class='seccion' id='listado_beneficiarios'>
    		<div class='fila center bold'><div style='border:none; width: 100%'>4. LISTADO DE BENEFICIARIOS REPORTADOS EN EL SISTEMA DE INFORMACIÓN DE BUEN COMIENZO</div></div>
    		<div class='fila colb'><div style='width: 20px;'>#</div><div style='width: 80px;'>4.1 DOCUMENTO</div><div style='width: 200px'>4.2 NOMBRE COMPLETO</div><div style='width: 200px'>4.3 GRUPO</div><div style='width: 70px'>4.4 ASISTENCIA</div>$fecha_encabezado</div>";
  		$html .= $encabezado;
  		$html .= $encabezado_beneficiarios;
  		foreach($acta->getCobActaconteoPersona(["tipoPersona = 0", 'order' => 'id_grupo, primerNombre asc']) as $row){
  			$nombre_completo = array($row->primerNombre, $row->segundoNombre, $row->primerApellido, $row->segundoApellido);
  			$nombre_completo = implode(" ", $nombre_completo);
  			$i = ($i<10) ? "0" .$i : $i;
  			if($j == 31){
  				$j = 1;
  				$p++;
  				$html .= "<div class='clear'></div></div>" . $pie_pagina;
  				$html .= "<div class='paginacion'>PÁGINA $p</div>";
  				$html .= $encabezado;
  				$html .= $encabezado_beneficiarios;
  			}
  		$html .="<div class='fila colb'><div style='width: 20px;'>$i</div><div style='width: 80px;'>$row->numDocumento</div><div style='width: 200px'>$nombre_completo</div><div style='width: 200px;'>$row->grupo</div><div style='width: 70px'></div>$fecha_lista</div>";
  			$i++;
  			$j++;
  		}
  		$p++;
  		$html .= "<div class='clear'></div></div>" . $pie_pagina;
  		$html .= "<div class='paginacion'>PÁGINA $p</div>";
  		//Si es el recorrido 1 se muestran los niños adicionales:
  		if($acta->recorrido == 1){
  			$html .= $encabezado;
  			$html .= "<div class='seccion' id='listado_beneficiarios'>
  			<div class='fila center bold'><div style='border:none; width: 100%'>5. LISTADO DE BENEFICIARIOS ADICIONALES A LOS REPORTADOS EN EL SISTEMA DE INFORMACIÓN DE BUEN COMIENZO</div></div>
  			<div class='fila colb'><div style='width: 20px;'>#</div><div style='width: 120px;'>5.1 DOCUMENTO</div><div style='width: 200px'>5.2 NOMBRE COMPLETO</div><div style='width: 160px'>5.3 GRUPO</div><div style='width: 70px'>5.4 ASISTENCIA</div>$fecha_encabezado2</div>";
  			for($i = 1; $i <= 30; $i++){
  				$html .="<div class='fila colb'><div style='width: 20px;'>$i</div><div style='width: 120px;'></div><div style='width: 200px'></div><div style='width: 160px;'></div><div style='width: 70px'></div>$fecha_lista</div>";
  			}
  			$p++;
  			$html .= "<div class='clear'></div></div>" . $pie_pagina;
  			$html .= "<div class='paginacion'>PÁGINA $p</div>";
  		}
  		$html .= "<div class='clear'></div>"; // </acta>
    	$datos_acta['html'] = $html; 
    	return $datos_acta;
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
    			return "Periodo cerrado";
    			break;
    	}
    }
    
    /**
     * Returns a human representation of 'id_actaconteo'
     *
     * @return string
     */
    public function getIdDetail()
    {
    	return "ACO-03-". date("Y") . sprintf('%05d', $this->id_actaconteo);
    }
    
    /**
     * Returns a human representation of 'id_actamuestreo'
     *
     * @return string
     */
    public function getId()
    {
    	return $this->id_actaconteo;
    }
    
    /**
     * Contar beneficiarios
     *
     * @return string
     */
    public function countBeneficiarios()
    {
    	return count($this->CobActaconteoPersona);
    }
    
    /**
     * Returns a human representation of 'url'
     *
     * @return string
     */
    public function getUrlDetail()
    {
    	return "cob_actaconteo/ver/$this->id_actaconteo";
    }
    
}
