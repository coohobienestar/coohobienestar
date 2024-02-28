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

$cod_programacion  = $_GET['cod_programacion'];
$cod_programacion2 = $_GET['cod_programacion2'];
$cod_programacion3 = $_GET['cod_programacion3'];
$cod_programacion4 = $_GET['cod_programacion4'];
$municipio_o = $_GET['mun_o'];
$municipio_1 = $_GET['mun_1'];
$municipio_2 = $_GET['mun_2'];
$municipio_3 = $_GET['mun_3'];
$tipo_informe = $_GET['tipo_informe'];

////LLAMAMOS LA FUNCION QUE CREA LA CADENA DE FECHA 
   $cad_fecha = generar_fecha($cod_programacion);

      ////BUSCAMOS EL CICLO DE LA PROGRAMACION
    $instruccion_ciclo ="SELECT DISTINCT ciclo.cod_ciclo AS cod_ciclo, ciclo.nombre AS nom_ciclo 
                         FROM calculo_requerimientos 
                         INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                         WHERE calculo_requerimientos.cod_programacion = $cod_programacion";
   
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

  ////BUSCAMOS EL NOMBRE DE CENTRO DE ACOPIO
  $instruccion_ca ="SELECT DISTINCT centro_acopio.nombre AS nombre, escuela.cod_municipio AS cod_municipio
                    FROM escuela 
                    INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = escuela.cod_centro_acopio
                    WHERE escuela.cod_municipio = $municipio_o;";     
  $consulta_ca = mysql_query($instruccion_ca);
  error_consulta($consulta_ca,$instruccion_ca);
  $row_ca = mysql_fetch_array($consulta_ca);  
  
  $origen = $row_ca['nombre']; 

 if ($municipio_o != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO    
  $instruccion_mu ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_o";      
  $consulta_mu = mysql_query($instruccion_mu);
  error_consulta($consulta_mu,$instruccion_mu);
  $row_mu = mysql_fetch_array($consulta_mu);  
  
  $mun_o = $row_mu['nombre'];
  $mun_o = strtoupper($mun_o);
  }  
  
 if ($municipio_1 != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO 1 a agrupar   
  $instruccion_mu1 ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_1";      
  $consulta_mu1 = mysql_query($instruccion_mu1);
  error_consulta($consulta_mu1,$instruccion_mu1);
  $row_mu1 = mysql_fetch_array($consulta_mu1);  
  
  $mun_1 = $row_mu1['nombre']; 
  $mun_1 = strtoupper($mun_1);
  }
  
 if ($municipio_2 != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO 1 a agrupar   
  $instruccion_mu2 ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_2";      
  $consulta_mu2 = mysql_query($instruccion_mu2);
  error_consulta($consulta_mu2,$instruccion_mu2);
  $row_mu2 = mysql_fetch_array($consulta_mu2);  
  
  $mun_2 = $row_mu2['nombre'];                          
  $mun_2 = strtoupper($mun_2);
  } 
  
 if ($municipio_3 != '') {
  ////BUSCAMOS EL NOMBRE DEL MUNICIPIO 1 a agrupar   
  $instruccion_mu3 ="SELECT nombre FROM municipio WHERE cod_municipio = $municipio_3";      
  $consulta_mu3 = mysql_query($instruccion_mu3);
  error_consulta($consulta_mu3,$instruccion_mu3);
  $row_mu3 = mysql_fetch_array($consulta_mu3);  
  
  $mun_3 = $row_mu3['nombre'];                          
  $mun_3 = strtoupper($mun_3);
  }        
  
  ////CADENA DE NOMBRE DEL DESTINO
  if($mun_2 != ''){
    $destino = "$mun_o - $mun_1 - $mun_2";
   }else{
      $destino = "$mun_o - $mun_1";
     }
     
  ////CADENA DE NOMBRE DEL DESTINO
  if($mun_3 != ''){
    $destino = "$mun_o - $mun_1 - $mun_2 - $mun_3";
   }else{
      $destino = "$mun_o - $mun_1 - $mun_2";
     }     
     
   
  ////BUSCAMOS EL NOMBRE DEL DEPARTAMENTO
  $instruccion_de ="SELECT DISTINCT escuela.cod_municipio AS cod_municipio, departamento.nombre AS nombre, departamento.cod_departamento AS cod_departamento
                    FROM escuela 
                    INNER JOIN municipio ON municipio.cod_municipio = escuela.cod_municipio
                    INNER JOIN departamento ON departamento.cod_departamento = municipio.cod_departamento
                    WHERE escuela.cod_municipio = $municipio_o;";      
  $consulta_de = mysql_query($instruccion_de);
  error_consulta($consulta_de,$instruccion_de);
  $row_de = mysql_fetch_array($consulta_de);  
  
  $nom_departamento = $row_de['nombre']; 
  $cod_departamento = $row_de['cod_departamento'];   

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
  
  ////FORMULAMOS LA CONDICION PARA LA CONSULTA
  if($cod_programacion2 == '0' && $cod_programacion3 == '0' && $cod_programacion4 == '0'){
   $condicion = " WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion";
   }
  if($cod_programacion2 != '0' && $cod_programacion3 == '0' && $cod_programacion4 == '0'){
   $condicion = " WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2)";
   }   
  if($cod_programacion2 != '0' && $cod_programacion3 != '0' && $cod_programacion4 == '0'){
   $condicion = " WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2 OR
                         calculo_redondeado_escuela.cod_programacion = $cod_programacion3)";
   } 
  if($cod_programacion2 != '0' && $cod_programacion3 != '0' && $cod_programacion4 != '0'){
   $condicion = " WHERE (calculo_redondeado_escuela.cod_programacion = $cod_programacion OR calculo_redondeado_escuela.cod_programacion = $cod_programacion2 OR
                         calculo_redondeado_escuela.cod_programacion = $cod_programacion3 OR calculo_redondeado_escuela.cod_programacion = $cod_programacion4)";
   }    
   
  if($municipio_2 != '0'){
     $condicion1 = "(calculo_redondeado_escuela.cod_municipio = $municipio_o OR calculo_redondeado_escuela.cod_municipio = $municipio_1 OR 
                     calculo_redondeado_escuela.cod_municipio = $municipio_2)";
   }  
   
  if($municipio_3 != '0'){
     $condicion1 = "(calculo_redondeado_escuela.cod_municipio = $municipio_o OR calculo_redondeado_escuela.cod_municipio = $municipio_1 OR 
                     calculo_redondeado_escuela.cod_municipio = $municipio_3)";
   }   
   
  if($municipio_2 != '0' && $municipio_3 != '0'){
     $condicion1 = "(calculo_redondeado_escuela.cod_municipio = $municipio_o OR calculo_redondeado_escuela.cod_municipio = $municipio_1 OR 
                     calculo_redondeado_escuela.cod_municipio = $municipio_2 OR calculo_redondeado_escuela.cod_municipio = $municipio_3)";
   }  

  if($municipio_2 == '0' && $municipio_3 == '0'){
   $condicion1 = "(calculo_redondeado_escuela.cod_municipio = $municipio_o OR calculo_redondeado_escuela.cod_municipio = $municipio_1)";
   }  

   $condicion_final = $condicion." AND ".$condicion1;        
   
?>
<html>
<head>
<title>ACTA DE DESPACHO AGRUPADA</title>
</head>
<body>
<?php
////INFORME DE ACTA DE DESPACHO DE CENTRO DE ACOPIO A CENTRO DE ACOPIO
if($tipo_informe == 1){
$hojaExcel.="<H1 class=SaltoDePagina>";

 ////ENCABEZADO DE LA TABLA DE RESULTADOS
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
    $hojaExcel.="<td><strong>Origen:</strong> $origen</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Destino:</strong> $destino</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Programa:</strong> DESAYUNOS TRADICIONALES Y ALMUERZOS $nom_departamento</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>Fecha:</strong> $fecha_actual - <strong>Ciclo:</strong> $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu &nbsp;&nbsp;&nbsp; $cad_fecha</td>";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";
$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>PRODUCTO</td>";
    $hojaExcel.="<th>PRESENTACION</th>";
    $hojaExcel.="<th>TOTAL</th>";
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
                      $condicion_final
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
           $hojaExcel.="<tr><th colspan='3'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  
        
        $hojaExcel.="<tr>"; 
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
$hojaExcel.="</table>";

$hojaExcel.="</H1>"; 
echo $hojaExcel;

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/FormatoActaDesAgrupada"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
