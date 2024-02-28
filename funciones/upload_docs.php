<?php 
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_execution_time', 0);

include("../conexion/conectarbd.php");
$conexion=Conectarse();  

$status = "";
if ($_POST["action"] == "upload") {

   $codigo2  = $_POST[codigo0];
   $usuario_2  = $_POST[usuario0];
   $tipoope  = $_POST[tipooperacion0];

	//obtenemos los datos del archivo 
	//$tamano = $_FILES["archivo"]['size'];
  $tipo = $_FILES["archivo"]['type'];  
	$archivo = $_FILES["archivo"]['name'];
  
	if ($archivo != ""){
    if (($_FILES["archivo"]['type'] == "image/gif") || ($_FILES["archivo"]['type'] == "image/jpeg") || ($_FILES["archivo"]['type'] == "image/png") 
    || ($_FILES["archivo"]['type'] == "application/pdf") || ($_FILES["archivo"]['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
    || ($_FILES["archivo"]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || ($_FILES["archivo"]['type'] == "video/mp4")
    || ($_FILES["archivo"]['type'] == "application/vnd.ms-excel")){
  		
      if($_FILES["archivo"]['type'] == "image/gif"){
         $tipo = ".gif";        
        }
      if($_FILES["archivo"]['type'] == "image/jpeg"){
         $tipo = ".jpg";        
        }
      if($_FILES["archivo"]['type'] == "image/png"){
         $tipo = ".png";        
        } 
      if($_FILES["archivo"]['type'] == "application/pdf"){
         $tipo = ".pdf"; 
        }         
      if($_FILES["archivo"]['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
         $tipo = ".xls";
        } 
      if($_FILES["archivo"]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
         $tipo = ".doc";
        } 
      if($_FILES["archivo"]['type'] == "video/mp4"){
         $tipo = ".mp4";
        } 
      if($_FILES["archivo"]['type'] == "application/vnd.ms-excel"){
         $tipo = ".xls";
        }                                       
      
      $nombre_final = $codigo2."_".$archivo.$tipo;
      
        $fecha = date("Y-m-d H:i:s");   
        
        if($tipoope == 10){
           ////ELIMINAMOS EL ARCHIVO ANTERIOR PARA NO GUARDAR BASURA
           $sql2 ="SELECT cuenta_cobro FROM fl_ruta WHERE cod_ruta = $codigo2";
           $consulta2 = mysql_query($sql2);
           error_consulta($consulta2,$sql2);
           $nfilas2 = mysql_num_rows ($consulta2);   
            
           $row2 = mysql_fetch_array ($consulta2);
           
           $nombre = $row2['cuenta_cobro'];          
           
           ////ELIMINAMOS EL ARCHIVO
           $dir = "../documento_subido/transporte/cuentacobro/$nombre";
           unlink($dir);    
           
           //echo"<br>Dir: ".$dir;               
                   
           $ruta = "../documento_subido/transporte/cuentacobro/"; 
           $sql_upd = "UPDATE fl_ruta SET cuenta_cobro = '$nombre_final', fecha_subido_cc = '$fecha', usuario_subio_cc = '$usuario_2' WHERE cod_ruta = $codigo2";  
          }
          
        if($tipoope == 11){
           ////ELIMINAMOS EL ARCHIVO ANTERIOR PARA NO GUARDAR BASURA
           $sql2 ="SELECT egreso FROM fl_ruta WHERE cod_ruta = $codigo2";
           $consulta2 = mysql_query($sql2);
           error_consulta($consulta2,$sql2);
           $nfilas2 = mysql_num_rows ($consulta2);   
            
           $row2 = mysql_fetch_array ($consulta2);
           
           $nombre = $row2['egreso'];          
           
           ////ELIMINAMOS EL ARCHIVO
           $dir = "../documento_subido/transporte/egreso/$nombre";
           unlink($dir);    
        
           $ruta = "../documento_subido/transporte/egreso/"; 
           $sql_upd = "UPDATE fl_ruta SET egreso = '$nombre_final', fecha_subido_egreso = '$fecha', usuario_subio_egreso = '$usuario_2' WHERE cod_ruta = $codigo2";
          }         

      // guardamos el archivo a la carpeta producto_imagen
  		$destino =  $ruta.$nombre_final;
  		if (copy($_FILES['archivo']['tmp_name'],$destino)) {
  			$status = "Archivo subido: <b>".$nombre_final."</b>";        
        
        ////ACTUALIZAMOS LOD DATOS DEL DOCUMENTO
        $instruccion4 = $sql_upd;
        $consulta4 = mysql_query ($instruccion4, $conexion); 
       
        ?>
        
        
          <body onLoad="JavaScript:Cerraraliniciar()">
          <script language="JavaScript">
          function Cerraraliniciar(){
          var id;
          id = setTimeout("cerrar()", 2000);
          }
          function cerrar() {
          var ventana = window.self;
          ventana.opener = window.self;
          ventana.close();
          }
          </script>
          
          </body>
        <?php 
  		 
       }else{
  			 $status = "Error al subir el archivo";
  		   }
     }else{
       $status = "Error al subir el archivo. No es un archivo valido (JPG - GIF - PNG - PDF - DOC - XLS) ";
       } 
	}else {
		$status = "Error al subir el archivo";
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Subir documentos Transportes</title>
<link href="../estilos/estilo_upload.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php

  $tipo = $_REQUEST['tipo_operacion']; 
  $codigo = $_REQUEST['codigo'];
  $usuario = $_REQUEST['usuario'];      
  
?>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="600" height="40" class="titulo">Subir Documento </td>
  </tr>
  <tr>
    <td class="text">Por favor seleccione el archivo a subir:</td>
  </tr>
  <tr>
  <form action="upload_docs.php" method="post" enctype="multipart/form-data">
    <td class="text">
      <input type='hidden' name='codigo0' value='<?php print("$codigo");?>'>  
      <input type='hidden' name='usuario0' value='<?php print("$usuario");?>'>
      <input type='hidden' name='tipooperacion0' value='<?php print("$tipo");?>'>
      
      <input name="archivo" type="file" class="casilla" id="archivo" size="60" />
      <input name="enviar" type="submit" class="boton" id="enviar" value="Subir Archivo" />
	    <input name="action" type="hidden" value="upload" />	  
    </td>
	</form>
  </tr>
  <tr>
    <td class="text" style="color:red"><?php echo $status; ?></td>
  </tr>
</table>
</body>
</html>
