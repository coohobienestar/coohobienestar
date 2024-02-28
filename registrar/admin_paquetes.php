<?php
session_start();

include("../conexion/conectarbd.php");
$conexion=Conectarse();

include("../funciones/generales.php");

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");

////SACAMOS EL NOMBRE DEL INFORME
$url = $_SERVER['REQUEST_URI'];
$array = explode('/',$url);
//print("$array[3]"); ////EL NIVEL 3 ES EL NOMBRE DEL INFORME PARA VALIDAR QUE EL USUARIO TENGA ACCESO A ESE INFORME
$num_array = count($array);
$num_array = $num_array - 1;
 
$informe = $array[$num_array];
$separar = explode('?',$informe);
$informe = $separar[0];

$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag'];

 ////LLAMAMOS LA FUNCION QUE DEFINE EL PERFIL DE VISTA DE LA OPCION
 $opcion_vista = opcion_vista($informe,$cod_usuario,$conexion);
   
$registro = "SELECT opcion.ruta FROM usuario_opcion
             INNER JOIN usuario ON usuario.cod_usuario=usuario_opcion.cod_usuario
             INNER JOIN opcion ON opcion.id_opcion=usuario_opcion.id_opcion
             WHERE usuario.cod_usuario=$cod_usuario AND opcion.ruta like '%$informe%'";
$result = mysql_query($registro);
error_consulta($result,$registro);                           
    
    if($reg=mysql_fetch_array($result)){
       $autorizado=1;
      }
  if(($cod_usuario != '') && ($autorizado==1)){
    }else{
      die("<br>No ha iniciado una sesión O no puede acceder a esta pagina por su perfil.");
      } 

 $fecha = date("Ymd_His");

?>
<HTML LANG="es">

<HEAD>
<TITLE>PAQUETES</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion,vista){ 
    var url="../admin/operacion_paquetes.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion+"&vista="+vista;
    open(url,"_blank","Sizewindow,width=1200,height=700,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      codigo = document.forms.datechooser.codigo.value;
      nombre = document.forms.datechooser.nombre.value;
      
      window.location = 'admin_paquetes.php?codigo='+codigo+'&nombre='+nombre+'&pagina='+pagina;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<?php if($opcion_vista == 1){?>
 <td width='30%' style='font-weight:bold; color: #f4d359' align="center"><a href=javascript:operar_tabla(0,1,<?php echo"$opcion_vista"; ?>)><img src='../imagenes/guardar.png' width='32' height='32' border='0' alt='Nuevo Registro'>&nbsp;&nbsp;Registrar Paquete</a></td>
<?php } ?>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<div align="Center">
<?PHP
session_start();

  $autorizado=0;

  $registro = "SELECT opcion.ruta FROM usuario_opcion
               INNER JOIN usuario ON usuario.cod_usuario=usuario_opcion.cod_usuario
               INNER JOIN opcion ON opcion.id_opcion=usuario_opcion.id_opcion
               WHERE usuario.cod_usuario=$cod_usuario AND opcion.ruta like '%$informe%'";
  $result = mysql_query($registro);
  error_consulta($result,$registro);                           
      
      if($reg=mysql_fetch_array($result)){
         $autorizado=1;
        }
    if(($cod_usuario != '') && ($autorizado==1)){
      }else{
        die("<br>No ha iniciado una sesión 1 O no puede acceder a esta pagina por su perfil.");
        } 

      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $codigo = $_REQUEST['codigo'];
      $nombre = $_REQUEST['nombre'];
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_paquete) AS cuenta FROM 0as_paquete_aseo";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $cuenta = $row3['cuenta'];  
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='90%' align='left'>");
      print ("<FORM NAME='datechooser' ACTION='admin_paquetes.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'><TD>Código ");
      print ("<INPUT type='text' size='5' name='codigo' value=''></TD>");
      print ("<TD>Nombre ");
      print ("<INPUT type='text' name='nombre' value=''></TD>");
               
      ////MOSTRAMOS LAS PAGINAS
      if($num_paginas>0){
        print ("<TD>Pagina ");
        print ("<SELECT NAME='pagina'>"); 
        
        $cont_pag = 1;
         
        $valdesc_p = 0;
        $descp_p = "--";
            print("<option value=".$valdesc_p.">".$descp_p."</option>");  
          do{ 
             print("<option value=".$cont_pag.">".$cont_pag."</option>");
             $cont_pag++;
          }while ($cont_pag<=$num_paginas); 
          print("</SELECT></TD>");   
        }
        
      print ("<TD><INPUT TYPE='submit' NAME='consultar' VALUE='Consultar'></TD>");  
      print ("</FORM>");
      print ("</TD></TR><tr><td>&nbsp;</td></tr></table>");
     
      ////GENERAMOS LA CONDICION DE LA CONSULTA
      $condicion = " WHERE ";
      
      if($codigo != ''){
         $condicion2 = $condicion2. "0as_paquete_aseo.cod_paquete = '$codigo' AND ";
        }
      if($nombre != ''){
         $condicion2 = $condicion2. "0as_paquete_aseo.nombre like '%$nombre%' AND ";
        }      
        
        $condicion2 = substr($condicion2, 0, -4);
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = "";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT 0as_paquete_aseo.cod_paquete AS cod_paquete, 0as_paquete_aseo.nombre AS nom_paquete 
                      FROM 0as_paquete_aseo 
                      $condicion_final 
                      ORDER BY 0as_paquete_aseo.cod_paquete   
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='95%'>";
      $hojaExcel.="<TR><TH colspan='7'><center>PAQUETES</center></TH></TR>";       
      $hojaExcel.="<TH><center>Código</center></TH>";
      $hojaExcel.="<TH><center>Nombre</center></TH>";
    if($opcion_vista == 1){  
      $hojaExcel.="<TH><center>Editar</center></TH>";
      $hojaExcel.="<TH><center>Componentes</center></TH>";
      }
    if($opcion_vista == 2){  
      $hojaExcel.="<TH><center>Ver Paquete</center></TH>";
      } 
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_paquete'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_paquete'] . "</TD>";   
          if($opcion_vista == 1){  
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_paquete],2,$opcion_vista)><img src='../imagenes/editar.png' width='14' height='14' border='0' alt='Editar'></a> </center></TD>";
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_paquete],3,$opcion_vista)><img src='../imagenes/componente.png' width='14' height='14' border='0' alt='Modificar Componentes'></a> </center></TD>"; 
            }
          if($opcion_vista == 2){  
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_paquete],3,$opcion_vista)><img src='../imagenes/ver_detalle.png' width='14' height='14' border='0' alt='Ver Detalles'></a> </center></TD>";
            }  
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/paquetes"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
          $fp=fopen($sfile,"w"); 
          fwrite($fp,$hojaExcel); 
          fclose($fp);
          echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 
      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible</span></center>");

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
