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
    $error=$error . "El Nombre del Centro de Acopio no puede estar vacio.<br>";  
    
  if(filter_var($_REQUEST['grupo'], FILTER_VALIDATE_INT) === false) 
       $error=$error . "El Grupo debe ser un valor Entero.<br>";
  
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
$codigo    = $_REQUEST[codigo0];
$nombre    = strtoupper($_REQUEST[nombre]);
$grupo    = $_REQUEST[grupo];
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO centro_acopio (nombre, grupo) VALUES ('$nombre', '$grupo')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE centro_acopio SET nombre='$nombre', grupo='$grupo' WHERE cod_centro_acopio = '$codigo'";
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
