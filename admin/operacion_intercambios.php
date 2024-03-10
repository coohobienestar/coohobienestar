<?php
session_start();

include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 


$cod_programacion = $_GET['codigo'];
$cod_ingrediente = $_GET['cod_ingrediente'];
$tipo_operacion = $_GET['tipo_operacion'];    


if($tipo_operacion == 1){
    $icono = "guardar.png";
   $nom_form = "REGISTRAR INTERCAMBIO PRODUCTO";
 }

# EDITAR
if($tipo_operacion == 4){
   $nom_operacion = "EDITAR OBSERVACION";
   $icono = "editar.png";
   
   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT observacion.cod_observacion AS cod_observacion, observacion.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio, 
                             observacion.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela, observacion.cod_tipo_minuta AS cod_tipo_minuta, 
                             tipo_minuta.nombre AS nom_tipo_minuta, observacion.observacion_lista_entrega AS observacion_lista_entrega, 
                             observacion.observacion_control_es AS observacion_control_es 
                      FROM observacion 
                      LEFT JOIN municipio ON municipio.cod_municipio = observacion.cod_municipio
                      LEFT JOIN escuela ON escuela.cod_escuela = observacion.cod_escuela
                      LEFT JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = observacion.cod_tipo_minuta
                      WHERE observacion.cod_observacion = $num_observacion";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);
   
   $cod_municipio = $row3['cod_municipio'];  
   $cod_escuela = $row3['cod_escuela']; 
   $cod_tipo_minuta = $row3['cod_tipo_minuta']; 
   $observacion_lista_entrega = $row3['observacion_lista_entrega']; 
   $observacion_lista_entrega = trim ($observacion_lista_entrega);
   $observacion_control_es = $row3['observacion_control_es']; 
   $observacion_control_es = trim($observacion_control_es);        
   
   if($cod_municipio == '0'){
      $disable_m = "Disabled";
     }else{
         $disable_m = "";
       }
   if($cod_escuela == '0'){
      $disable_e = "Disabled";
     }else{
         $disable_e = "";
        }    
      
    if($observacion_lista_entrega != '' && $observacion_control_es != ''){
       $observacion = $observacion_lista_entrega;
       $selected3 = "Selected";
      }
 
    if($observacion_lista_entrega != '' && $observacion_control_es == ''){
       $observacion = $observacion_lista_entrega;
       $selected1 = "Selected";
      }  

    if($observacion_lista_entrega == '' && $observacion_control_es != ''){
       $observacion = $observacion_control_es;
       $selected2 = "Selected";
      }                                      
 
 }

#ELIMINAR 
if($tipo_operacion == 5){
   $nom_operacion = "ELIMINAR ";
   $nom_form = " OBSERVACION";
   $icono = "borrar.png";
 }

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_intercambio.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$num_observacion");?>'>
<input type='hidden' name='programacion0' value='<?php print("$cod_programacion");?>'>
<input type='hidden' name='ingrediente0' value='<?php print("$cod_ingrediente");?>'>
<input type='hidden' name='municipio0' value='<?php print("$cod_municipio");?>'>
<input type='hidden' name='escuela0' value='<?php print("$cod_escuela");?>'>
 <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
    </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
    </tr>  
<?php
 if($tipo_operacion == 1){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_programacion");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Código ingrediente</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_ingrediente");?>'></td>   
  </tr>
  <tr>
    
  <?php
    ////BUSCAMOS LOS TIPOS DE MINUTA
    print ("<TD style='font-weight:bold; color: white'>Ingrediente Intercambio </TD>");
    print ("<TD><SELECT NAME='cod_ingrediente'>");                

    $instruccion = "SELECT cod_ingrediente, nombre FROM ingrediente ORDER BY nombre";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
          print("<option value=".$row['cod_ingrediente'].">".$row['nombre']."</option>");
          // print("<option value=".$row['cod_ingrediente']." Selected> ".$row['nombre']. "- [" . $row['cod_ingrediente']."]</option>");

         
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>
  </tr>   
<?php
 }  
