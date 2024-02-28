<?php
ini_set('max_execution_time',0);

////FUNCION QUE CREA UN BACKUP DE LA BASE DE DATOS
function backup(){
$serv="localhost"; //nombre del servidor
$bd="sicc";  //nombre de la base de datos
$usr="sa"; //usuario para conectarse a la base de datos
$pwd="sicc_3137912169"; //password del usuario
////RUTA ABSOLUTA
//$mysqldump='"D:/xampp/mysql/bin/mysqldump.exe"';

////RUTA RELATIVA
$mysqldump='"./funciones/mysqldump.exe"';

//el nombre del backup llevara la fecha y hora del servidor:
$nombre_back=$bd."_".date("Ymd_his");

////ALAMCENAMOS EL REGISTRO DEL BACLUP REALIZADO

passthru("$mysqldump $bd -h $serv -u $usr -p$pwd > ./backups/$nombre_back.sql");
}

////FUNCION QUE RESTAURA LA BASE DE DATOS
function restaurar(){
$serv="localhost"; //nombre del servidor
$bd="sicc";  //nombre de la base de datos
$usr="sa"; //usuario para conectarse a la base de datos
$pwd="sicc_3137912169"; //password del usuario
////RUTA ABSOLUTA
//$mysqldump='"D:/xampp/mysql/bin/mysqldump.exe"';

////RUTA RELATIVA
$mysqldump='"./funciones/mysqldump.exe"';


passthru("$mysql $bd -h $serv -u $usr -p$pwd < ./backups/$nombre_back.sql");

}

?> 