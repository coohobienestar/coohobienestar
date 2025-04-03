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
<link rel="stylesheet" type="text/css" href="../estilos/estilo_informes_2.css">
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
    // echo($cod_modalidad);
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

  ////BUSCAMOS LOS RANGOS DE EDAD DE ACUERDO A LA ESCUELA LA PROGRAMACION Y LA MODALIDAD
  $instruccion_re ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre
                    FROM calculo_requerimientos 
                    INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                    WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                      AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela 
                      AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
 
  $consulta_re = mysql_query($instruccion_re);
  error_consulta($consulta_re,$instruccion_re);
  $nfilas_re = mysql_num_rows ($consulta_re);
  $row_re = mysql_fetch_array($consulta_re);
  
  $columnas = 6 + ($nfilas_re * 2); ////6 numero de columnas fijas mas las de rango de edad variables por 2 veces q se deben dibujar

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
  
  // SE HACE MODIFICACION YA QUE LOS CUPOS QUE TRAE EL ACTA LOS TRAIA DE LA TABLA CUPOS, OSEA QUE EN CASO Q CAMBIARA CUPOS Y SE GENERABA EL ACTA LOS TRAIA CON LOS NUEVOS CUPOS
  $instruccion_nueva = "SELECT cupos AS cupos FROM item_programacion WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela";
  $consulta_nueva = mysql_query($instruccion_nueva);
  error_consulta($consulta_nueva,$instruccion_nueva);
  $numeroFilas = mysql_num_rows($consulta_nueva);
  $total_cupos = 0;
  for($i=0; $i < $numeroFilas; $i++){
    $fila = mysql_fetch_array($consulta_nueva);
    $cupos1 = $fila['cupos'];
    $total_cupos = $fila['cupos'] + $total_cupos;
  }
  
  for ($a=0; $a<$nfilas_min; $a++){
    $row_min = mysql_fetch_array($consulta_min);
    $cod_minuta = $row_min['cod_minuta'];
  }

  // for ($a=0; $a<$nfilas_min; $a++){
    //   $row_min = mysql_fetch_array($consulta_min);
    
    //   $cod_minuta = $row_min['cod_minuta'];
    
    ////BUSCAMOS LOS CUPOS DE LA JORNADA AM 

    // $instruccion_am ="SELECT cupos AS cupos 
    //                   FROM minuta_escuela 
    //                   WHERE cod_escuela = $cod_escuela AND cod_jornada = 'AM' AND cod_minuta = $cod_minuta";                                        
    // $consulta_am = mysql_query($instruccion_am);
    // error_consulta($consulta_am,$instruccion_am);              
    // $row_am = mysql_fetch_array($consulta_am); 
  
    // $cupos_am = $row_am['cupos'];
    
    // $total_cupos_am = $total_cupos_am + $cupos_am;
    
    ////BUSCAMOS LOS CUPOS DE LA JORNADA PM
    //   $instruccion_pm ="SELECT cupos AS cupos 
   //                     FROM minuta_escuela 
    //                     WHERE cod_escuela = $cod_escuela AND cod_jornada = 'PM' AND cod_minuta = $cod_minuta";                                        
    //   $consulta_pm = mysql_query($instruccion_pm);
    //   error_consulta($consulta_pm,$instruccion_pm);              
    //   $row_pm = mysql_fetch_array($consulta_pm); 
  
  //   $cupos_pm = $row_pm['cupos'];
    
  //   $total_cupos_pm = $total_cupos_pm + $cupos_pm;          
  //  }   
  
  //   $total_cupos = $total_cupos_am + $total_cupos_pm;
    

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
  $instruccion_for ="SELECT valor FROM parametro WHERE nombre='nombre_formato_entrega'";
 
  $consulta_for = mysql_query($instruccion_for);
  error_consulta($consulta_for,$instruccion_for);
  $row_for = mysql_fetch_array($consulta_for);
  
  $nom_formato = $row_for['valor'];     
  
?>
<html>
<head>
  <meta charset="utf-8">
