<?php
ini_set('max_execution_time',0);
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
<TITLE>INFORME DE CUPOS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.departamento.selectedIndex;
      departamento = document.forms.datechooser.departamento.options[i].value;    
           
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
      
      window.location = 'inf_cupos.php?departamento='+departamento+'&pagina='+pagina;
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
      $cod_departamento = $_REQUEST['departamento'];
      
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
      print ("<TABLE width='99%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='inf_cupos.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      
      ////BUSCAMOS LAS PROGRAMACIONES
      print ("<TD>Departamento");
      print ("&nbsp;&nbsp;<img src='../imagenes/requerido.gif'><SELECT NAME='departamento'>");                

      $instruccion = "SELECT cod_departamento, nombre FROM departamento ORDER BY cod_departamento";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_departamento'].">[".$row['cod_departamento']."] - ".$row['nombre']."</option>");
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
     
      ////GENERAMOS LA CONDICION DE LA CONSULTA
      $condicion = " WHERE ";
      
      if($cod_departamento != ''){
         $condicion2 = $condicion2. "municipio.cod_departamento = '$cod_departamento' AND ";
        }
   
        $condicion2 = substr($condicion2, 0, -4);         
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = " ";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT escuela.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela, municipio.nombre AS nom_municipio, 
                             tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             centro_acopio.nombre AS nom_centro_acopio
                      FROM minuta_escuela 
                      INNER JOIN escuela ON escuela.cod_escuela = minuta_escuela.cod_escuela
                      INNER JOIN minuta ON minuta_escuela.cod_minuta = minuta.cod_minuta 
                      INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = escuela.cod_centro_acopio
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = minuta.cod_tipo_minuta 
                      $condicion_final    
                      GROUP BY escuela.cod_escuela, tipo_minuta.cod_tipo_minuta 
                      ORDER BY tipo_minuta.cod_tipo_minuta, municipio.nombre, escuela.nombre                  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel="<TABLE width='98%'>";
        $hojaExcel.="<TR><TH colspan='12'><center>INFORME DE CUPOS</center></TH></TR>";       
        $hojaExcel.="<TH  colspan='2'><center>Escuela</center></TH>";
        $hojaExcel.="<TH><center>Municipio</center></TH>";
        $hojaExcel.="<TH><center>Centro Acopio</center></TH>";
        
        ////BUSCAMOS LOS RANGOS DE EDAD
        $instruccion_r ="SELECT DISTINCT cod_rango_edad, nombre FROM rango_edad ORDER BY cod_rango_edad";
 
        $consulta_r = mysql_query($instruccion_r);
        error_consulta($consulta_r,$instruccion_r);
        $nfilas_r = mysql_num_rows ($consulta_r);
        
        $cupos_total = 0;
        
        for ($r=0; $r<$nfilas_r; $r++){
          $row_r = mysql_fetch_array($consulta_r);
          
          $nombre_rango = $row_r['nombre'];
        
          $hojaExcel.="<TH><center>$nombre_rango</center></TH>";
        }
        
        $hojaExcel.="<TH><center>Total Cupos</center></TH>";
        $hojaExcel.="<TH><center>Tipo Minuta</center></TH>";
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
            
            ////BUSCAMOS LOS CUPOS 
            $instruccion3 ="SELECT DISTINCT minuta_escuela.cupos AS cupos, minuta_escuela.cod_rango_edad
                            FROM minuta_escuela 
                            INNER JOIN minuta ON minuta_escuela.cod_minuta = minuta.cod_minuta
                            WHERE minuta.cod_tipo_minuta= $row2[cod_tipo_minuta] AND minuta_escuela.cod_escuela = $row2[cod_escuela]                
                      ";
     
            $consulta3 = mysql_query($instruccion3);
            error_consulta($consulta3,$instruccion3);
            $nfilas3 = mysql_num_rows ($consulta3);
            
            $cupos_total = 0;
            
            for ($j=0; $j<$nfilas3; $j++){
              $row3 = mysql_fetch_array($consulta3);
              
              $cupos = $row3['cupos'];
              
              $cupos_total = $cupos_total + $cupos;
            
            }

            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] . "</TD>";
          
            ////BUSCAMOS LOS RANGOS DE EDAD
            $instruccion_r ="SELECT DISTINCT cod_rango_edad, nombre FROM rango_edad ORDER BY cod_rango_edad";
     
            $consulta_r = mysql_query($instruccion_r);
            error_consulta($consulta_r,$instruccion_r);
            $nfilas_r = mysql_num_rows ($consulta_r);
            
            for ($c=0; $c<$nfilas_r; $c++){
              $row_r = mysql_fetch_array($consulta_r);

              $cod_rango_edad = $row_r['cod_rango_edad'];
              
              ////BUSCAMOS LA CANTIDAD DE CUPOS POR CADA RANGO
             $instruccion_c ="SELECT DISTINCT minuta_escuela.cupos AS cupos_rango, minuta_escuela.cod_rango_edad
                            FROM minuta_escuela 
                            INNER JOIN minuta ON minuta_escuela.cod_minuta = minuta.cod_minuta
                            WHERE minuta.cod_tipo_minuta= $row2[cod_tipo_minuta] AND minuta_escuela.cod_escuela = $row2[cod_escuela]  
                              AND minuta_escuela.cod_rango_edad = $cod_rango_edad              
                      ";
     
              $consulta_c = mysql_query($instruccion_c);
              error_consulta($consulta3,$instruccion_c);
              $row_c = mysql_fetch_array($consulta_c);
               
              $cupos_rango = $row_c['cupos_rango'];
              
              if ($cupos_rango != ''){
                  $cupos_rango = $cupos_rango;
                }else{
                   $cupos_rango = 0;
                  }
              
              $hojaExcel.="<TD style=background:$color><center>$cupos_rango</center></TD>";
            }            
            
            $hojaExcel.="<TD style=background:$color>" . $cupos_total . "</TD>";             
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_minuta'] . "</TD>";
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";    
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $fecha = date("Ymd_His");
          $sfile="../excel/InformeCupos"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
