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

  if($tipo_operacion == 1){                      
      ////SACAMOS TODOS LOS CHECKBOX DE LOS MUNICIPIOS
      $instruccion3 = "SELECT cod_municipio, nombre FROM municipio WHERE cod_departamento = $codigo";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_municipio = $resultado['cod_municipio'];
       $nom_municipio = $resultado['nombre'];
       
       $v_cod_municipio = $_REQUEST[$cod_municipio];
       
       if($v_cod_municipio!=''){                 
         ////INSERTAMOS LOS MUNICIPIOS EXCLUIDOS
         $instruccion5 = "INSERT INTO excluido_municipio (cod_municipio) values ('$cod_municipio')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS EL MUNICIPIO SI NO ESTA SELECCIONADO
          $instruccion_del = "DELETE FROM excluido_municipio WHERE cod_municipio= $cod_municipio"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);        
          }
       }  
    }  

  if($tipo_operacion == 2){          
      ////SACAMOS TODOS LOS CHECKBOX DE LAS ESCUELAS
      $instruccion3 = "SELECT cod_escuela, nombre FROM escuela WHERE cod_municipio = $codigo";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_escuela = $resultado['cod_escuela'];
       $nom_escuela = $resultado['nombre'];
       
       $v_cod_escuela = $_REQUEST[$cod_escuela];
       
       if($v_cod_escuela!=''){
         ////INSERTAMOS LAS ESCUELAS EXCLUIDAS
         $instruccion5 = "INSERT INTO excluido_escuela (cod_escuela) values ('$cod_escuela')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS LA ESCUELA SI NO ESTA SELECCIONADA
          $instruccion_del = "DELETE FROM excluido_escuela WHERE cod_escuela = $cod_escuela"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);        
          }
       } 
    }

  if($tipo_operacion == 3){          
      ////SACAMOS TODOS LOS CHECKBOX DE LOS MENUS
      $instruccion3 = "SELECT cod_menu, nombre FROM menu";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_menu = $resultado['cod_menu'];
       $nom_menu = $resultado['nombre'];
       
       $v_cod_menu = $_REQUEST[$cod_menu];
       $v_cod_tipo_minuta = $_REQUEST[tipo_.$cod_menu];
       
       if($v_cod_menu!=''){
         ////INSERTAMOS LOS MENUS EXCLUIDOS PARA LA ESCUELA
         $instruccion5 = "INSERT INTO excluido_escuela_menu (cod_escuela, cod_menu, cod_tipo_minuta) values ('$codigo','$cod_menu', '$v_cod_tipo_minuta')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS EL MENU SI NO ESTA SELECCIONADO
          $instruccion_del = "DELETE FROM excluido_escuela_menu WHERE cod_escuela= $codigo AND cod_menu = $cod_menu"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);        
          }
       } 
    } 

  if($tipo_operacion == 4){          
      ////SACAMOS TODOS LOS CHECKBOX DE LOS MENUS
      $instruccion3 = "SELECT cod_menu, nombre FROM menu";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_menu = $resultado['cod_menu'];
       $nom_menu = $resultado['nombre'];
       
       $v_cod_menu = $_REQUEST[$cod_menu];
       
       if($v_cod_menu!=''){
         ////INSERTAMOS LOS MENUS EXCLUIDOS PARA LA ESCUELA
         $instruccion5 = "INSERT INTO excluido_municipio_menu (cod_municipio, cod_menu) values ('$codigo','$cod_menu')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
        }else{           
          ////ELIMINAMOS EL MENU SI NO ESTA SELECCIONADO
          $instruccion_del = "DELETE FROM excluido_municipio_menu WHERE cod_municipio = $codigo AND cod_menu = $cod_menu"; 
          $consulta_del = mysql_query ($instruccion_del, $conexion);        
          }
       } 
    }      
                               
} 

altaDatos();
   
?>
<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
</body>
</html>