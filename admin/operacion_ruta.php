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
$origen = $_REQUEST[origen];
$tipo_operacion = $_REQUEST[tipo_operacion];

$nom_form = " RUTA";

if($tipo_operacion == 0){
   $nom_operacion = "SELECCIONAR ";
   $icono = "guardar.png";
   
   $disabled = "";
 }

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
   
   $disabled = "";
   
   
   ////CONSULTAMOS LOS VALORES ACTUALES DE LA RUTA PREDETERMINADA
   $instruccion3 = "SELECT cod_origen, nombre, contenido, cedula_conductor, conductor, telefono_conductor, placa, tipo_vehiculo, cod_propiedad, programa, flete
                    FROM fl_origen 
                    WHERE cod_origen = $origen";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $cod_origen  = $row3['cod_origen'];  
   $nombre    = $row3['nombre']; 
   $contenido   = $row3['contenido'];
   $cedula_conductor = $row3['cedula_conductor'];
   $conductor = $row3['conductor'];   
   $telefono_conductor = $row3['telefono_conductor'];  
   $placa = $row3['placa'];  
   $tipo_vehiculo = $row3['tipo_vehiculo'];  
   $cod_propiedad = $row3['cod_propiedad'];  
   
  /*  if($cod_propiedad == ''){
        $disabled = "";
      }else{
         $disabled = "Disabled";
        }  */ 
   
    if($cod_propiedad == 1){
        $selected_p = "Selected";
      }else{
        $selected_p = "";
        }
      
    if($cod_propiedad == 2){
        $selected_c = "Selected";
      }else{
        $selected_c = "";
        }      
    
   $programa = $row3['programa'];  
   $flete = $row3['flete']; 
   $anticipo = 0;
   
   $fecha = date("Y-m-d");
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
   
    $disabled = "Disabled";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT cod_municipio, nombre, cod_departamento, cod_centro_zonal FROM municipio WHERE cod_municipio = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $cod_municipio  = $row3['cod_municipio'];  
   $nombre    = $row3['nombre']; 
   $cod_departamento = $row3['cod_departamento'];
   $cod_centro_zonal = $row3['cod_centro_zonal'];     
 }
 
if($tipo_operacion == 3){
   $nom_operacion = "MARCAR COMO PAGADO ";
   $icono = "pago.png";
   
   $disabled = "";
 } 
 
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form NAME='datechooser' action="vr_ruta.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
<input type='hidden' name='usuario0' value='<?php print("$cod_usuario");?>'>
 <table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
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
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$codigo");?>'></td>   
  </tr>
  <tr>
  <?php
    ////BUSCAMOS LOS ORIGENES
    print ("<TD style='font-weight:bold; color: white'>Origen </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='origen'>");                

    $instruccion = "SELECT cod_origen, nombre FROM fl_origen WHERE cod_origen = '$origen' ORDER BY nombre ";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 
    
    
      do{ 
        if($row['cod_origen'] == $cod_origen){
          print("<option value=".$row['cod_origen']." Selected>".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_origen'].">".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white' width='15%'>Fecha Viaje&nbsp;&nbsp;</td>
   <td><img width='18' height='18' src='../imagenes/calendar.png'><input type='text' size="10" name='fecha' value='<?php print("$fecha");?>' onfocus='doShow("datechooser1","datechooser","fecha")'><div enabled='false' id='datechooser1'></div></TD>
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Contenido</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="contenido" size="60" value='<?php print("$contenido");?>'></td>   
  </tr>  
  <tr>
   <td style='font-weight:bold; color: white'>Cedula</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cedula" size="40" value='<?php print("$cedula_conductor");?>'></td>   
  </tr>  
  <tr>
   <td style='font-weight:bold; color: white'>Conductor</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="conductor" size="40" value='<?php print("$conductor");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Teléfono Conductor</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="telefono" size="40" value='<?php print("$telefono_conductor");?>'></td>   
  </tr>  
  <tr>
   <td style='font-weight:bold; color: white'>Placa</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="placa" size="5" value='<?php print("$placa");?>'></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Tipo de Vehiculo</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="tipo_veh" size="20" value='<?php print("$tipo_vehiculo");?>'></td>   
  </tr> 
  <tr>
  <?php
    ////BUSCAMOS  PROPIEDAD
    print ("<TD style='font-weight:bold; color: white'>Es Propio </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='propio' $disabled>");                
          print("<option value='1' $selected_p>Propio</option>");
          print("<option value='2' $selected_c>Contratado</option>");
    print("</SELECT></TD>"); 
  ?>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Programa</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="programa" size="60" value='<?php print("$programa");?>'></td>   
  </tr>           
  <tr>
   <td style='font-weight:bold; color: white'>Valor Flete</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="flete" size="8" value='<?php print("$flete");?>'></td>   
  </tr>    
  <tr>
   <td style='font-weight:bold; color: white'>Valor Anticipo</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="anticipo" size="8" value='<?php print("$anticipo");?>'></td>   
  </tr>  
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿Esta seguro de la información proporcionada esta completa y correcta, despues no se podra modificar. Por favor verifique...?")'></td>
  </tr>           
        
<?php
 } 
 
 
  if($tipo_operacion == 3){  
?> 
  <tr>
   <td style='font-weight:bold; color: white' colspan='2'><center>DESEA MARCAR COMO PAGADO EL FLETE CON CODIGO:&nbsp;&nbsp;&nbsp;&nbsp; <?php print("$codigo");?></center></td> 
  </tr>
  <tr>
   <td style='font-weight:bold; color: white' width='15%'>Fecha Pago&nbsp;&nbsp;</td>
   <td><img width='18' height='18' src='../imagenes/calendar.png'><input type='text' size="10" name='fecha' value='<?php print("$fecha");?>' onfocus='doShow("datechooser1","datechooser","fecha")'><div enabled='false' id='datechooser1'></div></TD>
  </tr>              
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Pagado" onclick='return confirm("¿Esta seguro que desea marcar como pagado este flete...?")'></td>
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
