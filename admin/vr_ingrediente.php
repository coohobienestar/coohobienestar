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
    $error=$error . "El Nombre del Ingrediente no puede estar vacio.<br>";   
  if (empty($_REQUEST['categoria']))
    $error=$error . "Debe seleccionar una Categoria para el Ingrediente.<br>";
  if (empty($_REQUEST['unibase']))
    $error=$error . "Debe seleccionar una Unidad Base para el Ingrediente.<br>"; 
  if ($_REQUEST['inventario'] == '')
    $error=$error . "Debe seleccionar si el Ingrediente maneja o no inventario.<br>";       
    
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
$categoria = $_REQUEST[categoria];
$redondear = $_REQUEST[redondear];  
$unidad_base = $_REQUEST[unibase]; 
$redondear2 = $_REQUEST[redondear2];
$maneja_inventario = $_REQUEST[inventario];

if($redondear == 'on'){
   $redondear = 1;
  }else{
    $redondear = 0;
    }

if($redondear2 == 'on'){
   $redondear2 = 1;
  }else{
    $redondear2 = 0;
    }    
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO ingrediente (cod_categoria_ingrediente, nombre, redondear, unidad_base, redondear2, maneja_inventario) VALUES ('$categoria','$nombre','$redondear','$unidad_base','$redondear2','$maneja_inventario')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE ingrediente SET cod_categoria_ingrediente='$categoria', nombre='$nombre', redondear='$redondear', unidad_base='$unidad_base', redondear2='$redondear2', maneja_inventario='$maneja_inventario'  WHERE cod_ingrediente = '$codigo'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    } 

  if($tipo_operacion == 3){
      ////SACAMOS TODOS LOS CHECKBOX DE LAS UNIDADES DE MEDIDA
      $instruccion3 = "SELECT cod_unidad_medida, nombre FROM unidad_medida";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_uni = $resultado['cod_unidad_medida'];
       $nom_uni = $resultado['nombre'];
       
       $v_cod_uni = $_REQUEST[$cod_uni];
       $v_depto_v = $_REQUEST[depto_.$cod_uni];

       if($v_cod_uni!=''){
         ////ELIMINAMOS LAS OPCION PARA INSERTARLA ACTUALIZADA
         $instruccion_del = "DELETE FROM ingrediente_unidad_entrega WHERE cod_ingrediente = $codigo AND cod_unidad_medida = $v_cod_uni"; 
         $consulta_del = mysql_query ($instruccion_del, $conexion); 
           
         ////INSERTAMOS LAS UNIDADES DE MEDIDA PARA EL INGREDIENTE
         $instruccion5 = "INSERT INTO ingrediente_unidad_entrega (cod_ingrediente, cod_unidad_medida, cod_departamento) 
                               VALUES ('$codigo','$v_cod_uni', '$v_depto_v')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 

         ////INSERTAMOS LAS UNIDADES DE MEDIDA PARA EL INGREDIENTE
         $instruccion6 = "INSERT INTO ingrediente_unidad_entrega_consulta (cod_ingrediente, cod_unidad_medida, cod_departamento) 
                               VALUES ('$codigo','$v_cod_uni', '$v_depto_v')"; 
         $consulta6 = mysql_query ($instruccion6, $conexion);          
         
        }else{           
          ////ELIMINAMOS LAS OPCIONES QUE NO ESTEN SELECCIONADAS
          $instruccion_del = "DELETE FROM ingrediente_unidad_entrega WHERE cod_ingrediente = $codigo AND cod_unidad_medida = $cod_uni"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);        
          }
       } 
    }   
                             
} 
 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>
