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
$elemento = $_GET['elemento'];
$destino= $_GET['destino'];
$tipo_operacion = $_GET['tipo_operacion'];

////TIPO OPERACION 5 BORRA UNA SOLA EXCLUSION
if($tipo_operacion == 5){
   $icono = "borrar.png";
 }
////TIPO OPERACION 6 BORRA TODAS LAS EXCLUSIONES
if($tipo_operacion == 6){
   $icono = "borrar_todos.png";
 }

////TIPO OPERACION 6 LIMPIA LAS EXCLUSIONES DE TODAS LAS TABLAS
if($tipo_operacion == 7){
   $nom_operacion = "BORRAR TODAS LAS EXCLUSIONES";
   $icono = "limpiar_exc.png";
 }   
 
if($destino == 1){
   $nom_operacion = "BORRAR ESCUELA EXCLUIDA DE LA PROGRAMACION";
 }  

if($destino == 2){
   $nom_operacion = "BORRAR MENU EXCLUIDOS PARA ESCUELAS DE LA PROGRAMACION";
 }  
 
if($destino == 3){
   $nom_operacion = "BORRAR MUNICIPIO EXCLUIDO DE LA PROGRAMACION";
 }

if($destino == 4){
   $nom_operacion = "BORRAR MENU EXCLUIDOS PARA MUNICIPIOS DE LA PROGRAMACION";
 }  
     

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_borra_excluir.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo' value='<?php print("$codigo");?>'>
<input type='hidden' name='elemento' value='<?php print("$elemento");?>'>
<input type='hidden' name='destino' value='<?php print("$destino");?>'>
 <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="2"><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion");?></strong></td>
  </tr>
  <tr>
   <td colspan="2">
  <?php
////SI LA TABLA ES excluido_escuela **********************************************************************************************************************  
   if($destino == 1){
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_escuela.cod_escuela = $codigo ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }       
       
    ////BUSCAMOS LOS DATOS DE LA ESCUELA A QUITAR DE LA EXCLUSION  
    $instruccion2 ="SELECT excluido_escuela.cod_escuela AS cod_escuela, escuela.nombre AS nombre
                    FROM excluido_escuela
                    INNER JOIN escuela ON escuela.cod_escuela = excluido_escuela.cod_escuela
                    $condicion  
                   ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
      
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='100%' align='center'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Escuelas excluidas</center></TH></TR>";       
        $hojaExcel.="<TH><center>Código</center></TH>";
        $hojaExcel.="<TH><center>Nombre</center></TH>";
        $hojaExcel.="</TR>";

          $color = '';
    
             for ($i=0; $i<$nfilas; $i++){
                ////DEFINIMOS EL COLOR DE LA FILA
                $resto = $i%2;
                
                if($resto==0){
                   $color = '#D8D8D8';
                  }
                if($resto!=0){
                   $color = '#848484';
                  }              
    
                ////ESCRIBIMOS LOS RESULTADOS
                $row2 = mysql_fetch_array($consulta2);
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="</TR>"; 
               }

            $hojaExcel.="</TABLE>";            
            
         echo $hojaExcel;
      }
    }
  
////SI LA TABLA ES excluido_escuela_menu ********************************************************************************************************************
  if($destino == 2){
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_escuela_menu.cod_escuela = $codigo AND excluido_escuela_menu.cod_menu = $elemento ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       } 
      
      $instruccion2 ="SELECT excluido_escuela_menu.cod_escuela AS cod_escuela, escuela.nombre AS nombre, excluido_escuela_menu.cod_menu AS cod_menu,
                             menu.nombre AS nom_menu
                      FROM excluido_escuela_menu
                      INNER JOIN escuela ON escuela.cod_escuela = excluido_escuela_menu.cod_escuela
                      INNER JOIN menu ON menu.cod_menu= excluido_escuela_menu.cod_menu
                      $condicion
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='100%' align='center'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Menus Excluidos de Escuelas</center></TH></TR>";       
        $hojaExcel.="<TH colspan='2'><center>Escuela</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Menu</center></TH>";
        $hojaExcel.="</TR>";

          $color = '';
    
             for ($i=0; $i<$nfilas; $i++){
                ////DEFINIMOS EL COLOR DE LA FILA
                $resto = $i%2;
                
                if($resto==0){
                   $color = '#D8D8D8';
                  }
                if($resto!=0){
                   $color = '#848484';
                  }              
    
                ////ESCRIBIMOS LOS RESULTADOS
                $row2 = mysql_fetch_array($consulta2);
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_menu'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nom_menu'] . "</TD>";
                $hojaExcel.="</TR>"; 
              }
            $hojaExcel.="</TABLE>"; 
            
          echo $hojaExcel;   
        }   
   }   
 
