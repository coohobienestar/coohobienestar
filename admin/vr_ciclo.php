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
    $error=$error . "El Nombre del Ciclo no puede estar vacio.<br>";   
   
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

////BUSCAMOS EL CODIGO MAXIMO QUE SE HA INSERTADO
  $instruccion ="SELECT MAX(cod_ciclo) AS  max_ciclo FROM ciclo WHERE cod_ciclo < 900";
 
  $consulta = mysql_query($instruccion);
  error_consulta($consulta,$instruccion);
  $row = mysql_fetch_array($consulta); 
  
  $max_ciclo = $row['max_ciclo'];  
  
  $max_ciclo = $max_ciclo + 1;    

  if($tipo_operacion == 1){
     mysql_query("INSERT INTO ciclo (cod_ciclo, nombre) VALUES ('$max_ciclo','$nombre')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE ciclo SET nombre='$nombre' WHERE cod_ciclo = '$codigo'";
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
