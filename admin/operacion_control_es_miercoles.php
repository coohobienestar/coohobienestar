<?php 
ini_set('max_execution_time',0);
session_start();               
?>

<STYLE>
H1.SaltoDePagina
{
PAGE-BREAK-AFTER: always
}
</STYLE>
<?php
include("../conexion/conectarbd.php"); ////CONEXION A LA BD
include("../funciones/generales.php");

$conexion=Conectarse(); 

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$cod_programacion = $_GET['cod_programacion'];
$cod_municipio = $_GET['cod_municipio'];
$cod_escuela = $_GET['cod_escuela'];
$cod_modalidad = $_GET['cod_modalidad'];
$tipo = $_GET['tipo'];

////BUSCAMOS EL CODIGO DE LA PROGRAMACION DE INVENTARIOS 
$sql_1a = "SELECT DISTINCT cod_programacion_inventario FROM calculo_redondeado_escuela WHERE cod_programacion=$cod_programacion";
$result_1a = mysql_query($sql_1a);
error_consulta($result_1a,$sql_1a); 

$resultado_1a = mysql_fetch_array ($result_1a); 

$cod_programacion_inv = $resultado_1a['cod_programacion_inventario'];

////BUSCAMOS SI EL USUARIO ES COORDINADOR Y TIENE ALGUNOS MUNICIPIOS ASIGNADOS
$instruccion_usu = "SELECT cod_municipio FROM usuario_municipio WHERE cod_usuario = $cod_usuario";
$consulta_usu = mysql_query ($instruccion_usu, $conexion);  

$nfilas_usu = mysql_num_rows ($consulta_usu); 

