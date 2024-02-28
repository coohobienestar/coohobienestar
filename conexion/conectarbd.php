<?php
////USAMOS ESTA FUNCION PARA CONECTARNOS AL MOTOR DE BD
// sicc_3137912169
function Conectarse(){
   
   if (!($link=mysql_connect("localhost","root","bxdvsjooLY4B6eKt"))){
      // $conn->set_charset('utf8');    
      mysql_set_charset('utf8', $link);
      echo "<br>Error conectando al motor de BD.";

      exit();
   }
   // $link->set_charset("utf8");
   mysql_select_db("sicc24", $link);
   return $link;

}

function error_consulta($consulta,$sql){
  if (! $consulta){
   echo "<br>La consulta SQL contiene errores: ".$sql;
   exit();
    }
}
?>
