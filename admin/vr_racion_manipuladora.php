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

if($tipo_operacion == 2){ 

 $cod_escuela = $_REQUEST[cod_escuela0];
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
   
   ////BUSCAMOS LAS MANIPULADORAS DE LA ESCUELA
    $instruccion_esc ="SELECT manipuladora.cod_manipuladora AS cod_manipuladora, manipuladora.nombre AS nom_manipuladora
                       FROM escuela_manipuladora 
                       INNER JOIN manipuladora ON escuela_manipuladora.cod_manipuladora = manipuladora.cod_manipuladora
                       WHERE escuela_manipuladora.cod_escuela = $cod_escuela";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){
      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_manipuladora = $row_esc['cod_manipuladora'];
      $nom_manipuladora = $row_esc['nom_manipuladora'];
      
      $conta = $e + 1; 
      
        for ($n=0; $n<$ndiasmes; $n++){
          $conta_m2 = $n + 1;
          
          ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
          if($conta_m2 < 10){
             $conta_m2 = "0".$conta_m2; 
            }                   
        
            $raciones = $_REQUEST[raciones_.$cod_manipuladora.$conta_m2]; 
            
            if(filter_var($raciones, FILTER_VALIDATE_INT) === false){  
              $error=$error . "La cantidad de raciones debe ser un valor entero Manipuladora: $nom_manipuladora Dia: $conta_m2<br>"; 
              } 
              
            if($raciones < 0){  
              $error=$error . "La cantidad de raciones debe ser un valor Mayor que cero Manipuladora: $nom_manipuladora Dia: $conta_m2<br>"; 
              }               
            }    
       } 

  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
   } 
  
 }   
 
if($tipo_operacion == 5){ 

  if(filter_var($_REQUEST['q_raciones'], FILTER_VALIDATE_INT) === false){  
    $error=$error . "La cantidad de raciones debe ser un valor entero <br>"; 
    } 
    
  if($_REQUEST['q_raciones']< 0){  
    $error=$error . "La cantidad de raciones debe ser un valor Mayor que cero<br>"; 
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
    
if($tipo_operacion <= 3){ 

 $cod_escuela = $_REQUEST[cod_escuela0];
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
    $instruccion_del = "DELETE FROM escuela_manipuladora_racion WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);               
       
   ////BUSCAMOS LAS MANIPULADORAS DE LA ESCUELA
    $instruccion_esc ="SELECT manipuladora.cod_manipuladora AS cod_manipuladora, manipuladora.nombre AS nom_manipuladora
                       FROM escuela_manipuladora 
                       INNER JOIN manipuladora ON escuela_manipuladora.cod_manipuladora = manipuladora.cod_manipuladora
                       WHERE escuela_manipuladora.cod_escuela = $cod_escuela";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){
      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_manipuladora = $row_esc['cod_manipuladora'];
      $nom_manipuladora = $row_esc['nom_manipuladora'];
      
      $conta = $e + 1; 
      
        for ($n=0; $n<$ndiasmes; $n++){
          $conta_m2 = $n + 1;
          
          ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
          if($conta_m2 < 10){
             $conta_m2 = "0".$conta_m2; 
            }                   
        
            $raciones = $_REQUEST[raciones_.$cod_manipuladora.$conta_m2]; 
                
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
                
                $instruccion_ins = "INSERT INTO escuela_manipuladora_racion (cod_escuela, cod_manipuladora, anio, mes, dia, raciones) 
                                    VALUES ('$cod_escuela', '$cod_manipuladora','$anio', '$mes', '$conta_m2', '$raciones')";
                $consulta_ins = mysql_query ($instruccion_ins, $conexion);  
            }  
       } 
       
  valor_pagar_manipuladora($conexion,$cod_escuela,$anio,$mes);
 }     

if($tipo_operacion == 5){ 

 $anio = $_REQUEST[anio0];
 $mes  = $_REQUEST[mes0];
 $cod_escuela = $_REQUEST[cod_escuela0];
 $raciones = $_REQUEST[q_raciones];
 
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
    $instruccion_del = "DELETE FROM escuela_racion WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);    
    
    $instruccion_del = "DELETE FROM escuela_manipuladora_racion WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes'";
    $consulta_del = mysql_query ($instruccion_del, $conexion);              
       
   ////BUSCAMOS LAS MANIPULADORAS DE LA ESCUELA
    $instruccion_esc ="SELECT manipuladora.cod_manipuladora AS cod_manipuladora, manipuladora.nombre AS nom_manipuladora
                       FROM escuela_manipuladora 
                       INNER JOIN manipuladora ON escuela_manipuladora.cod_manipuladora = manipuladora.cod_manipuladora
                       WHERE escuela_manipuladora.cod_escuela = $cod_escuela";
   
    $consulta_esc = mysql_query($instruccion_esc);
    error_consulta($consulta_esc,$instruccion_esc);
    
    $nesc = mysql_num_rows ($consulta_esc);
    
    for ($e=0; $e<$nesc; $e++){
      $row_esc = mysql_fetch_array($consulta_esc);
      
      $cod_manipuladora = $row_esc['cod_manipuladora'];
      $nom_manipuladora = $row_esc['nom_manipuladora'];
      
      $conta = $e + 1; 
      
        for ($n=0; $n<$ndiasmes; $n++){
          $conta_m2 = $n + 1;
          
          ////CONCATENAMOS EL 0 SI ES MENOR O IGUAL A 9
          if($conta_m2 < 10){
             $conta_m2 = "0".$conta_m2; 
            }                   

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
               
                ////DEFINIMOS SI LA FECHA ES SABADO O DOMINGO PARA INSERTAR LAS RACIONES EN 0 (CERO)
                $fecha_act = $anio."-".$mes."-".$conta_m2;
                
                //Devuelve 0 o 6  (0 es domingo 6 es sabado)
                $num_dia = date('w',strtotime($fecha_act));
                
                if(($num_dia >= 1) && ($num_dia <= 5)){ ////lo hacemos entre lunes y viernes de 1 a 5
                  
                  if($e == 0){////ESTO LO PONEMOS PARA Q LO HAGA SOLO UNA VEZ PARA Q NO INSERTE LAS RACIONES DE LA ESCUELA POR CADA MANIPULADORA
                    $instruccion_ins1 = "INSERT INTO escuela_racion (cod_escuela, anio, mes, dia, raciones) 
                                         VALUES ('$cod_escuela', '$anio', '$mes', '$conta_m2', '$raciones')";
                    $consulta_ins1 = mysql_query ($instruccion_ins1, $conexion);  
                    }                
                  
                  $instruccion_ins = "INSERT INTO escuela_manipuladora_racion (cod_escuela, cod_manipuladora, anio, mes, dia, raciones) 
                                      VALUES ('$cod_escuela', '$cod_manipuladora','$anio', '$mes', '$conta_m2', '$raciones')";
                  $consulta_ins = mysql_query ($instruccion_ins, $conexion); 
                 }  
            }  
       } 
       
  valor_pagar_escuela ($conexion,$anio,$mes);       
  valor_pagar_manipuladora($conexion,$cod_escuela,$anio,$mes); 
  documento_equivalente ($conexion,$anio,$mes);
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
