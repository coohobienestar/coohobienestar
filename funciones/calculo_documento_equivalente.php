<?php
ini_set('max_execution_time',0);

setlocale(LC_TIME, 'spanish');
date_default_timezone_set('America/Bogota');

//// TOMAMOS LA FECHA ACTUAL 
$fecha=date("Y-m-d H:i:s");

function valor_pagar_escuela ($conexion,$anio,$mes){
////BUSCAMOS LAS ESCUELAS Y RACIONES PARA EL AÑO Y EL MES REQUERIDO
  $sql = "SELECT escuela_racion.cod_escuela AS cod_escuela, SUM(escuela_racion.raciones) AS raciones
          FROM escuela_racion  
          WHERE escuela_racion.anio = '$anio' AND escuela_racion.mes='$mes'
          GROUP BY escuela_racion.cod_escuela";
  $result = mysql_query($sql);
  error_consulta($result,$sql); 
  $nfilas = mysql_num_rows ($result);

  if($nfilas > 0){
   for($i=0; $i<$nfilas; $i++){   
     $resultado = mysql_fetch_array ($result);

     $cod_escuela = $resultado['cod_escuela'];
     $total_raciones = $resultado['raciones'];
     
     ////BUSCAMOS EL VALOR DE LA RACION
     $instruccion_val_racion ="SELECT valor FROM parametro WHERE nombre='valor_racion'";
   
     $consulta_val_racion = mysql_query($instruccion_val_racion);
     error_consulta($consulta_val_racion,$instruccion_val_racion);
     $row_val_racion = mysql_fetch_array($consulta_val_racion);
    
     $valor_racion = $row_val_racion['valor'];  
     
     if($cod_escuela == 4154){ $valor_racion = 80;}   
     if($cod_escuela == 4114){ $valor_racion = 75;} 
     if($cod_escuela == 4102){ $valor_racion = 75;} 
     
     $valor_pagar_escuela = $total_raciones * $valor_racion;
     
     ////ACTUALIZAMOS EL VALOR TOTAL A PAGAR  
     $instruccion4 = "UPDATE escuela_racion SET total_pagar_escuela='$valor_pagar_escuela', total_raciones_escuela = '$total_raciones' WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes='$mes'";
     $consulta4 = mysql_query ($instruccion4, $conexion);    
     }
   }  
}

function valor_pagar_manipuladora($conexion,$cod_escuela,$anio,$mes){
 ////BUSCAMOS EL VALOR DE LA RACION
 $instruccion_val_racion ="SELECT valor FROM parametro WHERE nombre='valor_racion'";

 $consulta_val_racion = mysql_query($instruccion_val_racion);
 error_consulta($consulta_val_racion,$instruccion_val_racion);
 $row_val_racion = mysql_fetch_array($consulta_val_racion);

 $valor_racion = $row_val_racion['valor']; 
 
 if($cod_escuela == 4154){ $valor_racion = 80;}  
 if($cod_escuela == 4114){ $valor_racion = 75;} 
 if($cod_escuela == 4102){ $valor_racion = 75;} 

////BUSCAMOS EL TOTAL DE RACIONES DIA A DIA DE LA ESCUELA
  $sql = "SELECT escuela_racion.dia AS dia, escuela_racion.raciones AS raciones
          FROM escuela_racion  
          WHERE escuela_racion.cod_escuela = $cod_escuela AND escuela_racion.anio = '$anio' AND escuela_racion.mes='$mes'";
  $result = mysql_query($sql);
  error_consulta($result,$sql); 
  $nfilas = mysql_num_rows ($result);

  if($nfilas > 0){
   for($i=0; $i<$nfilas; $i++){   
     $resultado = mysql_fetch_array ($result);

     $dia = $resultado['dia'];
     $raciones_dia = $resultado['raciones'];
     
     $total_pagar_dia = $raciones_dia * $valor_racion;
     
     ////BUSCAMOS CUANTAS MANIPULADORAS TRABAJARON EN ESE DIA
     $sql0 = "SELECT DISTINCT escuela_manipuladora_racion.cod_manipuladora AS cod_manipuladora
              FROM escuela_manipuladora_racion  
              WHERE escuela_manipuladora_racion.cod_escuela = $cod_escuela AND escuela_manipuladora_racion.anio = '$anio' 
                AND escuela_manipuladora_racion.mes='$mes' AND escuela_manipuladora_racion.dia = '$dia' AND raciones > 0";
     $result0 = mysql_query($sql0);
     error_consulta($result0,$sql0); 
     $nfilas0 = mysql_num_rows ($result0);
   
     if($nfilas0 > 0){
     
       ////CALCULAMOS EL VALOR CORRESPONDIENTE A CADA MANIPULADORA
       $total_manipuladora = $total_pagar_dia / $nfilas0;     
      
       for($j=0; $j<$nfilas0; $j++){   
          $resultado0 = mysql_fetch_array ($result0);

          $cod_manipuladora = $resultado0['cod_manipuladora'];

           ////ACTUALIZAMOS EL VALOR TOTAL A PAGAR  
           $instruccion4 = "UPDATE escuela_manipuladora_racion SET pago_dia ='$total_manipuladora' WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes' AND dia = '$dia'";
           $consulta4 = mysql_query ($instruccion4, $conexion);             
          
        }  
      }else{
         ////ACTUALIZAMOS EL VALOR TOTAL A PAGAR a cero si no trabajo ninguna manipuladora 
         $instruccion4 = "UPDATE escuela_manipuladora_racion SET pago_dia ='0' WHERE cod_escuela = $cod_escuela AND anio = '$anio' AND mes = '$mes' AND dia = '$dia'";
         $consulta4 = mysql_query ($instruccion4, $conexion);            
        }
      
     } 
    } 
 calcular_mensualidad_manipuladora ($conexion,$cod_escuela,$anio,$mes);         
}


