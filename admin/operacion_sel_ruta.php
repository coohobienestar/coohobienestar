<?php     
session_start();
  
include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$codigo= $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = " RUTA";

if($tipo_operacion == 0){
   $nom_operacion = "SELECCIONAR ";
   $icono = "guardar.png";
   
   $disabled = "";
 }
 
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form NAME='datechooser' action="operacion_ruta.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
<input type='hidden' name='usuario0' value='<?php print("$cod_usuario");?>'>
 <table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>  
<?php
 if($tipo_operacion == 0){  
    print ("<input type='hidden' name='tipo_operacion' value='1'>"); 
 
    ////BUSCAMOS LAS RUTAS PREDETERMINADAS
    print ("<TD style='font-weight:bold; color: white'>Origen </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='origen'>");                

    $instruccion = "SELECT cod_origen, nombre FROM fl_origen ORDER BY nombre";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_origen'] == $cod_origen){
          print("<option value=".$row['cod_origen']." Selected>".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_origen'].">".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>");   
 
  print("<tr>");
   print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Siguiente' onclick='return confirm(\"¿Esta seguro que la ruta seleccionada es la que desea registrar...?\")'></td>");
  print("</tr>");  
  }  
?>  
 

</table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
