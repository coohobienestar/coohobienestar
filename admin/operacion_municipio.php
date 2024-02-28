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

$nom_form = " MUNICIPIO";

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
   
   $disabled = "";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
   
    $disabled = "Disabled";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT cod_municipio, nombre, cod_departamento, cod_centro_zonal FROM municipio WHERE cod_municipio = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $cod_municipio  = $row3['cod_municipio'];  
   $nombre    = $row3['nombre']; 
   $cod_departamento = $row3['cod_departamento'];
   $cod_centro_zonal = $row3['cod_centro_zonal'];
   
 }
 
if($tipo_operacion == 3){
   $nom_operacion = "CAMBIAR MINUTA - ";
   $icono = "relacionar.png";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT cod_municipio, nombre, cod_departamento FROM municipio WHERE cod_municipio = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $cod_municipio  = $row3['cod_municipio'];  
   $nombre    = $row3['nombre']; 
   $cod_departamento = $row3['cod_departamento'];   
   
 }
    
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_municipio.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
 <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>  
<?php
 if($tipo_operacion <=2){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$codigo");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Nombre</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="nombre" size="40" value='<?php print("$nombre");?>'></td>   
  </tr> 
  <tr>
  <?php
    ////BUSCAMOS LOS DEPARTAMENTOS
    print ("<TD style='font-weight:bold; color: white'>Departamento </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='departamento'>");                

    $instruccion = "SELECT cod_departamento, nombre FROM departamento ORDER BY cod_departamento";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_departamento'] == $cod_departamento){
          print("<option value=".$row['cod_departamento']." Selected>[".$row['cod_departamento']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_departamento'].">[".$row['cod_departamento']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr> 
  <tr>
  <?php
    ////BUSCAMOS LOS CENTROS ZONALES
    print ("<TD style='font-weight:bold; color: white'>Centro Zonal  </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='centroz'>");                

    $instruccion = "SELECT cod_centro_zonal, nombre FROM centro_zonal ORDER BY cod_centro_zonal";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_centro_zonal'] == $cod_centro_zonal){
          print("<option value=".$row['cod_centro_zonal']." Selected>[".$row['cod_centro_zonal']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_centro_zonal'].">[".$row['cod_centro_zonal']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr>   
<?php
 } 
 
/////CONSTRUIMOS EL FORMULARIO PARA ENLAZAR LAS MINUTAS
if($tipo_operacion == 3){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione las Minutas que desea Cambiar al municipio: $nombre</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");
    print ("<tr><th colspan='5'>Esta acción actualiza la minuta en todas las escuelas del municipio que tengan la minuta a ser actualizada</th></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");
    $conta = 1;
        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Minuta Actual</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Minuta Nueva</td>");
          print("</tr>");           
          }

          ////BUSCAMOS LAS MINUTAS ACTUALES DEL MUNICIPIO
          $instruccion_a = "SELECT DISTINCT minuta_escuela.cod_minuta AS cod_minuta, minuta.nombre AS nom_minuta 
                            FROM minuta_escuela 
                            INNER JOIN escuela ON minuta_escuela.cod_escuela = escuela.cod_escuela
                            INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                            INNER JOIN minuta ON minuta.cod_minuta = minuta_escuela.cod_minuta 
                            WHERE escuela.cod_municipio = $cod_municipio
                            ORDER BY minuta_escuela.cod_minuta";
          $consulta_a = mysql_query ($instruccion_a, $conexion);
    
          $nfilas_a = mysql_num_rows ($consulta_a);
          
          for ($a=0; $a<$nfilas_a; $a++){  
            $row_a = mysql_fetch_array($consulta_a);
            
            $cod_minuta_actual = $row_a['cod_minuta'];
            $nom_minuta_actual = $row_a['nom_minuta'];  
                       
            $conta = $conta+1; 
           
            ////DEFINIMOS EL COLOR DE LA FILA
            $resto = $conta%2;
            
            if($resto==0){
               $color = '#D8D8D8';
              }
            if($resto!=0){
               $color = '#848484';
              } 

         print("<tr>");            
            print("<TD style=background:$color>".$cod_minuta_actual." - ".$nom_minuta_actual."</TD>");
                      
         
         ////BUSCAMOS LAS MINUTAS DEL DEPARTAMENTO
          print ("<TD style=background:$color><SELECT NAME='minnueva_$cod_minuta_actual'>"); 
          
          $instruccion_m = "SELECT DISTINCT minuta.cod_minuta AS cod_minuta, minuta.nombre AS nom_minuta 
                            FROM minuta
                            WHERE minuta.cod_departamento = $cod_departamento";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
    
          $row_m = mysql_fetch_array ($consulta_m); 
          
          $valdesc_m = "";
          $descp_m = "--";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
          
            do{ 
               print("<option value=".$row_m['cod_minuta'].">".$row_m['cod_minuta']." - ".$row_m['nom_minuta']."</option>");
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT></TD>");      
         print("</tr>"); 
       }  
} 
?>   
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar" onclick='return confirm("¿Esta seguro de la información proporcionada esta completa?")'></td>
  </tr>   
</table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
