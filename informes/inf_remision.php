<?php
session_start();

include("../conexion/conectarbd.php");
$conexion=Conectarse();

include("../funciones/generales.php");

/*
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  */

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
<TITLE>REMISIONES</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
    ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function generar_informe_ad(cod_programacion,cod_programacion2,cod_programacion3,cod_centro_a,cod_municipio,cod_departamento,tipo_minuta,cod_programacion4,cod_programacion5,cod_programacion6,cod_programacion7,cod_programacion8,cod_programacion9,cod_programacion10,cod_programacion11,tipo_informe){ 
    var url="../admin/operacion_remision.php?cod_programacion="+cod_programacion+"&cod_programacion2="+cod_programacion2+"&cod_programacion3="+cod_programacion3+"&cod_centro_a="+cod_centro_a+"&cod_municipio="+cod_municipio+"&cod_departamento="+cod_departamento+"&tipo_minuta="+tipo_minuta+"&cod_programacion4="+cod_programacion4+"&cod_programacion5="+cod_programacion5+"&cod_programacion6="+cod_programacion6+"&cod_programacion7="+cod_programacion7+"&cod_programacion8="+cod_programacion8+"&cod_programacion9="+cod_programacion9+"&cod_programacion10="+cod_programacion10+"&cod_programacion11="+cod_programacion11+"&tipo_informe="+tipo_informe;
    open(url,"_blank","Sizewindow,width=1200,height=700,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no")
    } 
      
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.programacion.selectedIndex;
      programacion = document.forms.datechooser.programacion.options[i].value;

      m = document.forms.datechooser.programacion2.selectedIndex;
      programacion2 = document.forms.datechooser.programacion2.options[m].value;  

      n = document.forms.datechooser.programacion3.selectedIndex;
      programacion3 = document.forms.datechooser.programacion3.options[n].value; 
      
      o = document.forms.datechooser.programacion4.selectedIndex;
      programacion4 = document.forms.datechooser.programacion4.options[o].value;      
      
      p = document.forms.datechooser.programacion5.selectedIndex;
      programacion5 = document.forms.datechooser.programacion5.options[p].value;

      q = document.forms.datechooser.programacion6.selectedIndex;
      programacion6 = document.forms.datechooser.programacion6.options[q].value;

      r = document.forms.datechooser.programacion7.selectedIndex;
      programacion7 = document.forms.datechooser.programacion7.options[r].value;
     
      s = document.forms.datechooser.programacion8.selectedIndex;
      programacion8 = document.forms.datechooser.programacion8.options[s].value;   
      
      t = document.forms.datechooser.programacion9.selectedIndex;
      programacion9 = document.forms.datechooser.programacion9.options[t].value;  
      
      u = document.forms.datechooser.programacion10.selectedIndex;
      programacion10 = document.forms.datechooser.programacion10.options[u].value;    

      v = document.forms.datechooser.programacion11.selectedIndex;
      programacion11 = document.forms.datechooser.programacion11.options[v].value;                 

      nom_municipio = document.forms.datechooser.nom_municipio.value; 
      
      tipo_minuta = document.forms.datechooser.tipo_minuta.value;          
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      window.location = 'inf_acta_despacho.php?programacion='+programacion+'&programacion2='+programacion2+'&programacion3='+programacion3+'&nom_municipio='+nom_municipio+'&pagina='+pagina+'&tipo_minuta='+tipo_minuta+'&programacion4='+programacion4+'&programacion5='+programacion5+'&programacion6='+programacion6+'&programacion7='+programacion7+'&programacion8='+programacion8+'&programacion9='+programacion9+'&programacion10='+programacion10+'&programacion11='+programacion11;
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
      $cod_programacion  = $_REQUEST['programacion'];
      $cod_programacion2 = $_REQUEST['programacion2'];
      $cod_programacion3 = $_REQUEST['programacion3'];
      $cod_programacion4 = $_REQUEST['programacion4'];
      $cod_programacion5 = $_REQUEST['programacion5'];
      $cod_programacion6 = $_REQUEST['programacion6'];
      $cod_programacion7 = $_REQUEST['programacion7'];
      $cod_programacion8 = $_REQUEST['programacion8'];
      $cod_programacion9 = $_REQUEST['programacion9'];
      $cod_programacion10 = $_REQUEST['programacion10'];
      $cod_programacion11 = $_REQUEST['programacion11'];
      $cod_centro_acopio = $_REQUEST['origen'];
      $nom_municipio = $_REQUEST['nom_municipio'];
      $tipo_minuta = $_REQUEST['tipo_minuta'];
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
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='inf_remision.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación 1 ");
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

      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación 2 ");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion2'>");                

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
        print("</SELECT></TD></TR>");             
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TR  style='font-weight:bold; color: white'><TD>Programación 3");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion3'>");                

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

      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación 4");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion4'>");                

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
        print("</SELECT></TD></TR>");  
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 5");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion5'>");                

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
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación 6");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion6'>");                

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
        print("</SELECT></TD></TR>");                

      ////BUSCAMOS LAS PROGRAMACIONES   7
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 7");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion7'>");                

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
        
      ////BUSCAMOS LAS PROGRAMACIONES   8 
      print ("<TD>Programación 8");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion8'>");                

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
        print("</SELECT></TD></TR>");  

