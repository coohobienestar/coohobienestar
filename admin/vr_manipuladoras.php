<?php 
session_start();

?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<body>

<?php
include("../conexion/conectarbd.php");
include("../funciones/calculo_documento_equivalente.php");

function validarDatosIngresados(){
$conexion=Conectarse();
 
$tipo_operacion = $_REQUEST[tipo_operacion];  
$cod_escuela = $_REQUEST[cod_escuela0];
$cod_mani = $_REQUEST[cod_mani0];
$anio = $_REQUEST[anio0];
$mes  = $_REQUEST[mes0]; 

if($tipo_operacion == 1){ 

  if (empty($_REQUEST['manipuladora']))
    $error=$error . "Debe seleccionar una Manipuladora.<br>";  
    
    $cod_manipuladora = $_REQUEST[manipuladora];       
    
  ///BUSCAMOS QUE LA MANIPULADORA YA NO ESTE REGISTRADA EN ESA ESCUELA
    $sql = "SELECT cod_manipuladora FROM escuela_manipuladora WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora";
    $result = mysql_query($sql);
    error_consulta($result,$sql); 
    $nfilas = mysql_num_rows ($result);

    if($nfilas > 0){  
      $error=$error . "La manipuladora ya se encuentra relacionada en esa escuela.<br>"; 
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
$cod_escuela = $_REQUEST[cod_escuela0];
$cod_mani = $_REQUEST[cod_mani0];
$anio = $_REQUEST[anio0];
$mes  = $_REQUEST[mes0];  
 
if($tipo_operacion == 1){
   $cod_manipuladora = $_REQUEST[manipuladora];     
   
   mysql_query("INSERT INTO escuela_manipuladora (cod_escuela, cod_manipuladora) VALUES ('$cod_escuela', '$cod_manipuladora')", $conexion);
  } 
    
if($tipo_operacion == 2){ 
   
  ////ELIMINAMOS LA RELACION DE LA MANIPULADORA CON AL ESCUELA
  $instruccion_del = "DELETE FROM escuela_manipuladora WHERE cod_manipuladora = $cod_mani AND cod_escuela = $cod_escuela";
  $consulta_del = mysql_query ($instruccion_del, $conexion);  
  
  ////ELIMINAMOS LA INFORMACION DE LAS RACIONES DE LA MANIPULADORA CON LA ESCUELA EN EL MES Y EL AÑO
  $instruccion_del = "DELETE FROM escuela_manipuladora_racion WHERE cod_manipuladora = $cod_mani AND cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'";
  $consulta_del = mysql_query ($instruccion_del, $conexion);    
  
  ////ELIMINAMOS EL DOCUMENO EQUIVALENTE DE LA MANIPULADORA CON LA ESCUELA EN EL MES Y EL AÑO
  $instruccion_del = "DELETE FROM documento_equivalente WHERE cod_manipuladora = $cod_mani AND cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'";
  $consulta_del = mysql_query ($instruccion_del, $conexion);  

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
