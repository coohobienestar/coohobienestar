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
$cod_programacion    = $_REQUEST[codigo0];

if($tipo_operacion == 3){
  $error="";
    
    ////BUSCAMOS EL ESTADO DE LA PROGRAMACION
    $instruccion2 ="SELECT DISTINCT estado FROM programacion WHERE cod_programacion = $cod_programacion";     
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $row2 = mysql_fetch_array($consulta2);

    $estado = $row2['estado'];
    
    if($estado == 1){
      $error=$error . "La programación debe estar Inactiva para poder ser Eliminada<br>";  
     }

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
$cod_programacion    = $_REQUEST[codigo0];

    ////BUSCAMOS EL ESTADO DE LA PROGRAMACION
    $instruccion2 ="SELECT DISTINCT estado FROM programacion WHERE cod_programacion = $cod_programacion";     
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $row2 = mysql_fetch_array($consulta2);

    $estado = $row2['estado'];
    
    if($estado == 1){
      $estado_act = 0; 
     }else{
        $estado_act = 1; 
       }

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE programacion SET estado='$estado_act' WHERE cod_programacion = $cod_programacion";
     $consulta4 = mysql_query ($instruccion4, $conexion);
    }  

  if($tipo_operacion == 3){
     mysql_query("DELETE FROM programacion WHERE cod_programacion = $cod_programacion", $conexion);
     mysql_query("DELETE FROM calculo_redondeado_escuela WHERE cod_programacion = $cod_programacion", $conexion); 
     mysql_query("DELETE FROM calculo_requerimientos WHERE cod_programacion = $cod_programacion", $conexion); 
     mysql_query("DELETE FROM ingrediente_programacion WHERE cod_programacion = $cod_programacion", $conexion); 
     mysql_query("DELETE FROM item_programacion WHERE cod_programacion = $cod_programacion", $conexion); 
     mysql_query("DELETE FROM programacion_observacion WHERE cod_programacion = $cod_programacion", $conexion); 
     mysql_query("DELETE FROM observacion WHERE cod_programacion = $cod_programacion", $conexion); 
     mysql_query("DELETE FROM excluido_menu WHERE cod_programacion = $cod_programacion", $conexion); 
    }                             
} 
 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>
