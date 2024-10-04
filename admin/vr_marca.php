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
$tipo_operacion = $_REQUEST['tipo_operacion'];
$cod_programacion  = $_REQUEST['programacion0'];
$cod_ingrediente  = $_REQUEST['ingrediente0']; 
$nombre_marca = $_REQUEST['nombre_marca'];

if($tipo_operacion == 1){
  $error="";
  if(empty($_REQUEST['nombre_marca'])){
    $error=$error . "El campo no puede estar vacio.<br>";   
    echo($nombre_marca);
    $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                      FROM observacion
                      WHERE cod_programacion = $_REQUEST[programacion0] AND cod_tipo_minuta = '$_REQUEST[tipo]' AND cod_municipio = '0' AND cod_escuela = '0'";     
    $consulta_ca = mysql_query($instruccion_ca);
    error_consulta($consulta_ca,$instruccion_ca);
    $row_ca = mysql_fetch_array($consulta_ca);  
    
    $cuenta = $row_ca['cuenta'];
    
    if($cuenta > 0){
       $error=$error . "Ya existe tiene una Observaci�n con esa informaci�n.<br>"; 
      } 
   
    if ($error!=""){
      echo "<center><span class=\"Estilo1\">$error</span></center><br>";
      echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
      die();
    }
  }
} 

if($tipo_operacion == 2){
  $error="";
  if (empty($_REQUEST['municipio']))
    $error=$error . "Debe selecionar un Municipio para la observaci�n.<br>";  
     
  if (empty($_REQUEST['observacion']))
    $error=$error . "La Observaci�n no puede estar vacia.<br>";  
    
 if($_REQUEST['municipio'] != '' && $_REQUEST['tipo'] != ''){ 
    $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                      FROM calculo_redondeado_escuela
                      WHERE calculo_redondeado_escuela.cod_municipio = $_REQUEST[municipio] AND calculo_redondeado_escuela.cod_tipo_minuta = $_REQUEST[tipo] 
                        AND calculo_redondeado_escuela.cod_programacion = $_REQUEST[programacion0]";     
    $consulta_ca = mysql_query($instruccion_ca);
    error_consulta($consulta_ca,$instruccion_ca);
    $row_ca = mysql_fetch_array($consulta_ca);  
    
    $cuenta = $row_ca['cuenta'];
    
    if($cuenta == 0){
       $error=$error . "No se encontro el tipo de Minuta para el Municipio.<br>"; 
      } 
   } 
   
  if($_REQUEST['municipio'] != ''){ 
    $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                      FROM observacion
                      WHERE cod_programacion = $_REQUEST[programacion0] AND cod_tipo_minuta = '$_REQUEST[tipo]' AND cod_municipio = $_REQUEST[municipio] 
                        AND cod_escuela = '0'";     
    $consulta_ca = mysql_query($instruccion_ca);
    error_consulta($consulta_ca,$instruccion_ca);
    $row_ca = mysql_fetch_array($consulta_ca);  
    
    $cuenta = $row_ca['cuenta'];
    
    if($cuenta > 0){
       $error=$error . "Ya existe tiene una Observaci�n con esa informaci�n.<br>"; 
      } 
   } 
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }

if($tipo_operacion == 3){
  $error="";
  if (empty($_REQUEST['escuela']))
    $error=$error . "Debe selecionar una Escuela para la observaci�n.<br>";  
     
  if (empty($_REQUEST['observacion']))
    $error=$error . "La Observaci�n no puede estar vacia.<br>";   
    
 if($_REQUEST['escuela'] != '' && $_REQUEST['tipo'] != ''){ 
    $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                      FROM calculo_redondeado_escuela
                      WHERE calculo_redondeado_escuela.cod_escuela = $_REQUEST[escuela] AND calculo_redondeado_escuela.cod_tipo_minuta = $_REQUEST[tipo] 
                        AND calculo_redondeado_escuela.cod_programacion = $_REQUEST[programacion0]";     
    $consulta_ca = mysql_query($instruccion_ca);
    error_consulta($consulta_ca,$instruccion_ca);
    $row_ca = mysql_fetch_array($consulta_ca);  
    
    $cuenta = $row_ca['cuenta'];
    
    if($cuenta == 0){
       $error=$error . "No se encontro el tipo de Minuta para la Escuela.<br>"; 
      } 
   }
   
  if($_REQUEST['escuela'] != ''){ 
    $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                      FROM observacion
                      WHERE cod_programacion = $_REQUEST[programacion0] AND cod_tipo_minuta = '$_REQUEST[tipo]' AND cod_municipio = '0' 
                        AND cod_escuela = $_REQUEST[escuela]";     
    $consulta_ca = mysql_query($instruccion_ca);
    error_consulta($consulta_ca,$instruccion_ca);
    $row_ca = mysql_fetch_array($consulta_ca);  
    
    $cuenta = $row_ca['cuenta'];
    
    if($cuenta > 0){
       $error=$error . "Ya existe tiene una Observaci�n con esa informaci�n.<br>"; 
      } 
   }        
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }  

