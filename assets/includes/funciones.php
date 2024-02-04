<?php if(!isset($_SESSION)){session_start();} header("Cache-Control: max-age=900"); header("Expires: ".gmdate("D, d M Y H:i:s",time()+900)." GMT"); header("Last-Modified: ".gmdate("D, d M Y H:i:s",time()-36000)." GMT");
if($_SERVER['SCRIPT_NAME'] != '/portal/anotados.php' AND $_SERVER['SCRIPT_NAME'] != '/portal/repormenus.php' AND $_SERVER['SCRIPT_NAME'] != '/portal/reports_vacs.php'){
    if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) { ini_set("zlib.output_compression", 1); }
}
$fnl = explode("\\",getcwd()); $ef = end($fnl); $archivo = explode("/",$_SERVER['SCRIPT_NAME']); $fa = end($archivo);
date_default_timezone_set('America/Mexico_City');

if(isset($_SESSION['usercorrect']) and $_SESSION['usercorrect']==1 and !empty($_SESSION['usercorrect'])){
    $validate = new tools();
    if(!$validate->validGroups($fa, $_SESSION['gpouser'])){
        if($fa!='index.php'){
            tools::redirApp($ef);
        }
    } else {
        tools::cdcSn();
    }
}else{
    if($fa != 'index.php' AND $fa != 'comedor.php' AND $fa != 'verifica_nss.php' AND $fa != 'verifica_datos.php' AND $fa != 'registra_comedor.php' and $fa != 'cuponeras.php' and $fa != 'mail.php'){
        tools::redirApp($ef);
    }
}
include("mpdf/mpdf.php");
$hostname_conexion = "localhost";
$database_conexion = "aplicaciones";
$username_conexion = "root";
$password_conexion = "Odrak1982*";
$conexion = mysqli_connect($hostname_conexion, $username_conexion, $password_conexion, $database_conexion);
global $conexion;
$server = "AMAPPSSERVER";
$database = "APSISistemas";
$user = "sa";
$password = "";
$conexion_sql = odbc_connect("Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;", $user, $password);
global $conexion_sql;
if ($conexion_sql === false) {
    throw new ErrorException(odbc_errormsg());
} clearstatcache();
class getParams{

    private static $vinstancia;
    public $path;
    public $content;
    public $parser;

    public function __construct() {
        $this->path = explode("\\", getcwd());
        $this->content = end($this->path)=='portal' ? 'assets/includes/setting.ini' : 'setting.ini';
        $this->parser = parse_ini_string(tools::encrypt_decrypt('decrypt', base64_decode(file_get_contents($this->content))),true);
    }

    public static function Instancia(){
        if(is_null(self::$vinstancia)){
            self::$vinstancia = new self();
        }
        return self::$vinstancia;
    }

    /**
     *
     * @param type $modulo
     * @param type $valor
     * @return \StringDevuelve el valor de configuracion solicitado
     * @return String
     */
    public function transConfig($modulo, $valor){
        return $this->parser[$modulo][$valor];
    }

    public function __destruct(){
        $this->parser = null;
        $this->content = null;
        $this->path = null;
    }
}

class SqlSrv extends getParams{
    private static $instance, $serverName, $dbname, $user, $password;
    protected $connection, $statement = null, $status = null;

    public function __construct(){
        parent::__construct();
        try{
            $this->setParams();
            //$this->connection = new PDO("odbc:host=".self::$serverName.";dbname=".self::$dbname.";", self::$user, self::$password);
            $this->connection = new PDO("odbc:Driver={SQL Server Native Client 10.0};Server=".self::$serverName.";Database=".self::$dbname.";", self::$user, self::$password);
            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->status = $this->connection ? true : false;
        } catch (PDOException $ex) {
            die(print_r($ex->getMessage()));
        }
    }