if($nfilas_usu > 0){
   $escoordinador = 1;
  } 

 if($cod_escuela != 0){
  $condicion = " WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_escuela = $cod_escuela 
                   AND calculo_requerimientos.cod_modalidad = $cod_modalidad AND calculo_requerimientos.cod_tipo_minuta = $tipo";
  }
  
 if($cod_municipio != 0){
  $condicion = " WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio 
                   AND calculo_requerimientos.cod_modalidad = $cod_modalidad AND calculo_requerimientos.cod_tipo_minuta = $tipo";
  }  

 if($cod_municipio == 0 && $cod_escuela == 0 && $tipo != 0){
  $condicion = " WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_tipo_minuta = $tipo";
  }    

  /////CONSULTA PARA HACER EL INFORME DE TODOS LOS REGISTROS
  $instruccion ="SELECT DISTINCT calculo_requerimientos.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela, 
                                 calculo_requerimientos.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, 
                                 calculo_requerimientos.cod_municipio, municipio.nombre AS nom_municipio, 
                                 calculo_requerimientos.cod_modalidad AS cod_modalidad, modalidad.nombre AS nom_modalidad, 
                                 calculo_requerimientos.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta
                  FROM calculo_requerimientos
                  INNER JOIN escuela ON escuela.cod_escuela = calculo_requerimientos.cod_escuela
                  INNER JOIN departamento ON departamento.cod_departamento = calculo_requerimientos.cod_departamento
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                  INNER JOIN modalidad ON modalidad.cod_modalidad = calculo_requerimientos.cod_modalidad
                  INNER JOIN minuta ON minuta.cod_minuta = calculo_requerimientos.cod_minuta  
                  INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = minuta.cod_tipo_minuta 
                  $condicion
                  ORDER BY tipo_minuta.nombre, calculo_requerimientos.cod_departamento, calculo_requerimientos.cod_municipio, calculo_requerimientos.cod_escuela                       
                  ";
 
  $consulta = mysql_query($instruccion);
  error_consulta($consulta,$instruccion);
  $nfilas_n = mysql_num_rows ($consulta);
  
   for($n=0; $n<$nfilas_n; $n++){
   
    ////ESCRIBIMOS LOS RESULTADOS
    $row = mysql_fetch_array($consulta);
  
    $cod_minuta_t = $row['cod_minuta'];
    $cod_escuela = $row['cod_escuela'];
    $cod_municipio = $row['cod_municipio'];  
    $cod_modalidad = $row['cod_modalidad']; 
    $cod_tipo_minuta = $row['cod_tipo_minuta']; 
    $nom_tipo_minuta = $row['nom_tipo_minuta'];  

  ////BUSCAMOS LOS DATOS DE LA PROGRAMACION ESPECIFICADA POR LOS PARAMETROS
  $instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo, calculo_requerimientos.cod_escuela AS cod_escuela, 
                                  escuela.nombre AS nom_escuela, calculo_requerimientos.cod_departamento AS cod_departamento, 
                                  departamento.nombre AS nom_departamento, calculo_requerimientos.cod_municipio, municipio.nombre AS nom_municipio
                  FROM calculo_requerimientos
                  INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                  INNER JOIN escuela ON escuela.cod_escuela = calculo_requerimientos.cod_escuela
                  INNER JOIN departamento ON departamento.cod_departamento = calculo_requerimientos.cod_departamento
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                  WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio 
                    AND calculo_requerimientos.cod_escuela = $cod_escuela AND calculo_requerimientos.cod_modalidad = $cod_modalidad 
                    AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta
                  ORDER BY calculo_requerimientos.cod_departamento, calculo_requerimientos.cod_municipio, calculo_requerimientos.cod_escuela                       
                  ";
 
  $consulta2 = mysql_query($instruccion2);
  error_consulta($consulta2,$instruccion2);
  $row2 = mysql_fetch_array($consulta2);
  
  $nom_ciclo   = $row2['nom_ciclo'];
  $nom_escuela = $row2['nom_escuela'];
  $cod_departamento = $row2['cod_departamento']; 
  $nom_departamento = $row2['nom_departamento']; 
  $nom_municipio = $row2['nom_municipio']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);    
  
  ////BUSCAMOS LOS DATOS DEL OPERADOR
  $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo AS logo
                     FROM operador
                     INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                     WHERE departamento.cod_departamento = $cod_departamento";
 
  $consulta_ope = mysql_query($instruccion_ope);
  error_consulta($consulta_ope,$consulta_ope);
  $row_ope = mysql_fetch_array($consulta_ope);  
  
  $nom_operador = $row_ope['nombre']; 
  $nit          = $row_ope['nit']; 
  $logo         = $row_ope['logo']; 
 
  ////BUSCAMOS LAS MINUTAS DE LA PROGRAMACION PARA LA ESCUELA
  $instruccion_min ="SELECT DISTINCT cod_minuta 
                     FROM calculo_requerimientos 
                     WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela AND cod_modalidad = $cod_modalidad 
                       AND cod_tipo_minuta = $cod_tipo_minuta";                                        
  $consulta_min = mysql_query($instruccion_min);
  error_consulta($consulta_min,$instruccion_min);              
  $nfilas_min = mysql_num_rows ($consulta_min);
  
  $total_cupos_am = 0;
  $cupos_am = 0;
  $total_cupos_pm = 0;
  $cupos_pm = 0;
  
  for ($a=0; $a<$nfilas_min; $a++){
    $row_min = mysql_fetch_array($consulta_min);
    
    $cod_minuta = $row_min['cod_minuta'];
    
    ////BUSCAMOS LOS CUPOS DE LA JORNADA AM
    $instruccion_am ="SELECT cupos AS cupos 
                      FROM minuta_escuela 
                      WHERE cod_escuela = $cod_escuela AND cod_jornada = 'AM' AND cod_minuta = $cod_minuta";                                        
    $consulta_am = mysql_query($instruccion_am);
    error_consulta($consulta_am,$instruccion_am);              
    $row_am = mysql_fetch_array($consulta_am); 
  
    $cupos_am = $row_am['cupos'];
    
    $total_cupos_am = $total_cupos_am + $cupos_am;
    
    ////BUSCAMOS LOS CUPOS DE LA JORNADA PM
    $instruccion_pm ="SELECT cupos AS cupos 
                      FROM minuta_escuela 
                      WHERE cod_escuela = $cod_escuela AND cod_jornada = 'PM' AND cod_minuta = $cod_minuta";                                        
    $consulta_pm = mysql_query($instruccion_pm);
    error_consulta($consulta_pm,$instruccion_pm);              
    $row_pm = mysql_fetch_array($consulta_pm); 
  
    $cupos_pm = $row_pm['cupos'];
    
    $total_cupos_pm = $total_cupos_pm + $cupos_pm;          
   }   
  
    $total_cupos = $total_cupos_am + $total_cupos_pm;
    

    ////BUSCAMOS EL PERIODO DE COMPRA Y ENTREGA DE LA ESCUELA
    $instruccion_per ="SELECT periodo.nombre AS nombre
                       FROM periodo
                       INNER JOIN escuela ON escuela.cod_periodo = periodo.cod_periodo 
                       WHERE escuela.cod_escuela = $cod_escuela";                                        
    $consulta_per = mysql_query($instruccion_per);
    error_consulta($consulta_per,$instruccion_per);              
    $row_per = mysql_fetch_array($consulta_per); 
  
    $periodo = $row_per['nombre'];
    
    if($periodo == 'SEMANAL') $per_s = "X";
    if($periodo == 'QUINCENAL') $per_q = "X";
    if($periodo == 'MENSUAL') $per_m = "X";
    
    ////BUCAMOS EL TIPO DE MODALIDAD
    $instruccion_mod ="SELECT modalidad.nombre AS nombre FROM modalidad WHERE cod_modalidad = $cod_modalidad";                                        
    $consulta_mod = mysql_query($instruccion_mod);
    error_consulta($consulta_mod,$instruccion_mod);              
    $row_mod = mysql_fetch_array($consulta_mod); 
  
    $modalidad = $row_mod['nombre'];
    
    if($modalidad == 'DESAYUNO INDUSTRIALIZADO') $mod_d = "X";
    if($modalidad == 'DESAYUNO TRADICIONAL') $mod_d = "X";
    if($modalidad == 'ALMUERZO') $mod_a = "X";

    ////BUSCAMOS LOS MENUS DE LA MINUTA
    $instruccion4 ="SELECT DISTINCT menu.cod_menu AS cod_menu, menu.nombre AS nom_menu 
                    FROM menu 
                    INNER JOIN plato_ingrediente ON plato_ingrediente.cod_menu = menu.cod_menu
                    WHERE plato_ingrediente.cod_minuta = $cod_minuta 
                    ORDER BY menu.cod_menu";
   
    $consulta4 = mysql_query($instruccion4);
    error_consulta($consulta4,$instruccion4);
    $nfilas = mysql_num_rows ($consulta4);
    
    for ($i=0; $i<$nfilas; $i++){
      $row4 = mysql_fetch_array($consulta4);   
      
      if($i == 0) $primer_menu = $row4['cod_menu']; 
      if($i == ($nfilas-1)) $ultimo_menu = $row4['cod_menu'];        
     }   
     
    $cad_menu = "MENU ".$primer_menu." - ".$ultimo_menu; 

   ////LLAMAMOS LA FUNCION QUE CREA LA CADENA DE FECHA 
   $cad_fecha = generar_fecha($cod_programacion);
   
  ////BUSCAMOS EL NOMBRE DEL FORMATO
  $instruccion_for ="SELECT valor FROM parametro WHERE nombre='nombre_formato_control_es'";
 
  $consulta_for = mysql_query($instruccion_for);
  error_consulta($consulta_for,$instruccion_for);
  $row_for = mysql_fetch_array($consulta_for);
  
  $nom_formato = $row_for['valor'];  
  
 ////DEFINIMOS EL TIPO DE MINUTA DE DESAYUNOS INDUSTRIALIZADOS PARA SACAR EL ESTILO 
