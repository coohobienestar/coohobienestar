<?php
session_start();

ini_set('max_execution_time',0);
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

?>
<HTML LANG="es">  
<HEAD>
<TITLE>LISTA DE EMPAQUE CARNE</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
    ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function generar_informe(cod_programacion,cod_municipio,cod_centro_acopio,tipo_minuta,tipo_informe){ 
    var url="../admin/operacion_listaempaque_carne.php?cod_programacion="+cod_programacion+"&cod_municipio="+cod_municipio+"&cod_centro_acopio="+cod_centro_acopio+"&tipo_minuta="+tipo_minuta+"&tipo_informe="+tipo_informe;
    open(url,"_blank","Sizewindow,width=1200,height=700,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.programacion.selectedIndex;
      programacion = document.forms.datechooser.programacion.options[i].value;    
           
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      window.location = 'inf_listaempaque_carne.php?programacion='+programacion+'&pagina='+pagina;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
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
      $cod_programacion = $_REQUEST['programacion'];
      
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_escuela) AS cuenta FROM escuela";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $cuenta = $row3['cuenta'];
      
      $cuenta = 0; ////NO SE MUESTRA PAGINACION EN ESTE FORMULARIO  
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='99%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='inf_listaempaque_carne.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación ");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion'>");                

      $instruccion = "SELECT DISTINCT programacion.cod_programacion AS cod_programacion, ciclo.nombre AS nombre
                      FROM programacion 
                      INNER JOIN ciclo ON ciclo.cod_ciclo = programacion.cod_ciclo 
                      WHERE programacion.estado = 1
                      ORDER BY programacion.cod_programacion DESC";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_programacion'].">[".$row['cod_programacion']."] - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");   
        
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
      
      if($cod_programacion != ''){
         $condicion2 = $condicion2. "calculo_redondeado_escuela.cod_programacion = '$cod_programacion' AND ";
        }
   
        $condicion2 = substr($condicion2, 0, -4);         
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = " WHERE calculo_redondeado_escuela.cod_programacion = 0";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT DISTINCT calculo_redondeado_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio,
                      calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                      calculo_redondeado_escuela.cod_centro_acopio AS cod_centro_acopio, centro_acopio.nombre AS nom_centro_acopio
                      FROM calculo_redondeado_escuela
                      INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio  
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio   
                      $condicion_final  
                      ORDER BY calculo_redondeado_escuela.cod_tipo_minuta, calculo_redondeado_escuela.cod_centro_acopio, calculo_redondeado_escuela.cod_municipio                      
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel="<TABLE width='60%'>";
        $hojaExcel.="<TR><TH colspan='8'><center>LISTAS DE EMPAQUE CARNE -- [Programación: $cod_programacion]</center></TH></TR>";       
        $hojaExcel.="<TH><center>Municipio</center></TH>";
        $hojaExcel.="<TH><center>Ver Informe Municipio &nbsp;&nbsp;<a href=javascript:generar_informe($cod_programacion,0,0,0,1) title='Ver Todos'><img src='../imagenes/informe.png' width='24' height='24' border='0' alt='Generar Informe'></a></center></TH>";
        $hojaExcel.="<TH><center>Centro de Acopio</center></TH>";
        $hojaExcel.="<TH><center>Ver Informe Centro de Acopio&nbsp;&nbsp;<a href=javascript:generar_informe($cod_programacion,0,0,0,2) title='Ver Todos'><img src='../imagenes/informe_02.png' width='24' height='24' border='0' alt='Generar Informe'></a></center></TH>";
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
            
            $cod_tipo_minuta = $row2['cod_tipo_minuta'];
            $cod_centro_acopio = $row2['cod_centro_acopio'];
            
            if($cod_tipo_minuta != $ant_cod_tipo_minuta){
               $hojaExcel.="<TR>";
               $hojaExcel.="<TH colspan='6'><center>" . $row2[nom_tipo_minuta] ." "; 
               $hojaExcel.=" <a href=javascript:generar_informe($cod_programacion,$row2[cod_municipio],$row2[cod_centro_acopio],$cod_tipo_minuta,1) title='Ver Informe municipios'><img src='../imagenes/informe.png' width='22' height='22' border='0' alt='Generar Informe'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
               $hojaExcel.=" <a href=javascript:generar_informe($cod_programacion,$row2[cod_municipio],$row2[cod_centro_acopio],$cod_tipo_minuta,2) title='Ver Informe municipios'><img src='../imagenes/informe_02.png' width='22' height='22' border='0' alt='Generar Informe'></a></center></TH>";
               $hojaExcel.="</TR>";
              }
              $ant_cod_tipo_minuta = $cod_tipo_minuta;              
                                                       
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe($cod_programacion,$row2[cod_municipio],0,0,1) title='Ver Informe $row2[nom_municipio]'><img src='../imagenes/informe.png' width='18' height='18' border='0' alt='Generar Informe'></a> </center></TD>"; 
         
          if($cod_centro_acopio != $ant_cod_centro_acopio){            
             $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] . "</TD>";
             $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe($cod_programacion,0,$cod_centro_acopio,0,2) title='Ver Informe $row2[nom_centro_acopio]'><img src='../imagenes/informe_02.png' width='18' height='18' border='0' alt='Generar Informe'></a> || <a href=javascript:generar_informe($cod_programacion,0,$cod_centro_acopio,0,3) title='Ver Informe $row2[nom_centro_acopio]'><img src='../imagenes/informe_03.png' width='18' height='18' border='0' alt='Generar Informe'></a></center></TD>"; 
            }else{
              $hojaExcel.="<TD style=background:$color>&nbsp;&nbsp;</TD>";
              $hojaExcel.="<TD style=background:$color>&nbsp;&nbsp;</TD>";
              } 
             $ant_cod_centro_acopio = $cod_centro_acopio; 
            
            $hojaExcel.="</TR>";                
            
         }
         $hojaExcel.="</TABLE>"; 
         
            
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $fecha = date("Ymd_His");
          $sfile="../excel/inf_listasempaqueCarne"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
