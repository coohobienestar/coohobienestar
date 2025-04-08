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
$cod_programacion12 = $_GET['cod_programacion12'];
$cod_programacion13 = $_GET['cod_programacion13'];
$cod_programacion14 = $_GET['cod_programacion14'];
$cod_programacion15 = $_GET['cod_programacion15'];
$cod_programacion16 = $_GET['cod_programacion16'];
$cod_programacion17 = $_GET['cod_programacion17'];
$cod_programacion18 = $_GET['cod_programacion18'];
$cod_programacion19 = $_GET['cod_programacion19'];
$cod_programacion20 = $_GET['cod_programacion20'];
$cod_programacion21 = $_GET['cod_programacion21'];
$cod_programacion22 = $_GET['cod_programacion22'];
$cod_programacion23 = $_GET['cod_programacion23'];
$cod_programacion24 = $_GET['cod_programacion24'];
$cod_centro_acopio = $_GET['cod_centro_a'];
$cod_municipio = $_GET['cod_municipio'];
$cod_departamento = $_GET['cod_departamento'];
$cod_tipo_minuta = $_GET['tipo_minuta'];
$tipo_informe = $_GET['tipo_informe'];
$categoria = $_GET['categoria'];
$periodo_entrega = $_GET['periodo_entrega'];

$periodo_entrega = str_replace('_',' ',$periodo_entrega);

////LLAMAMOS LA FUNCION QUE CREA LA CADENA DE FECHA 
$cad_fecha = generar_fecha($cod_programacion); 

  //////definimos LOGO 
  if($cod_tipo_minuta == 6 || $cod_tipo_minuta == 7 || $cod_tipo_minuta == 51 || $cod_tipo_minuta == 74 || $cod_tipo_minuta == 15 || $cod_tipo_minuta == 60){////con logo de ICBF y construyamos
     $logo1 = " ";
    }else{////con ministerio y construyamos
       $logo1 = " ";
      }         

///////////// CODIGO QUE INCLUYE NUMERO DE PROGRAMACION EN EL INFORME///////////////
//////// SE CAMBIO $nom_formato x $cadena_programacion en los encabezados de las tablas///////////////
$cadena_programacion = "ACTA DE DESPACHO <br>";
$cadena_programacion = $cadena_programacion."PROGRAMACIONES No ";