    public static function getInstance(){
        if(is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setParams(){
        self::$serverName = $this->transConfig('odbc','server');
        self::$dbname = $this->transConfig('odbc','mdatabase');
        self::$user = $this->transConfig('odbc','muser');
        self::$password = $this->transConfig('odbc','mpassword');
    }

    public function getStatus(){
        return $this->status;
    }

    public function preparar($consulta){
        $this->statement = $this->connection->prepare($consulta);
    }

    public function ejecutar(array $values){
        $this->statement->execute($values);
    }

    public function stored_proc($proc, array $params){
        $tots = count($params); $prevls = [];
        if ($tots > 1):
            for ($x = 0; $x < $tots; $x++): array_push($prevls, '?');
            endfor;
        else: array_push($prevls, '?'); endif;
        if ($this->getStatus()):
            $this->preparar("EXEC " . $proc . " " . ($tots ? implode(",", $prevls) : $prevls[0]));
            $this->ejecutar($params);
        endif;
        return $this->statement;
    }

    public function select($string, array $vals){
        if($this->getStatus()):
        $this->preparar($string);
        $this->ejecutar($vals);
        endif;
        return $this->statement;
    }

    public function __destruct() {
        parent::__destruct();
        self::$instance = null;
        $this->statement = null;
        $this->connection = null;
        $this->status = null;
    }
}

class Conexion extends mysqli{

    const __MYSQL__ = "conexion";

    private static $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $instance = null;
    public static $DIR, $END_PATH, $RUTA;
    protected static $CONFIG;

    public function __construct() {
        $this->setParameters();
        parent::__construct(self::$DB_HOST, self::$DB_USER, self::$DB_PASS, self::$DB_NAME);
        if(mysqli_connect_error()){
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_errno());
        }
        parent::set_charset('utf-8');
    }

    /* Obtiene la instancia de conexion correspondiente */

    public static function getInstance() {
        if(!self::$instance instanceof self){
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected static function getBase(){
        self::setParameters();
        return self::$DB_NAME;
    }

    /* no se permite duplicar la instancia de conexion */

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    /* se setean los parametros de las variables privadas */

    private static function setParameters(){
        self::$DB_HOST = getParams::Instancia()->transConfig(self::__MYSQL__, "hostname");
        self::$DB_USER = getParams::Instancia()->transConfig(self::__MYSQL__, "username");
        self::$DB_PASS = getParams::Instancia()->transConfig(self::__MYSQL__, "password");
        self::$DB_NAME = getParams::Instancia()->transConfig(self::__MYSQL__, "database");
    }

    /* funcion global que ejecuta consultas en mysql */

    public function query($query, $resultx = MYSQLI_USE_RESULT){
        /* llamada al metodo padre de mysqli */
        $result = parent::query($query , MYSQLI_USE_RESULT);
        if(mysqli_error($this)){
            throw new Exception(mysqli_error($this), mysqli_errno($this));
        }
        return $result;
    }

    /* se cierra la conexion automaticamente despues de cada procedimiento */

    public function __destruct() {
        parent::close();
    }
}

class CRUD extends Conexion{
    public static $rf = array();

    public static function stored_procedure($afrws, $nombre, $tparams, $tips, $valores){
        $nmpr = ($tparams >= 1) ? "?" : "";
        if ($tparams > 1): for ($x = 1; $x < $tparams; $x++): $nmpr .= ",?"; endfor; endif;
        $str = "CALL " . $nombre . "(" . $nmpr . ")";
        $sp = parent::getInstance()->prepare($str);
        if (count($valores) > 0 AND $tparams > 0):
            call_user_func_array(array($sp, 'bind_param'), array_merge(array($tips), self::Referencing(array_merge($valores))));
        endif;
        $rw = $sp->execute(); if ($rw === false): die('execute() failed: ' . htmlspecialchars($sp->error)); endif;
        return $afrws ? intval($sp->affected_rows) : $sp->get_result();
    }

    public static function Qsencillo($str){
        $sql_Qsencillo = parent::getInstance()->prepare($str); $sql_Qsencillo->execute(); return $sql_Qsencillo->get_result();
    }
    //consulta global que minoriza la cantidad de codigo
    static function Consultar($tabla, $ccampos, $tipos, $valor, $inner, $cson, $aftwh, $grpb, $vrgb, $ord, $lo, $lmt, $elmt){
        $elementos = count($ccampos) > 1 ? implode(",", $ccampos) : ($ccampos[0] == 'all' ? '*' : $ccampos[0]);
        if($inner): $ct = 0; $ct2 = 0;
            for($x=0;$x<count($tabla);$x++): $tabla[$x] = self::getBase().".".$tabla[$x];
                if($x>=1): $ct++; $tabla[$x] .= " ON ".$cson[$ct-1+$ct2]." = ".$cson[$ct+$ct2]; $ct2++; endif;
            endfor; $inter = implode(" INNER JOIN ", $tabla);
        else: $inter = self::getBase().".".$tabla; endif; $tdw = count($aftwh);
        for($z=0;$z<$tdw;$z++): $aftwh[$z] = $aftwh[$z]." = ?"; endfor;
        if($tdw>1): $enq = ' WHERE '.implode(" AND ", $aftwh); elseif($tdw==1): $enq = ' WHERE '.$aftwh[0]; endif;
        $li = count($elmt) > 1 ? '?,?' : !empty($elmt) ? '?' : ''; $lsgrb = count($vrgb) > 1 ? implode(",", $vrgb) : empty($vrgb) ? '' : $vrgb[0];
        $grpby = $grpb ? ' GROUP BY '.$lsgrb : '';
        $ord_lmt = $ord ? ' ORDER BY '.$lo : ''; $ord_lmt .= $lmt ? ' LIMIT '.$li : '';
        //var_dump("SELECT ".$elementos." FROM ".$inter." ".$enq.$grpby.$ord_lmt);
        $sql_Cons = parent::getInstance()->prepare("SELECT ".$elementos." FROM ".$inter." ".$enq.$grpby.$ord_lmt);
        if(count($valor)>0 OR count($elmt)>0): call_user_func_array(array($sql_Cons,'bind_param'), array_merge(array($tipos),self::Referencing(array_merge($valor,$elmt)))); endif;
        $rw = $sql_Cons->execute(); if($rw===false): die('execute() failed: '.htmlspecialchars($sql_Cons->error)); else: return $sql_Cons->get_result(); endif;
    }
    //exclusivamente borrar datos
    static function Borrar($tabla,$valor){
        $stmt_del = parent::getInstance()->prepare("DELETE FROM ".self::getBase().".".$tabla." WHERE ".$tabla.".id = ?");
        $idn = $valor; $stmt_del->bind_param('i',$idn);
        $stmt_del->execute(); $afectados = $stmt_del->affected_rows;
        return intval($afectados);
    }
    static function Insertar($tabla,$campos,$tip,$vls){
        $tcin = count($campos); $str = [];
        for($x=0;$x<$tcin;$x++): array_push($str, "?"); endfor;
        $arcmpin = $tcin > 1 ? implode(",", $campos) : $campos[0]; $signs = $vls > 1 ? implode(",", $str) : "?";
        $stmt_in = parent::getInstance()->prepare("INSERT INTO ".self::getBase().".".$tabla." (".$arcmpin.") VALUES (".$signs.")");
        call_user_func_array(array($stmt_in,'bind_param'), array_merge(array($tip), self::Referencing($vls))); $rs = $stmt_in->execute();
        if($rs === false): die('execute() failed: '.htmlspecialchars($stmt_in->error));
        else: return $stmt_in; endif;
        //var_dump(self::Referencing($vls));
    }
    static function Modificar($tabla, $campos, $tps, $vals){
        $tcampos = count($campos); for($x=0;$x<$tcampos;$x++): $campos[$x] .= " = ?"; endfor;
        $arcampos = $tcampos > 1 ? implode(",", $campos) : $campos[0];
        $sql_Upd = parent::getInstance()->prepare("UPDATE ".self::getBase().".".$tabla." SET ".$arcampos." WHERE ".$tabla.".id = ?");
        call_user_func_array(array($sql_Upd,'bind_param'), array_merge(array($tps), self::Referencing($vals)));
        $sql_Upd->execute(); $res = $sql_Upd->affected_rows;
        return $res;
    }

    static function Referencing($ar){
        foreach ($ar as $k => $v): $cn = $ar[$k];
            if(is_int($cn)): $ar[$k] = intval($cn);
            elseif(is_double($cn)): $ar[$k] = doubleval($cn);
            elseif(is_null($cn) or empty($cn)): $ar[$k] = NULL;
            else: $ar[$k] = trim(utf8_decode($cn)); endif;
            self::$rf[$k] = &$ar[$k];
        endforeach;
        return $ar;
    }
}
class altas{
    static function vacios($dato){
        if(empty($dato)){
            return 'NULL';
        }else{
            return "'".$dato."'";
        }
    }
    static function sveSp($trs,$eact,$usu,$depa,$are,$cate,$prob,$feso,$res,$fever,$veri,$slapl,$msts){
        global $conexion;
        if($conexion){
            switch ($trs){
              case 'sve':
                $sql_insSpt = "INSERT INTO `inv_soporte` (`id`, `fatiende`, `atiende`, `usuario`, `departamento`, `area`, `categoria`, `problema`, `fresuelve`, `resuelve`, `solucion`, `fvalida`, `valida`, `estatus`) VALUES (NULL, '".date('Y-m-d H:i:s')."', '".utf8_encode($_SESSION['nameuser'])."', '".utf8_decode($usu)."', '".utf8_encode($depa)."', '".utf8_encode($are)."', '".utf8_encode($cate)."', '".utf8_decode(addslashes($prob))."', ".altas::vacios($feso).", ".(empty($res) ? "'".utf8_decode($_SESSION['nameuser'])."'" : "'".utf8_decode($res)."'").", ".altas::vacios(utf8_decode(addslashes($slapl))).", ".altas::vacios($fever).", ".altas::vacios(utf8_decode($veri)).", '".$msts."');";
              break;
              case 'upd':
                $sql_insSpt = "UPDATE `inv_soporte` SET `usuario` = '".utf8_decode($usu)."', `departamento` = '".utf8_encode($depa)."', `area` = '".utf8_encode($are)."', `categoria` = '".utf8_encode($cate)."', `problema` = '".utf8_decode(addslashes($prob))."', `fresuelve` = ".altas::vacios($feso).", `resuelve` = ".altas::vacios(utf8_decode($res)).", `solucion` = ".altas::vacios(utf8_decode(addslashes($slapl))).", `estatus` = '".$msts."' WHERE `inv_soporte`.`id` = ".$eact.";";
              break;
            }
            mysqli_query($conexion, $sql_insSpt);
        }
    }
    static function sProgs($d, $at, $np, $ce) {
        global $conexion;
        if ($conexion) {
            $controller = new altas();
            $sql_updInv = "UPDATE inv_inventario SET fecha = '$np' WHERE id = " . $ce . ";";
            $rs_updInv = mysqli_query($conexion, $sql_updInv);
            $controller->insertServ($ce, 'PROGRAMADO', strtoupper(utf8_encode($d)), date('Y-m-d H:i:s'), strtoupper(utf8_encode($at)));
        }
    }

    function getData($arr, $campo) {
        $datos = explode(",", $arr);
        $pos = $this->customSearch($campo, $datos);
        return trim(utf8_decode(substr($datos[$pos], (stripos($datos[$pos], "=") + 1))));
    }

    function customSearch($keyword, $arrayToSearch) {
        foreach ($arrayToSearch as $key => $arrayItem) {
            if (stristr($arrayItem, $keyword)) {
                return $key;
            }
        }
    }

    static function insertServ($team, $type, $desc, $fec, $atend) {
        global $conexion;
        if ($conexion) {
            $bajas = true;
            if ($type == 'BAJA') {
                $sql_sBaja = "SELECT * FROM `inv_servicios` WHERE tipo = '" . $type . "' AND equipo = '" . $team . "';";
                $rs_sBaja = mysqli_query($conexion, $sql_sBaja);
                $rws_sBaja = mysqli_num_rows($rs_sBaja);
                if ($rws_sBaja > 0) {
                    $bajas = false;
                }
            }
            if ($bajas) {
                $sql_nwSrv = "INSERT INTO `inv_servicios` (`id`, `equipo`, `tipo`, `descripcion`, `fecha`, `atendio`) VALUES (NULL, '" . $team . "', '" . $type . "', '" . $desc . "', '" . $fec . "', '" . $atend . "');";
                $rs_nwSrv = mysqli_query($conexion, $sql_nwSrv);
            }
        }
    }

	public static function insertaper($ping, $pani, $pusr){
        global $conexion;
        $per = calcvig($ping); $prdf = explode("-", $per);
        $mydays = consultas::corrdayspr($pani); $vig_vac = restaday(calcvig($per)); $cntrls = new altas();
        if ($conexion) {
            $sql_gaps = "SELECT (t1.id + 1) as inicio, (SELECT MIN(t3.id) -1 FROM vac_periodos t3 WHERE t3.id > t1.id) as final FROM vac_periodos t1 WHERE NOT EXISTS (SELECT t2.id FROM vac_periodos t2 WHERE t2.id = t1.id + 1) HAVING final IS NOT NULL ORDER BY inicio LIMIT 1;";
            $rs_gaps = mysqli_query($conexion, $sql_gaps);
            $rw_tgaps = mysqli_num_rows($rs_gaps);
            $crid = '';
            if($rw_tgaps>0){
                $rw_rgaps = mysqli_fetch_assoc($rs_gaps);
                $crid = $rw_rgaps['inicio'];
                $sql_ins_per = "INSERT INTO `aplicaciones`.`vac_periodos` (`id`, `usuario`, `periodo`, `dias`, `tomados`, `pendientes`, `vigencia`) VALUES (".$cntrls->vacios($crid).", '" . $pusr . "', '" . $prdf[0] . "', '" . $mydays . "', '0', '" . $mydays . "', '" . $vig_vac . "');";
                $rs_ins_per = mysqli_query($conexion, $sql_ins_per);
                tools::retAITbl('vac_periodos');
            }else{
                $sql_ins_per = "INSERT INTO `aplicaciones`.`vac_periodos` (`id`, `usuario`, `periodo`, `dias`, `tomados`, `pendientes`, `vigencia`) VALUES (NULL, '" . $pusr . "', '" . $prdf[0] . "', '" . $mydays . "', '0', '" . $mydays . "', '" . $vig_vac . "');";
                $rs_ins_per = mysqli_query($conexion, $sql_ins_per);
            }
            if(!$rs_ins_per){
                tools::retAITbl('vac_periodos'); echo "error al agregar los registros: ". mysqli_error($conexion);
            }
        }
    }

    static function insertasolicitud($pper, $vsol, $fsal, $freg, $ddesc) {
        global $conexion;
        $usersol = $_SESSION['idloguser'];
        if ($conexion) {
            $sql_ultfol = "SELECT * FROM `vac_solicitud` ORDER BY `folio` DESC LIMIT 1;";
            $rs_ultfol = mysqli_query($conexion, $sql_ultfol);
            $tot_ultfol = mysqli_num_rows($rs_ultfol);
            if ($tot_ultfol > 0) {
                $rw_ultfol = mysqli_fetch_assoc($rs_ultfol);
                $folult = $rw_ultfol['folio'];
                $entcad = intval($folult) + 1;
                $strent = strval($entcad);
                $longstr = strlen($strent);
                switch ($longstr) {
                    case 1:$strent = '000000000' . $strent;
                        break;
                    case 2:$strent = '00000000' . $strent;
                        break;
                    case 3:$strent = '0000000' . $strent;
                        break;
                    case 4:$strent = '000000' . $strent;
                        break;
                    case 5:$strent = '00000' . $strent;
                        break;
                    case 6:$strent = '0000' . $strent;
                        break;
                    case 7:$strent = '000' . $strent;
                        break;
                    case 8:$strent = '00' . $strent;
                        break;
                    case 9:$strent = '0' . $strent;
                        break;
                    default:break;
                }
            } else {
                $strent = '0000000001';
            }$folio = $strent;
            $ddesc = intval($ddesc) + 1;
            if ($ddesc == 7) {
                $ddesc = 0;
            }$sql_addsol = "INSERT INTO `aplicaciones`.`vac_solicitud` (`id`, `folio`, `descanso`, `usuario`, `periodo`, `vacaciones`, `fecha_modifica`, `motivo_cancela`, `salida`, `regreso`, `estatus`, `fecha`) VALUES (NULL, '" . $folio . "', '" . $ddesc . "', '" . $usersol . "', '" . $pper . "', '" . $vsol . "', NULL, NULL, '" . $fsal . "', '" . $freg . "', 'P', now());";
            $rs_addsol = mysqli_query($conexion, $sql_addsol);
            if ($rs_addsol) {
                echo "Solicitud de vacaciones registrada exitosamente!";
            }
        }
    }

    static function insertInv($selects, $serie, $monitor, $asignado, $fecha, $tipo, $js) {
        global $conexion;
        if ($conexion) {
            $controller = new altas();
            if ($tipo == 'nw') {
                $sql_addInv = "INSERT INTO `inv_inventario` (`id`, `marca`, `modelo`, `serie`, `monitor`, `tipo`, `departamento`, `area`, `asignado`, `fecha`, `estatus`) VALUES (NULL, '" . $controller->getData($selects, "mrcInv") . "', '" . $controller->getData($selects, "modInv") . "', '" . $serie . "', '" . $monitor . "', '" . $controller->getData($selects, "tipoInv") . "', '" . $controller->getData($selects, "deptoInv") . "', '" . $controller->getData($selects, "areInv") . "', '" . utf8_decode($asignado) . "', " .altas::vacios($fecha). ", '" . $controller->getData($selects, "estInv") . "');";
            } else {
                $sql_addInv = "UPDATE `inv_inventario` SET `marca` = '" . $controller->getData($selects, "mrcInv") . "', `modelo` = '" . $controller->getData($selects, "modInv") . "', `serie` = '" . $serie . "', `monitor` = '" . $monitor . "', `tipo` = '" . $controller->getData($selects, "tipoInv") . "', `departamento` = '" . $controller->getData($selects, "deptoInv") . "', `area` = '" . $controller->getData($selects, "areInv") . "', `asignado` = '" . utf8_decode($asignado) . "', `fecha` = " .altas::vacios($fecha) . ", `estatus` = '" . $controller->getData($selects, "estInv") . "' WHERE `inv_inventario`.`id` = " . $js;
            }
            $rs_addInv = mysqli_query($conexion, $sql_addInv);
            if ($tipo == 'nw') {
                $ulreg = mysqli_insert_id($conexion);
                echo $ulreg;
            } else {
                echo $js;
            }
        }
    }

}

class modificaciones {
    static function UpdMnSys($me,$ca,$pe,$fe,$hs){
        global $conexion;
        if($conexion){
            $dy = explode(",", $hs);
            $sql_UpdM = "UPDATE `com_menuporfecha` SET menu = '".$me."',fecha='".$fe."',cantidad='".$ca."',disponibles='".$ca."',precio='".$pe."',dia='".$dy[1]."',semana='".$dy[2]."' where id = '".$dy[0]."'";
            $rs_UpdM = mysqli_query($conexion,$sql_UpdM);
            if($rs_UpdM){
                $nfh = explode("-",$fe);
                return "Menu ".$nfh[2]."/". tools::reducemes(tools::obtenermes($nfh[1]))."/".$nfh[0]." Actualizado!";
            }
        }
    }
    static function CancSpt($foc){
        global $conexion;
        if($conexion){
            $sql_cncSpt = "UPDATE `inv_soporte` SET `estatus` = 'C',`fresuelve` = '".date('Y-m-d H:i:s')."' WHERE `inv_soporte`.`id` = ".$foc.";";
            mysqli_query($conexion, $sql_cncSpt);
        }
    }
    static function finSpt($pa,$to,$qv){
        global $conexion;
        if($conexion){
            $tstr = $to == 'true' ? true : false; $opr = (!$tstr) ? 'T' : 'V'; $ocmp = (!$tstr) ?  '' : ",fvalida = '".date('Y-m-d H:i:s')."',valida = '".utf8_decode($qv)."'";
            $sql_finSpt = "UPDATE inv_soporte SET estatus = '".$opr."'".$ocmp." WHERE id = '".$pa."';";
            mysqli_query($conexion, $sql_finSpt);
        }
    }

    static function updateClSpt($ante,$actu,$colu){
        global $conexion;
        if($conexion){
            $sql_upClSpt = "UPDATE inv_soporte SET ".strtolower($colu)."='".$actu."' WHERE ".strtolower($colu)."='".$ante."';";
            mysqli_query($conexion, $sql_upClSpt);
        }
    }
    public static function updServ($rg, $dc, $fc, $ac) {
        global $conexion;
        if ($conexion) {
            $sql_updSrv = "UPDATE `inv_servicios` SET `descripcion` = '" . $dc . "', `fecha` = '" . $fc . "', `atendio` = '" . $ac . "' WHERE `inv_servicios`.`id` = " . $rg . ";";
            $rs_updSrv = mysqli_query($conexion, $sql_updSrv);
        }
    }

    public static function cngColInv($column, $anter, $nuev) {
        global $conexion;
        if ($conexion) {
            $sql_updCI = "UPDATE `inv_inventario` SET `" . $column . "`= '" . utf8_decode($nuev) . "' WHERE `" . $column . "` = '" . utf8_decode($anter) . "'";
            $rs_updCI = mysqli_query($conexion, $sql_updCI);
        }
    }

    public static function actualizamtvvcs($iden, $ntxt) {
        global $conexion;
        if ($conexion) {
            $sql_actmtv = "UPDATE vac_mcancela SET descripcion = '" . $ntxt . "' WHERE id = " . $iden;
            $rs_actmtv = mysqli_query($conexion, $sql_actmtv);
            if ($rs_actmtv) {
                $vrret = trim('Motivo actualizado con exito!');
            }if (!empty($vrret)) {
                return $vrret;
            }
        }
    }

}

class consultas {
    public static function getCumpleanios($plaza, $mes, $aux){
        $stmt = SqlSrv::getInstance()->select("Select (E.afiliacion + E.digvera) As nss, empleado = Nombre + ' ' + Ap_Paterno + ' ' + Ap_Materno, depto = C.NomDepto, dia = DatePart(dd,E.FchNac)
			From Empleados E, Llaves L, Centros C
			Where E.Activo = 'S' And E.Empresa In ('9','12') And DatePart(mm,E.FchNac) = ?
				And L.Empresa = E.Empresa And L.Codigo = E.Codigo
				And C.Empresa = L.Empresa And C.Centro = L.Centro
				And C.NomDepto Like Case ?
					WHEN 'CE' THEN '%CELAYA%'
					WHEN 'IR' THEN '%IRAPUATO%'
					WHEN 'LE' THEN '%LEON%'
					WHEN 'SA' THEN '%SALAMANCA%'
				End
			Order By 4,2;",[$mes,$plaza]);
        $elems = array(); $x=0;
         while($fila = $stmt->fetchObject()){
             $empleado = trim(utf8_encode($fila->empleado)); $departamento = trim(utf8_encode($fila->depto)); $ldia = trim($fila->dia); $nssC = trim($fila->nss);
             $elems[$nssC] = array('nombre' => $empleado, 'departamento' => $departamento, 'dia' => $ldia);
             $htm .= '<tr><td>'.($aux ? '<div class="input-group"><input id="'.$nssC.'" disabled="disabled" type=text class="form-control IedtCum" spellcheck="true" value="' : '').$empleado.($aux ? '" /><span class="input-group-btn"><button id="'.$nssC.'_'.$x.'" class="btn btn-default edtCum"><i class="fa fa-pencil"></i></button></span></div>' : '').'</td><td>'.$departamento.'</td><td>'.$ldia.'</td></tr>';
             /*$htm .= *//*consultas::vExisteUser($nssC);*/
             $x++;
         }
         $fp = fopen(__DIR__.'/json/cumpleanios.json', 'w+');
         $myData = json_encode($elems); $myData = base64_encode($myData);
         fwrite($fp, tools::encrypt_decrypt('encrypt', $myData));
         fclose($fp);
         return $htm;
    }
    static function getAtndSpt(){
        global $conexion;
        if($conexion){
            $sql_gtAtndSpt = "SELECT DISTINCT resuelve FROM inv_soporte ORDER BY resuelve;";
            $rs_gtAtndSpt = mysqli_query($conexion, $sql_gtAtndSpt);
            while($rw_gtAtndSpt = mysqli_fetch_assoc($rs_gtAtndSpt)){
                echo "<option value='".$rw_gtAtndSpt['resuelve']."'>".$rw_gtAtndSpt['resuelve']."</option>";
            }
        }
    }

    static function getClSpt($col){
        global $conexion;
        if($conexion){
            $sql_gtClSpt = "SELECT DISTINCT ".$col." FROM `inv_soporte` ORDER BY ".$col;
            $rs_gtClSpt = mysqli_query($conexion, $sql_gtClSpt);
            while($rw_gtClSpt = mysqli_fetch_assoc($rs_gtClSpt)){
                echo '<option value="'.$rw_gtClSpt[$col].'">'.$rw_gtClSpt[$col].'</option>';
            }
        }
    }
    function getNmUserById($Id){
        global $conexion;
        if($conexion){
            $sql_NmUser = "SELECT nombre FROM `com_usuarios` WHERE `id` = ".$Id;
            $rs_NmUser = mysqli_query($conexion, $sql_NmUser);
            $rows_NmUser = mysqli_num_rows($rs_NmUser);
            if($rows_NmUser>0){
                $rw_NmUser = mysqli_fetch_assoc($rs_NmUser);
                return utf8_encode($rw_NmUser['nombre']);
            }
        }
    }
    static function getSpt($act,$pnds,$edt,$tblupd,$lini,$lfini,$ufil,$totcns,$plimit,$pgact,$flsfil){
        global $conexion;
        if($conexion){
            $control = new consultas();
            if(!$pnds){
                if($tblupd == "tblRegsSpt"){
                    if($ufil!='' AND $ufil!= 'x' AND $lini!='' AND $lfini!=''){
                        $cade = "WHERE DATE(fatiende) BETWEEN '".$lini."' AND '".$lfini."' AND resuelve = '".$ufil."'";
                    }elseif (($ufil=='x' OR $ufil=='') AND $lini != '' AND $lfini != ''){
                        $cade = "WHERE DATE(fatiende) BETWEEN '".$lini."' AND '".$lfini."'";
                    }elseif ($lini == '' AND $lfini == '' AND $ufil != '' AND $ufil != 'x'){
                        $cade = "WHERE resuelve = '".$ufil."'";
                    }elseif ($lini == '' AND $lfini == '' AND ($ufil == '' OR $ufil == 'x')){
                        $cade = "";
                    }
                }else{
                    $cnrml = ($_SESSION['gpouser'] == '0') ? "" : " resuelve = '".$act."' AND";
                    $cade = "WHERE".$cnrml." estatus = 'A'";
                }
                $sql_gtSpt = "SELECT * FROM inv_soporte ".$cade." order by fatiende DESC";
            }else{
                $sql_gtSpt = "SELECT * FROM inv_soporte where estatus = 'T' order by fatiende DESC";
            }
            if ($plimit) {
                $pgact = ($pgact - 1) * $flsfil;
                if (intval($pgact) < 0) {
                    $pgact = 0;
                }
                $cls_limit = ' LIMIT ' . $pgact . ',' . $flsfil;
            } else {
                $cls_limit = '';
            }
            $sql_gtSpt = $sql_gtSpt.$cls_limit;
            $rs_gtSpt = mysqli_query($conexion, $sql_gtSpt);
            $rws_gtSpt = mysqli_num_rows($rs_gtSpt);
            if(!$totcns){
                $lclase = ($tblupd == 'tblvPnds') ? "class='vlspt' " : (($tblupd == 'tblRegsSpt') ? " " : "class='fspt' ");
                while ($rw_gtSpt = mysqli_fetch_assoc($rs_gtSpt)) {
                    switch ($rw_gtSpt['estatus']){
                        case 'A':
                            $cestatus = "<td><span class='badge bg-theme'>ACTIVO</span></td>";
                            break;
                        case 'T':
                            $cestatus = "<td><span class='badge bg-warning'>TERMINADO</span></td>";
                            break;
                        case 'C':
                            $cestatus = "<td><span class='badge bg-danger'>CANCELADO</span></td>";
                            break;
                        case 'V':
                            $cestatus = "<td><span class='badge bg-info'>VALIDADO</span></td>";
                            break;
                        default :
                            $cestatus = "";
                            break;
                    }
                    echo "<tr id='" . $rw_gtSpt['id'] . "' " . $lclase . "style='cursor:pointer;'><td class='centered'>" . utf8_encode($rw_gtSpt['fatiende']) . "°<input id='atnd_".$rw_gtSpt['id']."' type='hidden' value='".$rw_gtSpt['atiende']."' /></td><td id='tlt_" . $rw_gtSpt['id'] . "'>" . utf8_encode($rw_gtSpt['usuario']) . "</td><td>" . utf8_encode($rw_gtSpt['departamento']) . "</td><td>" . utf8_encode($rw_gtSpt['area']) . "</td><td>" . utf8_encode($rw_gtSpt['categoria']) . "</td><td id='prb_" . $rw_gtSpt['id'] . "' class='tltprb'>" . ((strlen($rw_gtSpt['problema']) > 0) ? substr(utf8_encode($rw_gtSpt['problema']), 0, 35) . "..." : '') . "<input id='hp_" . $rw_gtSpt['id'] . "' type='hidden' value='" . utf8_encode($rw_gtSpt['problema']) . "' /></td><td>" . $rw_gtSpt['fresuelve'] . "</td><td>" . $rw_gtSpt['resuelve'] . "<input id='hrs_" . $rw_gtSpt['id'] . "' type='hidden' value='" . $rw_gtSpt['resuelve'] . "' /></td><td id='tltsl_" . $rw_gtSpt['id'] . "' class='slprb'>" . (strlen($rw_gtSpt['solucion']) >= 25 ? utf8_encode(substr($rw_gtSpt['solucion'], 0, 24)) . '...' : utf8_encode($rw_gtSpt['solucion'])) . "<input id='hs_" . $rw_gtSpt['id'] . "' type='hidden' value='" . utf8_encode($rw_gtSpt['solucion']) . "' /><input id='hes_" . $rw_gtSpt['id'] . "' type='hidden' value='" . $rw_gtSpt['estatus'] . "' /></td>" . ($tblupd == 'tblRegsSpt' ? $cestatus : "") . "</tr>";
                }
                if ($rws_gtSpt == 0) {
                    $lcoslpan = $tblupd == 'tblRegsSpt' ? '10' : '9';
                    return '<tr><td colspan="' . $lcoslpan . '" class="centered">No existen registros en la tabla!</td></tr>';
                }
            }else{
               return trim('|'.$rws_gtSpt);
            }
        }
    }
    public static function vldRsgInv($eq, $de, $ara, $asi) {
        global $conexion;
        if ($conexion) {
            $sql_vldRsg = "SELECT departamento, area, asignado FROM inv_inventario WHERE id = '" . $eq . "'";
            $rs_vldRsg = mysqli_query($conexion, $sql_vldRsg);
            $rw_vldRsg = mysqli_fetch_assoc($rs_vldRsg);
            if ($de != $rw_vldRsg['departamento'] OR $ara != $rw_vldRsg['area'] OR $asi != $rw_vldRsg['asignado']) {
                return '1';
            } else {
                return '0';
            }
        }
    }

    public static function getServices($tipo, $tm, $ht) {
        global $conexion;
        if ($conexion) {
            $response = '';
            switch ($tipo) {
                case 'period':
                    $sql_gtSrv = "SELECT id, asignado, departamento, area, marca, modelo, tipo, serie, monitor, fecha FROM `inv_inventario` WHERE DATE(fecha) between '" . $tm . "' and '" . $ht . "' order by fecha, departamento, area, asignado;";
                    $rs_gtSrv = mysqli_query($conexion, $sql_gtSrv);
                    $num = mysqli_num_rows($rs_gtSrv);
                    while ($rw_gtSrv = mysqli_fetch_assoc($rs_gtSrv)) {
                        $response .= "<tr style='cursor:pointer;'><td>" . utf8_encode($rw_gtSrv['asignado']) . "</td><td>" . utf8_encode($rw_gtSrv['departamento']) . "</td><td>" . utf8_encode($rw_gtSrv['area']) . "</td><td>" . $rw_gtSrv['marca'] . "</td><td>" . $rw_gtSrv['modelo'] . "</td><td>" . $rw_gtSrv['tipo'] . "</td><td>" . $rw_gtSrv['serie'] . "</td><td>" . $rw_gtSrv['monitor'] . "</td><td id='fchprg_".$rw_gtSrv['id']."'>" . $rw_gtSrv['fecha'] . "</td><td class='centered'><span id='" . $rw_gtSrv['id'] . "' class='btn btn-theme btn-sm tprogs'><i class=\"fa fa-check\"></i></span></td></tr>";
                    }
                    if ($num == 0) {
                        $response .= "<tr><td class='centered' colspan='10'><strong>No hay servicios programados en el rango de fecha ingresado!</strong></td></tr>";
                    }
                    break;
                case 'team':
                    $col = 'equipo';
                    $sql_gtSrv = "SELECT * FROM `inv_servicios` WHERE " . $col . " = '" . $tm . "' ORDER BY fecha DESC;";
                    $rs_gtSrv = mysqli_query($conexion, $sql_gtSrv);
                    while ($rw_gtSrv = mysqli_fetch_assoc($rs_gtSrv)) {
                        switch ($rw_gtSrv['tipo']) {
                            case 'REASIGNACION': $tip = '<span class="badge bg-theme04"><i class="fa fa-exchange"></i> Reasignacion</span>';
                                break;
                            case 'BAJA': $tip = '<span class="badge bg-danger"><i class="fa fa-times-circle-o"></i> Baja</span>';
                                break;
                            case 'ALTA': $tip = '<span class="badge bg-info"><i class="fa fa-plus-circle"></i> Alta</span>';
                                break;
                            case 'PREVENTIVO': $tip = '<span class="badge bg-warning"><i class="fa fa-exclamation-circle"></i> Preventivo</span>';
                                break;
                            case 'CORRECTIVO': $tip = '<span class="badge bg-danger"><i class="fa fa-wrench"></i> Correctivo</span>';
                                break;
                            case 'PROGRAMADO': $tip = '<span class="badge bg-info"><i class="fa fa-calendar"></i> Programado</span>';
                                break;
                        }
                        $response .= '<tr ' . ($ht == "false" ? 'class="edtSrvs"' : '') . 'id="' . $rw_gtSrv['id'] . '" onselectstart="return false;" unselectable="on" style="cursor:pointer;"><td>' . $tip . '</td><td>' . utf8_encode($rw_gtSrv['descripcion']) . '</td><td>' . $rw_gtSrv['fecha'] . '</td><td>' . utf8_encode($rw_gtSrv['atendio']) . '</td></tr>';
                    }
                    break;
                case 'idn':
                    $col = 'id';
                    $sql_gtSrv = "SELECT * FROM `inv_servicios` WHERE " . $col . " = '" . $tm . "' ORDER BY fecha DESC;";
                    $rs_gtSrv = mysqli_query($conexion, $sql_gtSrv);
                    if ($rw_gtSrv = mysqli_fetch_assoc($rs_gtSrv)) {
                        $response .= utf8_encode($rw_gtSrv['descripcion']) . '|' . $rw_gtSrv['fecha'] . '|' . utf8_encode($rw_gtSrv['atendio']) . '|' . $rw_gtSrv['tipo'];
                    }
                    break;
                default :
                    break;
            }
            return $response;
        }
    }

    public static function retrDataInv($dp, $tf) {
        global $conexion;
        if ($conexion) {
            switch ($tf) {
                case 'dpto':
                    $are = array();
                    $areaHtml = "<option value='0'>Selecciona...</option>";
                    $sql_area = "SELECT DISTINCT area FROM `inv_inventario` WHERE departamento = '" . utf8_decode($dp) . "' ORDER BY area ASC;";
                    $rs_area = mysqli_query($conexion, $sql_area);
                    while ($rw_area = mysqli_fetch_assoc($rs_area)) {
                        array_push($are, $rw_area['area']);
                    }
                    $areas = count($are);
                    for ($y = 0; $y < $areas; $y++) {
                        $areaHtml .= "<option value='" . trim(utf8_encode($are[$y])) . "'>" . trim(utf8_encode($are[$y])) . "</option>";
                    }
                    return $areaHtml;
                    break;
                case 'area':
                    $series = array();
                    $asignado = array();
                    $ident = array();
                    $disp = array();
                    $mrc = array();
                    $mod = array();
                    $mrcHtml = "<option value='0'></option>";
                    $modHtml = "<option value='0'></option>";
                    $serieHtml = "<option value='0'></option>";
                    $asignadoHtml = "<option value='0'>Selecciona...</option>";
                    $dispHtml = "<option value='0'></option>";
                    $sql_rtD = "SELECT id, serie, asignado, tipo, marca, modelo FROM `inv_inventario` WHERE area = '" . utf8_decode($dp) . "' and estatus = 1;";
                    $rs_rtD = mysqli_query($conexion, $sql_rtD);
                    while ($rw_rtD = mysqli_fetch_assoc($rs_rtD)) {
                        array_push($series, $rw_rtD['serie']);
                        array_push($mod, $rw_rtD['modelo']);
                        array_push($mrc, $rw_rtD['marca']);
                        array_push($asignado, utf8_encode($rw_rtD['asignado']));
                        array_push($ident, $rw_rtD['id']);
                        array_push($disp, $rw_rtD['tipo']);
                    }
                    $total = count($series);
                    for ($x = 0; $x < $total; $x++) {
                        $serieHtml .= "<option value='s" . $x . "_" . $series[$x] . "_" . $ident[$x] . "'>" . $series[$x] . "</option>";
                        $asignadoHtml .= "<option value='s" . $x . "_" . $asignado[$x] . "_" . $ident[$x] . "'>" . $asignado[$x] . "</option>";
                        $dispHtml .= "<option value='s" . $x . "_" . $disp[$x] . "_" . $ident[$x] . "'>" . $disp[$x] . "</option>";
                        $mrcHtml .= "<option value='s" . $x . "_" . $mrc[$x] . "_" . $ident[$x] . "'>" . $mrc[$x] . "</option>";
                        $modHtml .= "<option value='s" . $x . "_" . $mod[$x] . "_" . $ident[$x] . "'>" . $mod[$x] . "</option>";
                    }
                    return $serieHtml . '*' . $asignadoHtml . '*' . $dispHtml . '*' . $mrcHtml . '*' . $modHtml;
                    break;
            }
        }
    }

    public static function selCtrlsInv($type) {
        global $conexion;
        if ($conexion) {
            $sql_sldptosInv = "SELECT DISTINCT " . $type . " FROM inv_inventario ORDER BY " . $type . " ASC;";
            $rs_sldptosInv = mysqli_query($conexion, $sql_sldptosInv);
            while ($rw_sldptosInv = mysqli_fetch_assoc($rs_sldptosInv)) {
                if (strpos($rw_sldptosInv[$type], "'") !== FALSE) {
                    $cadena = 'value="' . trim(utf8_encode($rw_sldptosInv[$type])) . '"';
                } else {
                    $cadena = "value='" . trim(utf8_encode($rw_sldptosInv[$type])) . "'";
                }
                echo "<option " . $cadena . ">" . utf8_encode($rw_sldptosInv[$type]) . "</option>";
            }
        }
    }

    public static function getInventario($order, $amount, $like, $id) {
        global $conexion;
        if ($conexion) {
            if ($like) {
                $sql_invent = "SELECT * FROM `inv_inventario` WHERE " . $order . " LIKE '%" . $amount . "%' COLLATE latin1_spanish_ci";
            } elseif (!empty($order) and ! empty($amount)) {
                $sql_invent = "SELECT * FROM `inv_inventario` ORDER BY " . $order . " " . $amount . ",id;";
            } elseif ($id != '') {
                $sql_invent = "SELECT * FROM inv_inventario WHERE id = " . $id . ";";
            }
            $res_invent = mysqli_query($conexion, $sql_invent);
            $rw_invtot = mysqli_num_rows($res_invent);
            if ($id == '') {
                while ($rw_invent = mysqli_fetch_assoc($res_invent)) {
                    echo "<tr id='edtInv_" . $rw_invent['id'] . "'><td align='center' width='13%'>" . utf8_encode($rw_invent['departamento']) . "</td><td width='6%'>" . utf8_encode($rw_invent['area']) . "</td><td align='center' width='5%'>" . $rw_invent['tipo'] . "</td><td align='center' width='6%'>" . $rw_invent['marca'] . "</td><td width='12.5%'>" . $rw_invent['modelo'] . "</td><td width='12.5%'>" . $rw_invent['serie'] . "</td><td width='15.5%'>" . $rw_invent['monitor'] . "</td><td width='11.5%'>" . utf8_encode($rw_invent['asignado']) . "</td><td width='7%'>" . $rw_invent['fecha'] . "</td><td class='centered' width='11%'><span class='badge " . ($rw_invent['estatus'] == '1' ? 'bg-success' : 'bg-danger') . "'>" . ($rw_invent['estatus'] == '1' ? 'Activo' : 'Inactivo') . "</span></td></tr>";
                }
                if ($rw_invtot == 0) {
                    echo "<tr><td colspan='11' align='center'><i class='fa fa-cube'></i>&nbsp;<strong>Aun no se han cargado registros al sistema!</strong></td></tr>";
                }
            } else {
                while ($rw_invent = mysqli_fetch_assoc($res_invent)) {
                    echo utf8_encode($rw_invent['departamento']) . ',' . $rw_invent['tipo'] . ',' . $rw_invent['marca'] . ',' . $rw_invent['modelo'] . ',' . $rw_invent['serie'] . ',' . $rw_invent['monitor'] . ',' . $rw_invent['area'] . ',' . utf8_encode($rw_invent['asignado']) . ',' . $rw_invent['fecha'] . ',' . $rw_invent['estatus'] . ',' . $rw_invent['id'];
                }
            }
        }
    }

    public static function printdeppues($retparam, $puser, $pempress) {
        global $conexion_sql;
        $puser = substr($puser, 2);
        $pempress = intval($pempress);
        $sql_deppues = "Select TOP 1 c.nomdepto AS departamento, actividad AS puesto From horas_laboradas H, Tabulador T, Centros C Where H.Codigo = '" . $puser . "' And H.empresa = '" . $pempress . "' And T.empresa = H.empresa And T.ocupacion = H.ocupacion And C.Empresa = H.empresa And C.Centro = H.centro Order By H.ayo_operacion Desc, H.periodo Desc";
        $stmt_deppues = odbc_exec($conexion_sql, $sql_deppues);
        if ($stmt_deppues === false) {
            throw new ErrorExcpetion(odbc_errormsg());
        } else {
            if ($row_deppues = odbc_fetch_row($stmt_deppues)) {
                $departamento = odbc_result($stmt_deppues, "departamento");
                $puesto = odbc_result($stmt_deppues, "puesto");
                if ($retparam == 'dep') {
                    return utf8_encode($departamento);
                } elseif ($retparam == 'pue') {
                    return utf8_encode($puesto);
                }
            }odbc_close($conexion_sql);
        }
    }

    public static function corrdayspr($prantg) {
        global $conexion;
        if ($conexion) {
            $sql_dcr = "SELECT dias FROM `vac_periodosley` WHERE antiguedad = " . $prantg;
            $rs_dcr = mysqli_query($conexion, $sql_dcr);
            $rw_dcr = mysqli_fetch_assoc($rs_dcr);
            $corr_usr = $rw_dcr['dias'];
            return $corr_usr;
        }
    }

    public static function prntmtvstblvcs() {
        global $conexion;
        if ($conexion) {
            $sql_tblmtvs = "SELECT * FROM vac_mcancela ORDER BY descripcion";
            $rs_tblmtvs = mysqli_query($conexion, $sql_tblmtvs);
            $num_rows = mysqli_num_rows($rs_tblmtvs);
            while ($rw_tblmtvs = mysqli_fetch_assoc($rs_tblmtvs)) {
                echo "<tr><td id=\"cldmtvcnc_" . $rw_tblmtvs['id'] . "\" width=\"15%\">" . $rw_tblmtvs['descripcion'] . "</td><td align=\"center\" width=\"15%\"><a class=\"btn btn-default edtmtvsvcs\" id=\"mtvs_" . $rw_tblmtvs['id'] . "\" style=\"cursor: pointer;\"><i class='fa fa-edit'></i> Editar</a></td></tr>";
            }if ($num_rows == 0) {
                echo '<tr><td colspan="2" align="center">No hay motivos registrados en sistema</td></tr>';
            }
        }
    }

    public static function prntusers($shdt, $ordsys, $entsys, $estts, $rtpsys) {
        global $conexion;
        if ($conexion) {
            $shsystem = '';
            $dtord = '';
            $celda = '';
            if (empty($entsys)) {
                $myenpsys = "AND empresa = '12' ";
            } else {
                $myenpsys = 'AND empresa = ' . $entsys . ' ';
            }if (!empty($shdt)) {
                $shsystem = "AND (nombre like '%" . $shdt . "%' OR nss = '" . $shdt . "' OR email like '%" . $shdt . "%' OR substring(usuario,3) = '" . $shdt . "') ";
            }$sql_users = "SELECT * FROM com_usuarios WHERE tipo = '4' " . $myenpsys . $shsystem . " AND estatus = '" . $estts . "'";
            switch ($ordsys) {
                case 'nss':$dtord = ' ORDER BY nss';
                    break;
                case 'codigo':$dtord = ' ORDER BY CAST(substring(usuario,3) AS SIGNED)';
                    break;
                case 'nombre':$dtord = ' ORDER BY nombre';
                    break;
                case 'ingreso':$dtord = ' ORDER BY ingreso';
                    break;
                default:$dtord = ' ORDER BY CAST(substring(usuario,3) AS SIGNED)';
                    break;
            }$sql_users.=$dtord;
            $rs_users = mysqli_query($conexion, $sql_users);
            $num_usrs = mysqli_num_rows($rs_users);
            $html = '';
            if ($num_usrs > 0) {
                while ($rw_users = mysqli_fetch_assoc($rs_users)) {
                    $fing = explode("-", $rw_users['ingreso']);
                    $idntfy = $rw_users['id'];
                    if ($rtpsys == '0') {
                        $columns = 8;
                        $rptemp = '';
                        $stl_ent = '';
                        $stl_empl = '';
                        $stl_nss = '';
                        $stl_ing = '';
                        $stl_nmb = '';
                        $icono = ($rw_users['estatus'] == 1) ? '<span class="badge bg-theme"><i class="fa fa-check-circle fa-xs"></i></span>' : '<span class="badge bg-danger"><i class="fa fa-times-circle fa-xs"></i></span>';
                        $act_ina = '<td width="5%" style="text-align: center;">' . $icono . '</td>';
                        $celda = '<td style="text-align:center;">' . substr($rw_users['usuario'], 2) . '</td>';
                        $stl_eml = 'style="font-size:0.98em; color:darkgreen; width:30%;"';
                        $accion = '<td width="5%" style="text-align: center;"><a id="edtusr_' . $idntfy . '" class="usredt"><span class="btn btn-default btn-xs"><i class="fa fa-pencil fa-xs"></i></span></a></td>';
                    } else {
                        $columns = 5;
                        $stl_empl = 'width="5%"';
                        $stl_nss = 'width="11%"';
                        $stl_nmb = 'width="41%"';
                        $stl_eml = 'width="33%" style="font-size:1.08em; color:darkgreen;"';
                        $stl_ing = 'width="10%"';
                        $stl_ent = '';
                        $rptemp = "<td " . $stl_empl . ">" . substr($rw_users['usuario'], 2) . "</td>";
                        $act_ina = '';
                        $accion = '';
                    }$html.="<tr>" . $rptemp . $act_ina . $celda . "<td " . $stl_nss . ">" . $rw_users['nss'] . "</td><td " . $stl_nmb . ">" . utf8_encode($rw_users['nombre']) . "</td><td " . $stl_eml . ">" . trim(utf8_encode($rw_users['email'])) . "</td><td " . $stl_ing . ">" . $fing[2] . "-" . tools::reducemes(tools::obtenermes($fing[1])) . "-" . $fing[0] . "</td>" . $accion . "</tr>";
                }$html.='<tr><td colspan="' . $columns . '" align="right"><strong style="font-size:1.19em; font-weight:bolder; font-family:Arial Black;"><span style="color:darkgreen;">' . $num_usrs . '</span></strong><strong style="font-size:1em; font-weight: bold;"> Usuario(s)</strong></td></tr>';
            }if ($html != '') {
                return trim($html);
            }
        }
    }

    public static function retnmempr($ident) {
        global $conexion;
        if ($conexion) {
            $sql_myent = "SELECT descripcion FROM com_empresas WHERE codigo = '" . $ident . "'";
            $rs_myent = mysqli_query($conexion, $sql_myent);
            $rw_myent = mysqli_fetch_assoc($rs_myent);
            return trim(utf8_encode($rw_myent['descripcion']));
        }
    }

    public static function retdsmtv($cllid) {
        global $conexion;
        if ($conexion) {
            $sql_dsmtv = "SELECT descripcion FROM vac_mcancela WHERE id = " . $cllid;
            $rs_dsmtv = mysqli_query($conexion, $sql_dsmtv);
            $rw_dsmtv = mysqli_fetch_assoc($rs_dsmtv);
            return trim(utf8_encode($rw_dsmtv['descripcion']));
        }
    }

    public static function obtieneingusr($idpusr) {
        global $conexion;
        if ($conexion) {
            $sql_gting = "SELECT ingreso FROM com_usuarios WHERE id = " . $idpusr . ";";
            $rs_gting = mysqli_query($conexion, $sql_gting);
            $fching = mysqli_fetch_assoc($rs_gting);
            return $fching['ingreso'];
        }
    }

    public static function getdatauser($empleado, $empresa, $actualiza, $idntfy) {
        global $conexion;
        if ($conexion) {
            if ($actualiza == TRUE and $idntfy != 'n') {
                $sql_gtname = "SELECT U.id, U.usuario, U.clave, substring(U.usuario,3) as codigo, U.nombre, U.nss, E.descripcion as empresa, U.ingreso, U.email, U.estatus FROM com_usuarios U INNER JOIN com_empresas E ON U.empresa = E.codigo WHERE U.id = '" . $idntfy . "'";
            } else {
                $sql_gtname = "SELECT id,usuario,nombre,ingreso FROM com_usuarios WHERE SUBSTRING(usuario,3) = '" . $empleado . "' AND empresa = '" . $empresa . "'";
            }$rs_gtname = mysqli_query($conexion, $sql_gtname);
            $data = mysqli_fetch_assoc($rs_gtname);
            $rowsnm = mysqli_num_rows($rs_gtname);
            if ($rowsnm > 0) {
                if ($actualiza) {
                    return $data['codigo'] . ',' . $data['nss'] . ',' . utf8_encode($data['nombre']) . ',' . $data['ingreso'] . ',' . $data['email'] . ',' . $data['estatus'] . ',' . $data['clave'] . ',' . $data['usuario'] . ',' . str_replace(",", " ", utf8_encode($data['empresa'])) . ',' . $data['id'];
                } else {
                    return $data['id'] . ',' . utf8_encode($data['nombre']) . ',' . $data['ingreso'] . ',' . substr($data['usuario'], 2);
                }
            }
        }
    }

}

class reportes {
    public static function GeneraEXCEL($ini, $fin, $vemp, $nombre, $cdempl, $perdvcs ,$cmfchnml , $cmfchrngs ,$type, $format) {
        global $conexion;
        if ($conexion) {
            set_time_limit(0);
            error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); date_default_timezone_set('America/Mexico_City');
            define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />'); $directorio = str_replace("includes", "", dirname(__FILE__));
            require_once $directorio . 'Classes/PHPExcel.php'; require_once $directorio . 'Classes/PHPExcel/IOFactory.php';
            if ($format == 'csv') { require_once $directorio . 'Classes/PHPExcel/Writer/CSV.php'; } elseif ($format == 'xls') { require_once $directorio . 'Classes/PHPExcel/Writer/Excel2007.php'; }
            $objPHPExcel = new PHPExcel(); $ttlerpt = $nombre;
            $nombre = str_replace(array(",", '.', ' '), "_", $nombre);
            $nombre = tools::sanear_string($nombre);
            $objPHPExcel->getProperties()->setCreator("PORTAL AM")->setLastModifiedBy("SISTEMAS AM");
            $objPHPExcel->setActiveSheetIndex(0);
            switch ($type) {
                case 'cmanot':
                    if(!empty($cmfchnml) and empty($cmfchrngs)){
                        $dtone = explode("-",$cmfchnml); $cadstring = "= '".$cmfchnml."'"; $ttheader = 'RELACION DE CONSUMOS DEL '.$dtone[2].' de '.tools::obtenermes($dtone[1]).' del '.$dtone[0];
                        $ordered = "U.`nombre`";
                    }elseif(empty($cmfchnml) and !empty($cmfchrngs)){
                        $rangesdt = explode("*",$cmfchrngs);
                        $cadstring = "BETWEEN '".$rangesdt[0]."' AND '".$rangesdt[1]."'";
                        $dateone = explode("-",$rangesdt[0]); $datetwo = explode("-",$rangesdt[1]);
                        $ttheader = 'RELACION DE CONSUMOS DEL '.$dateone[2].' de '.tools::obtenermes($dateone[1]).' del '.$dateone[0].' AL '.$datetwo[2].' de '.tools::obtenermes($datetwo[1]).' del '.$datetwo[0];
                        $ordered = "E.`descripcion`, U.`nombre`, F.`fecha`";
                    }
                    $sql_genera = "SELECT U.`usuario` AS codigo, U.`nombre`, M.`nombre` AS menu, E.`descripcion` AS empresa, F.`fecha`, R.`registro` FROM `com_anotaciones` R, `com_menuporfecha` F, `com_menus` M, `com_usuarios` U, `com_empresas` E WHERE F.`menu` = M.`id` AND R.`menuporfecha` = F.`id` AND R.`usuario` = U.`id` AND U.`empresa` = E.`codigo` AND F.`fecha` ".$cadstring." ORDER BY ".$ordered.";";
                    tools::noRepeat($objPHPExcel, 'EMPLEADOS ANOTADOS EN COMEDOR', 'COMEDOR AM', 'ANOTADOS EN COMEDOR', 'REPORTE COMEDOR ANOTADOS', 'RESUMEN DE ANOTADOS EN COMEDOR');
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 3);
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLEADOS REGISTRADOS EN COMEDOR')
                        ->setCellValue('E1', '.::' . date('d') . '/' . tools::obtenermes(date('m')) . '/' . date('Y').'::.')
                        ->setCellValue('A2',$ttheader)->setCellValue('A4', 'EMPLEADO')->setCellValue('B4', 'NOMBRE')->setCellValue('C4', 'MENU')->setCellValue('D4', 'EMPRESA')
                        ->setCellValue('E4', 'FECHA')->setCellValue('F4', 'REGISTRO');
                    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1'); $objPHPExcel->getActiveSheet()->mergeCells('E1:F1');
                    tools::setUpFont($objPHPExcel,'Calibri Light',9, true, 18, true, PHPExcel_Style_Color::COLOR_BLACK);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFill()->getStartColor()->setARGB('FF69EBC2');
                    break;
                case 'xlscm':
                    $sql_genera = "SELECT U.`usuario` AS codigo, U.`nombre`, COUNT(`precio`) AS cantidad, SUM(`precio`) AS importe FROM `com_anotaciones` R,`com_menuporfecha` F,`com_usuarios` U WHERE R.`menuporfecha` = F.`id`and R.usuario = U.id and U.`empresa` = '".$vemp."' AND F.`fecha` BETWEEN CAST('".$ini."' AS DATE) AND CAST('".$fin."' AS DATE) GROUP BY U.`nombre`;";
                    tools::noRepeat($objPHPExcel,'CONSUMOS EN COMEDOR DEL '.$ini.' AL '.$fin,'COMEDOR AM','CONSUMOS DEL '.$ini.' AL '.$fin,"REPORTE COMEDOR CONSUMOS","CONCENTRADO DE CONSUMOS EN COMEDOR");
                    $mini = explode("-", $ini); $fini = explode("-", $fin);
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 3);
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', $ttlerpt)
                        ->setCellValue('D1', '.::' . date('d') . '/' . tools::obtenermes(date('m')) . '/' . date('Y').'::.')
                        ->setCellValue('A2', 'CONSUMO DE EMPLEADOS EN COMEDOR DEL '.$mini[2].'/'.tools::obtenermes($mini[1]).'/'.$mini[0].' AL '.$fini[2].'/'.tools::obtenermes($fini[1]).'/'.$fini[0])
                        ->setCellValue('A4', 'EMPLEADO')->setCellValue('B4', 'NOMBRE')->setCellValue('C4', 'CANTIDAD')->setCellValue('D4', 'IMPORTE');
                    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1'); $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
                    tools::setUpFont($objPHPExcel,'Calibri Light',9, true, 18, true, PHPExcel_Style_Color::COLOR_BLACK);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
                    break;
                case 'vcsxprd':
                    $sql_genera = "SELECT U.usuario AS codigo, U.nombre AS nombre, P.periodo, P.dias, P.tomados, P.pendientes, P.vigencia FROM vac_periodos P, com_usuarios U WHERE P.usuario = U.id and P.pendientes > 0 and P.periodo <= '".$perdvcs."' And U.empresa = '".$vemp."' And U.estatus = 1 ORDER BY CAST(SUBSTRING(U.usuario, 3) AS unsigned),U.nombre, P.periodo;";
                    tools::noRepeat($objPHPExcel,'REPORTE DE VACACIONES AL PERIODO '.$perdvcs,'VACACIONES AM','VACACIONES AL PERIODO '.$perdvcs,"REPORTE VACACIONES PERIODOS","CONCENTRADO DE PERIODOS VACACIONALES");
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 3);
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', $ttlerpt)
                            ->setCellValue('F1', '.::' . date('d') . '/' . tools::obtenermes(date('m')) . '/' . date('Y').'::.')
                            ->setCellValue('A2', 'REPORTE DE VACACIONES AL PERIODO '.$perdvcs)->setCellValue('A4', 'EMPLEADO')
                            ->setCellValue('B4', 'NOMBRE')->setCellValue('C4', 'PERIODO')->setCellValue('D4', 'DIAS')->setCellValue('E4', 'TOMADOS')
                            ->setCellValue('F4', 'PENDIENTES')->setCellValue('G4', 'VIGENCIA')
                            ->setCellValue('H4','CENTRO')->setCellValue('I4','DEPARTAMENTO');
                    $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); $objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
                    $objPHPExcel->getActiveSheet()->mergeCells('F1:G1');
                    tools::setUpFont($objPHPExcel,'Calibri Light',9, true, 18, true, PHPExcel_Style_Color::COLOR_BLACK);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
                    break;
                case 'vcsxus':
                    $sql_genera = "SELECT S.folio, U.nombre AS nombre, P.periodo, S.estatus, S.vacaciones, S.fecha AS registro FROM `vac_solicitud` S, `com_usuarios` U, `com_empresas` E, `vac_periodos` P WHERE SUBSTRING(U.usuario,3) = '" . $cdempl . "' AND U.empresa = '" . $vemp . "' AND S.usuario = U.id AND U.empresa = E.codigo AND S.periodo = P.id ORDER BY folio DESC;";
                    tools::noRepeat($objPHPExcel,'REPORTE DE VACACIONES DEL EMPLEADO ' . $cdempl,'VACACIONES AM','VACACIONES DEL EMPLEADO ' . $cdempl,'REPORTE VACACIONES EMPLEADO','VACACIONES DEL EMPLEADO');
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 3);
                    $datsysempl = consultas::getdatauser($cdempl, $vemp, FALSE, 'n');
                    if (!empty($datsysempl)) {
                        $nmng = explode(",", $datsysempl); $nameus = $nmng[1]; $ingress = explode("-", $nmng[2]);
                      $objPHPExcel->getActiveSheet()->setCellValue('A1', $ttlerpt)->setCellValue('F1', '.::' . date('d') . '/' . tools::obtenermes(date('m')) . '/' . date('Y').'::.')
                            ->setCellValue('A2', $cdempl . '-' . $nameus . '-' . $ingress[2] . '/' . tools::obtenermes($ingress[1]) . '/' . $ingress[0])->setCellValue('A4', 'FOLIO')
                            ->setCellValue('B4', 'PERIODO')->setCellValue('C4', 'ESTATUS')->setCellValue('D4', 'DIAS')->setCellValue('E4', 'VACACIONES')->setCellValue('F4', 'REGISTRO');
                    $objPHPExcel->getActiveSheet()->mergeCells('A1:E1'); $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
                    tools::setUpFont($objPHPExcel,'Calibri Light',9, true, 18, true, PHPExcel_Style_Color::COLOR_BLACK);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
                    }
                    break;
                case 'vc':
                    $sql_genera = "SELECT S.folio, U.Usuario As empleado, U.Nombre As nombre, P.periodo, Min(V.Fecha) As salida, Count(*) As dias, S.fecha AS registro FROM vac_vacaciones V, vac_solicitud S, com_usuarios U, vac_periodos P WHERE V.Fecha Between '" . $ini . "' And '" . $fin . "' And S.Id = V.Solicitud And U.Id = S.Usuario And P.id = S.periodo And U.empresa = '" . $vemp . "' And S.estatus = 'A' Group By S.Folio, U.Usuario, U.Nombre, P.Periodo ORDER by S.folio DESC;";
                    tools::noRepeat($objPHPExcel,'REPORTE DE VACACIONES',"VACACIONES AM","REPORTE AUTOMATIZADO DE VACACIONES","REPORTE VACACIONES SOLICITUD","CONCENTRADO DE VACACIONES");
                    $dscfini = explode("-", $ini); $dscffin = explode("-", $fin);
                    $dtinic = $dscfini[2] . ' de ' . tools::obtenermes($dscfini[1]) . ' del ' . $dscfini[0];
                    $dtfins = $dscffin[2] . ' de ' . tools::obtenermes($dscffin[1]) . ' del ' . $dscffin[0];
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 3);
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', $ttlerpt)->setCellValue('G1', '.::' . date('d') . '/' . tools::obtenermes(date('m')) . '/' . date('Y').'::.')
                            ->setCellValue('A2', 'Del ' . $dtinic . ' al ' . $dtfins)->setCellValue('A4', 'FOLIO')
                            ->setCellValue('B4', 'CODIGO')->setCellValue('C4', 'NOMBRE')->setCellValue('D4', 'PERIODO')->setCellValue('E4', 'SALIDA')
                            ->setCellValue('F4', 'DIAS')->setCellValue('G4', 'REGISTRO');
                    $objPHPExcel->getActiveSheet()->mergeCells('A1:F1'); $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setSize('10');
                    tools::setUpFont($objPHPExcel,'Calibri Light',9, true, 18, true, PHPExcel_Style_Color::COLOR_BLACK);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
                    break;
                case 'cm':
                    $sql_genera = "SELECT F.fecha, U.`usuario` AS CODIGO, SUM(`precio`) AS IMPORTE FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_usuarios` U ON R.usuario = U.id WHERE U.`empresa` = '" . $vemp . "' AND F.`fecha` BETWEEN CAST('" . $ini . "' AS DATE) AND CAST('" . $fin . "' AS DATE) GROUP BY R.`usuario`;";
                    tools::noRepeat($objPHPExcel,'REPORTE DE CONSUMOS EN COMEDOR',"CONSUMOS EN COMEDOR","REPORTE AUTOMATIZADO DE CONSUMOS EN COMEDOR","REPORTE COMEDOR CONSUMOS","CONCENTRADO DE CONSUMOS");
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CODIGO')->setCellValue('B1', 'CONCEPTO')->setCellValue('C1', 'IMPORTE');
                    break;
            }
            $rs_genera = mysqli_query($conexion, $sql_genera);
            $totales = mysqli_num_rows($rs_genera);
            if ($totales > 0) {
                if ($type == 'vc') {
                    $i = 5;
                    $objPageSetup = new PHPExcel_Worksheet_PageSetup();
                    tools::setUpPage($objPageSetup, PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER, PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT,true,0.64,0.2,0.26,0.52,'Resumen de Vacaciones Autorizadas',$objPHPExcel, $totales, true , 0, '&R&F Página &P / &N', '&R&F Página &P / &N', 'Calibri Light', 9, FALSE);
                }elseif($type=='cmanot'){
                    $i = 5;
                    $objPageSetup = new PHPExcel_Worksheet_PageSetup();
                    tools::setUpPage($objPageSetup, PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER, PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE,true,0.64,0.2,0.26,0.52,'Resumen de Registros del Comedor',$objPHPExcel, $totales, true , 0, '&R&F Página &P / &N', '&R&F Página &P / &N', 'Calibri Light', 9, FALSE);
                }elseif($type=='xlscm'){
                    $i = 5;
                    $objPageSetup = new PHPExcel_Worksheet_PageSetup();
                    tools::setUpPage($objPageSetup, PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER, PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT,true,0.64,0.2,0.26,0.52,'Resumen de Consumos en Comedor',$objPHPExcel, $totales, true , 0, '&R&F Página &P / &N', '&R&F Página &P / &N', 'Calibri Light', 9, TRUE);
                }elseif($type=='vcsxprd'){
                    $i = 5;
                    $objPageSetup = new PHPExcel_Worksheet_PageSetup();
                    tools::setUpPage($objPageSetup, PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER, PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT,true,0.64,0.2,0.26,0.52,'Empleados con Vacaciones Pendientes',$objPHPExcel, $totales, true , 0, '&R&F Página &P / &N', '&R&F Página &P / &N', 'Calibri Light', 9, FALSE);
                } elseif ($type == 'vcsxus') {
                    $i = 5;
                    $objPageSetup = new PHPExcel_Worksheet_PageSetup();
                    tools::setUpPage($objPageSetup, PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER, PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT,true,0.64,0.2,0.29,0.52,'Vacaciones del Empleado ' . $cdempl, $objPHPExcel, $totales, true, 0, '&R&F Página &P / &N', '&R&F Página &P / &N', 'Calibri Light', 10, FALSE);
                } else {
                    $i = 2;
                }
                while ($rw_genera = mysqli_fetch_assoc($rs_genera)) {
                    if ($type == 'cm') {
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, substr($rw_genera["CODIGO"], 2, strlen($rw_genera["CODIGO"])))
                                ->setCellValue('B' . $i, 146)->setCellValue('C' . $i, $rw_genera["IMPORTE"]);
                    }elseif($type=='cmanot'){
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,  substr($rw_genera["codigo"], 2,  strlen($rw_genera["codigo"])))
                                ->setCellValue('B' . $i, utf8_encode($rw_genera["nombre"]))->setCellValue('C' . $i, utf8_encode($rw_genera["menu"]))
                                ->setCellValue('D' . $i, utf8_encode($rw_genera["empresa"]))->setCellValue('E' . $i, $rw_genera["fecha"])
                                ->setCellValue('F' . $i, $rw_genera["registro"]);
                    }elseif($type=='xlscm'){
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,  substr($rw_genera["codigo"], 2,  strlen($rw_genera["codigo"])))
                                ->setCellValue('B' . $i, utf8_encode($rw_genera["nombre"]))->setCellValue('C' . $i, $rw_genera["cantidad"])
                                ->setCellValue('D' . $i, $rw_genera["importe"]);
                    }elseif($type=='vcsxprd'){
                    	$ctrPDprP = SqlSrv::getInstance()->select("
							SELECT L.centro, C.nomdepto
							FROM Llaves L, centros C, horas_laboradas H, tabulador T, empleados E
							WHERE L.empresa = ? And
								L.codigo = ? And
								C.empresa = L.empresa And
								C.centro = L.centro And
								H.empresa = L.empresa And
								H.codigo = L.codigo And
								H.ayo_operacion = YEAR(GETDATE()) And
								H.periodo IN (
									SELECT MAX(HL.periodo)
									FROM horas_laboradas HL
									WHERE HL.empresa = L.empresa And HL.codigo = L.codigo And HL.ayo_operacion = YEAR(GETDATE())) And
								T.ocupacion = H.ocupacion And
								T.empresa = L.empresa And
								E.codigo = L.codigo And
								E.empresa = L.empresa",[$vemp,substr($rw_genera["codigo"], 2, strlen($rw_genera["codigo"]))]);
						if($rCentro = $ctrPDprP->fetchObject()){
						$objPHPExcel->getActiveSheet()->getStyle('H'. $i)->setQuotePrefix(true);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,  substr($rw_genera["codigo"], 2,  strlen($rw_genera["codigo"])))
                                ->setCellValue('B' . $i, utf8_encode($rw_genera["nombre"]))->setCellValue('C' . $i, $rw_genera["periodo"])
                                ->setCellValue('D' . $i, $rw_genera["dias"])->setCellValue('E' . $i, $rw_genera["tomados"])
                                ->setCellValue('F' . $i, $rw_genera["pendientes"])->setCellValue('G' . $i, $rw_genera["vigencia"])
                                ->setCellValue('I' . $i, trim($rCentro->nomdepto))
                                ->setCellValueExplicit('H' . $i, trim((string) $rCentro->centro), PHPExcel_Cell_DataType::TYPE_STRING);
						}
                    } elseif ($type == 'vc') {
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, (string) $rw_genera["folio"], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, substr($rw_genera["empleado"], 2, strlen($rw_genera["empleado"])))
                                ->setCellValue('C' . $i, utf8_encode($rw_genera["nombre"]))->setCellValue('D' . $i, $rw_genera["periodo"]);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $rw_genera["salida"])->setCellValue('F' . $i, $rw_genera["dias"])->setCellValue('G' . $i, $rw_genera["registro"]);
                    } elseif ($type == 'vcsxus') {
                        $diasvcs = explode(",", $rw_genera["vacaciones"]);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, (string) $rw_genera["folio"], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $rw_genera["periodo"])
                                ->setCellValue('C' . $i, $rw_genera["estatus"])->setCellValue('D' . $i, count($diasvcs))->setCellValue('E' . $i, $rw_genera["vacaciones"])
                                ->setCellValue('F' . $i, $rw_genera["registro"]);
                    }
                    $i++;
                }
                if ($format == 'csv') {
                    $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
                    $objWriter->setDelimiter(',');
                    $objWriter->setEnclosure('');
                    $objWriter->setLineEnding("\r\n");
                    $objWriter->setSheetIndex(0);
                } elseif ($format == 'xls') {
                    if ($type == 'vc') {
                        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                        }
                        tools::fillColors($objPHPExcel,4, 'FF69EBC2', 'FFE2FFE2', 'FFFFFFFF');
                        $ti = explode("-", $ini); $tf = explode("-", $fin);
                        $objPHPExcel->getActiveSheet()->setTitle('VAUTH_'.$ini.'_'.$fin)->setAutoFilter('A4:' . $objPHPExcel->getActiveSheet()->getHighestColumn() . $objPHPExcel->getActiveSheet()->getHighestRow());
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(false)->setWidth('10');
                        tools::setClAlgMnt($objPHPExcel,'E1:G1',PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        tools::setClAlgMnt($objPHPExcel,'D4:D'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'F4:F'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'B4:B'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
                        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,5);
                    }elseif($type=='cmanot'){
                        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                        }
                        $objPHPExcel->getActiveSheet()->setTitle('ANCOM_'.$ini.'_'.$fin)->setAutoFilter('A4:' . $objPHPExcel->getActiveSheet()->getHighestColumn() . $objPHPExcel->getActiveSheet()->getHighestRow());
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(false)->setWidth('9');
                        tools::setClAlgMnt($objPHPExcel,'E1:F1',PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        tools::setClAlgMnt($objPHPExcel,'A4:A'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(33);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(34);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
                        $objPHPExcel->getActiveSheet()->getStyle('C5:C'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
                        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,5);
                    }elseif($type=='xlscm'){
                        tools::fillColors($objPHPExcel,4, 'FF69EBC2', 'FFE2FFE2', 'FFFFFFFF');
                        $objPHPExcel->getActiveSheet()->setTitle('CNSMS_'.$ini.'_'.$fin)->setAutoFilter('A4:' . $objPHPExcel->getActiveSheet()->getHighestColumn() . $objPHPExcel->getActiveSheet()->getHighestRow());
                        tools::setClAlgMnt($objPHPExcel,'A4:A'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'C4:C'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'D4:D'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'D1',PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(42);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.($totales+5),'=SUM(C5:C'.($totales+4).')');
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.($totales+5),'=SUM(D5:D'.($totales+4).')');
                        $objPHPExcel->getActiveSheet()->getStyle('C'.($totales+5))->getNumberFormat()->setFormatCode('#,##0');
                        tools::setClAlgMnt($objPHPExcel,'C'.($totales + 5),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('D'.($totales+5))->getNumberFormat()->setFormatCode('_-$* #,##0.00_-;-$* #,##0.00_-;_-$* "-"??_-;_-@_-');
                        $objPHPExcel->getActiveSheet()->getStyle('D5:D'.($totales+4))->getNumberFormat()->setFormatCode('_-$* #,##0.00_-;-$* #,##0.00_-;_-$* "-"??_-;_-@_-');
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
                        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,5);
                    }elseif($type=='vcsxprd'){
                        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                        }
                        tools::fillColors($objPHPExcel,4, 'FF69EBC2', 'FFE2FFE2', 'FFFFFFFF');
                        $objPHPExcel->getActiveSheet()->setTitle('VACACIONES_AL_PERIODO_'.$perdvcs)->setAutoFilter('A4:' . $objPHPExcel->getActiveSheet()->getHighestColumn() . $objPHPExcel->getActiveSheet()->getHighestRow());
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(false)->setWidth('10');
                        tools::setClAlgMnt($objPHPExcel,'F1',PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        tools::setClAlgMnt($objPHPExcel,'A4:A'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'C4:C'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'D4:D'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'E4:E'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'F4:F'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('G5:G'.($totales + 4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
                        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,5);
                    } elseif ($type == 'vcsxus') {
                        tools::fillColors($objPHPExcel,4, 'FF69EBC2', 'FFE2FFE2', 'FFFFFFFF');
                        $objPHPExcel->getActiveSheet()->setTitle('VACS-' . $cdempl);
                        tools::setClAlgMnt($objPHPExcel,'B4:B'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'C4:C'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'D4:D'.($totales + 4),PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        tools::setClAlgMnt($objPHPExcel,'F1',PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('E5:E'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
                    }
                    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                }
                if ($type == 'cm') {
                    $anexo = '_COMEDOR' . $ini . '-' . $fin;
                }elseif($type=='cmanot'){
                    $anexo = 'ANOTADOS_COMEDOR';
                    $nombre = 'EMPLEADOS';
                }elseif($type=='vcsxprd'){
                    $anexo = 'VACACIONES_AL_'.$perdvcs;
                }elseif($type=='xlscm'){
                    $anexo = 'COMEDOR_'.$ini.'_'.$fin;
                } elseif ($type == 'vc') {
                    $anexo = 'VACACIONES_AUTORIZADAS';
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
                } elseif ($type == 'vcsxus') {
                    $anexo = 'VACACIONES_' . $cdempl;
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $objPHPExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(-1);
                }
                if ($nombre == 'EDITORIAL_MARTINICA_SA_DE_CV') {
                    $nombre = 'EMA';
                }elseif ($nombre == 'COMPANIA_PERIODISTICA_DE_CELAYA_SA_DE_CV') {
                    $nombre = 'CIA_CELAYA';
                }
                $objWriter->save($directorio . "/excel/" . utf8_decode($nombre) . "_" . $anexo . "." . (string) $format);
                tools::download_file($directorio . "/excel/" . $nombre . "_" . $anexo . "." . (string) $format);
                $file = $directorio . "/excel/" . $nombre . "_" . $anexo . "." . (string) $format;
                unlink($file);
                if ($type == 'cm') {
                    $sql_logrpt = "INSERT INTO `aplicaciones`.`com_logreportes` (`id`, `host`, `ip`, `usuario`, `generado`, `fechaini`, `fechafin`, `empresa`) VALUES (NULL, '" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "', '" . $_SERVER['REMOTE_ADDR'] . "', '" . $_SESSION['idloguser'] . "', now(), '" . $ini . "', '" . $fin . "', '" . $vemp . "');";
                    mysqli_query($conexion, $sql_logrpt);
                }
                exit;
            } else {
                header("X-UA-Compatible: IE=edge,chrome=1");
                if ($type == 'cm') {
                    $namearc = 'No hay registros de consumos del ' . $ini . ' al ' . $fin . ' en la empresa ' . $nombre;
                }elseif($type=='cmanot'){
                    $namearc = 'No hay registros de empleados anotados en esas fechas!';
                }elseif($type=='xlscm'){
                    $namearc = 'No hay registros de consumos del ' . $ini . ' al ' . $fin . ' en la empresa ' . $nombre;
                } elseif ($type == 'vc') {
                    $namearc = 'No hay registros de vacaciones del ' . $ini . ' al ' . $fin . ' en la empresa ' . $nombre;
                } elseif ($type == 'vcsxus'){
                    $namearc = 'El empleado ' . $cdempl . ' no existe en la empresa ' . $nombre . '!';
                } elseif ($type == 'vcsxprd'){
                    $namearc = 'No hay registros en la empresa ' . $nombre . ' al periodo ' . $perdvcs . '!';
                }
                echo '<script type="text/javascript">alert("' . $namearc . '");</script>';
            }
        }
    }

    public static function GeneraPDF($encabezado, $tpie, $cuerpo, $salida, $orientacion, $mglft, $mgrght, $mgitop, $mgtop, $solicitud){
        $mpdf = new mPDF('utf-8', array(215.9,279.4) , 0, '', $mglft, $mgrght, $mgitop, 9, $mgtop, 4.5, $orientacion);
        $mpdf->cacheTables = true; $mpdf->simpleTables = true; $mpdf->packTableData = true;
        $mpdf->SetHTMLHeader($encabezado);
        if(!$solicitud){$mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; border-bottom: 1px solid #000000; font-family: freesans; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr><td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-M-Y}</span></td><td width="33%" align="center" style="font-weight: bold; font-style: italic;">'.$tpie.'</td><td width="33%" style="text-align: right; ">{PAGENO}/{nbpg}</td></tr></table>');
        }else{ $mpdf->SetHTMLFooter(''); }
        $mpdf->WriteHTML($cuerpo); $mpdf->Output($salida. '.pdf', 'D');
    }

}

class tools {
    public static function retAITbl($tabla){
        global $conexion;
        if($conexion){
            $sql_rtainc = "SELECT (MAX(id)+1) AS MAXIMO FROM ".$tabla;
            $rs_rtainc = mysqli_query($conexion, $sql_rtainc);
            $rw_rtainc = mysqli_fetch_assoc($rs_rtainc);
            $maximo = $rw_rtainc['MAXIMO'];
            $sql_stai = "ALTER TABLE ".$tabla." AUTO_INCREMENT = ".$maximo;
            $rs_stai = mysqli_query($conexion, $sql_stai);
        }
    }
    public static function PrntCmbRsv($bool,$usrs){
        global $conexion;
        if($conexion){
            $cdusrs = $usrs == 'all' ? 'tipo = 5 or tipo = 1' : 'tipo = 1';
            $sql_PCR = "SELECT * FROM `com_usuarios` WHERE ".$cdusrs." AND estatus = 1;";
            $rs_PCR = mysqli_query($conexion, $sql_PCR);
            $htmlCmb = "<option value=''>Elige la opcion...</option>";
            while ($rw_PCR = mysqli_fetch_assoc($rs_PCR)){
                $htmlCmb .= "<option value='".(($bool) ? ($rw_PCR['id']) : (utf8_encode($rw_PCR['nombre'])))."'>".utf8_encode($rw_PCR['nombre'])."</option>";
            }
            return trim($htmlCmb);
        }
    }
    public static function cdcSn(){
        if (isset($_SESSION["ultimoAcceso"])) {
            $strStart = $_SESSION["ultimoAcceso"];
            $strEnd = date('Y-m-d H:i:s');
            $dteStart = new DateTime($strStart);
            $dteEnd = new DateTime($strEnd);
            $dteDiff = $dteStart->diff($dteEnd);
            if(intval($dteDiff->format("%I"))>=30){
               global $conexion; if($conexion){mysqli_close($conexion);}
                unset($_SESSION["ultimoAcceso"]); unset($_SESSION); session_unset();
                $_SESSION=array(); session_regenerate_id(); session_destroy();
                $session_cookie_params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 24 * 3600, $session_cookie_params['path'], $session_cookie_params['domain'], $session_cookie_params['secure'], $session_cookie_params['httponly']);
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false); header("Pragma: no-cache");
                clearstatcache();
                echo "<script languaje=\"javascript\">alert('La sesion ha caducado por favor vuelve a ingresar tu usuario y clave de acceso!'); parent.top.location.replace('/portal/');</script>";
            }else{
                if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                    # Ejecuta si la petición es a través de AJAX.
                }else{
                    $_SESSION["ultimoAcceso"] = date("Y-m-d H:i:s");
                }
            }
        }
    }

    public static function redirApp($dir){
        if($dir=='portal'){
            echo "<script languaje=\"javascript\">history.pushState(null, null, '#Again-No-back-button'); parent.top.location.replace('/portal/#Again-No-back-button');</script>";
            exit(0);
        }else{
            echo "<script languaje=\"javascript\">history.pushState(null, null, '#Again-No-back-button'); parent.top.location.replace('/portal/#Again-No-back-button');</script>";
            exit(0);
        }
    }
    static function noDupMnu($gpo,$fl){
        switch ($gpo){
            case '0':
                $str = '<li class="sub-menu"><a href="javascript:;"><i class="fa fa-support"></i><span>Vacaciones</span></a><ul class="sub" id="vacsub">' . ($fl == 'cat_fest_rh.php' ? '<li class="active"><a href="#">' : '<li><a href="cat_fest_rh.php">') . '<i class="fa fa-briefcase"></i>Catalogos</a></li>' . ($fl == 'conciliar.php' ? '<li class="active"><a href="#">' : '<li><a href="conciliar.php">') . '<i class="fa fa-clock-o"></i>Conciliar</a></li>' . ($fl == 'sol_vacs_rh.php' ? '<li class="active"><a href="#">' : '<li><a href="sol_vacs_rh.php">') . '<i class="fa fa-calendar-check-o"></i>Solicitudes</a></li>' . ($fl == 'reports_vacs.php' ? '<li class="active"><a href="#">' : '<li><a href="reports_vacs.php">') . '<i class="fa fa-bar-chart-o"></i>Reportes</a></li></ul><li class="sub-menu"><a href="javascript:;" id="fondocom"><i class="fa fa-cutlery"></i><span>Comedor</span></a><ul class="sub" id="comsub">' . ($fl == 'repormenus.php' ? '<li class="active"><a href="#">' : '<li><a href="repormenus.php">') . '<i class="fa fa-sellsy"></i>Reportes</a></li>' . ($fl == 'commenus.php' ? '<li class="active"><a href="#">' : '<li><a href="commenus.php">') . '<i class="fa fa-th"></i>Menu de la semana</a></li>' . ($fl == 'carg_consum.php' ? '<li class="active"><a href="#">' : '<li><a href="carg_consum.php">') . '<i class="fa fa-plus-square"></i>Registrar Consumo</a></li>' . ($fl == 'anotados.php' ? '<li class="active"><a href="#">' : '<li><a href="anotados.php">') . '<i class="fa fa-leanpub"></i>Usuarios anotados</a></li>' . ($fl == 'sugerencias_coc.php' ? '<li class="active"><a href="#">' : '<li><a href="sugerencias_coc.php">') . '<i class="fa fa-lightbulb-o"></i>Sugerencias</a></li></ul><li class="sub-menu"><a href="javascript:;" id="fondocap"><i class="fa fa-users"></i><span>Capital Humano</span></a><ul class="sub" id="capsub">' . ($fl == 'upload_img.php' ? '<li class="active"><a href="#">' : '<li><a href="upload_img.php">') . '<i class="fa fa-comments-o"></i>Avisos</a></li>' . ($fl == 'system_users.php' ? '<li class="active"><a href="#">' : '<li><a href="system_users.php">') . '<i class="fa fa-user"></i>Usuarios</a></li></ul></li><li class="sub-menu"><a href="javascript:;" id="fondosis"><i class="fa fa-tachometer"></i><span>Sistemas</span></a><ul class="sub" id="sissub">' . ($fl == 'inv_inventario.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_inventario.php">') . '<i class="fa fa-qrcode"></i>Inventario</a></li>' . ($fl == 'inv_services.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_services.php">') . '<i class="fa fa-server"></i>Servicios</a></li>' . ($fl == 'inv_programados.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_programados.php">') . '<i class="fa fa-calendar-check-o"></i>Programados</a></li>' . ($fl == 'inv_soporte.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_soporte.php">') . '<i class="fa fa-headphones"></i>Soporte</a></li></ul></li>';
                break;
            case '1':
                $str = '<li class="sub-menu"><a href="javascript:;" id="fondosis"><i class="fa fa-tachometer"></i><span>Sistemas</span></a><ul class="sub" id="sissub">' . ($fl == 'inv_inventario.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_inventario.php">') . '<i class="fa fa-qrcode"></i>Inventario</a></li>' . ($fl == 'inv_services.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_services.php">') . '<i class="fa fa-server"></i>Servicios</a></li>' . ($fl == 'inv_programados.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_programados.php">') . '<i class="fa fa-calendar-check-o"></i>Programados</a></li>' . ($fl == 'inv_soporte.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_soporte.php">') . '<i class="fa fa-headphones"></i>Soporte</a></li>' . ($fl == 'inv_registros.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_registros.php">') . '<i class="fa fa-address-card-o"></i>Registros</a></li></ul></li>';
                break;
            case '2':
                $str = '<li class="sub-menu"><a href="javascript:;"><i class="fa fa-support"></i><span>Vacaciones</span></a><ul class="sub" id="vacsub">' . ($fl == 'cat_fest_rh.php' ? '<li class="active"><a href="#">' : '<li><a href="cat_fest_rh.php">') . '<i class="fa fa-briefcase"></i>Catalogos</a></li>' . ($fl == 'conciliar.php' ? '<li class="active"><a href="#">' : '<li><a href="conciliar.php">') . '<i class="fa fa-clock-o"></i>Conciliar</a></li>' . ($fl == 'sol_vacs_rh.php' ? '<li class="active"><a href="#">' : '<li><a href="sol_vacs_rh.php">') . '<i class="fa fa-calendar-check-o"></i>Solicitudes</a></li>' . ($fl == 'reports_vacs.php' ? '<li class="active"><a href="#">' : '<li><a href="reports_vacs.php">') . '<i class="fa fa-bar-chart-o"></i>Reportes</a></li></ul><li class="sub-menu"><a href="javascript:;" id="fondocom"><i class="fa fa-cutlery"></i><span>Comedor</span></a><ul class="sub" id="comsub">' . ($fl == 'repormenus.php' ? '<li class="active"><a href="#">' : '<li><a href="repormenus.php">') . '<i class="fa fa-sellsy"></i>Reportes</a></li>' . ($fl == 'commenus.php' ? '<li class="active"><a href="#">' : '<li><a href="commenus.php">') . '<i class="fa fa-th"></i>Menu de la semana</a></li>' . ($fl == 'anotados.php' ? '<li class="active"><a href="#">' : '<li><a href="anotados.php">') . '<i class="fa fa-leanpub"></i>Usuarios anotados</a></li></ul><li class="sub-menu"><a href="javascript:;" id="fondocap"><i class="fa fa-users"></i><span>Capital Humano</span></a><ul class="sub" id="capsub">' . ($fl == 'upload_img.php' ? '<li class="active"><a href="#">' : '<li><a href="upload_img.php">') . '<i class="fa fa-comments-o"></i>Avisos</a></li>' .($fl == 'cumpleanios.php' ? '<li class="active"><a href="#">' : '<li><a href="cumpleanios.php">') . '<i class="fa fa-birthday-cake"></i>Cumpleaños</a></li>'.($fl == 'system_users.php' ? '<li class="active"><a href="#">' : '<li><a href="system_users.php">') . '<i class="fa fa-user"></i>Usuarios</a></li></ul></li>';
                break;
            case '3':
                $str = '<li class="sub-menu"><a href="javascript:;" id="fondocom"><i class="fa fa-cutlery"></i><span>Comedor</span></a><ul class="sub" id="comsub">' . ($fl == 'commenus.php' ? '<li class="active"><a href="#">' : '<li><a href="commenus.php">') . '<i class="fa fa-th"></i>Menu de la semana</a></li>' . ($fl == 'carg_consum.php' ? '<li class="active"><a href="#">' : '<li><a href="carg_consum.php">') . '<i class="fa fa-plus-square"></i>Registrar Consumo</a></li>' . ($fl == 'anotados.php' ? '<li class="active"><a href="#">' : '<li><a href="anotados.php">') . '<i class="fa fa-leanpub"></i>Usuarios anotados</a></li>' . ($fl == 'sugerencias_coc.php' ? '<li class="active"><a href="#">' : '<li><a href="sugerencias_coc.php">') . '<i class="fa fa-lightbulb-o"></i>Sugerencias</a></li></ul></li>';
                break;
            case '4':
                $str = '<li class="sub-menu"><a href="javascript:;" id="fondovac"><i class="fa fa-support"></i><span>Vacaciones</span></a><ul class="sub" id="vacsub">' . ($fl == 'prog_vacs.php' ? '<li class="active">' : '<li>') . '<a style="cursor: pointer;" id="shwplts"><i class="fa fa-calendar"></i>Programar</a></li>' . ($fl == 'my_vacs.php' ? '<li class="active"><a href="#">' : '<li><a href="my_vacs.php">') . '<i class="fa fa-calendar-check-o"></i>Mis Solicitudes</a></li>' . ($fl == 'consult_vacs.php' ? '<li class="active"><a href="#">' : '<li><a href="consult_vacs.php">') . '<i class="fa fa-search"></i>Consultar</a></li></ul><li class="sub-menu"><a href="javascript:;" id="fondocom"><i class="fa fa-cutlery"></i><span>Comedor</span></a><ul class="sub" id="comsub">' . ($fl == 'commenus.php' ? '<li class="active"><a href="#">' : '<li><a href="commenus.php">') . '<i class="fa fa-th"></i>Menu de la semana</a></li>' . ($fl == 'misconsumos.php' ? '<li class="active"><a href="#">' : '<li><a href="misconsumos.php">') . '<i class="fa fa-cube"></i>Mis consumos</a></li>' . ($fl == 'sugerencias.php' ? '<li class="active"><a href="#">' : '<li><a href="sugerencias.php">') . '<i class="fa fa-envelope-o"></i>Buzon/sugerencias</a></li></ul><li class="sub-menu"><a href="javascript:;" id="fondofmts"><i class="fa fa-stack-overflow"></i><span>Formatos</span></a><ul class="sub" id="bensfmts">' . ($fl == 'fpaternidad.php' ? '<li class="active"><a href="#">' : '<li><a href="fpaternidad.php">') . '<i class="fa fa-drivers-license-o"></i>Paternidad</a></li>' . ($fl == 'fmatrimonio.php' ? '<li class="active"><a href="#">' : '<li><a href="fmatrimonio.php">') . '<i class="fa fa-couple"></i>Matrimonio</a></li></ul></li><li class="sub-menu"><a href="javascript:;" id="fondoben"><i class="fa fa-diamond"></i><span>Beneficios</span></a><ul class="sub" id="bensub">' . ($fl == 'cuponeras.php' ? '<li class="active"><a href="#">' : '<li><a href="cuponeras.php">') . '<i class="fa fa-ticket"></i>Cuponeras</a></li></ul></li>';
                break;
            case '5':
                $str = '<li class="sub-menu"><a href="javascript:;" id="fondosis"><i class="fa fa-tachometer"></i><span>Sistemas</span></a><ul class="sub" id="sissub">' . ($fl == 'inv_inventario.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_inventario.php">') . '<i class="fa fa-qrcode"></i>Inventario</a></li>' . ($fl == 'inv_services.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_services.php">') . '<i class="fa fa-server"></i>Servicios</a></li>' . ($fl == 'inv_programados.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_programados.php">') . '<i class="fa fa-calendar-check-o"></i>Programados</a></li>' . ($fl == 'inv_soporte.php' ? '<li class="active"><a href="#">' : '<li><a href="inv_soporte.php">') . '<i class="fa fa-headphones"></i>Soporte</a></li></ul></li>';
                break;
            default :
                $str = '<li><a href="#"><i class="fa fa-support"></i><span>Vacaciones</span></a></li><li>' . ($fl == 'comedor.php' ? '<a class="active" href="#">' : '<a href="comedor.php">') . '<i class="fa fa-cutlery"></i><span>Comedor</span></a></li><li><a href="#"><i class="fa fa-users"></i><span>Capital Humano</span></a></li><li class="sub-menu"><a href="javascript:;" id="fondoben"><i class="fa fa-diamond"></i><span>Beneficios</span></a><ul class="sub" id="bensub">' . ($fl == 'cuponeras.php' ? '<li class="active"><a href="#">' : '<li><a href="cuponeras.php">') . '<i class="fa fa-ticket"></i>Cuponeras</a></li></ul></li>';
                break;
        }
        return $str;
    }
    function validGroups($archivo, $grupo){
        $dract = explode("\\",getcwd()); $final = end($dract); $array = array();
            switch ($grupo){
                    case '0':
                        return true;
                        break;
                    case '1':
                        array_push($array, "inv_inventario.php", "inv_services.php", "inv_programados.php", "inv_soporte.php","inv_registros.php");
                        break;
                    case '2':
                        array_push($array, "cumpleanios.php","cat_fest_rh.php", "conciliar.php", "sol_vacs_rh.php", "reports_vacs.php", "repormenus.php", "commenus.php", "anotados.php", "upload_img.php", "system_users.php");
                        break;
                    case '3':
                        array_push($array, "commenus.php", "carg_consum.php", "anotados.php", "sugerencias_coc.php");
                        break;
                    case '4':
                        array_push($array, "fpaternidad.php", "fmatrimonio.php", "prog_vacs.php", "my_vacs.php", "consult_vacs.php", "commenus.php", "misconsumos.php", "sugerencias.php");
                        break;
                    case '5':
                        array_push($array, "inv_inventario.php", "inv_services.php", "inv_programados.php", "inv_soporte.php");
                        break;
                }

        $diract = $final == 'portal' ? glob("assets/includes/*.php") : glob("*.php");
        foreach($diract as $nfile){
            array_push($array, trim(basename($nfile)));
        }
       if(!in_array($archivo,$array) and $archivo != 'index.php' and $archivo != 'closer.php' and $archivo != 'cuponeras.php' and $grupo != '0'){
            return false;
        }else{
            return true;
        }
    }
    public static function appMenu($group){
        $da = explode("/", $_SERVER['SCRIPT_NAME']); $df = end($da); $ret = ''; $inicio = '<li class="mt">'.($df == 'index.php' ? '<a class="active" href="#">' : '<a href="/portal/">').'<i class="fa fa-home"></i><span>Inicio</span></a></li>';
        $help = '<li class="sub-menu"><a href="javascript:;" ><i class="fa fa-question-circle"></i><span>Ayuda</span></a><ul class="sub"><li><a class="help" href="assets/ayuda/eat_support.pdf" target="_blank"><i class="fa fa-coffee"></i> Manual Comedor</a></li><li><a class="help" href="assets/ayuda/holiday_support.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> Manual Vacaciones</a></li><li><a class="help" href="assets/ayuda/slack_support.pdf" target="_blank"><i class="fa fa-files-o"></i> Manual Slack</a></li><li><a class="help" href="assets/ayuda/Intranet.pdf" target="_blank"><i class="fa fa-files-o"></i> Intranet</a></li><li><a class="help" href="assets/ayuda/Publicidad.pdf" target="_blank"><i class="fa fa-files-o"></i> Publicidad</a></li></ul></li>';
        $ret .= tools::noDupMnu($group, $df);
        return $inicio.$ret.$help;
    }
    public static function setClAlgMnt($objexcel,$range, $alignment){
              $objexcel->getActiveSheet()->getStyle($range)->getAlignment()->setHorizontal($alignment);
    }

    public static function fillColors($objexcel,$first, $clenc, $clbs, $clalt){
                $highestRow = $objexcel->getActiveSheet()->getHighestRow();
                $highestColumn = $objexcel->getActiveSheet()->getHighestColumn();
                    for ($row = $first; $row < $highestRow + 1; $row++) {
                        if ($row == $first || $row == $highestRow + 1) {
                            $color = $clenc;
                        } elseif ($row % 2 == 0) {
                            $color = $clalt;
                        } else {
                            $color = $clbs;
                        }
                        $objexcel->getActiveSheet()->getStyle('A' . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $objexcel->getActiveSheet()->getStyle('A' . $row)->getFill()->getStartColor()->setARGB($color);
                        $objexcel->getActiveSheet()->duplicateStyle($objexcel->getActiveSheet()->getStyle('A' . $row), 'B' . $row . ':' . $highestColumn . $row);
                    }
    }

    public static function setUpFont($objexcel, $fmclms, $szclms, $bldclms, $szttl, $bldttl, $clttl){
        $objexcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB($clttl);
        $objexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize($szttl)->setBold($bldttl);
        $objexcel->getActiveSheet()->getStyle('A4:' . $objexcel->getActiveSheet()->getHighestColumn() . $objexcel->getActiveSheet()->getHighestRow())
            ->getFont()->setName($fmclms)->setSize($szclms)->setBold($bldclms);
    }

    public static function setUpPage($objpage,$size,$orientation,$cph,$tpmg, $rgmg, $lfmg, $btmg, $header, $objexcel, $rowsbdy, $sftp, $sfth, $oddft, $evenft, $fmbdy, $szbody, $accounting){
                $objpage->setPaperSize($size);
                $objpage->setOrientation($orientation);
                $objpage->setHorizontalCentered($cph);
                $objexcel->getActiveSheet()->getPageMargins()->setTop($tpmg)->setRight($rgmg)->setLeft($lfmg)->setBottom($btmg);
                $objexcel->getActiveSheet()->getHeaderFooter()->setOddHeader($header);
                $onemore = $accounting == true ? 5 : 4;
                $objpage->setPrintArea('A1:' . $objexcel->getActiveSheet()->getHighestColumn() . ($rowsbdy + $onemore));
                $objexcel->getActiveSheet()->getPageSetup()->setFitToPage($sftp);
                $objexcel->getActiveSheet()->getPageSetup()->setFitToHeight($sfth);
                $objexcel->getActiveSheet()->setPageSetup($objpage);
                $objexcel->getActiveSheet()->getHeaderFooter()->setOddFooter($oddft);
                $objexcel->getActiveSheet()->getHeaderFooter()->setEvenFooter($evenft);
                $objexcel->getActiveSheet()->getStyle('A4:' . $objexcel->getActiveSheet()->getHighestColumn() . ($rowsbdy + 4))
                            ->getFont()->setName($fmbdy)->setSize($szbody);
    }

    public static function noRepeat($object, $setTitle, $setSubject, $setDescription, $setKeywords, $setCategory){
                $object->getProperties()->setTitle($setTitle)->setSubject($setSubject)->setDescription($setDescription)
                            ->setKeywords($setKeywords)->setCategory($setCategory);
    }

    public static function antiguedad($stringr, $prf) {
        $datetime1 = new DateTime($stringr);
        $datetime2 = new DateTime("now");
        $interval = $datetime1->diff($datetime2);
        $antg = $interval->format('%y');
        $mses = $interval->format('%m');
        if ($antg < 1 and $prf == 1) {
            $antg = $interval->format('%m meses %d dias');
        } elseif ($antg >= 1 and $prf == 1) {
            if ($mses == 0) {
                $antg = $interval->format('%y a&ntilde;o(s) %d dia(s)');
            } else {
                $antg = $interval->format('%y a&ntilde;o(s) %m mes(es)');
            }
        }return $antg;
    }

    public static function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }return $output;
    }

    public static function leermascom($lcadenadsc) {
        if (strlen($lcadenadsc) > 30) {
            $desctr = substr($lcadenadsc, 0, 30);
            return utf8_encode($desctr);
        } else {
            $desctr = $lcadenadsc;
            return utf8_encode($desctr);
        }
    }

    public static function obtenermes($valorm) {
        switch ($valorm) {
            case 1:$vm = 'Enero';
                break;
            case 2:$vm = 'Febrero';
                break;
            case 3:$vm = 'Marzo';
                break;
            case 4:$vm = 'Abril';
                break;
            case 5:$vm = 'Mayo';
                break;
            case 6:$vm = 'Junio';
                break;
            case 7:$vm = 'Julio';
                break;
            case 8:$vm = 'Agosto';
                break;
            case 9:$vm = 'Septiembre';
                break;
            case 10:$vm = 'Octubre';
                break;
            case 11:$vm = 'Noviembre';
                break;
            case 12:$vm = 'Diciembre';
                break;
            default:
                $vm = '';
                break;
        }return $vm;
    }

    public static function reducemes($strm) {
        switch ($strm) {
            case 'Enero':$strm = 'Ene';
                break;
            case 'Febrero':$strm = 'Feb';
                break;
            case 'Marzo':$strm = 'Mar';
                break;
            case 'Abril':$strm = 'Abr';
                break;
            case 'Mayo':$strm = 'May';
                break;
            case 'Junio':$strm = 'Jun';
                break;
            case 'Julio':$strm = 'Jul';
                break;
            case 'Agosto':$strm = 'Ago';
                break;
            case 'Septiembre':$strm = 'Sep';
                break;
            case 'Octubre':$strm = 'Oct';
                break;
            case 'Noviembre':$strm = 'Nov';
                break;
            case 'Diciembre':$strm = 'Dic';
                break;
            default:
                $strm = '';
                break;
        }return $strm;
    }

    public static function retornadia($valor) {
        switch ($valor) {
            case 0:$dia = 'Domingo';
                break;
            case 1:$dia = 'Lunes';
                break;
            case 2:$dia = 'Martes';
                break;
            case 3:$dia = 'Miercoles';
                break;
            case 4:$dia = 'Jueves';
                break;
            case 5:$dia = 'Viernes';
                break;
            case 6:$dia = 'Sabado';
                break;
            default:
                $dia = '';
                break;
        }return $dia;
    }

    public static function download_file($archivo, $downloadfilename = null) {
        if (file_exists($archivo)) {
            $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $downloadfilename);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo));
            ob_clean();
            flush();
            readfile($archivo);
        }
    }

    public static function sanear_string($string) {
        $string = trim($string);
	$string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
	$string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
	$string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
	$string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
	$string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
	$string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string);
	$string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "< ", ";", ",", ":", ".", " "), '', $string);
        return $string;
    }

    public static function prntmdledtusers() {
        $data_modal = '<div class="modal fade" id="mdledtusrs" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button id="crcn_edus" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="myModalLabel"><i class="fa fa-table"></i> Datos del usuario</h4></div><div class="modal-body" id="dtusrmdl" style="padding-top:7px; padding-bottom:15px;"></div><div class="modal-footer"><button id="edt-usrs" class="btn btn-info"><i class="fa fa-pencil"></i> Editar</button>&nbsp;<button class="btn btn-danger" id="canc-edt" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></div></div></div></div>';
        return $data_modal;
    }

    public static function prntmdlpltcs() {
        $contenido_modal = '<div class="modal fade" id="mpoltcs" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> INFORMACI&Oacute;N</h4></div><div id="lbpv" class="modal-body" style="max-height: 400px; overflow-y: auto;"><div class="row"><div class="col-lg-12"><div style="border:dotted black 1px; mso-border-alt:solid black 1px; padding:1px; margin-bottom: 0.85em;"><p align=center style="text-align:center; border:none; mso-border-alt:solid black .25pt; padding:0; mso-padding-alt:2px 1px 1px 1px"><b><span lang=ES-TRAD style="font-size:12.0pt; font-family:\'Arial\',\'sans-serif\';"><span style="mso-spacerun:yes"></span>POLITICA DE SOLICITUD DE VACACIONES</span></b><b style=\'mso-bidi-font-weight:normal\'><span lang=ES-TRAD style=\' font-size:12.0pt; font-family:Century Gothic,sans-serif; mso-bidi-font-family:Century Gothic; color:black\'><o:p></o:p></span></b></p></div><p style=\'text-align:justify\'><b style=\'mso-bidi-font-weight: normal\'><span lang=ES-TRAD style=\' font-size:1.05em;font-family:Century Gothic,sans-serif; font-weight:bolder; mso-bidi-font-family:Century Gothic; color:black\'><u>PROP&Oacute;SITO</u></span></b><span lang=ES-TRAD style=\' font-size:1.05em; font-family:Century Gothic,sans-serif; mso-bidi-font-family:Century Gothic; color:black\'><o:p></o:p></span></p><p style="text-align:justify"><span lang=ES-TRAD style=\' font-size:1.06em; font-family:Century Gothic,sans-serif; mso-bidi-font-family: Century Gothic; color:black\'>Contar con una adecuada administraci&oacute;n del periodo vacacional de cada colaborador as&iacute; como el tr&aacute;mite correspondiente.<o:p></o:p></span></p>		<p style="text-align:justify"><b style="mso-bidi-font-weight: normal"><span lang=ES-TRAD style=\' font-size:1.05em; font-family:Century Gothic,sans-serif; font-weight:bolder; mso-bidi-font-family:Century Gothic; color:black\'>POL&Iacute;TICA<o:p></o:p></span></b></p><p style="margin-left:36.0pt; text-align:justify; text-indent: -18.0pt; mso-list:l1 level1 lfo2; tab-stops: list 0cm"><!--[if !supportLists]><span lang=ES-TRAD style=\' font-size:1.06em; font-family:Century Gothic,sans-serif; mso-fareast-font-family:Century Gothic; mso-bidi-font-family:Century Gothic; color:black\'><span style=\'mso-list:Ignore\'>1.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em; font-family:"Century Gothic","sans-serif"; mso-bidi-font-family:"Century Gothic"; color:black\'>De acuerdo a lo establecido en la Ley Federal de Trabajo, en su art&iacute;culo 76, las vacaciones son otorgadas a los trabajadores que posean m&aacute;s de un a&ntilde;o de servicios, la posibilidad y obligaci&oacute;n de disfrutar de un periodo anual de vacaciones pagadas. Este periodo de vacaciones nunca podr&aacute; ser inferior a seis d&iacute;as laborales. <o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>2.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em; font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>Dentro de su art&iacute;culo 81, la Ley Federal del Trabajo determina que las vacaciones se deben conceder dentro de los seis meses siguientes al cumplimiento del a&ntilde;o de servicios.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>3.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>De acuerdo con el art&iacute;culo 516 del mismo ordenamiento, el plazo de la prescripci&oacute;n de la acci&oacute;n para reclamar el pago de las vacaciones y de la prima vacacional, debe computarse a partir del d&iacute;a siguiente que se concluye ese lapso de seis meses, dentro de los cuales el trabajador tiene derecho a disfrutar de su periodo vacacional, porque hasta la conclusi&oacute;n de ese t&eacute;rmino es cuando la obligaci&oacute;n se hace exigible, mas no a partir de la conclusi&oacute;n del per&iacute;odo anual o parte proporcional reclamados, debido a que el patr&oacute;n cuenta con seis meses para conceder a los trabajadores el periodo vacacional.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>4.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>El pago de las mismas, debe realizarse el d&iacute;a inmediato anterior en que empiece a disfrutarlas el trabajador.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>5.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>Por el primer a&ntilde;o de trabajo se tiene derecho a seis d&iacute;as de vacaciones. Durante los siguientes a&ntilde;os aumentar&aacute; dos d&iacute;as cada a&ntilde;o hasta llegar a 12 d&iacute;as de vacaciones. Asimismo, por cada cinco a&ntilde;os adicionales laborados bajo el mismo patr&oacute;n, el trabajador tiene derecho a dos d&iacute;as m&aacute;s de vacaciones.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>6.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>D&iacute;as de vacaciones seg&uacute;n la Ley Federal de Trabajo:<o:p></o:p></span></p><p style=\'margin-top:0cm;margin-right:0cm;margin-bottom:12.0pt;margin-left:72.0pt;line-height:12.0pt;background:white;vertical-align:baseline\'><span style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>A&ntilde;o 1: 6 d&iacute;as<br>A&ntilde;o 2: 8 d&iacute;as<br>A&ntilde;o 3: 10 d&iacute;as<br>A&ntilde;o 4: 12 d&iacute;as<br>De 5 a 9 a&ntilde;os: 14 d&iacute;as<br>De 10 a 14 a&ntilde;os: 16 d&iacute;as<br>De 15 a 19 a&ntilde;os: 18 d&iacute;as<br>De 20 a 24 a&ntilde;os: 20 d&iacute;as<br>De 25 a 29 a&ntilde;os: 22 d&iacute;as<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>7.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>El periodo vacacional se deber&aacute; de solicitar con 5 d&iacute;as de anticipaci&oacute;n a la fecha de salida (se analizaran casos especiales)<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>8.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>Todo colaborador deber&aacute; de ingresar al portal de am para generar su solicitud de vacaciones, imprimirla y solicitar la autorizaci&oacute;n o rechazo de su jefe inmediato del periodo vacacional que est&aacute; requiriendo y firmar&aacute;n ambos el formato. <o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>9.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>CH le dar&aacute; seguimiento a todas las solicitudes a trav&eacute;s del portal am.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>10.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>Es responsabilidad del colaborador entregar el formato a CH ya firmado, autorizadas las vacaciones o no.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>11.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>El jefe inmediato deber&aacute; reportar en la asistencia el periodo vacacional que ya hab&iacute;a autorizado, esto con el objetivo de cruzar lainformaci&oacute;n del formato con la n&oacute;mina que reporta.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>12.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>Ya firmado el formato por el colaborador, jefe inmediato y gerente de capital humano se entregara al departamento de n&oacute;minas para su aplicaci&oacute;n.<o:p></o:p></span></p><p style=\'margin-left:36.0pt;text-align:justify;text-indent:-18.0pt;mso-list:l1 level1 lfo2;tab-stops:list 0cm\'><!--[if !supportLists]><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-fareast-font-family:"Century Gothic";mso-bidi-font-family:"Century Gothic";color:black\'><span style=\'mso-list:Ignore\'>13.<span style=\'font:7.0pt "Times New Roman"\'>&nbsp;&nbsp;</span></span></span><![endif]--><span lang=ES-TRAD style=\'font-size:1.06em;font-family:"Century Gothic","sans-serif";mso-bidi-font-family:"Century Gothic";color:black\'>El departamento de n&oacute;minas registrar&aacute; en sistema el periodo vacacional que gozar&aacute; el colaborador y archivar&aacute; el formato en el expediente.<o:p></o:p></span></p></div></div></div><div class="modal-footer"><a href="prog_vacs.php" class="btn btn-primary"><i class="fa fa-check-circle-o"></i> Aceptar</a></div></div></div></div>';
        return $contenido_modal;
    }

}

