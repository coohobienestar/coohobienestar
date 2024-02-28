<?php
ini_set('max_execution_time',0);
/*
session_start();
if (!isset($_SESSION['cod_usuario']))
  die("No esta autorizado a ingresar al sitio (Clave o nombre de Usuario incorrecto)");
  */
?>
<html>
<head>
</head>
<link rel="stylesheet" type="text/css" href="../estilos/estilo.css">
<body>

<?php
include("../conexion/conectarbd.php");
function validarDatosIngresados(){
$conexion=Conectarse(); 

$tipo_operacion = $_REQUEST[tipo_operacion];

 if($tipo_operacion == 1){
  
  $error="";

  if($_REQUEST['menu'] == '')   
    $error=$error . "Debe seleccionar un Menu<br>";       

  if($_REQUEST['plato'] == '')   
    $error=$error . "Debe seleccionar un Plato<br>";  

  if($_REQUEST['ingrediente'] == '')   
    $error=$error . "Debe seleccionar un Ingrediente<br>"; 

  if(filter_var($_REQUEST['cantidad'], FILTER_VALIDATE_FLOAT) === false)  
    $error=$error . "La Cantidad debe ser un Valor Numerico.<br>";

  if($_REQUEST['cantidad'] <=0)   
    $error=$error . "La Cantidad debe ser ser Mayor que Cero<br>";    
    
   ////BUSCAMOS SI EL COMPONENTE YA EXISTE
   $instruccion3 = "SELECT minuta.nombre AS nom_minuta, menu.nombre AS nom_menu, plato.nombre AS nom_plato, ingrediente.nombre AS nom_ingrediente,
                           plato_ingrediente.cantidad AS cantidad
                    FROM plato_ingrediente 
                    INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                    INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu 
                    INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                    INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                    WHERE plato_ingrediente.cod_minuta = $_REQUEST[codigo0] AND plato_ingrediente.cod_menu = $_REQUEST[menu] 
                      AND plato_ingrediente.cod_plato = $_REQUEST[plato] AND plato_ingrediente.cod_ingrediente = $_REQUEST[ingrediente]";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $nfilas3 = mysql_num_rows ($consulta3); 
   
   if($nfilas3 > 0)
     $error=$error . "Esta información ya se encuentra registrada en el sistema<br>";       
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }

 if($tipo_operacion == 5){
  
  $error="";

  if(filter_var($_REQUEST['cantidad'], FILTER_VALIDATE_FLOAT) === false)  
    $error=$error . "La Cantidad debe ser un Valor Numerico.<br>";

  if($_REQUEST['cantidad'] <=0)   
    $error=$error . "La Cantidad debe ser ser Mayor que Cero<br>";       
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 
 
 if($tipo_operacion == 6){
  
  $error="";

  if($_REQUEST['ingrediente'] == '')   
    $error=$error . "Debe seleccionar un Ingrediente<br>";   

  if(filter_var($_REQUEST['cantidad'], FILTER_VALIDATE_FLOAT) === false)  
    $error=$error . "La Cantidad debe ser un Valor Numerico.<br>";

  if($_REQUEST['cantidad'] <=0)   
    $error=$error . "La Cantidad debe ser ser Mayor que Cero<br>"; 
    
  if($_REQUEST['ingrediente'] != ''){ 
   ////BUSCAMOS SI EL COMPONENTE YA EXISTE
   $instruccion3 = "SELECT minuta.nombre AS nom_minuta, menu.nombre AS nom_menu, plato.nombre AS nom_plato, ingrediente.nombre AS nom_ingrediente,
                           plato_ingrediente.cantidad AS cantidad
                    FROM plato_ingrediente 
                    INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                    INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu 
                    INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                    INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                    WHERE plato_ingrediente.cod_minuta = $_REQUEST[codigo0] AND plato_ingrediente.cod_menu = $_REQUEST[menu0] 
                      AND plato_ingrediente.cod_plato = $_REQUEST[plato0] AND plato_ingrediente.cod_ingrediente = $_REQUEST[ingrediente]";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $nfilas3 = mysql_num_rows ($consulta3); 
   
   if($nfilas3 > 0)
     $error=$error . "Esta información ya se encuentra registrada en el sistema<br>"; 
   }           
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }

 if($tipo_operacion == 7){
  
  $error="";

  if($_REQUEST['plato'] == '')   
    $error=$error . "Debe seleccionar un Plato<br>";
    
  if($_REQUEST['menu'] == '')   
    $error=$error . "Debe seleccionar un Menu<br>";         
    
  if($_REQUEST['menu'] != '' && $_REQUEST['plato'] != ''){
   ////BUSCAMOS SI EL PLATO YA EXISTE
   $instruccion3 = "SELECT minuta.nombre AS nom_minuta, menu.nombre AS nom_menu, plato.nombre AS nom_plato, ingrediente.nombre AS nom_ingrediente,
                           plato_ingrediente.cantidad AS cantidad
                    FROM plato_ingrediente 
                    INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                    INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu 
                    INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                    INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                    WHERE plato_ingrediente.cod_minuta = $_REQUEST[codigo0] AND plato_ingrediente.cod_menu = $_REQUEST[menu] 
                      AND plato_ingrediente.cod_plato = $_REQUEST[plato]";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $nfilas3 = mysql_num_rows ($consulta3); 
   
   if($nfilas3 > 0)
     $error=$error . "Esta información ya se encuentra registrada en el sistema<br>";          
    }
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }
 
 if($tipo_operacion == 8){
  
  $error="";

  if($_REQUEST['minuta_o'] == '')   
    $error=$error . "Debe seleccionar una Minuta de Origen<br>";
    
  if($_REQUEST['menu_o'] == '')   
    $error=$error . "Debe seleccionar un Menu de Origen<br>";         

  if($_REQUEST['menu_d'] == '')   
    $error=$error . "Debe seleccionar un Menu de Destino<br>";  
    
  if($_REQUEST['menu_d'] != '' && $_REQUEST['minuta_o'] != '' && $_REQUEST['menu_o'] != ''){
   ////BUSCAMOS SI EL MENU YA EXISTE
   $instruccion3 = "SELECT minuta.nombre AS nom_minuta, menu.nombre AS nom_menu, plato.nombre AS nom_plato, ingrediente.nombre AS nom_ingrediente,
                           plato_ingrediente.cantidad AS cantidad
                    FROM plato_ingrediente 
                    INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                    INNER JOIN menu ON menu.cod_menu = plato_ingrediente.cod_menu 
                    INNER JOIN plato ON plato.cod_plato = plato_ingrediente.cod_plato 
                    INNER JOIN ingrediente ON ingrediente.cod_ingrediente = plato_ingrediente.cod_ingrediente
                    WHERE plato_ingrediente.cod_minuta = $_REQUEST[codigo0] AND plato_ingrediente.cod_menu = $_REQUEST[menu_d]";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $nfilas3 = mysql_num_rows ($consulta3); 
   
   if($nfilas3 > 0)
     $error=$error . "Esta información ya se encuentra registrada en el sistema<br>";          
    }

  if($_REQUEST['menu_d'] != '' && $_REQUEST['minuta_o'] != '' && $_REQUEST['menu_o'] != ''){
   ////MIRAMOS SI HAY INFORMACION PARA TRASPASAR
   $instruccion2 = "SELECT plato_ingrediente.cod_plato AS cod_plato, plato_ingrediente.cod_ingrediente AS cod_ingrediente, plato_ingrediente.cantidad AS cantidad
                    FROM plato_ingrediente 
                    INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                    WHERE plato_ingrediente.cod_minuta = $_REQUEST[minuta_o] AND plato_ingrediente.cod_menu = $_REQUEST[menu_o] ";
                    
   $consulta2 = mysql_query ($instruccion2, $conexion);  
   $nfilas2 = mysql_num_rows ($consulta2); 
   
   if($nfilas2 == 0)
     $error=$error . "No se encuentra Información para Agregar por favor verifique...<br>";          
    } 
       
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 } 

 if($tipo_operacion == 9){
  
  $error="";

  if($_REQUEST['minuta_o'] == '')   
    $error=$error . "Debe seleccionar una Minuta de Origen<br>";
    
  if($_REQUEST['minuta_o'] != ''){
   ////BUSCAMOS QUE LA MINUTA DESTINO ESTE VACIA
   $instruccion3 = "SELECT cod_minuta FROM plato_ingrediente WHERE cod_minuta = $_REQUEST[codigo0]";
                    
   $consulta3 = mysql_query ($instruccion3, $conexion);  
   $nfilas3 = mysql_num_rows ($consulta3); 
   
   if($nfilas3 > 0)
     $error=$error . "La minuta $_REQUEST[codigo0] ya posee algunos componentes, debe estar vacia para poder ser duplicada<br>";          
    }

  if($_REQUEST['minuta_o'] != ''){
   ////MIRAMOS SI HAY INFORMACION PARA TRASPASAR
   $instruccion2 = "SELECT cod_minuta FROM plato_ingrediente WHERE cod_minuta = $_REQUEST[minuta_o]";
                    
   $consulta2 = mysql_query ($instruccion2, $conexion);  
   $nfilas2 = mysql_num_rows ($consulta2); 
   
   if($nfilas2 == 0)
     $error=$error . "No se encuentra Información para Agregar por favor verifique...<br>";          
    } 
       
    
  if ($error!=""){
    echo "<center><span class=\"Estilo1\">$error</span></center><br>";
    echo "<center><a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a></center><br>";
    die();
  }
 }   

}
  
function altaDatos(){
$conexion=Conectarse(); 

$tipo_operacion = $_REQUEST[tipo_operacion];
$cod_minuta = $_REQUEST[codigo0]; 
$cod_menu = $_REQUEST[menu0];  
$cod_plato = $_REQUEST[plato0]; 
$cod_ingrediente = $_REQUEST[ingrediente0];
    
  //// 1 AGREGAR COMPONENTE MINUTA
  if($tipo_operacion == 1){
     $cod_menu = $_REQUEST[menu];  
     $cod_plato = $_REQUEST[plato]; 
     $cod_ingrediente = $_REQUEST[ingrediente];
     $cantidad = $_REQUEST[cantidad]; 
     
     mysql_query("INSERT INTO plato_ingrediente(cod_minuta, cod_menu, cod_plato, cod_ingrediente, cantidad) VALUES ('$cod_minuta','$cod_menu','$cod_plato','$cod_ingrediente','$cantidad')", $conexion);
    }  
  //// 2 ELIMINAR MENU
  if($tipo_operacion == 2){
     $instruccion4 = "DELETE FROM plato_ingrediente WHERE cod_minuta = '$cod_minuta' AND cod_menu = '$cod_menu'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    } 
  //// 3 ELIMINAR PLATO
  if($tipo_operacion == 3){
     $instruccion4 = "DELETE FROM plato_ingrediente WHERE cod_minuta = '$cod_minuta' AND cod_menu = '$cod_menu' AND cod_plato = '$cod_plato'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    }
  //// 4 ELIMINAR INGREDIENTE
  if($tipo_operacion == 4){
     $instruccion4 = "DELETE FROM plato_ingrediente WHERE cod_minuta = '$cod_minuta' AND cod_menu = '$cod_menu' AND cod_plato = '$cod_plato' 
                         AND cod_ingrediente = '$cod_ingrediente'";
     $consulta4 = mysql_query ($instruccion4, $conexion);     
    }
//// 5 EDITAR CANTIDAD INGREDIENTE
  if($tipo_operacion == 5){
     $cantidad = $_REQUEST[cantidad]; 
     
     $instruccion4 = "UPDATE plato_ingrediente SET cantidad = '$cantidad' WHERE cod_minuta = '$cod_minuta' AND cod_menu = '$cod_menu' AND cod_plato = '$cod_plato' 
                                                                            AND cod_ingrediente = '$cod_ingrediente'";
     $consulta4 = mysql_query ($instruccion4, $conexion);                                                          
    }  
//// 6 AGREGAR INGREDIENTE
  if($tipo_operacion == 6){
     $cod_ingrediente = $_REQUEST[ingrediente];
     $cantidad = $_REQUEST[cantidad]; 
     
     mysql_query("INSERT INTO plato_ingrediente(cod_minuta, cod_menu, cod_plato, cod_ingrediente, cantidad) VALUES ('$cod_minuta','$cod_menu','$cod_plato','$cod_ingrediente','$cantidad')", $conexion);                                                         
    }

//// 7 AGREGAR PLATO
  if($tipo_operacion == 7){
     $plato = $_REQUEST[plato];
     $menu = $_REQUEST[menu]; 

     ////BUSCAMO EL DEPARTAMENTO DE LA MINUTA
     $instruccion2 = "SELECT minuta.cod_departamento AS cod_departamento FROM minuta WHERE minuta.cod_minuta = $cod_minuta";
                    
     $consulta2 = mysql_query ($instruccion2, $conexion); 
     
     $row2 = mysql_fetch_array($consulta2);
     
     $cod_departamento = $row2['cod_departamento'];    
     
     ////BUSCAMOS EL PLATO Y SUS INGREDIENTES A SER AGREGADO
     $instruccion3 = "SELECT DISTINCT plato_ingrediente.cod_plato AS cod_plato, plato_ingrediente.cod_ingrediente AS cod_ingrediente, plato_ingrediente.cantidad AS cantidad
                      FROM plato_ingrediente 
                      INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                      WHERE plato_ingrediente.cod_plato = $plato AND minuta.cod_departamento = $cod_departamento                     
                      GROUP BY  plato_ingrediente.cod_plato, plato_ingrediente.cod_ingrediente";
                    
     $consulta3 = mysql_query ($instruccion3, $conexion); 
      
     $nfilas3 = mysql_num_rows ($consulta3); 
     
     for ($i=0; $i<$nfilas3; $i++){
       $row3 = mysql_fetch_array($consulta3);
       
       $ingrediente = $row3['cod_ingrediente'];
       $q_ingredien = $row3['cantidad'];
       
            mysql_query("INSERT INTO plato_ingrediente (cod_minuta, cod_menu, cod_plato, cod_ingrediente, cantidad) 
                              VALUES ('$cod_minuta','$menu','$plato','$ingrediente','$q_ingredien')", $conexion);
      }
   }  

//// 8 AGREGAR MENU A MINUTA DESDE OTRA MINUTA
  if($tipo_operacion == 8){
     $minuta_o = $_REQUEST[minuta_o];
     $menu_o = $_REQUEST[menu_o]; 
     $menu_d = $_REQUEST[menu_d]; 

     ////BUSCAMO EL DEPARTAMENTO DE LA MINUTA
     $instruccion2 = "SELECT minuta.cod_departamento AS cod_departamento FROM minuta WHERE minuta.cod_minuta = $cod_minuta";
                    
     $consulta2 = mysql_query ($instruccion2, $conexion); 
     
     $row2 = mysql_fetch_array($consulta2);
     
     $cod_departamento = $row2['cod_departamento'];    
     
     ////BUSCAMOS EL MENU ORIGEN EN LA MINUTA ORIGEN
     $instruccion3 = "SELECT plato_ingrediente.cod_plato AS cod_plato, plato_ingrediente.cod_ingrediente AS cod_ingrediente, plato_ingrediente.cantidad AS cantidad
                      FROM plato_ingrediente 
                      INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                      WHERE plato_ingrediente.cod_minuta = $minuta_o AND plato_ingrediente.cod_menu = $menu_o                    
                      ";
                    
     $consulta3 = mysql_query ($instruccion3, $conexion); 
      
     $nfilas3 = mysql_num_rows ($consulta3); 
     
     for ($i=0; $i<$nfilas3; $i++){
       $row3 = mysql_fetch_array($consulta3);
       
       $plato = $row3['cod_plato'];
       $ingrediente = $row3['cod_ingrediente'];
       $q_ingredien = $row3['cantidad'];
       
            mysql_query("INSERT INTO plato_ingrediente (cod_minuta, cod_menu, cod_plato, cod_ingrediente, cantidad) 
                              VALUES ('$cod_minuta','$menu_d','$plato','$ingrediente','$q_ingredien')", $conexion);
      }
   }
 
//// 9 AGREGAR UNA MINUTA DESDE OTRA MINUTA DUPLICAR MINUTA    
  if($tipo_operacion == 9){
     $minuta_o = $_REQUEST[minuta_o];

     ////BUSCAMO EL DEPARTAMENTO DE LA MINUTA
     $instruccion2 = "SELECT minuta.cod_departamento AS cod_departamento FROM minuta WHERE minuta.cod_minuta = $cod_minuta";
                    
     $consulta2 = mysql_query ($instruccion2, $conexion); 
     
     $row2 = mysql_fetch_array($consulta2);
     
     $cod_departamento = $row2['cod_departamento'];    
     
     ////BUSCAMOS EL MENU ORIGEN EN LA MINUTA ORIGEN
     $instruccion3 = "SELECT plato_ingrediente.cod_menu AS cod_menu, plato_ingrediente.cod_plato AS cod_plato, plato_ingrediente.cod_ingrediente AS cod_ingrediente, 
                             plato_ingrediente.cantidad AS cantidad
                      FROM plato_ingrediente 
                      INNER JOIN minuta ON minuta.cod_minuta = plato_ingrediente.cod_minuta
                      WHERE plato_ingrediente.cod_minuta = $minuta_o                  
                      ";
                    
     $consulta3 = mysql_query ($instruccion3, $conexion); 
      
     $nfilas3 = mysql_num_rows ($consulta3); 
     
     for ($i=0; $i<$nfilas3; $i++){
       $row3 = mysql_fetch_array($consulta3);
       
       $menu = $row3['cod_menu'];
       $plato = $row3['cod_plato'];
       $ingrediente = $row3['cod_ingrediente'];
       $q_ingredien = $row3['cantidad'];
       
            mysql_query("INSERT INTO plato_ingrediente (cod_minuta, cod_menu, cod_plato, cod_ingrediente, cantidad) 
                              VALUES ('$cod_minuta','$menu','$plato','$ingrediente','$q_ingredien')", $conexion);
      }
   }   
                         
} 
 
validarDatosIngresados();
altaDatos();
   
?>

<center><strong><span class="Estilo1">Se registraron los datos correctamente</span></center></strong><br>

<body onLoad="JavaScript:Cerraraliniciar()">
<script language="JavaScript">
function Cerraraliniciar(){
var id;
id = setTimeout("cerrar()", 1000);
}
function cerrar() {
var ventana = window.self;
ventana.opener = window.self;
ventana.close();
}
</script>

</body>
</html>
