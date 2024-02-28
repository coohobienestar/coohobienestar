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

$codigo= $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = " RUTA PREDETERMINADA";

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
   
   $disabled = "";
   
   $flete = 0;
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
   
    $disabled = "Disabled";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT fl_origen.cod_origen AS cod_origen, fl_origen.nombre AS nombre, fl_origen.contenido AS contenido, 
                             fl_origen.cedula_conductor AS cedula_conductor, fl_origen.conductor AS conductor, 
                             fl_origen.telefono_conductor AS telefono_conductor, fl_origen.placa AS placa,
                             fl_origen.tipo_vehiculo AS tipo_vehiculo, fl_origen.cod_propiedad AS cod_propiedad, 
                             fl_origen.programa AS programa, fl_origen.flete AS flete    
                      FROM fl_origen
                      WHERE fl_origen.cod_origen = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $nombre = $row3['nombre']; 
   $contenido = $row3['contenido'];
   $cedula_conductor = $row3['cedula_conductor'];
   $conductor = $row3['conductor'];
   $telefono_conductor = $row3['telefono_conductor'];
   $placa = $row3['placa'];
   $tipo_vehiculo = $row3['tipo_vehiculo'];
   $cod_propiedad    = $row3['cod_propiedad'];
   $programa    = $row3['programa'];
   $flete    = $row3['flete'];
   
   if($flete == ''){
      $flete = 0;
     }
   
 }
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_ruta_pred.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
<input type='hidden' name='usuario0' value='<?php print("$cod_usuario");?>'>

 <table width="95%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>  
<?php
 if($tipo_operacion <=2){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$codigo");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Nombre</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="nombre" size="60" value='<?php print("$nombre");?>'></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Contenido</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="contenido" size="80" value='<?php print("$contenido");?>'></td>   
  </tr>   
  <tr>
   <td style='font-weight:bold; color: white'>Cedula conductor</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="cedula_conductor" size="10" value='<?php print("$cedula_conductor");?>'></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Nombre conductor</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="conductor" size="40" value='<?php print("$conductor");?>'></td>   
  </tr>   
  <tr>
   <td style='font-weight:bold; color: white'>Telefono conductor</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="telefono_conductor" size="20" value='<?php print("$telefono_conductor");?>'></td>   
  </tr>  
  <tr>
   <td style='font-weight:bold; color: white'>Placa</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="placa" size="10" value='<?php print("$placa");?>'></td>   
  </tr>   
  <tr>
   <td style='font-weight:bold; color: white'>Tipo vehiculo</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="tipo_vehiculo" size="20" value='<?php print("$tipo_vehiculo");?>'></td>   
  </tr>  
  <tr> 
  <?php
    ////BUSCAMOS  PROPIEDAD
    print ("<TD style='font-weight:bold; color: white'>Es Propio </TD>");
    print ("<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME='propio'>");                
          print("<option value='1' $selected_p>Propio</option>");
          print("<option value='2' $selected_c>Contratado</option>");
    print("</SELECT></TD>"); 
  ?>    
  </tr>    
  <tr>
   <td style='font-weight:bold; color: white'>Programa</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="programa" size="60" value='<?php print("$programa");?>'></td>   
  </tr>    
  <tr>
   <td style='font-weight:bold; color: white'>Valor Flete</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="flete" size="8" value='<?php print("$flete");?>'></td>   
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