function sysmsg() {
    $rspfcn = "<div id='sysmessage'></div>";
    return $rspfcn;
}

function actusersystem($idn, $claven, $emailn, $estadon) {
    global $conexion;
    if ($conexion) {
        $sql_actsysus = "UPDATE `com_usuarios` SET `clave` = '" . $claven . "', `email` = '" . $emailn . "', `estatus` = '" . $estadon . "' WHERE `com_usuarios`.`id` = " . $idn . ";";
        mysqli_query($conexion, $sql_actsysus);
        return $idn;
    }
}

function evalualogin($usrpost, $pswpost) {
    global $conexion;
    if ($conexion) {
        $usuario = $usrpost;
        $contrasena = $pswpost;
        $sql_loguer = "SELECT * FROM `com_usuarios` WHERE `usuario` = '" . $usuario . "' and `clave` = '" . $contrasena . "';";
        $rs_loguer = mysqli_query($conexion, $sql_loguer);
        $rows = mysqli_fetch_assoc($rs_loguer);
        $totrows = mysqli_num_rows($rs_loguer);
        if ($totrows == 0) {
            $sql_loguer = "SELECT * FROM `com_usuarios` WHERE `usuario` = '" . $usuario . "' and `clave` = SHA1('" . $contrasena . "');";
            $rs_loguer = mysqli_query($conexion, $sql_loguer);
            $rows = mysqli_fetch_assoc($rs_loguer);
            $totrows = mysqli_num_rows($rs_loguer);
            if ($totrows > 0) {
                $sql_update = "UPDATE `com_usuarios` SET clave = '" . $contrasena . "' WHERE `usuario` = '" . $usuario . "' and `clave` = SHA1('" . $contrasena . "');";
                $rs_update = mysqli_query($conexion, $sql_update);
            }
        }if ($totrows > 0) {
            $activo = $rows['estatus'];
            if ($activo == '1') {
                $_SESSION['usercorrect'] = 1;
                if (isset($_SESSION['usercorrect']) and $_SESSION['usercorrect'] == 1) {
                    unset($_SESSION['nameuser']); unset($_SESSION['gpouser']); unset($_SESSION['idempresa']);
                    unset($_SESSION['idusuario']); unset($_SESSION['idloguser']); unset($_SESSION['periodo']);
                    if(basename($_SERVER['PHP_SELF']) == 'comedor.php' OR basename($_SERVER['PHP_SELF']) == 'cuponeras.php'){
                        echo "<script languaje=\"javascript\">history.pushState(null, null, '#Again-No-back-button'); parent.top.location.replace('/portal/#Again-No-back-button');</script>";
                    }
                    $_SESSION['nameuser'] = $rows['nombre'];
                    $_SESSION['gpouser'] = $rows['tipo'];
                    $_SESSION['idempresa'] = $rows['empresa'];
                    $_SESSION['idusuario'] = $rows['usuario'];
                    $_SESSION['idloguser'] = $rows['id'];
                    $_SESSION['periodo'] = '2013';
                    $_SESSION["ultimoAcceso"] = date("Y-m-d H:i:s");
                    return true;
                }
            } elseif ($activo == '0') {
                $_SESSION['estatus'] = 'warning';
                $_SESSION['alertlog'] = 'Usuario inactivo, consulta al administrador!';
            }
        } else {
            if (isset($_POST['insesion']) and ! empty($_POST['userid']) and ! empty($_POST['passuser']) and ! isset($_SESSION['usercorrect'])) {
                $_SESSION['userincorrect'] = 1;
                unset($_SESSION['estatus']);
                unset($_SESSION['alertlog']);
            } elseif (isset($_POST['usercorrect'])) {
                unset($_SESSION['userincorrect']);
            }return false;
        }
    }
}

