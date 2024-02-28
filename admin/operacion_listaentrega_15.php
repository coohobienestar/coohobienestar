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
                                  departamento.nombre AS nom_departamento, calculo_requerimientos.cod_municipio, municipio.nombre AS nom_municipio,
                                  centro_zonal.nombre AS nom_centro_zonal
                  FROM calculo_requerimientos
                  INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo
                  INNER JOIN escuela ON escuela.cod_escuela = calculo_requerimientos.cod_escuela
                  INNER JOIN departamento ON departamento.cod_departamento = calculo_requerimientos.cod_departamento
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio
                  INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = calculo_requerimientos.cod_centro_acopio
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
  
  $columnas = 14 + ($nfilas_re * 1); ////6 numero de columnas fijas mas las de rango de edad variables por 1 veces q se deben dibujar

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
   
   
 ////DEFINIMOS EL TIPO DE MINUTA DE DESAYUNOS INDUSTRIALIZADOS DE RISARALDA PARA SACAR EL ESTILO 
if ($cod_tipo_minuta == 0 || $cod_tipo_minuta == 0){
  $estilo = "estilo_informes.css";
  }else{
    $estilo = "estilo_informes_2.css";
    } 
        
  
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../estilos/<?php print("$estilo");?>">
<title>LISTA DE ENTREGA PAE v.2015</title>
</head>
<body>
<?php     

$logo_1 = "<img src='../imagenes/logo_min.png' width='250' height='70' />";
$logo_2 = "<img src='../imagenes/escudo.png' width='97' height='90' />";
$logo_3 = "<img src='../imagenes/logo_min_15.png' width='250' height='70' />";
$logo   = "<img src='../imagenes/$logo' width='100' height='60' />";

////ESCUDO POR DEPARTAMENTO
////Risaralda
if ($cod_tipo_minuta == 3 || $cod_tipo_minuta == 4 || $cod_tipo_minuta == 50){
    $logo_4 = "<img src='../imagenes/esc_risaralda.jpg' width='60' height='60' />";
  }        
////Risaralda - SOLO PARA ALMUERZOS PAE ALCALDIA RISARALA
if ($cod_tipo_minuta == 61 ){
    $logo_4 = "<img src='../imagenes/logo_alc_pere.png' width='60' height='60' />";
  }   
////Risaralda transicion pereira
if ($cod_tipo_minuta == 77){
    $logo_4 = "<img src='../imagenes/logo_alc_pere.png' width='60' height='60' />";      
    $logo_3 = "<img src='../imagenes/logo_icbf.png' width='97' height='60' />"; 
  }   
  
////Risaralda PUREMBARA RISARALDA - ALMUERZOS
if ($cod_tipo_minuta == 83){
    $logo_1 = "";
    $logo_2 = "";
    $logo_4 = "";      
    $logo_3 = ""; 
  } 
    
////Tolima
if ($cod_tipo_minuta == 16 || $cod_tipo_minuta == 17 || $cod_tipo_minuta == 18 || $cod_tipo_minuta == 72  || $cod_tipo_minuta == 73){
    $logo_4 = "<img src='../imagenes/esc_tolima.jpg' width='60' height='60' /><img src='../imagenes/tolima02.png' width='50' height='50' />";
  } 
  
////Tolima - Melgar
if ($cod_tipo_minuta == 65 || $cod_tipo_minuta == 66 || $cod_tipo_minuta == 67){
    $logo_1 = "";
    $logo_2 = "";
    $logo_3 = "";
    $logo_4 = "<img src='../imagenes/alcaldia_melgar.png' width='80' height='60' /><img src='../imagenes/escudo.png' width='50' height='50' />";
  } 
////Tolima - INPEC
if ($cod_tipo_minuta == 75 || $cod_tipo_minuta == 79 || $cod_tipo_minuta == 80){
    $logo_4 = "<img src='../imagenes/logo_icbf.png' width='97' height='90' />";
    $logo_3 = "";
  }     
