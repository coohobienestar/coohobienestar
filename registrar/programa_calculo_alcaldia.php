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
      die("<br>No ha iniciado una sesión O no puede acceder a esta pagina por su perfil.");
      } 

?>
<html>
<head>
<title>PROGRAMAR CICLO A ALMUERZOS ALCALDIA</title>
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
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<body>
<form NAME='datechooser' action="valida_programa_calculo_alcaldia.php" method="post">

<?php
////ENVIAMOS EN CAMPOS OCULTOS VARIOS PARAMETROS


?>
<table width="70%" align="Center" border = "0">
  <tr>
    <th colspan=5><div align="center"><strong>PROGRAMACION DE CICLO A CALCULAR PARA ALMUERZO ALCALDIA</strong></div></th>
  </tr>
    <?php 
      print ("<tr><td align='left' style='font-weight:bold; color: white'>Fecha Inicial &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      print ("<img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' name='fecha_ini' onfocus='doShow(\"datechooser1\",\"datechooser\",\"fecha_ini\")'><div enabled='false' id='datechooser1'></div></td>");
      print ("<td align='right' style='font-weight:bold; color: white'>Fecha Final &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");                                                               
      print ("<img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' name='fecha_fin' onfocus='doShow(\"datechooser2\",\"datechooser\",\"fecha_fin\")'><div enabled='false' id='datechooser2'></div></td></tr>"); 
      print ("<tr><td>&nbsp;</td></tr>");     
    ?>
  </table>
  <table width="70%" align="Center" border = "0">
  <tr>
   <?php
    ////BUSCAMOS LOS CICLOS DEL SISTEMA LOS USADOS PARA PROGRAMAR NO EL CICLO MATRIZ
      print ("<TD style='font-weight:bold; color: white' colspan='5'>Seleccione el Ciclo a Calcular ");
      print ("<SELECT NAME='ciclo'>");                

      $instruccion = "SELECT cod_ciclo, nombre FROM ciclo WHERE cod_ciclo BETWEEN 950 AND 952 ORDER BY cod_ciclo";
      $consulta = mysql_query ($instruccion, $conexion);
      error_consulta($consulta,$instruccion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_ciclo'].">".$row['nombre']."</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");
   ?>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td style='font-weight:bold; color: white' colspan=5><strong>Seleccione los menus que desea INCLUIR en el calculo</strong></td>
  </tr>   
  <tr>
    <td>&nbsp;</td>
  </tr>   
    <?php
      ////BUSCAMOS LOS DIFERENTES MENUS QUE EXITEN EN EL SISTEMA
      $instruccion6 = "SELECT DISTINCT menu.cod_menu AS cod_menu, menu.nombre AS nombre
                       FROM plato_ingrediente 
                       INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu
                       WHERE plato_ingrediente.cod_minuta BETWEEN 9988 AND 9991
                       ORDER BY menu.cod_menu";
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $row6 = mysql_fetch_array($consulta6);
      $nfilas = mysql_num_rows ($consulta6);
      
      $conta = 0;
    
    if($nfilas>0){ 
      do{
          if($conta == 5){
            print("<tr>");
           }
            $conta = $conta + 1;
            $cod_menu = trim($row6['cod_menu']);
            $nom_menu = trim($row6['nombre']);
            
             print("<td><span class='presentacion'>");
             print("<input type='checkbox' name='$cod_menu' value=".$cod_menu.">&nbsp;".$nom_menu." ");            
             print("</td>"); 
          
          if($conta == 5){
            print("</tr>");
            $conta = 0;
           }
          
      }while ($row6 = mysql_fetch_array($consulta6)); 
     
     }  
    
    ?> 
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
   <?php
    ////BUSCAMOS LAS PROGRAMCIONES DEL SISTEMA
      print ("<TD style='font-weight:bold; color: white' colspan='5'>Seleccione la programación de Inventarios ");
      print ("<SELECT NAME='programacion_inv'>");                

      $instruccion = "SELECT DISTINCT programacion.cod_programacion AS cod_programacion, ciclo.nombre AS nombre
                      FROM programacion 
                      INNER JOIN ciclo ON ciclo.cod_ciclo = programacion.cod_ciclo 
                      WHERE programacion.estado = 1 AND programacion.cod_ciclo BETWEEN 950 AND 952
                      ORDER BY programacion.cod_programacion DESC";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
      
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_programacion'].">[".$row['cod_programacion']."] - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");  
   ?>
  </tr>           
    
 </table>  
 <table width="70%" align="Center" border = "0">  
  <tr>
    <td colspan=5>
    <div align="center" class="Estilo1"><input type="submit" name="Submit" value="Iniciar Calculo" onclick='return confirm("¿Esta seguro de la información proporcionada esta completa?")'/></div>
    </td>
  </tr>
</table>        
</form>
</body>
</head>
</html>                                         