function printlogin() {
    if (isset($_SESSION['nameuser']) and ! empty($_SESSION['nameuser'])) {
        echo '<div class="top-menu"><ul class="nav-collapse navbar-right" style="margin-top: 16px; margin-right: 2px;"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears fa-2x"></i><span class="caret"></span></a><ul class="dropdown-menu"><li><a href="#"><i class="fa fa-windows"></i>&nbsp;Perfil</a></li><li role="separator" class="divider"></li><li id="vclosecom"><a class="logout" id="cierracom" href="assets/includes/closer.php?' . random_system() . '" name="closesystem"><i class="fa fa-sign-out"></i > Salir</a></li></ul></li></ul></div>';
    } else {
        echo '<div class="top-menu"><ul class="nav navbar-right" style="width:59px;">';
        $dractual = explode("/", $_SERVER['SCRIPT_NAME']);
        $compdir = end($dractual);
        if ($compdir != 'comedor.php') {
            echo '<li class="dropdown" style="margin-top: 5px;">';
        } else {
            echo '<li class="dropdown" style="margin-top: 12px;">';
        }echo '<a class="dropdown-toggle" href="#" data-toggle="dropdown" id="inisesjs"><i class="fa fa-sign-in fa-lg"></i> <strong class="caret"></strong></a><div class="dropdown-menu extended inbox panel panel-primary" id="lg-indx" style="padding: 0; margin:0; width: 240px;"><div class="panel-heading" style="width:100%; height:34px; padding-top:7px;"><i class="fa fa-desktop"></i> Bienvenid@s</div><form method="post" action="' . $_SERVER['PHP_SELF'] . '"><div class="panel-body" style="padding-bottom:11px;"><div class="form-group';
        if (isset($_SESSION['userincorrect']) and $_SESSION['userincorrect'] == 1 or ( isset($_POST['userid']) and empty($_POST['userid']))) {
            if (isset($_SESSION['estatus']) and $_SESSION['estatus'] == 'warning') {
                echo ' has-warning"';
            } else {
                echo ' has-error"';
            }
        } else {
            if (isset($_SESSION['estatus']) and $_SESSION['estatus'] == 'warning') {
                echo ' has-warning"';
            } else {
                echo '"';
            }
        }echo '><div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input type="text" class="form-control" placeholder="Usuario" autofocus="true" id="cruser" name="userid" autocomplete="off" required="required"></div><div class="input-group" style="margin-top:15px;"><input type="password" class="form-control" placeholder="Clave de acceso" name="passuser" required="required"><div class="input-group-btn"><button type="submit" class="btn ';
        if (isset($_SESSION['userincorrect']) and $_SESSION['userincorrect'] == 1 or ( isset($_POST['userid']) and empty($_POST['userid']))) {
            if (isset($_SESSION['estatus']) and $_SESSION['estatus'] == 'warning') {
                echo ' btn-warning"';
            } else {
                echo ' btn-danger"';
            }
        } else {
            if (isset($_SESSION['estatus']) and $_SESSION['estatus'] == 'warning') {
                echo ' btn-warning"';
            } else {
                echo ' btn-primary"';
            }
        }echo ' name="insesion"><i class="fa fa-key"></i></button></div></div></div><div class="form-group" style="margin-bottom:0;"><div class="divider"></div><a class="small" data-toggle="modal" href="#mrecpass">&iquest;recuperar clave?</a><div class="pull-right"><a class="btn btn-xs btn-danger badge" href="comedor.php">registrate</a></div></div></div></form></div></li></ul></div>';
    }
}
function random_system() {
    unset($_SESSION['serialize']);
    $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $numerodeletras = 6;
    $cadena = "";
    for ($i = 0; $i < $numerodeletras; $i++) {
        $cadena.=substr($caracteres, rand(0, strlen($caracteres)), 1);
    }$_SESSION['serialize'] = $cadena;
    return sha1($cadena) . "=" . sha1($cadena . 'sr');
}
function insertacategoriamn($nmemn, $dscmn) {
    global $conexion;
    if ($conexion) {
        $sql_inscat = "INSERT INTO `aplicaciones`.`com_menus` (`id`, `nombre`, `descripcion`) VALUES (NULL, '" . utf8_decode($nmemn) . "', '" . utf8_decode($dscmn) . "');";
        $rs_inscat = mysqli_query($conexion, $sql_inscat);
        if ($rs_inscat) {
            echo trim('Menu agregado al catalogo exitosamente!');
        } else {
            echo trim('error al insertar el menu al catalogo!');
        }
    }
}

