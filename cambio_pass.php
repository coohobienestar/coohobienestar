<?php                                                                                                       
session_start();

$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag'];

if (!isset($_SESSION['login']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="menu_retorna.php" align="right"><img src="imagenes/retornar.png">&nbsp;Retornar</a> | <a href="logout.php"><img src="imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<form action="valida_cambio_pass.php" method="post">
 <table width="35%" height="25%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' width="100%" colspan="2"><center><img src="imagenes/password.png">&nbsp;CAMBIAR CONTRASEÑA</center></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>  
  <tr class="f_form_tabla">
   <td><span class="presentacion">&nbsp;&nbsp;Contraseña Anterior</span></td>
   <td><input type="password" name="con_anterior" size="20"><br></td>
  </tr>
  <tr class="f_form_tabla">
   <td><span class="presentacion">&nbsp;&nbsp;Contraseña Nueva</span></td>
   <td><input type="password" name="con_nueva" size="20"><br></td>   
  </tr>
  <tr class="f_form_tabla">
   <td><span class="presentacion">&nbsp;&nbsp;Repita la nueva Contraseña</span></td>
   <td><input type="password" name="con_nueva_rep" size="20"><br></td>   
  </tr> 
  <tr class="f_form_tabla">           
   <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Confirmar">
   </td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr> 
  <tr>
  <td>&nbsp;</td>
  </tr>     
  <tr>
   <td style='font-weight:bold; color: #FA5858; font-size: 11pt' align="center" width="100%" colspan="2"><strong>CONDICIONES DE LA CONTRASEÑA</strong></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>   
  <tr>
   <td style='font-weight:bold; color: #FA5858; font-size: 11pt' align="left" width="100%" colspan="2">1. La clave debe tener por lo menos 8 caracteres</td>
  </tr> 
  <tr>
   <td style='font-weight:bold; color: #FA5858; font-size: 11pt' align="left" width="100%" colspan="2">2. La clave no puede tener más de 16 caracteres</td>
  </tr>
  <tr>
   <td style='font-weight:bold; color: #FA5858; font-size: 11pt' align="left" width="100%" colspan="2">3. La clave debe tener por lo menos una letra minúscula</td>
  </tr> 
  <tr>
   <td style='font-weight:bold; color: #FA5858; font-size: 11pt' align="left" width="100%" colspan="2">4. La clave debe tener por lo menos una letra mayúscula</td>
  </tr>                    
  <tr>
   <td style='font-weight:bold; color: #FA5858; font-size: 11pt' align="left" width="100%" colspan="2">5. La clave debe tener por lo menos un caracter numérico</td>
  </tr>
 </table>  
</form>
</body>
</html>
