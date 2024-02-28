<?php
session_start();

include("../conexion/conectarbd.php"); ////CONEXION A LA BD
$conexion=Conectarse(); 

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  
$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag'];                    

$codigo= $_GET['codigo'];
$tipo_operacion = $_GET['tipo_operacion'];

$nom_form = " ";



if($tipo_operacion == 3){
   $nom_operacion = "ELIMINAR MUNICIPIOS ";
   $icono = "borrar.png";

 }   
 
if($tipo_operacion == 4){
   $nom_operacion = "ELIMINAR TIPOS DE MINUTAS ";
   $icono = "borrar.png";

 }    

if($tipo_operacion == 5){
   $nom_operacion = "ELIMINAR DUPLICADOS POR CODIGO ";
   $icono = "borrar.png";

 }
 
if($tipo_operacion == 6){
   $nom_operacion = "ASIGNAR CENTRO DE ACOPIO A MUNICIPIOS ";
   $icono = "repetir.png";

 } 
 
if($tipo_operacion == 7){
   $nom_operacion = "ASIGNAR GRUPOS A TIPOS DE MINUTAS ";
   $icono = "agregar_ing.png";

 } 
 
if($tipo_operacion == 8){
   $nom_operacion = "CALCULAR NUMERO DE MANIPULADORAS ";
   $icono = "duplicar_obs.png";

 }  
 
if($tipo_operacion == 9){
   $nom_operacion = "ELIMINAR DUPLICADOS POR NOMBRE Y MUNICIPIO ";
   $icono = "limpiar_exc.png";

 }  

?>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<html>
<head>
<title><?php print("$nom_operacion $nom_form");?></title>
</head>
<p style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></p>
<body>
<form action="vr_org_cupos_aseo.php" method="post">
<input type='hidden' name='tipo_operacion' value='<?php print("$tipo_operacion");?>'>
<input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>
 <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style='font-weight:bold; color: black; background-color:#f4d359' align="center" width="100%" colspan="5" ><img width='24' height='24' src="../imagenes/<?php print("$icono");?>">&nbsp;<strong><?php print("$nom_operacion $nom_form");?></strong></td>
  </tr>
  <tr>
    <td colspan='5'>&nbsp;</td>
  </tr>  
<?php

////ELIMINAR MUNICIPIOS
if($tipo_operacion == 3){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione las Municipios que desea eliminar</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS OPCIONES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT DISTINCT 0as_escuela.cod_municipio AS cod_municipio, municipio.nombre AS nombre  
                     FROM 0as_escuela 
                     INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                     ORDER BY municipio.nombre ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_municipio = $row6['cod_municipio'];
        $nom_municipio = strtoupper($row6['nombre']);

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Municipio</td>");
          print("</tr>");           
          }
        $conta = $conta+1;        
        
          ////DEFINIMOS EL COLOR DE LA FILA
          $resto = $conta%2;
          
          if($resto==0){
             $color = '#D8D8D8';
            }
          if($resto!=0){
             $color = '#848484';
            }  
                  
         print("<tr>");    

          print("<td style=background:$color><input type='checkbox' $cad name='$cod_municipio' value=".$cod_municipio.">&nbsp[$cod_municipio]&nbsp;&nbsp;-&nbsp;&nbsp;".$nom_municipio."</td>"); 
          
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }
     
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Eliminar'></td>");
     print("</tr>");     
}

////ELIMINAR TIPOS DE MINUTAS
if($tipo_operacion == 4){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Seleccione los tipos de minutas que desea eliminar</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS OPCIONES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT DISTINCT 0as_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre  
                     FROM 0as_escuela 
                     INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta
                     ORDER BY tipo_minuta.nombre ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_tipo_minuta = $row6['cod_tipo_minuta'];
        $nom_tipo_minuta = strtoupper($row6['nombre']);

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Municipio</td>");
          print("</tr>");           
          }
        $conta = $conta+1;        
        
          ////DEFINIMOS EL COLOR DE LA FILA
          $resto = $conta%2;
          
          if($resto==0){
             $color = '#D8D8D8';
            }
          if($resto!=0){
             $color = '#848484';
            }  
                  
         print("<tr>");    

          print("<td style=background:$color><input type='checkbox' $cad name='$cod_tipo_minuta' value=".$cod_tipo_minuta.">&nbsp[$cod_tipo_minuta]&nbsp;&nbsp;-&nbsp;&nbsp;".$nom_tipo_minuta."</td>"); 
          
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }
     
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Eliminar'></td>");
     print("</tr>");     
}

