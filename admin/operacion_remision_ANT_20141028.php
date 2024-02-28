<?php                                          
session_start();
ini_set('max_execution_time',0);
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

$conexion=Conectarse(); 

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$cod_programacion  = $_GET['cod_programacion'];
$cod_programacion2 = $_GET['cod_programacion2'];
$cod_programacion3 = $_GET['cod_programacion3'];
$cod_programacion4 = $_GET['cod_programacion4'];
$cod_programacion5 = $_GET['cod_programacion5'];
$cod_programacion6 = $_GET['cod_programacion6'];
$cod_programacion7 = $_GET['cod_programacion7'];
$cod_programacion8 = $_GET['cod_programacion8'];
$cod_programacion9 = $_GET['cod_programacion9'];
$cod_programacion10 = $_GET['cod_programacion10'];
$cod_programacion11 = $_GET['cod_programacion11'];
$cod_centro_acopio = $_GET['cod_centro_a'];
$cod_municipio = $_GET['cod_municipio'];
$cod_departamento = $_GET['cod_departamento'];
$cod_tipo_minuta = $_GET['tipo_minuta'];
$tipo_informe = $_GET['tipo_informe'];

////LLAMAMOS LA FUNCION QUE CREA LA CADENA DE FECHA 
$cad_fecha = generar_fecha($cod_programacion);
  