////BUSCAMOS LAS PROGRAMACIONES   9
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 9");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion9'>");                

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
        
      ////BUSCAMOS LAS PROGRAMACIONES   10
      print ("<TD>Programación 10");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion10'>");                

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
        print("</SELECT></TD></TR>"); 

////BUSCAMOS LAS PROGRAMACIONES   11
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 11");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion11'>");                

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
        print("</SELECT></TD></TR>");                         
       
      ////BUSCAMOS LOS CENTROS DE ACOPIO
      print ("<TR style='font-weight:bold; color: white'><TD>Origen ");
      print ("<SELECT NAME='origen'>");                

      $instruccion = "SELECT cod_centro_acopio, nombre FROM centro_acopio";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_centro_acopio'].">[".$row['cod_centro_acopio']."] - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");        
        
      print ("<TD>Municipio ");
      print ("<INPUT type='text' name='nom_municipio' value=''></TD>");     
      
      print("<td>Separar por tipo de Minuta: <INPUT type='checkbox' name='tipo_minuta'>");    

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
          print("</SELECT></TD></TR>");   
        }
                
      print ("<TR><TD colspan='4'><center><INPUT TYPE='submit' NAME='consultar' VALUE='Consultar'></center></TD>");  
      print ("</FORM>");
      print ("</TD></TR><tr><td>&nbsp;</td></tr></table>");
     
      ////GENERAMOS LA CONDICION DE LA CONSULTA
      $condicion = " WHERE ";
      
      if($cod_programacion != '' && $cod_programacion2 != '' && $cod_programacion3 != '' && $cod_programacion4 != '' && $cod_programacion5 != '' && $cod_programacion6 != '' && $cod_programacion7 != '' && $cod_programacion8 != '' && $cod_programacion9 != '' && $cod_programacion10 != '' && $cod_programacion11 != ''){
         $condicion2 = $condicion2. "(calculo_requerimientos.cod_programacion = '$cod_programacion' OR  calculo_requerimientos.cod_programacion = '$cod_programacion2' OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR  calculo_requerimientos.cod_programacion = '$cod_programacion4' OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR  calculo_requerimientos.cod_programacion = '$cod_programacion6' OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR  calculo_requerimientos.cod_programacion = '$cod_programacion8' OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10' OR calculo_requerimientos.cod_programacion = '$cod_programacion11') AND ";
        } 
      if($cod_centro_acopio != ''){
         $condicion2 = $condicion2. "calculo_requerimientos.cod_centro_acopio = '$cod_centro_acopio' AND ";
        }             
      if($nom_municipio != ''){
         $condicion2 = $condicion2. "municipio.nombre like '%$nom_municipio%' AND ";
        } 
        
        $condicion2 = substr($condicion2, 0, -4);         
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = " WHERE calculo_requerimientos.cod_programacion = 0";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
            
       if($tipo_minuta == 'on'){
         ///EJECUTAMOS LA CONSULTA
         $instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento, calculo_requerimientos.cod_centro_acopio AS cod_centro_acopio, 
                                      centro_acopio.nombre AS nom_centro_acopio, calculo_requerimientos.cod_municipio AS cod_municipio, 
                                      municipio.nombre AS nom_municipio, calculo_requerimientos.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta
                         FROM calculo_requerimientos 
                         INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                         INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                         INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_requerimientos.cod_tipo_minuta 
                         $condicion_final  
                         ORDER BY calculo_requerimientos.cod_tipo_minuta, calculo_requerimientos.cod_centro_acopio, calculo_requerimientos.cod_municipio                      
                        ";  
        $tipo_informe = 3;                     
        }else{
         ///EJECUTAMOS LA CONSULTA
         $instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento, calculo_requerimientos.cod_centro_acopio AS cod_centro_acopio, 
                                      centro_acopio.nombre AS nom_centro_acopio, calculo_requerimientos.cod_municipio AS cod_municipio, 
                                      municipio.nombre AS nom_municipio
                         FROM calculo_requerimientos 
                         INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                         INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio 
                         $condicion_final  
                         ORDER BY calculo_requerimientos.cod_centro_acopio, calculo_requerimientos.cod_municipio                      
                        ";
         $tipo_informe = 1;              
          }  
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);

      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel="<TABLE width='80%'>";
        $hojaExcel.="<TR><TH colspan='8'><center>ACTAS DE DESPACHO -- [Programación: $cod_programacion Y $cod_programacion2 Y $cod_programacion3 Y $cod_programacion4 Y $cod_programacion5 Y $cod_programacion6 Y $cod_programacion7 Y $cod_programacion8 Y $cod_programacion9 Y $cod_programacion10 Y $cod_programacion11]</center></TH></TR>";       
        $hojaExcel.="<TH><center>Centro de Acopio &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,4) title='Totales por Centro de Acopio'><img src='../imagenes/informe.png' width='24' height='24' border='0' alt=''></a> &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,5) title='Totales por Centro de Acopio Despacho'><img src='../imagenes/informe_04.png' width='24' height='24' border='0' alt=''></a></center></TH>";
        $hojaExcel.="<TH><center>Centro Acopio -> Centro Acopio &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$tipo_informe) title='Imprimir Todos'><img src='../imagenes/informe_02.png' width='24' height='24' border='0' alt=''></a></center></TH>";
        $hojaExcel.="<TH><center>Municipio</center></TH>";
        $hojaExcel.="<TH><center>Centro Acopio -> Municipio &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,2) title='Imprimir Todos'><img src='../imagenes/informe_03.png' width='24' height='24' border='0' alt=''></a></center></TH>";
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
            if($tipo_minuta == 'on'){
               $cod_tipo_minuta = $row2['cod_tipo_minuta'];
               
             if($cod_tipo_minuta != $ant_cod_tipo_minuta){
                $hojaExcel.="<TR>";
                $hojaExcel.="<TH colspan='2'><center>" . $row2['nom_tipo_minuta'] ." &nbsp; <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,4) title='Totales por Centro de Acopio por Tipo de Minuta'><img src='../imagenes/informe.png' width='18' height='18' border='0' alt=''></a> &nbsp;&nbsp; <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,$row2[cod_departamento],$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,3)><img src='../imagenes/informe_02.png' width='22' height='22' border='0' alt='Generar Informe'></a></center></TH>"; 
                $hojaExcel.="<TH colspan='2'>&nbsp;</TH>";
                $hojaExcel.="</TR>";
                
                $caco_ant ="";
               }                           
             }else{
                $cod_tipo_minuta = 0;
               }

            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] ."</TD>"; 
            
           if($tipo_minuta != 'on'){ 
            if($row2[cod_centro_acopio] != $ca_ant){
             $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,$row2[cod_departamento],$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$tipo_informe)><img src='../imagenes/informe_02.png' width='18' height='18' border='0' alt=''></a> </center></TD>";            
             $ca_ant = $row2[cod_centro_acopio];
             }else{
               $hojaExcel.="<TD style=background:$color>&nbsp;</TD>";
               }
            }else{
               if($row2[cod_centro_acopio] != $caco_ant){
                  $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,$row2[cod_departamento],$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$tipo_informe)><img src='../imagenes/informe_02.png' width='18' height='18' border='0' alt=''></a> </center></TD>";            
                  $caco_ant = $row2[cod_centro_acopio]; 
               }else{
                 $hojaExcel.="<TD style=background:$color>&nbsp;</TD>";
               }
             }                                    
              $ant_cod_tipo_minuta =  $cod_tipo_minuta;
               
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],$row2[cod_municipio],$row2[cod_departamento],0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,2)><img src='../imagenes/informe_03.png' width='18' height='18' border='0' alt=''></a> </center></TD>";
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";    
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $fecha = date("Ymd_His");
          $sfile="../excel/remision"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
          $fp=fopen($sfile,"w"); 
          fwrite($fp,$hojaExcel); 
          fclose($fp);
          echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 

      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible <br>Debe seleccionar las 8 programaciones para ejecutar el informe</span></center>");

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