function insmtvcanvcs($vmtvcnc) {
    global $conexion;
    if ($conexion) {
        $sql_insmtv = "INSERT INTO `aplicaciones`.`vac_mcancela` (`id`, `descripcion`) VALUES (NULL, '" . $vmtvcnc . "');";
        $rs_insmtv = mysqli_query($conexion, $sql_insmtv);
        if ($rs_insmtv) {
            echo trim('Motivo agregado con exito');
        }
    }
}

function totales($consult) {
    global $conexion;
    $sql_obttotrp = $consult;
    if ($conexion) {
        $rs_obttotrp = mysqli_query($conexion, $sql_obttotrp);
        $rw_obttotrp = mysqli_fetch_assoc($rs_obttotrp);
        if ($rw_obttotrp['total'] != null and ! empty($rw_obttotrp['total'])) {
            $htmlAl = "<td align='left' style='text-align: left;'>TOTAL: $<strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obttotrp['total'] . "</strong></td><td></td></tr>";
            return $htmlAl;
        }
    }
}

function obtenerfch($fchcons) {
    global $conexion;
    if ($conexion) {
        $sql_fchmn = "SELECT fecha FROM com_menuporfecha WHERE id = '" . $fchcons . "'";
        $rs_fchmn = mysqli_query($conexion, $sql_fchmn);
        $rw_fchmn = mysqli_fetch_assoc($rs_fchmn);
        return $rw_fchmn['fecha'];
    }
}

function prntmtvcncsl($paramsltd) {
    global $conexion;
    if ($conexion) {
        $sql_mtvcnc = "SELECT * FROM `vac_mcancela` ORDER BY descripcion";
        $rs_mtvcnc = mysqli_query($conexion, $sql_mtvcnc);
        $htmlsl = '';
        while ($rw_mtvcnc = mysqli_fetch_assoc($rs_mtvcnc)) {
            $htmlsl.="<option value='" . $rw_mtvcnc['id'] . "' ";
            if ($paramsltd == $rw_mtvcnc['id']) {
                $htmlsl.='selected';
            }$htmlsl.=">" . $rw_mtvcnc['descripcion'] . "</option>";
        }if (!empty($htmlsl)) {
            return '<option value="0">Selecciona una opcion</option>' . $htmlsl;
        }
    }
}

function revisa_duplvacs($misfch, $miper, $mireg) {
    global $conexion;
    if ($conexion) {
        $sql_revisa = "SELECT vacaciones FROM `vac_solicitud` WHERE `usuario` = " . $_SESSION['idloguser'] . " and estatus <> 'C';";
        $rs_revisa = mysqli_query($conexion, $sql_revisa);
        $tots_revisa = mysqli_num_rows($rs_revisa);
        if ($tots_revisa > 0) {
            $sharr = [];
            while ($rw_vacs = mysqli_fetch_assoc($rs_revisa)) {
                $sep_vacs = explode(",", $rw_vacs['vacaciones']);
                $comp_text = explode(",", $misfch);
                foreach ($comp_text as $rfch) {
                    if (in_array($rfch, $sep_vacs)) {
                        array_push($sharr, $rfch);
                    }
                }
            }$tot_sharr = count($sharr);
            if ($tot_sharr > 0) {
                return trim('Existen fechas ya solicitadas');
            }
        }
    }
}

function calcvig($strdt) {
    $fch = new DateTime($strdt);
    $fch->add(new DateInterval('P1Y'));
    return $fch->format('Y-m-d');
}

function restaday($strdy) {
    $rdy = new DateTime($strdy);
    $rdy->sub(new DateInterval('P1D'));
    return $rdy->format('Y-m-d');
}

function actpersvac($identify) {
    $usuario = $identify;
    global $conexion;
    $ingreso = consultas::obtieneingusr($usuario);
    $anios = tools::antiguedad($ingreso, '');
    if ($conexion) {
        if ($anios > 0) {
            $sql_totper = "SELECT COUNT(*) AS tperiodos FROM `vac_periodos` WHERE `usuario` = " . $usuario;
            $rs_totper = mysqli_query($conexion, $sql_totper);
            $rw_totper = mysqli_fetch_assoc($rs_totper);
            $tot_per = $rw_totper['tperiodos'];
            if ($tot_per < $anios) {
                $dif_pers = $anios - $tot_per;
                if ($anios == 1) {
                    altas::insertaper($ingreso, $anios, $usuario);
                } else {
                    $cont = $tot_per;
                    $sql_utlpr = "SELECT periodo FROM vac_periodos WHERE usuario = " . $usuario . " ORDER BY periodo DESC LIMIT 1";
                    $rs_ultpr = mysqli_query($conexion, $sql_utlpr);
                    $tot_perdb = mysqli_num_rows($rs_ultpr);
                    $rw_ultpr = mysqli_fetch_assoc($rs_ultpr);
                    $pr_pr = $rw_ultpr['periodo'];
                    $arrpr = [];
                    $printctrl = 0;
                    $sp_ing = explode("-", $ingreso);
                    $msing = $sp_ing[1];
                    $dying = $sp_ing[2];
                    $envpr = $pr_pr . '-' . $msing . '-' . $dying;
                    while ($printctrl < $dif_pers) {
                        $tot_arrfn = count($arrpr);
                        if ($tot_arrfn == 0) {
                            if ($tot_perdb == 0) {
                                altas::insertaper($ingreso, $cont + 1, $usuario);
                                array_push($arrpr, $ingreso);
                            } else {
                                altas::insertaper($envpr, $cont + 1, $usuario);
                                array_push($arrpr, $envpr);
                            }
                        } else {
                            $ult_elem = end($arrpr);
                            if ($ult_elem >= $ingreso) {
                                $ult_elem = calcvig($ult_elem);
                            }altas::insertaper($ult_elem, $cont + 1, $usuario);
                            array_push($arrpr, $ult_elem);
                        }$printctrl++;
                        $cont++;
                    }
                }
            }
        }
    }
}

function canslvacs($canidsl, $vlmtv) {
    global $conexion;
    if ($conexion) {
        $sql_canslvc = "UPDATE `aplicaciones`.`vac_solicitud` SET `estatus` = 'C', fecha_modifica = NOW(), motivo_cancela = " . $vlmtv . " WHERE `vac_solicitud`.`id` = " . $canidsl;
        $rs_canslvc = mysqli_query($conexion, $sql_canslvc);
        if ($rs_canslvc) {
            echo "1";
        }
    }
}

function authslvacs($authsl) {
    global $conexion;
    if ($conexion) {
        $sql_dtneed = "SELECT periodo, vacaciones FROM vac_solicitud WHERE id = " . $authsl;
        $rs_dtneed = mysqli_query($conexion, $sql_dtneed);
        $rw_dtneed = mysqli_fetch_assoc($rs_dtneed);
        $sysvacs = $rw_dtneed['vacaciones'];
        $sepvacs = explode(",", $sysvacs);
        $totvacs = count($sepvacs);
        $persys = $rw_dtneed['periodo'];
        for ($h = 0; $h < $totvacs; $h++) {
            $sql_inslvc = "INSERT INTO `aplicaciones`.`vac_vacaciones` (`id`, `fecha`, `solicitud`) VALUES (NULL, '" . $sepvacs[$h] . "', '" . $authsl . "');";
            $rs_inslvc = mysqli_query($conexion, $sql_inslvc);
        }$sql_updath = "UPDATE `aplicaciones`.`vac_solicitud` SET `estatus` = 'A', fecha_modifica = NOW() WHERE `vac_solicitud`.`id` = " . $authsl;
        $rs_updath = mysqli_query($conexion, $sql_updath);
        if ($rs_updath) {
            $sql_pndact = "SELECT pendientes, tomados FROM vac_periodos WHERE id = " . $persys;
            $rs_pndact = mysqli_query($conexion, $sql_pndact);
            $rw_pndact = mysqli_fetch_assoc($rs_pndact);
            $pndnw = intval($rw_pndact['pendientes']) - $totvacs;
            $nwtoms = intval($rw_pndact['tomados']) + $totvacs;
            $sql_updper = "UPDATE `aplicaciones`.`vac_periodos` SET `tomados` = '" . $nwtoms . "', `pendientes` = '" . $pndnw . "' WHERE `vac_periodos`.`id` = " . $persys;
            $rs_updper = mysqli_query($conexion, $sql_updper);
            echo "1";
        }
    }
}

function prntclusr() {
    global $conexion;
    if ($conexion) {
        $sql_clms = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='aplicaciones' AND `TABLE_NAME`='com_usuarios' AND `COLUMN_NAME` <> 'id' AND `COLUMN_NAME` <> 'clave' AND `COLUMN_NAME` <> 'tipo' AND `COLUMN_NAME` <> 'email' AND `COLUMN_NAME` <> 'estatus' AND `COLUMN_NAME` <> 'empresa' ORDER BY `COLUMN_NAME` DESC;";
        $rs_clms = mysqli_query($conexion, $sql_clms);
        $dtrt = '';
        while ($rw_clms = mysqli_fetch_assoc($rs_clms)) {
            $columna = $rw_clms['COLUMN_NAME'];
            if ($columna == 'usuario') {
                $columna = 'codigo';
                $sltd_ord = 'selected="selected"';
            } else {
                $sltd_ord = '';
            }$dtrt.="<option value=\"" . $columna . "\" " . $sltd_ord . ">" . $columna . "</option>";
        }return $dtrt;
    }
}

function prntemp() {
    global $conexion;
    if ($conexion) {
        $sql_crgemp = "SELECT * FROM com_empresas ORDER BY descripcion";
        $rs_crgemp = mysqli_query($conexion, $sql_crgemp);
        while ($rows_crgemp = mysqli_fetch_assoc($rs_crgemp)) {
            $myempsl = $rows_crgemp['codigo'];
            echo "<option value='" . $rows_crgemp['codigo'] . "' ";
            if ($myempsl == '12') {
                echo "selected='selected'";
            }echo ">" . trim(utf8_encode($rows_crgemp['descripcion'])) . "</option>";
        }
    }
}

function cnflslvc($pridslvc) {
    global $conexion;
    if ($conexion) {
        $sql_cnsfslvc = "SELECT S.`vacaciones`,S.`estatus`, U.usuario AS empleado, U.nombre, E.descripcion AS empresa, S.motivo_cancela AS motivo FROM `vac_solicitud` S INNER JOIN com_usuarios U ON S.`usuario` = U.`id` INNER JOIN com_empresas E ON U.empresa = E.codigo WHERE S.`id` = " . $pridslvc;
        $rs_cnsfslvc = mysqli_query($conexion, $sql_cnsfslvc);
        $rw_totfl = mysqli_num_rows($rs_cnsfslvc);
        $rw_cnsfslvc = mysqli_fetch_assoc($rs_cnsfslvc);
        if ($rw_totfl > 0) {
            $fchdtsvc = explode(",", $rw_cnsfslvc['vacaciones']);
            $est_vac = $rw_cnsfslvc['estatus'];
            $noemply = substr($rw_cnsfslvc['empleado'], 2);
            $nmemply = utf8_encode($rw_cnsfslvc['nombre']);
            $nmemp = utf8_encode($rw_cnsfslvc['empresa']);
            $mysltdcnc = utf8_encode($rw_cnsfslvc['motivo']);
            if (!empty($mysltdcnc)) {
                $mtvsltd = '<select class="form-control" disabled="disabled">' . prntmtvcncsl($mysltdcnc) . '</select>';
            } else {
                $mtvsltd = '';
            }$htmdts = '';
            $ctrldts = 0;
            $htmdts.=$est_vac . '*<select class="form-control" size="5">';
            foreach ($fchdtsvc as $mydts) {
                $mydts_format = explode("-", $mydts);
                $htmdts.='<option>' . $mydts_format[2] . '/' . tools::obtenermes($mydts_format[1]) . '/' . $mydts_format[0] . '</option>';
                $ctrldts++;
            }$htmdts.='*' . $noemply . '*' . $nmemply . '*' . $nmemp . '*' . $mtvsltd;
            if (!empty($htmdts)) {
                return $htmdts;
            }
        }
    }
}