////ELIMINAR ESCUELAS DUPLICADAS - POR CODIGO
if($tipo_operacion == 5){
      ////DEBEMOS VERIFICAR QUE TODAS LOS TIMOS DE MINUTA Y ESCUELA TENGAN ASIGNADO UN GRUPO
      $instruccion0 ="SELECT * FROM 0as_escuela WHERE grupo_tipo_minuta IS NULL";
     
      $consulta0 = mysql_query($instruccion0);
      error_consulta($consulta0,$instruccion0);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas_c = mysql_num_rows ($consulta0);
      if ($nfilas_c <= 0){
         
      ////BUSCAMOS Y ORDENAMOS LAS ESCUELAS POR CODIGO PARA SABER CUALES ESTAN DUPLICADAS
      $instruccion2 ="SELECT 0as_escuela.cod_escuela AS cod_escuela, 0as_escuela.nombre AS nom_escuela, municipio.nombre AS nom_municipio, 
                             0as_escuela.cod_municipio AS cod_municipio, 
                             tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             centro_acopio.nombre AS nom_centro_acopio, 0as_escuela.cod_centro_acopio AS cod_centro_acopio,
                             0as_escuela.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, 
                             0as_escuela.total_cupos AS total_cupos, 0as_escuela.grupo_tipo_minuta AS grupo_tipo_minuta 
                      FROM 0as_escuela 
                      INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = 0as_escuela.cod_centro_acopio
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta 
                      INNER JOIN departamento ON departamento.cod_departamento = 0as_escuela.cod_departamento
                      ORDER BY 0as_escuela.grupo_tipo_minuta, 0as_escuela.cod_escuela, 0as_escuela.total_cupos DESC     
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='98%'>";
      $hojaExcel.="<TR><TH colspan='9'><center>ELIMINAR ESCUELAS REPETIDAS</center></TH></TR>";       
      $hojaExcel.="<TH colspan='2'><center>Escuela</center></TH>";
      $hojaExcel.="<TH><center>Departamento</center></TH>";
      $hojaExcel.="<TH><center>Municipio </center></TH>";
      $hojaExcel.="<TH><center>Centro Acopio</center></TH>";
      $hojaExcel.="<TH><center>Total Cupos</center></TH>";
      $hojaExcel.="<TH><center>Tipo Minuta </center></TH>";
      $hojaExcel.="<TH><center>Tipo Minuta </center></TH>";
      $hojaExcel.="</TR>";

      $color = '';

         for ($i=0; $i<$nfilas; $i++){
              $row2 = mysql_fetch_array($consulta2);
            ////DEFINIMOS EL COLOR DE LA FILA
            $resto = $i%2;
            
            if($resto==0){
               $color = '#D8D8D8';
              }
            if($resto!=0){
               $color = '#848484';
              }   

            $cod_escuela = $row2[cod_escuela];  

            if($cod_escuela == $esc_ant){
               $color = '#FE2E2E';
              }  
 

            ////ESCRIBIMOS LOS RESULTADOS 
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $cod_escuela . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_departamento'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['total_cupos'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['grupo_tipo_minuta'] . "</TD>";
            $hojaExcel.="</TR>";
            
            $esc_ant = $cod_escuela;
       
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
        
        }
        

     print("<table width='98%' align='center'>");
     print("<tr>");
     print("<td align='center' width='100%' height='34'>&nbsp;</td>");
     print("</tr>");
     print("<tr>");
     print("<td align='center' width='100%'  height='34'><input type='submit' value='Eliminar Escuelas Duplicadas'></td>");
     print("</tr>");
     print("</table>");
   }else{
      print ("<tr><td colspan='5' style='font-weight:bold; color: white' align='center'>Todos los tipos de minuta deben tener un grupo Asignado. Por favor Verifique.</td></tr>");
     }  
  
  }

