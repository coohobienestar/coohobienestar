<?php                                     
session_start();
include("../conexion/conectarbd.php"); ////CONEXION A LA BD
include("../funciones/calculo_documento_equivalente.php");
$conexion=Conectarse(); 
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$cod_escuela = $_GET['cod_escuela'];
$anio = $_GET['anio'];
$mes  = $_GET['mes'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = " DUPLICAR RACIONES A MANPULADORAS";

if($tipo_operacion == 1){
   $nom_operacion = " ";
   $icono = "guardar.png";
   
   $disabled = "";
 }
 
if($tipo_operacion == 2){
    ////BUSCAMOS EL NOMBRE DE LA ESCUELA
    $instruccion_esc ="SELECT nombre FROM escuela WHERE cod_escuela = $cod_escuela";

    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    $row_esc = mysql_fetch_array($consulta_esc);
  
    $nom_escuela = $row_esc['nombre'];   

    ////BORRAMOS LOS DATOS Q EXISTAN PARA REEMPLZAR TODO
    $instruccion_del = "DELETE FROM escuela_manipuladora_racion WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);          
    
    ////COPIAMOS LOS CUPOS DE LA ESCUELA A LAS MANIPULADORAS DE LA ESCUELA
    ////BUSCAMOS LAS MANIPULADORAS DE LA ESCUELA
    $sql = "SELECT cod_manipuladora FROM escuela_manipuladora WHERE cod_escuela = $cod_escuela";
    $result = mysql_query($sql);
    error_consulta($result,$sql); 
    $nfilas = mysql_num_rows ($result);
  
    if($nfilas > 0){
     for($i=0; $i<$nfilas; $i++){   
       $resultado = mysql_fetch_array ($result);
  
       $cod_manipuladora = $resultado['cod_manipuladora'];  
       
       $instruccion_ins = "INSERT INTO escuela_manipuladora_racion (cod_escuela, cod_manipuladora, anio, mes, dia, raciones) 
                           SELECT cod_escuela, $cod_manipuladora, anio, mes, dia, raciones FROM escuela_racion 
                           WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'"; 
       $consulta_ins = mysql_query ($instruccion_ins, $conexion);  
        
      }
     }          
    
   $nom_operacion = " ";
   $icono = "tabla.png";
   
   $disabled = "";
 } 
 
if($tipo_operacion == 3){
    ////BUSCAMOS EL NOMBRE DE LA ESCUELA
    $instruccion_esc ="SELECT nombre FROM escuela WHERE cod_escuela = $cod_escuela";

    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    $row_esc = mysql_fetch_array($consulta_esc);
  
    $nom_escuela = $row_esc['nombre'];      
    
   $nom_operacion = " ";
   $icono = "tabla.png";
   
   $disabled = "";
 } 
 
if($tipo_operacion == 4){
    ////BUSCAMOS EL NOMBRE DE LA ESCUELA
    $instruccion_esc ="SELECT nombre FROM escuela WHERE cod_escuela = $cod_escuela";

    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    $row_esc = mysql_fetch_array($consulta_esc);
  
    $nom_escuela = $row_esc['nombre'];   

    ////BORRAMOS LOS DATOS Q EXISTAN PARA REEMPLZAR TODO
    $instruccion_del = "DELETE FROM escuela_manipuladora_racion WHERE anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);          
    
    ////COPIAMOS LOS CUPOS DE LA ESCUELA A LAS MANIPULADORAS DE LA ESCUELA
    ////BUSCAMOS LAS MANIPULADORAS DE LA ESCUELA
    $sql = "SELECT cod_escuela, cod_manipuladora FROM escuela_manipuladora";
    $result = mysql_query($sql);
    error_consulta($result,$sql); 
    $nfilas = mysql_num_rows ($result);
  
    if($nfilas > 0){
     for($i=0; $i<$nfilas; $i++){   
       $resultado = mysql_fetch_array ($result);
  
       $cod_escuela = $resultado['cod_escuela'];
       $cod_manipuladora = $resultado['cod_manipuladora'];  
       
       $instruccion_ins = "INSERT INTO escuela_manipuladora_racion (cod_escuela, cod_manipuladora, anio, mes, dia, raciones) 
                           SELECT cod_escuela, $cod_manipuladora, anio, mes, dia, raciones FROM escuela_racion 
                           WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'"; 
       $consulta_ins = mysql_query ($instruccion_ins, $conexion); 

       valor_pagar_manipuladora($conexion,$cod_escuela,$anio,$mes); 
        
      }
     }    
    
    $cod_escuela = ''; ////SE MANDA LA ESCUELA VACIA PARA QUE LA HAGA EN TODAS LAS ESCUELAS 
    calcular_mensualidad_manipuladora ($conexion,$cod_escuela,$anio,$mes);          
    
   $nom_operacion = " ";
   $icono = "tabla.png";
   
   $disabled = "";
 }

if($tipo_operacion == 5){
    ////BUSCAMOS EL NOMBRE DE LA ESCUELA
    $instruccion_esc ="SELECT nombre FROM escuela WHERE cod_escuela = $cod_escuela";

    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    $row_esc = mysql_fetch_array($consulta_esc);
  
    $nom_escuela = $row_esc['nombre'];  

   $nom_operacion = " ";
   $icono = "repetir.png";
   
   $disabled = "";  
  } 

////LLENAR FORMULARIO CON BASES  
if($tipo_operacion == 6){

  ////DEFINIMOS SI ES AÑO BISIESTO PARA VER SI FEBRERO TIENE 28 O 29 DIAS
  if((($anio % 4 == 0) && ($anio % 100 != 0)) || (($anio % 100 == 0) && ($anio % 400 == 0))){
     $bisiesto=1;
     }else{
       $bisiesto=0;
       } 

    ////DEFINIMOS EL NUEMRO DE DIAS QUE TIENE EL MES
    if($mes=='01'){ $ndiasmes=31; $mes_nombre='ENERO';}
    if($mes=='02'){ if($bisiesto==1){$ndiasmes=29;}else{$ndiasmes=28;} $mes_nombre='FEBRERO';}
    if($mes=='03'){ $ndiasmes=31; $mes_nombre='MARZO';}
    if($mes=='04'){ $ndiasmes=30; $mes_nombre='ABRIL';}
    if($mes=='05'){ $ndiasmes=31; $mes_nombre='MAYO';}
    if($mes=='06'){ $ndiasmes=30; $mes_nombre='JUNIO';}
    if($mes=='07'){ $ndiasmes=31; $mes_nombre='JULIO';}
    if($mes=='08'){ $ndiasmes=31; $mes_nombre='AGOSTO';}
    if($mes=='09'){ $ndiasmes=30; $mes_nombre='SEPTIEMBRE';}
    if($mes=='10'){ $ndiasmes=31; $mes_nombre='OCTUBRE';}
    if($mes=='11'){ $ndiasmes=30; $mes_nombre='NOVIEMBRE';}
    if($mes=='12'){ $ndiasmes=31; $mes_nombre='DICIEMBRE';}  
          
    ////BUSCAMOS EL NOMBRE DE LA ESCUELA
    $instruccion_esc ="SELECT nombre FROM escuela WHERE cod_escuela = $cod_escuela";

    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    $row_esc = mysql_fetch_array($consulta_esc);
  
    $nom_escuela = $row_esc['nombre'];   

    ////BORRAMOS LOS DATOS Q EXISTAN PARA REEMPLZAR TODO
    $instruccion_del = "DELETE FROM escuela_racion WHERE anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);          
    
    ////COPIAMOS LAS BASES DE LAS ESCUELAS 
    ////BUSCAMOS LA BASE DE CADA ESCUELA
    $sql = "SELECT cod_escuela, base FROM escuela_base";
    $result = mysql_query($sql);
    error_consulta($result,$sql); 
    $nfilas = mysql_num_rows ($result);
  
    if($nfilas > 0){
     for($i=0; $i<$nfilas; $i++){   
       $resultado = mysql_fetch_array ($result);
  
       $cod_escuela = $resultado['cod_escuela'];
       $base = $resultado['base']; 
       
        ////SI LAS RACIONES CON MENORES A 60 SE SUBEN A 60
        ////BUSCAMOS EL # DE RACIONES MINIMAS
        $instruccion_racion_min ="SELECT valor FROM parametro WHERE nombre='raciones_minimas'"; 
  
        $consulta_racion_min = mysql_query($instruccion_racion_min);
        error_consulta($consulta_racion_min,$instruccion_racion_min);
        $row_racion_min = mysql_fetch_array($consulta_racion_min);
      
        $raciones_minimas = $row_racion_min['valor'];  
        
        if(($base > 0)  && ($base < $raciones_minimas)){
           $base = $raciones_minimas; 
          }         


        for($j=0; $j<$ndiasmes; $j++){
            $conta_m2 = $j + 1;
        
            ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
            if($conta_m2 < 10){
               $conta_m2 = "0".$conta_m2; 
              } 
              
          ////DEFINIMOS SI LA FECHA ES SABADO O DOMINGO
          $fecha_act = $anio."-".$mes."-".$conta_m2;
          
          //Devuelve 0 o 6  (0 es domingo 6 es sabado)
          $num_dia = date('w',strtotime($fecha_act)); 
          
          if(($num_dia == 0) || ($num_dia == 6)){
            $base_ins = 0;
            }else{
             $base_ins = $base; 
              }           
           
          $instruccion_ins = "INSERT INTO escuela_racion (cod_escuela, anio, mes, dia, raciones) 
                              VALUES ('$cod_escuela','$anio', '$mes', '$conta_m2', '$base_ins')";
          $consulta_ins = mysql_query ($instruccion_ins, $conexion);   
    
         } 
      
      }
     }    
 
    
   $nom_operacion = " ";
   $icono = "tabla.png";
   
   $disabled = "";
 }   
 
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo_fcalidad.css">
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(cod_mani,cod_escuela,anio,mes,tipo_operacion){ 
    var url="../admin/operacion_manipuladoras.php?cod_mani="+cod_mani+"&cod_escuela="+cod_escuela+"&anio="+anio+"&mes="+mes+"&tipo_operacion="+tipo_operacion;
    open(url,"_blank","Sizewindow,width=1200,height=400,top=10,left=10,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
// -->
</SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_racion_manipuladora.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='cod_escuela0' value='<?php print("$cod_escuela");?>'>
<input type='hidden' name='anio0' value='<?php print("$anio");?>'>
<input type='hidden' name='mes0' value='<?php print("$mes");?>'>
 <table width="67%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>  
<?php

 if($tipo_operacion <= 3){ 

  ////DEFINIMOS SI ES AÑO BISIESTO PARA VER SI FEBRERO TIENE 28 O 29 DIAS
  if((($anio % 4 == 0) && ($anio % 100 != 0)) || (($anio % 100 == 0) && ($anio % 400 == 0))){
     $bisiesto=1;
     }else{
       $bisiesto=0;
       } 

    ////DEFINIMOS EL NUEMRO DE DIAS QUE TIENE EL MES
    if($mes=='01'){ $ndiasmes=31; $mes_nombre='ENERO';}
    if($mes=='02'){ if($bisiesto==1){$ndiasmes=29;}else{$ndiasmes=28;} $mes_nombre='FEBRERO';}
    if($mes=='03'){ $ndiasmes=31; $mes_nombre='MARZO';}
    if($mes=='04'){ $ndiasmes=30; $mes_nombre='ABRIL';}
    if($mes=='05'){ $ndiasmes=31; $mes_nombre='MAYO';}
    if($mes=='06'){ $ndiasmes=30; $mes_nombre='JUNIO';}
    if($mes=='07'){ $ndiasmes=31; $mes_nombre='JULIO';}
    if($mes=='08'){ $ndiasmes=31; $mes_nombre='AGOSTO';}
    if($mes=='09'){ $ndiasmes=30; $mes_nombre='SEPTIEMBRE';}
    if($mes=='10'){ $ndiasmes=31; $mes_nombre='OCTUBRE';}
    if($mes=='11'){ $ndiasmes=30; $mes_nombre='NOVIEMBRE';}
    if($mes=='12'){ $ndiasmes=31; $mes_nombre='DICIEMBRE';}  
    
  ////BUSCAMOS SI EL PERIODO YA TIENE NUMERO DE DOCUMENTO EQUIVALENTE ASIGNADO
  $instruccion3a = "SELECT count(num_documento) AS cuenta FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
  $consulta3a = mysql_query ($instruccion3a, $conexion);  
  $row3a = mysql_fetch_array ($consulta3a);
  $cuenta3a = $row3a['cuenta'];

print("<table width='98%' border='1'>");
  print("<tr>");
    print("<td colspan='2'>&nbsp;</td>");
    
    if($cuenta3a <= 0){  
         print("<td colspan='$ndiasmes'><div align='center'><strong>$nom_escuela  [$mes_nombre - $anio] || &nbsp;&nbsp; <a href=javascript:operar_tabla(0,$cod_escuela,'$anio','$mes',1) title='Agregar Manipuladora'><img src='../imagenes/agregar_comp.png' width='20' height='20' border='0'></a></strong></div></td>");
      }else{
         print("<td colspan='$ndiasmes'><div align='center'><strong>$nom_escuela  [$mes_nombre - $anio]</strong></div></td>");
         }

  print("</tr>");
  print("<tr>");
    print("<td> # </td>"); 
    print("<td align='center'>MANIPULADORA </a></td>"); 
    print("<td align='center'>&nbsp; </a></td>"); 
    
    for ($n=0; $n<$ndiasmes; $n++){
      $conta_m = $n + 1;
      
      ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
      if($conta_m < 10){
         $conta_m = "0".$conta_m; 
        }  
      print("<td align='center'>$conta_m</td>");

    }
    print("<td align='center'>VALOR TOTAL</td>");   
  print("</tr>");  
  
    ////BUSCAMOS LAS MANIPULADORAS DE LA ESCUELA
    $instruccion_esc ="SELECT manipuladora.cod_manipuladora AS cod_manipuladora, manipuladora.nombre AS nom_manipuladora
                       FROM escuela_manipuladora 
                       INNER JOIN manipuladora ON escuela_manipuladora.cod_manipuladora = manipuladora.cod_manipuladora
                       WHERE escuela_manipuladora.cod_escuela = $cod_escuela";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){
     print("<tr>");
      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_manipuladora = $row_esc['cod_manipuladora'];
      $nom_manipuladora = $row_esc['nom_manipuladora'];
      
      $conta = $e + 1; 
      print("<td>$conta</td>");
      print("<td>$nom_manipuladora </td>");
      if($cuenta3a <= 0){ 
        print("<td><center><a href=javascript:operar_tabla($cod_manipuladora,$cod_escuela,'$anio','$mes',2) title='Retirar manipuladora'><img src='../imagenes/borrar.png' width='14' height='14' border='0'></a></center></td>");
       }else{
        print("<td>-</td>"); 
         }
       
       
        for ($n=0; $n<$ndiasmes; $n++){
          $conta_m2 = $n + 1;
          
          ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
          if($conta_m2 < 10){
             $conta_m2 = "0".$conta_m2; 
            }                   
          
          ////BUSCAMOS EL VALOR DE LA ESCUELA EN EL DIA SI LA TIENE
          $instruccion_valor ="SELECT raciones AS raciones, total_pagar_manipuladora AS total_pagar_manipuladora 
                               FROM escuela_manipuladora_racion 
                               WHERE cod_escuela = '$cod_escuela' AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes' AND dia = $conta_m2";
         
          $consulta_valor = mysql_query($instruccion_valor);
          error_consulta($consulta_valor,$instruccion_valor);
          
          $row_valor = mysql_fetch_array($consulta_valor);
      
          $raciones = $row_valor['raciones'];
          $total_apagar = $row_valor['total_pagar_manipuladora']; 
          $total_apagar = round($total_apagar);
          
          if($raciones == '') $raciones = 0;   
          
          ////DEFINIMOS SI LA FECHA ES SABADO O DOMINGO
          $fecha_act = $anio."-".$mes."-".$conta_m2;
          
          //Devuelve 0 o 6  (0 es domingo 6 es sabado)
          $num_dia = date('w',strtotime($fecha_act));
          
          if(($num_dia == 0) || ($num_dia == 6)){
             $color_fondo = "#F57C67";
             $disabled = "readonly";
            }else{
              $color_fondo = "#FFFFFF";
              $disabled = " ";
              }                      
          
          print("<td><center><input type='text' $disabled name='raciones_$cod_manipuladora$conta_m2' maxlength='3' title='$nom_manipuladora: $anio-$mes-$conta_m2' style='width:25px;height:20px;text-align:center; background-color:$color_fondo' value='$raciones'></center></td>");
          } 
      print("<td><center>$total_apagar</center></td>");  
     print("</tr>");     
    }    
  
print("</table>");    

  ////BUSCAMOS SI EL PERIODO YA TIENE NUMERO DE DOCUMENTO EQUIVALENTE ASIGNADO
  $instruccion3 = "SELECT count(num_documento) AS cuenta FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
  $consulta3 = mysql_query ($instruccion3, $conexion);  
  $row3 = mysql_fetch_array ($consulta3);
  $cuenta = $row3['cuenta'];
  
 if($cuenta <= 0){
  
print("<table width='98%' border='0'>"); 
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");  
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");       
  print("<tr>");
   print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
  print("</tr>");
print("</table>");  
  }
 
 } 

if($tipo_operacion == 4){ 
print("<table width='98%' border='0'>"); 
  print("<tr>");
    print("<td align='center'><strong>SE DUPLICARON TODAS LAS RACIONES PARA LAS ESCUELAS CORRECTAMENTE POR FAVOR VERIFIQUE...</strong></td>");
  print("</tr>");
print("</table>");   
 } 
 
if($tipo_operacion == 5){ 
 ////FORMULARIO DE REPETIR RACIONES PARA ESCUELA
  print("<tr>");
   print("<td style='font-weight:bold; color: white'>Escuela </td>");
   print("<td>&nbsp;&nbsp;&nbsp;&nbsp; $cod_escuela - $nom_escuela</td>");   
  print("</tr>");
  print("<tr>");  
   print("<td style='font-weight:bold; color: white'>Año </td>");
   print("<td>&nbsp;&nbsp;&nbsp;&nbsp; $anio</td>");   
  print("</tr>");  
  print("<tr>");  
   print("<td style='font-weight:bold; color: white'>Mes </td>");
   print("<td>&nbsp;&nbsp;&nbsp;&nbsp; $mes</td>");   
  print("</tr>");    
  print("<tr>");  
   print("<td style='font-weight:bold; color: white'>Cantidad de Raciones</td>");
   print("<td><img src='../imagenes/requerido.gif'><input type='text' name='q_raciones' size='3' value=''></td>");   
  print("</tr>"); 
  
  ////BUSCAMOS SI EL PERIODO YA TIENE NUMERO DE DOCUMENTO EQUIVALENTE ASIGNADO
  $instruccion3 = "SELECT count(num_documento) AS cuenta FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
  $consulta3 = mysql_query ($instruccion3, $conexion);  
  $row3 = mysql_fetch_array ($consulta3);
  $cuenta = $row3['cuenta'];
  
 if($cuenta <= 0){
  
    print("<table width='98%' border='0'>"); 
      print("<tr>");
        print("<td>&nbsp;</td>");
      print("</tr>");  
      print("<tr>");
        print("<td>&nbsp;</td>");
      print("</tr>");       
      print("<tr>");
       print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
      print("</tr>");
    print("</table>");  
  }  
 } 
 
if($tipo_operacion == 6){ 
print("<table width='98%' border='0'>"); 
  print("<tr>");
    print("<td align='center'><strong>SE LLENO EL FORMULARIO CON LAS BASES...</strong></td>");
  print("</tr>");
print("</table>");   
 } 
 
?>
</table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
