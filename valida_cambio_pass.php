<?php
session_start();
include("conexion/conectarbd.php");

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar.");
?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
<body>
<?php
function validar_clave($clave,&$error_clave){
   if(strlen($clave) < 8){
      $error_clave = $error_clave. "La clave debe tener por lo menos 8 caracteres <br>";
    }
   if(strlen($clave) > 16){
      $error_clave = $error_clave. "La clave no puede tener más de 16 caracteres <br>";
    }
   if (!preg_match('`[a-z]`',$clave)){
      $error_clave = $error_clave. "La clave debe tener al menos una letra minúscula <br>";
    }
   if (!preg_match('`[A-Z]`',$clave)){
      $error_clave = $error_clave. "La clave debe tener al menos una letra mayúscula <br>";
   }
   if (!preg_match('`[0-9]`',$clave)){
      $error_clave = $error_clave. "La clave debe tener al menos un caracter numérico <br>";
   }
   
   if($error_clave != ""){
      return false;
     }else{
       $error_clave = "";
       return true;
       } 
}

function validarDatosIngresados(){
  $error="";
   if (empty($_REQUEST['con_anterior']))
    $error=$error . "La contraseña anterior no puede ser vacía.<br>";
     
  if (empty($_REQUEST['con_nueva']))
    $error=$error . "La contraseña nueva no puede ser vacía.<br>"; 
     
  if ($_REQUEST['con_anterior']==$_REQUEST['con_nueva'])
    $error=$error . "La clave Nueva y Anterior deben ser diferentes<br>";  
    
  if (empty($_REQUEST['con_nueva_rep']))
    $error=$error . "La repetición de la clave nueva no puede ser vacía.<br>";
    
  if ($_REQUEST['con_nueva']!=$_REQUEST['con_nueva_rep'])
    $error=$error . "Las claves deben ser iguales<br>";
    
  if($_REQUEST['con_nueva'] == $_REQUEST['con_nueva_rep']){
     
     $error_encontrado = "";
     
     if(validar_clave($_REQUEST['con_nueva'], $error_encontrado)){
       }else{
        $error=$error . "PASSWORD NO VÁLIDO: " . $error_encontrado;
        }
    }  

  if ($error!="")
  {
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
}
  
function altaDatos(){
$usuario = trim($_SESSION['cod_usuario']);
$conexion=Conectarse(); 
  
     $contra_act_digitada = $_REQUEST['con_anterior'];
    
    ////CONSULTAMOS LA CONTRASEÑA ANTERIOR
      $instruccion3 = "SELECT * FROM usuario WHERE cod_usuario='$usuario'";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $contra_act = $row3['clave'];
      
      $contra_act = trim($contra_act);
      
      $contra_act_digitada = md5($contra_act_digitada);   
      
      if($contra_act==$contra_act_digitada){
        ///convertimos a md5
        $clave = md5($_REQUEST['con_nueva']);
        
        $instruccion4 = "UPDATE usuario SET clave ='$clave' WHERE cod_usuario='$usuario'";
        $consulta4 = mysql_query ($instruccion4, $conexion);  
        
        }else{
           ?>
             <center><span class="Estilo1">LA CONTRASEÑA DIGITADA NO CORRESPONDE A SU CONTRASEÑA ACTUAL<br></span></center><br>
             <center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>
           <?php 
           die();          
          }
} 
 
validarDatosIngresados();
altaDatos();

session_start();
session_destroy();
?>

<html>
<META HTTP-EQUIV="Refresh" CONTENT="3;URL=index.php">
<head>
</head>
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
<body>
<center><span class="Estilo1">Sesion Finalizada</span></center><br>
<br><center><strong><span class='Estilo1'>Se actualizo su Contraseña Correctamente</span></center></strong>
</body>
</html>
