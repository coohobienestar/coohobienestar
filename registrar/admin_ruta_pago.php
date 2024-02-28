<?php                
session_start();

include("../conexion/conectarbd.php");
$conexion=Conectarse();

include("../funciones/generales.php");

if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");

////SACAMOS EL NOMBRE DEL INFORME
$url = $_SERVER['REQUEST_URI'];
$array = explode('/',$url);
//print("$array[3]"); ////EL NIVEL 3 ES EL NOMBRE DEL INFORME PARA VALIDAR QUE EL USUARIO TENGA ACCESO A ESE INFORME
$num_array = count($array);
$num_array = $num_array - 1;
 
$informe = $array[$num_array];
$separar = explode('?',$informe);
$informe = $separar[0];

$login = $_SESSION['login'];
$cod_usuario = $_SESSION['cod_usuario'];
$nom_usuario = $_SESSION['nombre'];
$ape_usuario = $_SESSION['apellidos'];
$num_reg_pag = $_SESSION['num_reg_pag'];

 ////LLAMAMOS LA FUNCION QUE DEFINE EL PERFIL DE VISTA DE LA OPCION
 $opcion_vista = opcion_vista($informe,$cod_usuario,$conexion);
   
$registro = "SELECT opcion.ruta FROM usuario_opcion
             INNER JOIN usuario ON usuario.cod_usuario=usuario_opcion.cod_usuario
             INNER JOIN opcion ON opcion.id_opcion=usuario_opcion.id_opcion
             WHERE usuario.cod_usuario=$cod_usuario AND opcion.ruta like '%$informe%'";
$result = mysql_query($registro);
error_consulta($result,$registro);                           
    
    if($reg=mysql_fetch_array($result)){
       $autorizado=1;
      }
  if(($cod_usuario != '') && ($autorizado==1)){
    }else{
      die("<br>No ha iniciado una sesión O no puede acceder a esta pagina por su perfil.");
      } 

 $fecha = date("Ymd_His");
?>
<HTML LANG="es">

