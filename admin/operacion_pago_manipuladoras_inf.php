<?php   
session_start();

include("../conexion/conectarbd.php"); ////CONEXION A LA BD
include("../funciones/calculo_documento_equivalente.php");
$conexion=Conectarse(); 

$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag']; 

$anio = $_GET['anio'];
$mes  = $_GET['mes'];
$tipo_operacion = $_GET['tipo_operacion'];

  ////DEFINIMOS EL NUEMRO DE DIAS QUE TIENE EL MES
  if($mes=='01'){ $ndiasmes=31; $mes_nombre='ENERO';}
  if($mes=='02'){ if($bisiesto==1){$ndiasmes=29;}else{$ndiasmes=28;} $mes_nombre='FEBRERO';}
  if($mes=='03'){ $ndiasmes=31; $mes_nombre='MARZO';}
  if($mes=='04'){ $ndiasmes=30; $mes_nombre='ABRIL';}
  if($mes=='05'){ $ndiasmes=31; $mes_nombre='MAYO';}
  if($mes=='06'){ $ndiasmes=30; $mes_nombre='JUNIO';}
  if($mes=='07'){ $ndiasmes=31; $mes_nombre='JULIO';}
  if($mes=='08'){ $ndiasmes=31; $mes_nombre='AGOSTO';}
  if($mes=='09'){ $ndiasmes=30; $mes_nombre='SEPTIEMBRE';}
  if($mes=='10'){ $ndiasmes=31; $mes_nombre='OCTUBRE';}
  if($mes=='11'){ $ndiasmes=30; $mes_nombre='NOVIEMBRE';}
  if($mes=='12'){ $ndiasmes=31; $mes_nombre='DICIEMBRE';}  

$nom_form = " PAGO A MANIPULADORAS";

if($tipo_operacion == 5){
   $nom_operacion = "VISUALIZAR DOCUMENTOS EQUIVALENTES PARA ";
   $icono = "informe.png";
   
   $disabled = "";
 }

?>

<link rel="stylesheet" type="text/css" href="../estilos/estilo_fcalidad_vis.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<body>