if ($cod_tipo_minuta == 4 || $cod_tipo_minuta == 50){
  $estilo = "estilo_informes.css";
  }else{
    $estilo = "estilo_informes_2.css";
    }    

  $encargado = " DE DOCENTE ENCARGADO PAE: ";
  $prepara  = " MANIPULADOR (A) ";
  $lugar = " Institución Educativa ";
  $quitar_docente = 1;
    
////DEFINIMOS EL TIPO DE MINUTA DE HOGARES COMUNITARIOS DE RISARALDA Y TOLIMA 
if ($cod_tipo_minuta == 0 || $cod_tipo_minuta == 0 || $cod_tipo_minuta == 0){
  $encargado = " DE ENCARGADO DE HOGAR COMUNITARIO: ";
  $prepara  = " MADRE COMUNITARIA ";
  $lugar = " Hogar Comunitario ";
  $quitar_docente = 0;
  } 
  
////DEFINIMOS EL TIPO DE MINUTA DE ADULTOS MAYORES Y TOLIMA 
if ($cod_tipo_minuta == 0){
  $encargado = " DE ENCARGADO: ";
  $prepara  = " MANIPULADOR (A) ";
  $lugar = " Unidad Aplicativa ";
  $quitar_docente = 0;
  }              
  
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../estilos/<?php print("$estilo");?>">
<title>CONTROL ENTRADA Y SALIDA DE ALIMENTOS</title>
</head>
<body>
<?php
////BUSCAMOS LOS MENUS
$instruccion0 ="SELECT DISTINCT menu.cod_menu AS cod_menu, menu.nombre AS nom_menu 
                FROM menu 
                INNER JOIN plato_ingrediente ON plato_ingrediente.cod_menu = menu.cod_menu
                WHERE plato_ingrediente.cod_minuta = $cod_minuta 
                ORDER BY menu.cod_menu";
                         
$consulta0 = mysql_query($instruccion0);
error_consulta($consulta0,$instruccion0);
$nfilas0 = mysql_num_rows ($consulta0);
                                        
$logo_1 = "<img src='../imagenes/logo_min.png' width='150' height='60' />";
$logo_2 = "<img src='../imagenes/escudo.png' width='60' height='60' />";
$logo   = "<img src='../imagenes/$logo' width='100' height='60' />";


////ESCUDO POR DEPARTAMENTO
////Risaralda
if ($cod_tipo_minuta == 3 || $cod_tipo_minuta == 4 || $cod_tipo_minuta == 50){
    $logo_4 = "<img src='../imagenes/esc_risaralda.jpg' width='60' height='60' />";
  }
////Tolima
if ($cod_tipo_minuta == 16 || $cod_tipo_minuta == 17 || $cod_tipo_minuta == 18){
    $logo_4 = "<img src='../imagenes/esc_tolima.jpg' width='60' height='60' /><img src='../imagenes/tolima02.png' width='50' height='50' />";
  } 