function printsolsvacrh($filter, $fltprd, $datash, $cdent, $totales, $limit, $pagina, $tfil) {
    global $conexion;
    if ($conexion) {
        $cad_sql = ''; $estsl = '';
        if ($fltprd != 'Todos'){ $fltrprds = "AND P.periodo = '" . $fltprd . "' "; }else{ $fltrprds = ''; }
        if ($cdent == 0){ $vluemp = ''; }else{ $vluemp = 'AND U.empresa = ' . $cdent . ' '; }
        if ($datash != ''){ $shbusca = "AND (S.folio like '%" . $datash . "%' OR U.usuario like '%" . $datash . "%' OR U.nombre like '%" . $datash . "%')"; }else{ $shbusca = ''; }
        $before_where = 'SELECT S.folio, U.usuario AS empleado, S.estatus, U.nombre AS nomemp, E.descripcion AS empresa, P.periodo AS periodo, S.vacaciones AS lds, S.fecha AS registro, S.id AS accion FROM `vac_solicitud` S INNER JOIN com_usuarios U ON S.usuario = U.id INNER JOIN com_empresas E ON U.empresa = E.codigo INNER JOIN vac_periodos P ON S.periodo = P.id';
        switch ($filter) {
            case '1':$estsl = 'P';
                $cad_sql = $before_where . " WHERE S.estatus = 'P' " . $fltrprds . $vluemp . $shbusca . " ORDER BY folio DESC";
                break;
            case '2':$cad_sql = $before_where . " WHERE S.estatus = 'C' " . $fltrprds . $vluemp . $shbusca . " ORDER BY folio DESC";
                break;
            case '3':$cad_sql = $before_where . " WHERE S.estatus = 'A' " . $fltrprds . $vluemp . $shbusca . " ORDER BY folio DESC";
                break;
        }
        if($limit){
            $pagina = ($pagina-1)*$tfil;
            if(intval($pagina)<0){ $pagina = 0; }
            $clause_limit = ' LIMIT '.$pagina.','.$tfil;
        } else {
            $clause_limit = '';
        }
        $sql_rhsol = $cad_sql.$clause_limit;
        $rs_rhsol = mysqli_query($conexion, $sql_rhsol);
        $tot_rhsol = mysqli_num_rows($rs_rhsol);
        if(!$totales){
            if($tot_rhsol>0){
                while ($rw_rhsol = mysqli_fetch_assoc($rs_rhsol)) {
                    $folio = $rw_rhsol['folio']; $idsl = $rw_rhsol['accion']; $empleado = $rw_rhsol['empleado']; $nameemp = utf8_encode($rw_rhsol['nomemp']);
                    $empresa = utf8_encode($rw_rhsol['empresa']); $periodo = $rw_rhsol['periodo']; $registro = $rw_rhsol['registro']; $ds = count(explode(",", $rw_rhsol['lds']));
                    echo "<tr><td id='flsl_" . $idsl . "' class='centered'>" . $folio . "</td><td class='centered'>" . substr($empleado, 2) . "</td><td class='centered'>" . $nameemp . "</td><td class='centered'>" . $periodo . "</td><td class='centered'>" . $ds . "</td><td class='centered'>" . $registro . "</td><td class='centered' style='cursor:default;'><a data-toggle='modal' id='dtls_" . $idsl . "' class='btn btn-default mdtlslv' style='cursor: pointer;'><i class='fa fa-newspaper-o fa-lg'></i></a>";
                    if ($estsl == 'P') {
                        echo "&nbsp;<a id='at_" . $idsl . "' class='btn btn-default auth' style='cursor:pointer; color: #00734E;'><i class='fa fa-check fa-lg'></i></a>&nbsp;<a id='cncsl_" . $idsl . "' style='cursor:pointer; color:red;' class='btn btn-default cansl'><i class='fa fa-remove fa-lg'></i></a>";
                    }echo "</td></tr>";
                }
            }else{
                echo "<tr class='centered'><td colspan='8' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No hay solicitudes en esta categoria filtrada!</td></tr>";
            }
        }else{
            if ($tot_rhsol != 0) {
                return '*'.$tot_rhsol;
            }
        }
    }
}

function printmysolct() {
    $useract = $_SESSION['idloguser'];
    global $conexion;
    if ($conexion) {
        $sql_mysolct = "SELECT * FROM `vac_solicitud` WHERE usuario = '" . $useract . "' ORDER BY periodo DESC, id DESC";
        $rs_mysolct = mysqli_query($conexion, $sql_mysolct);
        $tot_mysolct = mysqli_num_rows($rs_mysolct);
        $temporal = '';
        $contad = 0;
        $contad2 = 0;
        $contad3 = 0;
        $contador = 0;
        while ($rw_mysolct = mysqli_fetch_assoc($rs_mysolct)) {
            $folio = $rw_mysolct['folio'];
            $solicitadas = $rw_mysolct['vacaciones'];
            $salida = $rw_mysolct['salida'];
            $regreso = $rw_mysolct['regreso'];
            $sepsolic = explode(",", $solicitadas);
            $sepsali = explode(",", $salida);
            $sepreg = explode(",", $regreso);
            $totsol = count($sepsolic);
            $totsal = count($sepsali);
            $totreg = count($sepreg);
            $clase = '';
            $estatus = $rw_mysolct['estatus'];
            if ($estatus == 'P') {
                $clase = 'bg-warning';
                $estatus = 'Pendiente';
            } elseif ($estatus == 'C') {
                $clase = 'bg-danger';
                $estatus = 'Cancelada';
            } elseif ($estatus == 'A') {
                $clase = 'bg-theme';
                $estatus = 'Autorizada';
            }$periodo = $rw_mysolct['periodo'];
            $sql_per = "SELECT periodo FROM vac_periodos WHERE id = " . $periodo . " order by periodo DESC";
            $rs_per = mysqli_query($conexion, $sql_per);
            $rw_per = mysqli_fetch_assoc($rs_per);
            $persys = $rw_per['periodo'];
            if ($persys != $temporal and ! empty($temporal)) {
                echo "<tr><td style='text-align: right;'><i>Autorizados</i></td><td style='text-align: center;'><span class='badge bg-theme'><strong style='font-weight: 700; font-size: 1.2em;'>" . $contad . "</strong>&nbsp;Dias</span></td><td style='text-align: right;'><i>Pendientes</i></td><td style='text-align: center;'><span class='badge bg-warning'><strong style='font-weight: 700; font-size: 1.2em;'>" . $contad2 . "</strong>&nbsp;Dias</span></td><td style='text-align: right;'><i>Cancelados</i></td><td style='text-align: center;'><span class='badge bd-danger'><strong style='font-weight: 700; font-size: 1.2em;'>" . $contad3 . "</strong>&nbsp;Dias</span></td><td colspan='2'>&nbsp;</td></tr>";
                $contad = 0;
                $contad2 = 0;
                $contad3 = 0;
            }echo "<tr><td class='centered'>" . $folio . "</td><td class='centered'><span class='" . $clase . " badge'>" . $estatus . "</span></td><td class='centered'>" . $persys . "</td><td class='centered'>";
            if ($totsol > 1) {
                $htmlpopover = '<div style=\'height: 104px; width: 107%; overflow-y: auto; margin-bottom: 0.4em;\'><table>';
                foreach ($sepsolic as $fchsol) {
                    $sepfchsol = explode("-", $fchsol);
                    $htmlpopover.='<tr><td><strong class=\'badge bg-theme\' style=\'color: #ffffff; font-size: 1.15em; margin-bottom: 0.4em;\'>' . $sepfchsol[2] . '-' . tools::obtenermes($sepfchsol[1]) . '-' . $sepfchsol[0] . '</strong></td></tr>';
                }$htmlpopover.='</table></div><span style=\'text-align: right; margin-top: 0.8em;\'><strong>Para un total de ' . $totsol . ' dia(s)</strong></span>';
                echo '<u style="cursor: pointer;"><a style="cursor: pointer;" title="!Yo solicite estos dias¡" data-toggle="popover" data-html="true" data-placement="left" data-container="body" data-content="' . $htmlpopover . '"><i class="fa fa-search-plus"></i></a></u></td>';
            } else {
                echo $solicitadas . '</td>';
            }echo "<td class='centered'>";
            if ($totsal > 1) {
                $htmlsalidas = '<div style=\'height: 88px; width: 107%; overflow-y: auto; margin-bottom: 0.4em;\'><table>';
                foreach ($sepsali as $fchsal) {
                    $sepfchsal = explode("-", $fchsal);
                    $htmlsalidas.='<tr><td><strong class=\'badge bg-important\' style=\'color: #ffffff; font-size: 1.15em; font-weight: 600; margin-bottom: 0.4em;\'>' . $sepfchsal[2] . '-' . tools::obtenermes($sepfchsal[1]) . '-' . $sepfchsal[0] . '</strong></td></tr>';
                }$htmlsalidas.='</table></div><span style=\'text-align: right; margin-top: 0.8em;\'><strong>Acumulando ' . $totsal . ' fecha(s)</strong></span>';
                echo '<u style="cursor: pointer;"><a style="cursor: pointer;" title="!Mis salidas programadas¡" data-toggle="popover" data-html="true" data-placement="bottom" data-container="body" data-content="' . $htmlsalidas . '"><i class="fa fa-ship"></i></a></u></td>';
            } else {
                echo $salida;
            }echo "</td><td class='centered'>";
            if ($totreg > 1) {
                $htmlregresos = '<div style=\'height: 88px; width: 107%; overflow-y: auto; margin-bottom: 0.4em;\'><table>';
                foreach ($sepreg as $fchreg) {
                    $sepfchreg = explode("-", $fchreg);
                    $htmlregresos.='<tr><td><strong class=\'badge bg-primary\' style=\'color: #ffffff; font-size: 1.15em; font-weight: 600; margin-bottom: 0.4em;\'>' . $sepfchreg[2] . '-' . tools::obtenermes($sepfchreg[1]) . '-' . $sepfchreg[0] . '</strong></td></tr>';
                }$htmlregresos.='</table></div><span style=\'text-align: right; margin-top: 0.8em;\'><strong>Fecha(s) calculadas ' . $totreg . '</strong></span>';
                echo '<u style="cursor: pointer;"><a style="cursor: pointer;" title="!Regreso a Laborar el dia¡" data-toggle="popover" data-html="true" data-placement="right" data-container="body" data-content="' . $htmlregresos . '"><i class="fa fa-refresh"></i></a></u></td>';
            } else {
                echo $regreso;
            }$idsol = $rw_mysolct['id'];
            $encrypted_txt = tools::encrypt_decrypt('encrypt', $idsol);
            if($estatus=='Pendiente' or $estatus == 'Autorizada'){ $addtrgt = "target='_blank'"; $direccionar = 'assets/includes/gen_solvacs.php?sl=' . $encrypted_txt; }else{ $addtrgt = ''; $direccionar='#'; }
            echo "</td><td class='centered'><a '".$addtrgt."' href='".$direccionar."' id='" . $idsol . "' style='cursor: pointer;'><i class='fa fa-file-pdf-o fa-2x'></i></a></td><td class='centered'><strong>" . $rw_mysolct['fecha'] . "</strong></td></tr>";
            if ($contador == ($tot_mysolct - 1)) {
                if ($estatus == 'Autorizada') {
                    $contad = $contad + $totsol;
                } elseif ($estatus == 'Pendiente') {
                    $contad2 = $contad2 + $totsol;
                } elseif ($estatus == 'Cancelada') {
                    $contad3 = $contad3 + $totsol;
                }echo "<tr><td style='text-align: right;'><i>Autorizados</i></td><td style='text-align: center;'><span class='badge bg-theme'><strong style='font-weight: 700; font-size: 1.2em;'>" . $contad . "</strong>&nbsp;Dias</span></td><td style='text-align: right;'><i>Pendientes</i></td><td style='text-align: center;'><span class='badge bg-warning'><strong style='font-weight: 700; font-size: 1.2em;'>" . $contad2 . "</strong>&nbsp;Dias</span></td><td style='text-align: right;'><i>Cancelados</i></td><td style='text-align: center;'><span class='badge bd-danger'><strong style='font-weight: 700; font-size: 1.2em;'>" . $contad3 . "</strong>&nbsp;Dias</span></td><td colspan='2'>&nbsp;</td></tr>";
            }$temporal = $persys;
            if ($estatus == 'Autorizada') {
                $contad = $contad + $totsol;
            } elseif ($estatus == 'Pendiente') {
                $contad2 = $contad2 + $totsol;
            } elseif ($estatus == 'Cancelada') {
                $contad3 = $contad3 + $totsol;
            }$contador++;
        }if ($tot_mysolct == 0) {
            echo "<tr class='centered'><td colspan='8' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No has realizado solicitud de vacaciones al sistema!</td></tr>";
        }
    }
}

function retornafestivos() {
    global $conexion;
    if ($conexion) {
        $sql_festivos = "SELECT fecha FROM vac_festivos order by fecha";
        $rs_festivos = mysqli_query($conexion, $sql_festivos);
        $festivos = "";
        $tot_festivos = mysqli_num_rows($rs_festivos);
        while ($rw_festivos = mysqli_fetch_assoc($rs_festivos)) {
            $retfecha = explode("-", $rw_festivos['fecha']);
            $mesdig = $retfecha[1];
            $diadig = $retfecha[2];
            if ($mesdig < 10 or $diadig < 10) {
                $mesdig = str_replace("0", "", $mesdig);
                $diadig = str_replace("0", "", $diadig);
            }if ($tot_festivos == 1) {
                $festivos = $mesdig . "-" . $diadig . "-" . $retfecha[0];
            } else {
                $festivos = $festivos . "," . $mesdig . "-" . $diadig . "-" . $retfecha[0];
            }
        }return $festivos;
    }
}

function retcantdayssols($paramperiod, $identify_user) {
    global $conexion;
    if ($conexion) {
        $sql_solicitudes = "SELECT vacaciones FROM `vac_solicitud` WHERE periodo = " . $paramperiod . " and estatus = 'P' and usuario = " . $identify_user . ";";
        $rs_solicitudes = mysqli_query($conexion, $sql_solicitudes);
        $tot_solxper = mysqli_num_rows($rs_solicitudes);
        $contador = 1;
        $longsol = 0;
        while ($rw_solicitudes = mysqli_fetch_assoc($rs_solicitudes)) {
            $actual = $rw_solicitudes['vacaciones'];
            $sepactual = explode(",", $actual);
            $longsol = $longsol + count($sepactual);
            $contador++;
            if ($contador > $tot_solxper) {
                return $longsol;
            }
        }
    }
}

function prntprdsrptvcs($speriodo, $filtre) {
    global $conexion;
    if ($conexion) {
        if (!empty($speriodo)) {
            $cnsprds = 'WHERE periodo > ' . $speriodo;
        } else {
            $cnsprds = '';
        }$sql_pdvrpt = "SELECT `periodo` FROM `vac_periodos` " . $cnsprds . " GROUP BY `periodo` DESC";
        $rs_pdvrpt = mysqli_query($conexion, $sql_pdvrpt);
        if (!empty($filtre)) {
            echo "<option value='x'>Todos</option>";
        } else {
            echo "<option value='0'>Selecciona el periodo</option>";
        }while ($rw_pdvrpt = mysqli_fetch_assoc($rs_pdvrpt)) {
            echo "<option value='" . $rw_pdvrpt['periodo'] . "'>" . $rw_pdvrpt['periodo'] . "</option>";
        }
    }
}

function printper($speriodo) {
    global $conexion;
    if ($conexion) {
        $sql_gtper = "SELECT * FROM `vac_periodos` WHERE `usuario` = '" . $_SESSION['idloguser'] . "' AND `periodo` > " . $speriodo . " ORDER BY periodo DESC;";
        $rs_gtper = mysqli_query($conexion, $sql_gtper);
        echo "<option value='0'>Selecciona el periodo</option>";
        while ($rw_gtper = mysqli_fetch_assoc($rs_gtper)) {
            $period = $rw_gtper['periodo'];
            $pendientes = $rw_gtper['pendientes'];
            $id_periodo = $rw_gtper['id'];
            $dayssol = retcantdayssols($id_periodo, $_SESSION['idloguser']);
            if ($pendientes > 0 and $pendientes != $dayssol) {
                echo "<option value='" . $rw_gtper['id'] . "'>" . $period . "</option>";
            }
        }
    }
}

function conciliaprd($idmdf, $ttltmds, $dyscrr) {
    global $conexion;
    if ($conexion) {
        $resltopr = intval($dyscrr) - intval($ttltmds);
        $sql_updprd = "UPDATE `vac_periodos` SET `tomados` = '" . $ttltmds . "', pendientes = '" . $resltopr . "' WHERE `vac_periodos`.`id` = " . $idmdf . ";";
        $rs_updprd = mysqli_query($conexion, $sql_updprd);
        if ($rs_updprd) {
            return $resltopr;
        }
    }
}

function printmyvacs($paramusr, $speriodo, $conciliar) {
    global $conexion;
    if ($conexion) {
        $sql_myvacs = "SELECT * FROM `vac_periodos` WHERE usuario = '" . $paramusr . "' order by vigencia desc";
        $rs_myvacs = mysqli_query($conexion, $sql_myvacs);
        $tot_myvacs = mysqli_num_rows($rs_myvacs);
        $conta = $tot_myvacs;
        while ($rw_myvacs = mysqli_fetch_assoc($rs_myvacs)) {
            $vgusr = explode("-", $rw_myvacs['vigencia']);
            $rvvig = $rw_myvacs['vigencia'];
            $perd = $rw_myvacs['periodo'];
            $idper = $rw_myvacs['id'];
            $soldone = retcantdayssols($idper,$paramusr);
            if (empty($soldone)) {
                $soldone = 0;
            }echo "<tr ";
            $pendts = intval($rw_myvacs['pendientes']);
            if ($perd <= $speriodo OR $pendts == 0) {
                echo "style='color:red;'";
            }if ($conciliar) {
                if ($perd > '2020'){
                    $maximo = $rw_myvacs['dias'];
                    $actslt = $rw_myvacs['tomados'];
                    $dycrr = '<span id="idnty_' . $idper . '">' . $rw_myvacs['dias'] . '</span> dia(s)';
                    $edit = '<td class="centered" id="cldprd_' . $idper . '"><a id="edtprd_' . $idper . '" class="btn btn-xs btn-default edtper"><i class="fa fa-pencil fa-lg"></i></a></td>';
                    $campedt = '<input type="hidden" id="hdd_' . $idper . '" value="' . $actslt . '" /><select class="form-control input-sm col-xs-1" id="sldsfprd_' . $idper . '" disabled="disabled" style="z-index:0; font-size:1.1em;">';
                    for ($c = 0; $c <= $maximo; $c++) {
                        $selected = '';
                        if ($c == $actslt) {
                            $selected = 'selected="selected"';
                        }$campedt.='<option value="' . $c . '" ' . $selected . '>' . $c . '</option>';
                    }$campedt.='</select>';
                } else {
                    $campedt = $rw_myvacs['tomados'] . " dia(s)";
                    $edit = "<td>&nbsp;</td>";
                    $dycrr = $rw_myvacs['dias'] . " dia(s)";
                }
            } else {
                $edit = '';
                $campedt = $rw_myvacs['tomados'] . " dia(s)";
                $dycrr = $rw_myvacs['dias'] . " dia(s)";
            }echo "><td class='centered' style='font-weight: bold;'>" . $conta . "</td><td class='centered' style='font-weight: bold;'>" . $perd . "</td><td class='centered' style='font-weight: bold;'>" . $dycrr . "</td><td class='centered' width=\"90\">" . $campedt . "</td><td class='centered' style='font-weight: bold;'><span id=\"rsltprd_" . $idper . "\">" . $rw_myvacs['pendientes'] . "</span> dia(s)</td><td class='centered' style='font-weight: bold;'>" . $soldone . " dia(s)</td><td class='centered'><strong>" . $vgusr[2] . "/" . tools::reducemes(tools::obtenermes($vgusr[1])) . "/" . $vgusr[0] . "</strong></td>" . $edit . "</tr>";
            $conta--;
        }if ($tot_myvacs == 0) {
            echo "<tr class='centered'><td colspan='7' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No has cumplido aun el a&ntilde;o requerido para el primer periodo de vacaciones!</td></tr>";
        }
    }
}

function retsolfechas($parcad) {
    $arrayvacs = '';
    $contcad = 0;
    foreach ($parcad as $cadfecha) {
        $ndates = explode("-", $cadfecha);
        $rfecha = $ndates[2] . '-' . tools::reducemes(tools::obtenermes($ndates[1])) . '-' . $ndates[0];
        if ($contcad == 0) {
            $arrayvacs = $arrayvacs . $rfecha;
        } else {
            $arrayvacs = $arrayvacs . ',' . $rfecha;
        }$contcad++;
    }return $arrayvacs;
}

function msgerrlogrcpas() {
    if (isset($_SESSION['estatus']) and isset($_SESSION['alertlog'])) {
        $estatus = $_SESSION['estatus'];
        $alertlog = $_SESSION['alertlog'];
        $estiloerr = 'style="background-color: #EEA236;"';
    } else {
        $estatus = 'danger';
        $estiloerr = 'style="background-color: #953b39;"';
        $alertlog = 'Usuario o password Incorrectos!';
    }echo '<!-- Modal --><div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mrecpass" data-backdrop="static" data-keyboard="false" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" id="cierrarcpass" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Recupera tu acceso!</h4></div><div class="modal-body"><p>Ingrese su NSS y fecha de ingreso.</p><div class="row"><div class="col-lg-6"><div class="form-group"><input type="text" name="empleado" placeholder="NSS" autocomplete="off" class="form-control placeholder-no-fix" maxlength="11" id="vempleado" autofocus="true" /></div></div><div class="col-lg-6"><div class="form-group"><input type="text" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask="" name="email" placeholder="Fecha Ingreso" autocomplete="off" class="form-control placeholder-no-fix" id="vemail"></div></div><div class="col-lg-12" style="margin-top: 2%; margin-bottom: -2%; display: none;" id="clerror"><div class="form-group"><div id="errordatos" class="alert alert-danger"><b>Un momento!</b> los datos ingresados no son validos verificalos.</div></div></div></div></div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button" id="canenvmail">Cancelar</button><button class="btn btn-primary" type="button" id="envmail">Enviar</button></div></div></div></div><!-- modal --><!-- modal-error--><div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="modalError" class="modal fade" data-keyboard="false" data-backdrop="static"><div class="modal-dialog"><div class="modal-content panel-' . $estatus . '"><div class="modal-header panel-heading" ' . $estiloerr . '><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Mensaje del sistema!</h4></div><div class="modal-body"><p>' . $alertlog . '</p></div><div class="modal-footer"><button id="focuser" type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button></div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal --><!-- modal-erro-->';
}

function imprimeempresausr($dtprm) {
    global $conexion;
    if ($conexion) {
        $sql_gtemp = "SELECT descripcion FROM `com_empresas` E INNER JOIN `com_usuarios` U ON E.`codigo` = U.`empresa` WHERE E.`codigo` = " . $dtprm . " LIMIT 1;";
        $rs_gtemp = mysqli_query($conexion, $sql_gtemp);
        $nemp = mysqli_fetch_assoc($rs_gtemp);
        return utf8_encode($nemp['descripcion']);
    }
}

function actctmn($idmnact, $namact, $descact) {
    global $conexion;
    if ($conexion) {
        $sql_updatemnct = "UPDATE com_menus SET nombre = '" . utf8_decode($namact) . "', descripcion = '" . utf8_decode($descact) . "' WHERE id = '" . $idmnact . "'";
        $res_updatemnct = mysqli_query($conexion, $sql_updatemnct);
        if ($res_updatemnct) {
            echo "Registro actualizado con exito!";
        }
    }
}

function retorndscmn($vlidmn) {
    global $conexion;
    if ($conexion) {
        if (!empty($vlidmn)) {
            $sql_datamn = "SELECT nombre, descripcion FROM com_menus WHERE id = '" . $vlidmn . "'";
            $rs_datamn = mysqli_query($conexion, $sql_datamn);
            $rw_datamn = mysqli_fetch_assoc($rs_datamn);
            echo utf8_encode($rw_datamn['nombre']) . "*" . utf8_encode($rw_datamn['descripcion']);
        }
    }
}

function printcatmenus($shbus) {
    global $conexion;
    if ($conexion) {
        if ($shbus == '') {
            $sql_gtcat = "SELECT * FROM com_menus ORDER BY nombre";
        } else {
            $sql_gtcat = "SELECT * FROM com_menus WHERE nombre like '%" . utf8_decode($shbus) . "%' or descripcion like '%" . utf8_decode($shbus) . "%' ORDER BY nombre";
        }$rs_gtcat = mysqli_query($conexion, $sql_gtcat);
        $tot_gtcat = mysqli_num_rows($rs_gtcat);
        while ($rw_gtcat = mysqli_fetch_assoc($rs_gtcat)) {
            echo "<tr><td width=\"25%\" class='addcat' id='nombmn_" . $rw_gtcat['id'] . "' style='cursor: pointer;'>" . utf8_encode($rw_gtcat['nombre']) . "</td><td width=\"65%\" class='addcat' id='descmn_" . $rw_gtcat['id'] . "' style='cursor: pointer;'>" . utf8_encode($rw_gtcat['descripcion']) . "</td><td width=\"10%\" class='centered'><a id=\"editcata_" . $rw_gtcat['id'] . "\" class=\"edtcatmn\" style='cursor: pointer;'><i class='fa fa-edit'></i> Editar</a></td></tr>";
        }if ($tot_gtcat == 0) {
            if ($shbus == '') {
                echo "<tr class='centered' style='cursor: pointer;'><td colspan='2' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No hay menus registrados en el catalogo del sistema!</td></tr>";
            } else {
                echo trim('No existe menu en el catalogo!');
            }
        }
    }
}

function generascripterr() {
    echo "<script type=\"text/javascript\">$(document).ready(function(){ $('#modalError').modal('show'); $('#focuser').focus(); $('#modalError').on('hidden.bs.modal', function(){ $('#inisesjs').click(); $('#cruser').focus(); }); });</script>";
}

function printerrorlog() {
    if (isset($_SESSION['userincorrect']) and $_SESSION['userincorrect'] == 1 and ! empty($_POST['userid']) and ! empty($_POST['passuser'])) {
        generascripterr();
    } elseif (!empty($_POST['userid']) and ! empty($_POST['passuser']) and isset($_SESSION['estatus']) and $_SESSION['estatus'] = 'warning') {
        generascripterr();
    }
}

function prntloading() {
    echo '<!-- Modal Start here--><div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"><span class="fa fa-time"></span> Procesando transaccion...</h4></div><div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 100%"></div></div></div></div></div></div>';
}

