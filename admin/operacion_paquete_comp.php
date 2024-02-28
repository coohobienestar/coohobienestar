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

$cod_paquete  = $_GET['paquete'];
$cod_producto  = $_GET['producto'];
$tipo_operacion  = $_GET['tipo_operacion'];

$nom_form = "PAQUETE";

if($tipo_operacion == 1){
   $nom_operacion = "AGREGAR PRODUCTO ";
   $icono = "agregar_comp.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "ELIMINAR PRODUCTO ";
   $icono = "eliminar_menu.png";
 }

 
if($tipo_operacion == 9){
   $nom_operacion = "DUPLICAR UN PAQUETE DESDE OTRO ";
   $icono = "duplicar_minuta.png"; 
 }   
  
  ////GENERAMOS LA CONDICION DE LA CONSULTA
  $condicion = " WHERE ";
  
  $condicion2 = $condicion2. "0as_paquete_producto.cod_paquete = $cod_paquete AND ";

  if($cod_producto != 0){
     $condicion2 = $condicion2. "0as_paquete_producto.cod_producto = $cod_producto AND ";
    }        

  $condicion2 = substr($condicion2, 0, -4);
  $condicion_final = $condicion.$condicion2;   

   ////BUSCAMOS LOS DATOS DE LA MINUTA 
   $instruccion3 = "SELECT 0as_paquete_producto.cod_producto AS cod_producto, 0as_paquete_producto.cantidad AS cantidad, 0as_paquete_producto.relacion AS relacion, 
                           0as_paquete_producto.manipuladora AS manipuladora, 0as_producto.nombre AS nom_producto, 0as_paquete_aseo.nombre AS nom_paquete
                    FROM 0as_paquete_producto 
                    INNER JOIN 0as_producto ON 0as_producto.cod_producto = 0as_paquete_producto.cod_producto 
                    INNER JOIN 0as_paquete_aseo ON 0as_paquete_aseo.cod_paquete = 0as_paquete_producto.cod_paquete
                    $condicion_final
                    ORDER BY 0as_paquete_producto.cod_paquete, 0as_producto.nombre";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion); 
   $nfilas3 = mysql_num_rows ($consulta3);
    
   $row3 = mysql_fetch_array ($consulta3);
   
   $nom_paquete   = $row3['nom_paquete']; 
   $cantidad   = $row3['cantidad']; 
   $relacion  = $row3['relacion']; 
   $manipuladora = $row3['manipuladora']; 
   $nom_producto = $row3['nom_producto']; 
   
   if($nfilas3 == 0){
     ////BUSCAMOS LOS DATOS DE LA MINUTA SI LA MINUTA NO TIENE INFORMACION DE plato_ingrediente 
     $instruccion = "SELECT 0as_paquete_aseo.nombre AS nom_paquete, FROM 0as_paquete_aseo WHERE 0as_paquete_aseo.cod_paquete = $cod_paquete";
                      
     $consulta = mysql_query ($instruccion, $conexion); 
     
     $row = mysql_fetch_array ($consulta);
     
     $nom_paquete = $row['nom_paquete'];
    }
                                
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_paquete_comp.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='paquete0' value='<?php print("$cod_paquete");?>'>
 <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="2" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr> 
 <?php
  //// 1 AGREGAR PRODUCTO A PAQUETE 
  if($tipo_operacion == 1){
 ?> 
  <tr>
   <td style='font-weight:bold; color: white'>Paquete: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_paquete");?></td>   
  </tr>   
  <tr>
  <?php
      ////BUSCAMOS LOS PRODUCTOS
      print ("<TD style='font-weight:bold; color: white'>Producto </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='producto'>"); 
      
      $instruccion_m = "SELECT cod_producto, UPPER(nombre) AS nombre FROM 0as_producto ORDER BY nombre";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_producto'].">".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Cantidad</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cantidad" size="3" value=""></td>   
  </tr>  
  <tr>
   <td style='font-weight:bold; color: white'>Por cada</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="relacion" size="3" value=""></td>   
  </tr>  
  <tr>
  <?php
      ////BUSCAMOS LOS PRODUCTOS
      print ("<TD style='font-weight:bold; color: white'>Por Manipuladora? </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='manipuladora'>"); 
      
          print("<option value='0'>NO</option>");  
          print("<option value='1'>SI</option>");

        print("</SELECT></TD>");
  ?>  
  </tr>   
   <tr>
    <td>&nbsp;</td>                        
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿La información a registrar esta completa y correcta? \n Por favor verifique...")'></td>
  </tr>
 <?php
  }
  //// 2 ELIMINAR MENU
  if($tipo_operacion == 2){ 
 ?>
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Menu: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_menu");?></td>   
  </tr> 
   <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Eliminar" onclick='return confirm("¿Seguro que desea Eliminar la siguiente información? \n Menu: <?php print("[$cod_menu] - $nom_menu");?> \n Por favor verifique...")'></td>
  </tr> 
 <?php
  }
  //// 3 ELIMINAR PLATO
  if($tipo_operacion == 3){  
 ?>
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Menu: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_menu");?></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Plato: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_plato");?></td>   
  </tr>   
   <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Eliminar" onclick='return confirm("¿Seguro que desea Eliminar la siguiente información? \n Plato: <?php print("[$cod_plato] - $nom_plato");?> \n Por favor verifique...")'></td>
  </tr>   
 <?php
  }
  //// 4 ELIMINAR INGREDIENTE             
  if($tipo_operacion == 4){  
 ?> 
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Menu: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_menu");?></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Plato: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_plato");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Ingrediente: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_ingrediente");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Cantidad: </td>
   <td style='font-weight:bold; color: white'><?php print("$cantidad");?></td>   
  </tr>     
   <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Eliminar" onclick='return confirm("¿Seguro que desea Eliminar la siguiente información? \n Ingrediente: <?php print("[$cod_ingrediente] - $nom_ingrediente");?> \n Por favor verifique...")'></td>
  </tr>     
 <?php
  } 
  //// 5 EDITAR CANTIDAD INGREDIENTE
  if($tipo_operacion == 5){ 
 ?>
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Menu: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_menu");?></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Plato: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_plato");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Ingrediente: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_ingrediente");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Cantidad: </td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cantidad" size="3" value='<?php print("$cantidad");?>'></td>  
  </tr>     
   <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿La información a registrar esta completa y correcta? \n Por favor verifique...")'></td>
  </tr>  
 <?php
  }
  //// 6 AGREGAR INGREDIENTE
  if($tipo_operacion == 6){  
 ?>                 
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Menu: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_menu");?></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Plato: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_plato");?></td>   
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS INGREDIENTES
      print ("<TD style='font-weight:bold; color: white'>Ingrediente </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='ingrediente'>"); 
      
      $instruccion_m = "SELECT cod_ingrediente, UPPER(nombre) AS nombre FROM ingrediente ORDER BY nombre";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_ingrediente'].">".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Cantidad: </td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cantidad" size="3" value=''></td>  
  </tr>     
   <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿La información a registrar esta completa y correcta? \n Por favor verifique...")'></td>
  </tr> 
 <?php
  }
  ////7 - AGREGAR PLATO DESDE OTRA MINUTA
  if($tipo_operacion == 7){  
 ?> 
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS DIFERENTES PLATOS DE LAS MINUTAS DEL DEPARTAMENTO
      print ("<TD style='font-weight:bold; color: white'>Plato a Agregar </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='plato'>"); 
      
      $instruccion_m = "SELECT DISTINCT plato_ingrediente.cod_plato AS cod_plato, UPPER(plato.nombre) AS nombre
                        FROM plato_ingrediente 
                        INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                        INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                        ORDER BY plato.nombre";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_plato'].">".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?> 
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS MENUS
      print ("<TD style='font-weight:bold; color: white'>Menu Destino </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='menu'>"); 
      
      $instruccion_m = "SELECT cod_menu, nombre FROM menu ORDER BY cod_menu";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_menu'].">[".$row_m['cod_menu']."] - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>   
  </tr> 
  <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿La información a registrar esta completa y correcta? \n Por favor verifique...")'></td>
  </tr>  
 <?php
   } 
  ////8 - AGREGAR MENU A MINUTA DESDE OTRA MINUTA
  if($tipo_operacion == 8){     
 ?> 
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LAS MINUTAS DEL DEPARTAMENTO
      print ("<TD style='font-weight:bold; color: white'>Minuta Origen </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='minuta_o'>"); 
      
      $instruccion_m = "SELECT cod_minuta, nombre FROM minuta ORDER BY cod_minuta";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_minuta'].">[".$row_m['cod_minuta']."] - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS MENUS
      print ("<TD style='font-weight:bold; color: white'>Menu Origen </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='menu_o'>"); 
      
      $instruccion_m = "SELECT cod_menu, nombre FROM menu ORDER BY cod_menu";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_menu'].">[".$row_m['cod_menu']."] - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>   
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS MENUS
      print ("<TD style='font-weight:bold; color: white'>Menu Destino </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='menu_d'>"); 
      
      $instruccion_m = "SELECT cod_menu, nombre FROM menu ORDER BY cod_menu";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_menu'].">[".$row_m['cod_menu']."] - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>   
  </tr>   
  <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿La información a registrar esta completa y correcta? \n Por favor verifique...")'></td>
  </tr> 
 <?php
   } 
  ////9- DUPLICAR UNA MINUTA DESDE OTRA MINUTA
  if($tipo_operacion == 9){     
 ?> 
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LAS MINUTAS DEL DEPARTAMENTO
      print ("<TD style='font-weight:bold; color: white'>Minuta Origen </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='minuta_o'>"); 
      
      $instruccion_m = "SELECT cod_minuta, nombre FROM minuta ORDER BY cod_minuta";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_minuta'].">[".$row_m['cod_minuta']."] - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>
  </tr> 
  <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿La información a registrar esta completa y correcta? \n Por favor verifique...")'></td>
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