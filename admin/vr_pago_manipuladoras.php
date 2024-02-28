<?php   

ini_set('max_execution_time',0);

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

if($tipo_operacion == 1){
  $error="";       
 
  $anio = $_REQUEST['anio'];
  $mes = $_REQUEST['mes'];
  
  ////BUSCAMOS SI EL AÑO Y EL MES YA SE ENCUENTRAN REGISTRADOS EN EL SISTEMA
  $instruccion2 ="SELECT anio, mes FROM pago_manipuladoras WHERE anio = '$anio' AND mes = '$mes'";
 
  $consulta2 = mysql_query($instruccion2);
  error_consulta($consulta2,$instruccion2);

  $nfilas = mysql_num_rows ($consulta2);
  
  if($nfilas > 0)
  $error=$error . "El periodo registrado ya se encuentra registrado en el sistema<br>";
   
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 

if($tipo_operacion == 2){ 

 $anio = $_REQUEST[anio0];
 $mes = $_REQUEST[mes0];

  ////DEFINIMOS SI ES AÑO BISIESTO PARA VER SI FEBRERO TIENE 28 O 29 DIAS
  if((($anio % 4 == 0) && ($anio % 100 != 0)) || (($anio % 100 == 0) && ($anio % 400 == 0))){
     $bisiesto=1;
     }else{
       $bisiesto=0;
       } 

    ////DEFINIMOS EL NUEMRO DE DIAS QUE TIENE EL MES
    if($mes=='01'){ $ndiasmes=31; $mes_nombre='ENERO';}
    if($mes=='02'){ if($bisiesto==1){$ndiasmes=29;}else{$ndiasmes=28;} $mes_nombre='FEBRERO';}
    if($mes=='03'){ $ndiasmes=31; $mes_nombre='MARZO';}
    if($mes=='04'){ $ndiasmes=30; $mes_nombre='ABRIL';}
    if($mes=='05'){ $ndiasmes=31; $mes_nombre='MAYO';}
    if($mes=='06'){ $ndiasmes=30; $mes_nombre='JUNIO';}
    if($mes=='07'){ $ndiasmes=31; $mes_nombre='JULIO';}
    if($mes=='08'){ $ndiasmes=31; $mes_nombre='AGOSTO';}
    if($mes=='09'){ $ndiasmes=30; $mes_nombre='SEPTIEMBRE';}
    if($mes=='10'){ $ndiasmes=31; $mes_nombre='OCTUBRE';}
    if($mes=='11'){ $ndiasmes=30; $mes_nombre='NOVIEMBRE';}
    if($mes=='12'){ $ndiasmes=31; $mes_nombre='DICIEMBRE';}  
   
    ////BUSCAMOS LAS ESCUELAS DE RISARALDA
    $instruccion_esc ="SELECT escuela.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela 
                       FROM escuela
                       WHERE escuela.cod_escuela <> 1 AND escuela.cod_escuela <> 2868 AND escuela.cod_centro_acopio = 1";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){

      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_escuela = $row_esc['cod_escuela'];
      $nom_escuela = $row_esc['nom_escuela'];
           
           for($n=0; $n<$ndiasmes; $n++){
               $conta_m2 = $n + 1;
                
             ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
             if($conta_m2 < 10){
                $conta_m2 = "0".$conta_m2; 
               }  
            
                $raciones = $_REQUEST[raciones_.$cod_escuela.$conta_m2]; 
                
                if(filter_var($raciones, FILTER_VALIDATE_INT) === false){  
                  $error=$error . "La cantidad de raciones debe ser un valor entero Escuela: $nom_escuela Dia: $conta_m2<br>"; 
                  } 

                if($raciones < 0){  
                  $error=$error . "La cantidad de raciones debe ser un valor Mayor que cero Escuela: $nom_escuela Dia: $conta_m2<br>"; 
                  }                     
                  
            }    
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
    
if($tipo_operacion == 1){
   $anio = $_REQUEST[anio];
   $mes  = $_REQUEST[mes];
   
   mysql_query("INSERT INTO pago_manipuladoras (anio, mes) VALUES ('$anio', '$mes')", $conexion);
  } 
    
if($tipo_operacion == 2){ 

 $anio = $_REQUEST[anio0];
 $mes = $_REQUEST[mes0];

  ////DEFINIMOS SI ES AÑO BISIESTO PARA VER SI FEBRERO TIENE 28 O 29 DIAS
  if((($anio % 4 == 0) && ($anio % 100 != 0)) || (($anio % 100 == 0) && ($anio % 400 == 0))){
     $bisiesto=1;
     }else{
       $bisiesto=0;
       } 

    ////DEFINIMOS EL NUEMRO DE DIAS QUE TIENE EL MES
    if($mes=='01'){ $ndiasmes=31; $mes_nombre='ENERO';}
    if($mes=='02'){ if($bisiesto==1){$ndiasmes=29;}else{$ndiasmes=28;} $mes_nombre='FEBRERO';}
    if($mes=='03'){ $ndiasmes=31; $mes_nombre='MARZO';}
    if($mes=='04'){ $ndiasmes=30; $mes_nombre='ABRIL';}
    if($mes=='05'){ $ndiasmes=31; $mes_nombre='MAYO';}
    if($mes=='06'){ $ndiasmes=30; $mes_nombre='JUNIO';}
    if($mes=='07'){ $ndiasmes=31; $mes_nombre='JULIO';}
    if($mes=='08'){ $ndiasmes=31; $mes_nombre='AGOSTO';}
    if($mes=='09'){ $ndiasmes=30; $mes_nombre='SEPTIEMBRE';}
    if($mes=='10'){ $ndiasmes=31; $mes_nombre='OCTUBRE';}
    if($mes=='11'){ $ndiasmes=30; $mes_nombre='NOVIEMBRE';}
    if($mes=='12'){ $ndiasmes=31; $mes_nombre='DICIEMBRE';}  

    ////BORRAMOS LOS DATOS Q EXISTAN PARA REEMPLZAR TODO
    $instruccion_del = "DELETE FROM escuela_racion WHERE anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);               
       
    ////BUSCAMOS LAS ESCUELAS DE RISARALDA
    $instruccion_esc ="SELECT escuela.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela 
                       FROM escuela
                       WHERE escuela.cod_escuela <> 1 AND escuela.cod_centro_acopio = 1";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){

      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_escuela = $row_esc['cod_escuela'];
           
           for($n=0; $n<$ndiasmes; $n++){
               $conta_m2 = $n + 1;
                
             ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
             if($conta_m2 < 10){
                $conta_m2 = "0".$conta_m2; 
               }  
            
                $raciones = $_REQUEST[raciones_.$cod_escuela.$conta_m2]; 
                
                ////SI LAS RACIONES CON MENORES A 60 SE SUBEN A 60
                ////BUSCAMOS EL # DE RACIONES MINIMAS
                $instruccion_racion_min ="SELECT valor FROM parametro WHERE nombre='raciones_minimas'"; 

                $consulta_racion_min = mysql_query($instruccion_racion_min);
                error_consulta($consulta_racion_min,$instruccion_racion_min);
                $row_racion_min = mysql_fetch_array($consulta_racion_min);
              
                $raciones_minimas = $row_racion_min['valor'];  
                
                if(($raciones > 0)  && ($raciones < $raciones_minimas)){
                   $raciones = $raciones_minimas; 
                  }                
                
                $instruccion_ins = "INSERT INTO escuela_racion (cod_escuela, anio, mes, dia, raciones) 
                                    VALUES ('$cod_escuela', '$anio', '$mes', '$conta_m2', '$raciones')";
                $consulta_ins = mysql_query ($instruccion_ins, $conexion);  
            }  
       } 
       
  valor_pagar_escuela ($conexion,$anio,$mes);   
 }     
                  
} 
 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>