if ($cod_programacion != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion . ' ';
if ($cod_programacion2 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion2 . ' ';
if ($cod_programacion3 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion3 . ' ';
if ($cod_programacion4 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion4 . ' ';
if ($cod_programacion5 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion5 . ' ';
if ($cod_programacion6 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion6 . ' ';
if ($cod_programacion7 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion7 . ' ';
if ($cod_programacion8 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion8 . ' ';
if ($cod_programacion9 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion9 . ' ';
if ($cod_programacion10 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion10 . ' ';
if ($cod_programacion11 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion11 . ' ';
if ($cod_programacion12 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion12 . ' ';
if ($cod_programacion13 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion13 . ' ';
if ($cod_programacion14 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion14 . ' ';
if ($cod_programacion15 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion15 . ' ';
if ($cod_programacion16 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion16 . ' ';
if ($cod_programacion17 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion17 . ' ';
if ($cod_programacion18 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion18 . ' ';
if ($cod_programacion19 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion19 . ' ';
if ($cod_programacion20 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion20 . ' ';
if ($cod_programacion21 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion21 . ' ';
if ($cod_programacion22 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion22 . ' ';
if ($cod_programacion23 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion23 . ' ';
if ($cod_programacion24 != 0)
  $cadena_programacion = $cadena_programacion . $cod_programacion24 . ' ';



/////////////


?>
<html>
<head>
<title>ACTA DE DESPACHO</title>
</head>
<body>
<?php  
                   
////GENERAMOS LA CONDICION SI ES SELECCIONADO EL FILTRO DE SOLO ALGUNA CATEGORIA
if($categoria != '0'){
  $condi_categoria = " AND calculo_redondeado_escuela.cod_categoria_ingrediente = '$categoria' ";
  }
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO A CENTRO DE ACOPIO
if($tipo_informe == 1){  

if($cod_centro_acopio !=0){
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24') 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio ";  
}else{
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24')";   
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

  //$nom_formato
  ////FECHA ACTUAL
  $fecha_actual = date("Y-m-d");    
  $fecha_actual = generar_fecha_corta($fecha_actual);
  
 if($centro_ant != $cod_centro_acopio){
   
   $centro_ant = $cod_centro_acopio;

/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }            
 
$hojaExcel.="<H1 class=SaltoDePagina>";   
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
    $hojaExcel.="<td width='78%' align='center'>$cadena_programacion</td>";
    $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $origen</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="$cad_entrega";
  $hojaExcel.="</tr>";

///////************************************************INICIO MOSTRAR MUNICIPIOS****************************************************************************//////

  ////BUSCAMOS LOS MUNICIPIOS
  $instruccion_m ="SELECT DISTINCT calculo_redondeado_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio 
                  FROM calculo_redondeado_escuela 
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                       AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio 
                  ORDER BY municipio.nombre";
 
  $consulta_m = mysql_query($instruccion_m);
  error_consulta($consulta_m,$instruccion_m);
  $nfilas_m = mysql_num_rows ($consulta_m);     
   
  for ($m=0; $m<$nfilas_m; $m++){         
    $row_m = mysql_fetch_array($consulta_m);
    
    $cod_municipio = $row_m['cod_municipio'];
    $nom_municipio = $row_m['nom_municipio'];
      
    $cad_municipio = $cad_municipio." ".$nom_municipio." || ";               
    }
   
   $cad_municipio =  substr($cad_municipio, 0, -4); 
   $hojaExcel.="</tr>";
   $hojaExcel.="<td><strong>Municipios:</strong> $cad_municipio</td>";
   $hojaExcel.="</tr>";
   $cad_municipio = "";  
$hojaExcel.="</table>";
 
///////************************************************FIN MOSTRAR MUNICIPIOS****************************************************************************//////


$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    // $hojaExcel.="<th>COD GEMINUS</td>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                      ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio  $condi_categoria
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
        $cod_geminus       = $row7['codigo_geminus'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='2'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
        // $hojaExcel.="<td>$cod_geminus</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

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
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
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
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24') 
                  AND calculo_requerimientos.cod_municipio = $cod_municipio 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";  
}else{
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24')"; 
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

/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }  

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
    $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
    $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $destino</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="$cad_entrega";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    // $hojaExcel.="<th>COD GEMINUS</td>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                      ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                            $condi_categoria
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
        $cod_geminus       = $row7['codigo_geminus'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='2'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
        // $hojaExcel.="<td>$cod_geminus</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

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
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 

$hojaExcel.="<br>";

// $hojaExcel.="<table width='98%'>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td width='17%'><strong>NOMBRE CONDUCTOR</strong></td>";
//     $hojaExcel.="<td width='33%'>&nbsp;</td>";
//     $hojaExcel.="<td width='20%'><strong>NOMBRE DESPACHADOR</strong></td>";
//     $hojaExcel.="<td width='30%'>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
// $hojaExcel.="</table>";
 
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
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24') 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";  

 $cc = 1;
}else{
 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion' OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24')"; 
  
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

/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }  
 
$hojaExcel.="<H1 class=SaltoDePagina>";   
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
    $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
    $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $origen [$nom_tipo_minuta]</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="$cad_entrega";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    // $hojaExcel.="<th>COD GEMINUS</td>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                      ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_tipo_minuta = $tipo_min
                            $condi_categoria
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
        $cod_geminus       = $row7['codigo_geminus'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='2'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
        // $hojaExcel.="<td>$cod_geminus</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

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
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
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
                          OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                          OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                          OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                          OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                          OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                          OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                          OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24) 
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
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
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
  
/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }       
  
  $hojaExcel.="<table width='98%'' height='80' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
      $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
      $hojaExcel.="<td width='13%'><center><img src='../imagenes/$logo' width='100' height='100' /></center></td>";
    $hojaExcel.="</tr>";
  $hojaExcel.="</table>";
  $hojaExcel.="<table width='98%' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="<tr>";
      $hojaExcel.="$cad_entrega";
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
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
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
      // $hojaExcel.="<td>COD GEMINUS</td>";
      $hojaExcel.="<td align='center'>PRODUCTO</td>"; 
      $hojaExcel.="<td align='center'>PRESENTACION</td>"; 
      }       
    $hojaExcel.="<td align='center'>$nom_centro_acopio</td>";    
  } 
 $hojaExcel.="</tr>"; 

///////************************************************INICIO MOSTRAR MUNICIPIOS****************************************************************************//////

    ////BUSCAMOS LOS CENTROS DE ACOPIO PARA CONSULTAR LOS MUNICIPIOS
  $instruccion0 ="SELECT DISTINCT calculo_redondeado_escuela.cod_centro_acopio AS cod_centro_acopio, centro_acopio.nombre AS nom_centro_acopio 
                  FROM calculo_redondeado_escuela 
                  INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_redondeado_escuela.cod_centro_acopio 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                          $condicion2
                  ORDER BY centro_acopio.nombre";
 
  $consulta0 = mysql_query($instruccion0);
  error_consulta($consulta0,$instruccion0);
  $nfilas0 = mysql_num_rows ($consulta0);

 $hojaExcel.="<tr>";    
  
  for ($i=0; $i<$nfilas0; $i++){
    $row0 = mysql_fetch_array($consulta0);
    
    $cod_centro_acopio = $row0['cod_centro_acopio'];
    $nom_centro_acopio = $row0['nom_centro_acopio'];   
 
    if($i==0){
      $hojaExcel.="<td align='center' colspan='2'>MUNICIPIOS</td>"; 
      }   
  
  ////BUSCAMOS LOS MUNICIPIOS
  $instruccion_m ="SELECT DISTINCT calculo_redondeado_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio 
                  FROM calculo_redondeado_escuela 
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_redondeado_escuela.cod_municipio 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                          $condicion2 AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio 
                  ORDER BY municipio.nombre";
 
  $consulta_m = mysql_query($instruccion_m);
  error_consulta($consulta_m,$instruccion_m);
  $nfilas_m = mysql_num_rows ($consulta_m);     
   
  for ($m=0; $m<$nfilas_m; $m++){         
    $row_m = mysql_fetch_array($consulta_m);
    
    $cod_municipio = $row_m['cod_municipio'];
    $nom_municipio = $row_m['nom_municipio'];
      
    $cad_municipio = $cad_municipio." ".$nom_municipio." || ";               
    }
   
   $cad_municipio =  substr($cad_municipio, 0, -4); 
   $hojaExcel.="<td align='center'>$cad_municipio</td>";
   $cad_municipio = "";  
 }
 $hojaExcel.="</tr>";
 
///////************************************************FIN MOSTRAR MUNICIPIOS****************************************************************************//////
 
  ////BUSCAMOS LOS INGREDIENTES CON LAS UNIDADES
  $instruccion1 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad_medida,  
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                                  categoria_ingrediente.nombre AS nom_categoria_ingrediente, ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente 
                  INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                        $condicion2 $condi_categoria  
                  ORDER BY calculo_redondeado_escuela.cod_categoria_ingrediente, nom_ingrediente";
 
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
    $cod_geminus     = $row1['codigo_geminus'];
    
    $columnas = $nfilas0 + 2; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
    
    // $hojaExcel.="<td align='left'>$cod_geminus</td>";     
    $hojaExcel.="<td align='left'>$nom_ingrediente</td>"; 
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
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
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
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
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
      
      $hojaExcel.="<td align='center'>$cantidad</td>";     
     } 
     $hojaExcel.="</tr>";         
   } 

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
                          OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                          OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                          OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                          OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                          OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                          OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                          OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24) 
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
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                        OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
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
  
/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }    
  
  $hojaExcel.="<table width='98%'' height='80' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
      $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
      $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="</tr>";
  $hojaExcel.="</table>";
  $hojaExcel.="<table width='98%' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="<tr>";
      $hojaExcel.="$cad_entrega";
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
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                       OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24)
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
      // $hojaExcel.="<td>COD GEMINUS</td>";
      $hojaExcel.="<td align='center'>PRODUCTO</td>"; 
      $hojaExcel.="<td align='center'>PRESENTACION</td>"; 
      }   
    
    $hojaExcel.="<td align='center'>$nom_centro_acopio</td>";        
   } 
 $hojaExcel.="</tr>"; 
 
  ////BUSCAMOS LOS INGREDIENTES CON LAS UNIDADES
  $instruccion1 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad_medida,  
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                                  categoria_ingrediente.nombre AS nom_categoria_ingrediente, ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente 
                  INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                        $condicion2 $condi_categoria 
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
    $cod_geminus       = $row1['codigo_geminus']; 
    
    $columnas = $nfilas0 + 2; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
    
    // $hojaExcel.="<td align='left'>$cod_geminus</td>";      
    $hojaExcel.="<td align='left'>$nom_ingrediente</td>"; 
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
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                         OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24)
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
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                            OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
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
      
      $hojaExcel.="<td align='center'>$cantidad</td>";     
     } 
     $hojaExcel.="</tr>";         
   } 

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
 
////********************************************************************************************
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO CON SUS MUNICIPIOS
if($tipo_informe == 6){

 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR  calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR  calculo_requerimientos.cod_programacion = '$cod_programacion24') 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";  


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

/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }  

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
    $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
    $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $destino</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="$cad_entrega";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    // $hojaExcel.="<th>COD GEMINUS</td>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                      ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                            $condi_categoria
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
        $cod_geminus       = $row7['codigo_geminus'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='2'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
        // $hojaExcel.="<td>$cod_geminus</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

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
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 

$hojaExcel.="<br>";

// $hojaExcel.="<table width='98%'>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td width='17%'><strong>NOMBRE CONDUCTOR</strong></td>";
//     $hojaExcel.="<td width='33%'>&nbsp;</td>";
//     $hojaExcel.="<td width='20%'><strong>NOMBRE DESPACHADOR</strong></td>";
//     $hojaExcel.="<td width='30%'>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
// $hojaExcel.="</table>";
 
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

////********************************************************************************************
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO POR ESCUELA SEPARADO
if($tipo_informe == 7){

 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR calculo_requerimientos.cod_programacion = '$cod_programacion24') 
                  AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio";  


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
                                municipio.nombre AS nom_municipio, calculo_requerimientos.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela
                FROM calculo_requerimientos 
                INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio    
                INNER JOIN escuela ON escuela.cod_escuela = calculo_requerimientos.cod_escuela
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
  $nom_municipio = $row2['nom_municipio'];
  $cod_departamento = $row2['cod_departamento']; 
  $cod_escuela = $row2['cod_escuela']; 
  $nom_escuela = $row2['nom_escuela'];    
  
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
  
/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }  

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
    $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
    $hojaExcel.="<td width='13%'><center><img src='../imagenes/$logo' width='100' height='100' /></center></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $nom_escuela &nbsp;&nbsp;-&nbsp;&nbsp; <strong>Municipio:</strong> $nom_municipio</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="$cad_entrega";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    // $hojaExcel.="<th>COD GEMINUS</td>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                      ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                            AND calculo_redondeado_escuela.cod_escuela = $cod_escuela $condi_categoria
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
        $cod_geminus       = $row7['codigo_geminus'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='2'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
        // $hojaExcel.="<td>$cod_geminus</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

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
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
    $hojaExcel .= "<tr style='border-bottom: hidden;
      border-left: hidden;
      border-right: hidden;
      margin-top: 10px;'>";
    $hojaExcel .= "<td colspan='4' style='text-align: right; border:none;'>";
    $hojaExcel .= "Pag. " . ($n + 1);
    $hojaExcel .= "</td>";
  $hojaExcel .= "</tr>";
$hojaExcel.="</table>"; 

$hojaExcel.="<br>";

// $hojaExcel.="<table width='98%'>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td width='17%'><strong>NOMBRE CONDUCTOR</strong></td>";
//     $hojaExcel.="<td width='33%'>&nbsp;</td>";
//     $hojaExcel.="<td width='20%'><strong>NOMBRE DESPACHADOR</strong></td>";
//     $hojaExcel.="<td width='30%'>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
// $hojaExcel.="</table>";
 
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

////INFORME DE ACTA DE DESPACHO DE PRODUCTOS POR DIA
if($tipo_informe == 8){

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
                          OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                          OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                          OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                          OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                          OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                          OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR  calculo_requerimientos.cod_programacion = $cod_programacion22
                          OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR  calculo_requerimientos.cod_programacion = $cod_programacion24) 
                             $condicion";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo); 
  
  $cod_ciclo = $row_ciclo['cod_ciclo']; 
  $nom_ciclo = $row_ciclo['nom_ciclo']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);  

  ////BUSCAMOS EL DEPARTAMENTOS DE LAS MINUTA
  $instruccion_dep ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento
                     FROM calculo_requerimientos 
                     INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                     WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                        OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                        OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                        OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                        OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                        OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                        OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                        OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                        OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                        OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                        OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR  calculo_requerimientos.cod_programacion = $cod_programacion22
                        OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR  calculo_requerimientos.cod_programacion = $cod_programacion24) 
                           $condicion2  
                     ORDER BY calculo_requerimientos.cod_menu";
 
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
  
/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }     
  
  $hojaExcel.="<table width='98%'' height='80' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
      $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
      $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="</tr>";
  $hojaExcel.="</table>";
  $hojaExcel.="<table width='98%' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="<tr>";
      $hojaExcel.="$cad_entrega</td>";
    $hojaExcel.="</tr>";  
  $hojaExcel.="</table>";

  ////BUSCAMOS LOS MENU
  $instruccion0 ="SELECT DISTINCT calculo_requerimientos.cod_menu AS cod_menu, menu.nombre AS nom_menu   
                  FROM calculo_requerimientos 
                  INNER JOIN menu ON menu.cod_menu = calculo_requerimientos.cod_menu 
                  WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                       OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                       OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                       OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                       OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                       OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                       OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                       OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                       OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                       OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                       OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                       OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24)
                       $condicion2
                  ORDER BY calculo_requerimientos.cod_menu";
 
  $consulta0 = mysql_query($instruccion0);
  error_consulta($consulta0,$instruccion0);
  $nfilas0 = mysql_num_rows ($consulta0);
  
$hojaExcel.="<table width='98%' border='1'>";
 $hojaExcel.="<tr>";    
  
  for ($i=0; $i<$nfilas0; $i++){
    $row0 = mysql_fetch_array($consulta0);
    
    $cod_menu = $row0['cod_menu'];
    $nom_menu = $row0['nom_menu'];
            
    if($i==0){
      // $hojaExcel.="<td>COD GEMINUS</td>";
      $hojaExcel.="<td align='center'>PRODUCTO</td>"; 
      $hojaExcel.="<td align='center'>PRESENTACION</td>"; 
      }   
    
    $hojaExcel.="<td align='center'>$nom_menu</td>";        
   } 
 $hojaExcel.="</tr>"; 
 
  ////BUSCAMOS LOS INGREDIENTES CON LAS UNIDADES sacamos todas las categorias menos grano por que daria mucho si se redondea dia por dia
  $instruccion1 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad_medida,  
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                                  categoria_ingrediente.nombre AS nom_categoria_ingrediente, unidad_medida.valor_gr_cc AS valor_gr_cc, 
                                  ingrediente.redondear AS redondear, ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  INNER JOIN ingrediente_unidad_entrega_consulta ON ingrediente_unidad_entrega_consulta.cod_ingrediente = ingrediente.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                        $condicion2 AND calculo_redondeado_escuela.cod_categoria_ingrediente <> 2
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
    $valor_gr_cc = $row1['valor_gr_cc'];
    $redondear = $row1['redondear'];
    $cod_geminus = $row1['codigo_geminus']; 
    
    $columnas = $nfilas0 + 2; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
    
    // $hojaExcel.="<td align='left'>$cod_geminus</td>";     
    $hojaExcel.="<td align='left'>$nom_ingrediente</td>"; 
    $hojaExcel.="<td align='center'>$nom_unidad_medida</td>";  
     
    ////BUSCAMOS LAS CANTIDADES PARA EL PRODUCTO POR CADA MENU
    ////BUSCAMOS NUEVAMENTE LOS MENU 
    $instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_menu AS cod_menu
                    FROM calculo_requerimientos 
                    WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                         OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                         OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                         OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                         OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                         OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                         OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                         OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                         OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                         OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                         OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR  calculo_requerimientos.cod_programacion = $cod_programacion22
                         OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR  calculo_requerimientos.cod_programacion = $cod_programacion24)
                            $condicion2 
                    ORDER BY calculo_requerimientos.cod_menu";
   
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $nfilas2 = mysql_num_rows ($consulta2);

    for ($k=0; $k<$nfilas2; $k++){
      $row2 = mysql_fetch_array($consulta2);
      
      $cod_menu = $row2['cod_menu'];
      
        ////BUSCAMOS LA CANTIDAD
        $instruccion_q ="SELECT SUM(calculo_requerimientos.cantidad) AS cantidad
                         FROM calculo_requerimientos
                         WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                            OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                            OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                            OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                            OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                            OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                            OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                            OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                            OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                            OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                            OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                            OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24) 
                           AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_menu = $cod_menu 
                               $condicion2";
       
        $consulta_q = mysql_query($instruccion_q);
        error_consulta($consulta_q,$instruccion_q);
        $row_q = mysql_fetch_array($consulta_q);  
        
        $cantidad = $row_q['cantidad'];   
        
        $cantidad = $cantidad / $valor_gr_cc;
            
            if($redondear == 1){
              ////REDONDEAMOS LAS CANTIDADES DE LA UNIDAD ARITMETICAMENTE
              $cantidad_total_r = round($cantidad, 0);
             }else{
               ////NO REDONDEAMOS LAS CANTIDADES Y LA DEJAMOS CON UN SOLO DECIMAL
               $cantidad_total_r = round($cantidad, 1);
               }      

      $hojaExcel.="<td align='center'>$cantidad_total_r</td>";     
     } 
     $hojaExcel.="</tr>";         
   } 

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
 
////INFORME DE ACTA DE DESPACHO DE PRODUCTOS POR DIA  PARA CADA C.A.
if($tipo_informe == 9){

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
                          OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                          OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                          OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                          OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                          OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                          OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                          OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24) 
                             $condicion";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo); 
  
  $cod_ciclo = $row_ciclo['cod_ciclo']; 
  $nom_ciclo = $row_ciclo['nom_ciclo']; 
  
  $nom_ciclo = substr($nom_ciclo,0,7);  

  ////BUSCAMOS EL DEPARTAMENTOS DE LAS MINUTA
  $instruccion_dep ="SELECT DISTINCT calculo_requerimientos.cod_departamento AS cod_departamento
                     FROM calculo_requerimientos 
                     INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                     WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                        OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                        OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                        OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                        OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                        OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                        OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                        OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                        OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                        OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                        OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                        OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24) 
                           $condicion2  
                     ORDER BY calculo_requerimientos.cod_menu";
 
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
  
/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }     
  
  $hojaExcel.="<table width='98%'' height='80' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
      $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
      $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
    $hojaExcel.="</tr>";
  $hojaExcel.="</table>";
  $hojaExcel.="<table width='98%' border='1'>";
    $hojaExcel.="<tr>";
      $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
    $hojaExcel.="</tr>";
    $hojaExcel.="<tr>";
      $hojaExcel.="$cad_entrega</td>";
    $hojaExcel.="</tr>";  
  $hojaExcel.="</table>";

  ////BUSCAMOS LOS MENU
  $instruccion0 ="SELECT DISTINCT calculo_requerimientos.cod_menu AS cod_menu, menu.nombre AS nom_menu   
                  FROM calculo_requerimientos 
                  INNER JOIN menu ON menu.cod_menu = calculo_requerimientos.cod_menu 
                  WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                       OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                       OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                       OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                       OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                       OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                       OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                       OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                       OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                       OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                       OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                       OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24)
                       $condicion2 AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio 
                  ORDER BY calculo_requerimientos.cod_menu";
 
  $consulta0 = mysql_query($instruccion0);
  error_consulta($consulta0,$instruccion0);
  $nfilas0 = mysql_num_rows ($consulta0);
  
$hojaExcel.="<table width='98%' border='1'>";
 $hojaExcel.="<tr>";    
  
  for ($i=0; $i<$nfilas0; $i++){
    $row0 = mysql_fetch_array($consulta0);
    
    $cod_menu = $row0['cod_menu'];
    $nom_menu = $row0['nom_menu'];
            
    if($i==0){
      // $hojaExcel.="<td>COD GEMINUS</td>";
      $hojaExcel.="<td align='center'>PRODUCTO</td>"; 
      $hojaExcel.="<td align='center'>PRESENTACION</td>"; 
      }   
    
    $hojaExcel.="<td align='center'>$nom_menu</td>";        
   } 
 $hojaExcel.="</tr>"; 
 
  ////BUSCAMOS LOS INGREDIENTES CON LAS UNIDADES sacamos todas las categorias menos grano por que daria mucho si se redondea dia por dia
  $instruccion1 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                  calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad_medida,  
                                  calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria_ingrediente, 
                                  categoria_ingrediente.nombre AS nom_categoria_ingrediente, unidad_medida.valor_gr_cc AS valor_gr_cc, 
                                  ingrediente.redondear AS redondear, ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus
                  FROM calculo_redondeado_escuela 
                  INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                  INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida 
                  INNER JOIN ingrediente_unidad_entrega_consulta ON ingrediente_unidad_entrega_consulta.cod_ingrediente = ingrediente.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                  INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente 
                  WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                     OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                        $condicion2 AND calculo_redondeado_escuela.cod_categoria_ingrediente <> 2
                     AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio    
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
    $valor_gr_cc = $row1['valor_gr_cc'];
    $redondear = $row1['redondear'];
    $cod_geminus = $row1['codigo_geminus'];
    
    $columnas = $nfilas0 + 2; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
    
    // $hojaExcel.="<td align='left'>$cod_geminus</td>";      
    $hojaExcel.="<td align='left'>$nom_ingrediente</td>"; 
    $hojaExcel.="<td align='center'>$nom_unidad_medida</td>";  
     
    ////BUSCAMOS LAS CANTIDADES PARA EL PRODUCTO POR CADA MENU
    ////BUSCAMOS NUEVAMENTE LOS MENU 
    $instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_menu AS cod_menu
                    FROM calculo_requerimientos 
                    WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                         OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                         OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                         OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                         OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                         OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                         OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14  
                         OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                         OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                         OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                         OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                         OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24)
                            $condicion2 AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio 
                    ORDER BY calculo_requerimientos.cod_menu";
   
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $nfilas2 = mysql_num_rows ($consulta2);

    for ($k=0; $k<$nfilas2; $k++){
      $row2 = mysql_fetch_array($consulta2);
      
      $cod_menu = $row2['cod_menu'];
      
        ////BUSCAMOS LA CANTIDAD
        $instruccion_q ="SELECT SUM(calculo_requerimientos.cantidad) AS cantidad
                         FROM calculo_requerimientos
                         WHERE (calculo_requerimientos.cod_programacion = $cod_programacion OR calculo_requerimientos.cod_programacion = $cod_programacion2
                            OR calculo_requerimientos.cod_programacion = $cod_programacion3 OR calculo_requerimientos.cod_programacion = $cod_programacion4
                            OR calculo_requerimientos.cod_programacion = $cod_programacion5 OR calculo_requerimientos.cod_programacion = $cod_programacion6
                            OR calculo_requerimientos.cod_programacion = $cod_programacion7 OR calculo_requerimientos.cod_programacion = $cod_programacion8
                            OR calculo_requerimientos.cod_programacion = $cod_programacion9 OR calculo_requerimientos.cod_programacion = $cod_programacion10
                            OR calculo_requerimientos.cod_programacion = $cod_programacion11 OR calculo_requerimientos.cod_programacion = $cod_programacion12
                            OR calculo_requerimientos.cod_programacion = $cod_programacion13 OR calculo_requerimientos.cod_programacion = $cod_programacion14
                            OR calculo_requerimientos.cod_programacion = $cod_programacion15 OR calculo_requerimientos.cod_programacion = $cod_programacion16
                            OR calculo_requerimientos.cod_programacion = $cod_programacion17 OR calculo_requerimientos.cod_programacion = $cod_programacion18
                            OR calculo_requerimientos.cod_programacion = $cod_programacion19 OR calculo_requerimientos.cod_programacion = $cod_programacion20
                            OR calculo_requerimientos.cod_programacion = $cod_programacion21 OR calculo_requerimientos.cod_programacion = $cod_programacion22
                            OR calculo_requerimientos.cod_programacion = $cod_programacion23 OR calculo_requerimientos.cod_programacion = $cod_programacion24) 
                           AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_menu = $cod_menu 
                           AND calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio 
                               $condicion2";
       
        $consulta_q = mysql_query($instruccion_q);
        error_consulta($consulta_q,$instruccion_q);
        $row_q = mysql_fetch_array($consulta_q);  
        
        $cantidad = $row_q['cantidad'];   
        
        $cantidad = $cantidad / $valor_gr_cc;
            
            if($redondear == 1){
              ////REDONDEAMOS LAS CANTIDADES DE LA UNIDAD ARITMETICAMENTE
              $cantidad_total_r = round($cantidad, 0);
             }else{
               ////NO REDONDEAMOS LAS CANTIDADES Y LA DEJAMOS CON UN SOLO DECIMAL
               $cantidad_total_r = round($cantidad, 1);
               }      

      $hojaExcel.="<td align='center'>$cantidad_total_r</td>";     
     } 
     $hojaExcel.="</tr>";         
   } 

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
 
