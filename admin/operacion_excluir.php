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

$codigo = $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];

if($tipo_operacion == 1){
   $nom_operacion = "EXCLUIR MUNICIPIOS ";
   $icono = "excluir_municipio.png";
 }
 
if($tipo_operacion == 2){
   $nom_operacion = "EXCLUIR ESCUELAS ";
   $icono = "excluir_escuela.png";
 }

if($tipo_operacion == 3){
   $nom_operacion = "EXCLUIR MENU PARA UNA ESCUELA ";
   $icono = "excluir_escuela.png";
 }

if($tipo_operacion == 4){
   $nom_operacion = "EXCLUIR MENU PARA UN MUNICIPIO ";
   $icono = "excluir_municipio.png";
 } 
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion");?> ESCUELAS</title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_excluir.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo' value='<?php print("$codigo");?>'>
 <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion");?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>   
  <?php
////GENERAMOS LOS CHECKBOX PARA EXCLUIR MUNICIPIO
     if($tipo_operacion == 1){
      print ("<tr><td style='font-weight:bold; color: white'>Seleccione los Municipios que desea exluir: </td></tr>");
      print ("<tr><td>&nbsp;</td></tr>");

      $instruccion6 = "SELECT municipio.cod_municipio AS cod_municipio, excluido_municipio.cod_municipio AS cod_municipio_e, municipio.nombre AS nom_municipio
                       FROM municipio 
                       LEFT JOIN excluido_municipio ON excluido_municipio.cod_municipio = municipio.cod_municipio 
                       WHERE municipio.cod_departamento = $codigo
                       UNION
                       SELECT municipio.cod_municipio, excluido_municipio.cod_municipio, municipio.nombre 
                       FROM excluido_municipio
                       LEFT JOIN municipio ON municipio.cod_municipio =  excluido_municipio.cod_municipio
                       WHERE municipio.cod_departamento = $codigo";
                       
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $row6 = mysql_fetch_array($consulta6);          
      $nfilas = mysql_num_rows ($consulta6);
      
       if($nfilas>0){
        do{           
          $cod_municipio   = trim($row6['cod_municipio']);
          $cod_municipio_e = trim($row6['cod_municipio_e']);
          $nom_municipio   = trim($row6['nom_municipio']);     
          
           print("<tr><td colspan=2><span class='presentacion'>");
           
           if($cod_municipio == $cod_municipio_e){
              print("<input type='checkbox' name='$cod_municipio' value=".$cod_municipio." checked>&nbsp;".$nom_municipio." "); 
             }
            if($cod_municipio_e == ''){
               print("<input type='checkbox' name='$cod_municipio' value=".$cod_municipio.">&nbsp;".$nom_municipio." "); 
              }
                      
           print("</td></tr>"); 
        
        }while ($row6 = mysql_fetch_array($consulta6));  
       }          
    }  

