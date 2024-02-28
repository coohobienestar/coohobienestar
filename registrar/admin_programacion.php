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
<TITLE>PROGRAMACIONES</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_programacion.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=600,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.ciclo.selectedIndex;
      ciclo = document.forms.datechooser.ciclo.options[i].value;        
   
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      codigo = document.forms.datechooser.codigo.value;
      
      e = document.forms.datechooser.estado.selectedIndex;
      estado = document.forms.datechooser.estado.options[e].value;      
      
      window.location = 'admin_escuela.php?ciclo='+ciclo+'&codigo='+codigo+'&estado='+estado+'&pagina='+pagina;
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
      $codigo = $_REQUEST['codigo'];
      $ciclo = $_REQUEST['ciclo'];
      $pagina = $_REQUEST['pagina'];
      $estado = $_REQUEST['estado'];
      
      if($estado == '') $estado = 8;
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT DISTINCT cod_programacion FROM programacion ";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      
      $cuenta = mysql_num_rows ($consulta3);  
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_programacion.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'><TD>C�digo ");
      print ("<INPUT type='text' name='codigo' value=''></TD>");

      ////BUSCAMOS LOS CENTROS DE ACOPIO
      print ("<TD>Ciclo ");
      print ("<SELECT NAME='ciclo'>");                

      $instruccion = "SELECT cod_ciclo, nombre FROM ciclo ORDER BY cod_ciclo";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_ciclo'].">[".$row['cod_ciclo']."] - ".$row['nombre']."</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");      
        
      ////MOSTRAMOS LOS ESTADOS
      print ("<TD>Estado ");
      print ("<SELECT NAME='estado'>");                
      print("<option value=8>--</option>");
      print("<option value=9>Ver Todas</option>");
      print("<option value=1>ACTIVA</option>");
      print("<option value=0>INACTIVA</option>");  
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
      
      if($estado == 1){
        $condicion2 = $condicion2. "programacion.estado = 1 AND ";
        }
      if($estado == 0){
        $condicion2 = $condicion2. "programacion.estado = 0 AND ";
        } 
      if($estado == 8){
        $condicion2 = $condicion2. " ";
        }        
      if($estado == 9){
        $condicion2 = $condicion2. "programacion.estado <= 1 AND ";
        }                
      if($codigo != ''){
         $condicion2 = $condicion2. "programacion.cod_programacion = $codigo AND ";
        }
      if($ciclo != ''){
         $condicion2 = $condicion2. "programacion.cod_ciclo like $ciclo AND ";
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
      $instruccion2 ="SELECT DISTINCT programacion.cod_programacion AS cod_programacion, programacion.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo, 
                             programacion.fecha_inicial AS fecha_inicial, programacion.fecha_final AS fecha_final, programacion.fecha AS fecha,
                             programacion.estado AS estado, usuario.nombre AS nombre, usuario.apellidos AS apellidos
                      FROM programacion 
                      INNER JOIN ciclo ON ciclo.cod_ciclo = programacion.cod_ciclo 
                      INNER JOIN usuario ON usuario.cod_usuario = programacion.cod_usuario
                      $condicion_final 
                      ORDER BY programacion.cod_programacion DESC  
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='80%'>";
      $hojaExcel.="<TR><TH colspan='11'><center>PROGRAMACIONES</center></TH></TR>";       
      $hojaExcel.="<TH><center>C�digo</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Ciclo</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Periodo</center></TH>";
      $hojaExcel.="<TH><center>Fecha</center></TH>";
      $hojaExcel.="<TH><center>Estado</center></TH>";
      $hojaExcel.="<TH><center>Usuario</center></TH>";
      $hojaExcel.="<TH><center>Observaciones</center></TH>";
    if($opcion_vista == 1){
      $hojaExcel.="<TH><center>Activar/Desactivar</center></TH>";
      $hojaExcel.="<TH><center>Eliminar</center></TH>";
     }      
      $hojaExcel.="</TR>";

      $color = '';

         for($i=0; $i<$nfilas; $i++){
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
            
            if($row2[estado] == 1){
               $nom_estado = "ACTIVA";
              }else{
                $nom_estado = "INACTIVA";
                }
            
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_programacion'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_ciclo'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_ciclo'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_inicial'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_final'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $nom_estado . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nombre']." ".$row2['apellidos'] . "</TD>";
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_programacion],1) title='Ver observaciones de la programaci�n'><img src='../imagenes/observacion.png' width='16' height='16' border='0' alt=''></a> </center></TD>"; 
          if($opcion_vista == 1){  
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_programacion],2) title='Activar / Desactivar programaci�n'><img src='../imagenes/act_desact.png' width='16' height='16' border='0' alt=''></a> </center></TD>";                         
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_programacion],3) title='Eliminar programaci�n'><img src='../imagenes/borrar.png' width='16' height='16' border='0' alt=''></a> </center></TD>";
             }
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/Programaciones"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