<HEAD>
<TITLE>RUTAS</TITLE>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<SCRIPT SRC="../calendar/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE='JavaScript'>
<!--
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function operar_tabla(codigo,tipo_operacion){ 
    var url="../admin/operacion_ruta.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=900,height=620,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }
    
    function operar_tabla_sel(codigo,tipo_operacion){ 
    var url="../admin/operacion_sel_ruta.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion;
    open(url,"Sizewindow","width=900,height=620,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }   
    
   ////FUNCION PARA HACER OPERACIONES SOBRE LAS TABLAS [REGISTRAR (1) - EDITAR (2)]
    function subir_doc(codigo,tipo_operacion,vista,usuario){ 
    var url="../funciones/upload_docs.php?codigo="+codigo+"&tipo_operacion="+tipo_operacion+"&vista="+vista+"&usuario="+usuario;
    open(url,"Sizewindow","width=650,height=300,top=50,left=50,scrollbars=yes,toolbar=no,directories=no,location=no") 
    }         
   
   ////FUNCION QUE ACTUALIZA LA PAGINA AL CAMBIAR LOS PARAMETROS DE LA CONSULTA
   function actualizaPagina (){
      codigo = document.forms.datechooser.codigo.value;

      i = document.forms.datechooser.origen.selectedIndex;
      origen = document.forms.datechooser.origen.options[i].value;
      
      l = document.forms.datechooser.destino.selectedIndex;
      destino = document.forms.datechooser.destino.options[l].value;
      
      k = document.forms.datechooser.pagina.selectedIndex;
      pagina = document.forms.datechooser.pagina.options[k].value;

      fecha = document.forms.datechooser.fecha_ini.value;
      fecha2 = document.forms.datechooser.fecha_fin.value;  
      
      placa = document.forms.datechooser.placa.value;    
            
      window.location = 'admin_ruta_pago.php?origen='+origen+'&codigo='+codigo+'&nombre='+nombre+'&pagina='+pagina+'&destino='+destino+'&fecha='+fecha+'&fecha2='+fecha2+'&placa='+placa;
   }
// -->
</SCRIPT>

</head>
<body>
<table width='90%'>
<tr>
<td width='30%' style='font-weight:bold; color: white' align="left">Bienvenido&nbsp;&nbsp;&nbsp;<img src="../imagenes/usuario.png">&nbsp;&nbsp;&nbsp;<?php print("$nom_usuario $ape_usuario");?></td>
<?php if($opcion_vista == 6){ ///se cambia a 6 para que no muestre la opcion de registrar viaje opcion normal (1)?>
 <td width='15%' style='font-weight:bold; color: #f4d359' align="center"><a href=javascript:operar_tabla_sel(0,0)><img src='../imagenes/guardar.png' width='32' height='32' border='0' alt='Nuevo Registro'>&nbsp;&nbsp;Registrar Viaje</a></td>
<?php } ?>
<td width='15%' style='font-weight:bold; color: #f4d359' align="center"><a href='#' title='Actualizar'><img src='../imagenes/actualizar.png' border='0' width='32' height='32' border='0' alt='' onClick='window.location.reload(true);'></a></td>
<td width='30%' style='font-weight:bold; color: white' align="right"><a href="../menu_retorna.php" align="right"><img src="../imagenes/retornar.png">&nbsp;Retornar</a> | <a href="../logout.php"><img src="../imagenes/exit.png">&nbsp;Cerrar sesión</a></td>
</tr>
</table>
<br>
<div align="Center">
<?php                        
      ////RECIBIMOS LOS PARAMETROS Q VIENEN EN LA URL
      $codigo = $_REQUEST['codigo'];
      $origen = $_REQUEST['origen'];
      $destino = $_REQUEST['destino'];
      $fecha1 = $_REQUEST['fecha_ini'];
      $fecha2 = $_REQUEST['fecha_fin'];
      $placa = $_REQUEST['placa'];      
      $pagina = $_REQUEST['pagina'];
      
       if($pagina>0){
          $pagina = $pagina * ($num_reg_pag) - $num_reg_pag;
         }else{
           $pagina = 0;
           }

      ////DETERMINAMOS EL NUMERO DE PAGINAS QUE SE DEBEN MOSTRAR
      $instruccion3 = "SELECT count(cod_ruta) AS cuenta FROM fl_ruta WHERE cod_usuario = $cod_usuario";
      $consulta3 = mysql_query ($instruccion3, $conexion);  
      $row3 = mysql_fetch_array ($consulta3);
      $cuenta = $row3['cuenta'];  
      
      if($cuenta>$num_reg_pag){
         $num_paginas = $cuenta / $num_reg_pag;
         $num_paginas = $num_paginas +1;
        }else{
           $num_paginas = 0;
          } 
          
      ////MOSTRAMOS EL FORMULARIO DONDE SE UBICAN LOS FILTROS
      print ("<TABLE width='80%' align='center'>");
      print ("<FORM NAME='datechooser' ACTION='admin_ruta_pago.php' METHOD='POST'>");
      print ("<TR style='font-weight:bold; color: white'>");
      print ("<TD>Código ");
      print ("<INPUT type='text' name='codigo' size='3' value=''></TD>");

      ////BUSCAMOS LAS RUTAS
      print ("<TD>Origen ");
      print ("<SELECT NAME='origen'>");                

      $instruccion = "SELECT cod_origen, nombre FROM fl_origen ORDER BY nombre";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta); 
        
      $valdesc = "";
      $descp = "--";
          print("<option value=".$valdesc.">".$descp."</option>");  
        do{ 
           print("<option value=".$row['cod_origen'].">[".$row['cod_origen']."] - ".$row['nombre']."</option>");
        }while ($row = mysql_fetch_array($consulta)); 
        print("</SELECT></TD>");  
        
      print ("<td align='left' style='font-weight:bold; color: white'>Fecha Inicial&nbsp;&nbsp;");
      print ("<img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' size='8' name='fecha_ini' onfocus='doShow(\"datechooser1\",\"datechooser\",\"fecha_ini\")'><div enabled='false' id='datechooser1'></div></td>");
            
      print ("<td align='left' style='font-weight:bold; color: white'>Fecha Final&nbsp;&nbsp;");
      print ("<img width='18' height='18' src='../imagenes/calendar.png'><INPUT type='text' size='8' name='fecha_fin' onfocus='doShow(\"datechooser1\",\"datechooser\",\"fecha_fin\")'><div enabled='false' id='datechooser1'></div></td>");
       
      print ("<TD>Placa ");
      print ("<INPUT type='text' name='placa' size='6' value=''></TD>");               
               
      ////MOSTRAMOS LAS PAGINAS
      if($num_paginas>0){
        print ("<TD>Pagina ");
        print ("<SELECT NAME='pagina'>"); 
        
        $cont_pag = 1;
         
        $valdesc_p = 0;
        $descp_p = "--";
            print("<option value=".$valdesc_p.">".$descp_p."</option>");  
          do{ 
             print("<option value=".$cont_pag.">".$cont_pag."</option>");
             $cont_pag++;
          }while ($cont_pag<=$num_paginas); 
          print("</SELECT></TD>");   
        }
        
      print ("<TD><INPUT TYPE='submit' NAME='consultar' VALUE='Consultar'></TD>");  
      print ("</FORM>");
      print ("</TD></TR><tr><td>&nbsp;</td></tr></table>");
     
      ////GENERAMOS LA CONDICION DE LA CONSULTA
      if ($cod_usuario == 61 && $placa != ''){      ////DEJO QUE ALEXIS (61) VEA VIAJES DE GERMAN(45) CUANDO CONSULTA POR PLACA
           $condicion = " WHERE fl_ruta.cod_usuario = $cod_usuario OR fl_ruta.cod_usuario = 45 AND ";
        }else{
           $condicion = " WHERE fl_ruta.cod_usuario = $cod_usuario AND ";
          }         
      
      if($codigo != ''){
         $condicion2 = $condicion2. "fl_ruta.cod_ruta like '%$codigo%' AND ";
        }
      if($origen != ''){
         $condicion2 = $condicion2. "fl_ruta.cod_origen = '$origen' AND ";
        } 
      if($fecha1 != ''){
         $condicion2 = $condicion2. "fl_ruta.fecha_ruta >= '$fecha1' AND ";
        } 
      if($fecha2 != ''){
         $condicion2 = $condicion2. "fl_ruta.fecha_ruta <= '$fecha2' AND ";
        }         
      if($placa != ''){
         $condicion2 = $condicion2. "fl_ruta.placa = '$placa' AND ";
        }                  
        
        $condicion2 = substr($condicion2, 0, -4);
        
        if($condicion2 == '' ){
           $condicion = substr($condicion, 0, -4);
           }
        
        $condicion_final = $condicion.$condicion2;              
        
        if($condicion_final == " WHERE "){
           $condicion_final = "";
           $limit = "LIMIT ".$pagina.",".$num_reg_pag; 
          }else{
            $limit = "";
            } 
           
      ////EJECUTAMOS LA CONSULTA
      $instruccion2 ="SELECT fl_ruta.cod_ruta AS cod_ruta, fl_ruta.cod_origen AS cod_origen, fl_origen.nombre AS nom_origen,
                             fl_ruta.fecha_ruta AS fecha_ruta, fl_ruta.fecha_sistema AS fecha_sistema, fl_ruta.contenido AS contenido,
                             fl_ruta.cedula_conductor AS cedula_conductor, fl_ruta.conductor AS conductor, fl_ruta.placa AS placa, fl_ruta.flete AS flete, 
                             fl_ruta.tipo_vehiculo AS nom_tipo_vehiculo, fl_ruta.cod_propiedad AS cod_propiedad, fl_ruta.telefono_conductor AS telefono_conductor,
                             fl_ruta.anticipo AS anticipo, fl_ruta.saldo AS saldo, fl_ruta.programa AS programa, fl_ruta.pagado AS pagado,
                             fl_ruta.cuenta_cobro AS cuenta_cobro, fl_ruta.usuario_subio_cc AS usuario_subio_cc, fl_ruta.egreso AS egreso, 
                             fl_ruta.usuario_subio_egreso AS usuario_subio_egreso    
                      FROM fl_ruta
                      INNER JOIN fl_origen ON fl_origen.cod_origen = fl_ruta.cod_origen 
                      $condicion_final 
                      ORDER BY fl_ruta.cod_ruta DESC
                      $limit  
                      ";
     
      $consulta2 = mysql_query($instruccion2);
      error_consulta($consulta2,$instruccion2);
      
     // echo"<br>SQL: ".$instruccion2;
      
      ////MOSTRAMOS LOS RESULTADOS DE LA CONSULTA
      $nfilas = mysql_num_rows ($consulta2);
      if ($nfilas > 0){
      
      ////ENCABEZADO DE LA TABLA DE RESULTADOS
      $hojaExcel="<TABLE width='99%'>";
      $hojaExcel.="<TR><TH colspan='18'><center>RUTAS</center></TH></TR>";       
      $hojaExcel.="<TH><center>Código</center></TH>";
      $hojaExcel.="<TH><center>Recorrido</center></TH>";
      //$hojaExcel.="<TH colspan='2'><center>Destino</center></TH>";
      //$hojaExcel.="<TH><center>Dirección</center></TH>";
      //$hojaExcel.="<TH><center>Descripcion</center></TH>";
      $hojaExcel.="<TH><center>Fecha Ruta</center></TH>";
      $hojaExcel.="<TH><center>Contenido</center></TH>";
      $hojaExcel.="<TH><center>Conductor</center></TH>";
      $hojaExcel.="<TH><center>Telefono</center></TH>";
      $hojaExcel.="<TH><center>Placa</center></TH>";
      $hojaExcel.="<TH><center>Tipo Vehiculo</center></TH>";
      $hojaExcel.="<TH><center>Propiedad</center></TH>";
      $hojaExcel.="<TH><center>Programa</center></TH>";
      $hojaExcel.="<TH><center>Valor Flete</center></TH>";
      $hojaExcel.="<TH><center>Anticipo</center></TH>";
      $hojaExcel.="<TH><center>Saldo</center></TH>";
    if($opcion_vista == 1){        
      $hojaExcel.="<TH colspan='2'><center>Cuenta Cobro</center></TH>";
      $hojaExcel.="<TH colspan='2'><center>Egreso</center></TH>";
      $hojaExcel.="<TH><center>Pagado</center></TH>";
     }
      $hojaExcel.="</TR>";

      $color = '';

         for ($i=0; $i<$nfilas; $i++){
            ////DEFINIMOS EL COLOR DE LA FILA
            $resto = $i%2;
            
            if($resto==0){
               $color = '#D8D8D8';
              }
            if($resto!=0){
               $color = '#848484';
              }              

            ////ESCRIBIMOS LOS RESULTADOS
            $row2 = mysql_fetch_array($consulta2);

            $pagado = $row2['pagado'];
            
            if($pagado == 1) $color = 'red';    
            
            $propio = $row2['cod_propiedad'];
            
            if($propio == 1){
               $cad_propiedad = "Propio";
              }else{
                $cad_propiedad = "Contratado";
                }        
            
            $hojaExcel.="<TR>";
            $hojaExcel.="<TD style=background:$color>" . $row2['cod_ruta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_origen'] . "</TD>";
            //$hojaExcel.="<TD style=background:$color>" . $row2['otro_origen'] . "</TD>";
            //$hojaExcel.="<TD style=background:$color>" . $row2['nom_destino'] . "</TD>";
            //$hojaExcel.="<TD style=background:$color>" . $row2['otro_destino'] . "</TD>";
           // $hojaExcel.="<TD style=background:$color>" . $row2['destino_direccion'] . "</TD>";
            //$hojaExcel.="<TD style=background:$color>" . $row2['descripcion'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['fecha_ruta'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['contenido'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['conductor'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['telefono_conductor'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['placa'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['nom_tipo_vehiculo'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $cad_propiedad . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . $row2['programa'] . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . number_format($row2['flete']) . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . number_format($row2['anticipo']) . "</TD>";
            $hojaExcel.="<TD style=background:$color>" . number_format($row2['saldo']) . "</TD>"; 
            
          if($opcion_vista == 1 && $pagado == 0){   
            ////cuenta de cobro
            if($row2[cuenta_cobro] != ''){
               $hojaExcel.="<TD style=background:$color><center> <a href='../documento_subido/transporte/cuentacobro/".$row2[cuenta_cobro]."' target='_blank'><img src='../imagenes/ver_detalle.png' width='24' height='24' alt='Visualizar Cuenta de Cobro'></a>"; 
               }else{
                 $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
                 }
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:subir_doc($row2[cod_ruta],10,1,$cod_usuario) title='Subir Cuenta de cobro'><img src='../imagenes/imagen.png' width='18' height='18' border='0' alt='Editar'></a> </center></TD>";                 
            ///egreso
            if($row2[egreso] != ''){
               $hojaExcel.="<TD style=background:$color><center> <a href='../documento_subido/transporte/egreso/".$row2[egreso]."' target='_blank'><img src='../imagenes/ver_detalle.png' width='24' height='24' alt='Visualizar Comprobante de Egreso'></a>"; 
               }else{
                 $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
                 }
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:subir_doc($row2[cod_ruta],11,1,$cod_usuario) title='Subir Comprobante de Egreso'><img src='../imagenes/imagen.png' width='18' height='18' border='0' alt='Editar'></a> </center></TD>";                 
             
             $hojaExcel.="<TD style=background:$color><center> <a href=javascript:operar_tabla($row2[cod_ruta],3) title='Marcar como Pagado el Flete'><img src='../imagenes/pago.png' width='18' height='18' border='0' alt=''></a> </center></TD>"; 
            }else{
            ////cuenta de cobro
            if($row2[cuenta_cobro] != ''){
               $hojaExcel.="<TD style=background:$color><center> <a href='../documento_subido/transporte/cuentacobro/".$row2[cuenta_cobro]."' target='_blank'><img src='../imagenes/ver_detalle.png' width='24' height='24' alt='Visualizar Cuenta de Cobro'></a>"; 
               }else{
                 $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
                 }
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:subir_doc($row2[cod_ruta],10,1,$cod_usuario) title='Subir Cuenta de cobro'><img src='../imagenes/imagen.png' width='18' height='18' border='0' alt='Editar'></a> </center></TD>";                 
            ///egreso
            if($row2[egreso] != ''){
               $hojaExcel.="<TD style=background:$color><center> <a href='../documento_subido/transporte/egreso/".$row2[egreso]."' target='_blank'><img src='../imagenes/ver_detalle.png' width='24' height='24' alt='Visualizar Comprobante de Egreso'></a>"; 
               }else{
                 $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
                 }
            $hojaExcel.="<TD style=background:$color><center> <a href=javascript:subir_doc($row2[cod_ruta],11,1,$cod_usuario) title='Subir Comprobante de Egreso'><img src='../imagenes/imagen.png' width='18' height='18' border='0' alt='Editar'></a> </center></TD>";                 
                         
            
               $hojaExcel.="<TD style=background:$color><center> -- </center></TD>";
              } 
            $hojaExcel.="</TR>";
         }
         $hojaExcel.="</TABLE>";
         echo $hojaExcel;
          
          $login=trim($_SESSION['login']);
          $sfile="../excel/Rutas"._."$login"._."$fecha.xls"; //ruta del archivo a generar 
          $fp=fopen($sfile,"w"); 
          fwrite($fp,$hojaExcel); 
          fclose($fp);
          echo "<br><a href='../excel/".$sfile."'><img src='../imagenes/excel.png' width='36' height='36' alt='Exportar a Microsoft Excel'></a>"; 
      }
      else
         print ("<center><span class='Estilo1'>No hay informacion disponible</span></center>");

////Cerrar conexión
mysql_close ($conexion);
?>
</div>
</body>
</html>