?>
<html>
<head>
<title>ACTA DE DESPACHO</title>
</head>
<body>
<?php
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO A CENTRO DE ACOPIO
if($tipo_informe == 1){



if($cod_centro_acopio !=0){
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11') 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio ";  
}else{
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11')";   
 }

    ////BUSCAMOS EL CICLO DE LA PROGRAMACION
    $instruccion_ciclo ="SELECT DISTINCT ciclo.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo 
                         FROM calculo_requerimientos 
                         INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                         $condicion";
   
    $consulta_ciclo = mysql_query($instruccion_ciclo);
    error_consulta($consulta_ciclo,$instruccion_ciclo);
    $row_ciclo = mysql_fetch_array($consulta_ciclo); 
    
    $cod_ciclo = $row_ciclo['cod_ciclo']; 
    $nom_ciclo = $row_ciclo['nom_ciclo']; 
    
    $nom_ciclo = substr($nom_ciclo,0,7);

    ////BUSCAMOS LOS MENUS DEL CICLO
    $instruccion_men ="SELECT DISTINCT cod_menu FROM calculo_requerimientos WHERE cod_ciclo = $cod_ciclo";
   
    $consulta_men = mysql_query($instruccion_men);
    error_consulta($consulta_men,$instruccion_men);
    $nfilas_men = mysql_num_rows ($consulta_men);
    
    for ($v=0; $v<$nfilas_men; $v++){
      $row_men = mysql_fetch_array($consulta_men);   
      
      if($v == 0) $primer_menu = $row_men['cod_menu']; 
      if($v == ($nfilas_men-1)) $ultimo_menu = $row_men['cod_menu'];        
     }   
     
    $cad_menu = "MENU ".$primer_menu." - ".$ultimo_menu;   

////EJECUTAMOS LA CONSULTA
$instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento, calculo_requerimientos.cod_centro_acopio AS cod_centro_acopio, 
                                centro_acopio.nombre AS nom_centro_acopio, calculo_requerimientos.cod_municipio AS cod_municipio, 
                                municipio.nombre AS nom_municipio
                FROM calculo_requerimientos 
                INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                $condicion
                ORDER BY calculo_requerimientos.cod_centro_acopio, calculo_requerimientos.cod_municipio                      
                ";

$consulta2 = mysql_query($instruccion2);
error_consulta($consulta2,$instruccion2);

////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
$nfilas = mysql_num_rows ($consulta2);

for($n=0; $n<$nfilas; $n++){
  $row2 = mysql_fetch_array($consulta2);

  $cod_centro_acopio = $row2['cod_centro_acopio'];
  $cod_municipio = $row2['cod_municipio'];
  $cod_departamento = $row2['cod_departamento'];    
  
  ////BUSCAMOS EL NOMBRE DE CENTRO DE ACOPIO
  $instruccion_ca ="SELECT nombre FROM centro_acopio WHERE cod_centro_acopio = $cod_centro_acopio";     
  $consulta_ca = mysql_query($instruccion_ca);
  error_consulta($consulta_ca,$instruccion_ca);
  $row_ca = mysql_fetch_array($consulta_ca);  
  
  $origen = $row_ca['nombre']; 

  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO
  $instruccion_mu ="SELECT nombre FROM municipio WHERE cod_municipio = $cod_municipio";      
  $consulta_mu = mysql_query($instruccion_mu);
  error_consulta($consulta_mu,$instruccion_mu);
  $row_mu = mysql_fetch_array($consulta_mu);  
  
  $destino = $row_mu['nombre'];   
  
  ////BUSCAMOS EL NOMBRE DEL DEPARTAMENTO
  $instruccion_de ="SELECT nombre FROM departamento WHERE cod_departamento = $cod_departamento";      
  $consulta_de = mysql_query($instruccion_de);
  error_consulta($consulta_de,$instruccion_de);
  $row_de = mysql_fetch_array($consulta_de);  
  
  $nom_departamento = $row_de['nombre'];    

  ////BUSCAMOS LOS DATOS DEL OPERADOR
  $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo AS logo
                     FROM operador
                     INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                     WHERE departamento.cod_departamento = $cod_departamento";
 
  $consulta_ope = mysql_query($instruccion_ope);
  error_consulta($consulta_ope,$instruccion_ope);
  $row_ope = mysql_fetch_array($consulta_ope);  
  
  $nom_operador = $row_ope['nombre']; 
  $nit          = $row_ope['nit']; 
  $logo         = $row_ope['logo'];     
 
  ////BUSCAMOS EL NOMBRE DEL FORMATO
  $instruccion_for ="SELECT valor FROM parametro WHERE nombre='nombre_formato_acta_despacho'";
 
  $consulta_for = mysql_query($instruccion_for);
  error_consulta($consulta_for,$instruccion_for);
  $row_for = mysql_fetch_array($consulta_for);
  
  $nom_formato = $row_for['valor']; 
  
  ////FECHA ACTUAL
  $fecha_actual = date("Y-m-d");    
  $fecha_actual = generar_fecha_corta($fecha_actual);
  
 if($centro_ant != $cod_centro_acopio){
   
   $centro_ant = $cod_centro_acopio;
 
$hojaExcel.="<H1 class=SaltoDePagina>";   
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%' border='1'> ";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%' rowspan='4'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="<th width='40%' rowspan='4'>REMISION DE DESPACHO </th>";
    $hojaExcel.="<th width='18%'>&nbsp;&nbsp;  CONSECUTIVO  &nbsp;&nbsp;</th>";
    $hojaExcel.="<td width='22%'><h5>CODIGO: L0-R-001</h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='3'>&nbsp;</td>";
    $hojaExcel.="<td><h5>VERSION: 0 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA ELAB: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA REVIC: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='50%'><strong>FECHA: </strong></td>";
    $hojaExcel.="<td width='50%'><strong>HORA DE DESPACHO: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ORIGEN: </strong></td>";
    $hojaExcel.="<td><strong>DESTINO: $destino </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>RUTA: </strong></td>";
    $hojaExcel.="<td><strong>CONDUCTOR: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>VEHICULO: </strong></td>";
    $hojaExcel.="<td><strong>FLETE: </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='25%'>PRODUCTO</td>";
    $hojaExcel.="<th width='15%'>PRESENTACION</th>";
    $hojaExcel.="<th width='20%'>EMPAQUE</th>";
    $hojaExcel.="<th width='23%'>CANTIDAD POR EMPAQUE</th>";
    $hojaExcel.="<th width='7%'>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio
                      GROUP BY calculo_redondeado_escuela.cod_ingrediente, calculo_redondeado_escuela.cod_unidad_medida
                      ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, calculo_redondeado_escuela.cod_ingrediente";
     
      $consulta7 = mysql_query($instruccion7);
      error_consulta($consulta7,$instruccion7);
      $nfilas7 = mysql_num_rows ($consulta7);
      
      $cat_anterior = "";
      for ($i=0; $i<$nfilas7; $i++){
        $row7 = mysql_fetch_array($consulta7);
        
        $cod_ingrediente = $row7['cod_ingrediente'];
        $nom_ingrediente = $row7['nom_ingrediente'];
        $nom_ingrediente = strtoupper($nom_ingrediente);
        $cod_cat_ingredi  = $row7['cod_categoria'];
        $nom_cat_ingredi  = $row7['nom_categoria'];
        $cantidad         = $row7['cantidad'];
        $cod_unidad_medida = $row7['cod_unidad_medida'];
        $nom_unidad        = $row7['nom_unidad'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='5'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr height='22'>"; 
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>&nbsp;</td>";
        $hojaExcel.="<td align='center'>&nbsp;</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='50%'>QUIEN DESPACHA </th>";
    $hojaExcel.="<th width='50%'>CONDUCTOR</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td><p>&nbsp;</p><h6><center>NOMBRE/IDENTIFICACION</center></h6></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>QUIEN RECIBE </th>";
    $hojaExcel.="<th>NOTA DE DESPACHO </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>OBSERVACIONES DE ENTREGA Y RECEPCION DE MERCANCIA </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td colspan='2'><p>&nbsp;</p>";
    $hojaExcel.="<p>&nbsp;</p></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>$nom_operador  Nit: $nit</th>";
    $hojaExcel.="<td>Vo Bo: </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="</H1>"; 
  }
 }
 echo $hojaExcel;
 
    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>";
 
}

////********************************************************************************************
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO A MUNICIPIO
if($tipo_informe == 2){

if($cod_centro_acopio !=0){
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11') 
                  AND calculo_requerimientos.cod_municipio = $cod_municipio 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";  
}else{
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11')"; 
  }

    ////BUSCAMOS EL CICLO DE LA PROGRAMACION
  $instruccion_ciclo ="SELECT DISTINCT ciclo.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo 
                       FROM calculo_requerimientos 
                       INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                       $condicion";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo); 
  
  $cod_ciclo = $row_ciclo['cod_ciclo']; 
  $nom_ciclo = $row_ciclo['nom_ciclo']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);

  ////BUSCAMOS LOS MENUS DEL CICLO
  $instruccion_men ="SELECT DISTINCT cod_menu FROM calculo_requerimientos WHERE cod_ciclo = $cod_ciclo";
 
  $consulta_men = mysql_query($instruccion_men);
  error_consulta($consulta_men,$instruccion_men);
  $nfilas_men = mysql_num_rows ($consulta_men);
  
  for ($v=0; $v<$nfilas_men; $v++){
    $row_men = mysql_fetch_array($consulta_men);   
    
    if($v == 0) $primer_menu = $row_men['cod_menu']; 
    if($v == ($nfilas_men-1)) $ultimo_menu = $row_men['cod_menu'];        
   }   
   
  $cad_menu = "MENU ".$primer_menu." - ".$ultimo_menu; 

////EJECUTAMOS LA CONSULTA
$instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento, calculo_requerimientos.cod_centro_acopio AS cod_centro_acopio, 
                                centro_acopio.nombre AS nom_centro_acopio, calculo_requerimientos.cod_municipio AS cod_municipio, 
                                municipio.nombre AS nom_municipio
                FROM calculo_requerimientos 
                INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio 
                $condicion
                ORDER BY calculo_requerimientos.cod_centro_acopio, calculo_requerimientos.cod_municipio                      
                ";

$consulta2 = mysql_query($instruccion2);
error_consulta($consulta2,$instruccion2);

////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
$nfilas = mysql_num_rows ($consulta2);

for($n=0; $n<$nfilas; $n++){
  $row2 = mysql_fetch_array($consulta2);

  $cod_centro_acopio = $row2['cod_centro_acopio'];
  $cod_municipio = $row2['cod_municipio'];
  $cod_departamento = $row2['cod_departamento'];    
  
  ////BUSCAMOS EL NOMBRE DE CENTRO DE ACOPIO
  $instruccion_ca ="SELECT nombre FROM centro_acopio WHERE cod_centro_acopio = $cod_centro_acopio";     
  $consulta_ca = mysql_query($instruccion_ca);
  error_consulta($consulta_ca,$instruccion_ca);
  $row_ca = mysql_fetch_array($consulta_ca);  
  
  $origen = $row_ca['nombre']; 

  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO
  $instruccion_mu ="SELECT nombre FROM municipio WHERE cod_municipio = $cod_municipio";      
  $consulta_mu = mysql_query($instruccion_mu);
  error_consulta($consulta_mu,$instruccion_mu);
  $row_mu = mysql_fetch_array($consulta_mu);  
  
  $destino = $row_mu['nombre'];   
  
  ////BUSCAMOS EL NOMBRE DEL DEPARTAMENTO
  $instruccion_de ="SELECT nombre FROM departamento WHERE cod_departamento = $cod_departamento";      
  $consulta_de = mysql_query($instruccion_de);
  error_consulta($consulta_de,$instruccion_de);
  $row_de = mysql_fetch_array($consulta_de);  
  
  $nom_departamento = $row_de['nombre'];    

  ////BUSCAMOS LOS DATOS DEL OPERADOR
  $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo AS logo
                     FROM operador
                     INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                     WHERE departamento.cod_departamento = $cod_departamento";
 
  $consulta_ope = mysql_query($instruccion_ope);
  error_consulta($consulta_ope,$instruccion_ope);
  $row_ope = mysql_fetch_array($consulta_ope);  
  
  $nom_operador = $row_ope['nombre']; 
  $nit          = $row_ope['nit']; 
  $logo         = $row_ope['logo'];     
 
  ////BUSCAMOS EL NOMBRE DEL FORMATO
  $instruccion_for ="SELECT valor FROM parametro WHERE nombre='nombre_formato_acta_despacho'";
 
  $consulta_for = mysql_query($instruccion_for);
  error_consulta($consulta_for,$instruccion_for);
  $row_for = mysql_fetch_array($consulta_for);
  
  $nom_formato = $row_for['valor']; 
  
  ////FECHA ACTUAL
  $fecha_actual = date("Y-m-d");    
  $fecha_actual = generar_fecha_corta($fecha_actual);

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%' border='1'> ";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%' rowspan='4'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="<th width='40%' rowspan='4'>REMISION DE DESPACHO </th>";
    $hojaExcel.="<th width='18%'>&nbsp;&nbsp;  CONSECUTIVO  &nbsp;&nbsp;</th>";
    $hojaExcel.="<td width='22%'><h5>CODIGO: L0-R-001</h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='3'>&nbsp;</td>";
    $hojaExcel.="<td><h5>VERSION: 0 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA ELAB: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA REVIC: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='50%'><strong>FECHA: </strong></td>";
    $hojaExcel.="<td width='50%'><strong>HORA DE DESPACHO: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ORIGEN: </strong></td>";
    $hojaExcel.="<td><strong>DESTINO: $destino </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>RUTA: </strong></td>";
    $hojaExcel.="<td><strong>CONDUCTOR: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>VEHICULO: </strong></td>";
    $hojaExcel.="<td><strong>FLETE: </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='25%'>PRODUCTO</td>";
    $hojaExcel.="<th width='15%'>PRESENTACION</th>";
    $hojaExcel.="<th width='20%'>EMPAQUE</th>";
    $hojaExcel.="<th width='23%'>CANTIDAD POR EMPAQUE</th>";
    $hojaExcel.="<th width='7%'>TOTAL</th>";
  $hojaExcel.="</tr>";  
  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                      GROUP BY calculo_redondeado_escuela.cod_ingrediente, calculo_redondeado_escuela.cod_unidad_medida
                      ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, calculo_redondeado_escuela.cod_ingrediente";
     
      $consulta7 = mysql_query($instruccion7);
      error_consulta($consulta7,$instruccion7);
      $nfilas7 = mysql_num_rows ($consulta7);
      
      $cat_anterior = "";
      for ($i=0; $i<$nfilas7; $i++){
        $row7 = mysql_fetch_array($consulta7);
        
        $cod_ingrediente = $row7['cod_ingrediente'];
        $nom_ingrediente = $row7['nom_ingrediente'];
        $nom_ingrediente = strtoupper($nom_ingrediente);
        $cod_cat_ingredi  = $row7['cod_categoria'];
        $nom_cat_ingredi  = $row7['nom_categoria'];
        $cantidad         = $row7['cantidad'];
        $cod_unidad_medida = $row7['cod_unidad_medida'];
        $nom_unidad        = $row7['nom_unidad'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='5'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr height='22'>"; 
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>&nbsp;</td>";
        $hojaExcel.="<td align='center'>&nbsp;</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='50%'>QUIEN DESPACHA </th>";
    $hojaExcel.="<th width='50%'>CONDUCTOR</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td><p>&nbsp;</p><h6><center>NOMBRE/IDENTIFICACION</center></h6></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>QUIEN RECIBE </th>";
    $hojaExcel.="<th>NOTA DE DESPACHO </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>OBSERVACIONES DE ENTREGA Y RECEPCION DE MERCANCIA </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td colspan='2'><p>&nbsp;</p>";
    $hojaExcel.="<p>&nbsp;</p></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>$nom_operador  Nit: $nit</th>";
    $hojaExcel.="<td>Vo Bo: </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
 
$hojaExcel.="</H1>"; 
 }
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>"; 
} 

////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO A CENTRO DE ACOPIO  SEPARADO POR TIPO DE MINUTA
if($tipo_informe == 3){

if($cod_centro_acopio !=0){
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion' OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion11') 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";  

 $cc = 1;
}else{
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion' OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion11')"; 
  
   $cc = 0;
  }       

    ////BUSCAMOS EL CICLO DE LA PROGRAMACION
  $instruccion_ciclo ="SELECT DISTINCT ciclo.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo 
                       FROM calculo_requerimientos 
                       INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                       $condicion";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo); 
  
  $cod_ciclo = $row_ciclo['cod_ciclo']; 
  $nom_ciclo = $row_ciclo['nom_ciclo']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);

  ////BUSCAMOS LOS MENUS DEL CICLO
  $instruccion_men ="SELECT DISTINCT cod_menu FROM calculo_requerimientos WHERE cod_ciclo = $cod_ciclo";
 
  $consulta_men = mysql_query($instruccion_men);
  error_consulta($consulta_men,$instruccion_men);
  $nfilas_men = mysql_num_rows ($consulta_men);
  
  for ($v=0; $v<$nfilas_men; $v++){
    $row_men = mysql_fetch_array($consulta_men);   
    
    if($v == 0) $primer_menu = $row_men['cod_menu']; 
    if($v == ($nfilas_men-1)) $ultimo_menu = $row_men['cod_menu'];        
   }   
   
  $cad_menu = "MENU ".$primer_menu." - ".$ultimo_menu; 
    
////EJECUTAMOS LA CONSULTA
$instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento, calculo_requerimientos.cod_centro_acopio AS cod_centro_acopio, 
                            centro_acopio.nombre AS nom_centro_acopio
               FROM calculo_requerimientos 
               INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
               INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
               INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_requerimientos.cod_tipo_minuta 
               $condicion  
               ORDER BY calculo_requerimientos.cod_tipo_minuta, calculo_requerimientos.cod_centro_acopio                     
              ";

$consulta2 = mysql_query($instruccion2);
error_consulta($consulta2,$instruccion2);

////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
$nfilas = mysql_num_rows ($consulta2);

$condicion2 = "";
$condicion_f = "";

for($n=0; $n<$nfilas; $n++){
  $row2 = mysql_fetch_array($consulta2);

  $cod_centro_acopio = $row2['cod_centro_acopio'];
  $cod_municipio = $row2['cod_municipio'];
  $cod_departamento = $row2['cod_departamento']; 
                                                      
  if($cc == 1){
     $condicion2 = $condicion;
    }
  if($cc == 0){
     $condicion2 = $condicion." AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";
    } 
  
  if($cod_tipo_minuta != 0){
     $condicion_f = $condicion2." AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
    }else{
      $condicion_f = $condicion2;
      }   
        
  ////BUSCAMOS LOS TIPOS DE MINUTA
  $instruccion_t ="SELECT DISTINCT calculo_requerimientos.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta
                   FROM calculo_requerimientos 
                   INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                   INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                   INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_requerimientos.cod_tipo_minuta 
                   $condicion_f  
                   ORDER BY calculo_requerimientos.cod_tipo_minuta, calculo_requerimientos.cod_centro_acopio                     
                ";
  
  $consulta_t = mysql_query($instruccion_t);
  error_consulta($consulta2,$instruccion_t);

  ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
  $nfilas_t = mysql_num_rows ($consulta_t);
  
  for($m=0; $m<$nfilas_t; $m++){
    $row_t = mysql_fetch_array($consulta_t);
  
    $tipo_min = $row_t['cod_tipo_minuta'];
    $nom_tipo_minuta = $row_t['nom_tipo_minuta'];                
    
    ////BUSCAMOS EL NOMBRE DE CENTRO DE ACOPIO
    $instruccion_ca ="SELECT nombre FROM centro_acopio WHERE cod_centro_acopio = $cod_centro_acopio";     
    $consulta_ca = mysql_query($instruccion_ca);
    error_consulta($consulta_ca,$instruccion_ca);
    $row_ca = mysql_fetch_array($consulta_ca);  
    
    $origen = $row_ca['nombre']; 
    
    ////BUSCAMOS EL NOMBRE DEL DEPARTAMENTO
    $instruccion_de ="SELECT nombre FROM departamento WHERE cod_departamento = $cod_departamento";      
    $consulta_de = mysql_query($instruccion_de);
    error_consulta($consulta_de,$instruccion_de);
    $row_de = mysql_fetch_array($consulta_de);  
    
    $nom_departamento = $row_de['nombre'];    
  
    ////BUSCAMOS LOS DATOS DEL OPERADOR
    $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo AS logo
                       FROM operador
                       INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                       WHERE departamento.cod_departamento = $cod_departamento";
   
    $consulta_ope = mysql_query($instruccion_ope);
    error_consulta($consulta_ope,$instruccion_ope);
    $row_ope = mysql_fetch_array($consulta_ope);  
    
    $nom_operador = $row_ope['nombre']; 
    $nit          = $row_ope['nit']; 
    $logo         = $row_ope['logo'];     
   
    ////BUSCAMOS EL NOMBRE DEL FORMATO
    $instruccion_for ="SELECT valor FROM parametro WHERE nombre='nombre_formato_acta_despacho'";
   
    $consulta_for = mysql_query($instruccion_for);
    error_consulta($consulta_for,$instruccion_for);
    $row_for = mysql_fetch_array($consulta_for);
    
    $nom_formato = $row_for['valor']; 
    
    ////FECHA ACTUAL
    $fecha_actual = date("Y-m-d");    
    $fecha_actual = generar_fecha_corta($fecha_actual);

 
$hojaExcel.="<H1 class=SaltoDePagina>";   
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%' border='1'> ";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%' rowspan='4'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="<th width='40%' rowspan='4'>REMISION DE DESPACHO </th>";
    $hojaExcel.="<th width='18%'>&nbsp;&nbsp;  CONSECUTIVO  &nbsp;&nbsp;</th>";
    $hojaExcel.="<td width='22%'><h5>CODIGO: L0-R-001</h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='3'>&nbsp;</td>";
    $hojaExcel.="<td><h5>VERSION: 0 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA ELAB: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA REVIC: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='50%'><strong>FECHA: </strong></td>";
    $hojaExcel.="<td width='50%'><strong>HORA DE DESPACHO: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ORIGEN: </strong></td>";
    $hojaExcel.="<td><strong>DESTINO: $destino </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>RUTA: </strong></td>";
    $hojaExcel.="<td><strong>CONDUCTOR: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>VEHICULO: </strong></td>";
    $hojaExcel.="<td><strong>FLETE: </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='25%'>PRODUCTO</td>";
    $hojaExcel.="<th width='15%'>PRESENTACION</th>";
    $hojaExcel.="<th width='20%'>EMPAQUE</th>";
    $hojaExcel.="<th width='23%'>CANTIDAD POR EMPAQUE</th>";
    $hojaExcel.="<th width='7%'>TOTAL</th>";
  $hojaExcel.="</tr>"; 

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_tipo_minuta = $tipo_min
                      GROUP BY calculo_redondeado_escuela.cod_ingrediente, calculo_redondeado_escuela.cod_unidad_medida
                      ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, calculo_redondeado_escuela.cod_ingrediente";
     
      $consulta7 = mysql_query($instruccion7);
      error_consulta($consulta7,$instruccion7);
      $nfilas7 = mysql_num_rows ($consulta7);
      
      $cat_anterior = "";
      for ($i=0; $i<$nfilas7; $i++){
        $row7 = mysql_fetch_array($consulta7);
        
        $cod_ingrediente = $row7['cod_ingrediente'];
        $nom_ingrediente = $row7['nom_ingrediente'];
        $nom_ingrediente = strtoupper($nom_ingrediente);
        $cod_cat_ingredi  = $row7['cod_categoria'];
        $nom_cat_ingredi  = $row7['nom_categoria'];
        $cantidad         = $row7['cantidad'];
        $cod_unidad_medida = $row7['cod_unidad_medida'];
        $nom_unidad        = $row7['nom_unidad'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='5'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr height='22'>"; 
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>&nbsp;</td>";
        $hojaExcel.="<td align='center'>&nbsp;</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='50%'>QUIEN DESPACHA </th>";
    $hojaExcel.="<th width='50%'>CONDUCTOR</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td><p>&nbsp;</p><h6><center>NOMBRE/IDENTIFICACION</center></h6></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>QUIEN RECIBE </th>";
    $hojaExcel.="<th>NOTA DE DESPACHO </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>OBSERVACIONES DE ENTREGA Y RECEPCION DE MERCANCIA </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td colspan='2'><p>&nbsp;</p>";
    $hojaExcel.="<p>&nbsp;</p></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>$nom_operador  Nit: $nit</th>";
    $hojaExcel.="<td>Vo Bo: </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="</H1>"; 
  }
 }
 echo $hojaExcel;
 
    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>";
 
}

////INFORME DE ACTA DE DESPACHO DE DE TODOS LOS CENTROS DE ACOPIO
if($tipo_informe == 4){

if($cod_tipo_minuta != 0){
   $condicion = " AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
   $condicion2 = " AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta";
   
   ////BUSCAMOS EL NOMBRE DEL TIPO DE MINUTA
   $instruccion_tm ="SELECT nombre FROM tipo_minuta WHERE cod_tipo_minuta = $cod_tipo_minuta";

   $consulta_tm = mysql_query($instruccion_tm);
   error_consulta($consulta_tm,$instruccion_tm);
   $row_tm = mysql_fetch_array($consulta_tm);  
    
   $nom_tipo_minuta = $row_tm['nombre'];   
   $nom_tipo_minuta = "[$nom_tipo_minuta]";
   
 }else{
    $condicion = " ";
    $condicion2 = " ";
   }

  ////FECHA ACTUAL
  $fecha_actual = date("Y-m-d");    
  $fecha_actual = generar_fecha_corta($fecha_actual);
  
  ////BUSCAMOS EL CICLO DE LA PROGRAMACION
  $instruccion_ciclo ="SELECT DISTINCT ciclo.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo 
                       FROM calculo_requerimientos 
                       INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                       WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                          OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                          OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                          OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                          OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                          OR calculo_requerimientos.cod_programacion = $cod_programacion11) 
                             $condicion";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo); 
  
  $cod_ciclo = $row_ciclo['cod_ciclo']; 
  $nom_ciclo = $row_ciclo['nom_ciclo']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);  

  ////BUSCAMOS EL DEPARTAMENTOS DE LAS MINUTA
  $instruccion_dep ="SELECT DISTINCT calculo_redondeado_escuela.cod_departamento AS cod_departamento
                     FROM calculo_redondeado_escuela 
                     INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                     WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                           $condicion2  
                     ORDER BY centro_acopio.nombre";
 
  $consulta_dep = mysql_query($instruccion_dep);
  error_consulta($consulta_dep,$instruccion_dep);
  $row_dep = mysql_fetch_array($consulta_dep);      
    
  $cod_departamento = $row_dep['cod_departamento'];  

  ////BUSCAMOS LOS DATOS DEL OPERADOR
  $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo AS logo
                     FROM operador
                     INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                     WHERE departamento.cod_departamento = $cod_departamento";
 
  $consulta_ope = mysql_query($instruccion_ope);
  error_consulta($consulta_ope,$instruccion_ope);
  $row_ope = mysql_fetch_array($consulta_ope);  
  
  $nom_operador = $row_ope['nombre']; 
  $nit          = $row_ope['nit']; 
  $logo         = $row_ope['logo'];     
  
$hojaExcel.="<table width='98%' border='1'> ";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%' rowspan='4'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="<th width='40%' rowspan='4'>REMISION DE DESPACHO </th>";
    $hojaExcel.="<th width='18%'>&nbsp;&nbsp;  CONSECUTIVO  &nbsp;&nbsp;</th>";
    $hojaExcel.="<td width='22%'><h5>CODIGO: L0-R-001</h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='3'>&nbsp;</td>";
    $hojaExcel.="<td><h5>VERSION: 0 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA ELAB: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA REVIC: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='50%'><strong>FECHA: </strong></td>";
    $hojaExcel.="<td width='50%'><strong>HORA DE DESPACHO: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ORIGEN: </strong></td>";
    $hojaExcel.="<td><strong>DESTINO: $destino </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>RUTA: </strong></td>";
    $hojaExcel.="<td><strong>CONDUCTOR: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>VEHICULO: </strong></td>";
    $hojaExcel.="<td><strong>FLETE: </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

  ////BUSCAMOS LOS CENTROS DE ACOPIO
  $instruccion0 ="SELECT DISTINCT calculo_redondeado_escuela.cod_centro_acopio AS cod_centro_acopio, centro_acopio.nombre AS nom_centro_acopio 
                  FROM calculo_redondeado_escuela 
                  INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                          $condicion2
                  ORDER BY centro_acopio.nombre";
 
  $consulta0 = mysql_query($instruccion0);
  error_consulta($consulta0,$instruccion0);
  $nfilas0 = mysql_num_rows ($consulta0);
  
$hojaExcel.="<table width='98%' border='1'>";
 $hojaExcel.="<tr>";    
  
  for ($i=0; $i<$nfilas0; $i++){
    $row0 = mysql_fetch_array($consulta0);
    
    $cod_centro_acopio = $row0['cod_centro_acopio'];
    $nom_centro_acopio = $row0['nom_centro_acopio'];
            
    if($i==0){
      $hojaExcel.="<th width='25%'>PRODUCTO</td>";
      $hojaExcel.="<th width='15%'>PRESENTACION</th>";
      $hojaExcel.="<th width='20%'>EMPAQUE</th>";
      $hojaExcel.="<th width='23%'>CANTIDAD POR EMPAQUE</th>";
      }   
    
    $hojaExcel.="<th align='center' width='7%'>TOTAL $nom_centro_acopio</th>";        
   } 
 $hojaExcel.="</tr>"; 
 
  ////BUSCAMOS LOS INGREDIENTES CON LAS UNIDADES
  $instruccion1 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad_medida,  
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                                  categoria_ingrediente.nombre AS nom_categoria_ingrediente
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                        $condicion2  
                  ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, calculo_redondeado_escuela.cod_ingrediente";
 
  $consulta1 = mysql_query($instruccion1);
  error_consulta($consulta1,$instruccion1);
  $nfilas1 = mysql_num_rows ($consulta1);
  
$hojaExcel.="<tr>";    
  
  for ($j=0; $j<$nfilas1; $j++){
    $row1 = mysql_fetch_array($consulta1);
    
    $cod_ingrediente = $row1['cod_ingrediente'];     
    $cod_unidad_medida = $row1['cod_unidad_medida'];
    $nom_ingrediente = $row1['nom_ingrediente'];
    $nom_ingrediente = strtoupper($nom_ingrediente);
    $nom_unidad_medida = $row1['nom_unidad_medida'];
    $cod_cat_ingredi = $row1['cod_categoria_ingrediente']; 
    $nom_cat_ingredi = $row1['nom_categoria_ingrediente']; 
    
    $columnas = $nfilas0 + 4; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
         
    $hojaExcel.="<td align='left' height='22'>$nom_ingrediente</td>"; 
    $hojaExcel.="<td align='center'>$nom_unidad_medida</td>";  
     
    ////BUSCAMOS LAS CANTIDADES PARA EL PRODUCTO POR CADA CENTRO DE ACOPIO 
    ////BUSCAMOS NUEVAMENTE LOS CENTROS DE ACOPIO  
    $instruccion2 ="SELECT DISTINCT calculo_redondeado_escuela.cod_centro_acopio AS cod_centro_acopio, centro_acopio.nombre AS nom_centro_acopio 
                    FROM calculo_redondeado_escuela 
                    INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                    WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                            $condicion2 
                    ORDER BY centro_acopio.nombre";
   
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $nfilas2 = mysql_num_rows ($consulta2);

    for ($k=0; $k<$nfilas2; $k++){
      $row2 = mysql_fetch_array($consulta2);
      
      $cod_centro_acopio = $row2['cod_centro_acopio'];
      
        ////BUSCAMOS LA CANTIDAD
        $instruccion_q ="SELECT SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad
                         FROM calculo_redondeado_escuela
                         WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                           AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = $cod_unidad_medida
                           AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio $condicion2";
       
        $consulta_q = mysql_query($instruccion_q);
        error_consulta($consulta_q,$instruccion_q);
        $row_q = mysql_fetch_array($consulta_q);  
        
        $cantidad = $row_q['cantidad'];   
        
        if($cantidad != ''){
           $cantidad = round($cantidad,1);
          }else{
            $cantidad = 0;
            } 
                   
      $hojaExcel.="<td align='center'>&nbsp;</td>";
      $hojaExcel.="<td align='center'>&nbsp;</td>";
      $hojaExcel.="<td align='center'>$cantidad</td>";     
     } 
     $hojaExcel.="</tr>";         
   } 

 $hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='50%'>QUIEN DESPACHA </th>";
    $hojaExcel.="<th width='50%'>CONDUCTOR</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td><p>&nbsp;</p><h6><center>NOMBRE/IDENTIFICACION</center></h6></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>QUIEN RECIBE </th>";
    $hojaExcel.="<th>NOTA DE DESPACHO </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>OBSERVACIONES DE ENTREGA Y RECEPCION DE MERCANCIA </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td colspan='2'><p>&nbsp;</p>";
    $hojaExcel.="<p>&nbsp;</p></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>$nom_operador  Nit: $nit</th>";
    $hojaExcel.="<td>Vo Bo: </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 
 
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho_centros_acopio"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>";
 }

