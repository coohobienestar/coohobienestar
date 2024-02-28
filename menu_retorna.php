<?php
session_start();

include("conexion/conectarbd.php");
$conexion=Conectarse(); 


if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar.");
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

////BUSCAMOS LA CLAVE PARA COMPRAR SI ES = AL LOGIN
$instruccion3 = "SELECT clave FROM usuario WHERE login = '$login' ";
$consulta3 = mysql_query ($instruccion3, $conexion);  
$row3 = mysql_fetch_array ($consulta3);
$clave_2 = $row3['clave'];

$login_md = md5($login);    

?>
<html>
<head>
<title>MENU PRINCIPAL</title>
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
<link rel="stylesheet" type="text/css" media="screen" href="estilos/menus.css" />
</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="cambio_pass.php"><img src="imagenes/password.png">&nbsp;Cambiar Contraseña</a> | <a href="logout.php"><img src="imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<?php
$login = trim($_SESSION['cod_usuario']);

$conexion=Conectarse(); 
 if($login_md != $clave_2){ 
   print("<div id='menuv'>");
    print("<ul>");
    ////GENERAMOS EL LISTADO DE OPCIONES DEL USUARIO
    $instruccion1 ="SELECT DISTINCT opcion.cod_tipo_opcion AS tipo, tipo_opcion.nombre AS nombre_menu   
                    FROM opcion 
                    INNER JOIN tipo_opcion ON tipo_opcion.cod_tipo_opcion=opcion.cod_tipo_opcion
                    INNER JOIN usuario_opcion ON usuario_opcion.id_opcion=opcion.id_opcion
                    WHERE usuario_opcion.cod_usuario='$login' 
                    GROUP BY opcion.cod_tipo_opcion, tipo_opcion.nombre
                    ORDER BY opcion.cod_tipo_opcion";
    $consulta1 = mysql_query ($instruccion1, $conexion); 

      for ($i=0;$i<mysql_num_rows ($consulta1); $i++){
       $row1 = mysql_fetch_array($consulta1);
       $tipo = $row1['tipo'];
       
       print("<span class='Estilo1'>".$row1['nombre_menu']."</span><br>");
       
           $instruccion2 ="SELECT opcion.ruta AS ruta, opcion.nombre AS nombre, opcion.cod_tipo_opcion AS tipo, tipo_opcion.nombre AS nombre_opcion 
                           FROM opcion 
                           INNER JOIN tipo_opcion ON tipo_opcion.cod_tipo_opcion=opcion.cod_tipo_opcion
                           INNER JOIN usuario_opcion ON usuario_opcion.id_opcion=opcion.id_opcion
                           WHERE usuario_opcion.cod_usuario='$login' AND tipo_opcion.cod_tipo_opcion='$tipo' 
                           ORDER BY opcion.cod_tipo_opcion";
            $consulta2 = mysql_query ($instruccion2, $conexion); 
            
             for ($j=0;$j<mysql_num_rows ($consulta2); $j++){
              $row2 = mysql_fetch_array($consulta2);
              print("<li><a href=".$row2['ruta'].">".$row2['nombre']."</a></li>"); 
             }               
       }
      print("</ul>"); 
     print("</div>");  
 }else{
  ?>
   <center><span class="Estilo1">Antes de continuar debe Cambiar su contrase�a...</span></center><br>
  <?php 
  }     
// Cerrar conexi�n
mysql_close ($conexion);    
?>
</body>
</html>
