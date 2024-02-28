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
function validarDatosIngresados($conexion){
  $error="";
  if($_REQUEST['fecha_ini']=='')
    $error=$error . "Debe seleccionar la Fecha Inicial del programa.<br>";

  if($_REQUEST['fecha_fin']=='')
    $error=$error . "Debe seleccionar la Fecha Final del programa.<br>"; 
    
    $fecha_ini = strtotime($_REQUEST['fecha_ini']);
    $fecha_fin = strtotime($_REQUEST['fecha_fin']);
    
  if($fecha_fin<=$fecha_ini)
    $error=$error . "La Fecha Final debe ser mayor que la Fecha Inicial<br>";  

////BUSCAMOS QUE LAS FECHAS SELECCIONADAS CORRESPONDAN A UN PERIODO VALIDO DE PROGRAMACION
      $instruccion2 = "SELECT COUNT(cod_minuta) AS cuenta FROM minuta_escuela_historial WHERE fecha_inicial = '$_REQUEST[fecha_ini]' AND fecha_final = '$_REQUEST[fecha_fin]'";
      $consulta2 = mysql_query ($instruccion2, $conexion);
      $row2 = mysql_fetch_array ($consulta2);
      
      $cuenta = $row2['cuenta'];
      
      if($cuenta == 0){
         $error=$error . "Las Fechas seleccionadas no tienen un Historial de Cupos guardado.<br>"; 
        }                                                  
       
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
   }
}
  
function altaDatos($conexion){
      ////USUARIO
      $cod_usuario = $_SESSION['cod_usuario'];
       
      ////RECIBIMOS LA FECHA INICIAL
      $f_inicial = $_REQUEST['fecha_ini']; 

      ////RECIBIMOS LA FECHA FINAL
      $f_final = $_REQUEST['fecha_fin'];    
      
      ////BORRAMOS LA TABLA MINUTA ESCUELA PARA INSERTAR EL HISTORIAL DE CUPOS DESEADO
      $instruccion_del = "DELETE FROM minuta_escuela";
      $consulta_del = mysql_query ($instruccion_del, $conexion);
      
      ////INSERTAMOS EL HISTORIAL DE MINUTA ESCUELA EN EL RANGO DE FECHAS SELECCIONADO
      $instruccion_insert = "INSERT INTO minuta_escuela (cod_minuta,cod_escuela,cod_rango_edad,cupos,cod_jornada)
                             SELECT cod_minuta, cod_escuela, cod_rango_edad, cupos, cod_jornada
                             FROM minuta_escuela_historial
                             WHERE fecha_inicial = '$f_inicial' AND fecha_final = '$f_final'";
      $consulta_insert = mysql_query ($instruccion_insert, $conexion); 
      
      ////ACTUALIZAMOS EL PARAMETRO PARA SABER LOS CUPOS DE QUE FECHA ESTAN EN EL SISTEMA
      $instruccion_upd_par = "UPDATE parametro SET valor='$f_inicial - $f_final' WHERE nombre = 'fechas_actual_cupos'";
      $consulta_upd_par = mysql_query ($instruccion_upd_par, $conexion);   

} 
include("../conexion/conectarbd.php");
include("../funciones/calculo_requerimientos.php");
$conexion=Conectarse(); 

validarDatosIngresados($conexion);
altaDatos($conexion);  

// Cerrar conexión
mysql_close ($conexion);
?>
<br><center><strong><span class='Estilo1'>Se restauro correctamente el Historial de Cupos por favor verifique.</span></center></strong>
<center><strong><br><br><a href="../menu_retorna.php"><img src="../imagenes/retornar.png">&nbsp;Retornar</a><center>
</body>
</html>