<?php
 if($tipo_operacion == 5){  
 
  ////BUSCAMOS LOS DATOS DEL OPERADOR
  $instruccion_ope ="SELECT operador.nombre AS nombre, operador.nit AS nit, operador.logo  AS logo, operador.direccion AS direccion, 
                            operador.telefono AS telefono, operador.municipio AS municipio, departamento.nombre AS nom_departamento
                     FROM operador
                     INNER JOIN departamento ON departamento.cod_operador = operador.cod_operador 
                     WHERE departamento.cod_departamento = 1";
 
  $consulta_ope = mysql_query($instruccion_ope);
  error_consulta($consulta_ope,$consulta_ope);
  $row_ope = mysql_fetch_array($consulta_ope);  
  
  $nom_operador = $row_ope['nombre']; 
  $nit          = $row_ope['nit']; 
  $logo         = $row_ope['logo'];
  $direccion_op = $row_ope['direccion'];
  $telefono_op     = $row_ope['telefono'];
  $municipio    = $row_ope['municipio']; 
  $nom_departamento = $row_ope['nom_departamento'];  
  
  ////BUSCAMOS EL VALOR DE concepto_doc_equival_manipulad
  $instruccion_concepto ="SELECT valor FROM parametro WHERE nombre='concepto_doc_equival_manipulad'";
  
  $consulta_concepto = mysql_query($instruccion_concepto);
  error_consulta($consulta_concepto,$instruccion_concepto);
  $row_concepto = mysql_fetch_array($consulta_concepto);
  
  $concepto = $row_concepto['valor'];
  
  ////BUSCAMOS EL VALOR DE descripcion_doc_equival_manipu
  $instruccion_descrip ="SELECT valor FROM parametro WHERE nombre='descripcion_doc_equival_manipu'";
  
  $consulta_descrip  = mysql_query($instruccion_descrip);
  error_consulta($consulta_descrip,$instruccion_descrip);
  $row_descrip  = mysql_fetch_array($consulta_descrip);
  
  $descrip  = $row_descrip['valor'];  
  
  ////BUSCAMOS EL VALOR DE LA RACION
  $instruccion_val_racion ="SELECT valor FROM parametro WHERE nombre='valor_racion'";
  
  $consulta_val_racion = mysql_query($instruccion_val_racion);
  error_consulta($consulta_val_racion,$instruccion_val_racion);
  $row_val_racion = mysql_fetch_array($consulta_val_racion);
  
  $valor_racion = $row_val_racion['valor'];         
  
  ////BUSCAMOS LA FECHA ACTUAL
  $fecha = date("Y-m-d");
   
   ////BUSCAMOS LOS DATOS DEL DOCUMENTO EQUIVALENTE
   $sql = "SELECT documento_equivalente.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela, documento_equivalente.cod_manipuladora AS cod_manipuladora, 
                  documento_equivalente.num_documento AS num_documento, documento_equivalente.total_raciones AS total_raciones,
                  manipuladora.nombre AS nom_manipuladora, manipuladora.identificacion AS identificacion, 
                  manipuladora.direccion AS direccion, manipuladora.telefono AS telefono, 
                  documento_equivalente.subtotal_inc_retefuente AS subtotal_inc_retefuente, documento_equivalente.retefuente AS retefuente,
                  documento_equivalente.valor_retefuente AS valor_retefuente, documento_equivalente.reteiva AS reteiva, 
                  documento_equivalente.valor_reteiva      
           FROM documento_equivalente
           INNER JOIN manipuladora ON manipuladora.cod_manipuladora = documento_equivalente.cod_manipuladora 
           INNER JOIN escuela ON escuela.cod_escuela = documento_equivalente.cod_escuela
           WHERE anio = '$anio' AND mes = '$mes'
           ORDER BY documento_equivalente.cod_escuela, documento_equivalente.cod_manipuladora";
   $result = mysql_query($sql);
   error_consulta($result,$sql); 
   $nfilas = mysql_num_rows ($result);

   if($nfilas > 0){
    for($i=0; $i<$nfilas; $i++){   
      $resultado = mysql_fetch_array ($result);
      
      $cod_escuela = $resultado['cod_escuela'];
      $nom_escuela = $resultado['nom_escuela'];
      $cod_manipuladora = $resultado['cod_manipuladora'];
      $num_documento = $resultado['num_documento'];
      $total_raciones = $resultado['total_raciones'];
      $nom_manipuladora = $resultado['nom_manipuladora'];
      $identificacion = $resultado['identificacion'];
      $direccion = $resultado['direccion'];
      $telefono = $resultado['telefono'];
      $subtotal_inc_retefuente = $resultado['subtotal_inc_retefuente'];
       $subtotal_inc_retefuente = round($subtotal_inc_retefuente);
      $retefuente = $resultado['retefuente'];
      $valor_retefuente = $resultado['valor_retefuente'];
       $valor_retefuente = round($valor_retefuente); 
      $reteiva = $resultado['reteiva'];
      $valor_reteiva = $resultado['valor_reteiva'];
       $valor_reteiva = round($valor_reteiva);
       
       $total_pagar = $subtotal_inc_retefuente - $valor_retefuente;
         
print("<table width='98%' border='0' style='page-break-before: always;>'");
  print("<tr><td>");
print("<table width='98%' border='0'>");
  print("<tr>");
    print("<td width='50%'><strong><h2> $nom_operador </h2></strong> <br> $nit <br> $direccion_op  <br> TEL: $telefono_op <br> $municipio/$nom_departamento </td>");
    print("<td width='50%' align='right'><img src='../imagenes/$logo' width='134' height='60' /></td>");
  print("</tr>");
  print("<tr>"); 
print("</table>");   
print("<table width='98%' border='0'>");
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");
print("</table>");
print("<table width='98%' border='0'>");  
    print("<td>DOCUMENTO EQUIVALENTE A LA FACTURA PARA PERSONAS <br> NATURALES NO COMERCIANTES O INSCRITAS EN EL REGIMEN <br> SIMPLIFICADO <br> DECRETO #522 Art. 03 (Marzo 7 de 2003) </td>");
    print("<th>No: MP - $num_documento </th>");
  print("</tr>");
print("</table>");  
print("<table width='98%' border='0'>");
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");
print("</table>");  
print("<table width='98%' border='1'>");
  print("<tr>");
    print("<td width='40%'><strong>FECHA</strong></td>");
    print("<td width='60%'>$fecha </td>");
  print("</tr>");
  print("<tr>");
    print("<td><strong>NOMBRE</strong></td>");
    print("<td>$nom_manipuladora - $nom_escuela</td>");
  print("</tr>");
  print("<tr>");
    print("<td><strong>NIT o C.C: </strong></td>");
    print("<td>$identificacion </td>");
  print("</tr>");
  print("<tr>");
    print("<td><strong>DIRECCION Y/O TELEFONO </strong></td>");
    print("<td>$direccion &nbsp;&nbsp;&nbsp;&nbsp; $telefono</td>");
  print("</tr>");
  print("<tr>");
    print("<td><strong>CONCEPTO </strong></td>");
    print("<td>$concepto</td>");
  print("</tr>");
print("<table width='98%' border='0'>");  
  print("<tr>");
    print("<td colspan='2'>&nbsp;</td>");
  print("</tr>");
print("</table>");
print("<table width='98%' border='1'>");
  print("<tr>");
    print("<td align='center'><strong>DESCRIPCION </strong></td>");
    print("<td align='center'><strong>CANTIDAD </strong></td>");
    print("<td align='center'><strong>VR. UNITARIO </strong></td>");
    print("<td align='center'><strong>VR. TOTAL </strong></td>");
  print("</tr>");
  print("<tr>");
    print("<td>$descrip ($mes_nombre - $anio)</td>");
    print("<td>&nbsp;</td>");    //$total_raciones
    print("<td>&nbsp;</td>"); //$valor_racion
    print("<td>$subtotal_inc_retefuente</td>");
  print("</tr>");
  print("<tr>");
    print("<td>&nbsp;</td>");
    print("<td>&nbsp;</td>");
    print("<td>&nbsp;</td>");
    print("<td>&nbsp;</td>");
  print("</tr>");
  print("<tr>");
    print("<td>&nbsp;</td>");
    print("<td>Retención</td>");
    print("<td>$retefuente%</td>");
    print("<td>$valor_retefuente</td>");
  print("</tr>");
  print("<tr>");
    print("<td>&nbsp;</td>");
    print("<td>&nbsp;</td>");
    print("<td><strong>NETO A PAGAR </strong></td>");
    print("<td><strong>$total_pagar</strong></td>");
  print("</tr>");
  print("<tr>");
    print("<td>&nbsp;</td>");
    print("<td>Reteiva</td>");
    print("<td>$reteiva%</td>");
    print("<td>$valor_reteiva</td>");
  print("</tr>");  
print("</table>");
print("<table width='98%' border='0'>");
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");
print("</table>");
print("<table width='98%' border='0'>");
  print("<tr>");
    print("<td><strong>CERTIFICO QUE PERTENEZCO AL REGIMEN SIMPLIFICADO Y ACEPTO EL CONTENIDO DEL DOCUMENTO </strong></td>");
  print("</tr>");
  print("<tr>");
    print("<td>&nbsp;</td>");
  print("</tr>");
  print("<tr>");
    print("<td>FIRMA:_________________________________________</td>");
  print("</tr>");
  print("<tr>");
    print("<td>C.C.:</td>");
  print("</tr>");
print("</table>");
  print("</td></tr>");
print("</table>"); 
print("<br>");   
  }
 }else{
   print("<center><strong>NO HAY DOCUMENTOS GENERADOS PARA ESTE PERIODO</strong></center>");
   }
} 

?>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
