<?php 
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_execution_time', 0);

include("../conexion/conectarbd.php");
$conexion=Conectarse();

$status = "";
if ($_POST["action"] == "upload") {

   $cod_documento_2  = $_POST[codigo0];
   $usuario_2  = $_POST[usuario0];

	//obtenemos los datos del archivo 
	//$tamano = $_FILES["archivo"]['size'];
  //$tipo = $_FILES["archivo"]['type'];  
	$archivo = $_FILES["archivo"]['name'];
  
	if ($archivo != ""){
    if (($_FILES["archivo"]['type'] == "image/gif") || ($_FILES["archivo"]['type'] == "image/jpeg") || ($_FILES["archivo"]['type'] == "image/png") 
    || ($_FILES["archivo"]['type'] == "application/pdf") || ($_FILES["archivo"]['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
    || ($_FILES["archivo"]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")){
  		
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
      
      $nombre_final = $cod_documento_2."_".$archivo.$tipo;
      //$nombre_final = $cod_producto_2."_".$maximo_2.$tipo;

      // guardamos el archivo a la carpeta producto_imagen
  		$destino =  "../documento_subido/calidad/".$nombre_final;
  		if (copy($_FILES['archivo']['tmp_name'],$destino)) {
  			$status = "Archivo subido: <b>".$nombre_final."</b>";
       
        $fecha = date("Y-m-d H:i:s"); 
        
        ////ACTUALIZAMOS LOD DATOS DEL DOCUMENTO
        $instruccion4 = "UPDATE 0c_documento_calidad SET nombre_doc_subido = '$nombre_final', fecha_subido = '$fecha' WHERE cod_documento = $cod_documento_2";
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
       $status = "Error al subir el archivo. No es un archivo valido (JPG - GIF - PNG - PDF - DOC - XLS)";
       } 
	}else {
		$status = "Error al subir el archivo";
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Subir documentos de Calidad</title>
<link href="../estilos/estilo_upload.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
  $tipo = $_REQUEST['tipo_operacion']; 
  $cod_documento = $_REQUEST['codigo'];
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
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <td class="text">
      <input type='hidden' name='codigo0' value='<?php print("$cod_documento");?>'>  
      <input type='hidden' name='usuario0' value='<?php print("$usuario");?>'>
      
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