function menusem($vrcb, $paramini, $paramfin) {
    global $conexion;
    if ($conexion) {
        if (!empty($paramini) and ! empty($paramfin)) {
            $sql_cargaslmn = "SELECT * FROM `com_menuporfecha` WHERE `fecha` BETWEEN CAST('" . $paramini . "' AS DATE) AND CAST('" . $paramfin . "' AS DATE) GROUP BY `fecha` ORDER BY `fecha`;";
            echo "<option value='0'>Selecciona el menu</option>";
        } else {
            $sql_cargaslmn = "SELECT * FROM `com_menuporfecha` WHERE `fecha` >= CURDATE() GROUP BY `fecha` ORDER BY `fecha`;";
        }$rs_cargaslmn = mysqli_query($conexion, $sql_cargaslmn);
        if ($vrcb == 1) {
            echo "<option value='esp'>Personalizado</option>";
        }while ($rw_cargaslmn = mysqli_fetch_assoc($rs_cargaslmn)) {
            $nfecha = explode("-", $rw_cargaslmn['fecha']);
            if (strtotime($rw_cargaslmn['fecha']) == strtotime(date('Y-m-d'))) {
                $aractual = $_SERVER['PHP_SELF'];
                $pactual = explode("/", $aractual);
                if (end($pactual) == 'carg_consum.php'){
                    $seloption = '';
                } else {
                    $seloption = "selected='selected'";
                }
            } else {
                $seloption = '';
            }echo "<option value='" . $rw_cargaslmn['id'] . "' " . $seloption . ">" . tools::retornadia($rw_cargaslmn['dia']) . ' ' . $nfecha[2] . ' de ' . tools::obtenermes($nfecha[1]) . ' de ' . $nfecha[0] . "</option>";
        }mysqli_free_result($rs_cargaslmn);
    }
}

function llenaoptionsmnucrg($idmnucons) {
    global $conexion;
    if ($conexion) {
        $sql_vermenus = "SELECT * FROM com_menuporfecha WHERE id = '" . trim($idmnucons) . "'";
        $rs_vermenus = mysqli_query($conexion, $sql_vermenus);
        $rw_vermenus = mysqli_fetch_assoc($rs_vermenus);
        $sql_genoptsmnus = "SELECT F.id AS selmn, M.nombre FROM com_menuporfecha F INNER JOIN com_menus M ON F.menu = M.id WHERE fecha = '" . trim($rw_vermenus['fecha']) . "'";
        $rs_genoptsmnus = mysqli_query($conexion, $sql_genoptsmnus);
        $tots_genoptsmnus = mysqli_num_rows($rs_genoptsmnus);
        if ($tots_genoptsmnus > 1) {
            echo "<option value='0'>Elige el menu</option>";
            while ($rw_genoptsmnus = mysqli_fetch_assoc($rs_genoptsmnus)) {
                echo "<option value='" . $rw_genoptsmnus['selmn'] . "'>" . utf8_encode($rw_genoptsmnus['nombre']) . "</option>";
            }
        }mysqli_free_result($rs_vermenus);
        mysqli_free_result($rs_genoptsmnus);
    }
}

function actapdspsystem($aprt, $dspsys, $idactmn, $accion, $visit) {
    global $conexion;
    if ($conexion) {
        if ($accion == true) {
            if (!empty($visit)) {
                $vactualan = $aprt;
                $vactualan = intval($vactualan) + $visit;
                $vactualdsp = $dspsys;
                $vactualdsp = intval($vactualdsp) - $visit;
            } else {
                $vactualan = $aprt;
                $vactualan = intval($vactualan) + 1;
                $vactualdsp = $dspsys;
                $vactualdsp = intval($vactualdsp) - 1;
            }
        } else {
            if (!empty($visit)) {
                $vactualan = $aprt;
                $vactualan = intval($vactualan) - $visit;
                $vactualdsp = $dspsys;
                $vactualdsp = intval($vactualdsp) + $visit;
            } else {
                $vactualan = $aprt;
                $vactualan = intval($vactualan) - 1;
                $vactualdsp = $dspsys;
                $vactualdsp = intval($vactualdsp) + 1;
            }
        }$update_globalcnt = "UPDATE aplicaciones.com_menuporfecha SET apartados = '" . $vactualan . "', disponibles = '" . $vactualdsp . "' WHERE com_menuporfecha.id = " . $idactmn . ";";
        $rs_retorupd = mysqli_query($conexion, $update_globalcnt);
        if ($rs_retorupd) {
            return true;
        } else {
            return false;
        }
    }
}

function coccrgmnuusr($mnaf, $usraf) {
    global $conexion;
    if ($conexion) {
        $sql_crgmnu = "SELECT * FROM com_menuporfecha WHERE id = '" . $mnaf . "'";
        $rs_crgmnu = mysqli_query($conexion, $sql_crgmnu);
        $rw_crgmnu = mysqli_fetch_assoc($rs_crgmnu);
        if ($rw_crgmnu['disponibles'] > 0) {
            $sql_crganotacion = "INSERT INTO `aplicaciones`.`com_anotaciones` (`id`, `usuario`, `menuporfecha`, `registro`) VALUES (NULL, '" . $usraf . "', '" . $mnaf . "', now());";
            $execute = mysqli_query($conexion, $sql_crganotacion);
            echo trim('Usuario registrado con exito!');
            $sumar = true;
            actapdspsystem($rw_crgmnu['apartados'], $rw_crgmnu['disponibles'], $mnaf, $sumar, '');
        } else {
            echo trim('Menu agotado, ya no hay platillos disponibles para esta fecha!');
        }
    }
}

function delusrmnucoc($mncoc, $iduscoc) {
    global $conexion;
    if ($conexion) {
        $sql_obtmnudlusr = "SELECT apartados, disponibles FROM com_menuporfecha WHERE id = '" . $mncoc . "'";
        $rs_obtmnudlusr = mysqli_query($conexion, $sql_obtmnudlusr);
        $rw_obtnmnudludr = mysqli_fetch_assoc($rs_obtmnudlusr);
        $sumar = false;
        $rsupdcoc = actapdspsystem($rw_obtnmnudludr['apartados'], $rw_obtnmnudludr['disponibles'], $mncoc, $sumar, '');
        if ($rsupdcoc == true) {
            $sql_dlusmncoc = "DELETE FROM com_anotaciones WHERE usuario = '" . $iduscoc . "' and menuporfecha = '" . $mncoc . "'";
            $execute = mysqli_query($conexion, $sql_dlusmncoc);
            echo "Usuario eliminado del menu exitosamente!";
        } else {
            echo "Error al actualizar los globales del menu, no se pudo eliminar al usuario!";
        }mysqli_free_result($rs_obtmnudlusr);
    }
}

function eliminamrcns($datadl) {
    global $conexion;
    if ($conexion) {
        $sql_apdspglb = "SELECT F.apartados, F.disponibles, F.id AS actualiza FROM `com_anotaciones` A INNER JOIN `com_menuporfecha` F ON A.menuporfecha = F.id WHERE A.id = " . $datadl;
        $rs_apdspglb = mysqli_query($conexion, $sql_apdspglb);
        $rw_apdspglb = mysqli_fetch_assoc($rs_apdspglb);
        $aparts = $rw_apdspglb['apartados'];
        $disps = $rw_apdspglb['disponibles'];
        $act_system = $rw_apdspglb['actualiza'];
        $update_glbs = "UPDATE com_menuporfecha SET apartados = '" . ($aparts - 1) . "', disponibles = '" . ($disps + 1) . "' WHERE id = " . $act_system;
        $rs_glbs = mysqli_query($conexion, $update_glbs);
        if ($rs_glbs) {
            $sql_dlmrcns = "DELETE FROM com_anotaciones WHERE id = '" . $datadl . "'";
            $rs_dlmrcns = mysqli_query($conexion, $sql_dlmrcns);
            if ($rs_dlmrcns) {
                echo "Usuario eliminado con exito!";
            }
        }
    }
}

function llenatablacrgmn($cnid) {
    global $conexion;
    if ($conexion) {
        if (empty($cnid)) {
            $sql_crguserstbl = "SELECT SUBSTRING(U.usuario, 3) AS empleado, U.nombre, E.descripcion, F.fecha, A.registro, A.id as borrame, A.usuario  FROM `com_anotaciones` A INNER JOIN `com_menuporfecha` F ON A.menuporfecha = F.id INNER JOIN `com_usuarios` U ON A.usuario = U.id INNER JOIN `com_empresas` E ON U.empresa = E.codigo WHERE F.fecha = CURDATE() order by U.nombre;";
        } else {
            $sql_crguserstbl = "SELECT SUBSTRING(U.usuario, 3) AS empleado, U.nombre, E.descripcion, F.fecha, A.registro, A.id as borrame, A.usuario  FROM `com_anotaciones` A INNER JOIN `com_menuporfecha` F ON A.menuporfecha = F.id INNER JOIN `com_usuarios` U ON A.usuario = U.id INNER JOIN `com_empresas` E ON U.empresa = E.codigo WHERE F.id = " . $cnid . " order by U.nombre;";
        }$rs_crguserstbl = mysqli_query($conexion, $sql_crguserstbl);
        $tot_crgusertbl = mysqli_num_rows($rs_crguserstbl);
        if ($tot_crgusertbl > 0) {
            while ($rows_crguserstbl = mysqli_fetch_assoc($rs_crguserstbl)) {
                $sepdates = explode("-", $rows_crguserstbl['fecha']);
                echo "<tr><td>" . $rows_crguserstbl['empleado'] . "</td><td>" . utf8_encode($rows_crguserstbl['nombre']) . "</td><td class='centered'>" . $sepdates[2] . "/" . tools::obtenermes($sepdates[1]) . "/" . $sepdates[0] . "</td><td class='centered'>" . $rows_crguserstbl['registro'] . "</td><td class='centered'><a id=\"crgdel_" . $rows_crguserstbl['borrame'] . "\" class=\"delcrg\" style=\"cursor: pointer;\"><i class=\"fa fa-remove\"></i></a></td></tr>";
            }echo "<tr><td colspan=\"6\"><strong>Registrados: <span style='font-size: larger;'>" . $tot_crgusertbl . "</span></strong></td></tr>";
        } else {
            echo "<tr><td colspan='5' class='centered' style='font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No hay usuarios registrados en el sistema!</td></tr>";
        }mysqli_free_result($rs_crguserstbl);
    }
}

function elimmn($elim, $idmen) {
    global $conexion;
    if ($conexion) {
        $sql_delmn = "DELETE FROM `aplicaciones`.`com_anotaciones` WHERE `com_anotaciones`.`id` = " . $elim;
        $execute = mysqli_query($conexion, $sql_delmn);
        $sql_obtap = "SELECT * FROM com_menuporfecha WHERE id = '" . $idmen . "'";
        $rs_obtap = mysqli_query($conexion, $sql_obtap);
        $rw_obtap = mysqli_fetch_assoc($rs_obtap);
        $sumar = false;
        $resupd = actapdspsystem($rw_obtap['apartados'], $rw_obtap['disponibles'], $idmen, $sumar, '');
        if ($resupd == true) {
            echo "Registro borrado con exito!";
        } else {
            echo "Error al actualizar el global de consumos!";
        }mysqli_free_result($rs_obtap);
    }
}

function misconsumos() {
    global $conexion;
    if ($conexion) {
        $sql_mconsumos = "SELECT F.dia, M.nombre, M.descripcion, F.fecha, R.registro, R.id as cancelame FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.menuporfecha = F.id INNER JOIN `com_menus` M ON F.menu = M.id WHERE `usuario` = '" . $_SESSION['idloguser'] . "' AND F.fecha >= CURDATE() - INTERVAL 45 DAY ORDER BY F.id DESC;";
        $rs_mconsumos = mysqli_query($conexion, $sql_mconsumos);
        $tot_mconsumos = mysqli_num_rows($rs_mconsumos);
        if ($tot_mconsumos > 0) {
            while ($rows_mconsumos = mysqli_fetch_assoc($rs_mconsumos)) {
                echo "<tr><td>";
                echo tools::retornadia($rows_mconsumos['dia']);
                echo "</td><td>" . utf8_encode($rows_mconsumos['nombre']) . "</td><td>" . utf8_encode($rows_mconsumos['descripcion']) . "</td><td>";
                $descdt = explode("-", $rows_mconsumos['fecha']);
                echo $descdt[2] . '/' . tools::obtenermes($descdt[1]) . '/' . $descdt[0];
                echo "</td><td>" . $rows_mconsumos['registro'] . "</td>";
                if (strtotime($rows_mconsumos['fecha']) > strtotime(date('Y-m-d')) OR ( strtotime($rows_mconsumos['fecha']) == strtotime(date('Y-m-d')) and strtotime(date('H:i')) < strtotime('12:00'))) {
                    echo "<td><a class=\"cancelamn\" id='canmn_" . $rows_mconsumos['cancelame'] . "' style=\"cursor:pointer;\">Cancelar</a></td></tr>";
                } else {
                    echo "<td style='color: darkred; font-weight: 600; cursor: not-allowed;'>Concluido</td>";
                }
            }
        } else {
            echo "<tr style='text-align: center;'><td colspan='6' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No te has registrado en ningun menu de cocina!</td></tr>";
        }echo "<tr><td></td><td colspan='3'><span style=\"font-weight: bold; font-family: 'Comic Sans MS'; font-size: 1em;\">TOTALES: $ <u id=\"totmcons\">" . totmcons($_SESSION['idloguser']) . "</u></span></td><td colspan='2'><span style=\"font-weight: bold; font-family: 'Comic Sans MS'; font-size: 1em;\">PLATILLOS: <u>" . $tot_mconsumos . "</u></span></td></tr>";
        mysqli_free_result($rs_mconsumos);
    }
}

function consxrg($inf, $fnf) {
    $busquedarango = "SELECT E.codigo , U.empresa, U.id AS iduser, M.id AS idmenu, F.menu, F.id, R.menuporfecha, U.`usuario` AS empleado, U.`nombre` AS nomusuario, M.`nombre` AS nommenu, E.`descripcion` AS empresa, F.`fecha` AS fechamenu, R.`registro` AS anotado FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` INNER JOIN `com_usuarios` U ON R.`usuario` = U.`id` INNER JOIN `com_empresas` E ON U.`empresa` = E.`codigo` AND F.`fecha` BETWEEN CAST('" . $inf . "' AS DATE ) AND CAST('" . $fnf . "' AS DATE )";
    anotadosmenu($busquedarango, false);
}

function consvstxrg($vstin, $vstfn) {
    global $conexion;
    if ($conexion) {
        $vstrango = "SELECT M.`nombre`, F.`fecha`, V.`cantidad`, V.`precio`, (V.`cantidad` * V.`precio`) AS importe, V.`registro` FROM `com_visitantes` V INNER JOIN `com_menuporfecha` F ON V.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` WHERE F.`fecha` BETWEEN CAST('" . $vstin . "' AS DATE ) AND CAST('" . $vstfn . "' AS DATE )";
        consvisitsmnu($vstrango, '');
        $sql_obtpltrg = "SELECT F.`fecha`, SUM(V.`cantidad`) AS platillos, SUM(V.`cantidad` * V.`precio`) AS total FROM `com_visitantes` V INNER JOIN `com_menuporfecha` F ON V.`menuporfecha` = F.`id` WHERE F.`fecha` BETWEEN CAST('" . $vstin . "' AS DATE ) AND CAST('" . $vstfn . "' AS DATE );";
        $rs_obtpltrg = mysqli_query($conexion, $sql_obtpltrg);
        $tot_obtpltrg = mysqli_num_rows($rs_obtpltrg);
        $rw_obtpltrg = mysqli_fetch_assoc($rs_obtpltrg);
        if ($tot_obtpltrg > 0 and $rw_obtpltrg['platillos'] != null) {
            echo "<tr><td colspan='2'>CARGOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $tot_obtpltrg . "</strong></td><td colspan='2'>PLATILLOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obtpltrg['platillos'] . "</strong></td><td colspan='2'>TOTAL: $<strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obtpltrg['total'] . "</strong></td></tr>";
        }
    }
}

function blqdtvacs($dtlck) {
    $rcvdt = $dtlck;
    global $conexion;
    if ($conexion) {
        $sql_rvfest = "SELECT * FROM `vac_festivos` WHERE `fecha` = '" . $rcvdt . "';";
        $rs_rvfest = mysqli_query($conexion, $sql_rvfest);
        $tot_rvfest = mysqli_num_rows($rs_rvfest);
        if ($tot_rvfest == 0) {
            $sql_addfest = "INSERT INTO `aplicaciones`.`vac_festivos` (`id`, `fecha`) VALUES (NULL, '" . $rcvdt . "');";
            $rs_addfest = mysqli_query($conexion, $sql_addfest);
            $sep_dt = explode("-", $rcvdt);
            if ($rs_addfest) {
                echo " Se ha bloqueado " . $sep_dt[2] . "-" . tools::reducemes(tools::obtenermes($sep_dt[1])) . "-" . $sep_dt[0] . "!";
            }
        }
    }
}

function selectfest() {
    global $conexion;
    if ($conexion) {
        $sql_fest = "SELECT * FROM `vac_festivos` WHERE `fecha` > CURDATE() ORDER BY `fecha` ASC;";
        $rs_fest = mysqli_query($conexion, $sql_fest);
        while ($rw_fest = mysqli_fetch_assoc($rs_fest)) {
            $fchlock = explode("-", $rw_fest['fecha']);
            $mntlock = strtoupper(tools::obtenermes($fchlock[1]));
            echo "<option value='" . $rw_fest['id'] . "'>" . $fchlock[2] . "-" . $mntlock . "-" . $fchlock[0] . "</option>";
        }
    }
}
function slUpdMn(){
    global $conexion;
    if($conexion){
        $str_omn = "";
        $sql_slUpdMn = "SELECT * FROM com_menus ORDER BY nombre";
        $rs_slUpdMn = mysqli_query($conexion, $sql_slUpdMn);
        while($rw_slUpdMn = mysqli_fetch_assoc($rs_slUpdMn)){
            $str_omn .= "<option value='".$rw_slUpdMn['id']."'>".utf8_encode($rw_slUpdMn['nombre'])."</option>";
        }
        if(!empty($str_omn)) return $str_omn;
    }
}
function selectusers() {
    global $conexion;
    if ($conexion) {
        echo "<option value=\"0\">Elige el usuario</option>";
        $sql_users = "SELECT id,nombre FROM com_usuarios WHERE tipo = '4' and estatus = 1 ORDER BY nombre;";
        $rs_users = mysqli_query($conexion, $sql_users);
        while ($rw_users = mysqli_fetch_assoc($rs_users)) {
            echo "<option value='" . $rw_users['id'] . "'>" . utf8_encode($rw_users['nombre']) . "</option>";
        }
    }
}

function anotadosmenu($busqueda, $bussh) {
    global $conexion;
    if ($conexion) {
        $sql_anotados = $busqueda;
        $rs_anotados = mysqli_query($conexion, $sql_anotados);
        $tot_anotados = mysqli_num_rows($rs_anotados);
        if ($tot_anotados > 0) {
            while ($rows_anotados = mysqli_fetch_assoc($rs_anotados)) {
                $nfecha = explode("-", $rows_anotados['fechamenu']);
                echo "<tr><td>" . substr($rows_anotados['empleado'], 2) . "</td><td>" . utf8_encode($rows_anotados['nomusuario']) . "</td><td>" . utf8_encode($rows_anotados['nommenu']) . "</td><td>" . utf8_encode($rows_anotados['empresa']) . "</td><td>" . $nfecha[2] . "/" . tools::reducemes(tools::obtenermes($nfecha[1])) . "/" . $nfecha[0] . "</td><td>" . $rows_anotados['anotado'] . "</td></tr>";
            }echo "<tr><td colspan='3' align='left' style='text-align: left;'>REGISTRADOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $tot_anotados . "</strong></td>";
        } else {
            if ($bussh == true and $tot_anotados == 0) {
                echo "0";
            } else {
                echo "<tr style='text-align: center;'><td colspan='6' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No hay empleados registrados para esta fecha!</td></tr>";
            }
        }mysqli_free_result($rs_anotados);
    }
}

function registraempresa($empresa, $vempresa) {
    global $conexion;
    if ($conexion) {
        $sql_revisaempresa = "SELECT * FROM com_empresas WHERE codigo = '" . $empresa . "'";
        $rs_revisaempresa = mysqli_query($conexion, $sql_revisaempresa);
        $tots_revisaempresa = mysqli_num_rows($rs_revisaempresa);
        if ($tots_revisaempresa == 0) {
            $sql_insertempresa = "INSERT INTO `aplicaciones`.`com_empresas` (`id`, `codigo`, `descripcion`) VALUES (NULL, '" . $empresa . "', '" . $vempresa . "');";
            $execute = mysqli_query($conexion, $sql_insertempresa);
        }mysqli_free_result($rs_revisaempresa);
    }
}

function totmcons($usract) {
    global $conexion;
    if ($conexion) {
        $sql_usermcons = "SELECT SUM(F.precio) AS total FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.menuporfecha = F.id INNER JOIN `com_menus` M ON F.menu = M.id WHERE `usuario` = '" . $usract . "' AND f.fecha >= CURDATE() - INTERVAL 45 DAY";
        $rs_usermcoms = mysqli_query($conexion, $sql_usermcons);
        $rw_usermcoms = mysqli_fetch_assoc($rs_usermcoms);
        if ($rw_usermcoms['total'] == null) {
            return "0.00";
        } else {
            return $rw_usermcoms['total'];
        }mysqli_free_result($rs_usermcoms);
    }
}

function sugscoc($paramtot) {
    global $conexion;
    if ($conexion) {
        $sql_sugcoc = "SELECT * FROM com_sugerencias ORDER BY fecha DESC LIMIT 45";
        $rs_sugcoc = mysqli_query($conexion, $sql_sugcoc);
        $tot_sugcoc = mysqli_num_rows($rs_sugcoc);
        if (!empty($paramtot)) {
            return $tot_sugcoc;
        } else {
            while ($rw_sugcoc = mysqli_fetch_assoc($rs_sugcoc)) {
                $sql_nomusr = "SELECT nombre, usuario, empresa FROM com_usuarios WHERE id = '" . $rw_sugcoc['usuario'] . "'";
                $rs_nomusr = mysqli_query($conexion, $sql_nomusr);
                $rw_nomusr = mysqli_fetch_assoc($rs_nomusr);
                echo "<tr><td>" . substr($rw_nomusr['usuario'], 2, strlen($rw_nomusr['usuario'])) . "</td><td>" . utf8_encode($rw_nomusr['nombre']) . "</td><td>" . $rw_sugcoc['titulo'] . "</td><td style=\"word-wrap: break-word;\">" . $rw_sugcoc['sugerencia'] . "</td><td>" . $rw_sugcoc['fecha'] . "</td><td>" . consultas::retnmempr($rw_nomusr['empresa']) . "</td></tr>";
            }if ($tot_sugcoc == 0) {
                echo "<tr class='centered'><td colspan='6' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No existen sugerencias de usuarios aun en el sistema!</td></tr>";
            }mysqli_free_result($rs_sugcoc);
        }
    }
}

function missugs($iduser) {
    global $conexion;
    if ($conexion) {
        $sql_missgs = "SELECT * FROM `com_sugerencias` WHERE `usuario` = " . $iduser . " ORDER BY `fecha` DESC LIMIT 45 ";
        $rs_missgs = mysqli_query($conexion, $sql_missgs);
        $tot_missgs = mysqli_num_rows($rs_missgs);
        while ($rw_missgs = mysqli_fetch_assoc($rs_missgs)) {
            echo "<tr><td>" . $rw_missgs['titulo'] . "</td><td>" . $rw_missgs['sugerencia'] . "</td><td>" . $rw_missgs['fecha'] . "</td></tr>";
        }if ($tot_missgs == 0) {
            echo "<tr class='centered'><td colspan='3' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No has enviado ninguna sugerencia a cocina!</td></tr>";
        }mysqli_free_result($rs_missgs);
    }
}

function registrasugs($titulo, $sugerencias, $idntuser) {
    global $conexion;
    if ($conexion) {
        $sql_regsugs = "INSERT INTO `aplicaciones`.`com_sugerencias` (`id`, `usuario`, `titulo`, `sugerencia`, `fecha`) VALUES (NULL, '" . $idntuser . "', '" . $titulo . "', '" . $sugerencias . "', now());";
        $rs_regsugs = mysqli_query($conexion, $sql_regsugs);
        if ($rs_regsugs) {
            echo "Sugerencias enviadas exitosamente!";
        } else {
            echo "Error al enviar las sugerencias!";
        }
    }
}

function vrglobsxmn($mnidvr) {
    global $conexion;
    if ($conexion) {
        $sql_glbmnvr = "SELECT apartados, disponibles FROM com_menuporfecha WHERE id = '" . $mnidvr . "'";
        $rs_glbmnvr = mysqli_query($conexion, $sql_glbmnvr);
        $rw_glbmnvr = mysqli_fetch_assoc($rs_glbmnvr);
        echo $rw_glbmnvr['apartados'] . "," . $rw_glbmnvr['disponibles'];
        mysqli_free_result($rs_glbmnvr);
    }
}

function generadoslogs() {
    global $conexion;
    if ($conexion) {
        $sql_logsgns = "SELECT * FROM `com_logreportes` order by generado DESC LIMIT 90";
        $rs_logsgns = mysqli_query($conexion, $sql_logsgns);
        $tots_logsns = mysqli_num_rows($rs_logsgns);
        while ($rw_logsgns = mysqli_fetch_assoc($rs_logsgns)) {
            $sql_gtusr = "SELECT nombre FROM com_usuarios WHERE id = '" . $rw_logsgns['usuario'] . "'";
            $rs_gtusr = mysqli_query($conexion, $sql_gtusr);
            $rw_gtusr = mysqli_fetch_assoc($rs_gtusr);
            echo "<tr><td>" . $rw_logsgns['host'] . "</td><td>" . $rw_logsgns['ip'] . "</td><td>" . $rw_logsgns['generado'] . "</td><td>" . $rw_logsgns['fechaini'] . "</td><td>" . $rw_logsgns['fechafin'] . "</td><td>" . consultas::retnmempr($rw_logsgns['empresa']) . "</td></tr>";
        }if ($tots_logsns == 0) {
            echo "<tr class='centered'><td colspan='7' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No se han generado reportes del sistema aun!</td></tr>";
        }
    }
}

function cerradom($vdia, $disponiblesoff, $cmes, $lane){
    $lclen = $lane."-".$cmes."-".$vdia;
    if(strtotime($lclen) < strtotime(date('Y-m-d')) OR ($lclen == date('Y-m-d') AND strtotime(date('H:i')) >= strtotime('12:00'))){
        return true;
    }else{
        return false;
    }
}

