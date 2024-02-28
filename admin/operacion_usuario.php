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

$nom_form = " USUARIO";

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
   $instruccion3 = "SELECT cod_usuario, nombre, apellidos, cedula, login FROM usuario WHERE cod_usuario = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $nombre  = $row3['nombre'];  
   $apellidos = $row3['apellidos']; 
   $cedula    = $row3['cedula']; 
   $login_usu = $row3['login']; 
 }

if($tipo_operacion == 3){
   $nom_operacion = "DEFINIR OPCIONES DE";
   $icono = "relacionar.png";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT cod_usuario, nombre, apellidos, cedula, login FROM usuario WHERE cod_usuario = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $nombre  = $row3['nombre'];  
   $apellidos = $row3['apellidos']; 
   $cedula    = $row3['cedula']; 
   $login_usu = $row3['login']; 

} 

if($tipo_operacion == 4){
   $nom_operacion = "RESTABLECER CONTRASEÑA DE ";
   $icono = "password.png";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT cod_usuario, nombre, apellidos, cedula, login FROM usuario WHERE cod_usuario = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $nombre  = $row3['nombre'];  
   $apellidos = $row3['apellidos']; 
   $cedula    = $row3['cedula']; 
   $login_usu = $row3['login']; 

 }

if($tipo_operacion == 5){
   $nom_operacion = "ASIGNAR MUNICIPIO A ";
   $icono = "asig_municipio.png";

   ////CONSULTAMOS LOS VALORES ACTUALES DEL REGISTRO
   $instruccion3 = "SELECT cod_usuario, nombre, apellidos, cedula, login FROM usuario WHERE cod_usuario = $codigo";
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $row3 = mysql_fetch_array ($consulta3);

   $nombre  = $row3['nombre'];  
   $apellidos = $row3['apellidos']; 
   $cedula    = $row3['cedula']; 
   $login_usu = $row3['login']; 

 }   

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_usuario.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
 <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
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
   <td style='font-weight:bold; color: white'>Apellidos</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="apellido" size="30" value='<?php print("$apellidos");?>'></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Cedula</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" name="cedula" size="12" value='<?php print("$cedula");?>'></td>   
  </tr> 
  <tr>
   <td style='font-weight:bold; color: white'>Login</td>
   <td><img src="../imagenes/requerido.gif"><input type="text" <?php print("$disabled");?> name="login" size="10" value='<?php print("$login_usu");?>'></td>   
  </tr> 
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Registrar"></td>
  </tr>    
<?php
 }  
/////CONSTRUIMOS EL FORMULARIO PARA ENLAZAR LAS OPCIONES
if($tipo_operacion == 3){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione las opciones para el Usuario: $nombre $apellidos</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS OPCIONES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT opcion.id_opcion AS id_opcion, opcion.nombre AS nombre, opcion.cod_tipo_opcion AS cod_tipo_opcion, tipo_opcion.nombre AS nom_tipo_opcion
                     FROM opcion
                     INNER JOIN tipo_opcion ON tipo_opcion.cod_tipo_opcion = opcion.cod_tipo_opcion 
                     ORDER BY opcion.cod_tipo_opcion, opcion.id_opcion ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $id_opcion = $row6['id_opcion'];
        $nom_opcion = trim($row6['nombre']);
        $cod_tipo_opcion = $row6['cod_tipo_opcion'];
        $nom_tipo_opcion = trim($row6['nom_tipo_opcion']);

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Opción</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Tipo de Vista</td>");
          print("</tr>");           
          }
        $conta = $conta+1;
      
      if($cod_tipo_opcion != $cod_tipo_opcion_ant){
          print("<tr>"); 
          print("<td span class='presentacion' align='center' colspan='2'>$nom_tipo_opcion</td>");
          print("</tr>");         
        }
        $cod_tipo_opcion_ant = $cod_tipo_opcion;
        
        ////BUSCAMOS SI EL USUARIO YA TIENE LA OPCION ASOCIADA
        $instruccion6a = "SELECT id_opcion, cod_opcion_vista FROM usuario_opcion WHERE cod_usuario = $codigo AND id_opcion = $id_opcion";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $cod_opc_vista = $row6a['cod_opcion_vista'];        
        
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

          print("<td style=background:$color><input type='checkbox' $cad name='$id_opcion' value=".$id_opcion.">&nbsp[$id_opcion]&nbsp;&nbsp;-&nbsp;&nbsp;".$nom_opcion."</td>"); 
          
          ////BUSCAMOS LAS OPCIONES DE VISTA 
          print ("<TD style=background:$color><SELECT NAME='opcvista_$id_opcion'>"); 
          
          $instruccion_m = "SELECT cod_opcion_vista, nombre FROM opcion_vista ORDER BY cod_opcion_vista";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
    
          $row_m = mysql_fetch_array ($consulta_m); 
            
          $valdesc_m = "";
          $descp_m = "--";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
            do{ 
            
              if($row_m['cod_opcion_vista'] == $cod_opc_vista){
                print("<option value=".$row_m['cod_opcion_vista']." Selected>".$row_m['nombre']."</option>");
                
                }else{
                  print("<option value=".$row_m['cod_opcion_vista'].">".$row_m['nombre']."</option>");
                  }
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT></TD>");
                       
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }
     
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
     print("</tr>");     
}

if($tipo_operacion == 4){
 print("<tr>");
  print("<td style='font-weight:bold; font-size: 10pt; color: white'>");
   print("Desea restablecer la contraseña para el Usuario: [$login_usu] $nombre $apellidos");
  print("</td>");
 print("</tr>");
 print("<tr>");
  print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Restablecer'></td>");
 print("</tr>"); 
 }
 
////ASIGNAR MUNICIPIO A USUARIO 
if($tipo_operacion == 5){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione las Municipios que coordina el Usuario: $nombre $apellidos</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS OPCIONES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT cod_municipio, nombre FROM municipio ORDER BY cod_departamento, cod_municipio ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_municipio = $row6['cod_municipio'];
        $nom_municipio = strtoupper($row6['nombre']);

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Municipio</td>");
          print("</tr>");           
          }
        $conta = $conta+1;
        
        ////BUSCAMOS SI EL USUARIO YA TIENE EL MUNICIPIO ASOCIADO
        $instruccion6a = "SELECT cod_municipio, cod_usuario FROM usuario_municipio WHERE cod_usuario = $codigo AND cod_municipio = $cod_municipio";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $cod_municipio_aso = $row6a['cod_municipio'];        
        
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

          print("<td style=background:$color><input type='checkbox' $cad name='$cod_municipio' value=".$cod_municipio.">&nbsp[$cod_municipio]&nbsp;&nbsp;-&nbsp;&nbsp;".$nom_municipio."</td>"); 
          
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
