<?php   
session_start();

setlocale(LC_TIME, 'spanish');
date_default_timezone_set('America/Bogota');

include("../conexion/conectarbd.php"); ////CONEXION A LA BD
include("../funciones/calculo_documento_equivalente.php");
$conexion=Conectarse(); 

$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$anio = $_GET['anio'];
$mes  = $_GET['mes'];
$tipo_operacion = $_GET['tipo_operacion'];

$valor_pagar_total = 0;

$nom_form = " PAGO A MANIPULADORAS";

  ////BUSCAMOS SI EL PERIODO YA TIENE NUMERO DE DOCUMENTO EQUIVALENTE ASIGNADO
  $instruccion3 = "SELECT count(num_documento) AS cuenta FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
  $consulta3 = mysql_query ($instruccion3, $conexion);  
  $row3 = mysql_fetch_array ($consulta3);
  $cuenta = $row3['cuenta'];

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
   
   $disabled = "";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "REGISTRAR RACIONES PARA ";
   $icono = "tabla.png";
   
   $disabled = "";
 } 

if($tipo_operacion == 3){
   $nom_operacion = "CALCULAR DOCUMENTO EQUIVALENTE DE ";
   $icono = "calculado.png";
   
   $disabled = "";
 } 
 
if($tipo_operacion == 4){
   $nom_operacion = "GENERAR CONSECUTIVO PARA DOCUMENTO EQUIVALENTE DE ";
   $icono = "consecutivo.png";
   
   $disabled = "";
 } 
 
