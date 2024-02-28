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

$cod_programacion = $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = "PROGRAMACION";

if($tipo_operacion == 1){
   $nom_operacion = "OBSERVACIONES ";
   $icono = "observacion.png";
 }

if($tipo_operacion == 2){
   $nom_operacion = "ACTIVAR / DESACTIVAR ";
   $icono = "act_desact.png";
 }

if($tipo_operacion == 3){
   $nom_operacion = "ELIMINAR ";
   $icono = "borrar.png";
 } 
  
 
?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_programacion.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$cod_programacion");?>'>
 <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
    </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
    </tr>  
  <?php
  if($tipo_operacion == 1){    
    ////BUSCAMOS LAS OBSERVACIONES QUE TUVO LA PROGRAMACION
    $instruccion2 ="SELECT DISTINCT programacion_observacion.observacion AS observacion 
                    FROM programacion_observacion
                    WHERE programacion_observacion.cod_programacion = $cod_programacion
                   ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
      
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='70%' align='center'>";
        //$hojaExcel.="<TR><TH colspan='6'><center>Escuelas excluidas</center></TH></TR>";       
        $hojaExcel.="<TH><center>Observación</center></TH>";
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
                $hojaExcel.="<TD style=background:$color>" . $row2['observacion'] . "</TD>";
                $hojaExcel.="</TR>"; 
               }

            $hojaExcel.="</TABLE>";            
            
         echo $hojaExcel;
      }
    } 
      
  if($tipo_operacion == 2){ 
    ////BUSCAMOS EL ESTADO ACTUAL DE LA PROGRAMACION
    $instruccion2 ="SELECT DISTINCT estado FROM programacion WHERE cod_programacion = $cod_programacion";     
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $row2 = mysql_fetch_array($consulta2);    

    $estado = $row2['estado'];
    
     if($estado == 1){
       $estado_mostrar = " DESACTIVAR ";      
      }else{
         $estado_mostrar = " ACTIVAR ";
        }
        
      echo"<TR>"; 
      echo"<td align='center' width='100%' colspan='2' style='font-weight:bold; color: white'>Desea $estado_mostrar la programación $cod_programacion</td>";
      echo"</TR>";
      echo"<TR>";
      echo"<td align='center' width='100%' colspan='2'>&nbsp;</td>";
      echo"</TR>"; 
      echo"<TR>";
      echo"<td align='center' width='100%' colspan='2' height='34'><input type='submit' value='Registrar' onclick='return confirm('¿La información a registrar esta completa y correcta? \n Por favor verifique...')'></td>";
      echo"</TR>";
    }   

  if($tipo_operacion == 3){ 
      echo"<TR>"; 
      echo"<td align='center' width='100%' colspan='2' style='font-weight:bold; color: white'>¿Esta seguro de eliminar las programación $cod_programacion definitivamente?</td>";
      echo"</TR>";
      echo"<TR>";
      echo"<td align='center' width='100%' colspan='2'>&nbsp;</td>";
      echo"</TR>"; 
      echo"<TR>";
      echo"<td align='center' width='100%' colspan='2' height='34'><input type='submit' value='Registrar' onclick='return confirm('¿La información a registrar esta completa y correcta? \n Por favor verifique...')'></td>";
      echo"</TR>";
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
