<?php
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");    
?>
<html>
<style type="text/css">
<!--
.Estilo1 {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 18px;
  color: #F6951F;
  font-weight: bolt;
  }
-->
</style>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<body>

<?php
$cod_programacion_inv = $_REQUEST['programacion_inv'];

function validarDatosIngresados($conexion){
  $error="";
  if($_REQUEST['fecha_ini']=='')
    $error=$error . "Debe seleccionar la Fecha Inicial del programa.<br>";

  if($_REQUEST['fecha_fin']=='')
    $error=$error . "Debe seleccionar la Fecha Final del programa.<br>"; 
    
    $fecha_ini = strtotime($_REQUEST['fecha_ini']);
    $fecha_fin = strtotime($_REQUEST['fecha_fin']);
    
  if($fecha_fin<$fecha_ini)
    $error=$error . "La Fecha Final debe ser mayor o igual que la Fecha Inicial<br>";  

  if($_REQUEST['ciclo'] == '')
     $error=$error . "Debe seleccionar el ciclo que desea calcular<br>";  
     
    $instruccion3 = "SELECT cod_minuta FROM minuta WHERE cod_ciclo = $_REQUEST[ciclo]";
    $consulta3 = mysql_query ($instruccion3, $conexion);  
    
    $nfilas = mysql_num_rows($consulta3);
    
    if($nfilas<=0){
      $error=$error . "El ciclo seleccionado no tiene Minutas asociadas<br>";  
      }
           
       
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
   }
}

////SE INICIA LA CAPA QUE MUESTRA LA BARRA DE PROGRESO  
echo "<div id='progress' style='position:relative;padding:0px; width:650px;height:960px;left:25px;'>";  
function altaDatos($conexion){
      ////SACAMOS LA FECHA ACTUAL
      $fecha = date("Y-m-d H:i:s");
        
      ////USUARIO
      $cod_usuario = $_SESSION['cod_usuario'];
       
      ////RECIBIMOS LA FECHA INICIAL
      $f_inicial = $_REQUEST['fecha_ini']; 

      ////RECIBIMOS LA FECHA FINAL
      $f_final = $_REQUEST['fecha_fin']; 
      
      ////RECIBIMOS EL CICLO
      $cod_ciclo = $_REQUEST['ciclo'];

      ////RECIBIMOS EL NOMBRE DE LOS MENUS
      $nombre_Menu = $_REQUEST['nombre_Menu'];
          
      ////CONSULTAMOS EL ULTIMO CONSECUTIVO INSERTADO PARA LAS PROGRAMACIONES
      $instruccion2 = "SELECT MAX(cod_programacion) AS programacion FROM programacion";
      $consulta2 = mysql_query ($instruccion2, $conexion);
      $row2 = mysql_fetch_array ($consulta2);
      
      $max_prog = $row2['programacion'];
      $programacion = $max_prog+1; 

      ////SACAMOS TODOS LOS CHECKBOX DE LOS MENUS SELECCIONADOS
      $instruccion3 = "SELECT cod_menu FROM menu";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
      for($f=0;$f<$nfilas;$f++){

////ACA SE IMPRIMIE NE LA CAPA DE LA BARRA DE PROGRESO EL AVANCE      
echo "<div><img src='../imagenes/cargando.gif' /></div>";
flush();
ob_flush(); 

 
       $resultado = mysql_fetch_array ($consulta3);
       
       $cod_menu = $resultado['cod_menu'];
       
       $v_cod_menu= $_REQUEST[$cod_menu];
       
       if($v_cod_menu!=''){
         ////INSERTAMOS LOS MENUS QUE SE EXCLUIRAN PARA TODOS LOS MUNICIPIOS Y ESCUELAS
         mysql_query("INSERT INTO excluido_menu(cod_programacion,cod_menu) VALUES 
          ('$programacion','$v_cod_menu')",$conexion); 
        }
       }  
       
      ////SACAMOS LAS MINUTAS ASOCIADAS AL CICLO SELECCIONADO                         
      $instruccion3 = "SELECT cod_minuta FROM minuta WHERE cod_ciclo = $cod_ciclo";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      
      $nfilas = mysql_num_rows($consulta3);
      
       if($nfilas > 0){
        for($f=0;$f<$nfilas;$f++){
         $resultado = mysql_fetch_array ($consulta3);
         
         $cod_minuta = $resultado['cod_minuta'];
  
           ////INSERTAMOS LAS PROGRAMACIONES DE LAS MINUTAS
           mysql_query("INSERT INTO programacion(cod_programacion,cod_ciclo,cod_minuta,fecha_inicial,fecha_final,cod_usuario,fecha,nombre_menu) VALUES 
            ('$programacion','$cod_ciclo','$cod_minuta','$f_inicial','$f_final','$cod_usuario','$fecha','$nombre_Menu')",$conexion);  

            ////INSERTAMOS NOMBRE DEL MENU
           mysql_query("INSERT INTO programacion(cod_programacion,cod_ciclo,cod_minuta,fecha_inicial,fecha_final,cod_usuario,fecha) VALUES 
           ('$programacion','$cod_ciclo','$cod_minuta','$f_inicial','$f_final','$cod_usuario','$fecha')",$conexion); 
         }
        }       
} 

include("../conexion/conectarbd.php");
include("../funciones/calculo_requerimientos.php");
$conexion=Conectarse(); 

validarDatosIngresados($conexion);
altaDatos($conexion);  
                      
$cod_usuario = $_SESSION['cod_usuario'];

////BUSCAMOS LA PROGRAMACION QUE SE ACABO DE ALMACENAR
  $sql = "SELECT MAX(cod_programacion) AS programacion FROM programacion WHERE cod_usuario = $cod_usuario";
  $result = mysql_query($sql);
  error_consulta($result,$sql); 
  $resultado = mysql_fetch_array ($result);
     
  $cod_programacion = $resultado['programacion'];

calcular_ingredientes($conexion,$cod_programacion);    

redondear($conexion,$cod_programacion); 

inventario($conexion,$cod_programacion,$cod_programacion_inv);

////SE CIERRA LA BARRA DE PROGRESO Y SE QUITA CUANDO TERMINA DE PROCESAR
echo "</div>";
echo "<script>";
echo "document.getElementById('progress').style.display = 'none';";
echo "</script>";

// Cerrar conexi�n
mysql_close ($conexion);
?>
<br><center><strong><span class='Estilo1'>Se realizaron los calculos correctamente para la programaci�n: <?php echo($cod_programacion)?> Verifique los Informes</span></center></strong>
<center><strong><span class='presentacion'>Se registraron los datos correctamente.</span></strong><br><br><a href="../menu_retorna.php"><img src="../imagenes/retornar.png">&nbsp;Retornar</a><center>
</body>
</html>
