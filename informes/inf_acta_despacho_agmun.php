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

?>
<HTML LANG="es">  
<HEAD>
<TITLE>LISTA DE ENTREGA</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
    ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function generar_informe_ad(cod_programacion,cod_programacion2,cod_programacion3,cod_programacion4,mun_o,mun_1,mun_2,mun_3,tipo_informe){ 
    var url="../admin/operacion_acta_des_agmun.php?cod_programacion="+cod_programacion+"&cod_programacion2="+cod_programacion2+"&cod_programacion3="+cod_programacion3+"&cod_programacion4="+cod_programacion4+"&mun_o="+mun_o+"&mun_1="+mun_1+"&mun_2="+mun_2+"&mun_3="+mun_3+"&tipo_informe="+tipo_informe;
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

      s = document.forms.datechooser.programacion4.selectedIndex;
      programacion4 = document.forms.datechooser.programacion4.options[s].value;       
      
      o = document.forms.datechooser.municipio_o.selectedIndex;
      municipio_o = document.forms.datechooser.municipio_o.options[o].value;        

      p = document.forms.datechooser.municipio_agr1.selectedIndex;
      municipio_agr1 = document.forms.datechooser.municipio_agr1.options[p].value;  

      q = document.forms.datechooser.municipio_agr2.selectedIndex;
      municipio_agr2 = document.forms.datechooser.municipio_agr2.options[q].value;

      r = document.forms.datechooser.municipio_agr3.selectedIndex;
      municipio_agr3 = document.forms.datechooser.municipio_agr3.options[r].value;
      
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      window.location = 'inf_acta_despacho_agmun.php?programacion='+programacion+'&programacion2='+programacion2+'&programacion3='+programacion3+'&programacion4='+programacion4+'&municipio_o='+municipio_o+'&pagina='+pagina+'&municipio_agr1='+municipio_agr1+'&municipio_agr2='+municipio_agr2+'&municipio_agr3='+municipio_agr3;
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
      $cod_centro_acopio = $_REQUEST['origen'];
      $nom_municipio = $_REQUEST['nom_municipio'];
      $municipio_o = $_REQUEST['municipio_o'];
      $municipio_agr1 = $_REQUEST['municipio_agr1'];
      $municipio_agr2 = $_REQUEST['municipio_agr2'];
      $municipio_agr3 = $_REQUEST['municipio_agr3'];
      
      if($cod_programacion2 == '') $cod_programacion2 = 0;
      if($cod_programacion3 == '') $cod_programacion3 = 0;
      if($cod_programacion4 == '') $cod_programacion4 = 0;
      if($municipio_agr2 == '') $municipio_agr2 = 0;
      if($municipio_agr3 == '') $municipio_agr3 = 0;
      
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
      print ("<FORM NAME='datechooser' ACTION='inf_acta_despacho_agmun.php' METHOD='POST'>");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");        

      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación 2 ");
      print ("<SELECT NAME='programacion2'>");                

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
        
      print ("<TR style='font-weight:bold; color: white'>");            
        
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Programación 3 ");
      print ("<SELECT NAME='programacion3'>");                

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
        
      print ("<TD>Programación 4 ");
      print ("<SELECT NAME='programacion4'>");                

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
                   
      print ("<TR style='font-weight:bold; color: white'>");          
      ////BUSCAMOS LOS MUNICIPIOS
      print ("<TD>Municipio Origen ");
      print ("<SELECT NAME='municipio_o'>");                

      $instruccion = "SELECT cod_municipio, nombre FROM municipio ORDER BY nombre";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_municipio'].">".$row['cod_municipio']." - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");
        
      ////BUSCAMOS LOS MUNICIPIOS
      print ("<TD>Municipio a Agrupar ");
      print ("<SELECT NAME='municipio_agr1'>");                

      $instruccion = "SELECT cod_municipio, nombre FROM municipio ORDER BY nombre";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_municipio'].">".$row['cod_municipio']." - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");

      ////BUSCAMOS LOS MUNICIPIOS
      print ("<TD>Municipio a Agrupar ");
      print ("<SELECT NAME='municipio_agr2'>");                

      $instruccion = "SELECT cod_municipio, nombre FROM municipio ORDER BY nombre";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_municipio'].">".$row['cod_municipio']." - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD></TR>");
        
      ////BUSCAMOS LOS MUNICIPIOS
      print ("<TR style='font-weight:bold; color: white'><TD>Municipio a Agrupar ");
      print ("<SELECT NAME='municipio_agr3'>");                

      $instruccion = "SELECT cod_municipio, nombre FROM municipio ORDER BY nombre";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_municipio'].">".$row['cod_municipio']." - ".$row['nombre']);
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");        

      print ("<TD><INPUT TYPE='submit' NAME='consultar' VALUE='Consultar'></TD>");       
      print ("</FORM>");
      print ("</TD></TR><tr><td>&nbsp;</td></tr></table>");
    
        
 if ($municipio_o != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO    
  $instruccion_mu ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_o";      
  $consulta_mu = mysql_query($instruccion_mu);
  error_consulta($consulta_mu,$instruccion_mu);
  $row_mu = mysql_fetch_array($consulta_mu);  
  
  $mun_o = $row_mu['nombre'];

  ////VERIFICAMOS Q EL MUNICIPIO ESTE EN LAS PROGRAMACIONES SELECCIONADAS
  $instruccion_meo ="SELECT COUNT(calculo_redondeado_escuela.cod_programacion) AS cuenta
                     FROM calculo_redondeado_escuela
                     WHERE calculo_redondeado_escuela.cod_municipio = $municipio_o AND 
                          (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2 OR
                           calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4)";      
  $consulta_meo = mysql_query($instruccion_meo);
  error_consulta($consulta_meo,$instruccion_meo);
  $row_meo = mysql_fetch_array($consulta_meo);  
  
  $meo = $row_meo['cuenta'];  
  }  
  
 if ($municipio_agr1 != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO 1 a agrupar   
  $instruccion_mu1 ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_agr1";      
  $consulta_mu1 = mysql_query($instruccion_mu1);
  error_consulta($consulta_mu1,$instruccion_mu1);
  $row_mu1 = mysql_fetch_array($consulta_mu1);  
  
  $mun_1 = $row_mu1['nombre']; 
  
  ////VERIFICAMOS Q EL MUNICIPIO ESTE EN LAS PROGRAMACIONES SELECCIONADAS
  $instruccion_me1 ="SELECT COUNT(calculo_redondeado_escuela.cod_programacion) AS cuenta
                    FROM calculo_redondeado_escuela
                    WHERE calculo_redondeado_escuela.cod_municipio = $municipio_agr1 AND 
                         (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2 OR
                          calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4)";      
  $consulta_me1 = mysql_query($instruccion_me1);
  error_consulta($consulta_me1,$instruccion_me1);
  $row_me1 = mysql_fetch_array($consulta_me1);  
  
  $me1 = $row_me1['cuenta'];   
  }
  
 if ($municipio_agr2 != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO 1 a agrupar   
  $instruccion_mu2 ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_agr2";      
  $consulta_mu2 = mysql_query($instruccion_mu2);
  error_consulta($consulta_mu2,$instruccion_mu2);
  $row_mu2 = mysql_fetch_array($consulta_mu2);  
  
  $mun_2 = $row_mu2['nombre'];   

  ////VERIFICAMOS Q EL MUNICIPIO ESTE EN LAS PROGRAMACIONES SELECCIONADAS
  $instruccion_me2 ="SELECT COUNT(calculo_redondeado_escuela.cod_programacion) AS cuenta
                    FROM calculo_redondeado_escuela
                    WHERE calculo_redondeado_escuela.cod_municipio = $municipio_agr2 AND 
                         (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2 OR
                          calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4)";      
  $consulta_me2 = mysql_query($instruccion_me2);
  error_consulta($consulta_me2,$instruccion_me2);
  $row_me2 = mysql_fetch_array($consulta_me2);  
  
  $me2 = $row_me2['cuenta'];
                            
  }else{
    $me2 = 1; ////CUANDO ESTE VIENE VACIO SE PONE EN UNO PARA QUE EL IF DE ABAJO PASE
    }
    
 if ($municipio_agr3 != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO 1 a agrupar   
  $instruccion_mu3 ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_agr3";      
  $consulta_mu3 = mysql_query($instruccion_mu3);
  error_consulta($consulta_mu3,$instruccion_mu3);
  $row_mu3 = mysql_fetch_array($consulta_mu3);  
  
  $mun_3 = $row_mu3['nombre'];   

  ////VERIFICAMOS Q EL MUNICIPIO ESTE EN LAS PROGRAMACIONES SELECCIONADAS
  $instruccion_me3 ="SELECT COUNT(calculo_redondeado_escuela.cod_programacion) AS cuenta
                    FROM calculo_redondeado_escuela
                    WHERE calculo_redondeado_escuela.cod_municipio = $municipio_agr3 AND 
                         (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2 OR
                          calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4)";      
  $consulta_me3 = mysql_query($instruccion_me3);
  error_consulta($consulta_me3,$instruccion_me3);
  $row_me3 = mysql_fetch_array($consulta_me3);  
  
  $me3 = $row_me3['cuenta'];
                            
  }else{
    $me3 = 1; ////CUANDO ESTE VIENE VACIO SE PONE EN UNO PARA QUE EL IF DE ABAJO PASE
    }    
           
       if($cod_programacion != '' && $municipio_o !='' && $municipio_agr1 !=''){ 
        if($meo > 0 && $me1 > 0 && $me2 > 0 && $me3 > 0){
          ////ENCABEZADO DE LA TABLA DE RESULTADOS
          $hojaExcel="<TABLE width='80%'>";
          $hojaExcel.="<TR><TH colspan='9'><center>ACTAS DE DESPACHO AGRUPADA -- [Programación: $cod_programacion Y $cod_programacion2 Y $cod_programacion3 Y $cod_programacion4]</center></TH></TR>";       
          $hojaExcel.="<TH><center>Municipio Origen</center></TH>";
          $hojaExcel.="<TH><center>Municipio Agrupar 1</center></TH>";
          $hojaExcel.="<TH><center>Municipio Agrupar 2</center></TH>";
          $hojaExcel.="<TH><center>Municipio Agrupar 3</center></TH>";
          $hojaExcel.="<TH><center>Ver Informe</center></TH>";
          $hojaExcel.="</TR>";                        
        
              $hojaExcel.="<TR style=background:#D8D8D8>";
              $hojaExcel.="<TD>" . $mun_o. "</TD>";
              $hojaExcel.="<TD>" . $mun_1. "</TD>";
              $hojaExcel.="<TD>" . $mun_2. "</TD>";
              $hojaExcel.="<TD>" . $mun_3. "</TD>";
              $hojaExcel.="<TD><center><a href=javascript:generar_informe_ad($cod_programacion,$cod_programacion2,$cod_programacion3,$cod_programacion4,$municipio_o,$municipio_agr1,$municipio_agr2,$municipio_agr3,1)><img src='../imagenes/informe_03.png' width='18' height='18' border='0' alt='Generar Informe'></a> </center></TD>";
              $hojaExcel.="</TR>";
            $hojaExcel.="</TABLE>";  
           }else    
              print ("<center><span class='Estilo1'>Los municipio seleccionados no estan dentro de las programaciones seleccionadas... Por favor verifique</span></center>");
          }else
           print ("<center><span class='Estilo1'>No hay informacion disponible <br>Debe seleccionar por lo menos una Programación <br>y el Municipio Origen y un Municipio a Agrupar</span></center>");      
    echo $hojaExcel;

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
