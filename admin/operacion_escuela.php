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

$cod_escuela = $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = "ESCUELA";

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
   
   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT * FROM escuela
                    WHERE cod_escuela=$cod_escuela";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);
   
   $cod_escuela = $row3['cod_escuela'];  
   $nom_escuela = $row3['nombre']; 
   $c_acopio    = $row3['cod_centro_acopio']; 
   $c_municipio = $row3['cod_municipio'];
   $cod_escuela_agr = $row3['cod_escuela_agrupada']; 
 }

if($tipo_operacion == 3){
   $nom_operacion = "RELACIONAR MINUTA - ";
   $icono = "relacionar.png";
   
   ////CONSULTAMOS EL DEPARTAMENTO DE LA ESCUELA
   $instruccion3 = "SELECT municipio.cod_departamento AS cod_departamento, escuela.nombre AS nombre, municipio.nombre AS nom_m
                    FROM escuela 
                    INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                    INNER JOIN departamento ON municipio.cod_departamento = departamento.cod_departamento
                    WHERE escuela.cod_escuela = $cod_escuela";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);
   
   $cod_departamento = $row3['cod_departamento']; 
   $nom_escuela = $row3['nombre'];
   $nom_municipio = $row3['nom_m'];
 } 

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_escuela.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo_esc' value='<?php print("$cod_escuela");?>'>
 <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
    </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
    </tr>  
<?php
 if($tipo_operacion<=2){  
?> 
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_escuela");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Nombre</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="nombre" size="50" value='<?php print("$nom_escuela");?>'></td>   
  </tr>  
  <tr>
  <?php
    ////BUSCAMOS LOS CENTROS DE ACOPIO
    print ("<TD style='font-weight:bold; color: white'>Centro de Acopio </TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='c_acopio'>");                

    $instruccion = "SELECT cod_centro_acopio, nombre FROM centro_acopio ORDER BY cod_centro_acopio";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_centro_acopio'] == $c_acopio){
          print("<option value=".$row['cod_centro_acopio']." Selected>[".$row['cod_centro_acopio']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_centro_acopio'].">[".$row['cod_centro_acopio']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS MUNICIPIOS
      print ("<TD style='font-weight:bold; color: white'>Municipio </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='municipio'>"); 
      
      $instruccion_m = "SELECT cod_municipio, nombre FROM municipio ORDER BY cod_municipio";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['cod_municipio'] == $c_municipio){
            print("<option value=".$row_m['cod_municipio']." Selected>[".$row_m['cod_municipio']."] - ".$row_m['nombre']."</option>");
            }else{
              print("<option value=".$row_m['cod_municipio'].">[".$row_m['cod_municipio']."] - ".$row_m['nombre']."</option>");
              }
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LAS ESCUELAS
      print ("<TD style='font-weight:bold; color: white'>Agrupada Con </TD>");
      print ("<TD>&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME='escuela'>"); 
      
      $instruccion_m = "SELECT cod_escuela, nombre FROM escuela ORDER BY cod_escuela";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['cod_escuela'] == $cod_escuela_agr){
            print("<option value=".$row_m['cod_escuela']." Selected>[".$row_m['cod_escuela']."] - ".$row_m['nombre']."</option>");
            }else{
              print("<option value=".$row_m['cod_escuela'].">[".$row_m['cod_escuela']."] - ".$row_m['nombre']."</option>");
              }
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr>   
<?php
 }  
/////CONSTRUIMOS EL FORMULARIO PARA ENLAZAR LAS MINUTAS
if($tipo_operacion == 3){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione las Minutas que desea Relacionar a la Escuela: $nom_escuela del Municipio: $nom_municipio</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS MINUTAS QUE APLICAN AL DEPARTAMENTO DE LA ESCUELA
    $instruccion6 = "SELECT minuta.cod_minuta, minuta.nombre FROM minuta WHERE minuta.cod_departamento = $cod_departamento AND cod_minuta <> 9999";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_minuta = $row6['cod_minuta'];
        $nom_minuta = trim($row6['nombre']);
        
        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Minuta</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Rango Edad</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Cupos</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Jornada</td>");
          print("</tr>");           
          }
        $conta = $conta+1;
        
        ////BUSCAMOS SI LA ESCUELA YA TIENE ASOCIADA ESA MINUTA
        $instruccion6a = "SELECT cod_minuta, cod_rango_edad, cupos, cod_jornada 
                          FROM minuta_escuela 
                          WHERE cod_minuta = $cod_minuta AND cod_escuela = $cod_escuela";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $cupos = $row6a['cupos']; 
        $cod_rango = $row6a['cod_rango_edad'];
        $cod_jornada = $row6a['cod_jornada'];        
        
         if($cuenta > 0){
           $cad =" checked ";
          }else{
            $cad ="";
            }  
         
          ////DEFINIMOS EL COLOR DE LA FILA
          $resto = $conta%2;
          
          if($resto==0){
             $color = '#D8D8D8';
            }
          if($resto!=0){
             $color = '#848484';
            }  
                  
         print("<tr>");    

          print("<td style=background:$color><input type='checkbox' $cad name='$cod_minuta' value=".$cod_minuta.">&nbsp[$cod_minuta]&nbsp;&nbsp;-&nbsp;&nbsp;".$nom_minuta."</td>"); 
          
          ////BUSCAMOS LOS RANGOS DE EDAD
          print ("<TD style=background:$color><SELECT NAME='rango_$cod_minuta'>"); 
          
          $instruccion_m = "SELECT cod_rango_edad, nombre FROM rango_edad ORDER BY cod_rango_edad";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
    
          $row_m = mysql_fetch_array ($consulta_m); 
            
          $valdesc_m = "";
          $descp_m = "--";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
            do{ 
            
              if($row_m['cod_rango_edad'] == $cod_rango){
                print("<option value=".$row_m['cod_rango_edad']." Selected>".$row_m['nombre']."</option>");
                
                }else{
                  print("<option value=".$row_m['cod_rango_edad'].">".$row_m['nombre']."</option>");
                  }
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT></TD>");
          
          print("<td style=background:$color align='left'><input type='input' size='3' name='cupos_$cod_minuta' value=".$cupos."> </td>"); 
          
          ////BUSCAMOS LAS JORNADAS
          print ("<TD style=background:$color><SELECT NAME='jornada_$cod_minuta'>"); 
          
          $instruccion_m = "SELECT cod_jornada, nombre FROM jornada ORDER BY cod_jornada";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
    
          $row_m = mysql_fetch_array ($consulta_m); 
            
          $valdesc_m = "";
          $descp_m = "--";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
            do{ 
            
              if($row_m['cod_jornada'] == $cod_jornada){
                print("<option value=".$row_m['cod_jornada']." Selected>".$row_m['nombre']."</option>");
                
                }else{
                  print("<option value=".$row_m['cod_jornada'].">".$row_m['nombre']."</option>");
                  }
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT></TD>");          
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }    
}
?>   
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>            
 </table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