if($tipo_operacion == 4){
  $error="";

  if ($_REQUEST['municipio0'] != '0'){
    if (empty($_REQUEST['municipio']))
    $error=$error . "Debe selecionar un Municipio para la observaci�n.<br>";

     if($_REQUEST['municipio'] != '' && $_REQUEST['tipo'] != ''){ 
        $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                          FROM calculo_redondeado_escuela
                          WHERE calculo_redondeado_escuela.cod_municipio = $_REQUEST[municipio] AND calculo_redondeado_escuela.cod_tipo_minuta = $_REQUEST[tipo] 
                            AND calculo_redondeado_escuela.cod_programacion = $_REQUEST[programacion0]";     
        $consulta_ca = mysql_query($instruccion_ca);
        error_consulta($consulta_ca,$instruccion_ca);
        $row_ca = mysql_fetch_array($consulta_ca);  
        
        $cuenta = $row_ca['cuenta'];
        
        if($cuenta == 0){
           $error=$error . "No se encontro el tipo de Minuta para el Municipio.<br>"; 
          } 
       } 
       
      if($_REQUEST['municipio'] != ''){ 
        $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                          FROM observacion
                          WHERE cod_programacion = $_REQUEST[programacion0] AND cod_tipo_minuta = '$_REQUEST[tipo]' AND cod_municipio = $_REQUEST[municipio] 
                            AND cod_escuela = '0' AND cod_observacion <> $_REQUEST[codigo0]";     
        $consulta_ca = mysql_query($instruccion_ca);
        error_consulta($consulta_ca,$instruccion_ca);
        $row_ca = mysql_fetch_array($consulta_ca);  
        
        $cuenta = $row_ca['cuenta'];
        
        if($cuenta > 0){
           $error=$error . "Ya existe tiene una Observaci�n con esa informaci�n.<br>"; 
          } 
       }    
   }
   
  if ($_REQUEST['escuela0'] != '0'){
    if (empty($_REQUEST['escuela']))
    $error=$error . "Debe selecionar una Escuela para la observaci�n.<br>";
   
     if($_REQUEST['escuela'] != '' && $_REQUEST['tipo'] != ''){ 
        $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                          FROM calculo_redondeado_escuela
                          WHERE calculo_redondeado_escuela.cod_escuela = $_REQUEST[escuela] AND calculo_redondeado_escuela.cod_tipo_minuta = $_REQUEST[tipo] 
                            AND calculo_redondeado_escuela.cod_programacion = $_REQUEST[programacion0]";     
        $consulta_ca = mysql_query($instruccion_ca);
        error_consulta($consulta_ca,$instruccion_ca);
        $row_ca = mysql_fetch_array($consulta_ca);  
        
        $cuenta = $row_ca['cuenta'];
        
        if($cuenta == 0){
           $error=$error . "No se encontro el tipo de Minuta para la Escuela.<br>"; 
          } 
       }  
      
      if($_REQUEST['escuela'] != ''){ 
        $instruccion_ca ="SELECT COUNT(cod_programacion) AS cuenta 
                          FROM observacion
                          WHERE cod_programacion = $_REQUEST[programacion0] AND cod_tipo_minuta = '$_REQUEST[tipo]' AND cod_municipio = '0' 
                            AND cod_escuela = $_REQUEST[escuela] AND cod_observacion <> $_REQUEST[codigo0]";     
        $consulta_ca = mysql_query($instruccion_ca);
        error_consulta($consulta_ca,$instruccion_ca);
        $row_ca = mysql_fetch_array($consulta_ca);  
        
        $cuenta = $row_ca['cuenta'];
        
        if($cuenta > 0){
           $error=$error . "Ya existe tiene una Observaci�n con esa informaci�n.<br>"; 
          } 
       }  
         
   }    
     
  if (empty($_REQUEST['observacion']))
    $error=$error . "La Observaci�n no puede estar vacia.<br>";   
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }  
 
