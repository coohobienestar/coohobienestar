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
  
function altaDatos(){
$conexion=Conectarse(); 

$tipo_operacion = $_REQUEST[tipo_operacion];
$codigo = $_REQUEST[codigo]; 
$destino = $_REQUEST[destino]; 
$elemento = $_REQUEST[elemento]; 

  if($destino == 1){   
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_escuela.cod_escuela = $codigo";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }                      
      
        ////ELIMINAMOS LA (S) ESCUELA (S) DE LA EXCLUSION
        $instruccion_del = "DELETE FROM excluido_escuela $condicion"; 
        $consulta_del = mysql_query ($instruccion_del, $conexion);        
    }
    
  if($destino == 2){   
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_escuela_menu.cod_escuela = $codigo AND excluido_escuela_menu.cod_menu = $elemento ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }  
       
        ////ELIMINAMOS LOS  MENUS - ESCUELA DE LA EXCLUSION
        $instruccion_del = "DELETE FROM excluido_escuela_menu $condicion"; 
        $consulta_del = mysql_query ($instruccion_del, $conexion);     
 
    } 

  if($destino == 3){   
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_municipio.cod_municipio = $codigo ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }                          
      
        ////ELIMINAMOS LOS MUNICIPIOS DE LA EXCLUSION
        $instruccion_del = "DELETE FROM excluido_municipio $condicion"; 
        $consulta_del = mysql_query ($instruccion_del, $conexion);        
    }     
 
  if($destino == 4){   
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_municipio_menu.cod_municipio = $codigo AND excluido_municipio_menu.cod_menu = $elemento ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }                          
      
        ////ELIMINAMOS LOS MENU - MUNICIPIOS DE LA EXCLUSION
        $instruccion_del = "DELETE FROM excluido_municipio_menu $condicion"; 
        $consulta_del = mysql_query ($instruccion_del, $conexion);        
    }         
  
  if($tipo_operacion == 7){ 
     ////ELIMINAMOS LA (S) ESCUELA (S) DE LA EXCLUSION
     $instruccion_del = "DELETE FROM excluido_escuela $condicion"; 
     $consulta_del = mysql_query ($instruccion_del, $conexion);   
     
     ////ELIMINAMOS LOS  MENUS - ESCUELA DE LA EXCLUSION
     $instruccion_del = "DELETE FROM excluido_escuela_menu $condicion"; 
     $consulta_del = mysql_query ($instruccion_del, $conexion);  
    
     ////ELIMINAMOS LOS MUNICIPIOS DE LA EXCLUSION
     $instruccion_del = "DELETE FROM excluido_municipio $condicion"; 
     $consulta_del = mysql_query ($instruccion_del, $conexion); 
    
     ////ELIMINAMOS LOS MENU - MUNICIPIOS DE LA EXCLUSION
     $instruccion_del = "DELETE FROM excluido_municipio_menu $condicion"; 
     $consulta_del = mysql_query ($instruccion_del, $conexion);        
    }                  
} 

altaDatos();
   
?>
<center><strong><span class="Estilo1">Se actualizaron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>
