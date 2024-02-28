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
<TITLE>SUBIR DOCUMENTO</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion,vista,usuario){ 
    var url="../admin/operacion_subir_documento_registro.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion+"&vista="+vista+"&usuario="+usuario;
    open(url,"Sizewindow","width=600,height=600,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }

   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      codigo = document.forms.datechooser.codigo.value;
      nombre = document.forms.datechooser.nombre.value;
      apellido = document.forms.datechooser.apellido.value;
      documento = document.forms.datechooser.documento.value; 

      j = document.forms.datechooser.tipo.selectedIndex;
      tipo = document.forms.datechooser.tipo.options[j].value;            
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'inf_documentos_registro.php?codigo='+codigo+'&nombre='+nombre+'&tipo='+tipo+'&documento='+documento+'&apellido='+apellido;
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
      $nombre = $_REQUEST['nombre'];
      $apellido = $_REQUEST['apellido'];
      $documento = $_REQUEST['documento'];
      $tipo= $_REQUEST['tipo'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='inf_documentos_registro.php' METHOD='POST'>"); 
      print ("<TR style='font-weight:bold; color: white'>");

      ////BUSCAMOS LAS UNIDADES DE MEDIDA
      print ("<TD>Tipo de Documento ");
      print ("<SELECT NAME='tipo'>");                

      $instruccion = "SELECT cod_clasificacion, nombre FROM 0c_clasificacion_registro ORDER BY cod_clasificacion";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_clasificacion'].">".$row['nombre']."</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");  
        
      print ("<TD>Nombre ");
      print ("<INPUT type='text' name='nombre' value=''></TD>"); 
      
      print ("<TD>Apellidos ");
      print ("<INPUT type='text' name='apellido' value=''></TD>"); 
      
      print ("<TD>Documento ");
      print ("<INPUT type='text' name='documento' value=''></TD>");    
        
      print ("<TD><INPUT TYPE='submit' NAME='consultar' VALUE='Consultar'></TD>");  
      print ("</FORM>");
      print ("</TD></TR><tr><td>&nbsp;</td></tr></table>"); 


      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_menu) AS cuenta FROM menu";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $cuenta = $row3['cuenta'];  
      
      $cuenta = 0;
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
     
      ////GENERAMOS LA CONDICION DE LA CONSULTA
      $condicion = " WHERE 0c_documento_registro.obsoleto <> 1 ";
    
      if($tipo != ''){                
         $condicion2 = $condicion2. "AND 0c_documento_registro.cod_clasificacion = $tipo AND ";
        }  
        
      if($nombre != ''){                
         $condicion2 = $condicion2. "AND usuario.nombre like '%$nombre%' AND ";
        } 
        
      if($apellido != ''){                
         $condicion2 = $condicion2. "AND usuario.apellidos like '%$apellido%' AND ";
        }         
        
      if($documento != ''){                
         $condicion2 = $condicion2. "AND 0c_documento_registro.nombre_documento like '%$documento%' AND ";
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
      $instruccion2 ="SELECT 0c_documento_registro.cod_documento AS cod_documento, 0c_documento_registro.cod_usuario AS cod_usuario, 
                             usuario.nombre AS nom_usuario, usuario.apellidos AS apellidos, 0c_documento_registro.nombre_documento AS nom_documento, 
                             0c_documento_registro.nombre_doc_subido AS nom_doc_subido, 0c_documento_registro.fecha_subido AS fecha_subido,
                             0c_documento_registro.ult_fecha_revision AS ult_fecha_revision, 0c_documento_registro.cod_clasificacion, 
                             0c_clasificacion_registro.nombre AS nom_clasificacion   
                      FROM 0c_documento_registro
                      INNER JOIN usuario ON usuario.cod_usuario = 0c_documento_registro.cod_usuario 
                      INNER JOIN 0c_clasificacion_registro ON 0c_clasificacion_registro.cod_clasificacion = 0c_documento_registro.cod_clasificacion 
                      $condicion_final
                      ORDER BY 0c_documento_registro.cod_documento 
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
   //   echo"<br> SQL: ".$instruccion2;
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='98%'>";
      $hojaExcel.="<TR><TH colspan='14'><center>DOCUMENTOS DE CALIDAD</center></TH></TR>";       
      $hojaExcel.="<TH><center>ID</center></TH>";
      $hojaExcel.="<TH><center>Creo</center></TH>";
      $hojaExcel.="<TH><center>Nombre</center></TH>";       
      $hojaExcel.="<TH><center>Fecha Registro</center></TH>";
      $hojaExcel.="<TH><center>Clasificacion</center></TH>";
      $hojaExcel.="<TH><center>Fecha Subido</center></TH>";

    if($opcion_vista == 1){
     $hojaExcel.="<TH><center>Visualizar</center></TH>"; 
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_documento'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_usuario'] . " " . $row2['apellidos'] . " </TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_documento'] . "</TD>";             
            $hojaExcel.="<TD style=background:$color>" . $row2['ult_fecha_revision'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_clasificacion'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_subido'] . "</TD>";
            
          if($opcion_vista == 1){   
            if($row2[nom_doc_subido] != ''){
               $hojaExcel.="<TD style=background:$color><center> <a href='../documento_subido/registro/".$row2[nom_doc_subido]."' target='_blank'><img src='../imagenes/ver_detalle.png' width='24' height='24' alt='Visualizar Documento'></a>"; 
               }else{
                 $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
                 }
            
            }
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/doc_registro"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