<title>LISTA DE ENTREGA</title>
</head>
<body>
<?php

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='14%' height='74'><center><img src='../imagenes/logo_icbf.png' width='100' height='100' /></center></td>";
    $hojaExcel.="<th width='72%' align='center'>$nom_formato</th>";
    $hojaExcel.="<td width='14%'><center><img src='../imagenes/$logo' width='100' height='100' /></center></td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="</table>";
    $hojaExcel.="<table width='98%'>";
    $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>Regional</strong></td>";
    $hojaExcel.="<td width='15%'>$nom_departamento</td>";
    $hojaExcel.="<td width='10%'><strong>Municipio</strong></td>";
    $hojaExcel.="<td width='25%'>$nom_municipio</td>";
    $hojaExcel.="<td width='15%'><strong>Total Cupos Programados</strong></td>";
    $hojaExcel.="<td width='4%'>$total_cupos</td>";
    // $hojaExcel.="<td width='5%'><strong>AM</strong></td>";
    // $hojaExcel.="<td width='4%'>$total_cupos_am</td>";
    // $hojaExcel.="<td width='5%'><strong>PM</strong></td>";
    // $hojaExcel.="<td width='4%'>$total_cupos_pm</td>";
    // $hojaExcel.="<td width='5%'><strong>Total</strong></td>";
    // $hojaExcel.="<td width='4%'>$cupos_programacion</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>Operador</strong></td>";
    $hojaExcel.="<td width='30%'>$nom_operador</td>";
    $hojaExcel.="<td width='20%'><strong>Unidad de Servicio</strong></td>";
    $hojaExcel.="<td width='40%'>$nom_escuela</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
// $hojaExcel.="<table width='98%'>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td colspan='3' align='center'><strong>Periodo de Compra y Entrega</strong></td>";
//     $hojaExcel.="<td colspan='2' align='center'><strong></strong></td>";
//  $hojaExcel.=" </tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td width='20%'><strong>Semanal:</strong> $per_s </td>";
//     $hojaExcel.="<td width='20%'><strong>Quincenal:</strong> $per_q</td>";
//     $hojaExcel.="<td width='20%'><strong>Mensual:</strong> $per_m</td>";
//     $hojaExcel.="<td width='26%'><strong>Desayuno o Complemento J.T:</strong> $mod_d</td>";
//     $hojaExcel.="<td width='14%'><strong>Almuerzo:</strong> $mod_a</td>";
//   $hojaExcel.="</tr>";
// $hojaExcel.="</table> ";

$queryMenus = "SELECT nombre_menu FROM programacion WHERE cod_programacion = $cod_programacion";

$querysNombreMenu = mysql_query($queryMenus);
$nfilasConsulta = mysql_num_rows ($querysNombreMenu);

for ($i=0; $i<$nfilasConsulta; $i++){
  $row6 = mysql_fetch_array($querysNombreMenu);
  $nombre_Menus = $row6['nombre_menu']; 
 }
#consulta para saber si existen intermbios en la programacion
$intercambio_programacion = "SELECT cod_programacion FROM intercambios WHERE cod_programacion = $cod_programacion";
$consulta_int_programacion = mysql_query($intercambio_programacion);
error_consulta($consulta_int_programacion,$intercambio_programacion);
$nfilas_intercambio_programacion = mysql_num_rows ($consulta_int_programacion);

