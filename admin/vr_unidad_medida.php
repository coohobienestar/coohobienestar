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
    $error=$error . "El Nombre de la Unidad de Medida no puede estar vacio.<br>"; 

    if(filter_var($_REQUEST['valor_gr_cc'], FILTER_VALIDATE_FLOAT) === false){  
      $error=$error . "El Valor de la Unidad de Medida debe ser un valor Entero<br>";
     }          
    if ($_REQUEST['valor_gr_cc'] <= 0)
      $error=$error . "El Valor de la Unidad de Medida debe ser Mayor que Cero<br>";  
   
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
$valor_gr_cc = $_REQUEST[valor_gr_cc];   
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO unidad_medida (nombre, valor_gr_cc) VALUES ('$nombre','$valor_gr_cc')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE unidad_medida SET nombre='$nombre', valor_gr_cc='$valor_gr_cc' WHERE cod_unidad_medida = '$codigo'";
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
