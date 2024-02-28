<?php
include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$tipo_operacion = $_GET['tipo_operacion'];
$opcion_vista = $_GET['vista'];
$usuario = $_GET['usuario'];
$cod_documento = $_GET['codigo'];

$nom_formulario = "DOCUMENTO";

if($tipo_operacion == 1){
   $nom_operacion = "CREAR ";
   $icono = "imagen.png";
   $deshabi ="Disabled";
 
 }   
 
if($tipo_operacion == 3){
   $nom_operacion = "ASIGNAR USUARIO A ";
   $icono = "relacionar.png";
   
   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT codigo, version, nombre_documento FROM 0c_documento_calidad WHERE cod_documento = $cod_documento";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $codigo  = $row3['codigo'];  
   $version = $row3['version']; 
   $nombre_documento = $row3['nombre_documento']; 
 }   
  

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]               
    
    function operar_subir_imagen(nombre,tipo_operacion,usuario){ 
    nombre = document.forms.datechooser.nombre.value;                                        
    
    var url="../funciones/upload.php?nombre="+nombre+"&tipo_operacion="+tipo_operacion+"&usuario="+usuario;
    open(url,"_blank","Sizewindow,width=700,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    } 
               
// -->
</SCRIPT>
<html>
<head>
<title><?php print("$nom_operacion $nom_formulario");?> </title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form NAME='datechooser' action="vr_subir_documento.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='usuario0' value='<?php print("$usuario");?>'>
<input type='hidden' name='documento0' value='<?php print("$cod_documento");?>'>
 <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="3" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_formulario");?></strong></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>  
  <tr>
<?php

////SUBIR IMAGENES     
if($tipo_operacion == 1){

  if($opcion_vista == 1){      
     print("<tr>");
      print("<td style='font-weight:bold; color: white'>Codigo</td>");
      print("<td><img src='../imagenes/requerido.gif'><input type='text' name='codigo' size='20' maxlength='20' value=''></td>");   
     print("</tr>");  
     print("<tr>");
      print("<td style='font-weight:bold; color: white'>Versión</td>");
      print("<td><img src='../imagenes/requerido.gif'><input type='text' name='version' size='20' maxlength='20' value=''></td>");   
    print("</tr>");  
     print("<tr>");
      print("<td style='font-weight:bold; color: white'>Nombre</td>");
      print("<td><img src='../imagenes/requerido.gif'><input type='text' name='nombre' size='40' maxlength='100' value=''></td>");   
     print("</tr>");         
    print("<tr>");
      print ("<td align='left' style='font-weight:bold; color: white'>Ultima Revisión</td>");
      print ("<td><img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' name='fecha_ult' onfocus='doShow(\"datechooser1\",\"datechooser\",\"fecha_ult\")'><div enabled='false' id='datechooser1'></div></td>");
     print("</tr>");  
    print ("<TD style='font-weight:bold; color: white'>Clasificación</TD>");
    print ("<TD><img src='../imagenes/requerido.gif'><SELECT NAME='clasificacion'>");                

    $instruccion = "SELECT cod_clasificacion, nombre FROM 0c_clasificacion ORDER BY nombre";
    $consulta = mysql_query ($instruccion, $conexion);
    $row = mysql_fetch_array ($consulta); 

      $valdesc = "";
      $descp = "--";

        print("<option value=".$valdesc.">".$descp."</option>");  
      do{ 
        if($row['cod_clasificacion'] == $cod_categoria_ingrediente){
          print("<option value=".$row['cod_clasificacion']." Selected>".$row['nombre']."</option>");
         }else{ 
           print("<option value=".$row['cod_clasificacion'].">".$row['nombre']."</option>");
           }
      }while ($row = mysql_fetch_array($consulta)); 
      print("</SELECT></TD>");      
     print("<tr>");
     print("<td colspan = '2' >&nbsp;&nbsp;</td>");       
     print("</tr>");      
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Crear Documento'></td>");
     print("</tr>");   
    } 
    
    
     print("</tr>");
     print("</table>");         
  
 }  
 
////ASIGNAR MUNICIPIO A USUARIO 
if($tipo_operacion == 3){

    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione los usuarios para el documento: &nbsp; </td></tr>");
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'> $codigo - $version - $nombre_documento</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS OPCIONES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT cod_usuario, nombre, apellidos FROM usuario WHERE usuario_calidad = 1 ORDER BY cod_usuario ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_usuario = $row6['cod_usuario'];
        $nom_usuario = strtoupper($row6['nombre']);
        $ape_usuario = strtoupper($row6['apellidos']);
        
        $nombre_com = $nom_usuario." ".$ape_usuario;

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Usuarios</td>");
          print("</tr>");           
          }
        $conta = $conta+1;
        
        ////BUSCAMOS SI EL USUARIO YA TIENE EL DOCUMENTO ASOCIADO
        $instruccion6a = "SELECT cod_documento, cod_usuario FROM 0c_usuario_documento WHERE cod_usuario = $cod_usuario AND cod_documento = $cod_documento";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $cod_documento_aso = $row6a['cod_documento'];        
        
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

          print("<td style=background:$color><input type='checkbox' $cad name='$cod_usuario' value=".$cod_usuario.">&nbsp[$cod_usuario]&nbsp;&nbsp;-&nbsp;&nbsp;".$nombre_com."</td>"); 
          
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
