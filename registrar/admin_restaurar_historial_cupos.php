<?php                                                 
session_start();
                                    
include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 

include("../funciones/generales.php");

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");

////SACAMOS EL NOMBRE DEL INFORME
$url = $_SERVER['REQUEST_URI'];
$array = explode('/',$url);
//print("$array[3]"); ////EL NIVEL 3 ES EL NOMBRE DEL INFORME PARA VALIDAR QUE EL USUARIO TENGA ACCESO A ESE INFORME
$num_array = count($array);
$num_array = $num_array - 1;
 
$informe = $array[$num_array];
$separar = explode('?',$informe);
$informe = $separar[0];

$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag'];

 ////LLAMAMOS LA FUNCION QUE DEFINE EL PERFIL DE VISTA DE LA OPCION
 $opcion_vista = opcion_vista($informe,$cod_usuario,$conexion);
   
$registro = "SELECT opcion.ruta FROM usuario_opcion
             INNER JOIN usuario ON usuario.cod_usuario=usuario_opcion.cod_usuario
             INNER JOIN opcion ON opcion.id_opcion=usuario_opcion.id_opcion
             WHERE usuario.cod_usuario=$cod_usuario AND opcion.ruta like '%$informe%'";
$result = mysql_query($registro);
error_consulta($result,$registro);                           
    
    if($reg=mysql_fetch_array($result)){
       $autorizado=1;
      }
  if(($cod_usuario != '') && ($autorizado==1)){
    }else{
      die("<br>No ha iniciado una sesi�n O no puede acceder a esta pagina por su perfil.");
      } 

?>
<html>
<head>
<title>RESTAURAR HISTORIAL DE CUPOS</title>
<?php

////CONSULTAMOS LOS DATOS DEL USUARIO
$instruccion = "SELECT usuario.nombre AS nom_usuario, usuario.apellidos AS ape_usuario
                FROM usuario 
                WHERE usuario.cod_usuario=$cod_usuario";
$consulta = mysql_query ($instruccion, $conexion);
$row = mysql_fetch_array ($consulta);

$nom_usuario = $row['nom_usuario'];
$ape_usuario = $row['ape_usuario'];

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
</head>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesi�n</a></td>
</tr>
</table>
<br>
<body>
<form NAME='datechooser' action="valida_restaurar_historial.php" method="post">

<?php
////ENVIAMOS EN CAMPOS OCULTOS VARIOS PARAMETROS


////CONSULTAMOS LAS FECHAS DE LOS CUPOS
$instruccion_fec = "SELECT valor FROM parametro WHERE nombre = 'fechas_actual_cupos'";
$consulta_fec = mysql_query ($instruccion_fec, $conexion);
$row_fec = mysql_fetch_array ($consulta_fec);

$fechas = $row_fec['valor'];

$fecha_cup = explode("-", $fechas);
$fecha_ini = trim($fecha_cup[0]);
$fecha_fin = trim($fecha_cup[1]);

$date1 = date_create($fecha_ini);
$date_ini = date_format($date1, 'Y-m-d');

$date2 = date_create($fecha_fin);
$date_fin = date_format($date2, 'Y-m-d');

?>
<table width="70%" align="Center" border = "0">
  <tr>
    <th colspan=5><div align="center"><strong>RESTAURAR HISTORIAL DE CUPOS</strong></div></th>
  </tr>
    <?php 
      print ("<tr  style='font-weight:bold; color: green' colspan=5><td><strong>LOS CUPOS ACTUALES EN EL SISTEMA CORRESPONDEN A LAS FECHAS DEL: $date_ini A $date_fin</strong></td></tr>");
      print ("<tr><td>&nbsp;</td></tr>");
      print ("<td style='font-weight:bold; color: red' colspan=5><strong>RECUERDE QUE DEBE GUARDAR PRIMERO LOS CUPOS QUE ESTA UTILIZANDO ANTES DE RESTAURAR UN HISTORIAL DE CUPOS O LOS DATOS ACTUALES SE PERDERAN</strong></td>");
      print ("<tr><td>&nbsp;</td></tr>"); 
      print ("<td style='font-weight:bold; color: white' colspan=5><strong>Seleccione las Fechas para las cuales desea <strong>RESTAURAR</strong> el Historial de Cupos</strong></td>");
      print ("<tr><td>&nbsp;</td></tr>");
      print ("<tr><td align='left' style='font-weight:bold; color: white'>Fecha Inicial &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      print ("<img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' name='fecha_ini' onfocus='doShow(\"datechooser1\",\"datechooser\",\"fecha_ini\")'><div enabled='false' id='datechooser1'></div></td>");
      print ("<td align='right' style='font-weight:bold; color: white'>Fecha Final &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");                                                               
      print ("<img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' name='fecha_fin' onfocus='doShow(\"datechooser2\",\"datechooser\",\"fecha_fin\")'><div enabled='false' id='datechooser2'></div></td></tr>"); 
      print ("<tr><td>&nbsp;</td></tr>"); 
   
    ?>
  </table>   
 <table width="70%" align="Center" border = "0">  
  <tr>
    <td colspan=5>
    <div align="center" class="Estilo1"><input type="submit" name="Submit" value="Restaurar Historial" onclick='return confirm("�Esta seguro de la informaci�n proporcionada es correcta?")'/></div>
    </td>
  </tr>
</table>        
</form>
</body>
</head>
</html>                                         
