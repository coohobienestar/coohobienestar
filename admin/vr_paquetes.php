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

<?php
include("../conexion/conectarbd.php");
function validarDatosIngresados(){
$tipo_operacion = $_REQUEST[tipo_operacion];

if($tipo_operacion <= 2){
  $error="";
  if (empty($_REQUEST['nombre']))
    $error=$error . "El Nombre del paquete no puede ser cadena vacía.<br>";   

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
$cod_paquete = $_REQUEST[codigo0]; 
$nombre = strtoupper($_REQUEST[nombre]);
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO 0as_paquete_aseo (nombre) VALUES ('$nombre')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE 0as_paquete_aseo SET nombre='$nombre' WHERE cod_paquete = '$cod_paquete'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    }                             
} 
 
validarDatosIngresados();
altaDatos();
   
?>
<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">

<body onLoad="JavaScript:Cerraraliniciar()">
<script language="JavaScript">
function Cerraraliniciar(){
var id;
id = setTimeout("cerrar()", 1000);
}
function cerrar() {
var ventana = window.self;
ventana.opener = window.self;
ventana.close();
}
</script>

</body>
</html>