////INFORME DE ACTA DE DESPACHO DE DE TODOS LOS CENTROS DE ACOPIO PARA DESPACHOS
if($tipo_informe == 5){

if($cod_tipo_minuta != 0){
   $condicion = " AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
   $condicion2 = " AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta";
   
   ////BUSCAMOS EL NOMBRE DEL TIPO DE MINUTA
   $instruccion_tm ="SELECT nombre FROM tipo_minuta WHERE cod_tipo_minuta = $cod_tipo_minuta";

   $consulta_tm = mysql_query($instruccion_tm);
   error_consulta($consulta_tm,$instruccion_tm);
   $row_tm = mysql_fetch_array($consulta_tm);  
    
   $nom_tipo_minuta = $row_tm['nombre'];   
   $nom_tipo_minuta = "[$nom_tipo_minuta]";
   
 }else{
    $condicion = " ";
    $condicion2 = " ";
   }

  ////FECHA ACTUAL
  $fecha_actual = date("Y-m-d");    
  $fecha_actual = generar_fecha_corta($fecha_actual);
  
  ////BUSCAMOS EL CICLO DE LA PROGRAMACION
  $instruccion_ciclo ="SELECT DISTINCT ciclo.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo 
                       FROM calculo_requerimientos 
                       INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                       WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                          OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                          OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                          OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                          OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                          OR calculo_requerimientos.cod_programacion = $cod_programacion11) 
                             $condicion";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo); 
  
  $cod_ciclo = $row_ciclo['cod_ciclo']; 
  $nom_ciclo = $row_ciclo['nom_ciclo']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);  

  ////BUSCAMOS EL DEPARTAMENTOS DE LAS MINUTA
  $instruccion_dep ="SELECT DISTINCT calculo_redondeado_escuela.cod_departamento AS cod_departamento
                     FROM calculo_redondeado_escuela 
                     INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                     WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                           $condicion2  
                     ORDER BY calculo_redondeado_escuela.cod_centro_acopio";
 
  $consulta_dep = mysql_query($instruccion_dep);
  error_consulta($consulta_dep,$instruccion_dep);
  $row_dep = mysql_fetch_array($consulta_dep);      
    
  $cod_departamento = $row_dep['cod_departamento'];  

  ////BUSCAMOS LOS DATOS DEL OPERADOR
  $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo AS logo
                     FROM operador
                     INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                     WHERE departamento.cod_departamento = $cod_departamento";
 
  $consulta_ope = mysql_query($instruccion_ope);
  error_consulta($consulta_ope,$instruccion_ope);
  $row_ope = mysql_fetch_array($consulta_ope);  
  
  $nom_operador = $row_ope['nombre']; 
  $nit          = $row_ope['nit']; 
  $logo         = $row_ope['logo'];     
  