////********************************************************************************************
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO POR MUNICIPIO
if($tipo_informe == 10){

 $condicion = " WHERE (calculo_requerimientos.cod_programacion = '$cod_programacion'  OR calculo_requerimientos.cod_programacion = '$cod_programacion2' 
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion3' OR calculo_requerimientos.cod_programacion = '$cod_programacion4'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion5' OR calculo_requerimientos.cod_programacion = '$cod_programacion6'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion7' OR calculo_requerimientos.cod_programacion = '$cod_programacion8'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion9' OR calculo_requerimientos.cod_programacion = '$cod_programacion10'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion11' OR calculo_requerimientos.cod_programacion = '$cod_programacion12'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion13' OR calculo_requerimientos.cod_programacion = '$cod_programacion14'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion15' OR calculo_requerimientos.cod_programacion = '$cod_programacion16'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion17' OR calculo_requerimientos.cod_programacion = '$cod_programacion18'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion19' OR calculo_requerimientos.cod_programacion = '$cod_programacion20'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion21' OR calculo_requerimientos.cod_programacion = '$cod_programacion22'
                   OR  calculo_requerimientos.cod_programacion = '$cod_programacion23' OR calculo_requerimientos.cod_programacion = '$cod_programacion24') 
                  AND  calculo_requerimientos.cod_centro_acopio = $cod_centro_acopio AND calculo_requerimientos.cod_municipio = $cod_municipio";  


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
                                municipio.nombre AS nom_municipio, calculo_requerimientos.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela
                FROM calculo_requerimientos 
                INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio 
                INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio    
                INNER JOIN escuela ON escuela.cod_escuela = calculo_requerimientos.cod_escuela
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
  $nom_municipio = $row2['nom_municipio'];
  $cod_departamento = $row2['cod_departamento']; 
  $cod_escuela = $row2['cod_escuela']; 
  $nom_escuela = $row2['nom_escuela'];    
  
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
  
