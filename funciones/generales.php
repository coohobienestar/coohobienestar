<?php
ini_set('max_execution_time',0);

////FUNCION QUE DA FORMATO A LA FECHA 
function generar_fecha($cod_programacion){
setlocale(LC_TIME, 'spanish');

  ////BUSCAMOS LAS FECHAS DE LA PROGRAMACION
  $instruccion5 ="SELECT fecha_inicial, fecha_final FROM programacion WHERE cod_programacion=$cod_programacion ";
 
  $consulta5 = mysql_query($instruccion5);
  error_consulta($consulta5,$instruccion5);
  $row5 = mysql_fetch_array($consulta5);
  
  $fecha_ini = $row5['fecha_inicial'];  
  $fecha_fin = $row5['fecha_final'];  
  
  ////SACAMOS EL DIA MES Y ANO DE CADA FECHA
  $fecha1 = explode("-",$fecha_ini);
  $anio1 = $fecha1[0]; 
  $mes1  = $fecha1[1];
  $dia1  = $fecha1[2];
  
  $fecha2 = explode("-",$fecha_fin);
  $anio2 = $fecha2[0]; 
  $mes2  = $fecha2[1];
  $dia2  = $fecha2[2];  
  
  ////SACAMOS EL NOMBRE DEL MES
  $nom_mes1 = strftime("%B",mktime(0, 0, 0, $mes1, 1, 2000));
  $nom_mes2 = strftime("%B",mktime(0, 0, 0, $mes2, 1, 2000));
  
  $nom_mes1 = strtoupper($nom_mes1);
  $nom_mes2 = strtoupper($nom_mes2);
  
  ////ARMAMOS LA CADENA EN EL FORMATO QUE NECESITAMOS SEMANA DEL 19 A 23 MARZO DE 2012						
  if($mes1 == $mes2){
     $cad_fecha = "SEMANA DEL $dia1 AL $dia2 DE $nom_mes1 DE $anio1";
    }else{
      $cad_fecha = "SEMANA DEL $dia1 DE $nom_mes1 AL $dia2 DE $nom_mes2 DE $anio1";
     }    
  return $cad_fecha;  
}  

////FUNCION QUE DA FORMATO A LA FECHA CORTA 
function generar_fecha_corta($fecha){
setlocale(LC_TIME, 'spanish');
  
  ////SACAMOS EL DIA MES Y ANO DE LA FECHA
  $fecha1 = explode("-",$fecha);
  $anio1 = $fecha1[0]; 
  $mes1  = $fecha1[1];
  $dia1  = $fecha1[2];
  
  ////SACAMOS EL NOMBRE DEL MES
  $nom_mes1 = strftime("%B",mktime(0, 0, 0, $mes1, 1, 2000));
  
  $nom_mes1 = strtoupper($nom_mes1);
  
  ////ARMAMOS LA CADENA EN EL FORMATO QUE NECESITAMOS SEMANA DEL 19 A 23 MARZO DE 2012						
  $cad_fecha = "$dia1-$nom_mes1-$anio1";
     
  return $cad_fecha;  
}

////FUNCION QUE PERMITE DEFINIR EL TIPO DE VISTA PARA EL USUARIO  
function opcion_vista($informe,$cod_usuario,$conexion){

////BUSCAMOS LA VISTA QUE TIENE EL USUARIO PARA LA OPCION
$instruccion3 = "SELECT usuario_opcion.cod_opcion_vista AS opcion_vista
                 FROM usuario_opcion 
                 INNER JOIN opcion ON opcion.id_opcion = usuario_opcion.id_opcion
                 WHERE opcion.ruta like '%$informe%' AND usuario_opcion.cod_usuario = $cod_usuario;";
$consulta3 = mysql_query ($instruccion3, $conexion);  
$row3 = mysql_fetch_array ($consulta3);

$opcion_vista = $row3['opcion_vista'];  

 return $opcion_vista;
} 

function observacion_programacion($cod_programacion,$cod_tipo_minuta,$informe){

  if($informe == 1){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_lista_entrega, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '0' AND cod_escuela = '0' AND intercambio = '0' AND cod_tipo_minuta = $cod_tipo_minuta
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);

   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_lista_entrega'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_prog = $cad;
     }else{
       $cadena_obs_prog = " ";
      }            
   }

  if($informe == 2){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_control_es, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '0' AND cod_escuela = '0' AND intercambio = '0' AND cod_tipo_minuta = $cod_tipo_minuta
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);
   
   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_control_es'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_prog = $cad;
     }else{
       $cadena_obs_prog = " ";
      }   
  }
  
  return $cadena_obs_prog;   
}
// FUNCION QUE LLAMA LOS INTERCAMBIOS INGRESADOS EN LA PROGRAMACION
function observacion_programacion_intercambios($cod_programacion,$cod_tipo_minuta,$informe){

  if($informe == 1){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_lista_entrega, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '0' AND cod_escuela = '0' AND intercambio = '1' AND cod_tipo_minuta = $cod_tipo_minuta
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);

   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_lista_entrega'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_prog = $cad;
     }else{
       $cadena_obs_prog = " ";
      }            
   }

  if($informe == 2){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_control_es, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '0' AND cod_escuela = '0' AND cod_tipo_minuta = $cod_tipo_minuta
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);
   
   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_control_es'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_prog = $cad;
     }else{
       $cadena_obs_prog = " ";
      }   
  }
  
  return $cadena_obs_prog;   
}