////Quindio
if ($cod_tipo_minuta == 19 || $cod_tipo_minuta == 20 || $cod_tipo_minuta == 21 || $cod_tipo_minuta == 22 || $cod_tipo_minuta == 23){
    $logo_4 = "<img src='../imagenes/esc_quindio.jpg' width='60' height='60' />";
  } 
////Caqueta
if ($cod_tipo_minuta == 57 || $cod_tipo_minuta == 58 || $cod_tipo_minuta == 59){
    $logo_4 = "<img src='../imagenes/esc_caqueta.png' width='60' height='60' />";
  }    

////IBAGUE  
 if($cod_tipo_minuta == 85 || $cod_tipo_minuta == 86 || $cod_tipo_minuta == 87 || $cod_tipo_minuta == 88 || $cod_tipo_minuta == 89 || $cod_tipo_minuta == 90 ||
    $cod_tipo_minuta == 92 || $cod_tipo_minuta == 93 || $cod_tipo_minuta == 94 || $cod_tipo_minuta == 95 || $cod_tipo_minuta == 96 || $cod_tipo_minuta == 97){
    $logo_4 = "<img src='../imagenes/ibague_17.jpg' width='60' height='60' />";
  }   

////MINUTAS PAE ALMUERZOS
if ($cod_tipo_minuta == 3){ 
    
    $logo_1 = "<img src='../imagenes/logo_min.png' width='250' height='70' />";    
    $logo_2 = "<img src='../imagenes/escudo.png' width='97' height='90' />";
    $encabezado_formato = "República de Colombia <BR> Ministerio de Educación Nacional";
 }
 
////TIPO MINUTAS INDUSTRIALIZADOS PAE = Q ALMUERZOS SE SEPARA POR ORDEN
if ($cod_tipo_minuta == 4 || $cod_tipo_minuta == 50){
    
    $logo_1 = "<img src='../imagenes/logo_min.png' width='250' height='70' />";    
    $logo_2 = "<img src='../imagenes/escudo.png' width='97' height='90' />";
    $encabezado_formato = "República de Colombia <BR> Ministerio de Educación Nacional";  
  
  } 

 ////MINUTAS CDI Y HOGARES - VAN CON LOGO ICBF
if ($cod_tipo_minuta == 0){
    
    $logo_1 = "<img src='../imagenes/logo_icbf.png' width='97' height='90' />";  
    $logo_2 = "<img src='../imagenes/escudo.png' width='97' height='90' />";
  } 

if($cod_tipo_minuta == 0){
   $logo_1 = "<img src='../imagenes/$logo' width='134' height='60' />";
   $logo_2 = "<img src='../imagenes/escudo.png' width='97' height='90' />";
 }
  
if($cod_tipo_minuta == 0){
   $logo_1 = "<img src='../imagenes/$logo' width='97' height='90' />";
   $logo_2 = " "; 
 } 
 
////TIPO MINUTAS GIRARDOT ESCUDO ALCALDIA
if ($cod_tipo_minuta == 0 || $cod_tipo_minuta == 0 || $cod_tipo_minuta == 0){
    
    $logo_1 = "<img src='../imagenes/logo_min.png' width='250' height='70' />";    
    $logo_2 = "<img src='../imagenes/escudo.png' width='97' height='90' />&nbsp;&nbsp;<img src='../imagenes/logo_girardot.png' width='97' height='90' />";
    $encabezado_formato = "República de Colombia <BR> Ministerio de Educación Nacional";  
  
  }  
   
