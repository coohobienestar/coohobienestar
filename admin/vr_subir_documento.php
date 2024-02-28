<?php 
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">                                                               

<?php
include("../conexion/conectarbd.php");
function validarDatosIngresados(){
$conexion=Conectarse(); 
$tipo_operacion = $_REQUEST[tipo_operacion];

if($tipo_operacion <= 2){
  $error="";
  if (empty($_REQUEST['codigo']))
    $error=$error . "El Codigo del Documento no puede estar vacio.<br>";  
    
  if ($_REQUEST['version'] == '')
    $error=$error . "La Version del Documento no puede estar vacio.<br>"; 
    
  if (empty($_REQUEST['nombre']))
    $error=$error . "El nombre del Documento no puede estar vacio.<br>";  
    
  if (empty($_REQUEST['fecha_ult']))
    $error=$error . "La Fecha de la ultima revisión del Documento no puede estar vacia.<br>";    
    
  if (empty($_REQUEST['clasificacion']))
    $error=$error . "Debe seleccionar una Clasificación para el Documento.<br>";                 
   
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
$usuario = $_REQUEST[usuario0];

$codigo = strtoupper($_REQUEST[codigo]);
$version = strtoupper($_REQUEST[version]);
$nombre = strtoupper($_REQUEST[nombre]);
$fecha_ult = $_REQUEST[fecha_ult];
$clasificacion = $_REQUEST[clasificacion];

  if($tipo_operacion == 1){
     mysql_query("INSERT INTO 0c_documento_calidad (cod_usuario, codigo, version, nombre_documento, ult_fecha_revision, cod_clasificacion,obsoleto) 
         VALUES ('$usuario','$codigo','$version','$nombre','$fecha_ult','$clasificacion','0')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE ciclo SET nombre='$nombre' WHERE cod_ciclo = '$codigo'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    }
    
  if($tipo_operacion == 3){
      $cod_documento = $_REQUEST['documento0'];  
       
      ////SACAMOS TODOS LOS CHECKBOX DE LOS USUARIO   
      $instruccion3 = "SELECT cod_usuario, nombre, apellidos FROM usuario WHERE usuario_calidad = 1 ORDER BY cod_usuario";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_usuario = $resultado['cod_usuario'];
       $nom_usuario = $resultado['nombre'];
       
       $v_cod_usuario = $_REQUEST[$cod_usuario];

       if($v_cod_usuario!=''){
         ////ELIMINAMOS LOS USUARIOS PARA INSERTARLOS ACTUALIZADOS
         $instruccion_del = "DELETE FROM 0c_usuario_documento WHERE cod_usuario = $cod_usuario AND cod_documento = $cod_documento"; 
         $consulta_del = mysql_query ($instruccion_del, $conexion); 
           
         ////INSERTAMOS LOS DOCUMENTOS DEL USUARIO
         $instruccion5 = "INSERT INTO 0c_usuario_documento (cod_usuario, cod_documento) values ('$cod_usuario','$cod_documento')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS LOS USUARIOS QUE NO ESTAN SELECCIONADOS
          $instruccion_del = "DELETE FROM 0c_usuario_documento WHERE cod_usuario = $cod_usuario AND cod_documento = $cod_documento"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);  
          }
       } 
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
