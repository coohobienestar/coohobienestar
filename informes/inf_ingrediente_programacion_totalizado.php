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
<TITLE>CONSUMO DE INGREDIENTE POR PROGRAMACION Y PROGRAMA TOTALIZADO</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.programacion.selectedIndex;
      programacion = document.forms.datechooser.programacion.options[i].value; 
 
      j = document.forms.datechooser.ingrediente.selectedIndex;     
      ingrediente = document.forms.datechooser.ingrediente.options[j].value; 
      
      l = document.forms.datechooser.municipio.selectedIndex;     
      municipio = document.forms.datechooser.municipio.options[l].value;       

      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      window.location = 'inf_ingrediente_programacion_totalizado.php?ingrediente='+ingrediente+'&pagina='+pagina;
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
      $ingrediente = $_REQUEST['ingrediente'];
      $municipio = $_REQUEST['municipio'];
      
      if($ingrediente == '') $ingrediente = 0;
      
      if($municipio == '') $municipio = 0;
      
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
      print ("<FORM NAME='datechooser' ACTION='inf_ingrediente_programacion_totalizado.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
        
      ////BUSCAMOS LOS INGREDIENTES
      print ("<TD>Ingrediente ");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='ingrediente'>");                

      $instruccion = "SELECT cod_ingrediente, nombre FROM ingrediente ORDER BY nombre";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_ingrediente'].">".$row['nombre']."</option>");  
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
      
      if($ingrediente != ''){
         $condicion2 = $condicion2. "calculo_redondeado_escuela.cod_ingrediente = '$ingrediente' AND ";
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
      $instruccion2 ="SELECT calculo_redondeado_escuela.cod_programacion AS cod_programacion, calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, 
                             ingrediente.nombre AS nom_ingrediente, calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, 
                             unidad_medida.nombre AS nom_unidad_medida, programacion.fecha_inicial AS fecha_inicial, programacion.fecha_final AS fecha_final,
                             ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN tipo_minuta ON calculo_redondeado_escuela.cod_tipo_minuta = tipo_minuta.cod_tipo_minuta 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida
                      INNER JOIN programacion ON programacion.cod_programacion = calculo_redondeado_escuela.cod_programacion
                      INNER JOIN ingrediente_unidad_entrega_consulta ON ingrediente.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND  unidad_medida.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      $condicion_final 
                      GROUP BY calculo_redondeado_escuela.cod_programacion, calculo_redondeado_escuela.cod_ingrediente, calculo_redondeado_escuela.cod_tipo_minuta,
                               calculo_redondeado_escuela.cod_unidad_medida";                 
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel="<TABLE width='95%'>";
        $hojaExcel.="<TR><TH colspan='11'><center>INGREDIENTE POR PROGRAMACION Y PROGRAMA TOTALIZADO -- [Ingrediente: $ingrediente]</center></TH></TR>";       
        $hojaExcel.="<TH><center>Programacion</center></TH>";
        $hojaExcel.="<TH><center>Fecha Inicial</center></TH>";
        $hojaExcel.="<TH><center>Fecha Final</center></TH>";
        $hojaExcel.="<TH><center>Codigo Geminus</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Ingreciente</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Tipo Minuta</center></TH>";
        $hojaExcel.="<TH><center>Cantidad</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Unidad de Medida</center></TH>";
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
            
            ////SUMAMOS LA CANTIDAD TOTAL
            $instruccion_q = "SELECT SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad_redondeada 
                              FROM calculo_redondeado_escuela
                              WHERE calculo_redondeado_escuela.cod_programacion = $row2[cod_programacion] AND
                                    calculo_redondeado_escuela.cod_ingrediente = $row2[cod_ingrediente] AND
                                    calculo_redondeado_escuela.cod_tipo_minuta = $row2[cod_tipo_minuta] AND
                                    calculo_redondeado_escuela.cod_unidad_medida = $row2[cod_unidad_medida]";
            $consulta_q = mysql_query ($instruccion_q, $conexion);  
            $row_q = mysql_fetch_array ($consulta_q);
            $suma_cantidad = $row_q['cantidad_redondeada'];     
            
            $hojaExcel.="<TR>"; 
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_programacion'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_inicial'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_final'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['codigo_geminus'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_ingrediente'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_ingrediente'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $suma_cantidad . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_unidad_medida'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_unidad_medida'] . "</TD>";
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";    
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $fecha = date("Ymd_His");
          $sfile="../excel/Ingrediente_programacion_programa"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
