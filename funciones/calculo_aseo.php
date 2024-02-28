<?php
ini_set('max_execution_time',0);

////FUNCION QUE DA FORMATO A LA FECHA 
function calcular_aseo($conexion,$cod_programacion){
setlocale(LC_TIME, 'spanish');

  ////BUSCAMOS LAS ESCUELAS A PROGRAMAR
  $instruccion1 ="SELECT cod_escuela, cod_tipo_minuta, nombre, cod_centro_acopio_as, cod_municipio, cod_departamento, total_cupos, grupo_tipo_minuta, numero_manipuladoras      
                  FROM 0as_escuela 
                  WHERE cod_programacion
                  ORDER BY grupo_tipo_minuta";
 
  $consulta1 = mysql_query($instruccion1);
  error_consulta($consulta1,$instruccion1);   
  
  $nfilas1 = mysql_num_rows ($consulta1);

  if($nfilas1 > 0){
   for($i=0; $i<$nfilas1; $i++){   
     $row1 = mysql_fetch_array($consulta1);

     $cod_escuela = $row1['cod_escuela'];
     $cod_tipo_minuta = $row1['cod_tipo_minuta'];
     $nombre = $row1['nombre'];
     $cod_centro_acopio_as = $row1['cod_centro_acopio_as'];  
     $cod_municipio = $row1['cod_municipio'];  
     $cod_departamento = $row1['cod_departamento'];
     $total_cupos = $row1['total_cupos'];
     $numero_manipuladoras = $row1['numero_manipuladoras'];
     $grupo_tipo_minuta = $row1['grupo_tipo_minuta'];
     
      ////BUSCAMOS EL PAQUETE QUE SE DEBE PROGRAMAR DE ACUERDO AL TIPO DE MINUTA
      $instruccion2 ="SELECT 0as_paquete_producto.cod_producto AS cod_producto, 0as_paquete_producto.cantidad AS cantidad,
                             0as_paquete_producto.relacion AS relacion, 0as_paquete_producto.manipuladora AS manipuladora,
                             0as_producto.cod_presentacion AS cod_presentacion, 0as_presentacion.nombre AS nom_presentacion  
                      FROM 0as_paquete_producto 
                      INNER JOIN 0as_programacion ON 0as_programacion.cod_paquete = 0as_paquete_producto.cod_paquete
                      INNER JOIN 0as_producto ON 0as_producto.cod_producto = 0as_paquete_producto.cod_producto
                      INNER JOIN 0as_presentacion ON 0as_presentacion.cod_presentacion = 0as_producto.cod_presentacion
                      WHERE 0as_programacion.cod_tipo_minuta = $cod_tipo_minuta AND 0as_programacion.cod_programacion = $cod_programacion";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);   
      
      $nfilas2 = mysql_num_rows ($consulta2);
                                                     
      if($nfilas2 > 0){
       for($i=0; $i<$nfilas2; $i++){   
         $row2 = mysql_fetch_array($consulta2);
    
         $cod_producto = $row2['cod_producto'];
         $cantidad = $row2['cantidad'];
         $relacion = $row2['relacion'];
         $manipuladora = $row2['manipuladora'];
         $cod_presentacion = $row2['cod_presentacion'];
         
         ////CALCULAMOS LA CANTIDAD DE PRODUCTO DE ACUERDO A LA RELACION Y ASI ESTA ASOCIADO AL NUMERO DE MANIPULADORAS
         if($manipuladora != 1){ ///si el producto se calcula en base a cupos
            if($relacion > 0){////si se tiene una relacion de cupos
               ////calculamos la cantidad a pedir
               $cantidad_pedir = round(($total_cupos/$relacion) * $cantidad); 
              
              }else{///si NO se tiene relacion de cupos se asume que el producto se calcula para la totalidad de los alumnos ejemplo Cupos 362 Relacion 0 => Relacion se asume 362 
                ////calculamos la cantidad a pedir
                $relacion = $total_cupos;
                $cantidad_pedir = round(($total_cupos/$relacion) * $cantidad);
                }
           
           }else{ ///si el producto se calcula en base al numero de manipuladoras y no de los cupos
                ////calculamos la cantidad a pedir
                $cantidad_pedir = round($numero_manipuladoras * $cantidad); 
              
              }
          ////INSERTAMOS EL REGISTRO CON EL VALOR CALCULADO
          $instruccion3 = "INSERT INTO 0as_calculo_redondeado_aseo (cod_programacion, cod_escuela, cod_tipo_minuta, cod_centro_acopio_as, cod_municipio, cod_departamento, total_cupos, grupo_tipo_minuta, cantidad, relacion, numero_manipuladoras, manipuladora, cantidad_pedir, cod_presentacion) 
                           VALUES ('$cod_programacion', '$cod_escuela', '$cod_tipo_minuta', '$cod_centro_acopio_as', '$cod_municipio', '$cod_departamento', '$total_cupos', '$grupo_tipo_minuta', '$cantidad', '$relacion', '$numero_manipuladoras', '$manipuladora', '$cantidad_pedir', '$cod_presentacion')";
          $consulta3 = mysql_query($instruccion3);
          error_consulta($consulta3,$instruccion3);
         
        }
       }     
    }
   }  
     
}  


?> 