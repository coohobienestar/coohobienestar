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
    $error=$error . "El nombre de la ruta no puede estar vacio.<br>"; 
      
  if(filter_var($_REQUEST['flete'], FILTER_VALIDATE_INT) === false)  
    $error=$error . "El valor del flete debe ser un valor entero.<br>";    
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 
 
} 
  
function altaDatos(){
$conexion=Conectarse(); 

$fecha_sis = date("Y-m-d");

$tipo_operacion = $_REQUEST[tipo_operacion];
$codigo    = $_REQUEST[codigo0];
$usuario = $_REQUEST[usuario0];   

$nombre    = strtoupper($_REQUEST[nombre]);   
$contenido = ucfirst($_REQUEST[contenido]);
$cedula = strtoupper($_REQUEST[cedula_conductor]);
$conductor = strtoupper($_REQUEST[conductor]);
$telefono = strtoupper($_REQUEST[telefono_conductor]);
$placa = strtoupper($_REQUEST[placa]);
$tipo_veh = strtoupper($_REQUEST[tipo_vehiculo]);
$propio = strtoupper($_REQUEST[propio]);
$programa = strtoupper($_REQUEST[programa]);
$flete = $_REQUEST[flete];


  if($tipo_operacion == 1){
     $instruccion4 = "INSERT INTO fl_origen (nombre, contenido, cedula_conductor, conductor, telefono_conductor, placa, tipo_vehiculo, cod_propiedad, programa, flete, fecha_sistema, cod_usuario) 
                      VALUES ('$nombre','$contenido','$cedula','$conductor','$telefono','$placa','$tipo_veh','$propio','$programa','$flete','$fecha_sis','$usuario')";
     $consulta4 = mysql_query($instruccion4, $conexion);
    } 

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE fl_origen SET nombre='$nombre', contenido = '$contenido', cedula_conductor = '$cedula', conductor = '$conductor', 
                                           telefono_conductor = '$telefono', placa = '$placa', tipo_vehiculo = '$tipo_veh', cod_propiedad = '$propio', 
                                           programa = '$programa', flete = '$flete', fecha_sistema = '$fecha_sis', cod_usuario = '$usuario'        
                      WHERE cod_origen = '$codigo'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    }                             
} 
 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
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
