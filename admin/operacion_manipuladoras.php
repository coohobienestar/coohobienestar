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

$cod_mani = $_GET['cod_mani'];
$cod_escuela = $_GET['cod_escuela'];
$anio = $_GET['anio'];
$mes = $_GET['mes'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = " MANIPULADORA";

  ////BUSCAMOS EL NOMBRE DE LA ESCUELA
  $instruccion_esc ="SELECT nombre FROM escuela WHERE cod_escuela = $cod_escuela";

  $consulta_esc = mysql_query($instruccion_esc);
  error_consulta($consulta_esc,$instruccion_esc);
  $row_esc = mysql_fetch_array($consulta_esc);

  $nom_escuela = $row_esc['nombre'];    

if($tipo_operacion == 1){
   $nom_operacion = " AGREGAR ";
   $icono = "agregar_comp.png";
   
   $disabled = "";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = " ELIMINAR ";
   $icono = "borrar.png";
   
    ////BUSCAMOS EL NOMBRE DE LA MANIPULADORA
    $instruccion_mani ="SELECT nombre FROM manipuladora WHERE cod_manipuladora = $cod_mani";

    $consulta_mani = mysql_query($instruccion_mani);
    error_consulta($consulta_mani,$instruccion_mani);
    $row_mani = mysql_fetch_array($consulta_mani);
  
    $nom_mani = $row_mani['nombre'];          
   
   $disabled = "";
 } 
    
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo_fcalidad.css">
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(cod_escuela,anio,mes,tipo_operacion){ 
    var url="../admin/operacion_manipuladoras.php?cod_escuela="+cod_escuela+"&anio="+anio+"&mes="+mes+"&tipo_operacion="+tipo_operacion;
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
<form action="vr_manipuladoras.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='cod_escuela0' value='<?php print("$cod_escuela");?>'>
<input type='hidden' name='cod_mani0' value='<?php print("$cod_mani");?>'>
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
      print("<tr>");
       print("<td style='font-weight:bold; color: white'>Manipuladora <img src='../imagenes/requerido.gif'>");
        print ("<SELECT NAME='manipuladora'>");                

          $instruccion = "SELECT cod_manipuladora, nombre FROM manipuladora ORDER BY cod_manipuladora";
          $consulta = mysql_query ($instruccion, $conexion);
          $row = mysql_fetch_array ($consulta); 
            
          $valdesc = "";
          $descp = "--";
              print("<option value=".$valdesc.">".$descp."</option>");  
            do{ 
               print("<option value=".$row['cod_manipuladora'].">[".$row['cod_manipuladora']."] - ".$row['nombre']."</option>");
            }while ($row = mysql_fetch_array($consulta)); 
            print("</SELECT>");  
         print("</td>"); 
       print("</tr>");  
        print("<tr>");
          print("<td>&nbsp;</td>");
        print("</tr>");  
        print("<tr>");
          print("<td>&nbsp;</td>");
        print("</tr>");       
        print("<tr>");
         print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
        print("</tr>");       

  } 
 
if($tipo_operacion == 2){ 
 
print("<table width='98%' border='0'>"); 
  print("<tr>");
    print("<td align='center'><strong>Va a eliminar la manipuladora $nom_mani de la escuela $nom_escuela </strong></td>");
  print("</tr>");
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
 
?>
</table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
