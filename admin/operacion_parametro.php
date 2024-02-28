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

$nom_form = " PARAMETRO";

if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
   
    $disabled = "Disabled";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT nombre, valor, descripcion, modificable FROM parametro WHERE nombre = '$codigo'";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $nombre = $row3['nombre'];
   $valor = $row3['valor'];
   $descripcion = $row3['descripcion'];
   $modificable = $row3['modificable']; 
   
   if($modificable == 1){
     $checked = "Checked";
     $v_disabled = "";
    }else{
       $checked = "";
       $v_disabled = "Disabled";
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
<form action="vr_parametro.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
 <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
   <td style='font-weight:bold; color: white'>Nombre</td>
   <td><input type="text" Disabled name="nombre" size="40" value='<?php print("$codigo");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Valor</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" <?php print("$v_disabled");?> name="valor" size="160" value='<?php print("$valor");?>'></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Descripción</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="descripcion" size="160" value='<?php print("$descripcion");?>'></td>   
  </tr>   
  <tr>
   <td style='font-weight:bold; color: white'>Modificable</td>
   <td><img src="../imagenes/requerido.gif"><input type="checkbox" <?php print("$checked");?> <?php print("$disabled");?> name="modificable"></td>   
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
