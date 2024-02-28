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
<TITLE>EXCLUIR ESCUELAS O MUNICIPIOS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_excluir.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=500,height=600,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no,") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      i = document.forms.datechooser.cod_departamento.selectedIndex;
      cod_departamento = document.forms.datechooser.cod_departamento.options[i].value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      

      nom_municipio = document.forms.datechooser.nom_municipio.value;
      
      window.location = 'admin_excluir.php?cod_depto='+cod_depto+'&nom_municipio='+nom_municipio;
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
      $cod_departamento = $_REQUEST['cod_departamento'];
      $nom_municipio = $_REQUEST['nom_municipio'];
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
      
      $cuenta = 0; ////NO SE MUESTRA PAGINACION EN ESTE FORMULARIO
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_excluir.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");

      ////BUSCAMOS LOS DEPARTAMENTOS
      print ("<TD>Departamento ");
      print ("<SELECT NAME='cod_departamento'>");                

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
        
      print ("<TD>Municipio ");
      print ("<INPUT type='text' name='nom_municipio' value=''></TD>"); 
               
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
      if($nom_municipio != ''){
         $condicion2 = $condicion2. "municipio.nombre like '%$nom_municipio%' AND ";
        } 
        
        $condicion2 = substr($condicion2, 0, -4);
        $condicion_final = $condicion.$condicion2;    
        
        if($condicion_final == " WHERE "){
           $condicion_final = "";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = " ";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT municipio.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio, municipio.cod_departamento AS cod_departamento, 
                             departamento.nombre AS nom_departamento  
                      FROM municipio
                      INNER JOIN departamento ON departamento.cod_departamento = municipio.cod_departamento
                      $condicion_final 
                      ORDER BY municipio.cod_departamento, municipio.cod_municipio    
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);

      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='60%'>";
      $hojaExcel.="<TR><TH colspan='5'><center>DEPARTAMENTO - MUNICIPIO</center></TH></TR>";       
      $hojaExcel.="<TH><center>Departamento</center></TH>";
    if($opcion_vista == 1){  
      $hojaExcel.="<TH><center>Excluir Municipios</center></TH>";
     } 
      $hojaExcel.="<TH><center>Municipio</center></TH>";
    if($opcion_vista == 1){  
      $hojaExcel.="<TH><center>Excluir Escuelas</center></TH>";
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_departamento'] . " - ". $row2['nom_departamento'] ."</TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_departamento],1)><img src='../imagenes/excluir_municipio.png' width='14' height='14' border='0' alt='Excluir Municipios'></a> </center></TD>";
            }
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . " - " . $row2['nom_municipio'] . "</TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_municipio],2)><img src='../imagenes/excluir_escuela.png' width='14' height='14' border='0' alt='Excluir Escuelas'></a> </center></TD>"; 
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
