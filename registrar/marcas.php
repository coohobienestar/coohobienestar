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
<TITLE>MARCAS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,cod_ingrediente,tipo_operacion){ 
    var url="../admin/operacion_marca.php?codigo="+codigo+"&cod_ingrediente="+cod_ingrediente+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=800,height=400,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.programacion.selectedIndex;
      programacion = document.forms.datechooser.programacion.options[i].value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'marcas.php?programacion='+programacion+'&pagina='+pagina;
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
      print ("<FORM NAME='datechooser' ACTION='marcas.php' METHOD='POST'>");
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
           print("<option value=".$row['cod_programacion'].">".$row['cod_programacion']." - [".$row['nombre']."]</option>");
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
     
      // ////GENERAMOS LA CONDICION DE LA CONSULTA
      // $condicion = " WHERE ";
      
      // if($cod_programacion != ''){
      //    $condicion2 = $condicion2. "observacion.cod_programacion = $cod_programacion AND ";
      //   }
       
      //   $condicion2 = substr($condicion2, 0, -4);
      //   $condicion_final = $condicion.$condicion2;    
        
      //   if($condicion_final == " WHERE "){
      //      $condicion_final = " WHERE observacion.cod_observacion = 0";
      //      $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
      //     }else{
      //       $limit = "";
      //       } 
           
      ////EJECUTAMOS LA CONSULTA

      if(isset($_POST['consultar'])){

        $instruccion2 ="SELECT distinct calculo_redondeado_escuela.cod_ingrediente AS codigo_ingrediente,
                        ingrediente.nombre as nombre_ingrediente
                        from calculo_redondeado_escuela
                        inner join ingrediente on ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente
                        where cod_programacion = $cod_programacion order by nombre_ingrediente";
       
        $consulta2 = mysql_query($instruccion2);
        error_consulta($consulta2,$instruccion2);
        $nfilasingredientes = mysql_num_rows($consulta2);
      }
      

      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      
      if ($nfilasingredientes > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel.="<TABLE width='80%'>";
      $hojaExcel.="<TR><TH colspan='13'><center>REGISTRAR MARCAS EN PROGRAMACION ". $cod_programacion ."</center></TH></TR>";       
      $hojaExcel.="<TH><center>Producto Minuta</center></TH>";
      $hojaExcel.="<TH><center>Nombre Marca</center></TH>";
      $hojaExcel.="<TH width='10%'><center>Agregar Marca</center></TH>";

    if($opcion_vista == 1){
      
      $hojaExcel.="<TH><center>Eliminar</center></TH>";
     }
      $hojaExcel.="</TR>";

      $color = '';
      for ($i=0; $i<$nfilasingredientes; $i++){
    
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
            $hojaExcel.="<TD style=background:$color>" . $row2['nombre_ingrediente'] . "</TD>";
            /// CONSULTA DE MARCA SOBRE CADA PRODUCTO
            $query_marcas = "SELECT *
                                    FROM sicc24.marcas
                                    
                                    where cod_programacion = $cod_programacion and cod_ingrediente_programado = $row2[codigo_ingrediente]";
            // echo $query_intercambios;
            $consulta_marcas_ing = mysql_query($query_marcas);
            $nfilas_marcas = mysql_num_rows ($consulta_marcas_ing);
            $marca = null;
            
            for ($marc=0;$marc<$nfilas_marcas;$marc++){
              $row_marcas = mysql_fetch_array($consulta_marcas_ing);
              $marca = $row_marcas['nombre_marca'];
            }
            $hojaExcel.="<TD style=background:$color>". $marca ."</TD>";
            if ($nfilas_marcas == 0){
              $hojaExcel.="<TD style=background:$color> <center><a href=javascript:operar_tabla($cod_programacion,$row2[codigo_ingrediente],1) title='Registrar Marca Producto'><img src='../imagenes/observacion.png' width='15' height='15' border='0' alt=''></a></center>" . "</TD>";
              $hojaExcel.="<TD style=background:$color> <center>" . "</TD>";
            }else{
              $hojaExcel.="<TD style=background:$color> <center>" . "</TD>";
              $hojaExcel.="<TD style=background:$color> <center><a href=javascript:operar_tabla($cod_programacion,$row2[codigo_ingrediente],5) title='Eliminar Marca Producto'><img src='../imagenes/borrar.png' width='15' height='15' border='0' alt=''></a></center>" . "</TD>";
            }
            
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
