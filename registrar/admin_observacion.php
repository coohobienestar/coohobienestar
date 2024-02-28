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
      die("<br>No ha iniciado una sesi�n O no puede acceder a esta pagina por su perfil.");
      } 

 $fecha = date("Ymd_His");
?>
<HTML LANG="es">

<HEAD>
<TITLE>OBSERVACION</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,observacion,tipo_operacion){ 
    var url="../admin/operacion_observacion.php?codigo="+codigo+"&observacion="+observacion+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=800,height=400,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.programacion.selectedIndex;
      programacion = document.forms.datechooser.programacion.options[i].value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'admin_observacion.php?programacion='+programacion+'&pagina='+pagina;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesi�n</a></td>
</tr>
</table>
<br>
<div align="Center">
<?php                        
      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $cod_programacion = $_REQUEST['programacion'];
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_municipio) AS cuenta FROM municipio";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $cuenta = $row3['cuenta'];  
      
      $cuenta = 0; /////NO SE MUESTRA PAGINACION
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_observacion.php' METHOD='POST'>");
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
         $condicion2 = $condicion2. "observacion.cod_programacion = $cod_programacion AND ";
        }
       
        $condicion2 = substr($condicion2, 0, -4);
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = " WHERE observacion.cod_observacion = 0";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT observacion.cod_observacion AS cod_observacion, observacion.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio, 
                             observacion.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela, observacion.cod_tipo_minuta AS cod_tipo_minuta, 
                             tipo_minuta.nombre AS nom_tipo_minuta, observacion.observacion_lista_entrega AS observacion_lista_entrega, 
                             observacion.observacion_control_es AS observacion_control_es, observacion.intercambio AS intercambio
                      FROM observacion 
                      LEFT JOIN municipio ON municipio.cod_municipio = observacion.cod_municipio
                      LEFT JOIN escuela ON escuela.cod_escuela = observacion.cod_escuela
                      LEFT JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = observacion.cod_tipo_minuta
                      $condicion_final 
                      ORDER BY observacion.cod_observacion
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      if($cod_programacion != '' && $opcion_vista == 1){
        $hojaExcel2.="<TABLE width='90%'>";
        $hojaExcel2.="<TR><TH colspan='9'><center>REGISTRAR OBSERVACION PARA</center></TH></TR>";
        $hojaExcel2.="<TR><TH><center>Programación</center></TH>";
        $hojaExcel2.="<TH><center>Intercambio</center></TH>";
        $hojaExcel2.="<TH><center>Municipio</center></TH>";

        $hojaExcel2.="<TH><center>Escuela</center></TH></TR>";
        $hojaExcel2.="<TR><TH><center> <a href=javascript:operar_tabla($cod_programacion,0,1) title='Registrar Observación Programaci�n'><img src='../imagenes/observacion.png' width='24' height='24' border='0' alt=''></a> </center></TH>";
        $hojaExcel2.="<TH><center> <a href=javascript:operar_tabla($cod_programacion,0,7) title='Registrar Observación Intercambio'><img src='../imagenes/observacion.png' width='24' height='24' border='0' alt=''></a> </center></TH>";
        $hojaExcel2.="<TH><center> <a href=javascript:operar_tabla($cod_programacion,0,2) title='Registrar Observación Municipio'><img src='../imagenes/observacion.png' width='24' height='24' border='0' alt=''></a> </center></TH>";
        $hojaExcel2.="<TH><center> <a href=javascript:operar_tabla($cod_programacion,0,3) title='Registrar Observación Escuela'><img src='../imagenes/observacion.png' width='24' height='24' border='0' alt=''></a> </center></TH></TR>";
        $hojaExcel2.="</TABLE>";
        $hojaExcel2.="<br>";
        
        echo $hojaExcel2;
        }
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel.="<TABLE width='80%'>";
      $hojaExcel.="<TR><TH colspan='13'><center>OBSERVACIONES &nbsp;&nbsp;&nbsp;<a href=javascript:operar_tabla($cod_programacion,0,6) title='Duplicar Observaciones en Otra Programaci�n'><img src='../imagenes/duplicar_obs.png' width='24' height='24' border='0' alt=''></a></center></TH></TR>";       
      $hojaExcel.="<TH><center>#</center></TH>";
      $hojaExcel.="<TH><center>Programacion</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Municipio</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Escuela</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Tipo Minuta</center></TH>";
      $hojaExcel.="<TH colspan='1'><center>Intercambio</center></TH>";
      $hojaExcel.="<TH><center>Observación Lista Entrega</center></TH>";
      $hojaExcel.="<TH><center>Observación Control E/S</center></TH>";
    if($opcion_vista == 1){
      $hojaExcel.="<TH><center>Editar</center></TH>";
      $hojaExcel.="<TH><center>Eliminar</center></TH>";
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_observacion'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $cod_programacion . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['intercambio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['observacion_lista_entrega'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['observacion_control_es'] . "</TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($cod_programacion,$row2[cod_observacion],4) title='Editar Observaci�n'><img src='../imagenes/editar.png' width='14' height='14' border='0' alt=''></a> </center></TD>"; 
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($cod_programacion,$row2[cod_observacion],5) title='Eliminar Observaci�n'><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt=''></a> </center></TD>";
            } 
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/Municipios"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
          $fp=fopen($sfile,"w"); 
          fwrite($fp,$hojaExcel); 
          fclose($fp);
          echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 
      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible</span></center>");

////Cerrar conexi�n
mysql_close ($conexion);
?>
</div>
</body>
</html>