$hojaExcel.="<H1 class=SaltoDePagina>";

 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='45%' height='74' align='center'>$logo_4&nbsp;&nbsp$logo_1 </td>";
    $hojaExcel.="<th width='25%'>$nom_formato</th>";
    $hojaExcel.="<td width='30%' align='center'> $logo_2 &nbsp;&nbsp; $logo</td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="</table>";
    $hojaExcel.="<table width='100%'>";
    $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>Regional</strong></td>";
    $hojaExcel.="<td width='15%'>$nom_departamento</td>";
    $hojaExcel.="<td width='10%'><strong>Municipio</strong></td>";
    $hojaExcel.="<td width='25%'>$nom_municipio</td>";
    $hojaExcel.="<td width='15%'><strong># Cupos Programados</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos</td>";
    $hojaExcel.="<td width='5%'><strong>AM</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos_am</td>";
    $hojaExcel.="<td width='5%'><strong>PM</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos_pm</td>";
    $hojaExcel.="<td width='5%'><strong>Total</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>Operador</strong></td>";
    $hojaExcel.="<td width='30%'>$nom_operador</td>";
    $hojaExcel.="<td width='20%'><strong>$lugar</strong></td>";
    $hojaExcel.="<td width='40%'>$nom_escuela</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='3'>Periodo de Compra y Entrega</th>";
    $hojaExcel.="<th colspan='2'>Programa [$nom_tipo_minuta]</th>";
 $hojaExcel.=" </tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%'><strong>Semanal:</strong> $per_s </td>";
    $hojaExcel.="<td width='20%'><strong>Quincenal:</strong> $per_q</td>";
    $hojaExcel.="<td width='20%'><strong>Mensual:</strong> $per_m</td>";
    $hojaExcel.="<td width='26%'><strong>Desayuno o Complemento J.T:</strong> $mod_d</td>";
    $hojaExcel.="<td width='14%'><strong>Almuerzo:</strong> $mod_a</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table> ";
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='33%'><strong>Ciclo de Menu:</strong> &nbsp; $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu</td>";
    $hojaExcel.="<td width='33%'>$cad_fecha</td>";
    $hojaExcel.="<td width='33%'><strong>Fecha de entrega:</strong> </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td colspan='3'>&nbsp;</td>";
    $hojaExcel.="<th colspan='3'>JUEVES</th>";
    $hojaExcel.="<th colspan='3'>VIERNES</th>";
    $hojaExcel.="<th colspan='3'>LUNES</th>";  
    $hojaExcel.="<th colspan='3'>MARTES</th>";   
    $hojaExcel.="<th colspan='3'>MIERCOLES</th>";       
   if($nfilas0 == 6){
    $hojaExcel.="<th colspan='3'>&nbsp;</th>"; 
     } 
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%'>PRODUCTO</th>";
    $hojaExcel.="<th width='8%'>UNIDAD DE COMPRA</th>";
    $hojaExcel.="<th>CANTIDAD ENTREGADA EN LA UNIDAD</th>";
    $hojaExcel.="<th colspan='2'>CANTIDAD DE GASTO DIARIO</th>";
    $hojaExcel.="<th>SALIDA REAL DIARIA</th>";
    $hojaExcel.="<th colspan='2'>CANTIDAD DE GASTO DIARIO</th>";
    $hojaExcel.="<th>SALIDA REAL DIARIA</th>";           
    $hojaExcel.="<th colspan='2'>CANTIDAD DE GASTO DIARIO</th>";
    $hojaExcel.="<th>SALIDA REAL DIARIA</th>";
    $hojaExcel.="<th colspan='2'>CANTIDAD DE GASTO DIARIO</th>";
    $hojaExcel.="<th>SALIDA REAL DIARIA</th>";
    $hojaExcel.="<th colspan='2'>CANTIDAD DE GASTO DIARIO</th>";
    $hojaExcel.="<th>SALIDA REAL DIARIA</th>";
  if($nfilas0 == 6){
    $hojaExcel.="<th colspan='2'>CANTIDAD DE GASTO DIARIO</th>";
    $hojaExcel.="<th>SALIDA REAL DIARIA</th>";
    }      
    $hojaExcel.="<th width='4%'>SALDO</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT DISTINCT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_requerimientos.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria, 
                      ingrediente.unidad_base AS unidad_base 
                      FROM calculo_requerimientos 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_requerimientos.cod_categoria_ingrediente
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_modalidad = $cod_modalidad AND calculo_requerimientos.cod_tipo_minuta= $cod_tipo_minuta
                      ORDER BY calculo_requerimientos.cod_categoria_ingrediente, calculo_requerimientos.cod_ingrediente";
     
      $consulta7 = mysql_query($instruccion7);
      error_consulta($consulta7,$instruccion7);
      $nfilas7 = mysql_num_rows ($consulta7); 
            
      $cat_anterior = "";
      for ($i=0; $i<$nfilas7; $i++){
        $row7 = mysql_fetch_array($consulta7);
        
        $cod_ingrediente  = $row7['cod_ingrediente'];
        $nom_ingrediente  = $row7['nom_ingrediente'];
        $nom_ingrediente  = strtoupper($nom_ingrediente); 
        $cod_cat_ingredi  = $row7['cod_categoria'];
        $nom_cat_ingredi  = $row7['nom_categoria'];
        $cantidad         = $row7['cantidad'];
        $nom_unidad       = $row7['nom_unidad'];
        $unidad_base      = $row7['unidad_base'];
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='19'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi; 
        
        ////BUSCAMOS LA CANTIDAD 
        $sql9b = "SELECT SUM(calculo_redondeado_escuela.cantidad_redondeada) AS sum_cantidad
                  FROM calculo_redondeado_escuela 
                  WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_escuela = $cod_escuela 
                    AND calculo_redondeado_escuela.cod_modalidad = $cod_modalidad AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente
                    AND calculo_redondeado_escuela.cod_tipo_minuta= $cod_tipo_minuta";
        $result9b = mysql_query($sql9b);
        error_consulta($result9b,$sql9b); 
       
        $resultado9b = mysql_fetch_array ($result9b); 
      
        $cantidad = $resultado9b['sum_cantidad']; 
        
        ////BUSCAMOS LA UNIDAD DE MEDIDA CON QUE SE REDONDEO EL INGREDIENTE
        $sql8a = "SELECT unidad_medida.nombre AS nombre
                  FROM calculo_redondeado_escuela
                  LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_escuela = $cod_escuela 
                      AND calculo_redondeado_escuela.cod_modalidad = $cod_modalidad  AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente
                      AND calculo_redondeado_escuela.cod_tipo_minuta= $cod_tipo_minuta";
        $result8a = mysql_query($sql8a);
        error_consulta($result8a,$sql8a); 
       
        $resultado8a = mysql_fetch_array ($result8a); 

        $nom_unidad = $resultado8a['nombre'];
        
        if ($nom_unidad == ''){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $resultado8a['nombre'];
            }
    
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        
        ////BUSCAMOS LOS MENUS
        $instruccion6 ="SELECT DISTINCT menu.cod_menu AS cod_menu, menu.nombre AS nom_menu 
                        FROM menu 
                        INNER JOIN plato_ingrediente ON plato_ingrediente.cod_menu = menu.cod_menu
                        WHERE plato_ingrediente.cod_minuta = $cod_minuta 
                        ORDER BY menu.cod_menu";
                                 
        $consulta6 = mysql_query($instruccion6);
        error_consulta($consulta6,$instruccion6);
        $nfilas6 = mysql_num_rows ($consulta6);
        
        ////SE USA CUANDO HAY MINUTAS QUE TIENE MENOS DE 5 MENUS EN LA SEMANA
        if($nfilas6 < 5) $nfilas6 = 5;
        
        for ($h=0; $h<$nfilas6; $h++){             
          $row6 = mysql_fetch_array($consulta6);
          
          $cod_menu = $row6['cod_menu'];
          
         if($cod_menu <> ''){
          ////BUSCAMOS LA CANTIDAD DEL INGREDIENTE POR CADA DIA DE MENU
          $instruccion_q ="SELECT SUM(calculo_requerimientos.cantidad) AS suma  
                           FROM calculo_requerimientos 
                           WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_escuela = $cod_escuela 
                             AND calculo_requerimientos.cod_menu = $cod_menu AND calculo_requerimientos.cod_modalidad = $cod_modalidad 
                             AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_tipo_minuta= $cod_tipo_minuta";
                                   
          $consulta_q = mysql_query($instruccion_q);
          error_consulta($consulta_q,$instruccion_q);              
          $row_q = mysql_fetch_array($consulta_q);           
            if($row_q[suma] == '')  $row_q[suma] = 0;
            
            ////BUSCAMOS SI EL INGREDIENTE SE DEBE VOLVER A REDONDEAR
            $instruccion_r2 ="SELECT redondear2 FROM ingrediente WHERE cod_ingrediente = $cod_ingrediente";
                         
            $consulta_r2 = mysql_query($instruccion_r2);
            error_consulta($consulta_r2,$instruccion_r2);              
            $row_r2 = mysql_fetch_array($consulta_r2); 
            
            $redondear2 = $row_r2['redondear2'];
            
              if($redondear2 == 1){
                 ////BUSCAMOS LA UNIDAD EN QUE FUE REDONDEADA
                 $instruccion_und_2 ="SELECT calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.valor_gr_cc AS valor_gr_cc
                                      FROM calculo_redondeado_escuela
                                      INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                                      WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_escuela = $cod_escuela 
                                        AND calculo_redondeado_escuela.cod_modalidad = $cod_modalidad AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente
                                        AND calculo_redondeado_escuela.cod_tipo_minuta= $cod_tipo_minuta";
                         
                 $consulta_und_2 = mysql_query($instruccion_und_2);
                 error_consulta($consulta_und_2,$instruccion_und_2);              
                 $row_und_2 = mysql_fetch_array($consulta_und_2); 
                  
                 $valor_gr_cc_2 = $row_und_2['valor_gr_cc'];
                 
                  if($valor_gr_cc_2 > 0){ 
                    $row_q[suma] = $row_q[suma] / $valor_gr_cc_2;
                   }else{
                     $row_q[suma] = $row_q[suma];
                     }
                   $row_q[suma] = round($row_q[suma], 0);   
                } 
                
                 if($row_q[suma] != 0){
                    $unidad_base_mostrar = $unidad_base;
                   }else{
                    $unidad_base_mostrar = "&nbsp;";
                    }
  
             $suma_mostrar = $row_q[suma];
             
             }else{
              $suma_mostrar = '&nbsp;';
              $unidad_base_mostrar = '&nbsp;';            
               } 
  
            $hojaExcel.="<td align='center'>$suma_mostrar</td>";
            $hojaExcel.="<td align='center'>$unidad_base_mostrar</td>";
            $hojaExcel.="<td align='center'>&nbsp;</td>"; 
            
          
           } 
           $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="</tr>";         
         }                     