////Quindio
if ($cod_tipo_minuta == 19 || $cod_tipo_minuta == 20 || $cod_tipo_minuta == 21 || $cod_tipo_minuta == 22 || $cod_tipo_minuta == 23){
    $logo_4 = "<img src='../imagenes/esc_quindio.jpg' width='60' height='60' />";
  } 
////Caqueta
if ($cod_tipo_minuta == 57 || $cod_tipo_minuta == 58 || $cod_tipo_minuta == 59 || $cod_tipo_minuta == 81 || $cod_tipo_minuta == 82){
    $logo_4 = "<img src='../imagenes/esc_caqueta.png' width='60' height='60' />";
  }  
  
  //////definimos LOGO  
  if($cod_tipo_minuta == 6 || $cod_tipo_minuta == 7 || $cod_tipo_minuta == 51 || $cod_tipo_minuta == 74 || $cod_tipo_minuta == 15 || $cod_tipo_minuta == 60){////con logo de ICBF y construyamos
     $logo_1 = "<img src='../imagenes/logo_icbf.png' width='97' height='90' />";
     $logo_2 = " "; 
    }     

////TIPO DE MINUTA 84    
 if($cod_tipo_minuta == 84){
  $logo_1 = "";
  $logo_2 = "";
  $logo_3 = "";
  $logo   = "<img src='../imagenes/$logo' width='100' height='60' />";  
  } 
////IBAGUE  
 if($cod_tipo_minuta == 85 || $cod_tipo_minuta == 86 || $cod_tipo_minuta == 87 || $cod_tipo_minuta == 88 || $cod_tipo_minuta == 89 || $cod_tipo_minuta == 90 || 
    $cod_tipo_minuta == 92 || $cod_tipo_minuta == 93 || $cod_tipo_minuta == 94 || $cod_tipo_minuta == 95 || $cod_tipo_minuta == 96 || $cod_tipo_minuta == 97 || $cod_tipo_minuta == 101){
    $logo_4 = "<img src='../imagenes/ibague_17.jpg' width='60' height='60' />";
  }           

