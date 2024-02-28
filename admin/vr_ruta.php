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

if($tipo_operacion == 0){
  
  }

if($tipo_operacion == 1){
  $error="";
  if (empty($_REQUEST['fecha']))
    $error=$error . "Debe seleccionar la fecha del recorrido.<br>"; 
    
  if (empty($_REQUEST['cedula']))
    $error=$error . "Debe digitar la Cedula del conductor.<br>";     
    
  if (empty($_REQUEST['conductor']))
    $error=$error . "Debe digitar el nombre del conductor.<br>";  
    
  if (empty($_REQUEST['telefono']))
    $error=$error . "Debe digitar el nombre del conductor.<br>";     
    
  if (empty($_REQUEST['placa']))
    $error=$error . "Debe digitar la placa del vehiculo.<br>";   

  if (empty($_REQUEST['tipo_veh']))
    $error=$error . "Debe digitar el tipo de vehiculo que va a realizar el recorrido.<br>";  

  if (empty($_REQUEST['programa']))
    $error=$error . "Debe digitar el programa para el cual se realiza el recorrido.<br>"; 

  if (empty($_REQUEST['flete']))
    $error=$error . "Debe digitar el valor del flete.<br>";  
    
  if(filter_var($_REQUEST['flete'], FILTER_VALIDATE_INT) === false)  
    $error=$error . "El valor del flete debe ser un valor entero.<br>";     
    
  if($_REQUEST['flete'] <=0)   
    $error=$error . "El valor del flete debe ser Mayor que Cero<br>";   
        
  if(filter_var($_REQUEST['anticipo'], FILTER_VALIDATE_INT) === false)  
    $error=$error . "El valor del anticipo debe ser un valor entero o Cero si no hay anticipo.<br>";  
    
  if($_REQUEST['anticipo'] > $_REQUEST['flete']) 
    $error=$error . "El valor del anticipo no puede superar el valor del flete.<br>";                                 
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 

if($tipo_operacion == 3){
  if (empty($_REQUEST['fecha']))
    $error=$error . "Debe seleccionar la fecha en que se realizo el pago.<br>"; 

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
$origen = $_REQUEST[origen];
//$otro_origen = strtoupper($_REQUEST[otro_origen]);
//$destino = $_REQUEST[destino];
//$otro_destino = strtoupper($_REQUEST[otro_destino]);
//$destino_direccion = strtoupper($_REQUEST[destino_direccion]);
//$descripcion = $_REQUEST[descripcion];
$fecha = $_REQUEST[fecha];  
$fecha_sis = date("Y-m-d");
$contenido = ucfirst($_REQUEST[contenido]);
$cedula = strtoupper($_REQUEST[cedula]);
$conductor = strtoupper($_REQUEST[conductor]);
$telefono = strtoupper($_REQUEST[telefono]);
$placa = strtoupper($_REQUEST[placa]);
$tipo_veh = strtoupper($_REQUEST[tipo_veh]);
$propio = strtoupper($_REQUEST[propio]);
$programa = strtoupper($_REQUEST[programa]);
$flete = $_REQUEST[flete];
$anticipo = $_REQUEST[anticipo];
$usuario = $_REQUEST[usuario0];   

$saldo = $flete - $anticipo;  

  if($tipo_operacion == 1){
     mysql_query("INSERT INTO fl_ruta (cod_origen, fecha_ruta, fecha_sistema, contenido, cedula_conductor, conductor, telefono_conductor, placa, tipo_vehiculo, cod_propiedad, programa, flete, anticipo, saldo, cod_usuario) 
                  VALUES ('$origen','$fecha','$fecha_sis','$contenido','$cedula','$conductor','$telefono','$placa','$tipo_veh','$propio','$programa','$flete','$anticipo','$saldo','$usuario')", $conexion);
    } 

  if($tipo_operacion == 3){  
     $fecha_sis = date("Y-m-d");
     $fecha = $_REQUEST[fecha];
  
     $instruccion4 = "UPDATE fl_ruta SET pagado = '1', pagado_por = '$usuario', fecha_pago = '$fecha', fecha_pago_sistema = '$fecha_sis' WHERE cod_ruta = $codigo";
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