?>  
<?php
 if($tipo_operacion == 2){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>C�digo</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_programacion");?>'></td>   
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS MUNICIPIO
    print ("<TD style='font-weight:bold; color: white'>Municipio </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='municipio'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                    FROM calculo_redondeado_escuela 
                    INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion
                    ORDER BY calculo_redondeado_escuela.cod_municipio";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_municipio'] == $cod_municipio){
          print("<option value=".$row['cod_municipio']." Selected>[".$row['cod_municipio']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_municipio'].">[".$row['cod_municipio']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS TIPOS DE MINUTA
    print ("<TD style='font-weight:bold; color: white'>Ingrediente Intercambio </TD>");
    
    print ("<TD><SELECT NAME='tipo'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre
                    FROM calculo_redondeado_escuela 
                    INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion
                    ORDER BY calculo_redondeado_escuela.cod_tipo_minuta";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_tipo_minuta'] == $cod_tipo_minuta){
          print("<option value=".$row['cod_tipo_minuta']." Selected>[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_tipo_minuta'].">[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>
  </tr>      
  <tr>
   <td style='font-weight:bold; color: white'>Aplicar a Formato</td>
   <td><img src='../imagenes/requerido.gif'><select name="formato">
    <option value="3">Ambos</option>
    <option value="1">Listas de Entrega</option>
    <option value="2">Control de E/S</option>
   </td> 
  </select>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Observaci�n</td>
   <td><img src='../imagenes/requerido.gif'><textarea name="observacion" cols="80" rows="5"></textarea></td>   
  </tr>
<?php
 } 
?>     
<?php
 if($tipo_operacion == 3){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>C�digo</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_programacion");?>'></td>   
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LAS ESCUELAS
    print ("<TD style='font-weight:bold; color: white'>Escuela </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='escuela'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_escuela AS cod_escuela, escuela.nombre AS nombre,
                                    municipio.nombre AS nom_municipio
                    FROM calculo_redondeado_escuela 
                    INNER JOIN escuela ON escuela.cod_escuela = calculo_redondeado_escuela.cod_escuela
                    INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion
                    ORDER BY escuela.nombre";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_escuela'] == $cod_escuela){
          print("<option value=".$row['cod_escuela']." Selected>".$row['cod_escuela']." - ".$row['nombre']. "- [" . $row['nom_municipio']."]</option>");
         }else{ 
           print("<option value=".$row['cod_escuela'].">".$row['cod_escuela']." - ".$row['nombre']. " - [" . $row['nom_municipio']."]</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>      
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS TIPOS DE MINUTA
    print ("<TD style='font-weight:bold; color: white'>Tipo de Minuta </TD>");
    print ("<TD><SELECT NAME='tipo'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre
                    FROM calculo_redondeado_escuela 
                    INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion 
                    ORDER BY calculo_redondeado_escuela.cod_tipo_minuta";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_tipo_minuta'] == $cod_tipo_minuta){
          print("<option value=".$row['cod_tipo_minuta']." Selected>[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_tipo_minuta'].">[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>
  </tr>     
  <tr>
   <td style='font-weight:bold; color: white'>Aplicar a Formato</td>
   <td><img src='../imagenes/requerido.gif'><select name="formato">
    <option value="3">Ambos</option>
    <option value="1">Listas de Entrega</option>
    <option value="2">Control de E/S</option>
   </td> 
  </select>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Observaci�n</td>
   <td><img src='../imagenes/requerido.gif'><textarea name="observacion" cols="50" rows="5"></textarea></td>   
  </tr> 
<?php
 }  
?>  

<?php
 if($tipo_operacion == 4){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>C�digo</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_programacion");?>'></td>   
  </tr>
  <tr>
  <?php
   if($cod_municipio != 0){ 
    ////BUSCAMOS LOS MUNICIPIO
    print ("<TD style='font-weight:bold; color: white'>Municipio </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='municipio'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                    FROM calculo_redondeado_escuela 
                    INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion
                    ORDER BY calculo_redondeado_escuela.cod_municipio";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_municipio'] == $cod_municipio){
          print("<option value=".$row['cod_municipio']." Selected $disable_m>[".$row['cod_municipio']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_municipio']." $disable_m>[".$row['cod_municipio']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
    }
  ?>
  </tr>  
  <tr>
  <?php
   if($cod_escuela != 0){ 
    ////BUSCAMOS LAS ESCUELAS
    print ("<TD style='font-weight:bold; color: white'>Escuela </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='escuela'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_escuela AS cod_escuela, escuela.nombre AS nombre,
                                    municipio.nombre AS nom_municipio
                    FROM calculo_redondeado_escuela 
                    INNER JOIN escuela ON escuela.cod_escuela = calculo_redondeado_escuela.cod_escuela
                    INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion
                    ORDER BY escuela.nombre";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_escuela'] == $cod_escuela){
          print("<option value=".$row['cod_escuela']." Selected> ".$row['nombre']. "- [" . $row['nom_municipio']."]</option>");
         }else{ 
           print("<option value=".$row['cod_escuela'].">".$row['nombre']. " - [" . $row['nom_municipio']."]</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
    }  
  ?>      
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS TIPOS DE MINUTA
    print ("<TD style='font-weight:bold; color: white'>Tipo de Minuta </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='tipo'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre
                    FROM calculo_redondeado_escuela 
                    INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion 
                    ORDER BY calculo_redondeado_escuela.cod_tipo_minuta";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_tipo_minuta'] == $cod_tipo_minuta){
          print("<option value=".$row['cod_tipo_minuta']." Selected>[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_tipo_minuta'].">[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>
  </tr>    
  <tr>
   <td style='font-weight:bold; color: white'>Aplicar a Formato</td>
   <td><img src='../imagenes/requerido.gif'><select name="formato">
    <option value="3" <?php echo $selected3; ?> >Ambos</option>
    <option value="1" <?php echo $selected1; ?> >Listas de Entrega</option>
    <option value="2" <?php echo $selected2; ?> >Control de E/S</option>
   </td> 
  </select>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Observaci�n</td>
   <td><img src='../imagenes/requerido.gif'><textarea name="observacion" cols="50" rows="5"><?php echo $observacion; ?></textarea></td>   
  </tr> 
<?php
 } 

if($tipo_operacion == 5){ 
?>
  <TABLE width='100%' align='center'>
  <TR><TD  style='font-weight:bold; color: white' ><center><strong>Va a eliminar la Observacion #: <?php echo  $num_observacion; ?> </strong></center></TD></TR>      
  </TR>
<?php 
 }
if($tipo_operacion == 6){    
?>

  <TABLE width='100%' align='center'>
  <TR><TD  style='font-weight:bold; color: white' ><center><strong>Va a Duplicar las observaciones de la Programaci�n: <?php echo  $cod_programacion; ?> </strong></center></TD></TR>      
  </TR>

<?php 
    ////BUSCAMOS LAS PROGRAMACIONES ACTIVAS
      print ("<TR><TD style='font-weight:bold; color: white'>Programaci�n Destino");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion'>");                

      $instruccion = "SELECT DISTINCT programacion.cod_programacion AS cod_programacion, ciclo.nombre AS nombre
                      FROM programacion 
                      INNER JOIN ciclo ON ciclo.cod_ciclo = programacion.cod_ciclo 
                      WHERE programacion.estado = 1
                      ORDER BY programacion.cod_programacion DESC";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_programacion'].">[".$row['cod_programacion']."] - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");
 }
?> 
<?php
if($tipo_operacion == 7){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_programacion");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Código Ingrediente</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_ingrediente");?>'></td>   
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS TIPOS DE MINUTA
    print ("<TD style='font-weight:bold; color: white'>Tipo de Minuta </TD>");
    print ("<TD><SELECT NAME='tipo'>");                

    $instruccion = "SELECT DISTINCT calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre
                    FROM calculo_redondeado_escuela 
                    INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta
                    WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion
                    ORDER BY calculo_redondeado_escuela.cod_tipo_minuta";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_tipo_minuta'] == $cod_tipo_minuta){
          print("<option value=".$row['cod_tipo_minuta']." Selected>[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_tipo_minuta'].">[".$row['cod_tipo_minuta']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>
  </tr>  
  <tr>
   <td style='font-weight:bold; color: white'>Aplicar a Formato</td>
   <td><img src='../imagenes/requerido.gif'><select name="formato">
    <option value="3">Ambos</option>
    <option value="1">Listas de Entrega</option>
    <option value="2">Control de E/S</option>
   </td> 
  </select>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Observaci�n</td>
   <td><img src='../imagenes/requerido.gif'><textarea name="observacion" cols="50" rows="5"></textarea></td>   
  </tr>  
<?php
 }  
?>


  <tr>   
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr> 
            
 </table>  
</form>
</body>
</html>

<?php
// Cerrar conexi�n
mysql_close ($conexion);   
?>