////GENERAMOS LOS CHECKBOX PARA EXCLUIR ESCUELAS
     if($tipo_operacion == 2){
      print ("<tr><td style='font-weight:bold; color: white'>Seleccione las escuelas que desea exluir: </td></tr>");
      print ("<tr><td>&nbsp;</td></tr>");

      $instruccion6 = "SELECT escuela.cod_escuela AS cod_escuela, excluido_escuela.cod_escuela AS cod_escuela_e, escuela.nombre AS nom_escuela
                       FROM escuela
                       LEFT JOIN excluido_escuela ON excluido_escuela.cod_escuela = escuela.cod_escuela
                       WHERE escuela.cod_municipio = $codigo
                       UNION
                       SELECT escuela.cod_escuela AS cod_escuela, excluido_escuela.cod_escuela AS cod_escuela_e, escuela.nombre AS nom_escuela
                       FROM excluido_escuela
                       LEFT JOIN escuela ON escuela.cod_escuela = excluido_escuela.cod_escuela
                       WHERE escuela.cod_municipio = $codigo";
                       
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $row6 = mysql_fetch_array($consulta6);
      $nfilas = mysql_num_rows ($consulta6);
      
       if($nfilas>0){
        do{           
          $cod_escuela   = trim($row6['cod_escuela']);
          $cod_escuela_e = trim($row6['cod_escuela_e']);
          $nom_escuela   = trim($row6['nom_escuela']);     
          
           print("<tr><td colspan=2><span class='presentacion'>");
           
           if($cod_escuela == $cod_escuela_e){
              print("<input type='checkbox' name='$cod_escuela' value=".$cod_escuela." checked>&nbsp;".$nom_escuela." "); 
             }
            if($cod_escuela_e == ''){
               print("<input type='checkbox' name='$cod_escuela' value=".$cod_escuela.">&nbsp;".$nom_escuela." "); 
              }
                      
           print("</td></tr>"); 
        
        }while ($row6 = mysql_fetch_array($consulta6));  
       }          
    }
    
 ////GENERAMOS LOS CHECKBOX PARA EXCLUIR LOS MENUS PARA LA ESCUELA
     if($tipo_operacion == 3){
      
       ////BUSCAMOS EL NOMBRE DE LA ESCUELA
       $sql = "SELECT nombre FROM escuela WHERE cod_escuela=$codigo";
       $result = mysql_query($sql);
       error_consulta($result,$sql); 
       
       $resultado = mysql_fetch_array ($result); 
      
       $nom_escuela = $resultado['nombre'];  
       
      print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione el menu que desea excluir para la escuela: $codigo - $nom_escuela </td></tr>");
      print ("<tr><td colspan='5'>&nbsp;</td></tr>");

      $instruccion6 = "SELECT menu.cod_menu AS cod_menu, menu.nombre AS nom_menu
                       FROM menu";
                       
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $row6 = mysql_fetch_array($consulta6);
      $nfilas = mysql_num_rows ($consulta6);
      
       if($nfilas>0){        
        do{           
          $cod_menu   = trim($row6['cod_menu']);
          $nom_menu   = trim($row6['nom_menu']);     
          
          ////BUSCAMOS SI EL MENU ESTA INSERTADO PARA LA ESCUELA
          $sql2 = "SELECT cod_menu, cod_tipo_minuta FROM excluido_escuela_menu WHERE cod_escuela=$codigo AND cod_menu = $cod_menu";
          $result2 = mysql_query($sql2);
          error_consulta($result2,$sql2); 
         
          $resultado2 = mysql_fetch_array ($result2); 
        
          $cod_menu_e = $resultado2['cod_menu'];
          $cod_tipo_minuta_e = $resultado2['cod_tipo_minuta'];  
          
           print("<tr><td colspan=2><span class='presentacion'>");  
           
           if($cod_menu == $cod_menu_e){
              print("<input type='checkbox' name='$cod_menu' value=".$cod_menu." checked>&nbsp;".$nom_menu." "); 
             }
            if($cod_menu_e == ''){
               print("<input type='checkbox' name='$cod_menu' value=".$cod_menu.">&nbsp;".$nom_menu." "); 
              }
            
         print ("</TD>");     
           
          ////BUSCAMOS LAS TIPOS DE MINUTA DE LA ESCUELA
          print ("<TD style=background:$color><SELECT NAME='tipo_$cod_menu'>"); 
          
          $instruccion_m = "SELECT DISTINCT tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre
                            FROM minuta_escuela 
                            INNER JOIN minuta ON minuta.cod_minuta = minuta_escuela.cod_minuta 
                            INNER JOIN tipo_minuta ON minuta.cod_tipo_minuta = tipo_minuta.cod_tipo_minuta
                            WHERE minuta_escuela.cod_escuela = $codigo";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
    
          $row_m = mysql_fetch_array ($consulta_m); 
            
          $valdesc_m = "0";
          $descp_m = "Todos los Tipos de Minuta";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
            do{ 
            
              if($row_m['cod_tipo_minuta'] == $cod_tipo_minuta_e){
                print("<option value=".$row_m['cod_tipo_minuta']." Selected>".$row_m['nombre']."</option>");
                
                }else{
                  print("<option value=".$row_m['cod_tipo_minuta'].">".$row_m['nombre']."</option>");
                  }
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT>");             
                      
           print("</td></tr>"); 
        
        }while ($row6 = mysql_fetch_array($consulta6));  
       }          
    }    
      
           
////GENERAMOS LOS CHECKBOX PARA EXCLUIR LOS MENUS PARA LA ESCUELA
     if($tipo_operacion == 4){
       ////BUSCAMOS EL NOMBRE DEL MUNICIPIO
       $sql = "SELECT nombre FROM municipio WHERE cod_municipio = $codigo";
       $result = mysql_query($sql);
       error_consulta($result,$sql); 
       
       $resultado = mysql_fetch_array ($result); 
      
       $nom_municipio = $resultado['nombre']; 
        
      print ("<tr><td style='font-weight:bold; color: white'>Seleccione el menu que desea excluir para el municipio: $codigo - $nom_municipio </td></tr>");
      print ("<tr><td>&nbsp;</td></tr>");

      $instruccion6 = "SELECT menu.cod_menu AS cod_menu, menu.nombre AS nom_menu
                       FROM menu";
                       
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $row6 = mysql_fetch_array($consulta6);
      $nfilas = mysql_num_rows ($consulta6);
      
       if($nfilas>0){        
        do{           
          $cod_menu   = trim($row6['cod_menu']);
          $nom_menu   = trim($row6['nom_menu']);     
          
          ////BUSCAMOS SI EL MENU ESTA INSERTADO PARA EL MUNICIPIO
          $sql2 = "SELECT cod_menu FROM excluido_municipio_menu WHERE cod_municipio = $codigo AND cod_menu = $cod_menu";
          $result2 = mysql_query($sql2);
          error_consulta($result2,$sql2); 
         
          $resultado2 = mysql_fetch_array ($result2); 
        
          $cod_menu_e = $resultado2['cod_menu'];  
          
           print("<tr><td colspan=2><span class='presentacion'>");  
           
           if($cod_menu == $cod_menu_e){
              print("<input type='checkbox' name='$cod_menu' value=".$cod_menu." checked>&nbsp;".$nom_menu." "); 
             }
            if($cod_menu_e == ''){
               print("<input type='checkbox' name='$cod_menu' value=".$cod_menu.">&nbsp;".$nom_menu." "); 
              }
                      
           print("</td></tr>"); 
        
        }while ($row6 = mysql_fetch_array($consulta6));  
       }          
    }     
  ?>  
  <tr>
   <td align="center" width="100%" colspan="5" height="34"><input type="submit" value="Actualizar"></td>
  </tr>            
 </table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>