if($tipo_operacion == 6){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Relacione el centro de acopio correspondiente a cada municipio</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LAS OPCIONES QUE HAY EN EL SISTEMA
    $instruccion6 = "SELECT DISTINCT municipio.cod_municipio AS cod_municipio, municipio.nombre AS nombre
                     FROM 0as_escuela
                     INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio 
                     ORDER BY municipio.nombre ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_municipio = $row6['cod_municipio'];
        $nombre = trim($row6['nombre']);

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Municipio</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Centro de Acopio Aseo</td>");
          print("</tr>");           
          }
        $conta = $conta+1;
        
        ////BUSCAMOS SI EL MUNICIPIO YA TIENE EL CENTRO DE ACOPIO RELACIONADO
        $instruccion6a = "SELECT cod_centro_acopio FROM 0as_municipio_centro_acopio WHERE cod_municipio = $cod_municipio";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $cod_centro_acopio_as = $row6a['cod_centro_acopio'];  
        
          ////DEFINIMOS EL COLOR DE LA FILA
          $resto = $conta%2;
          
          if($resto==0){
             $color = '#D8D8D8';
            }
          if($resto!=0){
             $color = '#848484';
            }  
                  
         print("<tr>");    

          print("<td style=background:$color>&nbsp[$cod_municipio]&nbsp;&nbsp;-&nbsp;&nbsp;".$nombre."</td>"); 
          
          ////BUSCAMOS LOS CENTROS DE ACOPIO 
          print ("<TD style=background:$color><SELECT NAME='centro_$cod_municipio'>"); 
          
          $instruccion_m = "SELECT cod_centro_acopio, nombre FROM 0as_centro_acopio ORDER BY nombre";
          $consulta_m = mysql_query ($instruccion_m, $conexion);
    
          $row_m = mysql_fetch_array ($consulta_m); 
            
          $valdesc_m = "";
          $descp_m = "--";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");  
            do{ 
            
              if($row_m['cod_centro_acopio'] == $cod_centro_acopio_as){
                print("<option value=".$row_m['cod_centro_acopio']." Selected>".$row_m['nombre']."</option>");
                
                }else{
                  print("<option value=".$row_m['cod_centro_acopio'].">".$row_m['nombre']."</option>");
                  }
            }while ($row_m = mysql_fetch_array($consulta_m)); 
            print("</SELECT></TD>");
                       
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }
     
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
     print("</tr>");   

 } 
 
