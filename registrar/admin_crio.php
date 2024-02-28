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
<TITLE>BASE DE DATOS CRIO</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_crio.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=600,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      codigo = document.forms.datechooser.codigo.value;
      p_nombre = document.forms.datechooser.p_nombre.value;
      s_nombre = document.forms.datechooser.s_nombre.value;
      p_apellido = document.forms.datechooser.p_apellido.value;
      s_apellido = document.forms.datechooser.s_apellido.value;

            
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;
            
      window.location = 'admin_departamento.php?codigo='+codigo+'&p_nombre='+p_nombre+'&pagina='+pagina+'&p_apellido='+p_apellido+'&s_apellido='+s_apellido;
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
      $p_nombre = $_REQUEST['p_nombre'];
      $s_nombre = $_REQUEST['s_nombre'];
      $p_apellido = $_REQUEST['p_apellido'];
      $s_apellido = $_REQUEST['s_apellido'];
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_departamento) AS cuenta FROM departamento";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $cuenta = $row3['cuenta'];  
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
         $num_paginas = 0; ///no paginamos 
          
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='98%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_crio.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      print ("<TD># Documento Identidad ");
      print ("<INPUT type='text' name='codigo' size='10' value=''></TD>");
      print ("<TD>Primer Nombre ");
      print ("<INPUT type='text' name='p_nombre' size='15' value=''></TD>");
      print ("<TD>Segundo Nombre ");
      print ("<INPUT type='text' name='s_nombre' size='15' value=''></TD>");
      print ("<TD>Primer Apellido ");
      print ("<INPUT type='text' name='p_apellido' size='15' value=''></TD>");      
      print ("<TD>Segundo Apellido ");
      print ("<INPUT type='text' name='s_apellido' size='15' value=''></TD>"); 
               
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
         $condicion2 = $condicion2. "0_bdcrio.cedula = '$codigo' AND ";
        }
      if($p_nombre != ''){
         $condicion2 = $condicion2. "0_bdcrio.primer_nombre = '$p_nombre' AND ";
        } 
      if($s_nombre != ''){
         $condicion2 = $condicion2. "0_bdcrio.segundo_nombre = '$s_nombre' AND ";
        }   
      if($p_apellido != ''){
         $condicion2 = $condicion2. "0_bdcrio.primer_apellido = '$p_apellido' AND ";
        }      
      if($s_apellido != ''){
         $condicion2 = $condicion2. "0_bdcrio.segundo_apellido = '$s_apellido' AND ";
        }                
        
        $condicion2 = substr($condicion2, 0, -4);
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = " WHERE 0_bdcrio.cedula = '9999999999999999999999' ";   ////para que no muestre nada sin parametros de busqueda
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT id, cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, municipio, zona, email    
                      FROM 0_bdcrio
                      $condicion_final 
                      ORDER BY cedula
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='98%'>";
      $hojaExcel.="<TR><TH colspan='9'><center>RESULTADOS</center></TH></TR>";       
      $hojaExcel.="<TH><center>Documento</center></TH>";
      $hojaExcel.="<TH><center>Primer Nombre</center></TH>";
      $hojaExcel.="<TH><center>Segundo Nombre</center></TH>";
      $hojaExcel.="<TH><center>Primer Apellido</center></TH>";
      $hojaExcel.="<TH><center>Segundo Apellido</center></TH>";
      $hojaExcel.="<TH><center>Municipio</center></TH>";
      $hojaExcel.="<TH><center>Zona</center></TH>";
      $hojaExcel.="<TH><center>Email</center></TH>";
    if($opcion_vista == 1){
      $hojaExcel.="<TH><center>Editar</center></TH>";
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cedula'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['primer_nombre'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['segundo_nombre'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['primer_apellido'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['segundo_apellido'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['zona'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['email'] . "</TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[id],2) title='Editar'><img src='../imagenes/editar.png' width='14' height='14' border='0' alt='Editar'></a> </center></TD>"; 
            } 
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          /*
          $login=trim($_SESSION['login']);
          $sfile="../excel/Departamentos"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
          $fp=fopen($sfile,"w"); 
          fwrite($fp,$hojaExcel); 
          fclose($fp);
          echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 
         */ 
      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible</span></center>");

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
