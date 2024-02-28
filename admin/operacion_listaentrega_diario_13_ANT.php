<?php
session_start();
?>
<STYLE>
H1.SaltoDePagina
{
PAGE-BREAK-AFTER: always
}
</STYLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo_informes.css">
<?php
include("../conexion/conectarbd.php"); ////CONEXION A LA BD
include("../funciones/generales.php");
ini_set('max_execution_time',0);
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
$cod_menu= $_GET['menu'];
$fecha_menu = $_GET['fecha'];

   ////FORMATEAMOS LA FECHA PARA CONVERTIRLO AL FORMATO
   $anio = substr($fecha_menu,0,4);
   $mes  = substr($fecha_menu,4,2);
   $dia  = substr($fecha_menu,6,2);
   
   $fecha_menu = $anio."-".$mes."-".$dia;
   
   $fecha_menu = generar_fecha_corta($fecha_menu);

 if($cod_municipio != 0){
  $condicion = " WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio AND
                       calculo_requerimientos.cod_escuela = $cod_escuela AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                   AND calculo_requerimientos.cod_tipo_minuta = $tipo AND calculo_requerimientos.cod_menu = $cod_menu";
  }else{                                                          
     if($tipo != '0'){
        $condicion = " WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND minuta.cod_tipo_minuta = $tipo 
                         AND calculo_requerimientos.cod_menu = $cod_menu ";  
      }else{ 
        $condicion = " WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_menu = $cod_menu";
        }
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
                                  departamento.nombre AS nom_departamento, calculo_requerimientos.cod_municipio, municipio.nombre AS nom_municipio, 
                                  centro_zonal.nombre AS nom_centro_zonal
                  FROM calculo_requerimientos
                  INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                  INNER JOIN escuela ON escuela.cod_escuela = calculo_requerimientos.cod_escuela
                  INNER JOIN departamento ON departamento.cod_departamento = calculo_requerimientos.cod_departamento
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                  INNER JOIN centro_zonal ON centro_zonal.cod_centro_zonal = municipio.cod_centro_zonal
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
  $nom_centro_zonal = $row2['nom_centro_zonal'];
  
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
  
  $columnas = 14 + ($nfilas_re * 2); ////14 numero de columnas fijas mas las de rango de edad variables por 2 veces q se deben dibujar

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

  ////BUSCAMOS EL ENCABEZADO DEL FORMATO
  $instruccion_for ="SELECT valor FROM parametro WHERE nombre='encabezad_formato_entrega_2013'";
 
  $consulta_for = mysql_query($instruccion_for);
  error_consulta($consulta_for,$instruccion_for);
  $row_for = mysql_fetch_array($consulta_for);
  
  $encabezado_formato = $row_for['valor'];    
  
  ////BUSCAMOS EL NOMBRE DEL FORMATO
  $instruccion_for ="SELECT valor FROM parametro WHERE nombre='nombre_formato_entrega_2013'";
 
  $consulta_for = mysql_query($instruccion_for);
  error_consulta($consulta_for,$instruccion_for);
  $row_for = mysql_fetch_array($consulta_for);
  
  $nombre_formato = $row_for['valor'];      
  
?>
<html>
<head>
<title>LISTA DE ENTREGA DIARIO v.2013</title>
</head>
<body>
<?php

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
  $hojaExcel.="<table width='98%' height='80'>";
   $hojaExcel.="<tr>";
    $hojaExcel.="<td width='37%' rowspan='2' height='74' align='center'><img src='../imagenes/escudo.png' width='97' height='90' /> &nbsp; <img src='../imagenes/logo_icbf.png' width='95' height='91' /></td>";
    $hojaExcel.="<th width='50%' align='center' height='60'>$encabezado_formato </th>";
    $hojaExcel.="<td width='13%' rowspan='2'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="<tr>";
    $hojaExcel.="<th align='center' height='30'>$nombre_formato</th>";
    $hojaExcel.="</tr>";
    $hojaExcel.="</table>";
    $hojaExcel.="<table width='98%'>";
    $hojaExcel.="<tr>";                      
    $hojaExcel.="<td><strong>SEDE EDUCATIVA:</strong> $nom_escuela</td>";
    $hojaExcel.="<td><strong>MODALIDAD:</strong> $nom_tipo_minuta</td>";
   $hojaExcel.="</tr>";
 $hojaExcel.="</table>";
 
 $hojaExcel.="<table width='98%'>";
    
      ////BUSCAMOS LA CANTIDAD DE MENUS Q SE VAN A MANDAR
      $instruccion_men ="SELECT DISTINCT calculo_requerimientos.cod_menu AS cod_menu
                      FROM calculo_requerimientos 
                      INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
     
      $consulta_men = mysql_query($instruccion_men);
      error_consulta($consulta_men,$instruccion_men);
      $nfilas_men = mysql_num_rows ($consulta_men);

      ////BUSCAMOS LOS RANGOS DE EDAD
      $instruccion6 ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre, 
                                      calculo_requerimientos.cupos AS cupos
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
        $cupos = $row6['cupos'];
         
        $hojaExcel.="<tr>";
        $hojaExcel.="<td width='20%'><strong>No. CUPOS ADJUDICADOS $nom_rango:</strong> $cupos</td>";
        $hojaExcel.="<td width='20%'><strong>No. CUPOS ATENDIDOS $nom_rango: </strong> $cupos</td>";
        $hojaExcel.="<td width='20%'><strong>No. DIAS A ATENDER $nom_rango:</strong> 1</td>";
        if($i==0){
          $hojaExcel.="<td width='40%' rowspan='$nfilas6'><strong>SEMANAS DE CICLO DE MENU ENTREGADAS:</strong> <br> $cad_fecha - $nom_ciclo &nbsp;&nbsp; $cad_menu</td>";       
          } 
        $hojaExcel.="</tr>";
       }     
   $hojaExcel.="</table>";    
   $hojaExcel.="<table width='98%'>";
   $hojaExcel.="<tr>";
   $hojaExcel.="<td width='50%'><strong>OPERADOR: </strong>$nom_operador</td>";
   $hojaExcel.="<td width='50%'><strong>FECHA: </strong> $fecha_menu</td>";
   $hojaExcel.="</tr>";
   $hojaExcel.="</table>";
   $hojaExcel.="<table width='98%'>";  
   $hojaExcel.="<tr>";
   $hojaExcel.="<td width='100%'><strong>NOMBRE DE DOCENTE ENCARGADO PAE: </strong></td>";
   $hojaExcel.="</tr>";     
   $hojaExcel.="</table>";   
   $hojaExcel.="<table width='98%'>";  
   $hojaExcel.="<tr>";
   $hojaExcel.="<td width='33%'><strong>REGIONAL: </strong> $nom_departamento</td>";
   $hojaExcel.="<td width='33%'><strong>CENTRO ZONAL: </strong> $nom_centro_zonal</td>";
   $hojaExcel.="<td width='34%'><strong>MUNICIPIO O VEREDA: </strong> $nom_municipio</td>";      
   $hojaExcel.="</tr>";     
   $hojaExcel.="</table>";
   
  
