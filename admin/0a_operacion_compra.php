<?php
//session_start();

include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 
/*
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  */
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$cod_compra = $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];
$opcion_vista= $_GET['vista'];

$nom_form ="COMPRA";

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
  
  ////BUSCAMOS LOS VALORES ACTUALES DEL REGISTRO
  $instruccion_e = "SELECT 0a_compra.cod_producto AS cod_producto, 0a_compra.cod_presentacion AS cod_presentacion, 
                           0a_compra.cod_proveedor AS cod_proveedor, 0a_compra.fecha AS fecha, 
                           0a_compra.cantidad AS cantidad, 0a_compra.valor_unitario AS valor_unitario, 0a_compra.factura AS factura
                    FROM 0a_compra
                    WHERE 0a_compra.cod_compra = $cod_compra";
  $consulta_e = mysql_query ($instruccion_e, $conexion);  
  $row_e = mysql_fetch_array ($consulta_e);
  
  $cod_producto = $row_e['cod_producto'];
  $cod_presentacion  = $row_e['cod_presentacion'];
  $cod_proveedor = $row_e['cod_proveedor'];
  $factura = $row_e['factura'];
  $fecha = $row_e['fecha'];
  $cantidad = $row_e['cantidad'];
  $valor_unitario = $row_e['valor_unitario'];
 }


?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?> </title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form NAME='datechooser' action="0a_vr_compra.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$cod_compra");?>'>
 <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="2" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>  
 </table> 
 <table width="95%" border="0" align="center" cellpadding="3" cellspacing="3">
 <?php 
  if($tipo_operacion <= 2){
 ?>
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_compra");?>'></td>   
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS PRODUCTOS
    print ("<TD style='font-weight:bold; color: white'>Producto</TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='producto'>");                

    $instruccion = "SELECT cod_producto, nombre FROM 0a_producto ORDER BY nombre";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_producto'] == $cod_producto){
          print("<option value=".$row['cod_producto']." Selected>".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_producto'].">".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LAS PRESENTACIONES
      print ("<TD style='font-weight:bold; color: white'>Presentacion </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='presentacion'>"); 
      
      $instruccion_m = "SELECT cod_presentacion, nombre FROM 0a_presentacion ORDER BY nombre";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['cod_presentacion'] == $cod_presentacion){
            print("<option value=".$row_m['cod_presentacion']." Selected>".$row_m['nombre']."</option>");
            }else{
              print("<option value=".$row_m['cod_presentacion'].">".$row_m['nombre']."</option>");
              }
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS PROVEEDORES
      print ("<TD style='font-weight:bold; color: white'>Proveedores</TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='proveedor'>"); 
      
      $instruccion_m = "SELECT cod_proveedor, nombre FROM 0a_proveedor ORDER BY nombre";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['cod_proveedor'] == $cod_proveedor){
            print("<option value=".$row_m['cod_proveedor']." Selected>".$row_m['nombre']."</option>");
            }else{
              print("<option value=".$row_m['cod_proveedor'].">".$row_m['nombre']."</option>");
              }
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr> 
    <?php 
      print ("<tr><td align='left' style='font-weight:bold; color: white'>Fecha Compra </td>");
      print ("<td><img width='18' height='18' src='../imagenes/calendar.png'><INPUT size='10' value='$fecha' type='text' name='fecha' onfocus='doShow(\"datechooser1\",\"datechooser\",\"fecha\")'><div enabled='false' id='datechooser1'></div></td></tr>"); 
    ?>  
  <tr>
   <td style='font-weight:bold; color: white'># Factura</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="factura" size="10" value='<?php print("$factura");?>'></td>   
  </tr>  
<!--     
  <tr>
   <td style='font-weight:bold; color: white'>Cantidad</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cantidad" size="10" value='<?php print("$cantidad");?>'></td>   
  </tr> 
-->  
  <tr>
   <td style='font-weight:bold; color: white'>Valor Unitario</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="valor_unitario" size="10" value='<?php print("$valor_unitario");?>'></td>   
  </tr>     
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>     
 <?php
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
