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

$cod_paquete = $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];
$opcion_vista= $_GET['vista'];

$nom_form ="PAQUETE";

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
  
  ////BUSCAMOS LOS VALORES ACTUALES DEL REGISTRO
  $instruccion_e = "SELECT 0as_paquete_aseo.cod_paquete AS cod_paquete, 0as_paquete_aseo.nombre AS nom_paquete FROM 0as_paquete_aseo WHERE cod_paquete = $cod_paquete";
  $consulta_e = mysql_query ($instruccion_e, $conexion);  
  $row_e = mysql_fetch_array ($consulta_e);
  
  $nom_paquete = $row_e['nom_paquete'];

 }

if($tipo_operacion == 3){
   $nom_operacion = "MODIFICAR COMPONENTE ";
   $icono = "componente.png";
 } 

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(paquete,producto,tipo_operacion){ 
    var url="../admin/operacion_paquete_comp.php?paquete="+paquete+"&producto="+producto+"&tipo_operacion="+tipo_operacion;
    open(url,"_blank","Sizewindow,width=1100,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }                                                 
// -->
</SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?> </title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_paquetes.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$cod_paquete");?>'>
 <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="2" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>  
 </table> 
 <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
 <?php 
  if($tipo_operacion <= 2){
 ?>
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_paquete");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Nombre</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="nombre" size="100" value='<?php print("$nom_paquete");?>'></td>   
  </tr>  
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>     
 <?php
 }
 
if($tipo_operacion == 3){ 
     ////CONSULTAMOS LA INFORMACION DEL PAQUETE
     $sql1 ="SELECT 0as_paquete_aseo.cod_paquete AS cod_paquete, 0as_paquete_aseo.nombre AS nom_paquete 
             FROM 0as_paquete_aseo 
             WHERE  0as_paquete_aseo.cod_paquete = $cod_paquete";
     $consulta1 = mysql_query($sql1);
     error_consulta($consulta1,$sql1);  
     $row1 = mysql_fetch_array($consulta1);
     
     $nom_paquete = $row1['nom_paquete'];
     
     print("<table width='95%'' align='center' border='1'>");
     print("<tr>");
     print("<td style='font-weight:bold; color: white' align='Center' colspan='6'>$nom_paquete &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"); 
      if($opcion_vista == 1){  
        print("<a href=javascript:operar_tabla($cod_paquete,0,1) title='Agregar Producto Paquete'><img src='../imagenes/agregar_comp.png' alt='' width='24' height='24' border='0'></a>&nbsp;&nbsp;|&nbsp;&nbsp;");
        print("<a href=javascript:operar_tabla($cod_paquete,0,9) title='Duplicar Paquete'><img src='../imagenes/duplicar_minuta.png' alt='' width='24' height='24' border='0'></a>");     
        }
     print("</td>");
     print("</tr>");
     print("<tr>");
     print("<td style='font-weight:bold; color: white' align='Center'>Producto</td>");
     print("<td style='font-weight:bold; color: white' align='Center'>Cantidad</td>");
     print("<td style='font-weight:bold; color: white' align='Center'>Por cada</td>");
     print("<td style='font-weight:bold; color: white' align='Center'>Por Manipuladora?</td>");
     print("<td style='font-weight:bold; color: white' align='Center' colspan='2'>&nbsp;</td>");
     print("</tr>");
          
           ////BUSCAMOS LOS PRODUCTOS DEL PAQUETE  
           $sql3 ="SELECT 0as_paquete_producto.cod_producto AS cod_producto, 0as_paquete_producto.cantidad AS cantidad, 0as_paquete_producto.relacion AS relacion, 
                          0as_paquete_producto.manipuladora AS manipuladora, 0as_producto.nombre AS nom_producto
                   FROM 0as_paquete_producto 
                   INNER JOIN 0as_producto ON 0as_producto.cod_producto = 0as_paquete_producto.cod_producto 
                   WHERE 0as_paquete_producto.cod_paquete = $cod_paquete
                   ORDER BY 0as_paquete_producto.cod_paquete, 0as_producto.nombre";
           $consulta3 = mysql_query($sql3);
           error_consulta($consulta3,$sql3);
           $nfilas3 = mysql_num_rows ($consulta3);           
                         
             if ($nfilas3 > 0){
             $cod_plato_ant = "";
              for ($j=0; $j<$nfilas3; $j++){
               $row3 = mysql_fetch_array($consulta3);
               
               $cod_producto = $row3['cod_producto'];
               $nom_producto = $row3['nom_producto'];
               $nom_producto = strtoupper($nom_producto);
               $cantidad = $row3['cantidad'];
               $relacion = $row3['relacion'];
               $manipuladora= $row3['manipuladora'];
               
               if($manipuladora == 1){
                   $mani = "SI";
                   $sel_si = "Selected";
                 }else{
                    $mani = "NO";
                    $sel_no = "Selected";
                    }             
                  
                    print("<tr>"); 
                    print("<td style='font-weight:bold; color: white' align='left'>$nom_producto</td>");
                    print("<td style='font-weight:bold; color: white' align='Center'><input type='text' name='cantidad_$cod_producto' size=3 value='$cantidad'></td>");
                    print("<td style='font-weight:bold; color: white' align='Center'><input type='text' name='relacion_$cod_producto' size=3 value='$relacion'></td>");
                    print("<td style='font-weight:bold; color: white' align='Center'><SELECT NAME='manip_$cod_producto'>"); 
          
                          $instruccion_m = "SELECT cod_centro_acopio, nombre FROM 0as_centro_acopio ORDER BY nombre";
                          $consulta_m = mysql_query ($instruccion_m, $conexion);
                    
                          $row_m = mysql_fetch_array ($consulta_m); 
                          
                              print("<option value='0' $sel_no>NO</option>");
                              print("<option value='1' $sel_si>SI</option>");
                              
                    print("</SELECT></td>");
              
                  if($opcion_vista == 1){  
                    print("<td style='font-weight:bold; color: white' align='center'>");                    
                    print("<a href=javascript:operar_tabla($cod_paquete,$cod_producto,4) title='Quitar Producto'><img src='../imagenes/eliminar_ing.png' width='14' height='14' border='0' alt='Quitar Producto'></a>");
                    print("&nbsp;|&nbsp;");
                    print("<a href=javascript:operar_tabla($cod_paquete,$cod_producto,5) title='Editar Producto'><img src='../imagenes/editar_ing.png' width='14' height='14' border='0' alt='Editar Producto'></a>");
                    print("</td></tr>");
                    }
                if($conta==$nfilas4){             
                  print("</table>"); 
                  print("<br>");  
                 }
               }
              } 
             print("</td>");

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