if($tipo_operacion == 5){
   $nom_operacion = "GENERAR LISTADO DE DOCUMENTOS EQUIVALENTES PARA ";
   $icono = "consecutivo.png";
   
   $disabled = "";
 }   
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo_fcalidad.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(cod_escuela,anio,mes,tipo_operacion){ 
    var url="../admin/operacion_racion_manipuladora.php?cod_escuela="+cod_escuela+"&anio="+anio+"&mes="+mes+"&tipo_operacion="+tipo_operacion;
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
<form action="vr_pago_manipuladoras.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
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
 if($tipo_operacion == 1){  
 
 ////BUSCAMOS EL ANIO ACTUAL 
 $date = strtotime(date("Y-m-d"));
 
 $anio_actual = date("Y", $date); 
 $mes_actual = date("m", $date); 
 
  print("<tr>");
   print("<td style='font-weight:bold; color: white'>Año</td>");
   print("<td><img src='../imagenes/requerido.gif'>");
    print("<select name='anio'>");

    if($anio_actual == '2013'){    
     print("<option value='2013' Selected>2013</option>");
     }else{
      print("<option value='2013'>2013</option>");
      }
      
    if($anio_actual == '2014'){        
     print("<option value='2014' Selected>2014</option>");
     }else{
      print("<option value='2014'>2014</option>");
      }

    if($anio_actual == '2015'){        
     print("<option value='2015' Selected>2015</option>");
     }else{
      print("<option value='2015'>2015</option>");
      }
     
    print("</select>");
   print("</td>");   
  print("</tr>");
  print("<tr>");
   print("<td style='font-weight:bold; color: white'>Mes</td>");
   print("<td><img src='../imagenes/requerido.gif'>");
    print("<select name='mes'>");
    
    if($mes_actual == '01'){
     print("<option value='01' Selected>Enero</option>");
     }else{
      print("<option value='01'>Enero</option>");
      }
       
    if($mes_actual == '02'){       
     print("<option value='02' Selected>Febrero</option>");
     }else{
      print("<option value='02'>Febrero</option>");
      }
      
    if($mes_actual == '03'){       
     print("<option value='03' Selected>Marzo</option>");
     }else{
      print("<option value='03'>Marzo</option>");       
      }
      
    if($mes_actual == '04'){       
     print("<option value='04' Selected>Abril</option>");
     }else{
     print("<option value='04'>Abril</option>");      
      }
      
    if($mes_actual == '05'){       
     print("<option value='05' Selected>Mayo</option>");
     }else{
      print("<option value='05'>Mayo</option>");
      }
      
    if($mes_actual == '06'){      
     print("<option value='06' Selected>Junio</option>");
     }else{
      print("<option value='06'>Junio</option>");       
      }
      
    if($mes_actual == '07'){     
     print("<option value='07' Selected>Julio</option>");
     }else{
      print("<option value='07'>Julio</option>");
      }     
       
    if($mes_actual == '08'){
     print("<option value='08' Selected>Agosto</option>");
     }else{
      print("<option value='08'>Agosto</option>");
      }
      
    if($mes_actual == '09'){      
     print("<option value='09' Selected>Septiembre</option>");
     }else{
      print("<option value='09'>Septiembre</option>");
      }
      
    if($mes_actual == '10'){        
     print("<option value='10' Selected>Octubre</option>");
     }else{
      print("<option value='10'>Octubre</option>");
      }
      
    if($mes_actual == '11'){       
     print("<option value='11' Selected>Noviembre</option>");
     }else{
      print("<option value='11'>Noviembre</option>");
      }
      
    if($mes_actual == '12'){      
     print("<option value='12' Selected>Diciembre</option>");
     }else{
      print("<option value='12'>Diciembre</option>");     
      }
      
    print("</select>");   
   print("</td>");
 } 
 if($tipo_operacion == 2){ 

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
    
  ////BUSCAMOS EL # DE RACIONES MINIMAS
  $instruccion_racion_min ="SELECT valor FROM parametro WHERE nombre='raciones_minimas'"; 

  $consulta_racion_min = mysql_query($instruccion_racion_min);
  error_consulta($consulta_racion_min,$instruccion_racion_min);
  $row_racion_min = mysql_fetch_array($consulta_racion_min);

  $raciones_minimas = $row_racion_min['valor'];   
  
print("<table width='100%' border='0'>");  
  print("<tr>");
    print("<td><center><strong>RECUERDE QUE # DE RACIONES MENORES A $raciones_minimas SE GUARDAN EN $raciones_minimas AUTOMATICAMENTE</strong></center></td>");  
  print("</tr>"); 
  print("<tr>");
    print("<td>&nbsp;</td>");  
  print("</tr>");   
print("</table>");      

print("<table width='100%' border='1'>");  
  print("<tr>");                           
    print("<td colspan='6'>&nbsp;</td>");
    print("<td colspan='$ndiasmes'><div align='center'><strong>$mes_nombre - $anio</strong></div></td>");
  print("</tr>");
  print("<tr>");
    print("<td> # </td>"); 
    print("<td align='center'>ESCUELA </a></td>");
    print("<td align='center'>BASE </a></td>");
    print("<td align='center'>DUPLICAR </a></td>"); 
    print("<td align='center'>EDITAR</a></td>"); 
    print("<td align='center'>REPETIR</a></td>");  
    
    for ($n=0; $n<$ndiasmes; $n++){
      $conta_m = $n + 1;
      
      ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
      if($conta_m < 10){
         $conta_m = "0".$conta_m; 
        }  
      print("<td align='center'>$conta_m</td>");

    }
    print("<td align='center'>TOTAL RACIONES</td>");
    print("<td align='center'>VALOR TOTAL</td>");   
  print("</tr>");  
 
    ////BUSCAMOS LAS ESCUELAS DE RISARALDA
    $instruccion_esc ="SELECT escuela.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela 
                       FROM escuela
                       WHERE escuela.cod_escuela <> 1 AND escuela.cod_centro_acopio = 1
                       ORDER BY escuela.cod_escuela";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){
     print("<tr>");
      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_escuela = $row_esc['cod_escuela'];
      $nom_escuela = $row_esc['nom_escuela'];
      
      ////BUSCAMOS LAS BASES DE LA ESCUELA
      $instruccion_base ="SELECT base AS base FROM escuela_base WHERE cod_escuela = '$cod_escuela'";       
      $consulta_base = mysql_query($instruccion_base);
      error_consulta($consulta_base,$instruccion_base);
      
      $row_base= mysql_fetch_array($consulta_base);
  
      $base = $row_base['base'];     
      
      if ($base == '') $base = 0;          
      $conta = $e + 1; 
 
      print("<td>$conta</td>");
      print("<td>$nom_escuela </td>"); 
      print("<td><center>$base</center></td>");      
            
       if($cuenta <= 0){  
         print("<td><center><a href=javascript:operar_tabla('$cod_escuela','$anio','$mes',2) title='Duplicar raciones a Manipuladoras'><img src='../imagenes/duplicar.png' width='14' height='14' border='0'></a></center></td>");
         }else{
          print("<td><center>-</center></td>");
         }
       
        print("<td><center><a href=javascript:operar_tabla('$cod_escuela','$anio','$mes',3) title='Editar raciones a Manipuladoras'><img src='../imagenes/editar.png' width='14' height='14' border='0'></a></center></td>");            
       
       if($cuenta <= 0){  
         print("<td><center><a href=javascript:operar_tabla('$cod_escuela','$anio','$mes',5) title='Repetir raciones en Escuela'><img src='../imagenes/repetir.png' width='14' height='14' border='0'></a></center></td>");
         }else{
          print("<td><center>-</center></td>");
         }    
            
        for ($n=0; $n<$ndiasmes; $n++){
          $conta_m2 = $n + 1;
          
          ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
          if($conta_m2 < 10){
             $conta_m2 = "0".$conta_m2; 
            }                   
          
          ////BUSCAMOS EL VALOR DE LA ESCUELA EN EL DIA SI LA TIENE
          $instruccion_valor ="SELECT raciones AS raciones, total_pagar_escuela AS total_apagar, total_raciones_escuela AS total_raciones_escuela 
                               FROM escuela_racion 
                               WHERE cod_escuela = '$cod_escuela' AND anio = '$anio' AND mes = '$mes' AND dia = $conta_m2";
         
          $consulta_valor = mysql_query($instruccion_valor);
          error_consulta($consulta_valor,$instruccion_valor);
          
          $row_valor = mysql_fetch_array($consulta_valor);
      
          $raciones = $row_valor['raciones'];
          $total_apagar = $row_valor['total_apagar'];
          $total_raciones = $row_valor['total_raciones_escuela']; 
          
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
          
          print("<td><center><input type='text' $disabled name='raciones_$cod_escuela$conta_m2' maxlength='3' title='$nom_escuela: $anio-$mes-$conta_m2' style='width:25px;height:20px;text-align:center; background-color:$color_fondo' value='$raciones'></center></td>");
          } 
      print("<td><center>$total_raciones</center></td>");
      print("<td><center>$total_apagar</center></td>");  
      
      $valor_pagar_total = $valor_pagar_total + $total_apagar;
      
     print("</tr>");     
    }    
  
print("</table>"); 



  print("<table width='98%' border='0'>"); 
    print("<tr>");
      print("<td>&nbsp;</td>");
    print("</tr>");  
    print("<tr>");
      print("<td></strong>VALOR TOTAL MANIPULADORAS: $ $valor_pagar_total</strong></td>");
    print("</tr>"); 

if($cuenta <= 0){

  print("<table width='98%' border='0'>"); 
    print("<tr>");
      print("<td>&nbsp;</td>");
    print("</tr>");  
    print("<tr>");
      print("<td>&nbsp;&nbsp;<strong>Llenar con bases &nbsp;&nbsp; <a href=javascript:operar_tabla('$cod_escuela','$anio','$mes',6) title='LLenar con bases de las escuelas'><img src='../imagenes/repetir.png' width='22' height='22' border='0'></a></strong></td>");
    print("</tr>"); 
    
  print("</br>");  
  
  print("<table width='98%' border='0'>"); 
    print("<tr>");
      print("<td>&nbsp;</td>");
    print("</tr>");  
    print("<tr>");
      print("<td>&nbsp;&nbsp;<strong>Duplicar Todas las Raciones &nbsp;&nbsp; <a href=javascript:operar_tabla('$cod_escuela','$anio','$mes',4) title='Duplicar Todas las raciones de las escuelas'><img src='../imagenes/duplicar.png' width='22' height='22' border='0'></a></strong></td>");
    print("</tr>"); 
  }
 } 