if ($cod_tipo_minuta == 4 || $cod_tipo_minuta == 50 || $cod_tipo_minuta == 18 || $cod_tipo_minuta == 21 || $cod_tipo_minuta == 22 || $cod_tipo_minuta == 59 ||
    $cod_tipo_minuta == 77 || $cod_tipo_minuta == 79 || $cod_tipo_minuta == 80 || $cod_tipo_minuta == 84 || $cod_tipo_minuta == 87 || $cod_tipo_minuta == 88 || 
    $cod_tipo_minuta == 89 || $cod_tipo_minuta == 93 || $cod_tipo_minuta == 94 || $cod_tipo_minuta == 95 || $cod_tipo_minuta == 96 || $cod_tipo_minuta == 97){

  ///************************INICIO FORMATO MODELO 2015 INDUSTRIALIZADO
$hojaExcel.="<H1 class=SaltoDePagina>";
$hojaExcel.="<table width='98%'' border='0'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><div align='right'>$logo&nbsp;&nbsp;$logo_4&nbsp;&nbsp;$logo_3</div></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th><div align='center'>REMISI&Oacute;N ENTREGA DE COMPLEMENTOS ALIMENTARIOS EN ESTABLECIMIENTOS EDUCATIVOS RACI&Oacute;N INDUSTRIALIZADA </div></th>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";  

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='60%'><strong>OPERADOR: $nom_operador</strong></td>";
    $hojaExcel.="<td width='40%'><strong>FECHA (DD/MM/AAAA): ____ / ____ / ________ </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='57%'><strong>DEPARTAMENTO: $nom_departamento</strong></td>";
    $hojaExcel.="<td width='43%'><strong>MUNICIPIO: $nom_municipio</strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ESTABLECIMIENTO EDUCATIVO: </strong></td>";
    $hojaExcel.="<td><strong>SEDE: $nom_escuela</strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='43%'><strong>DIRECCI&Oacute;N:</strong></td>";
    $hojaExcel.="<td width='35%'><strong>BARRIO/VEREDA:</strong></td>";
    $hojaExcel.="<td width='22%'><strong>TEL&Eacute;FONO:</strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";   

  ////BUSCAMOS LA JORNADA DE LA ESCUELA
  $instruccion_jor ="SELECT DISTINCT minuta_escuela.cod_jornada AS cod_jornada FROM minuta_escuela WHERE minuta_escuela.cod_escuela = $cod_escuela";
 
  $consulta_jor = mysql_query($instruccion_jor);
  error_consulta($consulta_jor,$instruccion_jor);
  $row_jor = mysql_fetch_array($consulta_jor);
  
  $jornada = $row_jor['cod_jornada'];  

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

      $cupos_total = 0;
      
      ////CONSULTAMOS LOS CUPOS TOTALES
      $instruccion_ct ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre, 
                                      calculo_requerimientos.cupos AS cupos
                      FROM calculo_requerimientos 
                      INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
     
      $consulta_ct = mysql_query($instruccion_ct);
      error_consulta($consulta_ct,$instruccion_ct);
      $nfilas_ct = mysql_num_rows ($consulta_ct);
      
      for ($c=0; $c<$nfilas_ct; $c++){
        $row_ct = mysql_fetch_array($consulta_ct);
        
        $cupos_ct = $row_ct['cupos'];
        
        $cupos_total = $cupos_total + $cupos_ct;
        
        } 
      
$hojaExcel.="<br>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='12%' rowspan='2'>RANGO DE EDAD </th>";
    $hojaExcel.="<th width='13%' rowspan='2'>N&deg; RACIONES ADJUDICADAS </th>";
    $hojaExcel.="<th width='13%' rowspan='2'>N&deg; RACIONES ATENDIDAS </th>";
    $hojaExcel.="<th width='10%' rowspan='2'>N&deg; D&Iacute;AS A ATENDER </th>";
    $hojaExcel.="<th width='10%' rowspan='2'>N&deg; MENU </th>";
    $hojaExcel.="<th width='16%' rowspan='2'>N&deg; SEMANA DEL CICLO DE MEN&Uacute;S </th>";
    $hojaExcel.="<th height='20' colspan='2'>TOTAL RACIONES </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>JM</th>";
    $hojaExcel.="<th>JT</th>";
  $hojaExcel.="</tr>";    

        for ($i=0; $i<$nfilas6; $i++){
        $row6 = mysql_fetch_array($consulta6);
        
        $cod_rango = $row6['cod_rango_edad'];
        $nom_rango = $row6['nombre'];
        $cupos = $row6['cupos'];   
        
        $am_cupos = "";
        $pm_cupos = "";

        if($jornada == 'AM'){
            $am_cupos = $cupos_total;
           }  
        if($jornada == 'PM'){
            $pm_cupos = $cupos_total;
           }              

          $hojaExcel.="<tr>";
            $hojaExcel.="<td align='center'>$nom_rango</td>";
            $hojaExcel.="<td align='center'>$cupos</td>";
            $hojaExcel.="<td align='center'>$cupos</td>";
            $hojaExcel.="<td align='center'>$nfilas_men</td>";
            $hojaExcel.="<td align='center'>$cad_menu</td>";
            $hojaExcel.="<td align='center'>$cad_fecha - $nom_ciclo</td>";
           if($i==0){ 
             $hojaExcel.="<td width='10%' rowspan='$nfilas6' align='center'>$am_cupos</td>";
             $hojaExcel.="<td width='10%' rowspan='$nfilas6' align='center'>$pm_cupos</td>"; 
            } 
        $hojaExcel.="</tr>";
       }
                                
$hojaExcel.="</table>";

$hojaExcel.="<br>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th colspan='3'>COMPLEMENTOS ENTREGAR </th>";
    $hojaExcel.="<th colspan='3'>COMPLEMENTOS RECIBIDOS </th>";
    $hojaExcel.="<th colspan='2'>CONDICIONES DE EMPAQUE </th>";
    $hojaExcel.="<th colspan='3'>FALTANTES</th>";
    $hojaExcel.="<th colspan='3'>DEVOLUCIONES</th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='20%' height='25'>ALIMENTO</th>";
    $hojaExcel.="<th width='13%'>UNIDAD DE MEDIDA </th>";
    $hojaExcel.="<th width='7%'>CANTIDAD</th>";
    $hojaExcel.="<th width='7%'>CANTIDAD</th>";
    $hojaExcel.="<th width='2%'>C</th>";
    $hojaExcel.="<th width='9%'>NC</th>";
    $hojaExcel.="<th width='10%'>C</th>";
    $hojaExcel.="<th width='8%'>NC</th>";
    $hojaExcel.="<th width='2%'>SI</th>";
    $hojaExcel.="<th width='2%'>NO</th>";
    $hojaExcel.="<th width='7%'>CANTIDAD</th>";
    $hojaExcel.="<th width='2%'>SI</th>";
    $hojaExcel.="<th width='2%'>NO</th>";
    $hojaExcel.="<th width='9%'>CANTIDAD</th>";
  $hojaExcel.="</tr>";
  
      ////BUSCAMOS LOS INGREDIENTES
      $instruccion7 ="SELECT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                      calculo_requerimientos.cod_categoria_ingrediente AS cod_categoria, categoria_ingrediente.nombre AS nom_categoria, 
                      SUM(calculo_requerimientos.cantidad) AS cantidad, ingrediente.unidad_base AS unidad_base 
                      FROM calculo_requerimientos 
                      INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente 
                      INNER JOIN categoria_ingrediente ON categoria_ingrediente.cod_categoria_ingrediente = calculo_requerimientos.cod_categoria_ingrediente 
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_escuela = $cod_escuela 
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta
                      GROUP BY calculo_requerimientos.cod_ingrediente
                      ORDER BY calculo_requerimientos.cod_categoria_ingrediente, calculo_requerimientos.cod_ingrediente";
     
      $consulta7 = mysql_query($instruccion7);
      error_consulta($consulta7,$instruccion7);
      $nfilas7 = mysql_num_rows ($consulta7); 

      for ($i=0; $i<$nfilas7; $i++){
        $row7 = mysql_fetch_array($consulta7);
        
        $cod_ingrediente  = $row7['cod_ingrediente'];
        $nom_ingrediente  = $row7['nom_ingrediente'];
        $nom_ingrediente  = strtoupper($nom_ingrediente);
        $cod_cat_ingredi  = $row7['cod_categoria'];
        $nom_cat_ingredi  = $row7['nom_categoria'];
        $cantidad         = $row7['cantidad'];
        $unidad_base      = $row7['unidad_base'];
        
      $hojaExcel.="<tr>";
        $hojaExcel.="<td>$nom_ingrediente</td>";        
        
       ////BUSCAMOS LOS DEMAS DATOS DE LOS INGREDIENTES
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
           $hojaExcel.="<td align='center'>$cantidad_redondeada</td>";             
          
          }  
          
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
        $hojaExcel.="<td>&nbsp;</td>";
      $hojaExcel.="</tr>";
  }
  
