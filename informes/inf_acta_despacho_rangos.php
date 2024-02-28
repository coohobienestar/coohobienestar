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
<TITLE>ACTA DE DESPACHO POR RANGOS DE PROGRAMACIONES</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
    ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function generar_informe_ad(cod_programacion,cod_programacion2,cod_programacion3,cod_centro_a,cod_municipio,cod_departamento,tipo_minuta,cod_programacion4,cod_programacion5,cod_programacion6,cod_programacion7,cod_programacion8,cod_programacion9,cod_programacion10,cod_programacion11,cod_programacion12,cod_programacion13,cod_programacion14,tipo_informe,categoria,periodo_entrega,cod_programacion15,cod_programacion16,cod_programacion17,cod_programacion18,cod_programacion19,cod_programacion20,cod_programacion21){ 
    var url="../admin/operacion_acta_des_rangos.php?cod_programacion="+cod_programacion+"&cod_programacion2="+cod_programacion2+"&cod_programacion3="+cod_programacion3+"&cod_centro_a="+cod_centro_a+"&cod_municipio="+cod_municipio+"&cod_departamento="+cod_departamento+"&tipo_minuta="+tipo_minuta+"&cod_programacion4="+cod_programacion4+"&cod_programacion5="+cod_programacion5+"&cod_programacion6="+cod_programacion6+"&cod_programacion7="+cod_programacion7+"&cod_programacion8="+cod_programacion8+"&cod_programacion9="+cod_programacion9+"&cod_programacion10="+cod_programacion10+"&cod_programacion11="+cod_programacion11+"&cod_programacion12="+cod_programacion12+"&cod_programacion13="+cod_programacion13+"&cod_programacion14="+cod_programacion14+"&tipo_informe="+tipo_informe+"&categoria="+categoria+"&periodo_entrega="+periodo_entrega+"&cod_programacion15="+cod_programacion15+"&cod_programacion16="+cod_programacion16+"&cod_programacion17="+cod_programacion17+"&cod_programacion18="+cod_programacion18+"&cod_programacion19="+cod_programacion19+"&cod_programacion20="+cod_programacion20+"&cod_programacion21="+cod_programacion21;
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

      w = document.forms.datechooser.programacion12.selectedIndex;
      programacion12 = document.forms.datechooser.programacion12.options[w].value;    
      
      x = document.forms.datechooser.programacion13.selectedIndex;
      programacion13 = document.forms.datechooser.programacion13.options[x].value;   
      
      y = document.forms.datechooser.programacion14.selectedIndex;
      programacion14 = document.forms.datechooser.programacion14.options[y].value;  
      
      z = document.forms.datechooser.programacion15.selectedIndex;
      programacion15 = document.forms.datechooser.programacion15.options[z].value; 
      
      a = document.forms.datechooser.programacion16.selectedIndex;
      programacion16 = document.forms.datechooser.programacion16.options[a].value;   
      
      b = document.forms.datechooser.programacion17.selectedIndex;
      programacion17 = document.forms.datechooser.programacion17.options[b].value;  
      
      c = document.forms.datechooser.programacion18.selectedIndex;
      programacion18 = document.forms.datechooser.programacion18.options[c].value;    
      
      d = document.forms.datechooser.programacion19.selectedIndex;
      programacion19 = document.forms.datechooser.programacion19.options[d].value; 
      
      e = document.forms.datechooser.programacion20.selectedIndex;
      programacion20 = document.forms.datechooser.programacion20.options[e].value;                                                        

      g = document.forms.datechooser.programacion21.selectedIndex;
      programacion21 = document.forms.datechooser.programacion21.options[g].value;                                                             

      nom_municipio = document.forms.datechooser.nom_municipio.value; 
      
      periodo_entrega = document.forms.datechooser.periodo_entrega.value;
      
      tipo_minuta = document.forms.datechooser.tipo_minuta.value;  
       
      categoria = document.forms.datechooser.categoria.value;        
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      window.location = 'inf_acta_despacho_rangos.php?programacion='+programacion+'&programacion2='+programacion2+'&programacion3='+programacion3+'&nom_municipio='+nom_municipio+'&pagina='+pagina+'&tipo_minuta='+tipo_minuta+'&programacion4='+programacion4+'&programacion5='+programacion5+'&programacion6='+programacion6+'&programacion7='+programacion7+'&programacion8='+programacion8+'&programacion9='+programacion9+'&programacion10='+programacion10+'&programacion11='+programacion11+'&programacion12='+programacion12+'&programacion13='+programacion13+'&programacion14='+programacion14+'&categoria='+categoria+'&programacion15='+programacion15+'&programacion16='+programacion16+'&programacion17='+programacion17+'&programacion18='+programacion18+'&programacion19='+programacion19+'&programacion20='+programacion20+'&programacion21='+programacion21;
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
      $cod_programacion12 = $_REQUEST['programacion12'];
      $cod_programacion13 = $_REQUEST['programacion13'];
      $cod_programacion14 = $_REQUEST['programacion14'];
      $cod_programacion15 = $_REQUEST['programacion15'];
      $cod_programacion16 = $_REQUEST['programacion16'];
      $cod_programacion17 = $_REQUEST['programacion17'];
      $cod_programacion18 = $_REQUEST['programacion18'];
      $cod_programacion19 = $_REQUEST['programacion19'];
      $cod_programacion20 = $_REQUEST['programacion20'];
      $cod_programacion21 = $_REQUEST['programacion21'];
      $cod_centro_acopio = $_REQUEST['origen'];
      $nom_municipio = $_REQUEST['nom_municipio'];
      $periodo_entrega = $_REQUEST['periodo_entrega'];
      $tipo_minuta = $_REQUEST['tipo_minuta'];
      $categoria = $_REQUEST['categoria'];
      $pagina = $_REQUEST['pagina'];
      
      if($categoria == ''){
          $categoria = 0;
        }
      
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
      print ("<FORM NAME='datechooser' ACTION='inf_acta_despacho_rangos.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TR style='font-weight:bold; color: RED'> ");
      print ("<TD colspan='2'><center>SELECCIONE PRIMERO LA PROGRAMACION MENOR DEL RANGO Y LUEGO LA MAYOR</center></TD>");
      print ("</TR> ");
      print ("<TR style='font-weight:bold; color: white'> ");
      print ("<TD>ENTRE ");       
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");    

      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Y ");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");             
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TR  style='font-weight:bold; color: white'><TD>ENTRE");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");         

      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Y");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");  
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TR style='font-weight:bold; color: white'><TD>ENTRE");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>"); 
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Y");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");                

      ////BUSCAMOS LAS PROGRAMACIONES   7
      print ("<TR style='font-weight:bold; color: white'><TD>ENTRE");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");   
        
      ////BUSCAMOS LAS PROGRAMACIONES   8 
      print ("<TD>Y");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");  

