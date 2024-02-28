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
<TITLE>PAGO MANIPULADORAS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(anio,mes,tipo_operacion){ 
    var url="../admin/operacion_pago_manipuladoras.php?anio="+anio+"&mes="+mes+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=1200,height=600,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }

   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla2(anio,mes,tipo_operacion){ 
    var url="../admin/operacion_pago_manipuladoras_inf.php?anio="+anio+"&mes="+mes+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=1200,height=600,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }    
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      anio = document.forms.datechooser.anio.value;
      mes  = document.forms.datechooser.mes.value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'admin_pago_manipuladoras.php?anio='+anio+'&mes='+mes+'&pagina='+pagina;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<?php if($opcion_vista == 1){?>
 <td width='30%' style='font-weight:bold; color: #f4d359' align="center"><a href=javascript:operar_tabla(0,0,1)><img src='../imagenes/guardar.png' width='32' height='32' border='0' alt='Nuevo Registro'>&nbsp;&nbsp;Registrar Pago Manipuladoras</a></td>
<?php } ?>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<div align="Center">
<?php                        
      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $anio = $_REQUEST['anio'];
      $mes  = $_REQUEST['mes'];
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(anio) AS cuenta FROM pago_manipuladoras";
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
      print ("<FORM NAME='datechooser' ACTION='admin_pago_manipuladoras.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      print ("<TD>Año (aaaa)");
      print ("<INPUT type='text' name='anio' size='5' value=''></TD>");
      print ("<TD>Mes (mm)");
      print ("<INPUT type='text' name='mes' size='3' value=''></TD>");
               
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
      
      if($anio != ''){
         $condicion2 = $condicion2. "pago_manipuladoras.anio = '$anio' AND ";
        }
      if($mes != ''){
         $condicion2 = $condicion2. "pago_manipuladoras.mes = '$mes' AND ";
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
      $instruccion2 ="SELECT pago_manipuladoras.anio AS anio, pago_manipuladoras.mes AS mes
                      FROM pago_manipuladoras
                      $condicion_final 
                      ORDER BY pago_manipuladoras.anio, pago_manipuladoras.mes
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='80%'>";
      $hojaExcel.="<TR><TH colspan='9'><center>PERIODOS DE PAGO MANIPULADORAS</center></TH></TR>";       
      $hojaExcel.="<TR>";
      $hojaExcel.="<TH colspan='3'>&nbsp;</TH>";
      $hojaExcel.="<TH colspan='3'><center>Documento Equivalente</center></TH>";
      $hojaExcel.="</TR>";
      $hojaExcel.="<TR>";
      $hojaExcel.="<TH><center>Año</center></TH>";
      $hojaExcel.="<TH><center>Mes</center></TH>";
    if($opcion_vista == 1){
      $hojaExcel.="<TH><center>Registrar</center></TH>";
      $hojaExcel.="<TH><center>Calcular </center></TH>";
      $hojaExcel.="<TH><center>Generar Consecutivo </center></TH>";
      $hojaExcel.="<TH><center>Listado </center></TH>";
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
            
            $anio = $row2['anio'];
            $mes = $row2['mes'];
            
            ////DEFINIMOS SI ES AÑO BISIESTO PARA VER SI FEBRERO TIENE 28 O 29 DIAS
            if((($anio % 4 == 0) && ($anio % 100 != 0)) || (($anio % 100 == 0) && ($anio % 400 == 0))){
               $bisiesto=1;
               }else{
                 $bisiesto=0;
                 }                                           
            
            ////DEFINIMOS NOMBRE DEL MES Y NUMERO DE DIAS
            if($mes=='01'){ $ndiasmes=31; $mes_nombre='ENERO';}
            if($mes=='02'){ if($bisiesto==1){$ndiasmes=29;}else{$ndiasmes=28;} $mes_nombre='FEBRERO';}
            if($mes=='03'){ $ndiasmes=31; $mes_nombre='MARZO';}
            if($mes=='04'){ $ndiasmes=30; $mes_nombre='ABRIL';}
            if($mes=='05'){ $ndiasmes=31; $mes_nombre='MAYO';}
            if($mes=='06'){ $ndiasmes=30; $mes_nombre='JUNIO';}
            if($mes=='07'){ $ndiasmes=31; $mes_nombre='JULIO';}
            if($mes=='08'){ $ndiasmes=31; $mes_nombre='AGOSTO';}
            if($mes=='09'){ $ndiasmes=30; $mes_nombre='SEPTIEMBRE';}
            if($mes=='10'){ $ndiasmes=31; $mes_nombre='OCTUBRE';}
            if($mes=='11'){ $ndiasmes=30; $mes_nombre='NOVIEMBRE';}
            if($mes=='12'){ $ndiasmes=31; $mes_nombre='DICIEMBRE';}  
            
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['anio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $mes." - ".$mes_nombre. "</TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla('$row2[anio]','$row2[mes]',2) title='Registrar Raciones'><img src='../imagenes/tabla.png' width='14' height='14' border='0'></a> </center></TD>"; 
            
            ////BUSCAMOS SI EL PERIODO YA TIENE NUMERO DE DOCUMENTO EQUIVALENTE ASIGNADO
            $instruccion3 = "SELECT count(num_documento) AS cuenta FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
            $consulta3 = mysql_query ($instruccion3, $conexion);  
            $row3 = mysql_fetch_array ($consulta3);
            $cuenta = $row3['cuenta'];
            
             if($cuenta <= 0){
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla('$row2[anio]','$row2[mes]',3) title='Calcular Documento Equivalente'><img src='../imagenes/calculado.png' width='14' height='14' border='0'></a> </center></TD>";
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla('$row2[anio]','$row2[mes]',4) title='Generar Consecutivos'><img src='../imagenes/consecutivo.png' width='14' height='14' border='0'></a> </center></TD>";
               }else{
                 $hojaExcel.="<TD style=background:$color><center> - </center></TD>";
                 $hojaExcel.="<TD style=background:$color><center> - </center></TD>";                
                 }             
             } 
             $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla2('$row2[anio]','$row2[mes]',5) title='Listar Documentos Equivalentes'><img src='../imagenes/informe.png' width='14' height='14' border='0'></a> </center></TD>";
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/PagosManipuladoras"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