$hojaExcel.="<table width='98%' border='1'> ";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='20%' rowspan='4'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="<th width='40%' rowspan='4'>REMISION DE DESPACHO </th>";
    $hojaExcel.="<th width='18%'>&nbsp;&nbsp;  CONSECUTIVO  &nbsp;&nbsp;</th>";
    $hojaExcel.="<td width='22%'><h5>CODIGO: L0-R-001</h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='3'>&nbsp;</td>";
    $hojaExcel.="<td><h5>VERSION: 0 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA ELAB: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><h5>FECHA REVIC: ABRIL/2014 </h5></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='50%'><strong>FECHA: </strong></td>";
    $hojaExcel.="<td width='50%'><strong>HORA DE DESPACHO: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ORIGEN: </strong></td>";
    $hojaExcel.="<td><strong>DESTINO: $destino </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>RUTA: </strong></td>";
    $hojaExcel.="<td><strong>CONDUCTOR: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>VEHICULO: </strong></td>";
    $hojaExcel.="<td><strong>FLETE: </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

  ////BUSCAMOS LOS CENTROS DE ACOPIO
  $instruccion0 ="SELECT DISTINCT calculo_redondeado_escuela.cod_centro_acopio AS cod_centro_acopio, centro_acopio.nombre AS nom_centro_acopio 
                  FROM calculo_redondeado_escuela 
                  INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11)
                       $condicion2 AND centro_acopio.grupo <> 0 
                  ORDER BY centro_acopio.grupo, centro_acopio.nombre";
 
  $consulta0 = mysql_query($instruccion0);
  error_consulta($consulta0,$instruccion0);
  $nfilas0 = mysql_num_rows ($consulta0);
  