if($tipo_operacion == 6){
  $error="";
  if (empty($_REQUEST['programacion']))
    $error=$error . "Debe Seleccionar una Programaci�n Destino.<br>";
    
  if ($_REQUEST[programacion] == $cod_programacion)
    $error=$error . "La programaci�n destino debe ser Diferente a la Programaci�n origen.<br>";     

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
$cod_programacion  = $_REQUEST[programacion0]; 
$municipio = $_REQUEST[municipio0]; 
$escuela = $_REQUEST[escuela0];
$cod_ingrediente  = $_REQUEST[ingrediente0]; 
$nombre_marca = $_REQUEST[nombre_marca];

$cod_municipio = $_REQUEST[municipio]; 
$cod_escuela = $_REQUEST[escuela];  
$cod_tipo_minuta = $_REQUEST[tipo];  
$formato = $_REQUEST[formato];  
$observacion = $_REQUEST[observacion];

if($formato == 1){
  $obs1 = $observacion;
  $obs2 = "";
 }

//  if($formato == 2){
//   $obs1 = "";
//   $obs2 = $observacion;
//  }

//   if($formato == 3){
//   $obs1 = $observacion;
//   $obs2 = $observacion;
//  }
   

strtoupper($_REQUEST[nombre]);
$departamento = $_REQUEST[departamento];   
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO marcas (cod_programacion, cod_ingrediente_programado, nombre_marca)
                                 VALUES ('$cod_programacion','$cod_ingrediente','$nombre_marca')", $conexion);
    }  

  if($tipo_operacion == 2){
     mysql_query("INSERT INTO observacion (cod_programacion, cod_municipio, cod_escuela, cod_tipo_minuta, observacion_lista_entrega, observacion_control_es)
                                 VALUES ('$cod_programacion','$cod_municipio','$cod_escuela','$cod_tipo_minuta','$obs1','$obs2')", $conexion);  
    }  
    
  if($tipo_operacion == 3){
     mysql_query("INSERT INTO observacion (cod_programacion, cod_municipio, cod_escuela, cod_tipo_minuta, observacion_lista_entrega, observacion_control_es)
                                 VALUES ('$cod_programacion','$cod_municipio','$cod_escuela','$cod_tipo_minuta','$obs1','$obs2')", $conexion);  
    }                              

  if($tipo_operacion == 4){
     $instruccion4 = "UPDATE observacion SET cod_municipio='$cod_municipio', cod_escuela='$cod_escuela', cod_tipo_minuta='$cod_tipo_minuta', 
                                       observacion_lista_entrega='$obs1',observacion_control_es='$obs2' WHERE cod_observacion=$num_observacion"; 
     $consulta4 = mysql_query ($instruccion4, $conexion);                                  
    }

  if($tipo_operacion == 5){
     $instruccion4 = "DELETE FROM marcas WHERE cod_programacion = $cod_programacion and cod_ingrediente_programado=$cod_ingrediente"; 
     $consulta4 = mysql_query ($instruccion4, $conexion);                                  
    }     
    
  if($tipo_operacion == 6){   
     $cod_prog_destino = $_REQUEST['programacion']; 
  
     ////BUSCAMOS LAS OBSERVACIONES DE LA PROGRAMACION ORIGEN
     $instruccion_0 ="SELECT  cod_municipio, cod_escuela, cod_tipo_minuta, observacion_lista_entrega, observacion_control_es
                      FROM observacion
                      WHERE cod_programacion = $cod_programacion
                      ORDER BY cod_observacion";

      $consulta_0 = mysql_query($instruccion_0);
      error_consulta($consulta_0,$instruccion_0);
      $nfilas_0 = mysql_num_rows ($consulta_0);
      
      for ($a=0; $a<$nfilas_0; $a++){
       $row_0 = mysql_fetch_array($consulta_0);
      
       $cod_municipio = $row_0['cod_municipio'];
       $cod_escuela = $row_0['cod_escuela'];
       $cod_tipo_minuta = $row_0['cod_tipo_minuta'];
       $observacion_lista_entrega = $row_0['observacion_lista_entrega'];
       $observacion_control_es = $row_0['observacion_control_es'];

     mysql_query("INSERT INTO observacion (cod_programacion, cod_municipio, cod_escuela, cod_tipo_minuta, observacion_lista_entrega, observacion_control_es)
                                   VALUES ('$cod_prog_destino','$cod_municipio','$cod_escuela','$cod_tipo_minuta','$observacion_lista_entrega','$observacion_control_es')", $conexion);  
      }

    } 
    if($tipo_operacion == 7){
      mysql_query("INSERT INTO observacion (cod_programacion, cod_municipio, cod_escuela, cod_tipo_minuta, intercambio, observacion_lista_entrega, observacion_control_es)
                                  VALUES ('$cod_programacion','$cod_municipio','$cod_escuela','$cod_tipo_minuta','1','$obs1','$obs2')", $conexion);
     }      
} 
 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>