function prntmenususer() {
    global $conexion;
    if ($conexion) {
        $sempost = date('W') + 1;
        if (date('w') == 4 or date('w') == 5 or date('w') == 6 or date('w') == 0) {
            $sql_pmenus = "SELECT * FROM com_menuporfecha WHERE (semana = WEEKOFYEAR(CURDATE()) AND YEAR(fecha) >= YEAR(CURDATE())) OR semana = WEEKOFYEAR(ADDDATE(CURDATE(), INTERVAL 1 WEEK)) AND fecha >= CURDATE() GROUP BY fecha order by fecha";
        } else {
            $sql_pmenus = "SELECT * FROM com_menuporfecha WHERE (semana = WEEKOFYEAR(CURDATE()) AND YEAR(fecha) >= YEAR(CURDATE())) GROUP BY fecha order by fecha";
        }$rs_pmenus = mysqli_query($conexion, $sql_pmenus);
        while ($rows_pmenus = mysqli_fetch_assoc($rs_pmenus)) {
            $valorfecha = explode("-", $rows_pmenus['fecha']);
            $anio = $valorfecha[0];
            $mes = $valorfecha[1];
            $diad = $valorfecha[2];
            echo '<div class="col-lg-4 col-lg-4 col-sm-4 mb"><div class="content-panel pn"><div id="blog-bg" style="';
            if ($rows_pmenus["dia"] == 1) {
                echo 'background: url(assets/img/blog-bg.jpg) no-repeat center top;';
            } elseif ($rows_pmenus["dia"] == 2) {
                echo 'background: url(assets/img/2.jpg) no-repeat center top;';
            } else if ($rows_pmenus["dia"] == 3) {
                echo 'background: url(assets/img/3.jpg) no-repeat center top;';
            } else if ($rows_pmenus["dia"] == 4) {
                echo 'background: url(assets/img/4.jpg) no-repeat center top;';
            } else if ($rows_pmenus["dia"] == 5) {
                echo 'background: url(assets/img/5.jpg) no-repeat center top;';
            }echo '">';
            if (cerradom($diad, $rows_pmenus['disponibles'], $mes, $anio) == true or ($mes < date('m') and $anio <= date('Y'))){
                echo '<div class="badge">Cerrado</div>';
            }$sql_consregmn = "SELECT * FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.menuporfecha = F.id WHERE R.`usuario` = '" . $_SESSION['idloguser'] . "' and F.`fecha` = '" . $rows_pmenus['fecha'] . "';";
            $rs_consregmn = mysqli_query($conexion, $sql_consregmn);
            $rw_consregmn = mysqli_fetch_assoc($rs_consregmn);
            $tot_consregmn = mysqli_num_rows($rs_consregmn);
            if ($tot_consregmn > 0) {
                $sql_consmnreg = "SELECT * FROM com_menuporfecha WHERE id = '" . $rw_consregmn['menuporfecha'] . "'";
                $rs_consmnreg = mysqli_query($conexion, $sql_consmnreg);
                $rw_consmnreg = mysqli_fetch_assoc($rs_consmnreg);
                echo '<div id="dtapdsp_' . $rw_consmnreg["id"] . '" class="badge detalleapdsp apdspbg_';
                if ($rows_pmenus['semana'] == $sempost) {
                    echo "s";
                }echo $rw_consmnreg["dia"] . '" style="left: 71.85%; background-color: rgb(51, 107, 44);">A' . $rw_consmnreg["apartados"] . '/D' . $rw_consmnreg["disponibles"] . '</div><div class="blog-title">' . tools::retornadia($rw_consmnreg["dia"]) . ' ' . $diad . '/' . tools::reducemes(tools::obtenermes($mes)) . '/' . $anio . '</div></div><div class="blog-text">';
            } else {
                echo '<div id="dtapdsp_' . $rows_pmenus["id"] . '" class="badge detalleapdsp apdspbg_';
                if ($rows_pmenus['semana'] == $sempost) {
                    echo "s";
                }echo $rows_pmenus["dia"] . '" style="left: 71.85%; background-color: rgb(51, 107, 44);">A' . $rows_pmenus["apartados"] . '/D' . $rows_pmenus["disponibles"] . '</div><div class="blog-title">' . tools::retornadia($rows_pmenus["dia"]) . ' ' . $diad . '/' . tools::reducemes(tools::obtenermes($mes)) . '/' . $anio . '</div></div><div class="blog-text">';
            }if ($rows_pmenus['semana'] == $sempost) {
                echo '<p>Nombre: <select class="slmnusr" id="sldia_s' . $rows_pmenus["dia"] . '" ';
            } else {
                echo '<p>Nombre: <select class="slmnusr" id="sldia_' . $rows_pmenus["dia"] . '" ';
            }echo '>';
            $sql_gtnamdesc = "SELECT nombre, descripcion FROM com_menus WHERE id = '" . $rows_pmenus["menu"] . "'";
            $rs_gtnamdesc = mysqli_query($conexion, $sql_gtnamdesc);
            $rw_gtnamdesc = mysqli_fetch_assoc($rs_gtnamdesc);
            $sql_genopt = "SELECT * FROM com_menuporfecha WHERE fecha = '" . $rows_pmenus['fecha'] . "'";
            $rs_genopt = mysqli_query($conexion, $sql_genopt);
            while ($rw_genopt = mysqli_fetch_assoc($rs_genopt)) {
                $sql_mnoptions = "SELECT nombre FROM com_menus WHERE id = '" . $rw_genopt['menu'] . "'";
                $rs_mnoptions = mysqli_query($conexion, $sql_mnoptions);
                $rw_mnoptions = mysqli_fetch_assoc($rs_mnoptions);
                echo "<option value=\"" . $rw_genopt['id'] . "\" ";
                if ($rw_genopt['id'] == $rw_consregmn['menuporfecha']) {
                    echo "selected='selected'";
                }echo ">";
                if (strlen($rw_mnoptions['nombre']) > 27) {
                    echo substr(utf8_encode($rw_mnoptions['nombre']), 0, 27) . '...';
                } else {
                    echo utf8_encode($rw_mnoptions['nombre']);
                }echo "</option>";
            }mysqli_free_result($rs_genopt);
            if ($tot_consregmn > 0) {
                $sql_descmn = "SELECT descripcion FROM com_menus WHERE id = " . $rw_consregmn['menu'];
                $rs_descmn = mysqli_query($conexion, $sql_descmn);
                $rw_descmn = mysqli_fetch_assoc($rs_descmn);
                echo '</select><br>Descripcion: <span id="spmndsc_';
                if ($rows_pmenus['semana'] == $sempost) {
                    echo "s";
                }echo $rw_consmnreg["dia"] . '">' . tools::leermascom($rw_descmn["descripcion"]) . '</span> <a data-toggle="modal" href="#mdlvermas_' . $rw_consmnreg["id"] . '" class="verdesc dfldsc_';
                if ($rows_pmenus['semana'] == $sempost) {
                    echo "s";
                }echo $rw_consmnreg["dia"] . '" id="ver_' . $rw_consmnreg["id"] . '">Leer Mas</a></p><button id="anota_' . $rw_consmnreg["id"] . '" ';
            } else {
                echo '</select><br>Descripcion: <span id="spmndsc_';
                if ($rows_pmenus['semana'] == $sempost) {
                    echo "s";
                }echo $rows_pmenus["dia"] . '">' . tools::leermascom($rw_gtnamdesc["descripcion"]) . '</span> <a data-toggle="modal" href="#mdlvermas_' . $rows_pmenus["id"] . '" class="verdesc dfldsc_';
                if ($rows_pmenus['semana'] == $sempost) {
                    echo "s";
                }echo $rows_pmenus["dia"] . '" id="ver_' . $rows_pmenus["id"] . '">Leer Mas</a></p><button id="anota_' . $rows_pmenus["id"] . '" ';
            }mysqli_free_result($rs_consregmn);
            $sql_csantuser = "SELECT * FROM `com_anotaciones` R INNER JOIN `com_menuporfecha` F ON R.menuporfecha = F.id WHERE R.`usuario` = '" . $_SESSION['idloguser'] . "' and F.`fecha` = '" . $rows_pmenus['fecha'] . "';";
            $rs_csantuser = mysqli_query($conexion, $sql_csantuser);
            $tots_csantuser = mysqli_num_rows($rs_csantuser);
            if ($tots_csantuser > 0) {
                echo 'disabled="disabled"';
            }echo ' class="btn btn-theme img-responsive anotamenu btnmnsl_';
            if ($rows_pmenus['semana'] == $sempost) {
                echo "s";
            }echo $rows_pmenus["dia"] . '" ';
            if (cerradom($diad, $rows_pmenus['disponibles'], $mes, $anio) == true or ($mes < date('m') and $anio <= date('Y'))){
                echo "disabled=\"disabled\"";
                $btnvalue = "Cerrado";
            } else {
                if ($tots_csantuser > 0) {
                    $btnvalue = "Anotado";
                } else {
                    $btnvalue = "Anotarme";
                }
            }echo '>' . $btnvalue . '</button></div></div></div>';
            mysqli_free_result($rs_csantuser);
            $sql_genmdl = "SELECT * FROM com_menuporfecha WHERE fecha = '" . $rows_pmenus['fecha'] . "'";
            $rs_genmdl = mysqli_query($conexion, $sql_genmdl);
            while ($rw_genmdl = mysqli_fetch_assoc($rs_genmdl)) {
                echo '<!-- Modal --><div aria-hidden="true" aria-labelledby="mdlvermasLabel" role="dialog" tabindex="-1" id="mdlvermas_' . $rw_genmdl["id"] . '"class="modal fade"><div class="modal-dialog"><div class="modal-content';
                if (cerradom($diad, $rw_genmdl['disponibles'], $mes, $anio) == true or ($mes < date('m') and $anio <= date('Y'))) {
                    echo " panel-danger";
                } elseif ($rw_genmdl['disponibles'] == 0) {
                    echo " panel-danger";
                }echo '"><div class="modal-header';
                if (cerradom($diad, $rw_genmdl['disponibles'], $mes, $anio) == true or ($mes < date('m') and $anio <= date('Y'))) {
                    echo " panel-heading\" style=\"background-color: #953b39;";
                } elseif ($rw_genmdl['disponibles'] == 0) {
                    echo " panel-heading\" style=\"background-color: #953b39;";
                }echo '"><button type="button" class="close cerrardesc" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="titulom_' . $rw_genmdl["id"] . '"></h4></div><div class="modal-body"><p id="contenidomenu_' . $rw_genmdl["id"] . '"></p></div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-default cerrardesc" type="button">Cerrar</button></div></div></div></div><!-- modal -->';
            }mysqli_free_result($rs_genmdl);
        }$totsmenu = mysqli_num_rows($rs_pmenus);
        if ($totsmenu == 0) {
            echo "<center><img src=\"assets/img/sin_menu.png\" class='img-responsive img-rounded' align='center' width='27.5%' height='27.5%' /></center>";
        }mysqli_free_result($rs_pmenus);
    }
}

function letra($param, $mescom, $anioc){
    $ldte = $anioc."-".$mescom."-".$param;
    if (strtotime($ldte) < strtotime(date('Y-m-d')) OR (strtotime($ldte) == strtotime(date('Y-m-d')) and strtotime(date('H:i')) >= strtotime('12:00'))){
        return 'style="color: #F78181;"';
    } else {
        return '';
    }
}

function saber_dia($sfch){
    $dias = array("","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado","Domingo");
    $nombre = $dias[date('N', strtotime($sfch))];
    return $nombre;
}

function mdlEdtMns() {
    $dataRtn = '<div class="modal fade" data-backdrop="static" data-keyboard="false" id="mdledtmen" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="fa fa-retweet"></i> Actualizar Platillo</h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="slupdmn" class="control-label">Menu</label>
            <select id="slupdmn" class="form-control selectpicker show-tick show-menu-arrow" data-header="Selecciona el Platillo" data-live-search="true" data-size="10" aria-describedby="basic-addon" data-style="btn-default">';
    $dataRtn .= slUpdMn();
    $dataRtn .= '</select>
        </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                 <label for="upCnMn" class="control-label">Cantidad</label>
                   <input id="upCnMn" type="text" class="form-control" value=""/>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
               <label for="prMn" class="control-label">Precio</label>
               <input id="prMn" type="text" class="form-control" value=""/>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
               <label for="fMnUpd" class="control-label">Fecha</label>
                <input id="fMnUpd" type="text" class="form-control" value=""/>
                <input id="dSanMn" type="hidden" value="" />
              </div>
            </div>
           </div>
        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="upMnCm"><i class="fa fa-refresh"></i> Actualizar</button>
            </div>
        </div>
    </div>
</div>';
return trim($dataRtn);
}

function imprimetabla() {
    global $conexion;
    if ($conexion) {
        $sql_menus = "SELECT * FROM com_menuporfecha WHERE (semana = WEEKOFYEAR(CURDATE()) AND YEAR(fecha) >= YEAR(CURDATE())) OR semana = WEEKOFYEAR(ADDDATE(CURDATE(), INTERVAL 1 WEEK)) AND fecha >= CURDATE() order by fecha";
        $rs_menus = mysqli_query($conexion, $sql_menus);
        $totmenus = mysqli_num_rows($rs_menus);
        if ($totmenus > 0) {
            while ($rows_menus = mysqli_fetch_assoc($rs_menus)){
                $varfecha = explode("-", $rows_menus['fecha']);
                $fchday = end($varfecha);
                $mescomp = $varfecha[1]; $lanioc = $varfecha[0];
                (stripos($rows_menus['precio'], ".") === false) ? $puntocero = ".00" : $puntocero = "";
                ($_SESSION['gpouser']=='3') ? $cls = " class='dbledtmn' " : $cls = " ";
                echo "<tr".$cls."style='cursor: pointer;' id='".$rows_menus['id']."' onselectstart='return false'><td " . letra($fchday, $mescomp, $lanioc) . "><input id='vidcm_".$rows_menus['id']."' type='hidden' value ='".$rows_menus['menu']."' />" .tools::retornadia($rows_menus['dia']) . "</td>";
                $sql_menucat = "SELECT * FROM com_menus WHERE id = '" . $rows_menus['menu'] . "'";
                $rs_menucat = mysqli_query($conexion, $sql_menucat);
                $rw_menucat = mysqli_fetch_assoc($rs_menucat);
                echo "<td " . letra($fchday, $mescomp,$lanioc) . ">" . utf8_encode($rw_menucat['nombre']) . "</td><td " . letra($fchday, $mescomp,$lanioc) . ">" . utf8_encode($rw_menucat['descripcion']) . "</td>";
                echo " <td " . letra($fchday, $mescomp,$lanioc) . " align=\"center\">" . $rows_menus['cantidad'] . "</td><td " . letra($fchday, $mescomp,$lanioc) . " align=\"center\">" . $rows_menus['apartados'] . "</td><td " . letra($fchday, $mescomp,$lanioc) . " align=\"center\">" . $rows_menus['disponibles'] . "</td><td " . letra($fchday, $mescomp,$lanioc) . ">$" . $rows_menus['precio'] . $puntocero . "</td><td " . letra($fchday, $mescomp,$lanioc) . ">";
                $descomponer = explode("-", $rows_menus['fecha']);
                echo $descomponer[2] . '/' . tools::reducemes(tools::obtenermes($descomponer[1])) . '/' . $descomponer[0]."</td></tr>";
            }
        } else {
            echo "<tr style='text-align: center;'><td colspan='8' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No existen registros aun en el sistema!</td></tr>";
        }mysqli_free_result($rs_menus);
    }
}

function selectcocsmanticp() {
    global $conexion;
    if ($conexion) {
        $nwdate = date("Y-m-d"); $spsdte = explode("-",$nwdate);
        $year=$spsdte[0]; $month=$spsdte[1]; $day=$spsdte[2];

        # Obtenemos el día de la semana de la fecha dada
        $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));

        # el 0 equivale al domingo...
        if($diaSemana==0)
            $diaSemana=7;

        #genera semana1
        $s1Arr = array();
        for($x=0;$x<5;$x++){
            $cnt = $x+1;
            $s1Arr[$x]=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+$cnt,$year));
        }
        #genera semana2
        $s2Arr = array(); $cnt2 = 8;
        for($y=0;$y<5;$y++){
            $s2Arr[$y]=date("d-m-Y",mktime(0,0,0,$month,$day+($cnt2-$diaSemana),$year));
            $cnt2++;
        }
        //leemos los datos de cada arreglo.
        for($z=0;$z<5;$z++){
            $d1 = explode("-",$s1Arr[$z]);
            $sm1=date("W",mktime(0,0,0,$d1[1],$d1[0],$d1[2])); $dS1=date("w",mktime(0,0,0,$d1[1],$d1[0],$d1[2]));
            $lfch = getDateMnus($d1[2],$d1[1],$d1[0]);
            $ladte = $d1[2]."-".$d1[1]."-".$d1[0];
            if($lfch!=1 and strtotime($ladte) >= strtotime(date('Y-m-d'))){
                echo "<option id='ds_" . $d1[0] . "_" . $d1[1] . "_" . $sm1 . "_".$d1[2]."' value='" . $dS1 . "'>" . tools::retornadia($dS1) . " (" . $d1[0] . "-" . tools::obtenermes($d1[1]) ."-".$d1[2].")</option>";
            }
        }
        for($w=0;$w<5;$w++){
            $d2 = explode("-",$s2Arr[$w]);
            $sm2=date("W",mktime(0,0,0,$d2[1],$d2[0],$d2[2])); $dS2=date("w",mktime(0,0,0,$d2[1],$d2[0],$d2[2]));
            $lfch2 = getDateMnus($d2[2],$d2[1],$d2[0]);
            $ladte2 = $d2[2]."-".$d2[1]."-".$d2[0];
            if($lfch2!=1 and strtotime($ladte2) >= strtotime(date('Y-m-d'))){
                echo "<option id='ds_" . $d2[0] . "_" . $d2[1] . "_" . $sm2 . "_".$d2[2]."' value='" . $dS2 . "'>" . tools::retornadia($dS2) . " (" . $d2[0] . "-" . tools::obtenermes($d2[1]) ."-".$d2[2].")</option>";
            }
        }
    }
}
function getDateMnus($anio, $mes, $dia) {
    global $conexion;
    if ($conexion) {
        $sql_nwsem = "SELECT dia FROM com_menuporfecha WHERE fecha = '" . $anio . "-" . $mes . "-" . $dia . "' GROUP BY fecha";
        $rs_nwsem = mysqli_query($conexion, $sql_nwsem);
        $totsnwsem = mysqli_num_rows($rs_nwsem);
        if ($totsnwsem == 0) {
            return 0;
        } else {
            return 1;
        }
        mysqli_free_result($rs_nwsem);
    }
}
function imprimeselect(){
    selectcocsmanticp();
}

function imprimeselectadd() {
    global $conexion;
    if ($conexion) {
        $sql_crgmnadd = "SELECT * FROM com_menuporfecha WHERE (semana = WEEKOFYEAR(CURDATE()) AND YEAR(fecha) >= YEAR(CURDATE()) AND fecha >= CURDATE()) OR semana = WEEKOFYEAR(ADDDATE(CURDATE(), INTERVAL 1 WEEK)) AND fecha >= CURDATE() GROUP BY fecha order by fecha;";
        $rs_crgmnadd = mysqli_query($conexion, $sql_crgmnadd);
        $tot_crgmnadd = mysqli_num_rows($rs_crgmnadd);
        while ($rw_crgmnadd = mysqli_fetch_assoc($rs_crgmnadd)){
            $s_date = explode("-", $rw_crgmnadd['fecha']);
            echo "<option id='ad_" . $s_date[2] . "_" . $s_date[1] . "_" . $rw_crgmnadd['semana'] . "_".$s_date[0]."' value='" . $rw_crgmnadd['dia'] . "'>" . tools::retornadia($rw_crgmnadd['dia']) . " (" . $s_date[2] . "-" . tools::obtenermes($s_date[1]) . "-" . $s_date[0] . ")</option>";
        }mysqli_free_result($rs_crgmnadd);
    }
}

function consvisitsmnu($consulta, $fchplats) {
    global $conexion;
    if ($conexion) {
        if (empty($consulta)) {
            $sql_gtcnsmnvst = "SELECT V.id, V.`nombre` AS nombrevst, M.`nombre`, F.`fecha`, V.`cantidad`, V.`precio`, (V.`cantidad` * V.`precio`) AS importe, V.`registro` FROM `com_visitantes` V INNER JOIN `com_menuporfecha` F ON V.`menuporfecha` = F.`id` INNER JOIN `com_menus` M ON F.`menu` = M.`id` ORDER BY V.`registro` DESC LIMIT 45";
        } else {
            $sql_gtcnsmnvst = $consulta;
        }$rs_gtcnsmnvst = mysqli_query($conexion, $sql_gtcnsmnvst);
        $tots_gtcnsmnvst = mysqli_num_rows($rs_gtcnsmnvst);
        while ($rw_gtcnsmnvst = mysqli_fetch_assoc($rs_gtcnsmnvst)) {
            echo "<tr><td>" . utf8_encode($rw_gtcnsmnvst['nombrevst']) . "</td><td>" . utf8_encode($rw_gtcnsmnvst['nombre']) . "</td><td>" . $rw_gtcnsmnvst['fecha'] . "</td><td>" . $rw_gtcnsmnvst['cantidad'] . "</td><td>" . $rw_gtcnsmnvst['precio'] . "</td><td>" . $rw_gtcnsmnvst['importe'] . "</td><td>" . $rw_gtcnsmnvst['registro'] . "</td>";
            if (empty($consulta)) {
                echo "<td class=\"centered\">";
                if (strtotime($rw_gtcnsmnvst['fecha']) < strtotime(date('Y-m-d'))) {
                    echo "Procesado</td></tr>";
                } else {
                    echo "<a class='btncanmnvst' style=\"cursor: pointer;\" id=\"canconvst_" . $rw_gtcnsmnvst['id'] . "\">Cancelar</a></td></tr>";
                }
            } else {
                echo "</tr>";
            }
        }if ($tots_gtcnsmnvst == 0) {
            echo "<tr class='centered'><td colspan='8' style='text-align: center; font-weight: 900; font-family: \"Consolas\", \"Monaco\", \"Bitstream Vera Sans Mono\", \"Courier New\", Courier, monospace;'>No hay visitantes registrados para esta fecha!</td></tr>";
        } elseif (!empty($consulta) and $tots_gtcnsmnvst > 0) {
            if ($fchplats != '' and ( strlen($fchplats) > 12)) {
                $sql_obtplats = "SELECT F.`fecha`, SUM(V.`cantidad`) AS platillos, SUM(V.`cantidad` * V.`precio`) AS total FROM `com_visitantes` V INNER JOIN `com_menuporfecha` F ON V.`menuporfecha` = F.`id` WHERE F.`fecha` = '" . $fchplats . "';";
                $rs_obtplats = mysqli_query($conexion, $sql_obtplats);
                $tot_obtplats = mysqli_num_rows($rs_obtplats);
                $rw_obtplats = mysqli_fetch_assoc($rs_obtplats);
                if ($tot_obtplats > 0 and $rw_obtplats['platillos'] != null) {
                    echo "<tr><td colspan='2'>CARGOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $tots_gtcnsmnvst . "</strong></td><td colspan='2'>PLATILLOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obtplats['platillos'] . "</strong></td><td colspan='2'>TOTAL: $<strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obtplats['total'] . "</strong></td></tr>";
                }
            } elseif ($fchplats != '') {
                $sql_obtplats = "SELECT F.`fecha`, SUM(V.`cantidad`) AS platillos, SUM(V.`cantidad` * V.`precio`) AS total FROM `com_visitantes` V INNER JOIN `com_menuporfecha` F ON V.`menuporfecha` = F.`id` WHERE F.`fecha` = '" . $fchplats . "';";
                $rs_obtplats = mysqli_query($conexion, $sql_obtplats);
                $tot_obtplats = mysqli_num_rows($rs_obtplats);
                $rw_obtplats = mysqli_fetch_assoc($rs_obtplats);
                if ($tot_obtplats > 0 and $rw_obtplats['platillos'] != null) {
                    echo "<tr><td colspan='2'>CARGOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $tots_gtcnsmnvst . "</strong></td><td colspan='2'>PLATILLOS: <strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obtplats['platillos'] . "</strong></td><td colspan='2'>TOTAL: $<strong style=\"font-family: 'Roboto', sans-serif; font-size: larger;\">" . $rw_obtplats['total'] . "</strong></td></tr>";
                }
            }
        }
    }
}

function cancelamnvst($valelim) {
    global $conexion;
    if ($conexion) {
        $sql_dtvst = "SELECT * FROM com_visitantes WHERE id = '" . $valelim . "'";
        $rs_dtvst = mysqli_query($conexion, $sql_dtvst);
        $rw_dtvst = mysqli_fetch_assoc($rs_dtvst);
        $sql_canmnvst = "DELETE FROM com_visitantes WHERE id = '" . $valelim . "'";
        $rs_canmnvst = mysqli_query($conexion, $sql_canmnvst);
        if ($rs_canmnvst) {
            $sumar = false;
            $sql_apsdsps = "SELECT apartados, disponibles FROM com_menuporfecha WHERE id = '" . $rw_dtvst['menuporfecha'] . "'";
            $rs_apsdsps = mysqli_query($conexion, $sql_apsdsps);
            $rw_apsdsps = mysqli_fetch_assoc($rs_apsdsps);
            actapdspsystem($rw_apsdsps['apartados'], $rw_apsdsps['disponibles'], $rw_dtvst['menuporfecha'], $sumar, $rw_dtvst['cantidad']);
            echo "Eliminado exitosamente del menu!";
        } else {
            echo "Error al eliminar este consumo!";
        }
    }
}

function regconmnvisit($cantimn, $idmnvst, $nmvse) {
    global $conexion;
    if ($conexion) {
        $sql_fchmnpr = "SELECT fecha, precio, apartados, disponibles FROM com_menuporfecha WHERE id = '" . $idmnvst . "'";
        $rs_fchmnpr = mysqli_query($conexion, $sql_fchmnpr);
        $rw_fchmnpr = mysqli_fetch_assoc($rs_fchmnpr);
        $ctdmnvst = floatval($cantimn) * floatval($rw_fchmnpr['precio']);
        if ($rw_fchmnpr['disponibles'] > 0) {
            if ($cantimn > $rw_fchmnpr['disponibles']) {
                echo "Especifique una cantidad menor de platillos, solo restan " . $rw_fchmnpr['disponibles'] . " platillos!";
            } else {
                $sumar = true;
                actapdspsystem($rw_fchmnpr['apartados'], $rw_fchmnpr['disponibles'], $idmnvst, $sumar, $cantimn);
                $sql_insmnvst = "INSERT INTO `aplicaciones`.`com_visitantes` (`id`, `nombre`, `menuporfecha`, `cantidad`, `precio`, `registro`) VALUES (NULL, '" . $nmvse . "', '" . $idmnvst . "', '" . $cantimn . "', '" . $rw_fchmnpr['precio'] . "', now());";
                $rs_insmnvst = mysqli_query($conexion, $sql_insmnvst);
                if ($rs_insmnvst) {
                    echo "Menu cargado exitosamente en el sistema!";
                } else {
                    echo "Error al cargar el menu del visitante!";
                }
            }
        } else {
            echo "Menu agotado, ya no hay platillos disponibles en este menu!";
        }
    }
} ?>