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

$login_usu = $_REQUEST['login']; 
$login_usu = trim($login_usu);

if($tipo_operacion == 1){
  $error="";
  if (empty($_REQUEST['nombre']))
    $error=$error . "El Nombre del usuario no puede estar vacio.<br>";   
  if (empty($_REQUEST['apellido']))
    $error=$error . "Los Apellidos del usuario no pueden estar vacios.<br>";
  if (empty($_REQUEST['cedula']))
    $error=$error . "La Cedula del usuario no puede estar vacia.<br>";    
  if (empty($_REQUEST['login']))
    $error=$error . "El Login del usuario no puede estar vacio.<br>"; 
    
   ////CONSULTAMOS QUE EL LOGIN NO ESTE EN USO
   $instruccion3 = "SELECT * FROM usuario WHERE login='$login_usu'";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
  
   $nfilas = mysql_num_rows ($consulta3);
   
   if($nfilas >0) 
    $error=$error . "El Login del usuario [$login_usu] ya se encuentra en el sistema<br>";   
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 
 
if($tipo_operacion == 2){
  $error="";
  if (empty($_REQUEST['nombre']))
    $error=$error . "El Nombre del usuario no puede estar vacio.<br>";   
  if (empty($_REQUEST['apellido']))
    $error=$error . "Los Apellidos del usuario no pueden estar vacios.<br>";
  if (empty($_REQUEST['cedula']))
    $error=$error . "La Cedula del usuario no puede estar vacia.<br>";    
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }    

if($tipo_operacion == 3){
 $error="";
  ////SACAMOS TODOS LOS CHECKBOX DE LAS OPCIONES
  $instruccion3 = "SELECT id_opcion, nombre FROM opcion";
  $consulta3 = mysql_query ($instruccion3, $conexion);  
  
  $nfilas = mysql_num_rows($consulta3);
  
  for($f=0;$f<$nfilas;$f++){
   $resultado = mysql_fetch_array ($consulta3);
   
   $id_opcion = $resultado['id_opcion'];
   $nom_opcion = $resultado['nombre'];
   
   $v_id_opcion = $_REQUEST[$id_opcion];
   $v_opc_v = $_REQUEST[opcvista_.$id_opcion];

    if($v_id_opcion!=''){
     ////SI SE SELECCIONA UNA OPCION SE VALIDA QUE SE SELELCCIONE EL TIPO DE VISTA PARA LA OPCION
     if (empty($_REQUEST[opcvista_.$id_opcion])) $error=$error . "Debe seleccionar un Tipo de Vista para la opcion $v_id_opcion.<br>"; 
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
$codigo    = strtoupper($_REQUEST[codigo0]);
$nombre    = strtoupper($_REQUEST[nombre]);
$apellido  = strtoupper($_REQUEST[apellido]);
$cedula    = $_REQUEST[cedula];  
$login_usu = $_REQUEST[login]; 

$clave = md5($login_usu); 
    
  if($tipo_operacion == 1){
     mysql_query("INSERT INTO usuario (nombre, apellidos, cedula, login, clave) VALUES ('$nombre','$apellido','$cedula','$login_usu','$clave')", $conexion);
    }  

  if($tipo_operacion == 2){
     $instruccion4 = "UPDATE usuario SET nombre='$nombre', apellidos='$apellido', cedula='$cedula' WHERE cod_usuario = '$codigo'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    } 

  if($tipo_operacion == 3){
      ////SACAMOS TODOS LOS CHECKBOX DE LAS OPCIONES
      $instruccion3 = "SELECT id_opcion, nombre FROM opcion";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $id_opcion = $resultado['id_opcion'];
       $nom_opcion = $resultado['nombre'];
       
       $v_id_opcion = $_REQUEST[$id_opcion];
       $v_opc_v = $_REQUEST[opcvista_.$id_opcion];

       if($v_id_opcion!=''){
         ////ELIMINAMOS LAS OPCION PARA INSERTARLA ACTUALIZADA
         $instruccion_del = "DELETE FROM usuario_opcion WHERE cod_usuario = $codigo AND id_opcion = $id_opcion"; 
         $consulta_del = mysql_query ($instruccion_del, $conexion); 
           
         ////INSERTAMOS LAS OPCIONES DEL USUARIO
         $instruccion5 = "INSERT INTO usuario_opcion (cod_usuario, id_opcion, cod_opcion_vista) values ('$codigo','$v_id_opcion','$v_opc_v')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS LAS OPCIONES QUE NO ESTEN SELECCIONADAS
          $instruccion_del = "DELETE FROM usuario_opcion WHERE cod_usuario = $codigo AND id_opcion = $id_opcion"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);        
          }
       } 
    }     

if($tipo_operacion == 4){
      ////BUSCAMOS EL LOGIN DEL USUARIO
      $instruccion3 = "SELECT login FROM usuario WHERE cod_usuario = $codigo";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $resultado = mysql_fetch_array ($consulta3);
       
      $login_usu = $resultado['login'];
      
      $clave = md5($login_usu); 
      
      $instruccion4 = "UPDATE usuario SET clave='$clave' WHERE cod_usuario = '$codigo'";
      $consulta4 = mysql_query ($instruccion4, $conexion);

      ?>
       <center><span class="Estilo1">LA CONTRASEÑA PARA EL USUARIO <?php echo"$login_usu"; ?> SE HA RESTABLECIDO</span></center><br>
       <center><span class="Estilo1">Ahora la contraseña es igual al Login <?php echo"$login_usu"; ?> debe cambiar la contraseña al ingresar...</span></center><br>        
      <?php  
      
  }  

  if($tipo_operacion == 5){
      ////SACAMOS TODOS LOS CHECKBOX DE LOS MUNICIPIOS
      $instruccion3 = "SELECT cod_municipio, nombre FROM municipio";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_municipio = $resultado['cod_municipio'];
       $nom_municipio = $resultado['nombre'];
       
       $v_cod_municipio = $_REQUEST[$cod_municipio];

       if($v_cod_municipio!=''){
         ////ELIMINAMOS LOS MUNICIPIOS PARA INSERTARLOS ACTUALIZADOS
         $instruccion_del = "DELETE FROM usuario_municipio WHERE cod_usuario = $codigo AND cod_municipio = $cod_municipio"; 
         $consulta_del = mysql_query ($instruccion_del, $conexion); 
           
         ////INSERTAMOS LOS MUNICIPIO DEL USUARIO
         $instruccion5 = "INSERT INTO usuario_municipio (cod_usuario, cod_municipio) values ('$codigo','$v_cod_municipio')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS LOS MUNICIPIOS QUE NO ESTAN SELECCIONADOS
          $instruccion_del = "DELETE FROM usuario_municipio WHERE cod_usuario = $codigo AND cod_municipio = $cod_municipio"; 
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
