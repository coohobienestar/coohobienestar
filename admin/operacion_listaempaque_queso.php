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
ini_set('max_execution_time',0);
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
$tipo_informe = $_GET['tipo_informe'];
$tipo_minuta= $_GET['tipo_minuta'];

if ($cod_municipio != 0){
  $condicion = " AND calculo_requerimientos.cod_municipio = $cod_municipio ";
  }

if ($tipo_minuta != 0){
  $condicion = " AND calculo_requerimientos.cod_tipo_minuta = $tipo_minuta ";
  }
  
 $cod_queso = 107; 

  
  ////BUSCAMOS EL CICLO    
  $instruccion_ciclo ="SELECT DISTINCT calculo_requerimientos.cod_ciclo AS cod_ciclo, ciclo.nombre as nom_ciclo, calculo_requerimientos.cod_minuta AS cod_minuta
                       FROM calculo_requerimientos
                       INNER JOIN ciclo ON ciclo.cod_ciclo = calculo_requerimientos.cod_ciclo 
                       WHERE calculo_requerimientos.cod_programacion = $cod_programacion
                       LIMIT 1";
 
  $consulta_ciclo = mysql_query($instruccion_ciclo);
  error_consulta($consulta_ciclo,$instruccion_ciclo);
  $row_ciclo = mysql_fetch_array($consulta_ciclo);  
  
  $cod_ciclo = $row_ciclo['cod_ciclo'];
  $nom_ciclo = $row_ciclo['nom_ciclo'];
  $nom_ciclo = substr($nom_ciclo,0,7);
  $cod_minuta = $row_ciclo['cod_minuta']; 
  
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

  ////BUSCAMOS EL DEPARTAMENTO DEL MUNICIPIO BASADOS EN LA PROGRAMACION                             
  $instruccion_dep ="SELECT calculo_requerimientos.cod_departamento FROM calculo_requerimientos WHERE calculo_requerimientos.cod_programacion =  $cod_programacion";
 
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
  
  $logo = ""; 

////LLAMAMOS LA FUNCION QUE CREA LA CADENA DE FECHA 
   $cad_fecha = generar_fecha($cod_programacion);

