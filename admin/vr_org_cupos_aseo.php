<?php
ini_set('max_execution_time',0);
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">

<?php
include("../conexion/conectarbd.php");
function validarDatosIngresados(){
$conexion=Conectarse(); 
$tipo_operacion = $_REQUEST[tipo_operacion];

$login_usu = $_REQUEST['login']; 
$login_usu = trim($login_usu);

  
} 
  
function altaDatos(){
$conexion=Conectarse(); 

$tipo_operacion = $_REQUEST[tipo_operacion];
$codigo    = strtoupper($_REQUEST[codigo0]);
$nombre    = strtoupper($_REQUEST[nombre]);
$apellido  = strtoupper($_REQUEST[apellido]);
$cedula    = $_REQUEST[cedula];  
$login_usu = $_REQUEST[login]; 
  

  if($tipo_operacion == 3){
      ////SACAMOS TODOS LOS CHECKBOX DE LOS MUNICIPIOS
      $instruccion3 = "SELECT DISTINCT 0as_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nombre  
                       FROM 0as_escuela 
                       INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                       ORDER BY municipio.nombre ";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_municipio = $resultado['cod_municipio'];
       $nom_municipio = $resultado['nombre'];
       
       $v_cod_municipio = $_REQUEST[$cod_municipio];

       if($v_cod_municipio!=''){            
         ////INSERTAMOS LOS MUNICIPIO DEL USUARIO
         $instruccion5 = "DELETE FROM 0as_escuela WHERE cod_municipio = '$v_cod_municipio'"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 

       } 
    } 
  }    
  
  if($tipo_operacion == 4){
      ////SACAMOS TODOS LOS CHECKBOX DE LOS TIPOS DE MINUTA
      $instruccion3 = "SELECT DISTINCT 0as_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre  
                       FROM 0as_escuela 
                       INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta
                       ORDER BY tipo_minuta.nombre";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_tipo_minuta = $resultado['cod_tipo_minuta'];
       $nom_municipio = $resultado['nombre'];
       
       $v_cod_tipo_minuta = $_REQUEST[$cod_tipo_minuta];
       
       

       if($v_cod_tipo_minuta!=''){    
         ////ELIMIINAMOS LOS TIPOS DE MINUTA
         $instruccion5 = "DELETE FROM 0as_escuela WHERE cod_tipo_minuta = '$v_cod_tipo_minuta'"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 

       } 
    } 
  }   
  
  if($tipo_operacion == 5){
      ////BUSCAMOS Y ORDENAMOS LAS ESCUELAS POR CODIGO PARA SABER CUALES ESTAN DUPLICADAS
      $instruccion2 ="SELECT 0as_escuela.cod_escuela AS cod_escuela, 0as_escuela.nombre AS nom_escuela, municipio.nombre AS nom_municipio, 
                             0as_escuela.cod_municipio AS cod_municipio, 
                             tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             centro_acopio.nombre AS nom_centro_acopio, 0as_escuela.cod_centro_acopio AS cod_centro_acopio,
                             0as_escuela.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, 
                             0as_escuela.total_cupos AS total_cupos 
                      FROM 0as_escuela 
                      INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = 0as_escuela.cod_centro_acopio
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta 
                      INNER JOIN departamento ON departamento.cod_departamento = 0as_escuela.cod_departamento
                      ORDER BY 0as_escuela.grupo_tipo_minuta, 0as_escuela.cod_escuela, 0as_escuela.total_cupos DESC     
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){       

         for ($i=0; $i<$nfilas; $i++){
           $row2 = mysql_fetch_array($consulta2);
           
           $cod_escuela = $row2[cod_escuela];
           $cod_tipo_minuta = $row2[cod_tipo_minuta];
           $cupos = $row2[total_cupos];   
           
           ////ELIMINAMOS LA ESCUELA DUPLICADA CON MENOS CUPOS
           if($cod_escuela == $esc_ant){
              $instruccion5 = "DELETE FROM 0as_escuela WHERE cod_escuela = '$cod_escuela' AND total_cupos = '$cupos' AND cod_tipo_minuta = '$cod_tipo_minuta'"; 
              $consulta5 = mysql_query ($instruccion5, $conexion);
              
              $conta = $conta +1;           
              
              }  
            
           $esc_ant = $cod_escuela;
       
         }
        echo"<BR>Se eliminaron: ".$conta." Escuelas";  
      }    
  } 
  
  if($tipo_operacion == 6){
      ////SACAMOS TODOS LOS MUNICIPIOS
      $instruccion3 = "SELECT DISTINCT municipio.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                       FROM 0as_escuela
                       INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio 
                       ORDER BY municipio.nombre";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
     ////ELIMINAMOS TODAS LAS RELACIONES Y LAS VOLVEMOS A INSERTAR
     $instruccion_del = "DELETE FROM 0as_municipio_centro_acopio"; 
     $consulta_del = mysql_query ($instruccion_del, $conexion);       
      
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_municipio = $resultado['cod_municipio'];
       $nombre = $resultado['nombre'];

       $v_muni_v = $_REQUEST[centro_.$cod_municipio];

       if($cod_municipio !='' && $v_muni_v !=''){             
         ////INSERTAMOS LAS RELACIONES
         $instruccion5 = "INSERT INTO 0as_municipio_centro_acopio (cod_municipio, cod_centro_acopio) values ('$cod_municipio','$v_muni_v')"; 
         $consulta5 = mysql_query ($instruccion5, $conexion); 
         
         ////ACTUALIZAMOS EL CENTRO DE ACOPIO DE ASEO A LAS ESCUELAS 
         $instruccion6 = "UPDATE 0as_escuela SET cod_centro_acopio_as = $v_muni_v WHERE cod_municipio = $cod_municipio"; 
         $consulta6 = mysql_query ($instruccion6, $conexion);
        }
       } 
   } 
   
  if($tipo_operacion == 7){
      ////SACAMOS TODOS LOS MUNICIPIOS
      $instruccion3 = "SELECT DISTINCT 0as_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre  
                       FROM 0as_escuela 
                       INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta
                       ORDER BY tipo_minuta.nombre";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);  
            
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_tipo_minuta = $resultado['cod_tipo_minuta'];
       $nombre = $resultado['nombre'];

       $v_grupo_v = $_REQUEST[grupo_.$cod_tipo_minuta];

       if($cod_tipo_minuta !='' && $v_grupo_v !=''){        
         ////ACTUALIZAMOS EL GRUPO DE MINUTA A LAS ESCUELAS 
         $instruccion6 = "UPDATE 0as_escuela SET grupo_tipo_minuta = $v_grupo_v WHERE cod_tipo_minuta = $cod_tipo_minuta"; 
         $consulta6 = mysql_query ($instruccion6, $conexion);
        }
       } 
   }    
   
  if($tipo_operacion == 8){
      ////SACAMOS TODAS LAS ESCUELAS
      $instruccion3 = "SELECT DISTINCT 0as_escuela.cod_escuela AS cod_escuela, 0as_escuela.total_cupos AS total_cupos, 0as_escuela.cod_tipo_minuta AS cod_tipo_minuta   
                       FROM 0as_escuela 
                       ORDER BY cod_escuela";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);  
            
      for($f=0;$f<$nfilas;$f++){
       $resultado = mysql_fetch_array ($consulta3);
       
       $total_cupos = $resultado['total_cupos'];
       $cod_escuela = $resultado['cod_escuela'];
       $cod_tipo_minuta = $resultado['cod_tipo_minuta'];
       
       $num_manipuladoras = 0;        

       if($total_cupos <= 50){
           $num_manipuladoras = 1;
          }
       if( $total_cupos > 50 && $total_cupos <= 200){
           $num_manipuladoras = 2;
         }
       if( $total_cupos > 200 && $total_cupos <= 400){
           $num_manipuladoras = 3;
         } 
       if( $total_cupos > 400){
           $num_manipuladoras = 4;
         }          
    
         ////ACTUALIZAMOS LA CANTIDAD DE MANIPULADORAS
         $instruccion6 = "UPDATE 0as_escuela SET numero_manipuladoras = $num_manipuladoras WHERE cod_escuela = $cod_escuela AND cod_tipo_minuta = $cod_tipo_minuta"; 
         $consulta6 = mysql_query ($instruccion6, $conexion);
        }
       } 
       
  if($tipo_operacion == 9){
      ////BUSCAMOS Y ORDENAMOS LAS ESCUELAS POR CODIGO PARA SABER CUALES ESTAN DUPLICADAS
      $instruccion2 ="SELECT 0as_escuela.cod_escuela AS cod_escuela, 0as_escuela.nombre AS nom_escuela, municipio.nombre AS nom_municipio, 
                             0as_escuela.cod_municipio AS cod_municipio, 
                             tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             centro_acopio.nombre AS nom_centro_acopio, 0as_escuela.cod_centro_acopio AS cod_centro_acopio,
                             0as_escuela.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, 
                             0as_escuela.total_cupos AS total_cupos, 0as_escuela.grupo_tipo_minuta AS grupo  
                      FROM 0as_escuela 
                      INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = 0as_escuela.cod_centro_acopio
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta 
                      INNER JOIN departamento ON departamento.cod_departamento = 0as_escuela.cod_departamento
                      ORDER BY 0as_escuela.cod_municipio, 0as_escuela.nombre, 0as_escuela.grupo_tipo_minuta ASC      
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){       

         for ($i=0; $i<$nfilas; $i++){
           $row2 = mysql_fetch_array($consulta2);
           
           $cod_escuela = $row2[cod_escuela];
           $cod_tipo_minuta = $row2[cod_tipo_minuta];
           $cupos = $row2[total_cupos]; 
           $nom_escuela = $row2[nom_escuela]; 
           $nom_municipio = $row2[nom_municipio];
           $grupo = $row2[grupo];    
           
           ////ELIMINAMOS LA ESCUELA DUPLICADA CON MENOS CUPOS
           if(($nom_escuela == $nom_esc_ant) && ($nom_municipio == $nom_mun_ant)){
              $instruccion5 = "DELETE FROM 0as_escuela WHERE cod_escuela = '$cod_escuela' AND grupo_tipo_minuta = '$grupo'"; 
              $consulta5 = mysql_query ($instruccion5, $conexion);
              
              $conta = $conta +1;           
              
              }  
            
           $nom_esc_ant = $nom_escuela;
           $nom_mun_ant = $nom_municipio;
       
         }
        echo"<BR>Se eliminaron: ".$conta." Escuelas";  
      }    
  }        
    
}  


 
validarDatosIngresados();
altaDatos();
  
?>


<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>
<META HTTP-EQUIV="Refresh" CONTENT="3; url=javascript:window.close();">
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