$hojaExcel.="</table>";

 //// LLAMAMOS LAS FUNCIONES QUE TRAEN LAS OBSERVACIONES     
 $cad_pro = observacion_programacion($cod_programacion,$cod_tipo_minuta,2);   
 $cad_mun = observacion_municipio($cod_programacion,$cod_municipio,$cod_tipo_minuta,2);
 $cad_esc = observacion_escuela($cod_programacion,$cod_escuela,$cod_tipo_minuta,2);  

$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='3'>OBSERVACIONES</th>";
    $hojaExcel.="<td width='87%'>&nbsp;$cad_pro</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;$cad_mun</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;$cad_esc</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='13%'><strong>BIENESTARINA</strong></td>";
    $hojaExcel.="<td width='12%'>LOTE N&deg; </td>";
    $hojaExcel.="<td width='28%'>&nbsp;</td>";
    $hojaExcel.="<td width='19%'>FECHA DE VENCIMIENTO </td>";
    $hojaExcel.="<td width='28%'>&nbsp;</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 
$hojaExcel.="<table width='100%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>COORDINADOR (A)</th>";
    $hojaExcel.="<th colspan='2'>$prepara</th>";
   if($quitar_docente == 1){ 
     $hojaExcel.="<th colspan='2'>DOCENTE</th>";
     }
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";
   if($quitar_docente == 1){     
      $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
      $hojaExcel.="<td width='23%'>&nbsp;</td>";
      }
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
   if($quitar_docente == 1){     
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    }    
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
   if($quitar_docente == 1){     
      $hojaExcel.="<td><strong>TELEFONO</strong></td>";
      $hojaExcel.="<td>&nbsp;</td>";
    }    
  $hojaExcel.="</tr>";
 $hojaExcel.="</table>";

 $hojaExcel.="</H1>";
 
