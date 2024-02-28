<?php
//session_start();

include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 
/*
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  */
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$cod_minuta = $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];
$opcion_vista= $_GET['vista'];

$nom_form ="MINUTA";

if($tipo_operacion == 1){
   $nom_operacion = "REGISTRAR ";
   $icono = "guardar.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EDITAR ";
   $icono = "editar.png";
  
  ////BUSCAMOS LOS VALORES ACTUALES DEL REGISTRO
  $instruccion_e = "SELECT nombre, cod_ciclo, cod_departamento, cod_tipo_minuta FROM minuta WHERE cod_minuta = $cod_minuta";
  $consulta_e = mysql_query ($instruccion_e, $conexion);  
  $row_e = mysql_fetch_array ($consulta_e);
  
  $nom_minuta = $row_e['nombre'];
  $cod_ciclo  = $row_e['cod_ciclo'];
  $cod_departamento_e = $row_e['cod_departamento'];
  $cod_tipo_minuta  = $row_e['cod_tipo_minuta'];
 }

if($tipo_operacion == 3){
   $nom_operacion = "MODIFICAR COMPONENTE ";
   $icono = "componente.png";
 } 

  ////BUSCAMOS EL DEPARTAMENTO A LA Q ESTA ASOCIADA LA MINUTA
  $instruccion3 = "SELECT minuta.cod_departamento AS cod_departamento FROM minuta WHERE minuta.cod_minuta = $cod_minuta";
  $consulta3 = mysql_query ($instruccion3, $conexion);  
  $row3 = mysql_fetch_array ($consulta3);
  $cod_departamento = $row3['cod_departamento'];

