<?php 
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<body>

<?php
include("../conexion/conectarbd.php");
function validarDatosIngresados(){
$conexion=Conectarse(); 
$tipo_operacion = $_REQUEST[tipo_operacion];

if($tipo_operacion <= 2){
  $error="";
  if (empty($_REQUEST['nombre']))
    $error=$error . "El Nombre de la Opción no puede estar vacio.<br>";   
  if (empty($_REQUEST['ruta']))
    $error=$error . "La Ruta de la Opción no puede estar vacia.<br>";
  if (empty($_REQUEST['tipo']))
    $error=$error . "Debe seleccionar un Tipo de Opción.<br>";
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 
 
} 
  
function altaDatos(){
$conexion=Conectarse(); 

$tipo_operacion = $_REQUEST[tipo_operacion];
$codigo = $_REQUEST[codigo0];
$nombre = $_REQUEST[nombre];
$ruta = $_REQUEST[ruta];   
$tipo = $_REQUEST[tipo];   
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO opcion (nombre, ruta, cod_tipo_opcion) VALUES ('$nombre','$ruta','$tipo')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE opcion SET nombre='$nombre', ruta='$ruta', cod_tipo_opcion='$tipo' WHERE id_opcion = '$codigo'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    }                             
} 
 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>
