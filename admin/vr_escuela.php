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
    $error=$error . "El Nombre de la escuela no puede ser cadena vacía.<br>";   
  if (empty($_REQUEST['c_acopio']))
    $error=$error . "Debe seleccionar un Centro de Acopio.<br>";
  if (empty($_REQUEST['municipio']))
    $error=$error . "Debe seleccionar un Municipio.<br>";  

  if($_REQUEST['escuela'] != ''){
    ////VALIDAMOS Q LA ESCUELA A AGRUPAR Y LA Q SE ESTA EDITANDO SEAN DIFERENTES
    if($_REQUEST['escuela'] == $_REQUEST['codigo_esc'])
      $error=$error . "La escuela para agrupar debe ser diferente a la escuela que esta Editando<br>";
    
    ////VALIDAMOS Q LA ESCUELA A AGRUPAR NO ESTE AGRUPADA EN OTRA ESCUELA
    $instruccion_esc_agr = "SELECT cod_escuela FROM escuela WHERE cod_escuela_agrupada = $_REQUEST[escuela] 
                               AND cod_escuela <> $_REQUEST[codigo_esc]";
    $consulta_esc_agr = mysql_query ($instruccion_esc_agr, $conexion);  
    $row_esc_agr = mysql_fetch_array ($consulta_esc_agr);
    
    $cuenta = mysql_num_rows ($consulta_esc_agr);

    $cod_escuela = $row_esc_agr['cod_escuela']; 
    
    if($cuenta > 0){
       $error=$error . "La escuela seleccionada para agrupar ya se encuentra agrupada con la escuela $cod_escuela<br>";   
     }      
   
    ////VALIDAMOS Q LA ESCUELA Q SE SELECCIONO NO ESTE YA CON UNA ESCUELA AGRUPADA
    $instruccion_esc_agr = "SELECT cod_escuela_agrupada FROM escuela WHERE cod_escuela = $_REQUEST[escuela]";
    $consulta_esc_agr = mysql_query ($instruccion_esc_agr, $conexion);  
    $row_esc_agr = mysql_fetch_array ($consulta_esc_agr);

    $cod_escuela_agrupada = $row_esc_agr['cod_escuela_agrupada'];
    
    if($cod_escuela_agrupada > 0){
       $error=$error . "La escuela seleccionada Tiene asociada la escuela $cod_escuela_agrupada<br>";   
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
  ////SACAMOS TODOS LOS CHECKBOX DE LAS MINUTAS
  $instruccion3 = "SELECT cod_minuta, nombre FROM minuta";
  $consulta3 = mysql_query ($instruccion3, $conexion);  
  
  $nfilas = mysql_num_rows($consulta3);
  
  for($f=0;$f<$nfilas;$f++){
   $resultado = mysql_fetch_array ($consulta3);
   
   $cod_minuta = $resultado['cod_minuta'];
   $nom_minuta = $resultado['nombre'];
   
   $v_cod_minuta = $_REQUEST[$cod_minuta];
   $v_rango = $_REQUEST[rango_.$cod_minuta];
   $v_cupos = $_REQUEST[cupos_.$cod_minuta];
   $v_jornada = $_REQUEST[jornada_.$cod_minuta];

    if($v_cod_minuta!=''){
     ////SI SE SELECCIONA UNA MINUTA SE VALIDA QUE TRAIGA EL RANGO DE EDAD LOS CUPOS Y LA JORNADA
     if (empty($_REQUEST[rango_.$cod_minuta])) $error=$error . "Debe seleccionar un Rango de edad para la minuta $v_cod_minuta.<br>"; 
     if(filter_var($_REQUEST[cupos_.$cod_minuta], FILTER_VALIDATE_INT) === false) $error=$error . "El Numero de Cupos para la Minuta: $v_cod_minuta debe ser un valor Entero<br>";
     if ($_REQUEST[cupos_.$cod_minuta] <= 0) $error=$error . "El Numero de Cupos para la Minuta: $v_cod_minuta debe ser Mayor que Cero<br>"; 
     if (empty($_REQUEST[jornada_.$cod_minuta])) $error=$error . "Debe seleccionar una Jornada para la minuta $v_cod_minuta.<br>"; 
     } 
   }
   
  if($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();  
  }
 } 
} 
  
function altaDatos(){
$conexion=Conectarse(); 

$tipo_operacion = $_REQUEST[tipo_operacion];
$cod_escuela = $_REQUEST[codigo_esc]; 
$nombre = strtoupper($_REQUEST[nombre]);
$c_acopio = $_REQUEST[c_acopio];  
$municipio = $_REQUEST[municipio];
$cod_escuela_agr = $_REQUEST[escuela]; 

 if ($cod_escuela_agr == '') $cod_escuela_agr = 0;
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO escuela (nombre, cod_centro_acopio, cod_municipio, cod_escuela_agrupada) 
                  VALUES ('$nombre','$c_acopio','$municipio','$cod_escuela_agr')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE escuela SET nombre='$nombre', cod_centro_acopio='$c_acopio', cod_municipio='$municipio',
                             cod_escuela_agrupada='$cod_escuela_agr' 
                       WHERE cod_escuela = '$cod_escuela'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    } 

  if($tipo_operacion == 3){
        ////SACAMOS TODOS LOS CHECKBOX DE LAS MINUTAS
      $instruccion3 = "SELECT cod_minuta, nombre FROM minuta";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_minuta = $resultado['cod_minuta'];
       $nom_minuta = $resultado['nombre'];
       
       $v_cod_minuta = $_REQUEST[$cod_minuta];
       $v_rango = $_REQUEST[rango_.$cod_minuta];
       $v_cupos = $_REQUEST[cupos_.$cod_minuta];
       $v_jornada = $_REQUEST[jornada_.$cod_minuta];

       if($v_cod_minuta!=''){
          ////ELIMINAMOS LAS MINUTAS PARA INSERTARLAS ACTUALIZADAS
          $instruccion_del = "DELETE FROM minuta_escuela WHERE cod_escuela = $cod_escuela AND cod_minuta = $cod_minuta"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion); 
          
         ////INSERTAMOS LAS MINUTAS DE LA ESCUELA
         $instruccion5 = "INSERT INTO minuta_escuela (cod_minuta, cod_escuela, cod_rango_edad, cupos, cod_jornada) values ('$v_cod_minuta','$cod_escuela','$v_rango','$v_cupos','$v_jornada')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS LAS MINUTAS QUE NO ESTEN SELECCIONADAS
          $instruccion_del = "DELETE FROM minuta_escuela WHERE cod_escuela = $cod_escuela AND cod_minuta = $cod_minuta"; 
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