//// 1 AGREGAR COMPONENTE MINUTA
//// 2 ELIMINAR MENU
//// 3 ELIMINAR PLATO
//// 4 ELIMINAR INGREDIENTE
//// 5 EDITAR CANTIDAD INGREDIENTE
//// 6 AGREGAR INGREDIENTE

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(minuta,menu,plato,ingrediente,tipo_operacion){ 
    var url="../admin/operacion_minuta_comp.php?minuta="+minuta+"&menu="+menu+"&plato="+plato+"&ingrediente="+ingrediente+"&tipo_operacion="+tipo_operacion;
    open(url,"_blank","Sizewindow,width=900,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
// -->
</SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?> </title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_minuta.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$cod_minuta");?>'>
 <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="2" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>  
 </table> 
 <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
 <?php 
  if($tipo_operacion <= 2){
 ?>
  <tr>
   <td style='font-weight:bold; color: white'>Código</td>
   <td><input type="text" Disabled name="codigo" size="5" value='<?php print("$cod_minuta");?>'></td>   
  </tr>
  <tr>
   <td style='font-weight:bold; color: white'>Nombre</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="nombre" size="100" value='<?php print("$nom_minuta");?>'></td>   
  </tr>  
  <tr>
  <?php
    ////BUSCAMOS LOS CICLOS
    print ("<TD style='font-weight:bold; color: white'>Ciclo</TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='ciclo'>");                

    $instruccion = "SELECT cod_ciclo, nombre FROM ciclo ORDER BY cod_ciclo";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_ciclo'] == $cod_ciclo){
          print("<option value=".$row['cod_ciclo']." Selected>[".$row['cod_ciclo']."] - ".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_ciclo'].">[".$row['cod_ciclo']."] - ".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>"); 
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS DEPARTAMENTOS
      print ("<TD style='font-weight:bold; color: white'>Departamento </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='departamento'>"); 
      
      $instruccion_m = "SELECT cod_departamento, nombre FROM departamento ORDER BY cod_departamento";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['cod_departamento'] == $cod_departamento_e){
            print("<option value=".$row_m['cod_departamento']." Selected>[".$row_m['cod_departamento']."] - ".$row_m['nombre']."</option>");
            }else{
              print("<option value=".$row_m['cod_departamento'].">[".$row_m['cod_departamento']."] - ".$row_m['nombre']."</option>");
              }
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr> 
  <tr>
  <?php
      ////BUSCAMOS LOS TIPOS DE MINUTAS
      print ("<TD style='font-weight:bold; color: white'>Tipo de Minuta </TD>");
      print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='tipo'>"); 
      
      $instruccion_m = "SELECT cod_tipo_minuta, nombre FROM tipo_minuta ORDER BY cod_tipo_minuta";
      $consulta_m = mysql_query ($instruccion_m, $conexion);

      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
      
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
          if($row_m['cod_tipo_minuta'] == $cod_tipo_minuta){
            print("<option value=".$row_m['cod_tipo_minuta']." Selected>[".$row_m['cod_tipo_minuta']."] - ".$row_m['nombre']."</option>");
            }else{
              print("<option value=".$row_m['cod_tipo_minuta'].">[".$row_m['cod_tipo_minuta']."] - ".$row_m['nombre']."</option>");
              }
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");
  ?>  
  </tr> 
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>     
 <?php
 }
 
if($tipo_operacion == 3){ 
     ////CONSULTAMOS LA INFORMACION DE LA MINUTA
     $sql1 ="SELECT minuta.cod_minuta AS cod_minuta, minuta.nombre AS nom_minuta, minuta.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo, 
                                minuta.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, minuta.cod_tipo_minuta AS cod_tipo_minuta, 
                                tipo_minuta.nombre AS nom_tipo_minuta
                     FROM minuta 
                     INNER JOIN ciclo ON ciclo.cod_ciclo = minuta.cod_ciclo 
                     INNER JOIN departamento ON departamento.cod_departamento = minuta.cod_departamento 
                     INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = minuta.cod_tipo_minuta
                     WHERE minuta.cod_minuta = $cod_minuta";
     $consulta1 = mysql_query($sql1);
     error_consulta($consulta1,$sql1);  
     $row1 = mysql_fetch_array($consulta1);
     
     $nom_minuta = $row1['nom_minuta'];
     $nom_ciclo  = $row1['nom_ciclo'];
     $nom_depto  = $row1['nom_departamento'];                      

     ////BUSCAMOS LOS MENUS DE LA MINUTA 
     $sql2 ="SELECT DISTINCT plato_ingrediente.cod_menu AS cod_menu, menu.nombre AS nom_menu
             FROM plato_ingrediente 
             INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu
             WHERE plato_ingrediente.cod_minuta = $cod_minuta";
     $consulta2 = mysql_query($sql2);
     error_consulta($consulta2,$sql2);
     $nfilas2 = mysql_num_rows ($consulta2);  
        
        print("<tr>");
        print("<td colspan='$nfilas2' style='font-weight:bold; color: white' align='center'>$cod_minuta || $nom_minuta || $nom_ciclo || $nom_depto  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
      if($opcion_vista == 1){  
        print("<a href=javascript:operar_tabla($cod_minuta,0,0,0,1) title='Agregar Componente Minuta'><img src='../imagenes/agregar_comp.png' alt='' width='24' height='24' border='0'></a>&nbsp;&nbsp;|&nbsp;&nbsp;");
        print("<a href=javascript:operar_tabla($cod_minuta,0,0,0,7) title='Agregar Plato a Menu'><img src='../imagenes/agregar_pla.png' alt='' width='24' height='24' border='0'></a>&nbsp;&nbsp;|&nbsp;&nbsp;");
        print("<a href=javascript:operar_tabla($cod_minuta,0,0,0,8) title='Agregar Menu a Minuta'><img src='../imagenes/agregar_menu.png' alt='' width='24' height='24' border='0'></a>&nbsp;&nbsp;|&nbsp;&nbsp;");
        print("<a href=javascript:operar_tabla($cod_minuta,0,0,0,9) title='Duplicar Minuta'><img src='../imagenes/duplicar_minuta.png' alt='' width='24' height='24' border='0'></a>");     
        }
        print("</td>");
        print("</tr>");
        print("<tr>");         
               
         if ($nfilas2 > 0){
          $ancho = 95 / $nfilas2;  
          for ($i=0; $i<$nfilas2; $i++){
           $row2 = mysql_fetch_array($consulta2);       
          
           print("<td width='$ancho%' style='font-weight:bold; color: white' align='center'>$row2[nom_menu] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
         if($opcion_vista == 1){  
           print("<a href=javascript:operar_tabla($cod_minuta,$row2[cod_menu],0,0,2) title='Quitar Menu'><img src='../imagenes/eliminar_menu.png' width='14' height='14' border='0' alt='Quitar Menu'></a>");
           }
           print("</td>");  
          }
         }else{
           print("<td style='font-weight:bold; color: white' align='center'>La minuta no tiene menus asociados</td>");
          }           
        print("</tr>"); 
        print("</table>");  
  
    ////BUSCAMOS LOS MENUS DE LA MINUTA 
     $sql2 ="SELECT DISTINCT plato_ingrediente.cod_menu AS cod_menu, menu.nombre AS nom_menu
             FROM plato_ingrediente 
             INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu
             WHERE plato_ingrediente.cod_minuta = $cod_minuta";
     $consulta2 = mysql_query($sql2);
     error_consulta($consulta2,$sql2);
     $nfilas2 = mysql_num_rows ($consulta2);      
              
     print("<table width='95%'' align='center' border='2'>");
     print("<tr>");
   
      if ($nfilas2 > 0){
        for ($i=0; $i<$nfilas2; $i++){
         $row2 = mysql_fetch_array($consulta2); 
          
           ////BUSCAMOS LOS PLATOS DEL MENU Y LOS INGREDIENTES   
           $sql3 ="SELECT plato.cod_plato AS cod_plato, plato.nombre AS nom_plato, ingrediente.cod_ingrediente AS cod_ingrediente, 
                          ingrediente.nombre AS nom_ingrediente, plato_ingrediente.cantidad AS cantidad, ingrediente.unidad_base AS unidad_base,       
                          grupo_alimento.nombre AS nombre_grupo_alimento
                   FROM plato_ingrediente 
                   INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                   INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                   INNER JOIN grupo_alimento ON grupo_alimento.cod_grupo_alimento = plato.cod_grupo_alimento
                   WHERE plato_ingrediente.cod_minuta = $cod_minuta AND plato_ingrediente.cod_menu = $row2[cod_menu]
                   ORDER BY plato.cod_grupo_alimento, plato.cod_plato, ingrediente.nombre";
           $consulta3 = mysql_query($sql3);
           error_consulta($consulta3,$sql3);
           $nfilas3 = mysql_num_rows ($consulta3);           
            
            print("<td width='$ancho%' valign='top'>");
                         
             if ($nfilas3 > 0){
             $cod_plato_ant = "";
              for ($j=0; $j<$nfilas3; $j++){
               $row3 = mysql_fetch_array($consulta3);
               
               $cod_plato = $row3['cod_plato'];
               $nom_plato = $row3['nom_plato'];
               $cod_ingrediente = $row3['cod_ingrediente'];
               $nom_ingrediente = $row3['nom_ingrediente'];
               $nom_ingrediente = strtoupper($nom_ingrediente);
               $cantidad = $row3['cantidad'];
               $und_base = $row3['unidad_base'];
               $nom_grupo = $row3['nombre_grupo_alimento'];
               
              ////BUSCAMOS SI EL INGREDIENTE SE DEBE VOLVER A REDONDEAR
              $instruccion_r2 ="SELECT redondear2 FROM ingrediente WHERE cod_ingrediente = $cod_ingrediente";
                           
              $consulta_r2 = mysql_query($instruccion_r2);
              error_consulta($consulta_r2,$instruccion_r2);              
              $row_r2 = mysql_fetch_array($consulta_r2); 
              
              $redondear2 = $row_r2['redondear2'];

               if($redondear2 == 1){
                 ////BUSCAMOS el valor_gr_cc
                 $instruccion_und_2 ="SELECT unidad_medida.valor_gr_cc AS valor_gr_cc
                                      FROM unidad_medida 
                                      INNER JOIN ingrediente_unidad_entrega ON ingrediente_unidad_entrega.cod_unidad_medida = unidad_medida.cod_unidad_medida
                                      WHERE ingrediente_unidad_entrega.cod_ingrediente = $cod_ingrediente";
                         
                 $consulta_und_2 = mysql_query($instruccion_und_2);
                 error_consulta($consulta_und_2,$instruccion_und_2);              
                 $row_und_2 = mysql_fetch_array($consulta_und_2); 
                  
                 $valor_gr_cc_2 = $row_und_2['valor_gr_cc'];
                 
                  if($valor_gr_cc_2 > 0){ 
                    $cantidad_t = $cantidad / $valor_gr_cc_2;
                   }
                                      
                   if($cantidad_t >= 1){
                      $cantidad = $cantidad_t;
                      $und_base = $und_base;
                     }else{
                       $cantidad = $cantidad; 
                       $und_base = "gr";
                       } 
                 } 
               
               ////BUSCAMOS CUANTOS INGREDIENTES TIENE EL PLATO
               $sql4 ="SELECT plato.cod_plato AS cod_plato, plato.nombre AS nom_plato, ingrediente.cod_ingrediente AS cod_ingrediente, 
                              ingrediente.nombre AS nom_ingrediente, plato_ingrediente.cantidad AS cantidad
                       FROM plato_ingrediente 
                       INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                       INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                       WHERE plato_ingrediente.cod_minuta = $cod_minuta AND plato_ingrediente.cod_menu = $row2[cod_menu] 
                         AND plato_ingrediente.cod_plato= $row3[cod_plato]";
               $consulta4 = mysql_query($sql4);
               error_consulta($consulta4,$sql4);
               $nfilas4 = mysql_num_rows ($consulta4);
               
                if($cod_plato_ant != $cod_plato){
                   $conta = 0;  
                   print("<table width='100%' border='1'>");
                   print("<tr><td style='font-weight:bold; color: black; background-color:#f4d359' align='center' colspan='4'>$nom_grupo</td>");
                   print("<tr><td style='font-weight:bold; color: black; background-color:#f4d359' align='center' colspan='3'>$nom_plato</td>");
                 if($opcion_vista == 1){ 
                   print("<td style='font-weight:bold; color: black; background-color:#f4d359' align='center' colspan='1'>");
                   print("<a href=javascript:operar_tabla($cod_minuta,$row2[cod_menu],$cod_plato,$cod_ingrediente,3) title='Quitar Plato'><img src='../imagenes/eliminar_pla.png' width='14' height='14' border='0' alt='Quitar plato'></a>");
                   print("&nbsp;|&nbsp;");
                   print("<a href=javascript:operar_tabla($cod_minuta,$row2[cod_menu],$cod_plato,$cod_ingrediente,6) title='Agregar Ingrediente'><img src='../imagenes/agregar_ing.png' width='14' height='14' border='0' alt='Agregar Ingrediente'></a>");
                   }
                   print("</td></tr>");
                  }
                  $cod_plato_ant = $cod_plato;
                  $conta = $conta+1;
                  
                    print("<tr>"); 
                    print("<td style='font-weight:bold; color: white' align='left'>$nom_ingrediente</td>");
                    print("<td style='font-weight:bold; color: white' align='left'>$cantidad</td>");
                    print("<td style='font-weight:bold; color: white' align='left'>$und_base</td>");
                  if($opcion_vista == 1){  
                    print("<td style='font-weight:bold; color: white' align='center'>");                    
                    print("<a href=javascript:operar_tabla($cod_minuta,$row2[cod_menu],$cod_plato,$cod_ingrediente,4) title='Quitar Ingrediente'><img src='../imagenes/eliminar_ing.png' width='14' height='14' border='0' alt='Quitar Ingrediente'></a>");
                    print("&nbsp;|&nbsp;");
                    print("<a href=javascript:operar_tabla($cod_minuta,$row2[cod_menu],$cod_plato,$cod_ingrediente,5) title='Editar Ingrediente'><img src='../imagenes/editar_ing.png' width='14' height='14' border='0' alt='Editar Ingrediente'></a>");
                    print("</td></tr>");
                    }
                if($conta==$nfilas4){             
                  print("</table>"); 
                  print("<br>");  
                 }
               }
              } 
             print("</td>");
         }
        } 
        print("</tr>");
        print("</table>");
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