if($tipo_operacion == 7){
    print ("<tr><td colspan='5' style='font-weight:bold; color: white'>Asigne el grupo correspondiente a cada tipo de Minuta</td></tr>");
    print ("<tr><td colspan='5'>&nbsp;</td></tr>");

    ////BUSCAMOS LOS TIPOS DE MINUTA
    $instruccion6 = "SELECT DISTINCT 0as_escuela.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nombre  
                     FROM 0as_escuela 
                     INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta
                     ORDER BY tipo_minuta.nombre ";
                     
    $consulta6 = mysql_query($instruccion6);
    error_consulta($consulta6,$instruccion6);
    $row6 = mysql_fetch_array($consulta6);
    $nfilas = mysql_num_rows ($consulta6);
     
     $conta = 1;
     
     if($nfilas>0){
      do{           
        $cod_tipo_minuta = $row6['cod_tipo_minuta'];
        $nombre = trim($row6['nombre']);

        if($conta == 1){
          print("<tr>"); 
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Tipo de minuta</td>");
          print("<td style='font-weight:bold; font-size: 10pt; color: white' align='center'>Grupo</td>");
          print("</tr>");           
          }
        $conta = $conta+1;  
        
        ////BUSCAMOS SI EL TIPO DE MINUTA YA TIENE UN GRUPO ASOCIADO
        $instruccion6a = "SELECT DISTINCT grupo_tipo_minuta FROM 0as_escuela WHERE cod_tipo_minuta = $cod_tipo_minuta";
                     
        $consulta6a = mysql_query($instruccion6a);
        error_consulta($consulta6a,$instruccion6a);
        $row6a = mysql_fetch_array($consulta6a);
          
        $cuenta = mysql_num_rows ($consulta6a); 
        $grupo_tipo_minuta = $row6a['grupo_tipo_minuta'];    
        
         if($cuenta > 0){
           $cad =" Selected ";
          }else{
            $cad ="";
            }                    
       
          ////DEFINIMOS EL COLOR DE LA FILA
          $resto = $conta%2;
          
          if($resto==0){
             $color = '#D8D8D8';
            }
          if($resto!=0){
             $color = '#848484';
            }  
                  
         print("<tr>");    

          print("<td style=background:$color>&nbsp[$cod_tipo_minuta]&nbsp;&nbsp;-&nbsp;&nbsp;".$nombre."</td>"); 
          
          ////BUSCAMOS LOS CENTROS DE ACOPIO 
          print ("<TD style=background:$color><SELECT NAME='grupo_$cod_tipo_minuta'>"); 
          
          $valdesc_m = "";
          $descp_m = "--";
          
              print("<option value=".$valdesc_m.">".$descp_m."</option>");              
              
              if($grupo_tipo_minuta == 1){
                 print("<option value='1' Selected>1</option>");
                }else{
                    print("<option value='1'>1</option>");
                   }
                   
              if($grupo_tipo_minuta == 2){
                 print("<option value='2' Selected>2</option>");
                }else{
                    print("<option value='2'>2</option>");
                   }  
                   
              if($grupo_tipo_minuta == 3){
                 print("<option value='3' Selected>3</option>");
                }else{
                    print("<option value='3'>3</option>");
                   }  
                   
              if($grupo_tipo_minuta == 4){
                 print("<option value='4' Selected>4</option>");
                }else{
                    print("<option value='4'>4</option>");
                   }  
                   
              if($grupo_tipo_minuta == 5){
                 print("<option value='5' Selected>5</option>");
                }else{
                    print("<option value='5'>5</option>");
                   }   
                   
              if($grupo_tipo_minuta == 6){
                 print("<option value='6' Selected>6</option>");
                }else{
                    print("<option value='6'>6</option>");
                   }  
                   
              if($grupo_tipo_minuta == 7){
                 print("<option value='7' Selected>7</option>");
                }else{
                    print("<option value='7'>7</option>");
                   }  
                   
              if($grupo_tipo_minuta == 8){
                 print("<option value='8' Selected>8</option>");
                }else{
                    print("<option value='8'>8</option>");
                   }    
                   
              if($grupo_tipo_minuta == 9){
                 print("<option value='9' Selected>9</option>");
                }else{
                    print("<option value='9'>9</option>");
                   }                   

           print("</SELECT></TD>");
                       
         print("</tr>"); 
        
      }while ($row6 = mysql_fetch_array($consulta6));  
     }

     print("<tr>");
     print("<td colspan='2'>&nbsp;</td>");
     print("</tr>");
     print("<tr>");
     print("<td colspan='2' style=background:red><strong>OJO: Para Risaralda Almuerzos tiene que ser Grupo 1 e Industrializados Grupo 2</strong></td>");
     print("</tr>"); 
          
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Registrar'></td>");
     print("</tr>");    
} 

if($tipo_operacion == 8){
     
     print("<tr>");
     print("<td align='center' width='100%' colspan='6' height='34'><input type='submit' value='Calcular Manipuladoras'></td>");
     print("</tr>");    
} 