////TOTALES POR MUNICIPIO
if($tipo_informe == 1){
////BUSCAMOS LOS TIPOS DE MINUTA
$instruccion_0 ="SELECT DISTINCT cod_tipo_minuta AS cod_tipo_minuta FROM calculo_requerimientos WHERE cod_programacion = $cod_programacion";

$consulta_0 = mysql_query($instruccion_0);
error_consulta($consulta_0,$instruccion_0);
$nfilas_0 = mysql_num_rows ($consulta_0);

for ($a=0; $a<$nfilas_0; $a++){
$row_0 = mysql_fetch_array($consulta_0);

$cod_tipo_minuta = $row_0['cod_tipo_minuta'];
   
  ////BUSCAMOS LOS MUNICIPIOS
  $instruccion2 ="SELECT DISTINCT calculo_requerimientos.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio, 
                                  tipo_minuta.nombre AS nom_tipo_minuta 
                  FROM calculo_requerimientos
                  INNER JOIN municipio ON municipio.cod_municipio = calculo_requerimientos.cod_municipio 
                  INNER JOIN minuta ON minuta.cod_minuta = calculo_requerimientos.cod_minuta
                  INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = minuta.cod_tipo_minuta 
                  WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_departamento = $cod_departamento 
                    AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta  $condicion
                  ORDER BY tipo_minuta.nombre, calculo_requerimientos.cod_departamento, calculo_requerimientos.cod_municipio";
  
  $consulta2 = mysql_query($instruccion2);
  error_consulta($consulta2,$instruccion2);
  $nfilas2 = mysql_num_rows ($consulta2);
   
  for ($j=0; $j<$nfilas2; $j++){
    $row2 = mysql_fetch_array($consulta2);
    
    $cod_municipio = $row2['cod_municipio'];
    $nom_municipio = $row2['nom_municipio']; 
    $nom_tipo_minuta = $row2['nom_tipo_minuta'];
      
      ////BUSCAMOS LAS CATEGORIAS DE LOS ALIMENTOS
      $instruccion ="SELECT cod_categoria_ingrediente, nombre FROM categoria_ingrediente WHERE cod_categoria_ingrediente = 2 ORDER BY cod_categoria_ingrediente";
     
      $consulta = mysql_query($instruccion);
      error_consulta($consulta,$instruccion);
      $nfilas = mysql_num_rows ($consulta);
      
      for ($i=0; $i<$nfilas; $i++){
        $row = mysql_fetch_array($consulta);
        
        $cod_categoria = $row['cod_categoria_ingrediente'];
        $nom_categoria = $row['nombre'];       

        ////BUSCAMOS LAS ESCUELAS DE LA PROGRAMACION PARA EL MUNICIPIO Y LA CATEGORIA DE ALIMENTO
        $instruccion3 ="SELECT DISTINCT calculo_redondeado_escuela.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela 
                        FROM calculo_redondeado_escuela
                        INNER JOIN escuela ON escuela.cod_escuela = calculo_redondeado_escuela.cod_escuela 
                        WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_municipio = $cod_municipio  
                          AND calculo_redondeado_escuela.cod_categoria_ingrediente = $cod_categoria AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta 
                          AND calculo_redondeado_escuela.cod_ingrediente = $cod_queso
                        ORDER BY calculo_redondeado_escuela.cod_escuela";
     
        $consulta3 = mysql_query($instruccion3);
        error_consulta($consulta3,$instruccion3);
        $nfilas3 = mysql_num_rows ($consulta3);   
                    
       if($nfilas3 > 0){            
        ////BUSCAMOS LOS INGREDIENTES DEL MUNICIPIO EN ESA CATEGORIA
        $instruccion4 ="SELECT DISTINCT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente
                        FROM calculo_requerimientos
                        INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente   
                        WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio
                          AND calculo_requerimientos.cod_categoria_ingrediente = $cod_categoria AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta 
                          AND calculo_requerimientos.cod_ingrediente = $cod_queso
                        ORDER BY calculo_requerimientos.cod_ingrediente";
     
        $consulta4 = mysql_query($instruccion4);
        error_consulta($consulta4,$instruccion4);
        $nfilas4 = mysql_num_rows ($consulta4);
 
        $columnas = $nfilas4 + 2;
        
      $hojaExcel.="<H1 class=SaltoDePagina>"; 
        ////DIBUJAMOS EL ENCABEZADO DE LA TABLA
        $hojaExcel.="<table width='99%' border='1'>";
        $hojaExcel.="<tr>";
        $hojaExcel.="<th colspan='$columnas'><div align='center'>ORDEN DE PEDIDO $nom_categoria<BR>$nom_operador <BR> $nit <BR> Ciclo de Menu:&nbsp; $nom_ciclo &nbsp;&nbsp;&nbsp; $cad_menu <BR> $cad_fecha <BR> MUNICIPIO $nom_municipio <BR> $nom_tipo_minuta</div></th>";
        $hojaExcel.="</tr>";
        $hojaExcel.="</table>";        
       
        $hojaExcel.="<table width='99%' border='1'>";
        $hojaExcel.="<tr>";
        
        $cupos_total = 0;
                  
        for ($l=0; $l<$nfilas4; $l++){
          $row4 = mysql_fetch_array($consulta4);
          
          $cod_ingrediente = $row4['cod_ingrediente'];
          $nom_ingrediente = $row4['nom_ingrediente']; 
          $nom_ingrediente = strtoupper($nom_ingrediente);     
          
          if($l == 0) {
            $hojaExcel.="<td><strong>&nbsp;</strong></td>";
            $hojaExcel.="<th>&nbsp;</th>";
           }             
            $hojaExcel.="<th align='center'>$nom_ingrediente</th>"; 
                                
        } 
        $hojaExcel.="</tr>";  

        ////SE AGREGA LA FILA PARA PONER LA UNIDAD DE MEDIDA

        ////BUSCAMOS LOS INGREDIENTES DEL MUNICIPIO EN ESA CATEGORIA
        $instruccion4 ="SELECT DISTINCT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente, 
                                        unidad_medida.nombre AS nom_unidad_medida 
                        FROM calculo_requerimientos
                        INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente  
                        INNER JOIN ingrediente_unidad_entrega ON ingrediente_unidad_entrega.cod_ingrediente = ingrediente.cod_ingrediente
                        INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = ingrediente_unidad_entrega.cod_unidad_medida                          
                        WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio
                          AND calculo_requerimientos.cod_categoria_ingrediente = $cod_categoria AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta 
                          AND calculo_requerimientos.cod_ingrediente = $cod_queso 
                          AND (ingrediente_unidad_entrega.cod_departamento = 0 OR ingrediente_unidad_entrega.cod_departamento = $cod_departamento)
                        ORDER BY calculo_requerimientos.cod_ingrediente";
     
        $consulta4 = mysql_query($instruccion4);
        error_consulta($consulta4,$instruccion4);
        $nfilas4 = mysql_num_rows ($consulta4); 
        
      $hojaExcel.="<tr>";         
        
        for ($l=0; $l<$nfilas4; $l++){
             $row4 = mysql_fetch_array($consulta4);
                
             $nom_unidad_medida = $row4['nom_unidad_medida']; 
          if($l == 0) {
            $hojaExcel.="<td><strong>ESCUELA</strong></td>";
            $hojaExcel.="<th>CUPOS</th>";
           }             
            $hojaExcel.="<th align='center'>$nom_unidad_medida</th>";
          }   
        $hojaExcel.="</tr>";    
        
          for ($k=0; $k<$nfilas3; $k++){
            $row3 = mysql_fetch_array($consulta3);
            
            $cod_escuela = $row3['cod_escuela'];
            $nom_escuela = $row3['nom_escuela'];
            $nom_escuela = strtoupper($nom_escuela);
            
            $hojaExcel.="<tr>";  
              
              ////BUSCAMOS LOS INGREDIENTES DEL MUNICIPIO EN ESA CATEGORIA
              $instruccion4 ="SELECT DISTINCT calculo_requerimientos.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente,
                                              unidad_medida.valor_gr_cc AS valor_gr_cc, ingrediente.redondear AS redondear 
                              FROM calculo_requerimientos
                              INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_requerimientos.cod_ingrediente  
                              INNER JOIN ingrediente_unidad_entrega ON ingrediente_unidad_entrega.cod_ingrediente = ingrediente.cod_ingrediente
                              INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = ingrediente_unidad_entrega.cod_unidad_medida                          
                              WHERE calculo_requerimientos.cod_programacion = $cod_programacion AND calculo_requerimientos.cod_municipio = $cod_municipio
                                AND calculo_requerimientos.cod_categoria_ingrediente = $cod_categoria AND calculo_requerimientos.cod_tipo_minuta = $cod_tipo_minuta 
                                AND calculo_requerimientos.cod_ingrediente = $cod_queso
                                AND (ingrediente_unidad_entrega.cod_departamento = 0 OR ingrediente_unidad_entrega.cod_departamento = $cod_departamento)
                              ORDER BY calculo_requerimientos.cod_ingrediente";
           
              $consulta4 = mysql_query($instruccion4);
              error_consulta($consulta4,$instruccion4);
              $nfilas4 = mysql_num_rows ($consulta4);
                       
              for ($l=0; $l<$nfilas4; $l++){
                $row4 = mysql_fetch_array($consulta4);
                
                $cod_ingrediente = $row4['cod_ingrediente'];
                $valor_gr_cc = $row4['valor_gr_cc'];
                $redondear = $row4['redondear'];
                
                ////BUSCAMOS LA CANTIDAD DEL INGREDIENTE PARA LA ESCUELA
                $instruccionq ="SELECT SUM(cantidad_redondeada) AS cantidad 
                                FROM calculo_redondeado_escuela
                                WHERE cod_programacion = $cod_programacion AND cod_escuela=$cod_escuela AND cod_ingrediente=$cod_ingrediente 
                                AND cod_tipo_minuta = $cod_tipo_minuta";
   
                $consultaq = mysql_query($instruccionq);
                error_consulta($consultaq,$instruccionq);
                $rowq = mysql_fetch_array($consultaq);
                 
                $cantidad = $rowq['cantidad']; 
           
                $cupos = 0; 
                if($l == 0){
                   ////BUSCAMOS LOS CUPOS
                   $instruccion_c ="SELECT DISTINCT cod_rango_edad, cupos 
                                    FROM calculo_requerimientos 
                                    WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela AND cod_tipo_minuta = $cod_tipo_minuta";
   
                   $consulta_c = mysql_query($instruccion_c);
                   error_consulta($consulta_c,$instruccion_c);
                   $nfilas_c = mysql_num_rows ($consulta_c);
                    
                    for ($m=0; $m<$nfilas_c; $m++){
                      $row_c = mysql_fetch_array($consulta_c);
                      
                      $cupos_re = $row_c['cupos']; 
                      
                      $cupos = $cupos + $cupos_re;
                     }                     
                   $cupos_total = $cupos_total + $cupos;
  
                   $hojaExcel.="<td>$nom_escuela</td>";
                   $hojaExcel.="<td align='center'>$cupos</td>";                    
                 }
                 
                if($cantidad == '') $cantidad = 0;               
                
                $hojaExcel.="<td align='center'>$cantidad</td>";
                }          
            $hojaExcel.="</tr>";
           }
            if($k == $nfilas3){
               $hojaExcel.="<tr>";
               $hojaExcel.="<td><strong>TOTAL $nom_municipio</strong></td>";
               $hojaExcel.="<th>$cupos_total</th>";
               
               ////SACAMOS EL TOTAL DEL MUNICIPIO
               $instruccion6 ="SELECT DISTINCT calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, ingrediente.nombre AS nom_ingrediente
                               FROM calculo_redondeado_escuela
                               INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente
                               INNER JOIN ingrediente_unidad_entrega ON ingrediente_unidad_entrega.cod_ingrediente = ingrediente.cod_ingrediente
                               INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = ingrediente_unidad_entrega.cod_unidad_medida      
                               WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion AND calculo_redondeado_escuela.cod_municipio = $cod_municipio
                                 AND calculo_redondeado_escuela.cod_categoria_ingrediente = $cod_categoria AND calculo_redondeado_escuela.cod_ingrediente = $cod_queso
                                 AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta  AND calculo_redondeado_escuela.cod_departamento = $cod_departamento
                               ORDER BY calculo_redondeado_escuela.cod_ingrediente";
   
               $consulta6 = mysql_query($instruccion6);
               error_consulta($consulta6,$instruccion6);
               $nfilas6 = mysql_num_rows ($consulta6);
            
              for ($m=0; $m<$nfilas6; $m++){
                $row6 = mysql_fetch_array($consulta6); 
                
                 ////BUSCAMOS LA CANTIDAD TOTAL DEL INGREDIENTE
                $instruccionq ="SELECT SUM(cantidad_redondeada) AS cantidad_total, cod_escuela 
                                FROM calculo_redondeado_escuela 
                                WHERE cod_programacion = $cod_programacion AND cod_municipio = $cod_municipio AND cod_ingrediente = $row6[cod_ingrediente]
                                  AND calculo_redondeado_escuela.cod_tipo_minuta = $cod_tipo_minuta
                                GROUP BY cod_escuela";
   
                $consultaq = mysql_query($instruccionq);
                error_consulta($consultaq,$instruccionq);
                $nfilasq = mysql_num_rows ($consultaq);
                
              for ($x=0; $x<$nfilasq; $x++){ 
                
                $rowq = mysql_fetch_array($consultaq);
                                                                            
                $cantidad_total = $rowq['cantidad_total'];                 
                   
                $cantidad_total_final = $cantidad_total_final + $cantidad_total; 

                  }
                   
                $hojaExcel.="<th>$cantidad_total_final</th>";
                $cantidad_total_final = 0;
                
                }
            $hojaExcel.="</tr>";
          }  
       $hojaExcel.="</table>";
    $hojaExcel.="</H1>";  
      } 
    }     
  }  
 }
}   

?>
<html>
<head>
<title>INFORME LISTA DE EMPAQUE QUESO</title>
</head>
<body>  
  <?php 
    echo ($hojaExcel);
    
    $fecha = date("Ymd_His");
    
    $login=trim($_SESSION['login']);
    $sfile="../excel/ope_lista_empaqueQueso"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
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