$hojaExcel.="</table>";

$hojaExcel.="<table>";
 $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='1' align='center'><strong>C: Cumple &nbsp;&nbsp;&nbsp; NC: No Cumple</strong></td>"; 
 $hojaExcel.="</tr>";
$hojaExcel.="<table>"; 

 //// LLAMAMOS LAS FUNCIONES QUE TRAEN LAS OBSERVACIONES     
 $cad_pro = observacion_programacion($cod_programacion,$cod_tipo_minuta,1);   
 $cad_mun = observacion_municipio($cod_programacion,$cod_municipio,$cod_tipo_minuta,1);
 $cad_esc = observacion_escuela($cod_programacion,$cod_escuela,$cod_tipo_minuta,1);  

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='5'>OBSERVACIONES</th>";
    $hojaExcel.="<td width='87%'>&nbsp;$cad_pro</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;$cad_mun</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;$cad_esc</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";

$hojaExcel.="<table width='90%' border='1'>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td width='50%'><strong>NOMBRE FUNCIONARIO OPERADOR QUE ENTREGA: </strong></td>";
    $hojaExcel.="<td width='50%'><strong>NOMBRE RESPONSABLE PAE ESTABLECIMIENTO EDUCATIVO: </strong></td>";

  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td><strong>CARGO:</td>";
    $hojaExcel.="<td><strong>CARGO:</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td><strong>No DOCUMENTO DE IDENTIFICACI&Oacute;N: </strong></td>";
    $hojaExcel.="<td><strong>No DOCUMENTO DE IDENTIFICACI&Oacute;N: </strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td><strong>FIRMA:</strong></td>";
    $hojaExcel.="<td><strong>FIRMA:</strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>"; 

