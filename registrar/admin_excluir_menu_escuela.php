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
<TITLE>EXCLUIR MENU DE ESCUELAS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_excluir.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=650,height=600,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.c_acopio.selectedIndex;
      c_acopio = document.forms.datechooser.c_acopio.options[i].value;
      
      j = document.forms.datechooser.municipio.selectedIndex;
      municipio = document.forms.datechooser.municipio.options[j].value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      codigo = document.forms.datechooser.codigo.value;
      nombre = document.forms.datechooser.nombre.value;
      
      window.location = 'admin_excluir_menu_escuela.php?c_acopio='+c_acopio+'&codigo='+codigo+'&nombre='+nombre+'&municipio='+municipio+'&pagina='+pagina;
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
<?php

      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $codigo = $_REQUEST['codigo'];
      $nombre = $_REQUEST['nombre'];
      $c_acopio = $_REQUEST['c_acopio'];
      $municipio = $_REQUEST['municipio'];
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_escuela) AS cuenta FROM escuela";
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
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_excluir_menu_escuela.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'><TD>Código ");
      print ("<INPUT type='text' name='codigo' value=''></TD>");
      print ("<TD>Nombre ");
      print ("<INPUT type='text' size='5' name='nombre' value=''></TD>");

      ////BUSCAMOS LOS CENTROS DE ACOPIO
      print ("<TD>Centro de Acopio ");
      print ("<SELECT NAME='c_acopio'>");                

      $instruccion = "SELECT cod_centro_acopio, nombre FROM centro_acopio ORDER BY cod_centro_acopio";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_centro_acopio'].">[".$row['cod_centro_acopio']."] - ".$row['nombre']."</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");          
        
      ////BUSCAMOS LOS MUNICIPIOS
      print ("<TD>Municipio ");
      print ("<SELECT NAME='municipio'>"); 
      
      $instruccion_m = "SELECT cod_municipio, nombre FROM municipio ORDER BY cod_municipio";
      $consulta_m = mysql_query ($instruccion_m, $conexion);
      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_municipio'].">[".$row_m['cod_municipio']."] - ".$row_m['nombre']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
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
      
      if($codigo != ''){
         $condicion2 = $condicion2. "escuela.cod_escuela like '%$codigo%' AND ";
        }
      if($nombre != ''){
         $condicion2 = $condicion2. "escuela.nombre like '%$nombre%' AND ";
        } 
      if($c_acopio != ''){
         $condicion2 = $condicion2. "escuela.cod_centro_acopio = '$c_acopio' AND ";
        }   
      if($municipio != ''){
         $condicion2 = $condicion2. "escuela.cod_municipio = '$municipio' AND ";
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
      $instruccion2 =" SELECT escuela.cod_escuela AS cod_escuela, escuela.nombre AS esc_nombre, escuela.cod_centro_acopio AS cod_centro_acopio, 
                              centro_acopio.nombre AS nom_centro_acopio, escuela.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio
                       FROM escuela 
                       INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = escuela.cod_centro_acopio 
                       INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                       $condicion_final 
                       ORDER BY cod_escuela   
                       $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='60%'>";
      $hojaExcel.="<TR><TH colspan='5'><center>ESCUELAS</center></TH></TR>";       
      $hojaExcel.="<TH><center>Código</center></TH>";
      $hojaExcel.="<TH><center>Nombre</center></TH>";
      $hojaExcel.="<TH><center>Centro de Acopio</center></TH>";
      $hojaExcel.="<TH><center>Municipio</center></TH>";
    if($opcion_vista == 1){   
      $hojaExcel.="<TH><center>Menu a Excluir</center></TH>";
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['esc_nombre'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_centro_acopio'] . " - ". $row2['nom_centro_acopio'] ."</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . " - ". $row2['nom_municipio'] ."</TD>"; 
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_escuela],3)><img src='../imagenes/excluir_escuela.png' width='14' height='14' border='0' alt='Excluir'></a> </center></TD>"; 
            }
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
   
      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible</span></center>");

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
