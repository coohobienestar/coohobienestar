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
      
      ////BORRAMOS LA TABLA excluido_escuela PARA INSERTAR EL HISTORIAL DE EXCLUSIONES DESEADO
      $instruccion_del = "DELETE FROM excluido_escuela";
      $consulta_del = mysql_query ($instruccion_del, $conexion);
      
      ////BORRAMOS LA TABLA excluido_escuela_menu PARA INSERTAR EL HISTORIAL DE EXCLUSIONES DESEADO
      $instruccion_del1 = "DELETE FROM excluido_escuela_menu";
      $consulta_del1 = mysql_query ($instruccion_del1, $conexion);
      
      ////BORRAMOS LA TABLA excluido_municipio PARA INSERTAR EL HISTORIAL DE EXCLUSIONES DESEADO
      $instruccion_del2 = "DELETE FROM excluido_municipio";
      $consulta_del2 = mysql_query ($instruccion_del2, $conexion);      
      
      ////BORRAMOS LA TABLA excluido_municipio_menu PARA INSERTAR EL HISTORIAL DE EXCLUSIONES DESEADO
      $instruccion_del3 = "DELETE FROM excluido_municipio_menu";
      $consulta_del3 = mysql_query ($instruccion_del3, $conexion);                  
      
      ////INSERTAMOS EL HISTORIAL DE excluido_escuela EN EL RANGO DE FECHAS SELECCIONADO
      $instruccion_insert = "INSERT INTO excluido_escuela (cod_escuela)
                             SELECT cod_escuela
                             FROM excluido_escuela_historial
                             WHERE fecha_inicial = '$f_inicial' AND fecha_final = '$f_final'";
      $consulta_insert = mysql_query ($instruccion_insert, $conexion); 
      
      ////INSERTAMOS EL HISTORIAL DE excluido_escuela_menu EN EL RANGO DE FECHAS SELECCIONADO
      $instruccion_insert2 = "INSERT INTO excluido_escuela_menu (cod_escuela,cod_menu,cod_tipo_minuta)
                              SELECT cod_escuela, cod_menu, cod_tipo_minuta
                              FROM excluido_escuela_menu_historial
                              WHERE fecha_inicial = '$f_inicial' AND fecha_final = '$f_final'";
      $consulta_insert2 = mysql_query ($instruccion_insert2, $conexion); 
      
      ////INSERTAMOS EL HISTORIAL DE excluido_municipio EN EL RANGO DE FECHAS SELECCIONADO
      $instruccion_insert3 = "INSERT INTO excluido_municipio (cod_municipio)
                              SELECT cod_municipio
                              FROM excluido_municipio_historial
                              WHERE fecha_inicial = '$f_inicial' AND fecha_final = '$f_final'";
      $consulta_insert3 = mysql_query ($instruccion_insert3, $conexion);  
      
      ////INSERTAMOS EL HISTORIAL DE excluido_municipio_menu EN EL RANGO DE FECHAS SELECCIONADO
      $instruccion_insert4 = "INSERT INTO excluido_municipio_menu (cod_municipio,cod_menu)
                              SELECT cod_municipio, cod_menu
                              FROM excluido_municipio_menu_historial
                              WHERE fecha_inicial = '$f_inicial' AND fecha_final = '$f_final'";
      $consulta_insert4 = mysql_query ($instruccion_insert4, $conexion);                    

} 
include("../conexion/conectarbd.php");
include("../funciones/calculo_requerimientos.php");
$conexion=Conectarse(); 

validarDatosIngresados($conexion);
altaDatos($conexion);  

// Cerrar conexión
mysql_close ($conexion);
?>
<br><center><strong><span class='Estilo1'>Se restauro correctamente el Historial de Exclusiones por favor verifique.</span></center></strong>
<center><strong><br><br><a href="../menu_retorna.php"><img src="../imagenes/retornar.png">&nbsp;Retornar</a><center>
</body>
</html>
