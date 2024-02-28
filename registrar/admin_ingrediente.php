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
<TITLE>INGREDIENTES</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_ingrediente.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=800,height=500,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      codigo = document.forms.datechooser.codigo.value;
      nombre = document.forms.datechooser.nombre.value;
      
      i = document.forms.datechooser.categoria.selectedIndex;
      categoria = document.forms.datechooser.categoria.options[i].value;
      
      j = document.forms.datechooser.unibase.selectedIndex;
      unibase = document.forms.datechooser.unibase.options[j].value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;

      l = document.forms.datechooser.inventario.selectedIndex;
      inventario = document.forms.datechooser.pagina.options[l].value;
            
      window.location = 'admin_ingrediente.php?categoria='+categoria+'&codigo='+codigo+'&nombre='+nombre+'&unibase='+unibase+'&pagina='+pagina+'&inventario='+inventario;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<?php if($opcion_vista == 1){?>
 <td width='30%' style='font-weight:bold; color: #f4d359' align="center"><a href=javascript:operar_tabla(0,1)><img src='../imagenes/guardar.png' width='32' height='32' border='0' alt='Nuevo Registro'>&nbsp;&nbsp;Registrar Ingrediente</a></td>
<?php } ?>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<div align="Center">
<?php                        
      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $codigo = $_REQUEST['codigo'];
      $nombre = $_REQUEST['nombre'];
      $categoria = $_REQUEST['categoria'];
      $unibase = $_REQUEST['unibase'];
      $pagina = $_REQUEST['pagina'];
      $inventario = $_REQUEST['inventario'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_ingrediente) AS cuenta FROM ingrediente";
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
      print ("<FORM NAME='datechooser' ACTION='admin_ingrediente.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      print ("<TD>Código ");
      print ("<INPUT type='text' name='codigo' size='3' value=''></TD>");
      print ("<TD>Nombre Ingrediente ");
      print ("<INPUT type='text' name='nombre' size='30' value=''></TD>");

      ////BUSCAMOS LAS CATEGORIAS DE LOS INGREDIENTES
      print ("<TD>Categoria ");
      print ("<SELECT NAME='categoria'>");                

      $instruccion = "SELECT cod_categoria_ingrediente, nombre FROM categoria_ingrediente ORDER BY cod_categoria_ingrediente";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_categoria_ingrediente'].">[".$row['cod_categoria_ingrediente']."] - ".$row['nombre']."</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");          
        
      ////BUSCAMOS LAS UNIDADES BASE
      print ("<TD>Unidad base ");
      print ("<SELECT NAME='unibase'>"); 
      
      $instruccion_m = "SELECT DISTINCT unidad_base FROM ingrediente ORDER BY unidad_base";
      $consulta_m = mysql_query ($instruccion_m, $conexion);
      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['unidad_base'].">".$row_m['unidad_base']."</option>");
        }while ($row_m = mysql_fetch_array($consulta_m)); 
        print("</SELECT></TD>");  
        
      ////BUSCAMOS LAS UNIDADES BASE
      print ("<TD>Maneja Inventario ");
      print ("<SELECT NAME='inventario'>"); 
        
      $valdesc_m = "";
      $descp_m = "--";
          print("<option value=".$valdesc_m.">".$descp_m."</option>");
          print("<option value='1'>SI</option>");  
          print("<option value='0'>NO</option>");   
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
         $condicion2 = $condicion2. "ingrediente.cod_ingrediente like '%$codigo%' AND ";
        }
      if($nombre != ''){
         $condicion2 = $condicion2. "ingrediente.nombre like '%$nombre%' AND ";
        } 
      if($categoria != ''){
         $condicion2 = $condicion2. "ingrediente.cod_categoria_ingrediente = '$categoria' AND ";
        }   
      if($unibase != ''){
         $condicion2 = $condicion2. "ingrediente.unidad_base = '$unibase' AND ";
        }   
      if($inventario != ''){
         $condicion2 = $condicion2. "ingrediente.maneja_inventario = '$inventario' AND ";
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
      $instruccion2 ="SELECT ingrediente.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, ingrediente.redondear AS redondear, 
                             ingrediente.unidad_base AS unidad_base, ingrediente.redondear2 AS redondear2, 
                             ingrediente.cod_categoria_ingrediente AS cod_categoria_ingrediente, categoria_ingrediente.nombre AS nom_categoria_ingrediente,
                             ingrediente.maneja_inventario AS maneja_inventario
                      FROM ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = ingrediente.cod_categoria_ingrediente
                      $condicion_final 
                      ORDER BY ingrediente.cod_ingrediente   
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='85%'>";
      $hojaExcel.="<TR><TH colspan='10'><center>INGREDIENTES</center></TH></TR>";       
      $hojaExcel.="<TH><center>Código</center></TH>";
      $hojaExcel.="<TH><center>Nombre</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Categoria</center></TH>";
      $hojaExcel.="<TH><center>Unidad Base</center></TH>";
      $hojaExcel.="<TH><center>Redondear a Medida</center></TH>";
      $hojaExcel.="<TH><center>Convertir a UND</center></TH>";
      $hojaExcel.="<TH><center>Maneja Inventario</center></TH>";
    if($opcion_vista == 1){
      $hojaExcel.="<TH><center>Editar</center></TH>";
      $hojaExcel.="<TH><center>Relacionar Unidades</center></TH>";
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
            
            $redondeo1 = $row2['redondear'];
              if($redondeo1 == 1){
                 $redondeo1 = "SI";
                }else{
                  $redondeo1 = "NO";
                  }
                  
            $redondeo2 = $row2['redondear2'];
              if($redondeo2 == 1){
                 $redondeo2 = "SI";
                }else{
                  $redondeo2 = "NO";
                  } 
                  
            $maneja_inventario = $row2['maneja_inventario'];
              if($maneja_inventario == 1){
                 $maneja_inventario = "SI";
                }else{
                  $maneja_inventario = "NO";
                  }                                    
            
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_ingrediente'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_ingrediente'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_categoria_ingrediente'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_categoria_ingrediente'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['unidad_base'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $redondeo1 . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $redondeo2 . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $maneja_inventario . "</TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_ingrediente],2) title='Editar Ingrediente'><img src='../imagenes/editar.png' width='14' height='14' border='0' alt='Editar'></a> </center></TD>"; 
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_ingrediente],3) title='Relacionar Unidades de medida'><img src='../imagenes/relacionar.png' width='14' height='14' border='0' alt='Relacionar'></a> </center></TD>";            
           } 
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/Ingredientes"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