$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='2%'  rowspan='2' align='center'>#</th>";
    $hojaExcel.="<th width='20%' rowspan='2' align='center'>ALIMENTO</th>";
    $hojaExcel.="<th width='15%' colspan='$nfilas_re' rowspan='1' align='center'>Cantidad de alimento según minuta por grupo de edad</th>";
    $hojaExcel.="<th width='15%' colspan='$nfilas_re' rowspan='1' align='center'>Cantidad de alimentos por n° de niños en unidad de medida</th>";
    $hojaExcel.="<th width='5%' rowspan='2' align='center'>Suma total en unidad de medida</th>";
    $hojaExcel.="<th width='2%' rowspan='2' align='center'>Unidad de medida</th>";
    $hojaExcel.="<th width='4%' rowspan='2' align='center'>Cantidad Total Entregada</th>";
    $hojaExcel.="<th width='8%' rowspan='2' align='center'>Unidad de Medida</th>"; 
    $hojaExcel.="<th width='11%' colspan='3' rowspan='1' align='center'>Cantidad Entregada</th>";
    $hojaExcel.="<th width='8%' colspan='2' rowspan='1' align='center'>Especificaciones Tecnicas</th>";
    $hojaExcel.="<th width='10%' colspan='3' rowspan='1' align='center'>Devolucion</th>";
     
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
        
        $hojaExcel.="<td rowspan='1' align='center'>$nom_rango</td>";        
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
      
      for ($i=0; $i<$nfilas6; $i++){
        $row6 = mysql_fetch_array($consulta6);
        
        $cod_rango = $row6['cod_rango_edad'];
        $nom_rango = $row6['nombre'];
        
        $hojaExcel.="<td rowspan='1' align='center'>$nom_rango</td>";        
       }

       $hojaExcel.="<td rowspan='1' align='center'>Total</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>C</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>NC</td>";
       $hojaExcel.="<td rowspan='1' align='center'>C</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>NC</td>";
       $hojaExcel.="<td rowspan='1' align='center'>SI</td>";
       $hojaExcel.="<td rowspan='1' align='center'>NO</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>Cantidad</td>";              
         
   $hojaExcel.="</tr>"; 
          
 $hojaExcel.="<tr>";
      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_requerimientos.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria, 
                      SUM(calculo_requerimientos.cantidad) AS cantidad, ingrediente.unidad_base AS unidad_base 
                      FROM calculo_requerimientos 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_requerimientos.cod_categoria_ingrediente 
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_escuela = $cod_escuela 
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta AND calculo_requerimientos.cod_menu = $cod_menu
                      GROUP BY calculo_requerimientos.cod_ingrediente
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
        $unidad_base      = $row7['unidad_base'];
        
        $conse = $i + 1;
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='$columnas' align='center'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<td>$conse</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        
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
                             AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta
                             AND calculo_requerimientos.cod_menu = $cod_menu";
                                   
          $consulta_q = mysql_query($instruccion_q);
          error_consulta($consulta_q,$instruccion_q);              
          $row_q = mysql_fetch_array($consulta_q);   
          
          if($row_q[racion] == '')  $row_q[racion] = 0;

          $hojaExcel.="<td align='center'>$row_q[racion]</td>";
           
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
                               AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta
                               AND calculo_requerimientos.cod_menu = $cod_menu";
                                     
            $consulta_qt = mysql_query($instruccion_qt);
            error_consulta($consulta_qt,$instruccion_qt);              
            $row_qt = mysql_fetch_array($consulta_qt);    
            
            if($row_qt[cantidad] == '')  $row_qt[cantidad] = 0;

            $hojaExcel.="<td align='center'>$row_qt[cantidad]</td>";
           }  
        $hojaExcel.="<td align='center'>$cantidad</td>"; 
        $hojaExcel.="<td align='center'>$unidad_base</td>";   
 
        ////BUSCAMOS LOS INGREDIENTES
        $instruccion8 ="SELECT calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                               unidad_medida.valor_gr_cc AS valor_gr_cc, ingrediente.redondear AS redondear
                        FROM calculo_redondeado_escuela
                        LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida
                        INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente
                        WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                          AND calculo_redondeado_escuela.cod_escuela = $cod_escuela AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente 
                          AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta";
       
        $consulta8 = mysql_query($instruccion8);
        error_consulta($consulta8,$instruccion8);
        $nfilas8 = mysql_num_rows ($consulta8);
        
        for ($j=0; $j<$nfilas8; $j++){
          $row8 = mysql_fetch_array($consulta8);
          
          $cod_unidad = $row8['cod_unidad_medida'];          
          $valor_gr_cc = round($row8['valor_gr_cc'],1);
          $redondear = $row8['redondear'];
          
          $cantidad_redondeada = $cantidad / $valor_gr_cc;
          
          if($cantidad_redondeada < 1){
       
            if($redondear == 1){
              $cantidad_redondeada = 1;
              ////REDONDEAMOS LAS CANTIDADES DE LA UNIDAD ARITMETICAMENTE
              $cantidad_redondeada = round($cantidad_redondeada, 0);
             }else{
                if($cantidad_redondeada < '0.1'){////Si la cantidad es menor a 0.1 kilo,  se pone  la cantidad en 0.1 litro Se pone las cantidades en GR/CC para mas facil manejo
                   $cantidad_redondeada = '0.1';
                 } 
               ////NO REDONDEAMOS LAS CANTIDADES Y LA DEJAMOS CON UN SOLO DECIMAL
               $cantidad_redondeada = round($cantidad_redondeada, 1);
               } 
            }else{
              if($redondear == 1){
                 ////REDONDEAMOS LAS CANTIDADES DE LA UNIDAD SIEMPRE HACIA ARRIBA SI EL INGREDIENTE SE REDONDEA
                 $cad_cant = explode('.',$cantidad_redondeada);
                 $entero = $cad_cant[0];
                 $decimales = $cad_cant[1]; 
                 $decimales = substr($decimales, 0, 1);
              
                 $cantidad_redondeada = $entero.".".$decimales;
                                
                 $cantidad_redondeada = ceil($cantidad_redondeada);
               }else{
                 ////NO REDONDEAMOS LAS CANTIDADES Y LA DEJAMOS CON UN SOLO DECIMAL
                 $cantidad_redondeada = round($cantidad_redondeada, 1);
                 } 
              } 
          
          if($cod_unidad == 0){
            $nom_unidad = "GR/CC";
            }else{
              $nom_unidad = $row8['nom_unidad'];
              }
          $hojaExcel.="<td align='center'>$cantidad_redondeada</td>";
          $hojaExcel.="<td align='center'>$nom_unidad</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
          $hojaExcel.="<td align='center'>&nbsp;</td>";
  
         }                     
       $hojaExcel.="</tr>";  
      }    
