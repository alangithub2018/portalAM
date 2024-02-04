<?php include_once "assets/includes/funciones.php";?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Portal AM" />
        <meta name="author" content="Sistemas AM" />
        <meta name="keyword" content="Sistema, Portal AM, Servicios" />
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <title>.::|::. PORTAL AM .::|::.</title>
        <link href="assets/css/bootstrap.css.php" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="assets/css/image-picker.css.php">
        <link rel="shortcut icon"  type="image/png"  href="assets/img/favicon.ico">
    </head>
    <body>
    <section id="container">
        <header class="header">
            <div class="sidebar-toggle-box">
                <div id="navigator" class="fa fa-windows"></div>
            </div>
            <a href="index.php" class="logo"><img src="assets/img/logo.png" width="136" height="33"></a>
            <div class="top-menu">
                <ul class="nav-collapse navbar-right" style="margin-top: 16px; margin-right: 2px;">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears fa-2x"></i> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-windows"></i>&nbsp;Perfil</a></li>
                            <li role="separator" class="divider"></li>
                            <li id="vclosecom"><a class="logout" id="cierracom" href="assets/includes/closer.php?<?php echo random_system();?>" name="closesystem"><i class="fa fa-sign-out"></i > Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <aside>
            <div id="sidebar"  class="nav-collapse">
                <ul class="sidebar-menu" id="nav-accordion">
                    <p class="centered"><a href="#"><img src="assets/img/ui-sam.png" class="img-circle" width="60"></a></p>
                    <h5 class="centered" style="font-weight: 700;"><u><?php echo utf8_encode($_SESSION['nameuser']);?></u></h5>
                    <?php echo tools::appMenu($_SESSION['gpouser']);?>
                </ul>
            </div>
        </aside>
        <section id="main-content" style="min-height:400px;">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-birthday-cake"></i> Reportes de cumpleaños</h3>
                                <span class="pull-right clickable"><i class="fa fa-chevron-up"></i></span>
                            </div>
                            <div class="panel-body" style="display: block;">
                                <div id="accordion">
                                    <h3>Formato del Reporte</h3>
                                    <div>
                                        <p>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="picker">
                                                    <select id="lFormCm" class='image-picker show-html form-control' style="margin-bottom: 1em;">
                                                        <?php $imagesx=glob("assets/img/cumpleanios/*.jpg");
                                                            $orderA = []; $y = 1;
                                                            foreach($imagesx as $imagex){
                                                                $sepData = explode("/", $imagex); $finalData = end($sepData);
                                                                $compF = explode("_", $finalData); $nImagen = explode(".", $finalData);
                                                                if($compF[0] == 'previa'){
                                                                    $miClave = ($compF[1]."_".$compF[2]);
                                                                    if(array_key_exists($miClave, $orderA)){
                                                                        $miClave .= $y;
                                                                        $y++;
                                                                    }
                                                                    $orderA += array($miClave => $compF[1]);
                                                                }
                                                            }
                                                            asort($orderA); $x = 0;
                                                            foreach($orderA as $k => $v){
                                                                $sinBajo = explode("_", $k); $sinExt = explode(".", $sinBajo[1]);
                                                                echo "<option data-img-src='assets/img/cumpleanios/previa_".$v."_".$sinBajo[1]."' ".(($x==0) ? ("data-img-class='first'") : (""))."data-img-alt='".$v."_".$sinBajo[1]."' value='".$v."_".str_replace(".jpg", "", $sinBajo[1])."'>". tools::obtenermes($v)."(".$sinExt[0].")</option>";
                                                                $x++;
                                                            }
                                                        ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </p>
                                        </div>
                                    <h3>Seleccionar Plaza</h3>
                                    <div>
                                        <p>
                                            <select id="sPlacm" class="form-control">
                                                <option value="CE">Celaya</option>
                                                <option value="IR">Irapuato</option>
                                                <option selected="selected" value="LE">Leon</option>
                                                <option value="SA">Salamanca</option>
                                            </select>
                                        </p>
                                    </div>
                                    <h3>Mes del Año</h3>
                                    <div>
                                        <p>
                                            <select id="smCum" class="form-control">
                                                <?php $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo',
                                                                     '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio',
                                                                     '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre',
                                                                     '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
                                                    foreach($meses as $m => $month){
                                                        echo '<option ';
                                                            if($m == date('m') and date('d') < 16){
                                                                //echo "selected='selected'";
                                                            }else{
                                                                if($m == (intval(date('m')))){
                                                                    echo "selected='selected'";
                                                                }
                                                            }
                                                        echo ' value="'.$m.'">'.$month.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </p>
                                    </div>
                                </div>
                                <div class="row pull-right">
                                    <div class="col-lg-2" style="margin-top: 1em; float: right;">
                                        <div class="form-group">
                                            <a id="genCump2" class="btn btn-default pull-right" style="cursor: pointer;"><img src='assets/img/report_user.png' /></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pull-left">
                                    <div class="col-lg-7" style="margin-top: 1em; float: left;">
                                        <div class="form-group">
                                            <form id="subeme" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <div class="input-group input-file" name="Fichier1">
                                                        <a class="btn btn-default btn-choose" type="button"><i class="fa fa-calendar-plus-o"></i></a>
                                                        <a id="qformato" style="margin-left:0.5em;" class="btn btn-danger" type="button"><i class="fa fa-calendar-minus-o"></i></a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo sysmsg(). prntloading();?>
                </div>
                <div aria-hidden="true" aria-labelledby="rptcumpleLabel" data-keyboard="false" data-backdrop="static" role="dialog" tabindex="-1" id="rptcumple" class="modal fade">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close cerrardesc" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="fa fa-server"></i> Listado de Cumplea&ntilde;eros</h4>
                            </div>
                                <div class="modal-body">
                                    <div class="table-responsive" style="margin-right: 1%; margin-left: 1%; margin-top: 1%; margin-bottom: 0.5%; width: 98%; height: 450px; overflow-y: auto;">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <caption style="margin-top: 0;">CONCENTRADO DE CUMPLEA&Ntilde;OS</caption>
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Departamento</th>
                                                    <th>Dia</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblcumple">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-6" style="margin-top:0.8em; padding-top:1.90em; padding-bottom:-0.2em; margin-bottom:-0.2em; padding-left:-0.5em; margin-left:-0.5em; padding-right: 0; margin-right: 0;">
                                        <div class="col-lg-5 col-md-5 col-sm-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Nombre</span>
                                                <input onkeyup="javascript: this.value = this.value.toUpperCase()" id="Uname" class="form-control" type="text" aria-label="Amount (to the nearest dollar)">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">Departamento</span>
                                                <input id="UDpt" onkeyup="javascript: this.value = this.value.toUpperCase()" class="form-control" type="text" aria-label="Amount (to the nearest dollar)">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-1">
                                            <div class="input-group">
                                                <span class="input-group-addon">Dia</span>
                                                <select id="UDay" class="form-control" aria-label="Amount (to the nearest dollar)">
                                                    <?php for($z=1;$z<32;$z++){
                                                        echo "<option ".($z==1 ? 'selected':'')." value='".$z."'>".$z."</option>";
                                                    } ?>
                                                </select>
                                                <span id="gbNC" class="input-group-addon btn btn-theme04"><i class="fa fa-plus"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="modal-footer">
                                <a id="genCump1" class="btn btn-default"><i class="fa fa-file-pdf-o fa-2x"></i></a>
                                <button data-dismiss="modal" class="btn btn-theme04 cerrardesc" type="button">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <footer class="footer" style="position: fixed;">
            <div style="text-align: center; margin-top: 0.8%;">Todos los derechos reservados &copy; 2015 Periodico AM.</div>
            <a class="go-top animated fadeInRight"><i></i></a>
        </footer>
    </section>
    <script src="assets/js/jquery-1.10.2.min.js.php"></script>
    <script src="assets/js/bootstrap.min.js.php"></script>
    <script src="assets/js/image-picker.min.js.php" type="text/javascript"></script>
    <script src="assets/js/jquery-ui.min.js.php" type="text/javascript"></script>
    <script>
/*Cumpleanios*/
$("#qformato").on("click",function(e){
    if($('#lFormCm option').length > 0){
        var miOpt = $("#lFormCm option:selected").val() ,forma = $.trim($("#lFormCm option:selected").html()), srcOut = $("div.thumbnail.selected > img").attr("src").toString(),
        nrmF = srcOut.replace("previa_","");
        if(confirm("Realmente deseas quitar el formato: " + forma)){
             $("#myPleaseWait").modal("show");
             $.ajax({
                 url:"assets/includes/remove_Format.php",
                     data:{ft:srcOut, sp:nrmF}
                 }).done(function(b){
                 $("#myPleaseWait").modal("hide");
                 if(b != ''){
                     alert('Formato ' + forma + ' eliminado con exito!!');
                     $("#lFormCm option[value='"+miOpt+"']").remove();
                     $("#lFormCm").data("picker").destroy(); $("#lFormCm").imagepicker({ hide_select:  false }); $("ul.image_picker_selector").css({ "height": "300px", "overflow-y": "scroll" });
                 }else{
                     alert('No se pudo eliminar el formato, intenta nuevamente!!');
                 }
             });
        }
    }else{
        e.preventDefault();
        alert('No existen mas formatos para eliminar, sube un formato!!');
    }
});

$(document).delegate("#gbNC",'click',function(){
    var dy = $("#UDay").val(), dp = $.trim($("#UDpt").val()),nm = $.trim($("#Uname").val());
    if(dp != '' && nm != ''){
        $("#myPleaseWait").modal("show");
        $.ajax({
        url:"assets/includes/add_jsonC.php",
        data:{di:dy, de:dp, no:nm}
    }).done(function(b){
        $("#myPleaseWait").modal("hide");
        if(!b == ''){
            $("#tblcumple").empty().html(b);
            $("#UDpt").val(''); $("#Uname").val(''); $("#Uname").focus(); $("#UDay").val('1');
            alert('Registro agregado Exitosamente!!');
        }
    });
    }else{
        if(nm == ''){
            $("#Uname").focus();
        }else if(dp == ''){
            $("#UDpt").focus();
        }else{
            $("#UDay").focus();
        }
        alert('Debes ingresar todos los datos!!');
    }
});
function bs_input_file() {
    $(".input-file").before(
        function() {
            if ( ! $(this).prev().hasClass('input-ghost') ) {
                var element = $("<input id='imagen' name='imagen' type='file' accept='imagen/jpg, image/jpeg' class='input-ghost' style='visibility:hidden; height:0'>");
                element.change(function(){
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                });
                $(this).find("a.btn-choose").click(function(){
                        element.click();
                });
                $(this).find("button.btn-reset").click(function(){
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                });
                $(this).find('input').css("cursor","pointer");
                $(this).find('input').mousedown(function() {
                    $(this).parents('.input-file').prev().click();
                    return false;
                });
                return element;
            }
        }
    );
}
$(function() {
    bs_input_file();
});
$(document).delegate("#imagen", "change", function(event){
    event.preventDefault();
    $("#subeme").submit();
});
function obtenMes(m){
    var str = "";
    switch(parseInt(m)){
        case 1: str = "Enero";
            break;
        case 2: str = "Febrero";
            break;
        case 3: str = "Marzo";
            break;
        case 4: str = "Abril";
            break;
        case 5: str = "Mayo";
            break;
        case 6: str = "Junio";
            break;
        case 7: str = "Julio";
            break;
        case 8: str = "Agosto";
            break;
        case 9: str = "Septiembre";
            break;
        case 10: str = "Octubre";
            break;
        case 11: str = "Noviembre";
            break;
        case 12: str = "Diciembre";
            break;
        default: str = "";
            break;
    }
    return str;
}
$("#subeme").on('submit', function(e){
    e.preventDefault();
    var file = document.forms['subeme']['imagen'].files[0], fname = file.name, lnombre = fname.replace(/ /g,"_");
    var onlynm = lnombre.split(".")[0];
    if($("#lFormCm option[value='"+onlynm+"']").length > 0){
        alert('Ese nombre de formato ya ha sido cargado, elige un formato con nombre diferente!!');
    }else{
        $.ajax({
            headers: {
                Accept: "application/x-www-form-urlencoded; charset=iso-8859-1",
            },
            type: 'POST',
            global:false,
            url: 'assets/includes/guardarImagen.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            mimeType: "multipart/form-data",
            success: function(msg){
                if(msg == 'ok'){
                    $('#subeme')[0].reset();
                    $("#lFormCm").data("picker").destroy(); var miM = onlynm.split("_");
                    $("#lFormCm").append('<option data-img-src="assets/img/cumpleanios/previa_'+lnombre+'" data-img-alt="'+onlynm+'.jpg" value="'+onlynm+'">'+obtenMes(miM[0])+'('+miM[1]+')</option>');
                    var sel = $('#lFormCm'), selected = sel.val(), opts_list = sel.find('option');
                    opts_list.sort(function(a, b) { return parseInt($(a).val().split("_")[0]) > parseInt($(b).val().split("_")[0]) ? 1 : -1; });
                    sel.empty().append(opts_list);
                    sel.val(selected);
                    $("#lFormCm").imagepicker({ hide_select:  false });
                    $("ul.image_picker_selector").css({ "height": "300px", "overflow-y": "scroll" });
                    $('#lFormCm').val(onlynm).trigger('change');
                    $("#lFormCm").data('picker').sync_picker_with_select();
                    $("div.thumbnail.selected").get(0).scrollIntoView();
                    alert("Formato Agregado con exito!!");
                }else{
                    alert('Ha ocurrido un problema, por favor intenta nuevamente!.');
                }
            }
        });
    }
});

$("#genCump1").attr("href", "assets/includes/gen_Cump.php?fMa=" + $("#lFormCm").val() + "&pLa=" + $("#sPlacm").val() + "&mAn=" + $("#smCum").val());

$("#smCum").on('change', function(){
    $("#genCump1").attr("href", "assets/includes/gen_Cump.php?fMa=" + $("#lFormCm").val() + "&pLa=" + $("#sPlacm").val() + "&mAn=" + $(this).val());
});
$("#sPlacm").on('change', function(){
    $("#genCump1").attr("href", "assets/includes/gen_Cump.php?fMa=" + $("#lFormCm").val() + "&pLa=" + $(this).val() + "&mAn=" + $("#smCum").val());
});
$("#lFormCm").on('change', function(){
    $("#genCump1").attr("href", "assets/includes/gen_Cump.php?fMa=" + $(this).val() + "&pLa=" + $("#sPlacm").val() + "&mAn=" + $("#smCum").val());
});
$("#genCump2").on("click", function(){
    if($.trim($("#lFormCm").val()) != '' && $.trim($("#lFormCm").val()) != 'x'){
        $("#myPleaseWait").modal("show");
        $.ajax({
            url:"assets/includes/aux_Cump.php",
            data:{fm:$("#lFormCm").val(), pl:$("#sPlacm").val(), lm:$("#smCum").val()}
        }).done(function(b){
            $("#myPleaseWait").modal("hide");
            if(!b == ''){
                $("#tblcumple").html(b);
                $("#rptcumple").modal("show");
            }else{
                alert('No hay cumpleañeros en esta plaza para el mes seleccionado, intenta con otro mes u otra plaza.');
            }
        });
    }else{
        alert('No se permiten valores vacios!!');
    }
});

$("#lFormCm").imagepicker({
    hide_select:  false
});
$("ul.image_picker_selector").css({
    "height": "300px",
    "overflow-y": "scroll"
});
$("#accordion").accordion({
    heightStyle: "content"
});
$(document).delegate('.edtCum','click', function(){
    var miId = this.id.split("_")[0];
    if($(this).html() == '<i class="fa fa-pencil"></i>'){
        $(this).html('<i class="fa fa-refresh"></i>');
        //console.log(miId);
        $("#" + miId).removeAttr('disabled').focus();
    }else{
        $(this).html('<i class="fa fa-pencil"></i>');
        $("#" + miId).attr('disabled', 'disabled');
    }
});
$(document).delegate('#genCump1','click',function(e){
    if($(".edtCum").find("i").hasClass("fa-refresh")){
        e.preventDefault();
        $("#tblcumple input:enabled").first().focus();
        alert("Termina de editar los usuarios!");
    }
});
$(document).delegate('.IedtCum','change',function(){
    //console.log('Ahora mi valor es: ' + this.value + ' Y MI ID ES: ' + this.id);
    /*Enviar el cambio de este id en el json desde php*/
    var mD = this.id, mN = this.value;
    $.ajax({
        url:"assets/includes/upd_Cump.php",
        data:{ns:mD, nm:mN}
    }).done(function(b){
        if(b==1){
            //console.log('se actualizó el json para ' + mN + 'con id ' + mD);
        }
    });
});
/*Cumpleanios*/
</script>
</body>
</html>