////*********************************************************************************************************** 
////CREAMOS EL INFORME DE LOS INVENTARIOS SI ES NECESARIO
////SI EL CODIGO DE LA PROGRAMACION DE INVENTARIOS no ESTA VACIA GENERAMOS EL INFORME

///// $no_formtato_inv_ind si es tipo minuta industrializado no se saca formato de inventario

 ////DEFINIMOS EL TIPO DE MINUTA DE DESAYUNOS INDUSTRIALIZADOS PARA NO GENERAR FORMATO DE INVENTARIO 
if ($cod_tipo_minuta == 4 || $cod_tipo_minuta == 50 || $cod_tipo_minuta == 18 || $cod_tipo_minuta == 21 || $cod_tipo_minuta == 22 || $cod_tipo_minuta == 59 ||
    $cod_tipo_minuta == 77 || $cod_tipo_minuta == 79 || $cod_tipo_minuta == 80 || $cod_tipo_minuta == 84 || $cod_tipo_minuta == 87 || $cod_tipo_minuta == 88 ||
    $cod_tipo_minuta == 89 || $cod_tipo_minuta == 93 || $cod_tipo_minuta == 94 || $cod_tipo_minuta == 95 || $cod_tipo_minuta == 96 || $cod_tipo_minuta == 97){
  $no_formtato_inv_ind = 0;
  }else{
    $no_formtato_inv_ind = 1;
    }