function calcular_mensualidad_manipuladora ($conexion,$cod_escuela,$anio,$mes){
if($cod_escuela != ''){
  $condicion = " WHERE cod_escuela = $cod_escuela";
 }else{
   $condicion = " ";
   }
   
////CALCULAMOS EL VALOR TOTAL MENSUAL A PAGAR A LA MANIPULADORA
///BUSCAMOS LAS MANIPULADORAS
 $sql0 = "SELECT cod_escuela, cod_manipuladora FROM escuela_manipuladora $condicion";
 $result0 = mysql_query($sql0);
 error_consulta($result0,$sql0); 
 $nfilas0 = mysql_num_rows ($result0);

 if($nfilas0 > 0){
   for($j=0; $j<$nfilas0; $j++){   
      $resultado0 = mysql_fetch_array ($result0);
      
      $cod_escuela = $resultado0['cod_escuela'];
      $cod_manipuladora = $resultado0['cod_manipuladora'];
      
       ////SACAMOS EL TOTAL POR CADA MANIPULADORA
       $instruccion_total ="SELECT SUM(pago_dia) AS total_pago 
                            FROM escuela_manipuladora_racion 
                            WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes'";
     
       $consulta_total = mysql_query($instruccion_total);
       error_consulta($consulta_total,$instruccion_total);
       $row_total = mysql_fetch_array($consulta_total);
      
       $total_mensualidad = $row_total['total_pago'];  
             
       ////ACTUALIZAMOS EL VALOR DE LA MENSUALIDAD DE LA MANIPULADORA
       $instruccion4 = "UPDATE escuela_manipuladora_racion SET total_pagar_manipuladora ='$total_mensualidad' 
                         WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes'";
       $consulta4 = mysql_query ($instruccion4, $conexion);        
      
    }  
   }

}