////ELIMINAR ESCUELAS DUPLICADAS - POR NOMBRE Y MUNICIPIO
if($tipo_operacion == 9){
      ////DEBEMOS VERIFICAR QUE TODAS LOS TIMOS DE MINUTA Y ESCUELA TENGAN ASIGNADO UN GRUPO
      $instruccion0 ="SELECT * FROM 0as_escuela WHERE grupo_tipo_minuta IS NULL";
     
      $consulta0 = mysql_query($instruccion0);
      error_consulta($consulta0,$instruccion0);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas_c = mysql_num_rows ($consulta0);
      if ($nfilas_c <= 0){
         
      ////BUSCAMOS Y ORDENAMOS LAS ESCUELAS POR CODIGO PARA SABER CUALES ESTAN DUPLICADAS
      $instruccion2 ="SELECT 0as_escuela.cod_escuela AS cod_escuela, 0as_escuela.nombre AS nom_escuela, municipio.nombre AS nom_municipio, 
                             0as_escuela.cod_municipio AS cod_municipio, 
                             tipo_minuta.cod_tipo_minuta AS cod_tipo_minuta, tipo_minuta.nombre AS nom_tipo_minuta,
                             centro_acopio.nombre AS nom_centro_acopio, 0as_escuela.cod_centro_acopio AS cod_centro_acopio,
                             0as_escuela.cod_departamento AS cod_departamento, departamento.nombre AS nom_departamento, 
                             0as_escuela.total_cupos AS total_cupos, 0as_escuela.grupo_tipo_minuta AS grupo_tipo_minuta 
                      FROM 0as_escuela 
                      INNER JOIN municipio ON municipio.cod_municipio = 0as_escuela.cod_municipio
                      INNER JOIN centro_acopio ON centro_acopio.cod_centro_acopio = 0as_escuela.cod_centro_acopio
                      INNER JOIN tipo_minuta ON tipo_minuta.cod_tipo_minuta = 0as_escuela.cod_tipo_minuta 
                      INNER JOIN departamento ON departamento.cod_departamento = 0as_escuela.cod_departamento
                      ORDER BY 0as_escuela.cod_municipio, 0as_escuela.nombre, 0as_escuela.grupo_tipo_minuta ASC     
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='98%'>";
      $hojaExcel.="<TR><TH colspan='9'><center>ELIMINAR ESCUELAS REPETIDAS</center></TH></TR>";       
      $hojaExcel.="<TH colspan='2'><center>Escuela</center></TH>";
      $hojaExcel.="<TH><center>Departamento</center></TH>";
      $hojaExcel.="<TH><center>Municipio </center></TH>";
      $hojaExcel.="<TH><center>Centro Acopio</center></TH>";
      $hojaExcel.="<TH><center>Total Cupos</center></TH>";
      $hojaExcel.="<TH><center>Tipo Minuta </center></TH>";
      $hojaExcel.="<TH><center>Grupo </center></TH>";
      $hojaExcel.="</TR>";

      $color = '';

         for ($i=0; $i<$nfilas; $i++){
              $row2 = mysql_fetch_array($consulta2);
            ////DEFINIMOS EL COLOR DE LA FILA
            $resto = $i%2;
            
            if($resto==0){
               $color = '#D8D8D8';
              }
            if($resto!=0){
               $color = '#848484';
              }   

            $cod_escuela = $row2[cod_escuela]; 
            $nom_escuela = $row2[nom_escuela]; 
            $nom_municipio = $row2[nom_municipio];  

            if(($nom_escuela == $nom_esc_ant) && ($nom_municipio == $nom_mun_ant)){
               $color = '#FE2E2E';
              }  
 

            ////ESCRIBIMOS LOS RESULTADOS 
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $cod_escuela . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_escuela'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_departamento'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_municipio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_centro_acopio'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['total_cupos'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_minuta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['grupo_tipo_minuta'] . "</TD>";
            $hojaExcel.="</TR>";
            
            $nom_esc_ant = $nom_escuela;
            $nom_mun_ant = $nom_municipio;
       
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
        
        }
        

     print("<table width='98%' align='center'>");
     print("<tr>");
     print("<td align='center' width='100%' height='34'>&nbsp;</td>");
     print("</tr>");
     print("<tr>");
     print("<td align='center' width='100%'  height='34'><input type='submit' value='Eliminar Escuelas Duplicadas'></td>");
     print("</tr>");
     print("</table>");
   }else{
      print ("<tr><td colspan='5' style='font-weight:bold; color: white' align='center'>Todos los tipos de minuta deben tener un grupo Asignado. Por favor Verifique.</td></tr>");
     }  
  
  }  


?>       

</table>  
</form>
</body>
</html>

<?php
// Cerrar conexión
mysql_close ($conexion);   
?>