$hojaExcel.="<br>";

$hojaExcel.="<table width='98%' border='0'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><div align='center'>";
      $hojaExcel.="<p>Calle 43 No. 57 - 14 Centro Administrativo Nacional - CAN, Bogot&aacute; D.C.</p>";
      $hojaExcel.="<p>PBX: +57 (1) 222 2800 - Fax 222 4953</p>";
      $hojaExcel.="<p>www.mineducacion.gov.co - atencionalciudadano@.mineducacion.gov.co </p>";
    $hojaExcel.="</div></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="</H1>";   
  ////FIN FORMATO MODELO 2015 INDUSTRIALIZADOS
}

if ($cod_tipo_minuta == 3 || $cod_tipo_minuta == 16 || $cod_tipo_minuta == 19 || $cod_tipo_minuta == 23 || $cod_tipo_minuta == 17 || $cod_tipo_minuta == 20 ||
    $cod_tipo_minuta == 57 || $cod_tipo_minuta == 58 || $cod_tipo_minuta == 61 || $cod_tipo_minuta == 65 || $cod_tipo_minuta == 66 || $cod_tipo_minuta == 67 || 
    $cod_tipo_minuta == 72 || $cod_tipo_minuta == 73 || $cod_tipo_minuta == 75 || $cod_tipo_minuta == 81 || $cod_tipo_minuta == 82 || $cod_tipo_minuta == 83 || 
    $cod_tipo_minuta == 85 || $cod_tipo_minuta == 86 || $cod_tipo_minuta == 90 || $cod_tipo_minuta == 92 || $cod_tipo_minuta == 101){

 if($cod_tipo_minuta == 17 || $cod_tipo_minuta == 20 || $cod_tipo_minuta == 58 || $cod_tipo_minuta == 65 || $cod_tipo_minuta == 81 || $cod_tipo_minuta == 82){
   $titulo ="REMISIÓN ENTREGA DE VÍVERES EN COMEDORES ESCOLARES - RACIÓN PREPARADA EN SITIO - COMPLEMENTO ALIMENTARIO JM/JT ";
   }

 if($cod_tipo_minuta == 3 || $cod_tipo_minuta == 16 || $cod_tipo_minuta == 19 || $cod_tipo_minuta == 23 || $cod_tipo_minuta == 57 || $cod_tipo_minuta == 61
   || $cod_tipo_minuta == 66 || $cod_tipo_minuta == 67 || $cod_tipo_minuta == 72 || $cod_tipo_minuta == 83){
   $titulo ="ENTREGA VÍVERES- RACIÓN PREPARADA EN SITIO - ALMUERZO";
   }  
   
 if($cod_tipo_minuta == 73 ){
   $titulo ="ENTREGA VÍVERES- RACIÓN PREPARADA EN SITIO - COMIDA";
   }
   
 if($cod_tipo_minuta == 75 ){
   $titulo ="ENTREGA VÍVERES- RACIÓN PREPARADA EN SITIO";
   }    
   
 if($cod_tipo_minuta == 85 ){
   $titulo ="COMPLEMENTO ALIMENTARIO JORNADA MAÑANA/TARDE PREPARADO EN SITIO IBAGUE URBANO";
   }  
   
  if($cod_tipo_minuta == 86 ){
   $titulo ="COMPLEMENTO ALIMENTARIO JORNADA MAÑANA/TARDE PREPARADO EN SITIO IBAGUE RURAL";
   }  

  if($cod_tipo_minuta == 90 || $cod_tipo_minuta == 92){
   $titulo ="ALMUERZO PREPARADO EN SITIO TOLIMA -IBAGUE";
   }                                                               

  ///************************INICIO FORMATO MODELO 2015 TRADICIONAL Y ALMUERZOS
$hojaExcel.="<H1 class=SaltoDePagina>";
$hojaExcel.="<table width='98%'' border='0'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><div align='right'>$logo&nbsp;&nbsp;$logo_4&nbsp;&nbsp;$logo_3</div></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th><div align='center'>$titulo</div></th>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";  

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='60%'><strong>OPERADOR: $nom_operador</strong></td>";
    $hojaExcel.="<td width='40%'><strong>FECHA (DD/MM/AAAA): ____ / ____ / ________ </strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='57%'><strong>DEPARTAMENTO: $nom_departamento</strong></td>";
    $hojaExcel.="<td width='43%'><strong>MUNICIPIO: $nom_municipio</strong></td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><strong>ESTABLECIMIENTO EDUCATIVO: </strong></td>";
    $hojaExcel.="<td><strong>SEDE: $nom_escuela</strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td width='43%'><strong>DIRECCI&Oacute;N:</strong></td>";
    $hojaExcel.="<td width='35%'><strong>BARRIO/VEREDA:</strong></td>";
    $hojaExcel.="<td width='22%'><strong>TEL&Eacute;FONO:</strong></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";   

  ////BUSCAMOS LA JORNADA DE LA ESCUELA
  $instruccion_jor ="SELECT DISTINCT minuta_escuela.cod_jornada AS cod_jornada FROM minuta_escuela WHERE minuta_escuela.cod_escuela = $cod_escuela";
 
  $consulta_jor = mysql_query($instruccion_jor);
  error_consulta($consulta_jor,$instruccion_jor);
  $row_jor = mysql_fetch_array($consulta_jor);
  
  $jornada = $row_jor['cod_jornada'];  

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

      $cupos_total = 0;
      
      ////CONSULTAMOS LOS CUPOS TOTALES
      $instruccion_ct ="SELECT DISTINCT calculo_requerimientos.cod_rango_edad AS cod_rango_edad, rango_edad.nombre AS nombre, 
                                      calculo_requerimientos.cupos AS cupos
                      FROM calculo_requerimientos 
                      INNER JOIN rango_edad ON rango_edad.cod_rango_edad = calculo_requerimientos.cod_rango_edad  
                      WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_modalidad = $cod_modalidad
                        AND calculo_requerimientos.cod_municipio = $cod_municipio AND calculo_requerimientos.cod_escuela = $cod_escuela
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
     
      $consulta_ct = mysql_query($instruccion_ct);
      error_consulta($consulta_ct,$instruccion_ct);
      $nfilas_ct = mysql_num_rows ($consulta_ct);
      
      for ($c=0; $c<$nfilas_ct; $c++){
        $row_ct = mysql_fetch_array($consulta_ct);
        
        $cupos_ct = $row_ct['cupos'];
        
        $cupos_total = $cupos_total + $cupos_ct;
        
        } 
      
$hojaExcel.="<br>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='12%' rowspan='2'>RANGO DE EDAD </th>";
    $hojaExcel.="<th width='13%' rowspan='2'>N&deg; RACIONES ADJUDICADAS </th>";
    $hojaExcel.="<th width='13%' rowspan='2'>N&deg; RACIONES ATENDIDAS </th>";
    $hojaExcel.="<th width='10%' rowspan='2'>N&deg; D&Iacute;AS A ATENDER </th>";
    $hojaExcel.="<th width='10%' rowspan='2'>N&deg; MENU </th>";
    $hojaExcel.="<th width='16%' rowspan='2'>N&deg; SEMANA DEL CICLO DE MEN&Uacute;S </th>";
    $hojaExcel.="<th height='20' colspan='2'>TOTAL RACIONES </th>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th>JM</th>";
    $hojaExcel.="<th>JT</th>";
  $hojaExcel.="</tr>";    

        for ($i=0; $i<$nfilas6; $i++){
        $row6 = mysql_fetch_array($consulta6);
        
        $cod_rango = $row6['cod_rango_edad'];
        $nom_rango = $row6['nombre'];
        $cupos = $row6['cupos'];  

        $am_cupos = "";
        $pm_cupos = "";       

        if($jornada == 'AM'){
            $am_cupos = $cupos_total;
           }  
        if($jornada == 'PM'){
            $pm_cupos = $cupos_total;
           }              

          $hojaExcel.="<tr>";
            $hojaExcel.="<td align='center'>$nom_rango</td>";
            $hojaExcel.="<td align='center'>$cupos</td>";
            $hojaExcel.="<td align='center'>$cupos</td>";
            $hojaExcel.="<td align='center'>$nfilas_men</td>";
            $hojaExcel.="<td align='center'>$cad_menu</td>";
            $hojaExcel.="<td align='center'>$cad_fecha - $nom_ciclo</td>";
           if($i==0){ 
             $hojaExcel.="<td width='10%' rowspan='$nfilas6' align='center'>$am_cupos</td>";
             $hojaExcel.="<td width='10%' rowspan='$nfilas6' align='center'>$pm_cupos</td>"; 
            } 
        $hojaExcel.="</tr>";
       }
                                
$hojaExcel.="</table>";

$hojaExcel.="<br>";

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='20%' rowspan='2' align='center'>ALIMENTO</th>";
    $hojaExcel.="<th width='15%' colspan='$nfilas_re' rowspan='1' align='center'>CANTIDAD DE ALIMENTOS POR NUMERO DE RACIONES</th>";
    $hojaExcel.="<th width='2%' rowspan='2' align='center'>UNIDAD DE MEDIDA</th>";
    $hojaExcel.="<th width='4%' rowspan='2' align='center'>CANTIDAD TOTAL</th>";
    $hojaExcel.="<th width='11%' colspan='3' rowspan='1' align='center'>CANTIDAD ENTREGADA</th>";
    $hojaExcel.="<th width='8%' colspan='2' rowspan='1' align='center'>ESPECIFICACIONES DE CALIDAD</th>";
    $hojaExcel.="<th width='10%' colspan='3' rowspan='1' align='center'>FALTANTES</th>";
    $hojaExcel.="<th width='10%' colspan='3' rowspan='1' align='center'>DEVOLUCIONES</th>";
    
     
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

       $hojaExcel.="<td rowspan='1' align='center'>Total</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>C</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>NC</td>";
       $hojaExcel.="<td rowspan='1' align='center'>C</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>NC</td>";
       $hojaExcel.="<td rowspan='1' align='center'>SI</td>";
       $hojaExcel.="<td rowspan='1' align='center'>NO</td>"; 
       $hojaExcel.="<td rowspan='1' align='center'>Cantidad</td>";   
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
                        AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta
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
        
        if($cod_ingrediente==55) $unidad_base = 'gr';  ////PONEMOS LA UNIDAD BASE DEL HUEVO PARA EL FORMATO EN GR YA QUE SALIA EN UND Y SE PODIA PRESTAR PARA CONFUSIONES        
        
        $conse = $i + 1;
        
        if($cat_anterior != $cod_cat_ingredi){
           $hojaExcel.="<tr><th colspan='$columnas' align='center'>$nom_cat_ingredi</th></tr>";
          }
        $cat_anterior = $cod_cat_ingredi;  

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
                             AND calculo_requerimientos.cod_ingrediente = $cod_ingrediente AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta";
                                   
          $consulta_q = mysql_query($instruccion_q);
          error_consulta($consulta_q,$instruccion_q);              
          $row_q = mysql_fetch_array($consulta_q);   
          
          if($row_q[racion] == '')  $row_q[racion] = 0;

          $hojaExcel.="<td align='center'>$row_q[racion]</td>";
           
           }  

        $hojaExcel.="<td align='center'>$unidad_base</td>";   
        $hojaExcel.="<td align='center'>$cantidad</td>";   
 
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
          $hojaExcel.="<td align='center'>$cantidad_redondeada [$nom_unidad]</td>";
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
  
         }                     
       $hojaExcel.="</tr>";  
      }    