////FUNCION QUE REALIZA EL CALCULO DE LOS VALORES A PAGAR EN EL DOCUMENTO EQUIVALENTE
function documento_equivalente ($conexion,$anio,$mes){

  ////BORRAMOS LOS DOCUMENTOS EQUIVALENTES EXISTENTES SI NO TIENEN CONSECUTIVO ASIGNADO
  $instruccion_del = "DELETE FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
  $consulta_del = mysql_query ($instruccion_del, $conexion);   

  ////BUSCAMOS TODAS LAS MANIPULADORAS PARA CALCULAR EL DOCUMENTOS EQUIVALENTE
  $sql = "SELECT DISTINCT cod_escuela, cod_manipuladora, total_pagar_manipuladora 
          FROM escuela_manipuladora_racion 
          WHERE anio = '$anio' AND mes = '$mes'";
  $result = mysql_query($sql);
  error_consulta($result,$sql); 
  $nfilas = mysql_num_rows ($result);

  if($nfilas > 0){
   for($i=0; $i<$nfilas; $i++){   
     $resultado = mysql_fetch_array ($result);

     $cod_escuela = $resultado['cod_escuela'];
     $cod_manipuladora = $resultado['cod_manipuladora'];
     $total_pagar_manipuladora = $resultado['total_pagar_manipuladora'];
     
     ////BUSCAMOS EL VALOR DE LA base_retefuente
     $instruccion_base_retefuente ="SELECT valor FROM parametro WHERE nombre='base_retefuente'";
   
     $consulta_base_retefuente = mysql_query($instruccion_base_retefuente);
     error_consulta($consulta_base_retefuente,$instruccion_base_retefuente);
     $row_base_retefuente = mysql_fetch_array($consulta_base_retefuente);
    
     $base_retefuente = $row_base_retefuente['valor'];      
     
     ////SI $total_pagar_manipuladora es mayor a $base_retefuente SE DEBE CALCULAR RETENCION EN LA FUENTE Y RETEIVA     
     
     if($total_pagar_manipuladora >= $base_retefuente){ 
       ////BUSCAMOS EL VALOR DE LA RETENCION EN LA FUENTE
       $instruccion_retencion_fuente ="SELECT valor FROM parametro WHERE nombre='retencion_fuente'";
     
       $consulta_retencion_fuente = mysql_query($instruccion_retencion_fuente);
       error_consulta($consulta_retencion_fuente,$instruccion_retencion_fuente);
       $row_retencion_fuente = mysql_fetch_array($consulta_retencion_fuente);
      
       $retencion_fuente = $row_retencion_fuente['valor']; 
 
        ////CALCULAMOS EL SUBTOTAL ASUMIENDO LA RETENCION EN LA FUENTE
        $subtotal_raciones = ($total_pagar_manipuladora * 100) / (100-$retencion_fuente);
        
        ////CALCULAMOS EL VALOR DE LA RETENCION EN LA FUENTE
        $valor_retefuente =  ($subtotal_raciones * $retencion_fuente) / 100;
        
       ////BUSCAMOS EL VALOR DEL RETEIVA
       $instruccion_reteiva ="SELECT valor FROM parametro WHERE nombre='reteiva'";
     
       $consulta_reteiva = mysql_query($instruccion_reteiva);
       error_consulta($consulta_reteiva,$instruccion_reteiva);
       $row_reteiva = mysql_fetch_array($consulta_reteiva);
      
       $reteiva = $row_reteiva['valor'];  
       
       ////BUSCAMOS EL VALOR DEL IVA
       $instruccion_iva ="SELECT valor FROM parametro WHERE nombre='iva'";
     
       $consulta_iva = mysql_query($instruccion_iva);
       error_consulta($consulta_iva,$instruccion_iva);
       $row_iva = mysql_fetch_array($consulta_iva);
      
       $iva = $row_iva['valor'];       
       
       ////CALCULAMOS LA BASE DEL RETEIVA
       $base_reteiva = ($subtotal_raciones * $iva) / 100;
       
       ////CALCULAMOS EL VALOR DEL RETEIVA
       $valor_reteiva = ($base_reteiva * $reteiva) / 100;
       
       ////BUSCAMOS EL TOTAL DE RACIONES SERVIDAS POR LA MANIPULADORA
       $instruccion_total_r ="SELECT SUM(raciones) AS raciones 
                              FROM escuela_manipuladora_racion 
                              WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes'";
     
       $consulta_total_r = mysql_query($instruccion_total_r);
       error_consulta($consulta_total_r,$instruccion_total_r);
       $row_total_r = mysql_fetch_array($consulta_total_r);
      
       $total_raciones = $row_total_r['raciones'];    
       
       ////BUSCAMOS EL VALOR DE LA RACION
       $instruccion_val_racion ="SELECT valor FROM parametro WHERE nombre='valor_racion'";             
    
       $consulta_val_racion = mysql_query($instruccion_val_racion);
       error_consulta($consulta_val_racion,$instruccion_val_racion);
       $row_val_racion = mysql_fetch_array($consulta_val_racion);
      
       $valor_racion = $row_val_racion['valor'];    
       
       /////ojo escuela que gana a 80
       if($cod_escuela == 4154){ $valor_racion = 80;} 
       if($cod_escuela == 4114){ $valor_racion = 75;}     
       if($cod_escuela == 4102){ $valor_racion = 75;}              
       
       ////INSERTAMOS LOS DATOS DEL DOCUMENTO EQUIVALENTE
       $instruccion_ins = "INSERT INTO documento_equivalente (cod_escuela, cod_manipuladora, anio, mes, total_raciones, valor_racion, subtotal, subtotal_inc_retefuente, retefuente, valor_retefuente, reteiva, valor_reteiva) 
                           VALUES ('$cod_escuela', '$cod_manipuladora','$anio', '$mes', '$total_raciones', '$valor_racion', '$total_pagar_manipuladora', '$subtotal_raciones', '$retencion_fuente', '$valor_retefuente', '$reteiva', 
                                   '$valor_reteiva')";
       $consulta_ins = mysql_query ($instruccion_ins, $conexion); 
       
       }else{////SI NO SE SUPERA LA BASE DE LA RETENCION EN LA FUENTE NO SE CALCULA NI RETENCION EN LA FUENTE NI RETEIVA
         ////BUSCAMOS EL TOTAL DE RACIONES SERVIDAS POR LA MANIPULADORA
         $instruccion_total_r ="SELECT SUM(raciones) AS raciones 
                                FROM escuela_manipuladora_racion 
                                WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes'";
       
         $consulta_total_r = mysql_query($instruccion_total_r);
         error_consulta($consulta_total_r,$instruccion_total_r);
         $row_total_r = mysql_fetch_array($consulta_total_r);
        
         $total_raciones = $row_total_r['raciones'];    
         
         ////BUSCAMOS EL VALOR DE LA RACION
         $instruccion_val_racion ="SELECT valor FROM parametro WHERE nombre='valor_racion'"; 
                
         $consulta_val_racion = mysql_query($instruccion_val_racion);
         error_consulta($consulta_val_racion,$instruccion_val_racion);
         $row_val_racion = mysql_fetch_array($consulta_val_racion);
        
         $valor_racion = $row_val_racion['valor'];  
         
         if($cod_escuela == 4154){ $valor_racion = 80;} 
         if($cod_escuela == 4114){ $valor_racion = 75;}    
         if($cod_escuela == 4102){ $valor_racion = 75;}        
                  
         $instruccion_ins = "INSERT INTO documento_equivalente (cod_escuela, cod_manipuladora, anio, mes, total_raciones, valor_racion, subtotal, subtotal_inc_retefuente, retefuente, valor_retefuente, reteiva, valor_reteiva) 
                             VALUES ('$cod_escuela', '$cod_manipuladora','$anio', '$mes', '$total_raciones', '$valor_racion', '$total_pagar_manipuladora', '$total_pagar_manipuladora', '0', '0', '0','0')";
         $consulta_ins = mysql_query ($instruccion_ins, $conexion); 
         }
     
     }
    }  
}

