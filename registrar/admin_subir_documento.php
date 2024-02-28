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
<TITLE>SUBIR DOCUMENTO</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion,vista,usuario){ 
    var url="../admin/operacion_subir_documento.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion+"&vista="+vista+"&usuario="+usuario;
    open(url,"Sizewindow","width=600,height=600,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
    
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function subir_doc(codigo,tipo_operacion,vista,usuario){ 
    var url="../funciones/upload.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion+"&vista="+vista+"&usuario="+usuario;
    open(url,"Sizewindow","width=650,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }    
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      codigo = document.forms.datechooser.codigo.value;
      nombre = document.forms.datechooser.nombre.value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'admin_subir_documento.php?codigo='+codigo+'&nombre='+nombre+'&pagina='+pagina;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<?php if($opcion_vista == 1){?>
 <td width='30%' style='font-weight:bold; color: #f4d359' align="center"><a href=javascript:operar_tabla(0,1,1,<?php print("$cod_usuario");?>)><img src='../imagenes/guardar.png' width='32' height='32' border='0' alt='Nuevo Registro'>&nbsp;&nbsp;Crear Documento</a></td>
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
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

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
      $condicion = " WHERE ";
      
      if($codigo != ''){
         $condicion2 = $condicion2. "menu.cod_menu like '%$codigo%' AND ";
        }
      if($nombre != ''){
         $condicion2 = $condicion2. "menu.nombre like '%$nombre%' AND ";
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
      $instruccion2 ="SELECT 0c_documento_calidad.cod_documento AS cod_documento, 0c_documento_calidad.cod_usuario AS cod_usuario, 
                             usuario.nombre AS nom_usuario, usuario.apellidos AS apellidos, 0c_documento_calidad.nombre_documento AS nom_documento, 
                             0c_documento_calidad.nombre_doc_subido AS nom_doc_subido, 0c_documento_calidad.fecha_subido AS fecha_subido,
                             0c_documento_calidad.codigo AS codigo, 0c_documento_calidad.version AS version, 0c_documento_calidad.ult_fecha_revision AS ult_fecha_revision,
                             0c_documento_calidad.cod_clasificacion, 0c_clasificacion.nombre AS nom_clasificacion  
                      FROM 0c_documento_calidad
                      INNER JOIN usuario ON usuario.cod_usuario = 0c_documento_calidad.cod_usuario 
                      INNER JOIN 0c_clasificacion ON 0c_clasificacion.cod_clasificacion = 0c_documento_calidad.cod_clasificacion 
                      WHERE 0c_documento_calidad.obsoleto <> 1
                      ORDER BY 0c_documento_calidad.cod_documento 
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='80%'>";
      $hojaExcel.="<TR><TH colspan='11'><center>DOCUMENTOS DE CALIDAD</center></TH></TR>";       
      $hojaExcel.="<TH><center>ID</center></TH>";
      $hojaExcel.="<TH><center>Creo</center></TH>";
      $hojaExcel.="<TH><center>Codigo</center></TH>";
      $hojaExcel.="<TH><center>Version</center></TH>";
      $hojaExcel.="<TH><center>Nombre</center></TH>";
      $hojaExcel.="<TH><center>Ultima Fecha Revision</center></TH>";
      $hojaExcel.="<TH><center>Clasificacion</center></TH>";
      $hojaExcel.="<TH><center>Fecha Subido</center></TH>";

    if($opcion_vista == 1){
     $hojaExcel.="<TH><center>Visualizar</center></TH>"; 
     $hojaExcel.="<TH><center>Agregar</center></TH>";
     $hojaExcel.="<TH><center>Asigar Usuarios</center></TH>";     
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
            $hojaExcel.="<TD style=background:$color>" . $row2['codigo'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['version'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_documento'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['ult_fecha_revision'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_clasificacion'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_subido'] . "</TD>";
            
          if($opcion_vista == 1){   
            if($row2[nom_doc_subido] != ''){
               $hojaExcel.="<TD style=background:$color><center> <a href='../documento_subido/calidad/".$row2[nom_doc_subido]."' target='_blank'><img src='../imagenes/ver_detalle.png' width='24' height='24' alt='Visualizar Documento'></a>"; 
               }else{
                 $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
                 }

            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:subir_doc($row2[cod_documento],3,$row2[cod_usuario]) title='Subir Documento'><img src='../imagenes/imagen.png' width='18' height='18' border='0' alt='Editar'></a> </center></TD>"; 
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_documento],3,1,$row2[cod_usuario]) title='Asignar Documento a Usuario'><img src='../imagenes/relacionar.png' width='14' height='14' border='0' alt='Asignar Documento a Usuario'></a> </center></TD>";            
            }
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/doc_calidad"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