function observacion_municipio($cod_programacion,$cod_municipio,$cod_tipo_minuta,$informe){
  if($informe == 1){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_lista_entrega, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '$cod_municipio' AND cod_escuela = '0' 
                       AND cod_tipo_minuta = '$cod_tipo_minuta'
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);
   
   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_lista_entrega'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_mun = $cad;
     }else{
       $cadena_obs_mun = " ";
      }  
  }

  if($informe == 2){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_control_es, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '$cod_municipio' AND cod_escuela = '0'
                       AND cod_tipo_minuta = '$cod_tipo_minuta'
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);
   
   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_control_es'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_mun = $cad;
     }else{
       $cadena_obs_mun = " ";
      }  
  }
  
  return $cadena_obs_mun;   
}

function observacion_escuela($cod_programacion,$cod_escuela,$cod_tipo_minuta,$informe){
  if($informe == 1){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_lista_entrega, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '0' AND cod_escuela = '$cod_escuela'
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);
   
   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_lista_entrega'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_esc = $cad;
     }else{
       $cadena_obs_esc = " ";
      }   
  }

  if($informe == 2){
   ////BUSCAMOS LAS OBSERVACIONES
   $instruccion_ca ="SELECT observacion_control_es, cod_tipo_minuta 
                     FROM observacion 
                     WHERE cod_programacion = $cod_programacion AND cod_municipio = '0' AND cod_escuela = '$cod_escuela'
                     ";     
   $consulta_ca = mysql_query($instruccion_ca);
   error_consulta($consulta_ca,$instruccion_ca);
   
   $nfilas_ca = mysql_num_rows ($consulta_ca);
   
   if($nfilas_ca > 0){
     for($a=0; $a<$nfilas_ca; $a++){
        $row_ca = mysql_fetch_array($consulta_ca);        

        $obser_prog = $row_ca['observacion_control_es'];
        $tipo_minuta = $row_ca['cod_tipo_minuta'];
        
        $cad = $cad."  ".$obser_prog;      
       }
     } 
   
   if($tipo_minuta == '0' || $tipo_minuta == $cod_tipo_minuta){
      $cadena_obs_esc = $cad;
     }else{
       $cadena_obs_esc = " ";
      } 
  }
  
  return $cadena_obs_esc;   
}

function redondear($cod_ingrediente,$cod_depto){                                                             
     ////BUSCAMOS LAS UNIDADES DE EMPAQUE DEL PRODUCTO PARA DETERMINAR LA MEJOR OPCION
     $sql2 = "SELECT  DISTINCT ingrediente_unidad_entrega.cod_unidad_medida AS cod_unidad, unidad_medida.valor_gr_cc AS valor
              FROM ingrediente_unidad_entrega
              INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = ingrediente_unidad_entrega.cod_unidad_medida
              WHERE ingrediente_unidad_entrega.cod_ingrediente = $cod_ingrediente AND (ingrediente_unidad_entrega.cod_departamento = $cod_depto
                 OR ingrediente_unidad_entrega.cod_departamento = 0) 
              ORDER BY unidad_medida.valor_gr_cc DESC";
      $result2 = mysql_query($sql2);        
      error_consulta($result2,$sql2); 
      $nfilas2 = mysql_num_rows ($result2);

     ////BUSCAMOS SI EL INGREDIENTE SE DEBE REDONDEAR O SE DEJA CON DECIMAL
     $instruccion3 = "SELECT redondear, maneja_inventario FROM ingrediente WHERE cod_ingrediente = $cod_ingrediente";
     $consulta3 = mysql_query ($instruccion3);
     error_consulta($consulta3,$instruccion3);  
     $row3 = mysql_fetch_array ($consulta3);
     
     $redondeo = $row3['redondear'];  
     $maneja_inventario = $row3['maneja_inventario'];        

      $control = 0;    
            
      if($nfilas2 > 0){
       for($i=0; $i<$nfilas2; $i++){          
         $resultado2 = mysql_fetch_array ($result2); 
        
         $cod_unidad = $resultado2['cod_unidad'];
         $valor_cc_gr = $resultado2['valor'];
         
         if($valor_cc_gr > 0){
           $cantidad_unidad = $cantidad_gr_cc / $valor_cc_gr; 
          }else{
            $cantidad_unidad = $cantidad_gr_cc;
            }
          
         if($cantidad_unidad>=1 && $control == 0){
         
            if($redondeo == 1){
              ////REDONDEAMOS LAS CANTIDADES DE LA UNIDAD SIEMPRE HACIA ARRIBA SI EL INGREDIENTE SE REDONDEA
              $cad_cant = explode('.',$cantidad_unidad);
              $entero = $cad_cant[0];
              $decimales = $cad_cant[1]; 
              $decimales = substr($decimales, 0, 1);
              
              $cantidad_unidad = $entero.".".$decimales;
              
              $cantidad_unidad_r = ceil($cantidad_unidad);
             }else{
               ////NO REDONDEAMOS LAS CANTIDADES Y LA DEJAMOS CON UN SOLO DECIMAL SI EL INGREDIENTE NO SE REDONDEA
               $cantidad_unidad_r = round($cantidad_unidad, 1);
               }

            $control = 1;       
         } 
      }
    }
}  

?> 