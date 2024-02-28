<?php 
ini_set('max_execution_time',0);

///ESTA FUNCION SELECCIONA Y CALCULA REQUERIMIENTOS DE LOS INGREDIENTES DE CADA MINUTA
function calcular_ingredientes($conexion,$cod_programacion){

  ////BUSCAMOS LAS MINUTAS QUE SE PROGRAMARON EN LA PROGRAMACION
  $sql = "SELECT minuta.nombre AS nom_minuta, minuta.cod_ciclo AS cod_ciclo, programacion.cod_minuta AS cod_minuta, programacion.fecha_inicial AS fecha_inicial, 
                 programacion.fecha_final AS fecha_final  
          FROM programacion 
          INNER JOIN minuta ON minuta.cod_minuta = programacion.cod_minuta            
          WHERE programacion.cod_programacion = $cod_programacion";
  $result = mysql_query($sql);
  error_consulta($result,$sql); 
  $nfilas = mysql_num_rows ($result);

  if($nfilas > 0){
   for($i=0; $i<$nfilas; $i++){   
     $resultado = mysql_fetch_array ($result);

     $cod_ciclo = $resultado['cod_ciclo'];
     $cod_minuta = $resultado['cod_minuta'];
     $nom_minuta = $resultado['nom_minuta'];
     $fecha_inicial = $resultado['fecha_inicial'];  
     $fecha_final = $resultado['fecha_final'];  
       
     ////BUSCAMOS LAS ESCUELAS PARA LAS QUE APLICAN LAS MINUTAS SELECCIONADAS Y LOS CUPOS QUE HAY
     $sql2 = "SELECT minuta_escuela.cod_escuela AS cod_escuela, minuta_escuela.cupos AS cupos, escuela.cod_municipio AS cod_municipio
              FROM minuta_escuela 
              INNER JOIN escuela ON escuela.cod_escuela = minuta_escuela.cod_escuela
              WHERE minuta_escuela.cod_minuta= $cod_minuta";
     $result2 = mysql_query($sql2);
     error_consulta($result2,$sql2); 
     $nfilas2 = mysql_num_rows ($result2);

     if($nfilas2 > 0){
      for($j=0; $j<$nfilas2; $j++){          
        $resultado2 = mysql_fetch_array ($result2); 

        $cod_escuela = $resultado2['cod_escuela']; 
        $cupos       = $resultado2['cupos']; 
        $c_municipio = $resultado2['cod_municipio']; 
        
        ////INSERTAMOS LOS RESULTADOS PARA LUEGO HACER LOS CALCULOS
        $sql_ins_ip = "INSERT INTO item_programacion (cod_programacion,cod_escuela,cod_municipio,cod_minuta,cupos) VALUES 
                                                     ('$cod_programacion','$cod_escuela','$c_municipio','$cod_minuta','$cupos')";
        $result_ins_ip = mysql_query($sql_ins_ip);
       }
      }else{
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES 
                   ('$cod_programacion','NO SE ENCONTRARON ESCUELAS RELACIONADAS CON LA MINUTA: $cod_minuta - $nom_minuta')", $conexion); 
        } 
        
        
///INICIO EXCLUSIONES ESCUELA *** MUNICIPIO   

     ////BUSCAMOS LOS MUNICIPIOS QUE SE EXLUYERON
     $sql_exc_mun = "SELECT excluido_municipio.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                     FROM  excluido_municipio
                     INNER JOIN municipio ON municipio.cod_municipio = excluido_municipio.cod_municipio";
     $result_exc_mun = mysql_query($sql_exc_mun);
     error_consulta($result_exc_mun,$sql_exc_mun); 
     $nfilas_exc_mun = mysql_num_rows ($result_exc_mun);
     
     if($nfilas_exc_mun > 0){
      for($k=0; $k<$nfilas_exc_mun; $k++){          
        $resultado_exc_mun = mysql_fetch_array ($result_exc_mun); 
        
        $cod_muni_exc = $resultado_exc_mun['cod_municipio'];
        $nom_muni_exc = $resultado_exc_mun['nombre'];
        
        ////ELIMINAMOS LAS EXCLUSIONES DE MUNICIPIOS Y ESCUELAS CONFIGURADAS 
        $instruccion_del1 = "DELETE FROM item_programacion WHERE cod_programacion = $cod_programacion AND cod_municipio = $cod_muni_exc";
        $consulta_del1 = mysql_query ($instruccion_del1, $conexion); 
        
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        $texto_exc_mun = 'SE EXCLUYO EL MUNICIPIO: '.$cod_muni_exc.' - '.$nom_muni_exc;
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES ('$cod_programacion','$texto_exc_mun')", $conexion);              
       }
      }

     ////BUSCAMOS LAS ESCUELAS QUE SE EXLUYERON
     $sql_exc_esc = "SELECT excluido_escuela.cod_escuela AS cod_escuela, escuela.nombre AS nombre
                     FROM excluido_escuela 
                     INNER JOIN escuela ON excluido_escuela.cod_escuela = escuela.cod_escuela";
     $result_exc_esc = mysql_query($sql_exc_esc);
     error_consulta($result_exc_esc,$sql_exc_esc); 
     $nfilas_exc_esc = mysql_num_rows ($result_exc_esc);
     
     if($nfilas_exc_esc > 0){
      for($k=0; $k<$nfilas_exc_esc; $k++){          
        $resultado_exc_esc = mysql_fetch_array ($result_exc_esc); 
        
        $cod_esc_exc = $resultado_exc_esc['cod_escuela'];
        $nom_esc_exc = $resultado_exc_esc['nombre'];
        
        ////ELIMINAMOS LAS EXCLUSIONES DE MUNICIPIOS Y ESCUELAS CONFIGURADAS 
        $instruccion_del2 = "DELETE FROM item_programacion WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_esc_exc";
        $consulta_del2 = mysql_query ($instruccion_del2, $conexion); 
        
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        $texto_exc_esc = 'SE EXCLUYO LA ESCUELA: '.$cod_esc_exc.' - '.$nom_esc_exc;
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES ('$cod_programacion','$texto_exc_esc')", $conexion);              
       }
      }  
////FIN EXCLUSIONES ESCUELA *** MUNICIPIO  
 
     ////BUSCAMOS LOS PLATOS DE CADA MINUTA
     $sql3 = "SELECT DISTINCT plato_ingrediente.cod_menu AS cod_menu, plato_ingrediente.cod_plato AS cod_plato, plato.nombre AS nombre
              FROM  plato_ingrediente
              INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato
              WHERE cod_minuta=$cod_minuta";
     $result3 = mysql_query($sql3);
     error_consulta($result3,$sql3); 
     $nfilas3 = mysql_num_rows ($result3);
     
     if($nfilas3 > 0){
      for($k=0; $k<$nfilas3; $k++){          
        $resultado3 = mysql_fetch_array ($result3); 
        
        $cod_menu = $resultado3['cod_menu']; 
        $cod_plato = $resultado3['cod_plato']; 
        $nom_plato = $resultado3['nombre']; 

        ////BUSCAMOS LOS INGREDIENTES DE CADA PLATO DE LA MINUTA
        $sql4 = "SELECT cod_ingrediente, cantidad FROM plato_ingrediente WHERE cod_minuta=$cod_minuta AND cod_menu=$cod_menu AND cod_plato=$cod_plato";
        $result4 = mysql_query($sql4);
        error_consulta($result3,$sql4); 
        $nfilas4 = mysql_num_rows ($result4);
       
        if($nfilas4 > 0){
         for($l=0; $l<$nfilas4; $l++){          
           $resultado4 = mysql_fetch_array ($result4); 
           
           $cod_ingrediente = $resultado4['cod_ingrediente']; 
           $cantidad = $resultado4['cantidad'];  
        
            ////INSERTAMOS LOS RESULTADOS PARA LUEGO HACER LOS CALCULOS
            mysql_query("INSERT INTO ingrediente_programacion (cod_programacion,cod_minuta,cod_menu,cod_plato,cod_ingrediente,cantidad) VALUES 
                       ('$cod_programacion','$cod_minuta','$cod_menu','$cod_plato','$cod_ingrediente','$cantidad')", $conexion);  
          }                          
         }else{
           ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
           $texto1 = 'NO SE ENCONTRARON INGREDIENTES RELACIONADOS CON EL PLATO: '.$cod_plato.' - '.$nom_plato;
           mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES 
                      ('$cod_programacion','$texto1')", $conexion); 
           }
        
       }  
      }else{
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        $texto2 = 'NO SE ENCONTRARON PLATOS RELACIONADOS CON LA MINUTA: '.$cod_minuta.' - '.$nom_minuta;
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES 
                   ('$cod_programacion','$texto2')", $conexion); 
        }    
   }
  }
 
   ////**********CALCULAMOS LOS INGREDIENTES**********
   
   ////BUSCAMOS LOS ITEMS DE LA PROGRAMACION
   $sql8 = "SELECT cod_escuela, cod_minuta, cupos FROM item_programacion WHERE cod_programacion=$cod_programacion";
   $result8 = mysql_query($sql8);
   error_consulta($result8,$sql8); 
   $nfilas8 = mysql_num_rows ($result8);
   
   if($nfilas8 > 0){
    for($n=0; $n<$nfilas8; $n++){          
      $resultado8 = mysql_fetch_array ($result8); 
      
      $cod_escuela_ip = $resultado8['cod_escuela'];
      $cod_minuta_ip = $resultado8['cod_minuta'];
      $cupos_ip = $resultado8['cupos'];

       ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Departamento, Municipio, Centro de Acopio
       $sql8a = "SELECT municipio.cod_departamento AS departamento, escuela.cod_municipio AS municipio, escuela.cod_centro_acopio AS centro_acopio
                 FROM escuela
                 INNER JOIN centro_acopio ON escuela.cod_centro_acopio = centro_acopio.cod_centro_acopio
                 INNER JOIN municipio ON escuela.cod_municipio = municipio.cod_municipio
                 INNER JOIN departamento ON departamento.cod_departamento = municipio.cod_departamento
                 WHERE escuela.cod_escuela = $cod_escuela_ip";
       $result8a = mysql_query($sql8a);
       error_consulta($result8a,$sql8a); 
       
       $resultado8a = mysql_fetch_array ($result8a); 
      
       $departamento = $resultado8a['departamento'];
       $municipio = $resultado8a['municipio'];
       $centro_acopio = $resultado8a['centro_acopio'];
      
      ////BUSCAMOS LOS INGREDIENTES CORRESPONDIENTES DE LA MINUTA PARA HACER EL CALCULO
      $sql9 = "SELECT cod_menu, cod_plato, cod_ingrediente, cantidad 
               FROM ingrediente_programacion 
               WHERE cod_programacion=$cod_programacion AND cod_minuta=$cod_minuta_ip";
      $result9 = mysql_query($sql9);
      error_consulta($result9,$sql9); 
      $nfilas9 = mysql_num_rows ($result9);
     
      if($nfilas9 > 0){
       for($o=0; $o<$nfilas9; $o++){        
 
         $resultado9 = mysql_fetch_array ($result9); 
        
         $cod_menu_ingpro = $resultado9['cod_menu'];
         $cod_plato_ingpro = $resultado9['cod_plato'];
         $cod_ingrediente_ingpro = $resultado9['cod_ingrediente'];
         $cantidad_ingpro = $resultado9['cantidad'];
         
         $cantidad_requerida = $cantidad_ingpro * $cupos_ip;            

         ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Grupo de Alimento
         $sql9a = "SELECT cod_grupo_alimento FROM plato WHERE cod_plato=$cod_plato_ingpro";
         $result9a = mysql_query($sql9a);
         error_consulta($result9a,$sql9a); 
         
         $resultado9a = mysql_fetch_array ($result9a); 
        
         $cod_grupo_alimento = $resultado9a['cod_grupo_alimento'];

         ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Categoria de Alimento
         $sql9b = "SELECT cod_categoria_ingrediente FROM ingrediente WHERE cod_ingrediente=$cod_ingrediente_ingpro";
         $result9b = mysql_query($sql9b);
         error_consulta($result9b,$sql9b); 
         
         $resultado9b = mysql_fetch_array ($result9b); 
        
         $cod_categoria_ingrediente = $resultado9b['cod_categoria_ingrediente'];
         
         ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Rango de edad
         $sql9c = "SELECT cod_rango_edad FROM minuta_escuela WHERE cod_minuta=$cod_minuta_ip AND cod_escuela=$cod_escuela_ip";
         $result9c = mysql_query($sql9c);
         error_consulta($result9c,$sql9c); 
         
         $resultado9c = mysql_fetch_array ($result9c); 
        
         $cod_rango_edad = $resultado9c['cod_rango_edad'];    

         ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Modalidad y Tipo Minuta
         $sql9d = "SELECT tipo_minuta.cod_modalidad AS cod_modalidad, minuta.cod_tipo_minuta AS cod_tipo_minuta 
                   FROM minuta 
                   INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = minuta.cod_tipo_minuta 
                   WHERE cod_minuta=$cod_minuta_ip";
         $result9d = mysql_query($sql9d);
         error_consulta($result9d,$sql9d); 
         
         $resultado9d = mysql_fetch_array ($result9d); 
        
         $cod_modalidad = $resultado9d['cod_modalidad'];
         $cod_tipo_minuta = $resultado9d['cod_tipo_minuta'];                
         
         ////INSERTAMOS LOS RESULTADOS DE LOS CALCULOS 
         mysql_query("INSERT INTO calculo_requerimientos (cod_programacion,cod_ciclo,cod_minuta,cod_modalidad,cod_tipo_minuta,cod_menu,cod_escuela,cod_rango_edad,cupos,cod_departamento,
                                                          cod_municipio,cod_centro_acopio,cod_plato,cod_ingrediente,cod_grupo_alimento,cod_categoria_ingrediente,
                                                          cantidad)
                                                  VALUES ('$cod_programacion','$cod_ciclo','$cod_minuta_ip','$cod_modalidad','$cod_tipo_minuta','$cod_menu_ingpro','$cod_escuela_ip','$cod_rango_edad',
                                                          '$cupos_ip','$departamento','$municipio','$centro_acopio','$cod_plato_ingpro','$cod_ingrediente_ingpro',
                                                          '$cod_grupo_alimento','$cod_categoria_ingrediente','$cantidad_requerida')", $conexion);                                                       
                                                          
   
        }
      } 
    }
   } 
   
   ////INICIO EXCLUSIONES ESCUELA - MENU *** MUNICIPIO - MENU 
   
     ////BUSCAMOS LOS MENUS EXCLUIDOS POR ESCUELA
     $sql_exc_esc_menu = "SELECT excluido_escuela_menu.cod_escuela AS cod_escuela, escuela.nombre AS nom_escuela,
                                 excluido_escuela_menu.cod_menu AS cod_menu, menu.nombre AS nom_menu,
                                 excluido_escuela_menu.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta
                          FROM excluido_escuela_menu 
                          INNER JOIN escuela ON excluido_escuela_menu.cod_escuela = escuela.cod_escuela
                          INNER JOIN menu ON menu.cod_menu = excluido_escuela_menu.cod_menu
                          LEFT JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = excluido_escuela_menu.cod_tipo_minuta";
     $result_exc_esc_menu = mysql_query($sql_exc_esc_menu);
     error_consulta($result_exc_esc_menu,$sql_exc_esc_menu); 
     $nfilas_exc_esc_menu = mysql_num_rows ($result_exc_esc_menu);
     
     if($nfilas_exc_esc_menu > 0){
      for($k=0; $k<$nfilas_exc_esc_menu; $k++){          
        $resultado_exc_esc_menu = mysql_fetch_array ($result_exc_esc_menu); 
        
        $cod_esc_exc_menu = $resultado_exc_esc_menu['cod_escuela'];
        $nom_esc_exc_menu = $resultado_exc_esc_menu['nom_escuela'];
        $cod_menu_exc_esc = $resultado_exc_esc_menu['cod_menu'];
        $nom_menu_exc_esc = $resultado_exc_esc_menu['nom_menu'];
        $cod_tipo_minuta_exc_esc = $resultado_exc_esc_menu['cod_tipo_minuta'];
        $nom_tipo_minuta_exc_esc = $resultado_exc_esc_menu['nom_tipo_minuta'];
        
        if($cod_tipo_minuta_exc_esc != 0){
           $condi_exc_esc = " AND cod_tipo_minuta = $cod_tipo_minuta_exc_esc";
           $nom_tipo_minuta_exc_esc = $resultado_exc_esc_menu[nom_tipo_minuta];
          }else{
             $condi_exc_esc = " ";
             $nom_tipo_minuta_exc_esc = "Todos los Tipos de Minuta";
            }
        
        ////ELIMINAMOS LAS EXCLUSIONES DE MENUS POR ESCUELAS
        $instruccion_del2 = "DELETE FROM calculo_requerimientos WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_esc_exc_menu 
                                                                  AND cod_menu = $cod_menu_exc_esc $condi_exc_esc";
        $consulta_del2 = mysql_query ($instruccion_del2, $conexion); 
        
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        $texto_exc_esc = 'SE EXCLUYO EL MENU: '.$cod_menu_exc_esc.' - '.$nom_menu_exc_esc.' PARA LA ESCUELA: '.$cod_esc_exc_menu.' - '.$nom_esc_exc_menu.' TIPO DE MINUTA: '.$cod_tipo_minuta_exc_esc.' - '.$nom_tipo_minuta_exc_esc;
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES ('$cod_programacion','$texto_exc_esc')", $conexion);              
       }
      }      

     ////BUSCAMOS LOS MENUS EXCLUIDOS POR MUNICIPIO
     $sql_exc_mun_menu = "SELECT excluido_municipio_menu.cod_municipio AS cod_municipio, municipio.nombre AS nom_municipio,
                                 excluido_municipio_menu.cod_menu AS cod_menu, menu.nombre AS nom_menu
                          FROM menu 
                          INNER JOIN excluido_municipio_menu ON menu.cod_menu = excluido_municipio_menu.cod_menu 
                          INNER JOIN municipio ON municipio.cod_municipio = excluido_municipio_menu.cod_municipio";
     $result_exc_mun_menu = mysql_query($sql_exc_mun_menu);
     error_consulta($result_exc_mun_menu,$sql_exc_mun_menu); 
     $nfilas_exc_mun_menu = mysql_num_rows ($result_exc_mun_menu);
     
     if($nfilas_exc_mun_menu > 0){
      for($k=0; $k<$nfilas_exc_mun_menu; $k++){          
        $resultado_exc_mun_menu = mysql_fetch_array ($result_exc_mun_menu); 
        
        $cod_mun_exc_menu  = $resultado_exc_mun_menu['cod_municipio'];
        $nom_mun_exc_menu  = $resultado_exc_mun_menu['nom_municipio'];
        $cod_menu_exc_mun  = $resultado_exc_mun_menu['cod_menu'];
        $nom_menu_exc_mun  = $resultado_exc_mun_menu['nom_menu'];
        
        ////ELIMINAMOS LAS EXCLUSIONES DE MENUS POR ESCUELAS
        $instruccion_del2 = "DELETE FROM calculo_requerimientos WHERE cod_programacion = $cod_programacion AND cod_municipio = $cod_mun_exc_menu 
                                                                  AND cod_menu = $cod_menu_exc_mun";
        $consulta_del2 = mysql_query ($instruccion_del2, $conexion); 
        
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        $texto_exc_esc = 'SE EXCLUYO EL MENU: '.$cod_menu_exc_mun.' - '.$nom_menu_exc_mun.' PARA EL MUNICIPIO: '.$cod_mun_exc_menu.' - '.$nom_mun_exc_menu;
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES ('$cod_programacion','$texto_exc_esc')", $conexion);              
       }
      } 
      
     ////BUSCAMOS LOS MENUS QUE SE EXLUYERON
     $sql_exc_menu = "SELECT excluido_menu.cod_menu AS cod_menu, menu.nombre AS nombre  
                      FROM  excluido_menu 
                      INNER JOIN menu ON menu.cod_menu = excluido_menu.cod_menu 
                      WHERE cod_programacion = $cod_programacion";
     $result_exc_menu = mysql_query($sql_exc_menu);
     error_consulta($result_exc_menu,$sql_exc_menu); 
     $nfilas_exc_menu = mysql_num_rows ($result_exc_menu);
     
     if($nfilas_exc_menu > 0){
      for($k=0; $k<$nfilas_exc_menu; $k++){          
        $resultado_exc_menu = mysql_fetch_array ($result_exc_menu); 
        
        $cod_menu_exc = $resultado_exc_menu['cod_menu'];
        $nom_menu_exc = $resultado_exc_menu['nombre'];
        
        ////ELIMINAMOS LAS EXCLUSIONES DE MENUS    
        $instruccion_del1 = "DELETE FROM calculo_requerimientos WHERE cod_programacion = $cod_programacion AND cod_menu = $cod_menu_exc";
        $consulta_del1 = mysql_query ($instruccion_del1, $conexion); 
        
        ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
        $texto_exc_mun = 'SE EXCLUYO EL MENU: '.$cod_menu_exc.' - '.$nom_menu_exc;
        mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES ('$cod_programacion','$texto_exc_mun')", $conexion);              
       }
      }           
   ////FIN EXCLUSIONES ESCUELA - MENU *** MUNICIPIO - MENU    
}  

////FUNCION QUE REDONDEA LAS CANTIDADES DE LOS INGREDIENTES
function redondear($conexion,$cod_programacion){
  ////BUSCAMOS TODOS LOS ALIMENTOS CALCULADOS AGRUPADOS POR ESCUELA
  $sql1 = "SELECT cod_departamento, cod_modalidad, cod_tipo_minuta, cod_escuela, cod_ingrediente, SUM(cantidad) AS cantidad
           FROM calculo_requerimientos
           WHERE cod_programacion=$cod_programacion
           GROUP BY cod_escuela, cod_ingrediente, cod_modalidad, cod_tipo_minuta";
  $result1 = mysql_query($sql1);
  error_consulta($result1,$sql1); 
  $nfilas1 = mysql_num_rows ($result1);
  
  if($nfilas1 > 0){
   for($o=0; $o<$nfilas1; $o++){          
     $resultado1 = mysql_fetch_array ($result1); 
    
     $cod_depto = $resultado1['cod_departamento'];
     $cod_modalidad = $resultado1['cod_modalidad'];
     $cod_tipo_minuta = $resultado1['cod_tipo_minuta'];
     $cod_escuela = $resultado1['cod_escuela'];
     $cod_ingrediente = $resultado1['cod_ingrediente'];
     $cantidad_gr_cc = $resultado1['cantidad'];
     
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
     $instruccion3 = "SELECT redondear FROM ingrediente WHERE cod_ingrediente = $cod_ingrediente";
     $consulta3 = mysql_query ($instruccion3);
     error_consulta($consulta3,$instruccion3);  
     $row3 = mysql_fetch_array ($consulta3);
     $redondeo = $row3['redondear'];        

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
      
           ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Departamento, Municipio, Centro de Acopio
           $sql8a = "SELECT municipio.cod_departamento AS departamento, escuela.cod_municipio AS municipio, escuela.cod_centro_acopio AS centro_acopio
                     FROM escuela
                     INNER JOIN centro_acopio ON escuela.cod_centro_acopio = centro_acopio.cod_centro_acopio
                     INNER JOIN municipio ON escuela.cod_municipio = municipio.cod_municipio
                     INNER JOIN departamento ON departamento.cod_departamento = municipio.cod_departamento
                     WHERE escuela.cod_escuela = $cod_escuela";
           $result8a = mysql_query($sql8a);
           error_consulta($result8a,$sql8a); 
           
           $resultado8a = mysql_fetch_array ($result8a); 
          
           $departamento = $resultado8a['departamento'];
           $municipio = $resultado8a['municipio'];
           $centro_acopio = $resultado8a['centro_acopio'];
           
           ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Categoria de Alimento
           $sql9b = "SELECT cod_categoria_ingrediente FROM ingrediente WHERE cod_ingrediente=$cod_ingrediente";
           $result9b = mysql_query($sql9b);
           error_consulta($result9b,$sql9b); 
           
           $resultado9b = mysql_fetch_array ($result9b); 
          
           $cod_categoria_ingrediente = $resultado9b['cod_categoria_ingrediente'];            
           
            ////INSERTAMOS EN LA TABLA LOS VALORES REDONDEADOS
            mysql_query("INSERT INTO calculo_redondeado_escuela(cod_programacion, cod_escuela, cod_departamento, cod_municipio, cod_centro_acopio, cod_modalidad, cod_tipo_minuta, cod_categoria_ingrediente, cod_ingrediente, cod_unidad_medida_ai, cantidad_gr_cc, cantida_bruta, cantidad_redondeada_ai, redondear) 
                VALUES ('$cod_programacion','$cod_escuela','$departamento','$municipio','$centro_acopio','$cod_modalidad','$cod_tipo_minuta','$cod_categoria_ingrediente','$cod_ingrediente','$cod_unidad','$cantidad_gr_cc','$cantidad_unidad','$cantidad_unidad_r','$redondeo')", $conexion);
          }       
        }
          if($cantidad_unidad<1){
          ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Departamento, Municipio, Centro de Acopio
           $sql8a = "SELECT municipio.cod_departamento AS departamento, escuela.cod_municipio AS municipio, escuela.cod_centro_acopio AS centro_acopio
                     FROM escuela
                     INNER JOIN centro_acopio ON escuela.cod_centro_acopio = centro_acopio.cod_centro_acopio
                     INNER JOIN municipio ON escuela.cod_municipio = municipio.cod_municipio
                     INNER JOIN departamento ON departamento.cod_departamento = municipio.cod_departamento
                     WHERE escuela.cod_escuela = $cod_escuela";
           $result8a = mysql_query($sql8a);
           error_consulta($result8a,$sql8a); 
           
           $resultado8a = mysql_fetch_array ($result8a); 
          
           $departamento = $resultado8a['departamento'];
           $municipio = $resultado8a['municipio'];
           $centro_acopio = $resultado8a['centro_acopio'];
           
           ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Categoria de Alimento
           $sql9b = "SELECT cod_categoria_ingrediente FROM ingrediente WHERE cod_ingrediente=$cod_ingrediente";
           $result9b = mysql_query($sql9b);
           error_consulta($result9b,$sql9b); 
           
           $resultado9b = mysql_fetch_array ($result9b); 
          
           $cod_categoria_ingrediente = $resultado9b['cod_categoria_ingrediente'];                    
           
            ////Si no se alcanza a redondedar a ninguna unidad de medida se debe redondear a 1 [UNO] en cantidad, de la minima unidad de medida posible 
            ////Si el ingrediente se redondea [1],
            ////Si No se redondea si se al almacena en GR/CC  y Unidad de medida 0 GR/CC                         
            if($redondeo == 1){
             $cantidad_unidad_r = 1;
             $cantidad_bruta = $cantidad_unidad;
             $cod_unidad = $cod_unidad;
             }
            if($redondeo == 0){
               $cantidad_unidad_r = round($cantidad_unidad, 1);
               $cantidad_bruta = $cantidad_unidad_r;
               $cod_unidad = $cod_unidad; 
               
               if($cantidad_unidad_r < '0.1'){////Si la cantidad es menor a 0.1 kilo,  se pone  la cantidad en 0.1 litro Se pone las cantidades en GR/CC para mas facil manejo
                   $cantidad_unidad_r = '0.1';
                   $cantidad_bruta = $cantidad_gr_cc;
                   $cod_unidad = $cod_unidad;
                 }              
             }                              
            
            mysql_query("INSERT INTO calculo_redondeado_escuela(cod_programacion, cod_escuela, cod_departamento, cod_municipio, cod_centro_acopio, cod_modalidad, cod_tipo_minuta, cod_categoria_ingrediente, cod_ingrediente, cod_unidad_medida_ai, cantidad_gr_cc, cantida_bruta, cantidad_redondeada_ai, redondear) 
                VALUES ('$cod_programacion','$cod_escuela','$departamento','$municipio','$centro_acopio','$cod_modalidad','$cod_tipo_minuta','$cod_categoria_ingrediente','$cod_ingrediente','$cod_unidad','$cantidad_gr_cc','$cantidad_unidad_r','$cantidad_unidad_r','$redondeo')", $conexion);          
           }  
           
       }else{ 
           ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Departamento, Municipio, Centro de Acopio
           $sql8a = "SELECT municipio.cod_departamento AS departamento, escuela.cod_municipio AS municipio, escuela.cod_centro_acopio AS centro_acopio
                     FROM escuela
                     INNER JOIN centro_acopio ON escuela.cod_centro_acopio = centro_acopio.cod_centro_acopio
                     INNER JOIN municipio ON escuela.cod_municipio = municipio.cod_municipio
                     INNER JOIN departamento ON departamento.cod_departamento = municipio.cod_departamento
                     WHERE escuela.cod_escuela = $cod_escuela";
           $result8a = mysql_query($sql8a);
           error_consulta($result8a,$sql8a); 
           
           $resultado8a = mysql_fetch_array ($result8a); 
          
           $departamento = $resultado8a['departamento'];
           $municipio = $resultado8a['municipio'];
           $centro_acopio = $resultado8a['centro_acopio'];
           
           ////BUSCAMOS LOS DATOS QUE NOS FALTAN PARA INSERTAR -- Categoria de Alimento
           $sql9b = "SELECT cod_categoria_ingrediente, nombre FROM ingrediente WHERE cod_ingrediente=$cod_ingrediente";
           $result9b = mysql_query($sql9b);
           error_consulta($result9b,$sql9b); 
           
           $resultado9b = mysql_fetch_array ($result9b); 
          
           $cod_categoria_ingrediente = $resultado9b['cod_categoria_ingrediente'];
           $nom_categoria_ingrediente = $resultado9b['nombre'];    
           
           ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
           $texto2 = 'NO SE ENCONTRARON UNIDADES DE MEDIDA CONFIGURADAS PARA EL INGREDIENTE: '.$cod_ingrediente;
           mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES 
                       ('$cod_programacion','$texto2')", $conexion);               
       
           ////si no estan configuradas las unidades de medida del ingrediente se debe insertar el valor en cc gr
           mysql_query("INSERT INTO calculo_redondeado_escuela(cod_programacion, cod_escuela, cod_departamento, cod_municipio, cod_centro_acopio, cod_modalidad, cod_tipo_minuta, cod_categoria_ingrediente, cod_ingrediente, cod_unidad_medida_ai, cantidad_gr_cc, cantida_bruta, cantidad_redondeada_ai, redondear) 
                  VALUES ('$cod_programacion','$cod_escuela','$departamento','$municipio','$centro_acopio','$cod_modalidad','$cod_tipo_minuta','$cod_categoria_ingrediente','$cod_ingrediente','0','$cantidad_gr_cc','$cantidad_gr_cc','$cantidad_gr_cc','0')", $conexion);          
         }    
    }
   }
}
                                                                                                               
////***********************************************************************************************************
////FUNCION QUE REALIZA EL CALCULO DE LOS INVENTARIOS
function inventario($conexion,$cod_programacion,$cod_programacion_inv){

////SI HAY PROGRAMACION DE INVENTARIOS HACEMOS EL PROCESO 
if($cod_programacion_inv  == ''){ 
   $cod_programacion_inv = 100000; ////SE PONE EN CERO PARA Q LA PRIMERA PROGRAMACION CALCULE EL INVENTARIO SIN TENER Q SELECCIONAR UNA PROGRAMACION
  }

if($cod_programacion_inv != ''){  
  
  ////BUSCAMOS EL PARAMETRO DE LA CANTIDAD MINIMA DE INVENTARIO QUE SE DEBE CONSERVAR
  $instruccion_minimo ="SELECT valor FROM parametro WHERE nombre='cantidad_minima_inventario'";
 
  $consulta_minimo = mysql_query($instruccion_minimo);
  error_consulta($consulta_minimo,$instruccion_minimo);
  $row_minimo = mysql_fetch_array($consulta_minimo);
  
  $minimo_inventario = $row_minimo['valor'];   

  ////BUSCAMOS LOS INGREDIENTES 
  $sql = "SELECT calculo_redondeado_escuela.cod_escuela AS cod_escuela, calculo_redondeado_escuela.cod_ingrediente AS cod_ingrediente, 
                 calculo_redondeado_escuela.cod_unidad_medida_ai AS cod_unidad_medida, calculo_redondeado_escuela.cod_tipo_minuta AS cod_tipo_minuta, 
                 ingrediente.maneja_inventario AS maneja_inventario, calculo_redondeado_escuela.cantidad_redondeada_ai AS cantidad_redondeada, 
                 unidad_medida.valor_gr_cc AS valor_gr_cc, calculo_redondeado_escuela.cod_departamento AS cod_departamento,
                 calculo_redondeado_escuela.cantidad_gr_cc AS cantidad_requerimiento 
          FROM calculo_redondeado_escuela 
          INNER JOIN ingrediente ON ingrediente.cod_ingrediente = calculo_redondeado_escuela.cod_ingrediente 
          INNER JOIN unidad_medida ON unidad_medida.cod_unidad_medida = calculo_redondeado_escuela.cod_unidad_medida_ai           
          WHERE calculo_redondeado_escuela.cod_programacion = $cod_programacion";
          
  $result = mysql_query($sql);
  error_consulta($result,$sql); 
  $nfilas = mysql_num_rows ($result);
  
  if($nfilas > 0){
   for($i=0; $i<$nfilas; $i++){   
     $resultado = mysql_fetch_array ($result);
     
     $cod_escuela = $resultado['cod_escuela'];
     $cod_ingrediente = $resultado['cod_ingrediente'];
     $cod_unidad_medida = $resultado['cod_unidad_medida'];
     $cod_tipo_minuta = $resultado['cod_tipo_minuta'];
     $maneja_inventario = $resultado['maneja_inventario'];
     $cantidad_redondeada = $resultado['cantidad_redondeada'];
     $valor_cc_gr = $resultado['valor_gr_cc'];
     $cod_depto = $resultado['cod_departamento'];
     $cantidad_requerimiento = $resultado['cantidad_requerimiento'];
     
     $cantidad_redondeada_gr_cc = $cantidad_redondeada * $valor_cc_gr;
     
     ////SI MANEJA INVENTARIO HACEMOS LOS SIGUIENTES CALCULOS
     if($maneja_inventario == 1){

       ////BUSCAMOS EL INVENTARIO FINAL DE LA PROGRAMACION ANTERIOR 
       ////SIN LA UNIDAD DE MEDIDA PARA QUE TRAIGA TODO EL INVENTARIO DEL INGREDIENTE SIN IMPORTAR LA PRESENTACION
       $sql9c = "SELECT q_inventario_final_gr_cc 
                 FROM calculo_redondeado_escuela 
                 WHERE cod_programacion = $cod_programacion_inv AND cod_escuela = $cod_escuela AND cod_ingrediente = $cod_ingrediente 
                   AND cod_tipo_minuta = $cod_tipo_minuta";
       $result9c = mysql_query($sql9c);
       error_consulta($result9c,$sql9c); 
       
       $resultado9c = mysql_fetch_array ($result9c); 
      
       $inv_inicial = $resultado9c['q_inventario_final_gr_cc'];
       
       if($inv_inicial == '') $inv_inicial = 0;
       
       ////CALCULAMOS LA CANTIDAD A COMPRAR EN GR - CC
       if($cantidad_requerimiento > $inv_inicial){
          $cantidad_gr_cc = $cantidad_requerimiento - $inv_inicial;
         }else{
           $cantidad_gr_cc = 0;
           }   
      
      ////SI LA CANTIDAD A COMPRAR $cantidad_gr_cc ES MAYOR QUE CERO ES PK EL INVENTARIO NO ES SUFICIENTE PARA CUBRIR LA CANTIDAD RQUERIDA   
      if($cantidad_gr_cc > 0){
       ////REDONDEAMOS NUEVAMENTE LA CANTIDAD A COMPRAR
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
         $instruccion3 = "SELECT redondear FROM ingrediente WHERE cod_ingrediente = $cod_ingrediente";
         $consulta3 = mysql_query ($instruccion3);
         error_consulta($consulta3,$instruccion3);  
         $row3 = mysql_fetch_array ($consulta3);
         $redondeo = $row3['redondear'];        
    
          $control = 0;    
                
          if($nfilas2 > 0){
           for($x=0; $x<$nfilas2; $x++){           
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
                
                 ////CALCULAMOS LA CANTIDAD REDONDEADA EN GR/CC
                 $q_final_gr_cc = $cantidad_unidad_r * $valor_cc_gr;
                 
                 ////CALCULAMOS LA CANTIDAD DEL INVENTARIO FINAL EN GR/CC
                 
                 ////ojo mirar qe el inventario debe ser en el ejemplo del arroz (500 + 125) - 375 es cantidad enviar + inv inicial - requeimiento real 
                 $q_inventario_final_gr_cc = ($q_final_gr_cc + $inv_inicial) - $cantidad_requerimiento; 
                 
                 if($q_inventario_final_gr_cc < $minimo_inventario){
                    $q_inventario_final_gr_cc = 0;
                   }                         
                
                 ////ACTUALIZAMOS EN LA TABLA LOS VALORES REDONDEADOS
                 $instruccion4 = "UPDATE calculo_redondeado_escuela SET cod_programacion_inventario = '$cod_programacion_inv', maneja_inventario = '$maneja_inventario', cantidad_redondeada_gr_cc = '$cantidad_redondeada_gr_cc',
                                         q_inventario_inicial_gr_cc = '$inv_inicial', q_inventario_final_gr_cc = '$q_inventario_final_gr_cc', q_final_gr_cc = '$q_final_gr_cc', 
                                         cod_unidad_medida = '$cod_unidad', cantidad_redondeada = '$cantidad_unidad_r'    
                                   WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela AND cod_ingrediente = $cod_ingrediente 
                                     AND cod_unidad_medida_ai = $cod_unidad_medida AND cod_tipo_minuta = $cod_tipo_minuta";
                 $consulta4 = mysql_query ($instruccion4, $conexion);  
                
              }       
            }
              if($cantidad_unidad<1){
                ////Si no se alcanza a redondedar a ninguna unidad de medida se debe redondear a 1 [UNO] en cantidad, de la minima unidad de medida posible 
                ////Si el ingrediente se redondea [1],
                ////Si No se redondea si se al almacena en GR/CC  y Unidad de medida 0 GR/CC                         
                if($redondeo == 1){
                 $cantidad_unidad_r = 1;
                 $cantidad_bruta = $cantidad_unidad;
                 $cod_unidad = $cod_unidad;
                 }
                if($redondeo == 0){
                   $cantidad_unidad_r = round($cantidad_unidad, 1);
                   $cantidad_bruta = $cantidad_unidad_r;
                   $cod_unidad = $cod_unidad; 
                   
                   if($cantidad_unidad_r < '0.1'){////Si la cantidad es menor a 0.1 kilo,  se pone  la cantidad en 0.1 litro Se pone las cantidades en GR/CC para mas facil manejo
                       $cantidad_unidad_r = '0.1';
                       $cantidad_bruta = $cantidad_gr_cc;
                       $cod_unidad = $cod_unidad;
                     }              
                 }      
                 
                 ////CALCULAMOS LA CANTIDAD REDONDEADA EN GR/CC
                 $q_final_gr_cc = $cantidad_unidad_r * $valor_cc_gr;
                 
                 ////CALCULAMOS LA CANTIDAD DEL INVENTARIO FINAL EN GR/CC
                 //$q_inventario_final_gr_cc = $q_final_gr_cc - $cantidad_requerimiento; 
                 
                 ////ojo mirar qe el inventario debe ser en el ejemplo del arroz (500 + 125) - 375 es cantidad enviar + inv inicial - requeimiento real 
                 $q_inventario_final_gr_cc = ($q_final_gr_cc + $inv_inicial) - $cantidad_requerimiento; 
                 
                 if($q_inventario_final_gr_cc < $minimo_inventario){
                    $q_inventario_final_gr_cc = 0;
                   }                                   
                
                 ////ACTUALIZAMOS EN LA TABLA LOS VALORES REDONDEADOS
                 $instruccion4 = "UPDATE calculo_redondeado_escuela SET cod_programacion_inventario = '$cod_programacion_inv', maneja_inventario = '$maneja_inventario', cantidad_redondeada_gr_cc = '$cantidad_redondeada_gr_cc',
                                         q_inventario_inicial_gr_cc = '$inv_inicial', q_inventario_final_gr_cc = '$q_inventario_final_gr_cc', q_final_gr_cc = '$q_final_gr_cc', 
                                         cod_unidad_medida = '$cod_unidad', cantidad_redondeada = '$cantidad_unidad_r'    
                                   WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela AND cod_ingrediente = $cod_ingrediente 
                                     AND cod_unidad_medida_ai = $cod_unidad_medida AND cod_tipo_minuta = $cod_tipo_minuta";
                 $consulta4 = mysql_query ($instruccion4, $conexion); 

               }  
               
           }else{ 
             ////INSERTAMOS LA OBSERVACION DE LA PROGRAMACION
             $texto2 = 'NO SE ENCONTRARON UNIDADES DE MEDIDA CONFIGURADAS PARA EL INGREDIENTE: '.$cod_ingrediente;
             mysql_query("INSERT INTO programacion_observacion (cod_programacion,observacion) VALUES ('$cod_programacion','$texto2')", $conexion);               
                           
             ////INSERTAMOS EN LA TABLA LOS VALORES REDONDEADOS             
    
             }  
         }else{////SI EL INVENTARIO ES MAYOR QUE LA CANTIDAD REQUERIDA ACTUALIZAMOS LOS DATOS ASI
           $q_inventario_final_gr_cc = $inv_inicial - $cantidad_requerimiento; 

           if($q_inventario_final_gr_cc < $minimo_inventario){
              $q_inventario_final_gr_cc = 0;
             }                       
           
           $instruccion4 = "UPDATE calculo_redondeado_escuela SET cod_programacion_inventario = '$cod_programacion_inv', maneja_inventario = '$maneja_inventario', cantidad_redondeada_gr_cc = '$cantidad_redondeada_gr_cc',
                                   q_inventario_inicial_gr_cc = '$inv_inicial', q_inventario_final_gr_cc = '$q_inventario_final_gr_cc', q_final_gr_cc = '0', 
                                   cod_unidad_medida = '$cod_unidad_medida', cantidad_redondeada = '0'    
                             WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela AND cod_ingrediente = $cod_ingrediente 
                               AND cod_unidad_medida_ai = $cod_unidad_medida AND cod_tipo_minuta = $cod_tipo_minuta";
           $consulta4 = mysql_query ($instruccion4, $conexion);           
           }           
           
       }else{////NO MANEJA INVENTARIOS ENTONCES ACTUALIZAMOS EL REGISTRO
         $instruccion4 = "UPDATE calculo_redondeado_escuela SET cod_programacion_inventario = '$cod_programacion_inv', maneja_inventario = '$maneja_inventario', cantidad_redondeada_gr_cc = '$cantidad_redondeada_gr_cc',
                                 q_inventario_inicial_gr_cc = '0', q_inventario_final_gr_cc = '0', q_final_gr_cc = '0', 
                                 cod_unidad_medida = '$cod_unidad_medida', cantidad_redondeada = '$cantidad_redondeada'    
                           WHERE cod_programacion = $cod_programacion AND cod_escuela = $cod_escuela AND cod_ingrediente = $cod_ingrediente 
                             AND cod_unidad_medida_ai = $cod_unidad_medida AND cod_tipo_minuta = $cod_tipo_minuta";
         $consulta4 = mysql_query ($instruccion4, $conexion);     
         }
     
    } 
   } 
 }else{
   ////SI NO HABIA PROGRAMACION DE INVENTARIOS SE ACTUALIZA SOLO LA CANTIDAD REDONDEADA FINAL = CANTIDAD REDONDEADA
   $instruccion4 = "UPDATE calculo_redondeado_escuela SET cantidad_redondeada = cantidad_redondeada_ai, cod_unidad_medida = cod_unidad_medida_ai
                    WHERE cod_programacion = $cod_programacion";
   $consulta4 = mysql_query ($instruccion4, $conexion);    
   }
}
////***********************************************************************************************************

?> 
