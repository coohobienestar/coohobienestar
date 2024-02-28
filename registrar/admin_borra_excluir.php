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
<TITLE>BORRAR EXCLUSIONES</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]   || Destino define la tabla a la cual se le van a eliminar las exclusiones
    function operar_tabla(codigo,elemento,destino,tipo_operacion){ 
    var url="../admin/operacion_borra_excluir.php?codigo="+codigo+"&elemento="+elemento+"&destino="+destino+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=600,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<td width='30%' style='font-weight:bold; color: #f4d359' align="center"><a href='#' title='Actualizar'><img src='../imagenes/actualizar.png' border='0' width='32' height='32' border='0' alt='' onClick='window.location.reload(true);'></a></td>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<div align="Center">
<?php               
      ////***************************************************************************************************************************************************
      ////BUCAMOS LAS ESCUELAS EXCLUIDAS
      $instruccion2 ="SELECT excluido_escuela.cod_escuela AS cod_escuela, escuela.nombre AS nombre, municipio.nombre AS nom_municipio
                      FROM excluido_escuela
                      INNER JOIN escuela ON escuela.cod_escuela = excluido_escuela.cod_escuela
                      INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio 
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
         $a = $nfilas;
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='50%'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Escuelas Excluidas</center></TH></TR>";       
        $hojaExcel.="<TH><center>Código</center></TH>";
        $hojaExcel.="<TH><center>Nombre</center></TH>";
        $hojaExcel.="<TH><center>Municipio</center></TH>";
      if($opcion_vista == 1){  
        $hojaExcel.="<TH><center>Borrar</center></TH>";
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
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
              if($opcion_vista == 1){   
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_escuela],0,1,5)><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt='Borrar exclusión'></a></center></TD>"; 
                } 
                $hojaExcel.="</TR>"; 
              }
              if($opcion_vista == 1){
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style='background:$color; font-size: 10pt; font-weight: bold; text-align: right' colspan='3'>Borrar todas las Escuelas Excluidas</TD>";
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla(0,0,1,6)><img src='../imagenes/borrar_todos.png' width='24' height='24' border='0' alt='Borrar todas las exclusiones'></a></center></TD>";
                $hojaExcel.="</TR>";
               } 
            $hojaExcel.="</TABLE>";
            $hojaExcel.="<BR><BR>";
      }
               
      ////***************************************************************************************************************************************************
      ////BUCAMOS LOS MENUS EXCLUIDOS PARA ESCUELAS
      $instruccion2 ="SELECT excluido_escuela_menu.cod_escuela AS cod_escuela, escuela.nombre AS nombre, excluido_escuela_menu.cod_menu AS cod_menu,
                             menu.nombre AS nom_menu, excluido_escuela_menu.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             escuela.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio  
                      FROM excluido_escuela_menu
                      INNER JOIN escuela ON escuela.cod_escuela = excluido_escuela_menu.cod_escuela
                      INNER JOIN menu ON menu.cod_menu= excluido_escuela_menu.cod_menu
                      INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                      LEFT JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = excluido_escuela_menu.cod_tipo_minuta  
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
       $b = $nfilas;
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='80%'>";
        $hojaExcel.="<TR><TH colspan='9'><center>Menus Excluidos de Escuelas</center></TH></TR>";       
        $hojaExcel.="<TH colspan='2'><center>Escuela</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Municipio</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Menu</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Tipo Minuta</center></TH>";
      if($opcion_vista == 1){  
        $hojaExcel.="<TH><center>Borrar</center></TH>";
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
                
                if($row2[cod_tipo_minuta] == 0){
                    $nom_tipo_minuta = "Todos los Tipos de Minuta";
                  }else{
                     $nom_tipo_minuta = "$row2[nom_tipo_minuta]";
                    }
                  
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_escuela'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_menu'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nom_menu'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_tipo_minuta'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $nom_tipo_minuta . "</TD>";
              if($opcion_vista == 1){   
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_escuela],$row2[cod_menu],2,5)><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt='Borrar exclusión'></a></center></TD>"; 
                } 
                $hojaExcel.="</TR>"; 
              }
              if($opcion_vista == 1){  
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style='background:$color; font-size: 10pt; font-weight: bold; text-align: right' colspan='8'>Borrar todos los Menus excluidos para las Escuelas</TD>";
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla(0,0,2,6)><img src='../imagenes/borrar_todos.png' width='24' height='24' border='0' alt='Borrar todas las exclusiones'></a></center></TD>";
                $hojaExcel.="</TR>";
                }
            $hojaExcel.="</TABLE>"; 
            $hojaExcel.="<BR><BR>";  
        }

      ////***************************************************************************************************************************************************
      ////BUCAMOS LOS MUNICIPIOS EXCLUIDOS
      $instruccion2 ="SELECT excluido_municipio.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                      FROM excluido_municipio
                      INNER JOIN municipio ON municipio.cod_municipio = excluido_municipio.cod_municipio
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
       $c = $nfilas;
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='50%'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Municipios Excluidos</center></TH></TR>";       
        $hojaExcel.="<TH><center>Código</center></TH>";
        $hojaExcel.="<TH><center>Nombre</center></TH>";
      if($opcion_vista == 1){  
        $hojaExcel.="<TH><center>Borrar</center></TH>";
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
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
              if($opcion_vista == 1){   
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_municipio],0,3,5)><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt='Borrar exclusión'></a></center></TD>"; 
                } 
                $hojaExcel.="</TR>"; 
              }
              if($opcion_vista == 1){  
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style='background:$color; font-size: 10pt; font-weight: bold; text-align: right' colspan='2'>Borrar todos los Municipios excluidos</TD>";
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla(0,0,3,6)><img src='../imagenes/borrar_todos.png' width='24' height='24' border='0' alt='Borrar todas las exclusiones'></a></center></TD>";
                $hojaExcel.="</TR>";
                }
            $hojaExcel.="</TABLE>";  
            $hojaExcel.="<BR><BR>"; 
        }

      ////***************************************************************************************************************************************************
      ////BUCAMOS LOS MENUS EXCLUIDOS PARA LOS MUNICIPIOS
      $instruccion2 ="SELECT excluido_municipio_menu.cod_municipio AS cod_municipio, municipio.nombre AS nombre, excluido_municipio_menu.cod_menu AS cod_menu,
                             menu.nombre AS nom_menu
                      FROM excluido_municipio_menu
                      INNER JOIN municipio ON municipio.cod_municipio = excluido_municipio_menu.cod_municipio
                      INNER JOIN menu ON menu.cod_menu= excluido_municipio_menu.cod_menu
                      ";     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
       
    if($nfilas > 0){
       $d = $nfilas;
        ////ENCABEZADO DE LA TABLA DE RESULTADOS
        $hojaExcel.="<TABLE width='50%'>";
        $hojaExcel.="<TR><TH colspan='6'><center>Menus Excluidos de Municipios</center></TH></TR>";       
        $hojaExcel.="<TH colspan='2'><center>Municipio</center></TH>";
        $hojaExcel.="<TH colspan='2'><center>Menu</center></TH>";
      if($opcion_vista == 1){  
        $hojaExcel.="<TH><center>Borrar</center></TH>";
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
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_municipio'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nombre'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['cod_menu'] . "</TD>";
                $hojaExcel.="<TD style=background:$color>" . $row2['nom_menu'] . "</TD>";
              if($opcion_vista == 1){   
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_municipio],$row2[cod_menu],4,5)><img src='../imagenes/borrar.png' width='14' height='14' border='0' alt='Borrar exclusión'></a></center></TD>"; 
                } 
                $hojaExcel.="</TR>"; 
              }
              if($opcion_vista == 1){  
                $hojaExcel.="<TR>";
                $hojaExcel.="<TD style='background:$color; font-size: 10pt; font-weight: bold; text-align: right' colspan='4'>Borrar todos los Menus excluidos para los Municipios</TD>";
                $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla(0,0,4,6)><img src='../imagenes/borrar_todos.png' width='24' height='24' border='0' alt='Borrar todas las exclusiones'></a></center></TD>";
                $hojaExcel.="</TR>";
                }
            $hojaExcel.="</TABLE>"; 
            $hojaExcel.="<BR><BR>";  
        }        
      
     if(($a >0) || ($b >0) || ($c >0) || ($d >0)){ 
      $hojaExcel.="<TABLE width='50% align='center'>";
      $hojaExcel.="<TR>";
      $hojaExcel.="<TD style='background: #f4d359; font-size: 10pt; font-weight: bold; text-align: center'>Borrar todas las Exclusiones</TD>";
      $hojaExcel.="<TD style=background:#f4d359><center> <a href=javascript:operar_tabla(0,0,0,7)><img src='../imagenes/limpiar_exc.png' width='24' height='24' border='0' alt='Borrar todas las exclusiones'></a></center></TD>";
      $hojaExcel.="</TR>"; 
      $hojaExcel.="</TABLE>"; 
      }else 
        print ("<center><span class='Estilo1'>No hay informacion disponible</span></center>");
            
  echo $hojaExcel;   
  
    $login=trim($_SESSION['login']);
    $sfile="../excel/BorrarExclusiones"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 
        

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