#consulta para saber si existen marcas en la programacion
$marcas_programacion = "SELECT cod_programacion FROM marcas WHERE cod_programacion = $cod_programacion";
$consulta_marcas_programacion = mysql_query($marcas_programacion);
error_consulta($consulta_marcas_programacion,$marcas_programacion);
$nfilas_marcas_programacion = mysql_num_rows ($consulta_marcas_programacion);

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='33%'><strong>Ciclo de Menú:</strong> &nbsp; $nombre_Menus &nbsp;&nbsp;&nbsp;</td>";
    $hojaExcel.="<td width='33%'>$cad_fecha</td>";
    $hojaExcel.="<td width='33%'><strong><center>Programa:   $nom_tipo_minuta</center></strong> </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='2%'  rowspan='4' align='center'>#</th>";
    $hojaExcel.="<th width='20%' rowspan='4' align='center'>Producto</th>";
    if($nfilas_intercambio_programacion > 0){
      $hojaExcel.="<th width='15%' rowspan='4' align='center'>Intercambio</th>";
    }
    if($nfilas_marcas_programacion > 0){
      $hojaExcel.="<th width='10%' rowspan='4' align='center'>Marca</th>";
    }
    // $hojaExcel.="<th width='20%' colspan='$nfilas_re' rowspan='3' align='center'>Cantidad de alimento seg�n minuta por grupo de edad</th>";
    $hojaExcel.="<th width='20%' colspan='$nfilas_re' align='center'>Número de niños por Rango Edad</th>";
    // $hojaExcel.="<th width='10%' rowspan='4' align='center'>Suma total en unidad de medida GR o CC</th>";
    $hojaExcel.="<th width='15%' rowspan='4' align='center'>Unidad de compra (libra, kilo, botella, unidad)</th>";
    // $hojaExcel.="<th width='10%' rowspan='4' align='center'>Cantidad a comprar para la unidad</th>";
    $hojaExcel.="<th width='10%' rowspan='4' align='center'>Cantidad entregada en la unidad de servicio</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";

      ////BUSCAMOS LOS RANGOS DE EDAD
      $instruccion6 ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre
                      FROM calculo_requerimientos 
                      INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
     
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $nfilas6 = mysql_num_rows ($consulta6);
      
      for ($i=0; $i<$nfilas6; $i++){
        $row6 = mysql_fetch_array($consulta6);
        
        $cod_rango = $row6['cod_rango_edad'];
        $nom_rango = $row6['nombre'];
        $nom_rango = $nom_rango;
        
        $hojaExcel.="<td  width='8%' align='center'>$nom_rango</td>";      
       } 
   $hojaExcel.="</tr>";                                         
   $hojaExcel.="<tr>";
      ////BUSCAMOS LOS RANGOS DE EDAD
      $instruccion6 ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre
                      FROM calculo_requerimientos 
                      INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";

      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $nfilas6 = mysql_num_rows ($consulta6);
      
      for ($i=0; $i<$nfilas6; $i++){
        $row6 = mysql_fetch_array($consulta6);
        
        $cod_rango = $row6['cod_rango_edad'];
        ////BUSCAMOS LOS CUPOS DEL RANGO
        // $instruccion_c ="SELECT cupos AS cupos FROM minuta_escuela WHERE cod_escuela = $cod_escuela AND cod_rango_edad = $cod_rango";                                        
        $instruccion_c = "SELECT calculo_requerimientos.cod_escuela,
        calculo_requerimientos.cod_rango_edad,
        calculo_requerimientos.cupos
        From calculo_requerimientos 
        inner join rango_edad on calculo_requerimientos.cod_rango_edad = $cod_rango
        where cod_programacion = $cod_programacion and cod_escuela = $cod_escuela Limit 1";
        $consulta_c = mysql_query($instruccion_c);
        error_consulta($consulta_c,$instruccion_c);              
        $row_c = mysql_fetch_array($consulta_c); 

        $hojaExcel.="<td align='center'>$row_c[cupos]</td>";            
       }        
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";

      ////BUSCAMOS LOS RANGOS DE EDAD
      $instruccion6 ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre
                      FROM calculo_requerimientos 
                      INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
     
      $consulta6 = mysql_query($instruccion6);
      error_consulta($consulta6,$instruccion6);
      $nfilas6 = mysql_num_rows ($consulta6);
      
      for ($i=0; $i<$nfilas6; $i++){
        $row6 = mysql_fetch_array($consulta6);
        
        $nom_rango = $row6['nombre'];
        
        // $hojaExcel.="<td rowspan='2' align='center'>$nom_rango</td>";        
       }
        $hojaExcel.="<th colspan='$nfilas_re' align='center'>Cantidad de alimentos por número de niños en unidad de medida</td>";  
   $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";

      //  INICIO DE LA BUSQUEDA DE TODOS LOS INGREDIENTES DE CADA UNIDAD DE SERVICIO
      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_requerimientos.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria, 
                      SUM(calculo_requerimientos.cantidad) AS cantidad
                      FROM calculo_requerimientos 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_requerimientos.cod_categoria_ingrediente 
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_escuela = $cod_escuela 
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta
                      GROUP BY calculo_requerimientos.cod_ingrediente
                      ORDER BY calculo_requerimientos.cod_categoria_ingrediente, nom_ingrediente";
     
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
        
        $conse = $i + 1;
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='$columnas' align='center'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<td>$conse</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";


        //CONSULTA INTERCAMBIOS EN LA PROGRAMACION
        if ($nfilas_intercambio_programacion > 0){
          $instruccion_intercambio = "SELECT intercambios.cod_ingrediente_programado AS cod_ingrediente_programado,
                                      intercambios.cod_ingrediente_intercambio AS cod_ingrediente_intercambio, 
                                      ingrediente.nombre AS nombre_intercambio
                                      FROM intercambios
                                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = intercambios.cod_ingrediente_intercambio
                                      WHERE cod_programacion = $cod_programacion AND cod_ingrediente_programado = $cod_ingrediente";
                                      
          $consulta_intercambio = mysql_query($instruccion_intercambio);
          error_consulta($consulta_intercambio,$instruccion_intercambio);
          $nfilas_intercambio = mysql_num_rows ($consulta_intercambio);
          if ($nfilas_intercambio == 0){
            $hojaExcel.="<td width='3%'></td>";
          }else{
            for ($inter=0;$inter<$nfilas_intercambio;$inter++){
              $row_intercambio = mysql_fetch_array($consulta_intercambio);
              $intercambio = $row_intercambio['nombre_intercambio'];
              $hojaExcel.="<td width='3%'>$intercambio</td>";
            }
          }   
        }

        //CONSULTA MARCAS EN LA PROGRAMACION
        if ($nfilas_marcas_programacion > 0){
          $instruccion_marcas = "SELECT *
                                      FROM marcas
                                      WHERE cod_programacion = $cod_programacion AND cod_ingrediente_programado = $cod_ingrediente";
                                                
          $consulta_marcas = mysql_query($instruccion_marcas);
          error_consulta($consulta_marcas,$instruccion_marcas);
          $nfilas_marcas = mysql_num_rows ($consulta_marcas);
          if ($nfilas_marcas == 0){
            $hojaExcel.="<td width='5%'></td>";
          }else{
            for ($marc=0;$marc<$nfilas_marcas;$marc++){
              $row_marca = mysql_fetch_array($consulta_marcas);
              $marca = $row_marca['nombre_marca'];
              $hojaExcel.="<td width='5%'>$marca</td>";
            }
          }   
        }
        
          ////BUSCAMOS LOS RANGOS DE EDAD DE ACUERDO A LA ESCUELA LA PROGRAMACION Y LA MODALIDAD
          $instruccion6 ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre
                          FROM calculo_requerimientos 
                          INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                          WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                            AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                            AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
                                  
          $consulta6 = mysql_query($instruccion6);
          error_consulta($consulta6,$instruccion6);
          $nfilas6 = mysql_num_rows ($consulta6);
          
          for ($h=0; $h<$nfilas6; $h++){             
            $row6 = mysql_fetch_array($consulta6);
            
            $cod_rango = $row6['cod_rango_edad'];
            
            ////BUSCAMOS EL VALOR DEL INGREDIENTE DE ACUERDO AL RANGO DE EDAD
            $instruccion_q ="SELECT (SUM(calculo_requerimientos.cantidad) / calculo_requerimientos.cupos) AS racion 
                            FROM calculo_requerimientos 
                            WHERE calculo_requerimientos.cod_programacion = $cod_programacion  AND calculo_requerimientos.cod_municipio = $cod_municipio
                              AND calculo_requerimientos.cod_escuela = $cod_escuela 
                              AND calculo_requerimientos.cod_rango_edad = $cod_rango AND calculo_requerimientos.cod_modalidad = $cod_modalidad 
                              AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
                                    
            $consulta_q = mysql_query($instruccion_q);
            error_consulta($consulta_q,$instruccion_q);              
            $row_q = mysql_fetch_array($consulta_q);   
            
            if($row_q[racion] == '')  $row_q[racion] = 0;

            // $hojaExcel.="<td align='center'>$row_q[racion]</td>";
            
            }  
  
            ////BUSCAMOS LOS RANGOS DE EDAD
            $instruccion6 ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre
                            FROM calculo_requerimientos 
                            INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                            WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                              AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                              AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
          
            $consulta6 = mysql_query($instruccion6);
            error_consulta($consulta6,$instruccion6);
            $nfilas6 = mysql_num_rows ($consulta6);
          
            for ($m=0; $m<$nfilas6; $m++){
              $row6 = mysql_fetch_array($consulta6);
            
              $cod_rango = $row6['cod_rango_edad'];
              
              ////BUSCAMOS EL VALOR DEL INGREDIENTE DE ACUERDO AL RANGO DE EDAD
              $instruccion_qt ="SELECT SUM(calculo_requerimientos.cantidad) AS cantidad 
                              FROM calculo_requerimientos 
                              WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio 
                                AND calculo_requerimientos.cod_escuela = $cod_escuela 
                                AND calculo_requerimientos.cod_rango_edad = $cod_rango AND calculo_requerimientos.cod_modalidad = $cod_modalidad 
                                AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
                                      
              $consulta_qt = mysql_query($instruccion_qt);
              error_consulta($consulta_qt,$instruccion_qt);              
              $row_qt = mysql_fetch_array($consulta_qt);    
              
              if($row_qt[cantidad] == '')  $row_qt[cantidad] = 0;

              // $hojaExcel.="<td align='center'>$row_qt[cantidad]</td>";
            }  
          $hojaExcel.="<td colspan='$nfilas_re' align='center'>$cantidad</td>";   
  
          ////BUSCAMOS LOS INGREDIENTES
          $instruccion8 ="SELECT calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad, 
                                calculo_redondeado_escuela.cantidad_gr_cc AS cantidad_gr_cc, calculo_redondeado_escuela.cantida_bruta AS cantida_bruta, 
                                calculo_redondeado_escuela.cantidad_redondeada AS cantidad_redondeada
                          FROM calculo_redondeado_escuela
                          LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida
                          WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                            AND calculo_redondeado_escuela.cod_escuela = $cod_escuela AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente 
                            AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta";
    
          $consulta8 = mysql_query($instruccion8);
          error_consulta($consulta8,$instruccion8);
          $nfilas8 = mysql_num_rows ($consulta8);
          
          for ($j=0; $j<$nfilas8; $j++){
            $row8 = mysql_fetch_array($consulta8);
            
            $cod_unidad = $row8['cod_unidad_medida'];          
            $cantidad_bruta = round($row8['cantida_bruta'],1);
            $cantidad_redondeada = $row8['cantidad_redondeada'];
            
            if($cod_unidad == 0){
              $nom_unidad = "GR/CC";
              }else{
                $nom_unidad = $row8['nom_unidad'];
                }
            
            $hojaExcel.="<td align='center'>$nom_unidad</td>";
            // $hojaExcel.="<td align='center'>$cantidad_bruta</td>";
            $hojaExcel.="<td align='center'>$cantidad_redondeada</td>";
          
          }                     
        $hojaExcel.="</tr>";  
        }    
