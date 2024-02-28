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
    $error=$error . "El Nombre del Plato no puede estar vacio.<br>";   
  if (empty($_REQUEST['grupo']))
    $error=$error . "Debe seleccionar un Grupo para el Plato.<br>";
   
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
$grupo = $_REQUEST[grupo]; 
$panaderia = $_REQUEST[panaderia];  

if($panaderia == 'on'){
   $panaderia = 1;
  }else{
    $panaderia = 0;
    }
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO plato (nombre, cod_grupo_alimento, panaderia) VALUES ('$nombre','$grupo','$panaderia')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE plato SET nombre='$nombre', cod_grupo_alimento='$grupo', panaderia='$panaderia'  WHERE cod_plato = '$codigo'";
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
