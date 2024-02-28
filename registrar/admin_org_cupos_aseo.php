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
<TITLE>ORGANIZAR CUPOS ASEO</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_org_cupos_aseo.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=900,height=800,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      municipio = document.forms.datechooser.municipio.value;

      t = document.forms.datechooser.tipo.selectedIndex;
      tipo = document.forms.datechooser.tipo.options[t].value;      
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'admin_org_cupos_aseo.php?tipo='+tipo+'&municipio='+municipio+'&pagina='+pagina;
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
      $tipo = $_REQUEST['tipo'];
      $municipio = $_REQUEST['municipio'];
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
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='98%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_org_cupos_aseo.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      print ("<TD>Nombre Municipio ");
      print ("<INPUT type='text' name='municipio' size='30' value=''></TD></TR>");
      
      ////BUSCAMOS LOS TIPOS DE MINUTA
      print ("<TR style='font-weight:bold; color: white'><TD>Tipo ");
      print ("<SELECT NAME='tipo'>"); 
      
      $instruccion_m = "SELECT DISTINCT tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre 
                        FROM 0as_escuela
                        INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta 
                        ORDER BY cod_tipo_minuta";
      $consulta_m = mysql_query ($instruccion_m, $conexion);
      $row_m = mysql_fetch_array ($consulta_m); 
        
      $valdesc_m = "";
      $descp_m = "--";
          print("<option value=".$valdesc_m.">".$descp_m."</option>");  
        do{ 
           print("<option value=".$row_m['cod_tipo_minuta'].">[".$row_m['cod_tipo_minuta']."] - ".$row_m['nombre']."</option>");
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
      
      if($tipo != ''){
         $condicion2 = $condicion2. "0as_escuela.cod_tipo_minuta = '$tipo' AND ";
        }
      if($municipio != ''){
         $condicion2 = $condicion2. "municipio.nombre like '%$municipio%' AND ";
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
      $instruccion2 ="SELECT 0as_escuela.cod_escuela AS cod_escuela, 0as_escuela.nombre AS nom_escuela, municipio.nombre AS nom_municipio, 
                             0as_escuela.cod_municipio AS cod_municipio, 
                             tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             centro_acopio.nombre AS nom_centro_acopio, 0as_escuela.cod_centro_acopio AS cod_centro_acopio,
                             0as_escuela.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, 
                             0as_escuela.total_cupos AS total_cupos, 0as_centro_acopio.nombre AS nom_centro_acopio_as,
                             0as_escuela.grupo_tipo_minuta AS grupo_tipo_minuta, 0as_escuela.numero_manipuladoras AS numero_manipuladoras   
                      FROM 0as_escuela 
                      INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = 0as_escuela.cod_centro_acopio
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta 
                      INNER JOIN departamento ON departamento.cod_departamento = 0as_escuela.cod_departamento
                      LEFT JOIN 0as_centro_acopio ON 0as_centro_acopio.cod_centro_acopio = 0as_escuela.cod_centro_acopio_as
                      $condicion_final
                      GROUP BY 0as_escuela.cod_escuela, 0as_escuela.cod_tipo_minuta 
                      ORDER BY 0as_escuela.cod_tipo_minuta, municipio.nombre, 0as_escuela.nombre     
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='98%'>";
      $hojaExcel.="<TR><TH colspan='10'><center>ORGANIZAR CUPOS</TH></TR>";       
      $hojaExcel.="<TR><TH colspan='10'><center>POR CODIGO ESCUELA: 4 <a href=javascript:operar_tabla(0,5) title='Eliminar Escuelas Duplicadas POR CODIGO'><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt=''></a>  || POR NOMBRE ESCUELA Y MUNICIPIO (Risaralda): 4 <a href=javascript:operar_tabla(0,9) title='Eliminar Escuelas Duplicadas POR NOMBRE'><img src='../imagenes/limpiar_exc.png' width='14' height='14' border='0' alt=''></a></center></TH></TR>";
      $hojaExcel.="<TH colspan='2'><center>Escuela</center></TH>";
      $hojaExcel.="<TH><center>Departamento</center></TH>";
      $hojaExcel.="<TH><center>Municipio || 2 <a href=javascript:operar_tabla(0,3) title='Eliminar Municipios'><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt='Editar'></a></center></TH>";
      $hojaExcel.="<TH><center>Centro Acopio</center></TH>";
      $hojaExcel.="<TH><center>Centro Acopio Aseo  || 5 <a href=javascript:operar_tabla(0,6) title='Asignar Centro Acopio'><img src='../imagenes/repetir.png' width='14' height='14' border='0' alt='Editar'></a></center></TH>";
      $hojaExcel.="<TH><center>Total Cupos</center></TH>";
      $hojaExcel.="<TH><center># Manip.  || 6 <a href=javascript:operar_tabla(0,8) title='Calcular Numero de manipuladoras'><img src='../imagenes/duplicar_obs.png' width='14' height='14' border='0' alt='Editar'></a> </center></TH>";
      $hojaExcel.="<TH><center>Tipo Minuta || 1 <a href=javascript:operar_tabla(0,4) title='Eliminar Tipos de minutas'><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt='Editar'></a>  || 3 <a href=javascript:operar_tabla(0,7) title='Asignar grupos a Minutas'><img src='../imagenes/agregar_ing.png' width='14' height='14' border='0' alt='Editar'></a></center></TH>";
      $hojaExcel.="<TH><center>Grupo</center></TH>";
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
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_departamento'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio_as'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['total_cupos'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['numero_manipuladoras'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['grupo_tipo_minuta'] . "</TD>";
            $hojaExcel.="</TR>";

         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/cupos_org_aseo"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