$hojaExcel.="</table>";

 //// LLAMAMOS LAS FUNCIONES QUE TRAEN LAS OBSERVACIONES     
 $cad_pro = observacion_programacion($cod_programacion,$cod_tipo_minuta,1);  
 $cad_intercambios = observacion_programacion_intercambios($cod_programacion,$cod_tipo_minuta,1);   
 $cad_mun = observacion_municipio($cod_programacion,$cod_municipio,$cod_tipo_minuta,1);
 $cad_esc = observacion_escuela($cod_programacion,$cod_escuela,$cod_tipo_minuta,1);  

// SECCION FIN OBSERVACIONES
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='3'><h4>OBSERVACIONES</h4></th>";
    $hojaExcel.="<td width='87%' height='30px'></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td height='30px'></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td height='30px'></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
// SECCION FIN OBSERVACIONES

// SECCION REPOSICIONES
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='3'><h4>REPOSICIONES</h4></th>";
    // $hojaExcel.="<td width='87%' height='30px'>$cad_intercambios</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td height='30px'></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td height='30px'></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
// SECCION FIN REPOSICIONES

// SECCION LOTES
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='3'><h4>LOTES</h4></th>";
    $hojaExcel.="<td width='87%' height='30px'>&nbsp;$cad_pro</td>";
  $hojaExcel.="</tr>";

$hojaExcel.="</table>";
// SECCION FIN LOTES

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='13%'><strong>BIENESTARINA</strong></td>";
    $hojaExcel.="<td width='12%'>LOTE N&deg; </td>";
    $hojaExcel.="<td width='28%'>&nbsp;</td>";
    $hojaExcel.="<td width='19%'>FECHA DE VENCIMIENTO </td>";
    $hojaExcel.="<td width='28%'>&nbsp;</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>PERSONA RESPONSABLE QUE ENTREGA LOS ALIMENTOS</th>";
    $hojaExcel.="<th colspan='2'>PERSONA RESPONSABLE QUE RECIBE LOS ALIMENTOS</th>";
    // $hojaExcel.="<th colspan='2'>DOCENTE RESPONSABLE </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='23%'>&nbsp;</td>";  
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>CEDULA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";    
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>TELEFONO</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>"; 
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>"; 
  $hojaExcel.="</tr>";
 $hojaExcel.="</table>";

 $hojaExcel.="</H1>";
 }
echo $hojaExcel; 

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/FormatoListaEntrega"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>"; 

?>
</body>
</html>

<?php
// Cerrar conexi�n
mysql_close ($conexion);   
?>