////SI LA TABLA ES excluido_municipio ********************************************************************************************************************
  if($destino == 3){
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_municipio.cod_municipio = $codigo ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }     

      ////BUCAMOS LOS MUNICIPIOS EXCLUIDOS
      $instruccion2 ="SELECT excluido_municipio.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                      FROM excluido_municipio
                      INNER JOIN municipio ON municipio.cod_municipio = excluido_municipio.cod_municipio
                      $condicion
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='100%' align='center'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Municipios Excluidos</center></TH></TR>";       
        $hojaExcel.="<TH><center>Código</center></TH>";
        $hojaExcel.="<TH><center>Nombre</center></TH>";
        $hojaExcel.="</TR>";

          $color = '';
    
             for ($i=0; $i<$nfilas; $i++){
                ////DEFINIMOS EL COLOR DE LA FILA
                $resto = $i%2;
                
                if($resto==0){
                   $color = '#D8D8D8';
                  }
                if($resto!=0){
                   $color = '#848484';
                  }              
    
                ////ESCRIBIMOS LOS RESULTADOS
                $row2 = mysql_fetch_array($consulta2);
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="</TR>"; 
              }
            $hojaExcel.="</TABLE>";  
        }        
      echo $hojaExcel;      
 }  

////SI LA TABLA ES excluido_municipio_menu ********************************************************************************************************************
  if($destino == 4){
     if($tipo_operacion == 5){
        $condicion = " WHERE excluido_municipio_menu.cod_municipio = $codigo AND excluido_municipio_menu.cod_menu = $elemento ";
       }
     if($tipo_operacion == 6){
        $condicion = "";
       }     

      $instruccion2 ="SELECT excluido_municipio_menu.cod_municipio AS cod_municipio, municipio.nombre AS nombre, excluido_municipio_menu.cod_menu AS cod_menu,
                             menu.nombre AS nom_menu
                      FROM excluido_municipio_menu
                      INNER JOIN municipio ON municipio.cod_municipio = excluido_municipio_menu.cod_municipio
                      INNER JOIN menu ON menu.cod_menu= excluido_municipio_menu.cod_menu
                      $condicion
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='100%' align='center'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Menus Excluidos de Municipios</center></TH></TR>";       
        $hojaExcel.="<TH colspan='2'><center>Municipio</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Menu</center></TH>";
        $hojaExcel.="</TR>";

          $color = '';
    
             for ($i=0; $i<$nfilas; $i++){
                ////DEFINIMOS EL COLOR DE LA FILA
                $resto = $i%2;
                
                if($resto==0){
                   $color = '#D8D8D8';
                  }
                if($resto!=0){
                   $color = '#848484';
                  }              
    
                ////ESCRIBIMOS LOS RESULTADOS
                $row2 = mysql_fetch_array($consulta2);
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_menu'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nom_menu'] . "</TD>";
                $hojaExcel.="</TR>"; 
              }
            $hojaExcel.="</TABLE>"; 
        }        
        
  echo $hojaExcel;   
 } 
 
if($tipo_operacion == 7){
  $hojaExcel.="<TABLE width='100%' align='center'>";
  $hojaExcel.="<TR><TH><center>Va a eliminar todas las Exclusiones existentes</center></TH></TR>";       
  $hojaExcel.="</TR>";
 } 
 
  ?> 
   </td>
  </tr> 
  <tr> 
   <td>
    &nbsp;
   </td>
  </tr> 
  <tr>
   <td align="center" width="100%" colspan="6" height="34"><input type="submit" value="Borrar" onclick='return confirm("¿Esta seguro que desea eliminar esta información?")'></td>
  </tr>            
 </table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