/////CADENA POR SI EL PERIODO DE ENTREGA SE DIGITA 
if($periodo_entrega != ''){
   $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <strong>Periodo de Entrega: </strong>$periodo_entrega</td>"; 
  }else{
    $cad_entrega = "<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
    }  

$hojaExcel.="<H1 class=SaltoDePagina>";
 ////ENCABEZADO DE LA TABLA DE RESULTADOS
$hojaExcel.="<table width='98%'' height='80' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='9%' height='74'>$logo1</td>";
    $hojaExcel.="<th width='78%' align='center'>$cadena_programacion</th>";
    $hojaExcel.="<td width='13%'><img src='../imagenes/$logo' width='134' height='60' /></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Operador:</strong> $nom_operador &nbsp;&nbsp;&nbsp; <strong>Nit:</strong> $nit</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $nom_escuela &nbsp;&nbsp;-&nbsp;&nbsp; <strong>Municipio:</strong> $nom_municipio</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="$cad_entrega";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    // $hojaExcel.="<th>COD GEMINUS</td>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
  $hojaExcel.="</tr>";  

      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_redondeado_escuela.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria,                      
                      SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad,
                      calculo_redondeado_escuela.cod_unidad_medida AS cod_unidad_medida, unidad_medida.nombre AS nom_unidad,
                      ingrediente_unidad_entrega_consulta.codigo_geminus AS codigo_geminus 
                      FROM calculo_redondeado_escuela 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_redondeado_escuela.cod_categoria_ingrediente
                      INNER JOIN ingrediente_unidad_entrega_consulta ON calculo_redondeado_escuela.cod_ingrediente = ingrediente_unidad_entrega_consulta.cod_ingrediente AND calculo_redondeado_escuela.cod_unidad_medida = ingrediente_unidad_entrega_consulta.cod_unidad_medida
                      LEFT JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida  
                      WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion5 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion6
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion7 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion8
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion9 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion10
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion11 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion12
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion13 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion14
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion15 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion16
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion17 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion18
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion19 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion20
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion21 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion22
                             OR calculo_redondeado_escuela.cod_programacion = $cod_programacion23 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion24) 
                            AND calculo_redondeado_escuela.cod_centro_acopio = $cod_centro_acopio AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                            AND calculo_redondeado_escuela.cod_escuela = $cod_escuela $condi_categoria
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
        $cod_geminus       = $row7['codigo_geminus'];
        
        if($cod_unidad_medida == 0){
           $nom_unidad = "GR/CC";
          }else{
            $nom_unidad = $row7['nom_unidad'];
             }
          
        
        $cantidad = round($cantidad,1);
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='2'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
        // $hojaExcel.="<td>$cod_geminus</td>";
        $hojaExcel.="<td>$nom_ingrediente</td>";
        $hojaExcel.="<td align='center'>$nom_unidad</td>";
        $hojaExcel.="<td align='center'>$cantidad</td>";
        $hojaExcel.="</tr>"; 
       } 
                
                    
$hojaExcel.="</table>";

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
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td><strong>FECHA Y HORA</strong></td>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 

$hojaExcel.="<br>";

// $hojaExcel.="<table width='98%'>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td width='17%'><strong>NOMBRE CONDUCTOR</strong></td>";
//     $hojaExcel.="<td width='33%'>&nbsp;</td>";
//     $hojaExcel.="<td width='20%'><strong>NOMBRE DESPACHADOR</strong></td>";
//     $hojaExcel.="<td width='30%'>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>FIRMA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
//   $hojaExcel.="<tr>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//     $hojaExcel.="<td><strong>CEDULA</strong></td>";
//     $hojaExcel.="<td>&nbsp;</td>";
//   $hojaExcel.="</tr>";
// $hojaExcel.="</table>";
 
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

?>
</body>
</html>

<?php
// Cerrar conexi�n
mysql_close ($conexion);   
?>
