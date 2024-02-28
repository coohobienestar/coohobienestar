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
<TITLE>USUARIOS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_usuario.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"_blank","Sizewindow,width=700,height=650,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;      
           
      nombre = document.forms.datechooser.nombre.value;
      apellido = document.forms.datechooser.apellido.value;
      login = document.forms.datechooser.login.value;
      cedula = document.forms.datechooser.cedula.value;
      
      window.location = 'admin_usuario.php?nombre='+nombre+'&apellido='+apellido+'&login='+login+'&cedula='+cedula+'&pagina='+pagina;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<?php if($opcion_vista == 1){?>
 <td width='30%' style='font-weight:bold; color: #f4d359' align="center"><a href=javascript:operar_tabla(0,1)><img src='../imagenes/guardar.png' width='32' height='32' border='0' alt='Nuevo Registro'>&nbsp;&nbsp;Registrar Usuario</a></td>
<?php } ?>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<div align="Center">
<?php                        
      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $nombre   = $_REQUEST['nombre'];
      $apellido = $_REQUEST['apellido'];
      $login    = $_REQUEST['login'];
      $cedula   = $_REQUEST['cedula'];
      $pagina   = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_usuario) AS cuenta FROM usuario";
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
      print ("<FORM NAME='datechooser' ACTION='admin_usuario.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      print ("<TD>Nombre ");
      print ("<INPUT type='text' name='nombre' value=''></TD>");
      print ("<TD>Apellidos ");
      print ("<INPUT type='text' name='apellido' value=''></TD>");
      print ("<TD>Cedula ");
      print ("<INPUT type='text' name='cedula' value=''></TD>");
      print ("<TD>Login ");
      print ("<INPUT type='text' name='login' value=''></TD>");
      
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
      
      if($nombre != ''){
         $condicion2 = $condicion2. "usuario.nombre like '%$nombre%' AND ";
        }
      if($apellido != ''){
         $condicion2 = $condicion2. "usuario.apellidos like '%$apellido%' AND ";
        } 
      if($cedula != ''){
         $condicion2 = $condicion2. "usuario.cedula like '%$cedula%' AND ";
        }   
      if($login != ''){
         $condicion2 = $condicion2. "usuario.login like '%$login%'AND ";
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
      $instruccion2 ="SELECT usuario.cod_usuario AS cod_usuario, usuario.nombre AS nombre, usuario.apellidos AS apellidos, usuario.cedula AS cedula, usuario.login AS login
                      FROM usuario
                      $condicion_final 
                      ORDER BY usuario.cod_usuario  
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='80%'>";
      $hojaExcel.="<TR><TH colspan='9'><center>USUARIOS</center></TH></TR>";       
      $hojaExcel.="<TH><center>Código</center></TH>";
      $hojaExcel.="<TH><center>Nombre</center></TH>";
      $hojaExcel.="<TH><center>Apellidos</center></TH>";
      $hojaExcel.="<TH><center>Cedula</center></TH>";
      $hojaExcel.="<TH><center>Login</center></TH>";
    if($opcion_vista == 1){
      $hojaExcel.="<TH><center>Editar</center></TH>";
      $hojaExcel.="<TH><center>Definir Opciones</center></TH>";
      $hojaExcel.="<TH><center>Asignar municipio</center></TH>";
      $hojaExcel.="<TH><center>Restablecer Contraseña</center></TH>";
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
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_usuario'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['apellidos'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cedula'] . " </TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['login'] . " </TD>";
          if($opcion_vista == 1){   
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_usuario],2) title='Editar Usuario'><img src='../imagenes/editar.png' width='14' height='14' border='0' alt='Editar Usuario'></a> </center></TD>"; 
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_usuario],3) title='Definir Opciones de Usuario'><img src='../imagenes/relacionar.png' width='14' height='14' border='0' alt='Definir Opciones de Usuario'></a> </center></TD>";            
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_usuario],5) title='Asignar Municipio a Coordinador'><img src='../imagenes/asig_municipio.png' width='14' height='14' border='0' alt='Asignar Municipio a Coordinador'></a> </center></TD>";
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_usuario],4) title='Restablecer Contraseña'><img src='../imagenes/password.png' width='14' height='14' border='0' alt='Restablecer Contraseña'></a> </center></TD>";             
           } 
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/Usuario"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
