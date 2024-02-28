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

?>
<html>
<head>
<title>ACTA DE DESPACHO CONSOLIDADO</title>
</head>
<body>
<?php  

$categoria = 0;
                   
////GENERAMOS LA CONDICION SI ES SELECCIONADO EL FILTRO DE SOLO ALGUNA CATEGORIA
if($categoria != '0'){
  $condi_categoria = " AND calculo_redondeado_escuela.cod_categoria_ingrediente = '$categoria' ";
  }

////********************************************************************************************

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
      $hojaExcel.="<td width='9%' height='74'><img src='../imagenes/logo_min.png' width='250' height='70' /></td>";
      $hojaExcel.="<th width='78%' align='center'>$nom_formato</th>";
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
      $hojaExcel.="<td align='center'>COD GEMINUS</td>";
      $hojaExcel.="<td align='center'>PRODUCTO</td>";
      $hojaExcel.="<td align='center'>CATEGORIA</td>"; 
      $hojaExcel.="<td align='center'>PRESENTACION</td>"; 
      }   
    
    $hojaExcel.="<td align='center'>$nom_centro_acopio</td>";        
   }
   
   $hojaExcel.="<td align='center'>TOTAL</td>";
    
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
    $cod_geminus = $row1['codigo_geminus'];
   
   /* 
    $columnas = $nfilas0 + 3; 
    
    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;  
   */  
    
    $hojaExcel.="<td align='left'>$cod_geminus</td>";        
    $hojaExcel.="<td align='left'>$nom_ingrediente</td>"; 
    $hojaExcel.="<td align='left'>$nom_cat_ingredi</td>"; 
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
     
        ////BUSCAMOS LA CANTIDAD TOTAL POR INGREDIENTE 
        $instruccion_qt ="SELECT SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad
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
                               $condicion2";
       
        $consulta_qt = mysql_query($instruccion_qt);
        error_consulta($consulta_qt,$instruccion_qt);
        $row_qt = mysql_fetch_array($consulta_qt);  
        
        $cantidad_total = $row_qt['cantidad'];   
        
        if($cantidad_total != ''){
           $cantidad_total = round($cantidad_total,1);
          }else{
            $cantidad_total = 0;
            }        
      
      $hojaExcel.="<td align='center'>$cantidad_total</td>";       
     
     $hojaExcel.="</tr>";         
   } 

 $hojaExcel.="</table>";
 
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho_centros_acopio_consol"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
    $fp=fopen($sfile,"w"); 
    fwrite($fp,$hojaExcel); 
    fclose($fp);
    echo "<br><center><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a></center>";
 }

////INFORME DE ACTA DE DESPACHO DE DE TODOS LOS PROGRAMAS
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
      $hojaExcel.="<td width='9%' height='74'><img src='../imagenes/logo_min.png' width='250' height='70' /></td>";
      $hojaExcel.="<th width='78%' align='center'>$nom_formato</th>";
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

  ////BUSCAMOS LOS TIPOS DE MINUTAS
  $instruccion0 ="SELECT DISTINCT calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta
                  FROM calculo_redondeado_escuela 
                  INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta 
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
                  ORDER BY tipo_minuta.nombre";
 
  $consulta0 = mysql_query($instruccion0);
  error_consulta($consulta0,$instruccion0);
  $nfilas0 = mysql_num_rows ($consulta0);
  
$hojaExcel.="<table width='98%' border='1'>";
 $hojaExcel.="<tr>";    
  
  for ($i=0; $i<$nfilas0; $i++){
    $row0 = mysql_fetch_array($consulta0);
    
    $cod_tipo_minuta = $row0['cod_tipo_minuta'];
    $nom_tipo_minuta = $row0['nom_tipo_minuta'];
            
    if($i==0){
      $hojaExcel.="<td align='center'>COD GEMINUS</td>";
      $hojaExcel.="<td align='center'>PRODUCTO</td>"; 
      $hojaExcel.="<td align='center'>CATEGORIA</td>"; 
      $hojaExcel.="<td align='center'>PRESENTACION</td>"; 
      }   
    
    $hojaExcel.="<td align='center'>$nom_tipo_minuta</td>";  
  
   } 
   
   $hojaExcel.="<td align='center'>TOTAL</td>"; 
   
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
    $cod_geminus = $row1['codigo_geminus']; 
  
    /* 
    $columnas = $nfilas0 + 3; 

    if($cat_anterior != $cod_cat_ingredi){
       $hojaExcel.="<tr><th colspan='$columnas'>$nom_cat_ingredi</th></tr>";
      }
    $cat_anterior = $cod_cat_ingredi;    */
    
    $hojaExcel.="<td align='left'>$cod_geminus</td>";     
    $hojaExcel.="<td align='left'>$nom_ingrediente</td>"; 
    $hojaExcel.="<td align='left'>$nom_cat_ingredi</td>";
    $hojaExcel.="<td align='center'>$nom_unidad_medida</td>";  
     
    ////BUSCAMOS LAS CANTIDADES PARA EL PRODUCTO POR CADA TIPO DE MINUTA 
    ////BUSCAMOS NUEVAMENTE LOS TIPOS DE MINUTA 
    $instruccion2 ="SELECT DISTINCT calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta
                    FROM calculo_redondeado_escuela 
                    INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = calculo_redondeado_escuela.cod_tipo_minuta 
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
                    ORDER BY tipo_minuta.nombre";
   
    $consulta2 = mysql_query($instruccion2);
    error_consulta($consulta2,$instruccion2);
    $nfilas2 = mysql_num_rows ($consulta2);

    for ($k=0; $k<$nfilas2; $k++){
      $row2 = mysql_fetch_array($consulta2);
      
      $cod_tipo_minuta = $row2['cod_tipo_minuta'];
      
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
                           AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta $condicion2";
       
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
        ////BUSCAMOS LA CANTIDAD TOTAL DEL INGREDIENTE PARA EL PROGRAMA
        $instruccion_qt ="SELECT SUM(calculo_redondeado_escuela.cantidad_redondeada) AS cantidad
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
                             $condicion2";
       
        $consulta_qt = mysql_query($instruccion_qt);
        error_consulta($consulta_qt,$instruccion_qt);
        $row_qt = mysql_fetch_array($consulta_qt);  
        
        $cantidad_total = $row_qt['cantidad'];   
        
        if($cantidad_total != ''){
           $cantidad_total = round($cantidad_total,1);
          }else{
            $cantidad_total = 0;
            }  
      
      $hojaExcel.="<td align='center'>$cantidad_total</td>";      
     
     $hojaExcel.="</tr>";         
   } 


 $hojaExcel.="</table>";
 
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/formato_Acta_despacho_programas_consol"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
