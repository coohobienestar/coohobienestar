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
    $error=$error . "El Nombre del Municipio no puede estar vacio.<br>";   
  if (empty($_REQUEST['departamento']))
    $error=$error . "Debe seleccionar un Departamento para el Municipio.<br>";
  if (empty($_REQUEST['centroz']))
    $error=$error . "Debe seleccionar un Centro zonal para el Municipio.<br>";
       
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
$departamento = $_REQUEST[departamento];
$centroz = $_REQUEST[centroz];   
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO municipio (nombre, cod_departamento, cod_centro_zonal) VALUES ('$nombre','$departamento', '$centroz')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE municipio SET nombre='$nombre', cod_departamento='$departamento', cod_centro_zonal='$centroz' 
                       WHERE cod_municipio = '$codigo'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    } 

  if($tipo_operacion == 3){
     ////BUSCAMOS LAS MINUTAS ACTUALES DEL MUNICIPIO
          $instruccion_a = "SELECT DISTINCT minuta_escuela.cod_minuta AS cod_minuta, minuta.nombre AS nom_minuta 
                            FROM minuta_escuela 
                            INNER JOIN escuela ON minuta_escuela.cod_escuela = escuela.cod_escuela
                            INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                            INNER JOIN minuta ON minuta.cod_minuta = minuta_escuela.cod_minuta 
                            WHERE escuela.cod_municipio = $codigo
                            ORDER BY minuta_escuela.cod_minuta";
          $consulta_a = mysql_query ($instruccion_a, $conexion);
    
          $nfilas_a = mysql_num_rows ($consulta_a);
          
          for ($a=0; $a<$nfilas_a; $a++){  
            $row_a = mysql_fetch_array($consulta_a);
            
            $cod_minuta_actual = $row_a['cod_minuta'];
            $nom_minuta_actual = $row_a['nom_minuta'];
            
            $v_min_actual = $_REQUEST[minnueva_.$cod_minuta_actual];             

            
            if($v_min_actual != ''){
               $instruccion4 = "UPDATE minuta_escuela 
                                INNER JOIN escuela ON minuta_escuela.cod_escuela = escuela.cod_escuela
                                INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                                SET minuta_escuela.cod_minuta = $v_min_actual
                                WHERE escuela.cod_municipio = $codigo  AND minuta_escuela.cod_minuta = $cod_minuta_actual";
               $consulta4 = mysql_query ($instruccion4, $conexion);
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
