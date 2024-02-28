<?php
/*
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  */
?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<body>

<?php
include("../conexion/conectarbd.php");
function validarDatosIngresados(){
$tipo_operacion = $_REQUEST[tipo_operacion];

if($tipo_operacion <= 2){
  $error="";
  if (empty($_REQUEST['nombre']))
    $error=$error . "El Nombre de la minuta no puede ser cadena vacía.<br>";   
  if (empty($_REQUEST['ciclo']))
    $error=$error . "Debe seleccionar un Ciclo.<br>";
  if (empty($_REQUEST['departamento']))
    $error=$error . "Debe seleccionar un Departamento.<br>";    
  if (empty($_REQUEST['tipo']))
    $error=$error . "Debe seleccionar un Tipo de Minuta.<br>";  

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
$cod_minuta = $_REQUEST[codigo0]; 
$nombre = strtoupper($_REQUEST[nombre]);
$ciclo = $_REQUEST[ciclo];  
$departamento = $_REQUEST[departamento]; 
$tipo = $_REQUEST[tipo]; 

////BUSCAMOS EL CODIGO MAXIMO QUE SE HA INSERTADO
  $instruccion ="SELECT MAX(cod_minuta) AS  max_minuta FROM minuta WHERE cod_minuta < 9000";
 
  $consulta = mysql_query($instruccion);
  error_consulta($consulta,$instruccion);
  $row = mysql_fetch_array($consulta); 
  
  $max_minuta = $row['max_minuta'];  
  
  $max_minuta = $max_minuta + 1;      
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO minuta(cod_minuta, nombre, cod_ciclo, cod_departamento, cod_tipo_minuta) VALUES ('$max_minuta','$nombre','$ciclo','$departamento','$tipo')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE minuta SET nombre='$nombre', cod_ciclo='$ciclo', cod_departamento='$departamento', cod_tipo_minuta='$tipo' 
                       WHERE cod_minuta = '$cod_minuta'";
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
