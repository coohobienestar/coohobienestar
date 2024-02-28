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

$nom_form = " INGREDIENTE";

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
   $instruccion3 = "SELECT cod_ingrediente, cod_categoria_ingrediente, nombre, redondear, unidad_base, redondear2, maneja_inventario 
                    FROM ingrediente WHERE cod_ingrediente = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $cod_ingrediente  = $row3['cod_ingrediente'];  
   $cod_categoria_ingrediente = $row3['cod_categoria_ingrediente']; 
   $nombre    = $row3['nombre']; 
   $redondear = $row3['redondear'];
   $unidad_base = $row3['unidad_base']; 
   $redondear2 = $row3['redondear2']; 
   $maneja_inventario = $row3['maneja_inventario']; 
   
   if($redondear == 1){
      $checked = "Checked";
     }else{
       $checked = "";
       }

   if($redondear2 == 1){
      $checked2 = "Checked";
     }else{
       $checked2 = "";
       }  

   if($maneja_inventario == 1){
      $selected_mi1 = "Selected";
     } 
   if($maneja_inventario == 0){
      $selected_mi0 = "Selected";
     }      
            
 }

if($tipo_operacion == 3){
   $nom_operacion = "DEFINIR UNIDADES DE MEDIDA PARA ";
   $icono = "relacionar.png";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT ingrediente.cod_ingrediente AS cod_ingrediente, ingrediente.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                           ingrediente.nombre AS nombre, ingrediente.redondear AS redondear, ingrediente.unidad_base AS unidad_base, 
                           ingrediente.redondear2 AS redondear2, ingrediente_unidad_entrega.cod_departamento AS cod_departamento
                    FROM ingrediente
                    INNER JOIN ingrediente_unidad_entrega ON ingrediente_unidad_entrega.cod_ingrediente = ingrediente.cod_ingrediente 
                    WHERE ingrediente.cod_ingrediente = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $cod_ingrediente  = $row3['cod_ingrediente'];  
   $cod_categoria_ingrediente = $row3['cod_categoria_ingrediente']; 
   $nombre    = $row3['nombre']; 
   $redondear = $row3['redondear'];
   $unidad_base = $row3['unidad_base']; 
   $redondear2 = $row3['redondear2'];
} 

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_ingrediente.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
 <table width="65%" border="0" align="center" cellpadding="0" cellspacing="0">
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
   <td><img src="../imagenes/requerido.gif"><input type="text" name="nombre" size="30" value='<?php print("$nombre");?>'></td>   
  </tr> 
  <tr>
  <?php
    ////BUSCAMOS LAS CATEGORIAS DE ALIMENTOS
    print ("<TD style='font-weight:bold; color: white'>Categoria</TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='categoria'>");                

    $instruccion = "SELECT cod_categoria_ingrediente, nombre FROM categoria_ingrediente ORDER BY cod_categoria_ingrediente";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_categoria_ingrediente'] == $cod_categoria_ingrediente){
          print("<option value=".$row['cod_categoria_ingrediente']." Selected>[".$row['cod_categoria_ingrediente']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_categoria_ingrediente'].">[".$row['cod_categoria_ingrediente']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Redondear a Medida</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php print("$checked");?> name="redondear"></td>   
  </tr> 
  <tr>
    <?php
      print ("<TD style='font-weight:bold; color: white'>Unidad base </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='unibase'>"); 
      
      $instruccion_m = "SELECT DISTINCT unidad_base FROM ingrediente ORDER BY unidad_base";
      $consulta_m = mysql_query ($instruccion_m, $conexion);
      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['unidad_base'] == $unidad_base){
            print("<option value=".$row_m['unidad_base']." Selected>".$row_m['unidad_base']."</option>");
           }else{
              print("<option value=".$row_m['unidad_base'].">".$row_m['unidad_base']."</option>");
             } 
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");  
    ?>    
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Convertir a Unidades</td>
   <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php print("$checked2");?> name="redondear2"></td>   
  </tr>
  <tr>
    <?php
      print ("<TD style='font-weight:bold; color: white'>Maneja Inventario </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='inventario'>"); 
        
      $valdesc_m = "";
      $descp_m = "--";
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
          print("<option value='1' $selected_mi1 >SI</option>");  
          print("<option value='0' $selected_mi0 >NO</option>");   
      print("</SELECT></TD>"); 
    ?>  
  </tr>       
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>    
<?php
 }  
/////CONSTRUIMOS EL FORMULARIO PARA ENLAZAR LAS UNIDADES
if($tipo_operacion == 3){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione las Unidades para el Ingrediente: $nombre</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS UNIDADES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT cod_unidad_medida, nombre, valor_gr_cc FROM unidad_medida";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_unidad_medida = $row6['cod_unidad_medida'];
        $nom_unidad_med = trim($row6['nombre']);
        $valor_gr_cc = trim($row6['valor_gr_cc']);
        
        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Unidad de Medida</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Valor en GR/CC</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Departamento</td>");
          print("</tr>");           
          }
        $conta = $conta+1;
        
        ////BUSCAMOS SI EL INGREDIENTE YA TIENE LA UNIDADE DE MEDIDA ASOCIADA
        $instruccion6a = "SELECT cod_unidad_medida, cod_departamento FROM ingrediente_unidad_entrega WHERE cod_unidad_medida = $cod_unidad_medida AND cod_ingrediente = $codigo";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $cod_departamento = $row6a['cod_departamento'];
        
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

          print("<td style=background:$color><input type='checkbox' $cad name='$cod_unidad_medida' value=".$cod_unidad_medida.">&nbsp[$cod_unidad_medida]&nbsp;&nbsp;-&nbsp;&nbsp;".$nom_unidad_med."</td>"); 
          
          print("<td style=background:$color align='center'><input type='input' Disabled size='5' name='gr_cc_$cod_unidad_medida' value=".$valor_gr_cc."> </td>"); 

          print("<td style=background:$color align='center'><SELECT NAME='depto_$cod_unidad_medida'>"); 
          
          $instruccion_m = "SELECT cod_departamento, nombre FROM departamento ORDER BY cod_departamento";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
          $row_m = mysql_fetch_array ($consulta_m); 
            
          $valdesc_m = "0";
          $descp_m = "Todos los Departamentos";
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
            do{ 
              if($row_m['cod_departamento'] == $cod_departamento){
                print("<option value=".$row_m['cod_departamento']." Selected>".$row_m['nombre']."</option>");
               }else{
                  print("<option value=".$row_m['cod_departamento'].">".$row_m['nombre']."</option>");
                 } 
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT></td>");
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }
     
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
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
  