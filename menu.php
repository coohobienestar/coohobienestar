<?php
session_start();               

include("conexion/conectarbd.php");
$conexion=Conectarse();     

////BUSCAMOS EL PARAMETRO DEL NUMERO DE REGITROS POR PAGINA
$instruccion3 = "SELECT valor FROM parametro WHERE nombre = 'numero_reg_pagina' ";
$consulta3 = mysql_query ($instruccion3, $conexion);  
$row3 = mysql_fetch_array ($consulta3);
$num_reg_pag = $row3['valor'];                           

 ////TOMAMOS LA CLAVE QUE DIGITO EL USUARIO Y LA CONVERTIMOS A MD5 PARA COMPARAR 
 $clave = md5($_REQUEST['clave']);
 $clave_2 = $_REQUEST['clave'];
 
////BUSCAMOS QUE EL USUARIO ESTE REGISTRADO Y LA CONTRASE�A SEA CORRECTA 
$instruccion_login = "SELECT * FROM usuario WHERE login = '$_REQUEST[login]' AND clave = '$clave_2'";
$consulta_login = mysql_query ($instruccion_login, $conexion); 
$nfilas_login = mysql_num_rows ($consulta_login); 
// echo($nfilas_login);

$row_login = mysql_fetch_array ($consulta_login); 

 if($nfilas_login > 0){  
    $identificacion = $row_login['cod_usuario'];
    
    ////PARAMETRIZAMOS LAS VARIABLES DE SESION
    $_SESSION['cod_usuario']=$row_login['cod_usuario'];
    $_SESSION['login']=$row_login['login'];
    $_SESSION['nombre']=$row_login['nombre'];
    $_SESSION['apellidos']=$row_login['apellidos'];
    $_SESSION['num_reg_pag']=$num_reg_pag;
    
    $login = $_SESSION['login'];
    $cod_usuario = $_SESSION['cod_usuario'];
    $nom_usuario = $_SESSION['nombre'];
    $ape_usuario = $_SESSION['apellidos'];
    $num_reg_pag = $_SESSION['num_reg_pag'];        
  }  

?>
<html>
<head>
<title>MENU PRINCIPAL</title>
<link rel="stylesheet" type="text/css" href="estilos/estilo.css">
<link rel="stylesheet" type="text/css" media="screen" href="estilos/menus.css" />
</head>
<body>

<?php

  
if($nfilas_login > 0){      
    ?>
      <table width='90%'>
      <tr>
      <td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
      <td width='30%' style='font-weight:bold; color: white' align="right"><a href="cambio_pass.php"><img src="imagenes/password.png">&nbsp;Cambiar Contrase�a</a> | <a href="logout.php"><img src="imagenes/exit.png">&nbsp;Cerrar sesi�n</a></td>
      </tr>
      </table>
      <br>
    <?php
   
  if($_REQUEST[login] != $clave_2){ 
    print("<div id='menuv'>");
     print("<ul>");

      ////GENERAMOS EL LISTADO DE OPCIONES DEL USUARIO
      $instruccion1 ="SELECT DISTINCT opcion.cod_tipo_opcion AS tipo, tipo_opcion.nombre AS nombre_menu   
                      FROM opcion 
                      INNER JOIN tipo_opcion ON tipo_opcion.cod_tipo_opcion=opcion.cod_tipo_opcion
                      INNER JOIN usuario_opcion ON usuario_opcion.id_opcion=opcion.id_opcion
                      WHERE usuario_opcion.cod_usuario='$identificacion' 
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
                           WHERE usuario_opcion.cod_usuario='$identificacion' AND tipo_opcion.cod_tipo_opcion='$tipo' 
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
 }else{
   ?>
   <center><span class="Estilo1">No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)</span></center><br>
   <META HTTP-EQUIV="Refresh" CONTENT="2;URL=index.php">
   <?php 
   }
   // Cerrar conexi�n
   mysql_close ($conexion);    

////BORRAMOS LOS ARCHIVOS DE EXCEL QUE SE HAN CREADO HACE MAS DE UNA SEMANA
$dia = time()-(7*24*60*60); //Te resta 7 dias
$fecha_actual = date("Y-m-d",$dia); 
$dir = "excel/";
$directorio=opendir($dir); 
while ($archivo = readdir($directorio)){
   if(is_file($dir.$archivo)){
     
     $fecha = date("Y-m-d", filemtime($dir.$archivo)); 
     
     if($fecha < $fecha_actual){
        unlink($dir.$archivo);
       }
   }
 }
closedir($directorio); 

////BORRAMOS LOS ARCHIVOS DE SQL QUE SE HAN CREADO HACE MAS DE UNA SEMANA
$dia = time()-(7*24*60*60); //Te resta 7 dias
$fecha_actual = date("Y-m-d",$dia); 
$dir = "backups/";
$directorio=opendir($dir); 
while ($archivo = readdir($directorio)){
   if(is_file($dir.$archivo)){
     
     $fecha = date("Y-m-d", filemtime($dir.$archivo)); 
     
     if($fecha < $fecha_actual){
        unlink($dir.$archivo);
       }
   }
 }
closedir($directorio);     
?>
  
</body>
</html>
