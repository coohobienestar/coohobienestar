<?php
include("conexion/conectarbd.php");
$conexion=Conectarse();     

////BUSCAMOS EL PARAMETRO DEL ESTADO DEL SERVIDOR
$instruccion3 = "SELECT valor FROM parametro WHERE nombre = 'estado_servidor' ";
$consulta3 = mysql_query ($instruccion3, $conexion);  
$row3 = mysql_fetch_array ($consulta3);
$estado_servidor = $row3['valor'];  

if($estado_servidor == 1){     
?>
<html>
<?xml version="1.0" encoding="UTF-8"?>
<body>
<title>COOHOBIENESTAR</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
</head>
<body>
	  <table width="100%" height="50%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <!-- -->
          <td height="60" align="left"><center><img src="imagenes/logo_cc.png"></center></td>          
        </tr>
        <tr>
          <td height="350" align="center" valign="middle"><br>
           <table width="25%" border="0" align="center" cellpadding="0" cellspacing="0">
            <form action="menu.php" method="post">
            <tr>
            <td align="center" width="100%" colspan="2" ><span class="presentacion2"><img src="imagenes/usuario.png">&nbsp;<strong>INICIAR SESION</strong></span></td>
            </tr>
            <tr class="f_form_tabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr class="f_form_tabla">
            <td><span class="presentacion">&nbsp;&nbsp;Usuario</span></td>
            <td><input type="text" name="login" size="20"><br></td>
            </tr>
            <tr class="f_form_tabla">
            <td><span class="presentacion">&nbsp;&nbsp;Contraseña</span></td>
            <td><input type="password" name="clave" size="20"><br></td>
            </tr>
            <tr class="f_form_tabla">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr class="f_form_tabla">           
            <td align="center" width="100%" colspan="2" height="34"><input type="submit" value="Ingresar"></td>
            </tr>
            </form> 
           </table> 
          </td>
        </tr>
        <tr>
          <td height="40" align="left" background="imagenes/pie_pagina.gif">
		  	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><strong><span class="Estilo2">Un desarrollo a la medida por</span> <span class="Estilo3">Pablo Alejandro Moreno Duque</span></strong></td>
            </tr>
          </table>
		      </td>
        </tr>
      </table>
</body>
</html>
<?php
}else{
?>

<html>
<body>
<title>COOHOBIENESTAR</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
</head>
<body>
	  <table width="100%" height="50%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <!-- -->
          <td height="60" align="left"><center><img src="imagenes/logo_cc.png"></center></td>          
        </tr>
        <tr>
          <td height="350" align="center" valign="middle"><br>
           <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
            <!-- -->
             <td height="60" align="left"><center><span class="presentacion2"><strong>ACTUALMENTE ESTAMOS ACTUALIZANDO LA INFORMACI�N INTENTE EN UNOS MINUTOS POR FAVOR.</strong></span></center></td>          
            </tr>
           </table> 
          </td>
        </tr>
        <tr>
          <td height="40" align="left" background="imagenes/pie_pagina.gif">
		  	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><strong><span class="Estilo2">Un desarrollo a la medida por</span> <span class="Estilo3">Pablo Alejandro Moreno Duque</span></strong></td>
            </tr>
          </table>
		      </td>
        </tr>
      </table>
</body>
</html>
<?php
}     
?>