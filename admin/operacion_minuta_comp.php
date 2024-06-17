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

$cod_minuta  = $_GET['minuta'];
$cod_menu    = $_GET['menu'];
$cod_plato   = $_GET['plato'];
$cod_ingrediente  = $_GET['ingrediente'];
$tipo_operacion  = $_GET['tipo_operacion'];

$nom_form = "MINUTA";

if($tipo_operacion == 1){
   $nom_operacion = "AGREGAR COMPONENTE ";
   $icono = "agregar_comp.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "ELIMINAR MENU ";
   $icono = "eliminar_menu.png";
 }

if($tipo_operacion == 3){
   $nom_operacion = "ELIMINAR PLATO ";
   $icono = "eliminar_pla.png";
 }
 
if($tipo_operacion == 4){
   $nom_operacion = "ELIMINAR INGREDIENTE ";
   $icono = "eliminar_ing.png";
 } 

if($tipo_operacion == 5){
   $nom_operacion = "EDITAR CANTIDAD INGREDIENTE ";
   $icono = "editar_ing.png"; 
 }  

if($tipo_operacion == 6){
   $nom_operacion = "AGREGAR INGREDIENTE ";
   $icono = "agregar_ing.png"; 
 }
 
if($tipo_operacion == 7){
   $nom_operacion = "AGREGAR PLATO DESDE OTRA ";
   $icono = "agregar_pla.png"; 
 } 

if($tipo_operacion == 8){
   $nom_operacion = "AGREGAR MENU A MINUTA DESDE OTRA ";
   $icono = "agregar_pla.png"; 
 }  
 
if($tipo_operacion == 9){
   $nom_operacion = "DUPLICAR UNA MINUTA DESDE OTRA ";
   $icono = "duplicar_minuta.png"; 
 }   
  
  ////GENERAMOS LA CONDICION DE LA CONSULTA
  $condicion = " WHERE ";
  
  $condicion2 = $condicion2. "plato_ingrediente.cod_minuta = $cod_minuta AND ";

  if($cod_menu != 0){
     $condicion2 = $condicion2. "plato_ingrediente.cod_menu = $cod_menu AND ";
    }
  if($cod_plato != 0){
     $condicion2 = $condicion2. "plato_ingrediente.cod_plato = $cod_plato AND ";
    } 
  if($cod_ingrediente != 0){
     $condicion2 = $condicion2. "plato_ingrediente.cod_ingrediente = $cod_ingrediente AND ";
    }        

  $condicion2 = substr($condicion2, 0, -4);
  $condicion_final = $condicion.$condicion2;   

   ////BUSCAMOS LOS DATOS DE LA MINUTA 
   $instruccion3 = "SELECT minuta.nombre AS nom_minuta, menu.nombre AS nom_menu, plato.nombre AS nom_plato, ingrediente.nombre AS nom_ingrediente,
                           plato_ingrediente.cantidad AS cantidad, minuta.cod_departamento AS cod_departamento
                    FROM plato_ingrediente 
                    INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                    INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu 
                    INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                    INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                    $condicion_final
                    LIMIT 1";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion); 
   $nfilas3 = mysql_num_rows ($consulta3);
    
   $row3 = mysql_fetch_array ($consulta3);
   
   $nom_minuta = $row3['nom_minuta'];  
   $nom_menu   = $row3['nom_menu']; 
   $nom_plato  = $row3['nom_plato']; 
   $nom_ingrediente = $row3['nom_ingrediente']; 
   $cantidad = $row3['cantidad']; 
   $cod_departamento = $row3['cod_departamento']; 
   
   if($nfilas3 == 0){
     ////BUSCAMOS LOS DATOS DE LA MINUTA SI LA MINUTA NO TIENE INFORMACION DE plato_ingrediente 
     $instruccion = "SELECT minuta.nombre AS nom_minuta, minuta.cod_departamento AS cod_departamento FROM minuta WHERE minuta.cod_minuta = $cod_minuta";
                      
     $consulta = mysql_query ($instruccion, $conexion); 
     
     $row = mysql_fetch_array ($consulta);
     
     $nom_minuta = $row['nom_minuta'];
     $cod_departamento = $row['cod_departamento'];      
    }
                                
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_minuta_comp.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$cod_minuta");?>'>
<input type='hidden' name='menu0' value='<?php print("$cod_menu");?>'>
<input type='hidden' name='plato0' value='<?php print("$cod_plato");?>'>
<input type='hidden' name='ingrediente0' value='<?php print("$cod_ingrediente");?>'>
 <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="2" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr> 
 <?php
  //// 1 AGREGAR COMPONENTE MINUTA 
  if($tipo_operacion == 1){
 ?> 
  <tr>
   <td style='font-weight:bold; color: white'>Minuta: </td>
   <td style='font-weight:bold; color: white'><?php print("$nom_minuta");?></td>   
  </tr>   
  <tr>
  <?php
    ////BUSCAMOS LOS MENUS
    print ("<TD style='font-weight:bold; color: white'>Menu </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='menu'>");                

    $instruccion = "SELECT cod_menu, UPPER(nombre) AS nombre FROM menu ORDER BY cod_menu";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        print("<option value=".$row['cod_menu'].">[".$row['cod_menu']."] - ".$row['nombre']."</option>");            
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS PLATOS
      print ("<TD style='font-weight:bold; color: white'>Plato </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='plato'>"); 
      
      $instruccion_m = "SELECT cod_plato, UPPER(nombre) AS nombre FROM plato ORDER BY nombre";
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
   <td style='font-weight:bold; color: white'>Cantidad</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cantidad" size="3" value=""></td>   
  </tr>  
   <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("�La informaci�n a registrar esta completa y correcta? \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Eliminar" onclick='return confirm("�Seguro que desea Eliminar la siguiente informaci�n? \n Menu: <?php print("[$cod_menu] - $nom_menu");?> \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Eliminar" onclick='return confirm("�Seguro que desea Eliminar la siguiente informaci�n? \n Plato: <?php print("[$cod_plato] - $nom_plato");?> \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Eliminar" onclick='return confirm("�Seguro que desea Eliminar la siguiente informaci�n? \n Ingrediente: <?php print("[$cod_ingrediente] - $nom_ingrediente");?> \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("�La informaci�n a registrar esta completa y correcta? \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("�La informaci�n a registrar esta completa y correcta? \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("�La informaci�n a registrar esta completa y correcta? \n Por favor verifique...")'></td>
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
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("�La informaci�n a registrar esta completa y correcta? \n Por favor verifique...")'></td>
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
           print("<option value=".$row_m['cod_minuta'].">".$row_m['cod_minuta']." - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>
  </tr> 
  <tr>
    <td>&nbsp;</td>
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Registrar" onclick='return confirm("�La informaci�n a registrar esta completa y correcta? \n Por favor verifique...")'></td>
  </tr> 
 <?php
   } 
 ?>   
 </table>  
</form>
</body>
</html>

<?php
// Cerrar conexi�n
mysql_close ($conexion);   
?>