$hojaExcel.="<table width='98%' border='1'>";
 $hojaExcel.="<tr>";    
  
  for ($i=0; $i<$nfilas0; $i++){
    $row0 = mysql_fetch_array($consulta0);
    
    $cod_centro_acopio = $row0['cod_centro_acopio'];
    $nom_centro_acopio = $row0['nom_centro_acopio'];
            
    if($i==0){
        $hojaExcel.="<th width='25%'>PRODUCTO</td>";
        $hojaExcel.="<th width='15%'>PRESENTACION</th>";
        $hojaExcel.="<th width='20%'>EMPAQUE</th>";
        $hojaExcel.="<th width='23%'>CANTIDAD POR EMPAQUE</th>";      
      }   
    
    $hojaExcel.="<th width='7%'>TOTAL $nom_centro_acopio</th>";        
   } 
 $hojaExcel.="</tr>"; 
 
  ////BUSCAMOS LOS INGREDIENTES CON LAS UNIDADES
  $instruccion1 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad_medida,  
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                                  categoria_ingrediente.nombre AS nom_categoria_ingrediente
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                        $condicion2  
                  ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, calculo_redondeado_escuela.cod_ingrediente";
 
  $consulta1 = mysql_query($instruccion1);
  error_consulta($consulta1,$instruccion1);
  $nfilas1 = mysql_num_rows ($consulta1);
  
