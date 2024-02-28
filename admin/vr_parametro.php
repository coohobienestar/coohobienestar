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
$codigo = $_REQUEST[codigo0];

if($tipo_operacion <= 2){
  $error="";
  if (empty($_REQUEST['valor']))
    $error=$error . "El Valor del Parametro no puede estar vacio.<br>";   

 if($codigo == 'numero_reg_pagina'){
  if(filter_var($_REQUEST['valor'], FILTER_VALIDATE_INT) === false){  
    $error=$error . "El Valor del parametro: $codigo debe ser un valor Entero<br>";
   }          
  if ($_REQUEST['valor'] <= 0)
    $error=$error . "El Valor del Parametro: $codigo debe ser Mayor que Cero<br>";  
  }
  
  if (empty($_REQUEST['descripcion']))
    $error=$error . "La Descripción del Parametro no puede estar vacia.<br>";
    
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
$valor = strtoupper($_REQUEST[valor]);
$descripcion = $_REQUEST[descripcion];

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE parametro SET valor='$valor', descripcion='$descripcion'  WHERE nombre = '$codigo'";
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