////BUSCAMOS LAS PROGRAMACIONES   9
      print ("<TR style='font-weight:bold; color: white'><TD>ENTRE");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");   
        
      ////BUSCAMOS LAS PROGRAMACIONES   10
      print ("<TD>Y");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>"); 
 /*
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT>"); 
        
////BUSCAMOS LAS PROGRAMACIONES   12
      print ("<TD>Programación 12");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion12'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");   
        
      ////BUSCAMOS LAS PROGRAMACIONES   13
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 13");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion13'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");     
        
     ////BUSCAMOS LAS PROGRAMACIONES   14
      print ("<TD>Programación 14");     
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion14'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");  
        
 ////BUSCAMOS LAS PROGRAMACIONES   15
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 15");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion15'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");     
        
     ////BUSCAMOS LAS PROGRAMACIONES   16
      print ("<TD>Programación 16");     
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion16'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");  
        
 ////BUSCAMOS LAS PROGRAMACIONES   17
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 17");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion17'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");     
        
     ////BUSCAMOS LAS PROGRAMACIONES   18
      print ("<TD>Programación 18");     
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion18'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");  
        
 ////BUSCAMOS LAS PROGRAMACIONES   19
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 19");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion19'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");     
        
     ////BUSCAMOS LAS PROGRAMACIONES   20
      print ("<TD>Programación 20");     
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion20'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");   
        
 ////BUSCAMOS LAS PROGRAMACIONES   21
      print ("<TR style='font-weight:bold; color: white'><TD>Programación 21");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='programacion21'>");                

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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");     
  */    
      print("<TD></TD></TR>");                                                                        
       
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
           print("<option value=".$row['cod_centro_acopio'].">".$row['cod_centro_acopio']." - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");        
        
      print ("<TD>Municipio ");
      print ("<INPUT type='text' name='nom_municipio' value=''></TD></TR>");     
      
      print("<TR style='font-weight:bold; color: white'><td>Separar por tipo de Minuta: <INPUT type='checkbox' name='tipo_minuta'>");       
      
      ////PONEMOS LA OPCION DE FILTRAR POR CATEGORIA
      print ("<TD>Filtrar solo Categoria ");
      print ("<SELECT NAME='categoria'>");                       

      $instruccion = "SELECT cod_categoria_ingrediente, nombre FROM categoria_ingrediente";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_categoria_ingrediente'].">".$row['cod_categoria_ingrediente']." - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");  
        
      print ("<TR TR style='font-weight:bold; color: white'><TD>Periodo de Entrega ");
      print ("<INPUT type='text' name='periodo_entrega' size='50' value=''></TD></TR>");           

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
      
      /////SE PONEN EN CERO PARA PODER ENVIAR EL VALOR EN LA FUNCION generar_informe_ad
      if( $cod_programacion == '') $cod_programacion  = 0;
      if( $cod_programacion2 == '') $cod_programacion2 = 0;
      if( $cod_programacion3 == '') $cod_programacion3 = 0;
      if( $cod_programacion4 == '') $cod_programacion4 = 0;
      if( $cod_programacion5 == '') $cod_programacion5 = 0;
      if( $cod_programacion6 == '') $cod_programacion6 = 0;
      if( $cod_programacion7 == '') $cod_programacion7 = 0;
      if( $cod_programacion8 == '') $cod_programacion8 = 0;
      if( $cod_programacion9 == '') $cod_programacion9 = 0;
      if( $cod_programacion10 == '') $cod_programacion10 = 0;
      if( $cod_programacion11 == '') $cod_programacion11 = 0;
      if( $cod_programacion12 == '') $cod_programacion12 = 0;
      if( $cod_programacion13 == '') $cod_programacion13 = 0;
      if( $cod_programacion14 == '') $cod_programacion14 = 0;     
      if( $cod_programacion15 == '') $cod_programacion15 = 0; 
      if( $cod_programacion16 == '') $cod_programacion16 = 0; 
      if( $cod_programacion17 == '') $cod_programacion17 = 0; 
      if( $cod_programacion18 == '') $cod_programacion18 = 0; 
      if( $cod_programacion19 == '') $cod_programacion19 = 0; 
      if( $cod_programacion20 == '') $cod_programacion20 = 0; 
      if( $cod_programacion21 == '') $cod_programacion21 = 0;  
     
      ////GENERAMOS LA CONDICION DE LA CONSULTA
      $condicion = " WHERE ";               
      
      if(($cod_programacion <= $cod_programacion2) && ($cod_programacion3 <= $cod_programacion4) && ($cod_programacion5 <= $cod_programacion6) && ($cod_programacion7 <= $cod_programacion8) && ($cod_programacion9 <= $cod_programacion10)){
   
        $condicion2 = $condicion2. "(calculo_requerimientos.cod_programacion BETWEEN '$cod_programacion' AND '$cod_programacion2' 
                                   OR calculo_requerimientos.cod_programacion BETWEEN '$cod_programacion3' AND '$cod_programacion4' 
                                   OR calculo_requerimientos.cod_programacion BETWEEN '$cod_programacion5' AND '$cod_programacion6' 
                                   OR calculo_requerimientos.cod_programacion BETWEEN '$cod_programacion7' AND '$cod_programacion8' 
                                   OR calculo_requerimientos.cod_programacion BETWEEN '$cod_programacion9' AND '$cod_programacion10') AND ";
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
      
       if($categoria != '0'){ 
        $instruccion3 = "SELECT nombre FROM categoria_ingrediente WHERE cod_categoria_ingrediente='$categoria'";
        $consulta3 = mysql_query ($instruccion3, $conexion);  
        $row3 = mysql_fetch_array ($consulta3);
     
        $nom_categoria_ingrediente = $row3['nombre'];    
        
         $txt_categoria = " || Filtrada Categoria: <strong>$nom_categoria_ingrediente</strong>";
        }
            

        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel="<TABLE width='80%'>";
        $hojaExcel.="<TR><TH colspan='8'><center>ACTAS DE DESPACHO RANGOS -- [Programación: ENTRE $cod_programacion Y $cod_programacion2 || ENTRE $cod_programacion3 Y $cod_programacion4 || ENTRE $cod_programacion5 Y $cod_programacion6 || ENTRE $cod_programacion7 Y $cod_programacion8 || ENTRE $cod_programacion9 Y $cod_programacion10] $txt_categoria </center></TH></TR>";       
        $hojaExcel.="<TH><center>Centro de Acopio &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,4,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Totales por Centro de Acopio'><img src='../imagenes/informe.png' width='24' height='24' border='0' alt=''></a> &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,5,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Totales por Centro de Acopio Despacho'><img src='../imagenes/informe_04.png' width='24' height='24' border='0' alt=''></a>&nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,8,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Informe de productos por dia'><img src='../imagenes/informe_05.png' width='24' height='24' border='0' alt=''></a></center></TH>";
        $hojaExcel.="<TH><center>Centro Acopio -> Centro Acopio &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,$tipo_informe,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Imprimir Todos'><img src='../imagenes/informe_02.png' width='24' height='24' border='0' alt=''></a></center></TH>";
        $hojaExcel.="<TH><center>Municipio</center></TH>";
        $hojaExcel.="<TH><center>Centro Acopio -> Municipio &nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,2,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Imprimir Todos'><img src='../imagenes/informe_03.png' width='24' height='24' border='0' alt=''></a></center></TH>";
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
                $hojaExcel.="<TH colspan='2'><center>" . $row2['nom_tipo_minuta'] ." &nbsp; <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,0,$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,4,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Totales por Centro de Acopio por Tipo de Minuta'><img src='../imagenes/informe.png' width='18' height='18' border='0' alt=''></a> &nbsp;&nbsp; <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,0,0,$row2[cod_departamento],$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,3,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe_02.png' width='22' height='22' border='0' alt='Generar Informe'></a></center></TH>"; 
                $hojaExcel.="<TH colspan='2'>&nbsp;</TH>";
                $hojaExcel.="</TR>";
                
                $caco_ant ="";
               }                           
             }else{
                $cod_tipo_minuta = 0;
               }

            $hojaExcel.="<TR>";
            
            if($row2[cod_centro_acopio] != $ca_ant){
               $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] ."&nbsp;&nbsp;<a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,0,0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,9,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21) title='Informe de productos por dia para cada C.A.'><img src='../imagenes/informe_05.png' width='18' height='18' border='0' alt=''></a></TD>"; 
               }else{
               $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] ."</TD>";                  
                  }
               
           if($tipo_minuta != 'on'){ 
            if($row2[cod_centro_acopio] != $ca_ant){
             $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,$row2[cod_departamento],$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,$tipo_informe,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe_02.png' width='18' height='18' border='0' alt=''></a> || <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,$row2[cod_departamento],0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,6,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe.png' width='18' height='18' border='0' alt=''></a> || <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,$row2[cod_departamento],0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,7,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe_04.png' width='18' height='18' border='0' alt=''></a></center></TD>";            
             $ca_ant = $row2[cod_centro_acopio];
             }else{
               $hojaExcel.="<TD style=background:$color>&nbsp;</TD>";
               }
            }else{
               if($row2[cod_centro_acopio] != $caco_ant){
                  $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],0,$row2[cod_departamento],$cod_tipo_minuta,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,$tipo_informe,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe_02.png' width='18' height='18' border='0' alt=''></a></center></TD>";            
                  $caco_ant = $row2[cod_centro_acopio]; 
               }else{
                 $hojaExcel.="<TD style=background:$color>&nbsp;</TD>";
               }
             }                                    
              $ant_cod_tipo_minuta =  $cod_tipo_minuta;
               
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],$row2[cod_municipio],$row2[cod_departamento],0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,2,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe_03.png' width='18' height='18' border='0' alt=''></a> | <a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$row2[cod_centro_acopio],$row2[cod_municipio],$row2[cod_departamento],0,$cod_programacion4,$cod_programacion5,$cod_programacion6,$cod_programacion7,$cod_programacion8,$cod_programacion9,$cod_programacion10,$cod_programacion11,$cod_programacion12,$cod_programacion13,$cod_programacion14,10,$categoria,'$periodo_entrega',$cod_programacion15,$cod_programacion16,$cod_programacion17,$cod_programacion18,$cod_programacion19,$cod_programacion20,$cod_programacion21)><img src='../imagenes/informe_04.png' width='18' height='18' border='0' alt=''></a></center></TD>";
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";    
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $fecha = date("Ymd_His");
          $sfile="../excel/actas_despacho_rangos"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
          $fp=fopen($sfile,"w"); 
          fwrite($fp,$hojaExcel); 
          fclose($fp);
          echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 

      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible <br>Debe seleccionar todas las programaciones para ejecutar el informe <br> Recuerde seleccionar primero la programacion menor y luego la mayor para cada rango</span></center>");

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