function generar_consecutivo($conexion,$anio,$mes){

////BUSCAMOS LOS DOCUMENTOS EQUIVALENTES GENERADOS  
$sql = "SELECT cod_escuela, cod_manipuladora FROM documento_equivalente WHERE anio = '$anio' AND mes = '$mes'";
$result = mysql_query($sql);
error_consulta($result,$sql); 
$nfilas = mysql_num_rows ($result);

if($nfilas > 0){
 for($i=0; $i<$nfilas; $i++){   
   $resultado = mysql_fetch_array ($result);

   $cod_escuela = $resultado['cod_escuela'];
   $cod_manipuladora = $resultado['cod_manipuladora'];
   
   ////BUSCAMOS EL CONSECUTIVO DEL DOCUMENTO EQUIVALENTE Y LO INCREMENTAMOS EN UNO Y ESE ES EL CONSECUTIVO A USAR
   $instruccion_consecutivo ="SELECT valor FROM parametro WHERE nombre='consecutivo_doc_equival_manipu'";
 
   $consulta_consecutivo = mysql_query($instruccion_consecutivo);
   error_consulta($consulta_consecutivo,$instruccion_consecutivo);
   $row_consecutivo = mysql_fetch_array($consulta_consecutivo);
  
   $consecutivo = $row_consecutivo['valor'];  
   
   $consecutivo = $consecutivo + 1;
   
   $conse_act = $consecutivo;  
   
    if($consecutivo <= 9){
       $consecutivo = "000".$consecutivo;
     }
    if(($consecutivo > 9) && ($consecutivo <= 99)){
       $consecutivo = "00".$consecutivo;
     }     
    if(($consecutivo > 99) && ($consecutivo <= 999)){
       $consecutivo = "0".$consecutivo;
     }  
     
   ////ACTUALIZAMOS EL NUMERO DEL CONSECUTIVO  EN EL DOCUMENTO EQUIVALENTE
   $instruccion4 = "UPDATE documento_equivalente SET num_documento = '$consecutivo' 
                    WHERE cod_escuela = $cod_escuela AND cod_manipuladora = $cod_manipuladora AND anio = '$anio' AND mes = '$mes'";
   $consulta4 = mysql_query ($instruccion4, $conexion); 
   
   ////ACTUALIZAMOS EL NUMERO DEL CONSECUTIVO EN EL PARAMETRO
   $instruccion5 = "UPDATE parametro SET valor = '$conse_act' WHERE nombre='consecutivo_doc_equival_manipu'";
   $consulta5 = mysql_query ($instruccion5, $conexion);     
  }
 }      
}
?>