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
  if (empty($_REQUEST['producto']))
    $error=$error . "Debe seleccionar un Producto.<br>";
    
  if (empty($_REQUEST['presentacion']))
    $error=$error . "Debe seleccionar una Presentacion.<br>"; 
       
  if (empty($_REQUEST['proveedor']))
    $error=$error . "Debe seleccionar un Proveedor.<br>";  

  if (empty($_REQUEST['factura']))
    $error=$error . "Debe digitar un numero de Factura.<br>";  
    
  if($_REQUEST['fecha']=='')
    $error=$error . "Debe seleccionar la Fecha de compra del Producto.<br>";
/*    
   if(filter_var($_REQUEST['cantidad'], FILTER_VALIDATE_FLOAT) === false)  
    $error=$error . "La Cantidad debe ser un Valor Numerico.<br>";    */

   if(filter_var($_REQUEST['valor_unitario'], FILTER_VALIDATE_FLOAT) === false)  
    $error=$error . "El Valor Unitario debe ser un Valor Numerico.<br>";         

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
$cod_compra = $_REQUEST[codigo0]; 
$cod_producto = $_REQUEST[producto];  
$cod_presentacion = $_REQUEST[presentacion]; 
$cod_proveedor = $_REQUEST[proveedor]; 
$factura = $_REQUEST[factura]; 
$fecha = $_REQUEST[fecha]; 
$cantidad = $_REQUEST[cantidad]; 
$valor_unitario = $_REQUEST[valor_unitario]; 
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO 0a_compra(cod_producto, cod_presentacion, cod_proveedor, factura, fecha, cantidad, valor_unitario) 
         VALUES ('$cod_producto','$cod_presentacion','$cod_proveedor','$factura','$fecha','$cantidad','$valor_unitario')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE 0a_compra SET cod_producto='$cod_producto', cod_presentacion='$cod_presentacion', cod_proveedor='$cod_proveedor', factura='$factura', fecha='$fecha', cantidad='$cantidad', valor_unitario='$valor_unitario'   
                      WHERE cod_compra = '$cod_compra'";
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
