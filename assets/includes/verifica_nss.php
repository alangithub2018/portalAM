<?php include_once "funciones.php";
if (isset($_POST['nss']) and ! empty($_POST['nss'])){
    if (isset($_SESSION['usermail'])) {
        unset($_SESSION['usermail']);
    } $nss = $_POST['nss'];
    $sql_empleado = "SELECT TOP 1 empresa, codigo as usuario, (RTRIM(LTRIM(nombre)) + ' ' + RTRIM(LTRIM(ap_paterno)) + ' ' + RTRIM(LTRIM(ap_materno))) as empleado, CONVERT (VARCHAR (10), fchantigua, 23) as ingreso FROM empleados where afiliacion = SUBSTRING('" . $nss . "',1,10) and digvera = SUBSTRING('" . $nss . "',11,1) and activo = 'S' ORDER BY fchalta DESC;";
    $stmt_empleado = odbc_exec($conexion_sql, $sql_empleado);
    if ($stmt_empleado === false) {
        throw new ErrorExcpetion(odbc_errormsg());
    } else {
        if ($row_empleado = odbc_fetch_row($stmt_empleado)) {
            $empresa = odbc_result($stmt_empleado, "empresa");
            $usuario = odbc_result($stmt_empleado, "usuario");
            $nombre = odbc_result($stmt_empleado, "empleado");
            $ingreso = odbc_result($stmt_empleado, "ingreso");
            $sql_email = "SELECT email FROM datosemps WHERE codigo = '" . $usuario . "' and empresa = '" . $empresa . "';";
            $stmt_email = odbc_exec($conexion_sql, $sql_email);
            if ($stmt_email === false) {
                throw new ErrorException(odbc_errormsg());
            } else {
                if ($row_email = odbc_fetch_row($stmt_email)) {
                    $email = odbc_result($stmt_email, "email");
                    $_SESSION['usermail'] = $email;
                }odbc_free_result($stmt_email);
            }$sql_empresa = "select * from empresas where empresa = '" . $empresa . "';";
            $stmt_empresa = odbc_exec($conexion_sql, $sql_empresa);
            if ($stmt_empresa === false) {
                throw new ErrorException(odbc_errormsg());
            } else {
                if ($row_empresa = odbc_fetch_row($stmt_empresa)) {
                    $vempresa = odbc_result($stmt_empresa, "nombre_empresa");
                    registraempresa($empresa, $vempresa);
                    $sql_centro = "select centro from Llaves where codigo = '" . $usuario . "' and empresa = '" . $empresa . "';";
                    $stmt_centro = odbc_exec($conexion_sql, $sql_centro);
                    if ($stmt_centro === false) {
                        throw new ErrorException(odbc_errormsg());
                    } else {
                        if ($row_centro = odbc_fetch_row($stmt_centro)) {
                            $centro = odbc_result($stmt_centro, "centro");
                            $sql_departamento = "select c.nomdepto as departamento from Llaves l inner join centros c on l.centro = c.centro where l.codigo = '" . $usuario . "' and c.empresa = '" . $empresa . "' and c.centro = '" . $centro . "';";
                            $stmt_departamento = odbc_exec($conexion_sql, $sql_departamento);
                            if ($stmt_departamento === false) {
                                throw new ErrorException(odbc_errormsg());
                            } else {
                                if ($row_departamento = odbc_fetch_row($stmt_departamento)) {
                                    $departamento = odbc_result($stmt_departamento, "departamento");
                                    if ($empresa < 10) {
                                        $empresa = '0' . $empresa;
                                    }$iduser = $empresa . $usuario;
                                    $verificado = '<div class="col-lg-12"><div class="form-group"><label class="control-label">Usuario: </label><span style="color: #009947; font-weight: bold;"><strong id="reguser">' . trim($iduser) . '</strong></span></div></div><div class="col-lg-6"><div class="form-group"><label class="control-label">Clave</label><input type="password" class="form-control" id="npass" required="required" /></div></div><div class="col-lg-6"><div class="form-group"><label class="control-label">Confirmar</label><div class="input-group"><input type="password" id="cnpass" class="form-control" aria-describedby="basic-addon" required="required" style="z-index:0;"><a class="input-group-addon btn btn-default" id="btnregpass">Registrar</a></div></div></div>';
                                    echo '<div class="form-group"><div class="col-lg-12">Empresa: <span style="color: #009947; font-weight: 800;" id="valempresa">' . utf8_encode($vempresa) . '</span></div><div class="col-lg-12" style="margin-top: 3px;">Empleado: <strong>' . $usuario . '</strong></div><div class="col-lg-12" style="margin-top: 3px;">Nombre: <strong id="nameuser">' . utf8_encode($nombre) . '</strong></div><div class="col-lg-12" style="margin-top: 3px;">Departamento: <span style="font-weight:bold;" id="valdepto">(' . trim(utf8_encode($departamento)) . ')</span></div>' . '<div class="col-lg-12" style="margin-top: 3px;">Ingreso: <span id="fingreso">' . $ingreso . '</span></div></div>*' . $verificado;
                                } odbc_free_result($stmt_departamento);
                            }
                        }odbc_free_result($stmt_centro);
                    }
                } else {
                    echo "0";
                }odbc_free_result($stmt_empresa);
            }
        }odbc_free_result($stmt_empleado);
        odbc_close($conexion_sql);
    }
} else {
    header('Location: ..\..\comedor.php');
}?>