if($tipo_operacion == 3){
 documento_equivalente ($conexion,$anio,$mes);
 
print("<table width='98%' border='0'>"); 
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");  
  print("<tr>");
    print("<td>&nbsp;&nbsp;<strong><center> SE HAN CALCULADO LOS DOCUMENTOS EQUIVALENTES POR FAVOR VERIFIQUE... </center></strong></td>");
  print("</tr>");  
  print("<META HTTP-EQUIV='Refresh' CONTENT='2; url=javascript:window.close();'>");   
 } 
 
if($tipo_operacion == 4){
 documento_equivalente ($conexion,$anio,$mes);
 generar_consecutivo($conexion,$anio,$mes);
 
print("<table width='98%' border='0'>"); 
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");  
  print("<tr>");
    print("<td>&nbsp;&nbsp;<strong><center> SE HAN GENERADO LOS CONSECUTIVOS PARA LOS DOCUMENTOS EQUIVALENTES POR FAVOR VERIFIQUE... </center></strong></td>");
  print("</tr>");  
  
  print("<META HTTP-EQUIV='Refresh' CONTENT='2; url=javascript:window.close();'>"); 
 }  

if($tipo_operacion <= 2){    
  if($cuenta <= 0){
?>        
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>
<?php
   }
}
?>
  
</table> 
</table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