$hojaExcel.="</table>";

$hojaExcel.="<table>";
 $hojaExcel.="<tr>";
    $hojaExcel.="<td rowspan='1' align='center'><strong>C: Cumple &nbsp;&nbsp;&nbsp; NC: No Cumple</strong></td>"; 
 $hojaExcel.="</tr>";
$hojaExcel.="<table>"; 

 //// LLAMAMOS LAS FUNCIONES QUE TRAEN LAS OBSERVACIONES     
 $cad_pro = observacion_programacion($cod_programacion,$cod_tipo_minuta,1);   
 $cad_mun = observacion_municipio($cod_programacion,$cod_municipio,$cod_tipo_minuta,1);
 $cad_esc = observacion_escuela($cod_programacion,$cod_escuela,$cod_tipo_minuta,1);  

$hojaExcel.="<table width='98%'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<th width='13%' rowspan='5'>OBSERVACIONES</th>";
    $hojaExcel.="<td width='87%'>&nbsp;$cad_pro</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;$cad_mun</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;$cad_esc</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";  
  $hojaExcel.="<tr>";
    $hojaExcel.="<td>&nbsp;</td>";
  $hojaExcel.="</tr>";  
$hojaExcel.="</table>";

$hojaExcel.="<table width='98%' border='1'>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td width='33%'>NOMBRE TRANSPORTADOR QUE ENTREGA (OPERADOR):</td>";
    $hojaExcel.="<td width='34%'>NOMBRE MANIPULADOR DE ALIMENTOS QUE RECIBE (OPERADOR):</td>";
    $hojaExcel.="<td width='33%'>NOMBRE RESPONSABLE ESTABLECIMIENTO EDUCATIVO:</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td>CARGO:</td>";
    $hojaExcel.="<td>N&deg; DOCUMENTO DE IDENTIFICACI&Oacute;N:</td>";
    $hojaExcel.="<td>CARGO:</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td>N&deg; DOCUMENTO DE IDENTIFICACI&Oacute;N:</td>";
    $hojaExcel.="<td>FIRMA:</td>";
    $hojaExcel.="<td>N&deg; DOCUMENTO DE IDENTIFICACI&Oacute;N:</td>";
  $hojaExcel.="</tr>";
  $hojaExcel.="<tr style='height:20px;'>";
    $hojaExcel.="<td>FIRMA:</td>";
    $hojaExcel.="<td>&nbsp;</td>";
    $hojaExcel.="<td>FIRMA:</td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="<br>";

$hojaExcel.="<table width='98%' border='0'>";
  $hojaExcel.="<tr>";
    $hojaExcel.="<td><div align='center'>";
      $hojaExcel.="<p>Calle 43 No. 57 - 14 Centro Administrativo Nacional - CAN, Bogot&aacute; D.C.</p>";
      $hojaExcel.="<p>PBX: +57 (1) 222 2800 - Fax 222 4953</p>";
      $hojaExcel.="<p>www.mineducacion.gov.co - atencionalciudadano@.mineducacion.gov.co </p>";
    $hojaExcel.="</div></td>";
  $hojaExcel.="</tr>";
$hojaExcel.="</table>";

$hojaExcel.="</H1>";   
  ////FIN FORMATO MODELO 2015 TRADICIONAL  Y ALMUERZOS
}
 
 } 
    
echo $hojaExcel; 

    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/FormatoListaEntrega"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