$hojaExcel.="<tr>";    
  
  for ($j=0; $j<$nfilas1; $j++){
    $row1 = mysql_fetch_array($consulta1);
    
    $cod_ingrediente = $row1['cod_ingrediente'];     
    $cod_unidad_medida = $row1['cod_unidad_medida'];
    $nom_ingrediente = $row1['nom_ingrediente'];
    $nom_ingrediente = strtoupper($nom_ingrediente);
    $nom_unidad_medida = $row1['nom_unidad_medida'];
    $cod_cat_ingredi = $row1['cod_categoria_ingrediente']; 
    $nom_cat_ingredi = $row1['nom_categoria_ingrediente']; 
    
    $columnas = $nfilas0 + 4; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
         
    $hojaExcel.="<td align='left' height='22'>$nom_ingrediente</td>"; 
    $hojaExcel.="<td align='center'>$nom_unidad_medida</td>";  
     
    ////BUSCAMOS LAS CANTIDADES PARA EL PRODUCTO POR CADA CENTRO DE ACOPIO 
    ////BUSCAMOS NUEVAMENTE LOS CENTROS DE ACOPIO  
    $instruccion2 ="SELECT DISTINCT calculo_redondeado_escuela.cod_centro_acopio AS cod_centro_acopio, centro_acopio.nombre AS nom_centro_acopio 
                    FROM calculo_redondeado_escuela 
                    INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                    WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11)
                            $condicion2 AND centro_acopio.grupo <> 0  
                    ORDER BY centro_acopio.grupo, centro_acopio.nombre";
   
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $nfilas2 = mysql_num_rows ($consulta2);

    for ($k=0; $k<$nfilas2; $k++){
      $row2 = mysql_fetch_array($consulta2);
      
      $cod_centro_acopio = $row2['cod_centro_acopio'];
      
        ////BUSCAMOS LA CANTIDAD
        $instruccion_q ="SELECT SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad
                         FROM calculo_redondeado_escuela
                         WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11) 
                           AND calculo_redondeado_escuela.cod_ingrediente = $cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = $cod_unidad_medida
                           AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio $condicion2";
       
        $consulta_q = mysql_query($instruccion_q);
        error_consulta($consulta_q,$instruccion_q);
        $row_q = mysql_fetch_array($consulta_q);  
        
        $cantidad = $row_q['cantidad'];   
        
        if($cantidad != ''){
           $cantidad = round($cantidad,1);
          }else{
            $cantidad = 0;
            }        
      
      $hojaExcel.="<td align='center'>&nbsp;</td>";
      $hojaExcel.="<td align='center'>&nbsp;</td>";
      $hojaExcel.="<td align='center'>$cantidad</td>";     
     } 
     $hojaExcel.="</tr>";         
   } 

 $hojaExcel.="</table>";

 $hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='50%'>QUIEN DESPACHA </th>";
    $hojaExcel.="<th width='50%'>CONDUCTOR</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td><p>&nbsp;</p><h6><center>NOMBRE/IDENTIFICACION</center></h6></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>QUIEN RECIBE </th>";
    $hojaExcel.="<th>NOTA DE DESPACHO </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><p>&nbsp;</p>";
    $hojaExcel.="<p><h6><center>NOMBRE/IDENTIFICACION</center></h6></p></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='2'>OBSERVACIONES DE ENTREGA Y RECEPCION DE MERCANCIA </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td colspan='2'><p>&nbsp;</p>";
    $hojaExcel.="<p>&nbsp;</p></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>$nom_operador  Nit: $nit</th>";
    $hojaExcel.="<td>Vo Bo: </td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

 
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho_centros_acopio_Despacho"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>";
 } 

?>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