$hojaExcel.="</table>";

 //// LLAMAMOS LAS FUNCIONES QUE TRAEN LAS OBSERVACIONES     
 $cad_pro = observacion_programacion($cod_programacion,$cod_tipo_minuta,1);   
 $cad_mun = observacion_municipio($cod_programacion,$cod_municipio,$cod_tipo_minuta,1);
 $cad_esc = observacion_escuela($cod_programacion,$cod_escuela,$cod_tipo_minuta,1); 

$hojaExcel.="<table width='98%'>";
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
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='40%'>&nbsp;</td>";
    $hojaExcel.="<td width='10%'><strong>NOMBRE</strong></td>";
    $hojaExcel.="<td width='40%'>&nbsp;</td>";
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
 $hojaExcel.="</table>";  
 
if($cod_departamento == 2){ 
 $hojaExcel.="<br>";
 $hojaExcel.="<table width='98%'>";
 $hojaExcel.="<tr>";
 $hojaExcel.="<th colspan='2'>REPOSICIÓN O ENTREGA DE FALTANTES (En caso de presentarse devolución o faltante de viveres en la entrega)</th>";
 $hojaExcel.="</tr>";
 $hojaExcel.="<tr>";
 $hojaExcel.="<td><strong>FECHA: </strong></td>";
 $hojaExcel.="<td><strong>HORA:  </strong></td>";
 $hojaExcel.="</tr>";
 $hojaExcel.="</table>";
 
 $hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='2%'  rowspan='2' align='center'>#</th>";
    $hojaExcel.="<th width='15%' rowspan='2' align='center'>ALIMENTO</th>";
    $hojaExcel.="<th width='15%' colspan='1' rowspan='2' align='center'>Cantidad Faltante o devuelta</th>";
    $hojaExcel.="<th width='15%' colspan='1' rowspan='2' align='center'>Unidad de Medida</th>";
    $hojaExcel.="<th width='14%' colspan='3' rowspan='1' align='center'>Cantidad Entregada</th>";
    $hojaExcel.="<th width='9%' colspan='2' rowspan='1' align='center'>Especificaciones Tecnicas</th>";
    $hojaExcel.="<th width='11%' colspan='3' rowspan='1' align='center'>Devolucion</th>";
  $hojaExcel.="</tr>";
          
  $hojaExcel.="<tr>";
   $hojaExcel.="<td rowspan='1' align='center'>Total</td>"; 
   $hojaExcel.="<td rowspan='1' align='center'>C</td>"; 
   $hojaExcel.="<td rowspan='1' align='center'>NC</td>";
   $hojaExcel.="<td rowspan='1' align='center'>C</td>"; 
   $hojaExcel.="<td rowspan='1' align='center'>NC</td>";
   $hojaExcel.="<td rowspan='1' align='center'>SI</td>";
   $hojaExcel.="<td rowspan='1' align='center'>NO</td>"; 
   $hojaExcel.="<td rowspan='1' align='center'>Cantidad</td>";              
  $hojaExcel.="</tr>"; 
  
////BUSCAMOS LA CANTIDAD DE LINEAS Q SE IMPRIMIRAN PARA LAS REPOSICIONES
  $instruccion_lineas ="SELECT valor FROM parametro WHERE nombre = 'num_lineas_reposicion'";                                        
  $consulta_lineas = mysql_query($instruccion_lineas);
  error_consulta($consulta_lineas,$instruccion_lineas);              
  
  $row_lineas = mysql_fetch_array($consulta_lineas);
          
  $num_lineas= $row_lineas['valor'];
 
  for ($l=0; $l<$num_lineas; $l++){
   $hojaExcel.="<tr>"; 
    $hojaExcel.="<td height='20' align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
    $hojaExcel.="<td align='center'>&nbsp;</td>";
   $hojaExcel.="</tr>"; 
   }                    
  $hojaExcel.="</table>"; 

  $hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
  $hojaExcel.="<td><strong>OBSERVACIONES &nbsp;</strong></td>";
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
  $hojaExcel.="<td width='50%'><strong>NOMBRE PROCESADOR QUE RECIBE (Operador): </strong></td>";
  $hojaExcel.="<td width='50%'><strong>NOMBRE ENCARGADO SEDE EDUCATIVA: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
  $hojaExcel.="<td><strong>FIRMA: </strong></td>";
  $hojaExcel.="<td><strong>FIRMA: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="</table>"; 
 }      

 $hojaExcel.="</H1>";
 }
echo $hojaExcel; 

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/FormatoListaEntregaDiario"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>"; 

?>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