if($no_formtato_inv_ind == '1'){ 

$hojaExcel.="<H1 class=SaltoDePagina>";

 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='50%' height='74'>$logo&nbsp;&nbsp;$logo_4&nbsp;&nbsp$logo_1</td>";
    $hojaExcel.="<th width='30%'>REGISTRO Y CONTROL DE INVENTARIOS</th>";
    $hojaExcel.="<td width='20%'>$logo_2</td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="</table>";
    $hojaExcel.="<table width='98%'>";
    $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>Regional</strong></td>";
    $hojaExcel.="<td width='15%'>$nom_departamento</td>";
    $hojaExcel.="<td width='10%'><strong>Municipio</strong></td>";
    $hojaExcel.="<td width='25%'>$nom_municipio</td>";
    $hojaExcel.="<td width='15%'><strong># Cupos Programados</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos</td>";
    $hojaExcel.="<td width='5%'><strong>AM</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos_am</td>";
    $hojaExcel.="<td width='5%'><strong>PM</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos_pm</td>";
    $hojaExcel.="<td width='5%'><strong>Total</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>Operador</strong></td>";
    $hojaExcel.="<td width='30%'>$nom_operador</td>";
    $hojaExcel.="<td width='20%'><strong>$lugar</strong></td>";
    $hojaExcel.="<td width='40%'>$nom_escuela</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='3'>Periodo de Compra y Entrega</th>";
    $hojaExcel.="<th colspan='2'>Programa [$nom_tipo_minuta]</th>";
 $hojaExcel.=" </tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%'><strong>Semanal:</strong> $per_s </td>";
    $hojaExcel.="<td width='20%'><strong>Quincenal:</strong> $per_q</td>";
    $hojaExcel.="<td width='20%'><strong>Mensual:</strong> $per_m</td>";
    $hojaExcel.="<td width='26%'><strong>Desayuno o Complemento J.T:</strong> $mod_d</td>";
    $hojaExcel.="<td width='14%'><strong>Almuerzo:</strong> $mod_a</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table> ";
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='33%'><strong>Ciclo de Menu:</strong> &nbsp; $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu</td>";
    $hojaExcel.="<td width='33%'>$cad_fecha</td>";
    $hojaExcel.="<td width='33%'><strong>Fecha de entrega:</strong> </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>PRODUCTO</th>";
    $hojaExcel.="<th>UNIDAD BASE</th>";
    $hojaExcel.="<th>CANTIDAD REQUERIDA</th>";
    $hojaExcel.="<th>INVENTARIO INICIAL</th>";
    $hojaExcel.="<th colspan='2'>CANTIDAD ENTREGADA</th>";
    $hojaExcel.="<th>UNIDAD MEDIDA</th>";
    $hojaExcel.="<th>INVENTARIO FINAL</th>";
  $hojaExcel.="</tr>";

////BUSCAMOS LOS INGREDIENTES QUE MANEJAN INVENTARIO
  $instruccion7 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria, 
                                  ingrediente.unidad_base AS unidad_base, calculo_redondeado_escuela.cantidad_gr_cc AS cantidad_gr_cc,
                                  calculo_redondeado_escuela.q_inventario_inicial_gr_cc AS q_inventario_inicial_gr_cc,
                                  calculo_redondeado_escuela.q_final_gr_cc AS q_final_gr_cc,
                                  calculo_redondeado_escuela.q_inventario_final_gr_cc AS q_inventario_final_gr_cc,
                                  calculo_redondeado_escuela.cantidad_redondeada AS q_redondeada_final,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida_final,
                                  unidad_medida.nombre AS nom_unidad_medida    
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida
                  WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_escuela = $cod_escuela
                    AND calculo_redondeado_escuela.cod_modalidad = $cod_modalidad AND calculo_redondeado_escuela.cod_tipo_minuta= $cod_tipo_minuta
                    AND calculo_redondeado_escuela.maneja_inventario = 1 
                  ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, calculo_redondeado_escuela.cod_ingrediente";
 
  $consulta7 = mysql_query($instruccion7);
  error_consulta($consulta7,$instruccion7);
  $nfilas7 = mysql_num_rows ($consulta7);
  
  $cat_anterior = "";
  for ($i=0; $i<$nfilas7; $i++){
    $row7 = mysql_fetch_array($consulta7);
    
    $cod_ingrediente  = $row7['cod_ingrediente'];
    $nom_ingrediente  = $row7['nom_ingrediente'];
    $nom_ingrediente  = strtoupper($nom_ingrediente); 
    $cod_cat_ingredi  = $row7['cod_categoria'];
    $nom_cat_ingredi  = $row7['nom_categoria'];
    $cantidad_gr_cc   = $row7['cantidad_gr_cc'];
    $nom_unidad       = $row7['nom_unidad'];
    $unidad_base      = $row7['unidad_base'];
    $q_inventario_inicial_gr_cc = $row7['q_inventario_inicial_gr_cc'];
    $q_final_gr_cc = $row7['q_final_gr_cc'];
    $q_inventario_final_gr_cc = $row7['q_inventario_final_gr_cc'];
    $q_redondeada_final = $row7['q_redondeada_final'];
    $nom_unidad_medida = $row7['nom_unidad_medida'];
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='8'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi; 
    
    $hojaExcel.="<tr>";
      $hojaExcel.="<td>$nom_ingrediente</td>";
      $hojaExcel.="<td align='center'>$unidad_base</td>";
      $hojaExcel.="<td align='center'>$cantidad_gr_cc</td>";
      $hojaExcel.="<td align='center'>$q_inventario_inicial_gr_cc</td>";
      $hojaExcel.="<td align='center'>$q_final_gr_cc</td>";
       if($q_final_gr_cc <= 0){
          $nom_unidad_medida = "-";
         }
      $hojaExcel.="<td align='center'>$q_redondeada_final</td>";
      $hojaExcel.="<td align='center'>$nom_unidad_medida</td>";
      $hojaExcel.="<td align='center'>$q_inventario_final_gr_cc</td>";
    $hojaExcel.="</tr>";    
    
   } 
$hojaExcel.="</table>";  

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='3'>OBSERVACIONES</th>";
    $hojaExcel.="<td width='87%'>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>COORDINADOR (A)</th>";
    $hojaExcel.="<th colspan='2'>$prepara</th>";
   if($quitar_docente == 1){     
    $hojaExcel.="<th colspan='2'>DOCENTE</th>";
    }
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";
   if($quitar_docente == 1){     
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";
    }
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
   if($quitar_docente == 1){     
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    }    
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
   if($quitar_docente == 1){     
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    }    
  $hojaExcel.="</tr>";
 $hojaExcel.="</table>";

$hojaExcel.="</H1>"; 
$hojaExcel.="<br>";  
 }        


////***********************************************************************************************************
 
 
}
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/FormatoControlES"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    
    if($escoordinador != 1){
       echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>"; 
      }

?>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
