/* This version of JavaScript Browser can be used by anyone who is not intented
to do profit directly with this code, it means you can not sale the code as is, 
you can use it ont your web or php project, or put in a CD Magazine, if you really
do profit with your project and my code consider sharing them :P.
If you want to include the calendar in some web editor, do not remove this legend.
The lastest version can be found at
http://www.compunauta.com/forums/linux/programacion/java/gus_browser.html

This is Open and Free Software, madgus@gmail.com, gustavo@compunauta.com
bugfix - feb bad days.
*/
//INICIALIZACIÓN DE ELEMENTOS PARA EL CALENDARIO
var meses=new Array();
meses[1]="Enero";
meses[2]="Febrero";
meses[3]="Marzo";
meses[4]="Abril";
meses[5]="Mayo";
meses[6]="Junio";
meses[7]="Julio";
meses[8]="Agosto";
meses[9]="Septiembre";
meses[10]="Octubre";
meses[11]="Noviembre";
meses[12]="Diciembre";
var dias=new Array();
dias[1]="Lunes";
dias[2]="Martes";
dias[3]="Miercoles";
dias[4]="Jueves";
dias[5]="Viernes";
dias[6]="Sabado";
dias[0]="Domingo";

fecha = new Date();
var year;
if(fecha.getFullYear){
year = fecha.getFullYear();
}else{
year = fecha.getYear()+1900;}
var month= fecha.getMonth()+1;
var fname='';
//CONVERSION DEL CALENDARIO ARREGLOS MULTIDIMENSIONALES
var tabla_i=new Array();
var tabla_ishift=new Array();
//VALORES MATICES EN AÑOS DE CENTURIAS
//COLUMNA 1 FILA 0 VAL 6
tabla_i[00]=6;
tabla_i[06]=6;
tabla_i[17]=6;
tabla_i[23]=6;
tabla_i[28]=6;
tabla_i[34]=6;
tabla_i[45]=6;
tabla_i[51]=6;
tabla_i[56]=6;
tabla_i[62]=6;
tabla_i[73]=6;
tabla_i[79]=6;
tabla_i[84]=6;
tabla_i[90]=6;
//COLUMNA 2 FILA 0 VAL 0
tabla_i[01]=0;
tabla_i[07]=0;
tabla_i[12]=0;
tabla_i[18]=0;
tabla_i[29]=0;
tabla_i[35]=0;
tabla_i[40]=0;
tabla_i[46]=0;
tabla_i[57]=0;
tabla_i[63]=0;
tabla_i[68]=0;
tabla_i[74]=0;
tabla_i[85]=0;
tabla_i[91]=0;
tabla_i[96]=0;
//COLUMNA 3 FILA 0 VAL 1
tabla_i[02]=1;
tabla_i[13]=1;
tabla_i[19]=1;
tabla_i[24]=1;
tabla_i[30]=1;
tabla_i[41]=1;
tabla_i[47]=1;
tabla_i[52]=1;
tabla_i[58]=1;
tabla_i[69]=1;
tabla_i[75]=1;
tabla_i[80]=1;
tabla_i[86]=1;
tabla_i[97]=1;
//COLUMNA 4 FILA 0 VAL 2
tabla_i[03]=2;
tabla_i[08]=2;
tabla_i[14]=2;
tabla_i[25]=2;
tabla_i[31]=2;
tabla_i[36]=2;
tabla_i[42]=2;
tabla_i[53]=2;
tabla_i[59]=2;
tabla_i[64]=2;
tabla_i[70]=2;
tabla_i[81]=2;
tabla_i[87]=2;
tabla_i[92]=2;
tabla_i[98]=2;
//COLUMNA 5 FILA 0 VAL 3
tabla_i[09]=3;
tabla_i[15]=3;
tabla_i[20]=3;
tabla_i[26]=3;
tabla_i[37]=3;
tabla_i[43]=3;
tabla_i[48]=3;
tabla_i[54]=3;
tabla_i[65]=3;
tabla_i[71]=3;
tabla_i[76]=3;
tabla_i[82]=3;
tabla_i[93]=3;
tabla_i[99]=3;
//COLUMNA 6 FILA 0 VAL 4
tabla_i[04]=4;
tabla_i[10]=4;
tabla_i[21]=4;
tabla_i[27]=4;
tabla_i[32]=4;
tabla_i[38]=4;
tabla_i[49]=4;
tabla_i[55]=4;
tabla_i[60]=4;
tabla_i[66]=4;
tabla_i[77]=4;
tabla_i[83]=4;
tabla_i[88]=4;
tabla_i[94]=4;
//COLUMNA 7 FILA 0 VAL 5
tabla_i[05]=5;
tabla_i[11]=5;
tabla_i[16]=5;
tabla_i[22]=5;
tabla_i[33]=5;
tabla_i[39]=5;
tabla_i[44]=5;
tabla_i[50]=5;
tabla_i[61]=5;
tabla_i[67]=5;
tabla_i[72]=5;
tabla_i[78]=5;
tabla_i[89]=5;
tabla_i[95]=5;

//DESPLAZAMIENTOS DE LA TABLA 1 PARA LOS RESULTADOS (EN CENTURIAS)
//0 es que no hay desplazamiento ej: si corresponde un 5 para que de 6 hay que:
//(5+D) Mod 7 = 6, D=6
//hasta la centuria 21 está bien, más habría que calcularlas. Años mayores al 2199 no están contemplados.
tabla_ishift[00]=0;
tabla_ishift[01]=6;
tabla_ishift[02]=5;
tabla_ishift[03]=4;
tabla_ishift[04]=3;
tabla_ishift[05]=2;
tabla_ishift[06]=1;
tabla_ishift[07]=0;
tabla_ishift[08]=6;
tabla_ishift[09]=5;
tabla_ishift[10]=4;
tabla_ishift[11]=3;
tabla_ishift[12]=2;
tabla_ishift[13]=1;
tabla_ishift[14]=0;
tabla_ishift[15]=4;
tabla_ishift[16]=2;
tabla_ishift[17]=0;
tabla_ishift[18]=5;
tabla_ishift[19]=3; //Realmente solo deberíamos poner desde aquí (1900)
tabla_ishift[20]=2;
tabla_ishift[21]=6;

var theStatus = new Object();
//FUNCIONES DE CALENDARIO
//STEP I (paso uno)
function step_i(Year){
var Century=Math.floor(Year/100);
var Decade=Year-(Century*100);
var TABLA_I=tabla_i[Decade];
return ((TABLA_I+tabla_ishift[Century]) % 7);
}
//STEP II (paso dos)
function step_ii(Year,Month){
var step1=step_i(Year);
var CATEGORIA;
var b=bisiesto(Year);

	switch (Month){
		case 1: //ENERO
			if(b){CATEGORIA=6;}
			else{CATEGORIA=0;}
		break;
		case 2: //FEBRERO
			if(b){CATEGORIA=2;}
			else{CATEGORIA=3;}
		break;
//		case 3: //MARZO
//			CATEGORIA=3;
//		break;
		case 4: //ABRIL
		case 7: //JULIO		
			CATEGORIA=6;
		break;
		case 5: //MAYO
			CATEGORIA=1;
		break;
		case 6: //JUNIO
			CATEGORIA=4;
		break;
		case 8: //AGOSTO
			CATEGORIA=2;
		break;
		case 10: //OCTUBRE
			CATEGORIA=0;
		break;
//		case 11: //NOVIEMBRE
//			CATEGORIA=3;
//		break;
		case 12: //DICIEMBRE
		case 9: //SEPTIEMBRE
			CATEGORIA=5;
		break;
		default: //OPTIMIZACION, LAS QUE SON MAS MAR. NOV.
			CATEGORIA=3;
		
	}
return ((CATEGORIA+step1) % 7);
}
//STEP III (paso 3)
function step_iii(Year,Month,Day){
var step2=step_ii(Year,Month);
Day=(Day % 7);
return step3=((Day+5+step2) % 7);
}

function bisiesto(Year){
var b=false;
if((Year % 4)==0){b=true;}
if((Year % 100)==0){b=false;}
if((Year % 400)==0){b=true;}
return b;
}

function daysOfMonth(Month,Year){
switch (Month){
case 4:
case 6:
case 9:
case 11:
	return 30;
	break;
case 2:
	if (bisiesto(Year)){return 29;}else{return 28;}
	break;
default:
	return 31;
	
}
}

//FUNCIONES PARA DIBUJAR Y LIMPIAR EL CALENDARIO
//LIMPIA CALENDARIO
function doHide(id,formname,fieldname){
layer = document.getElementById(id);
layer.innerHTML='';
}
//DIBUJAR CALENDARIO
function doShow(id,formname,fieldname){
if (year>2100){year=2100;}
if (year<1900){year=1900;}
if (month<1){month=12;year-=1;}
if (month>12){month=1;year+=1;}
layer = document.getElementById(id);
layer.innerHTML='';
var innerTMP='';
var MonthNum;
var DayNum;
var DayOfWeek=step_iii(year,month,1);
innerTMP+="<table width=\"100%\">";
innerTMP+="<tr><TD align='center'><A href='#' onClick=\"year-=1;doShow('"+id+"','"+formname+"','"+fieldname+"')\">&#060;&#060;</A></TD><TD align='center'><A href='#' onClick=\"month-=1;doShow('"+id+"','"+formname+"','"+fieldname+"')\">&#060;</A></TD><TD colspan='3' align='center'><H4>" + meses[month] + " - " + year + "</H4></TD><TD align='center'><A href='#' onClick=\"month+=1;doShow('"+id+"','"+formname+"','"+fieldname+"')\">&#062;</A></TD><TD align='center'><A href='#' onClick=\"year+=1;doShow('"+id+"','"+formname+"','"+fieldname+"')\">&#062;&#062;</A></TD></tr>";
innerTMP+="<tr>";
var MaxDays=daysOfMonth(month,year);
for(var dow=0;dow<7;dow++){
innerTMP+="<td align='center'><h4>"+dias[dow]+"</h4></td>";
}
innerTMP+="</tr>";
var SDraw=false;
if(month>9){MonthNum=''+month;}else{MonthNum='0'+month;}
for(var day_c=1;day_c<=36;){
innerTMP+="<tr>\n";
	for(var dow=0;dow<7;dow++){
	if(dow==DayOfWeek){SDraw=true;}
	if(day_c>9){DayNum=''+day_c;}else{DayNum='0'+day_c;}
	innerTMP+="<td align='center'>";
	if((day_c<=MaxDays) && SDraw){innerTMP+="<A href='#' onClick=\"document.forms['"+formname+"'].elements['"+fieldname+"'].value='"+year+"'+'"+MonthNum+"'+'"+DayNum+"';doHide('"+id+"','"+formname+"','"+fieldname+"');\">"+(day_c++)+"</A>";}else if(SDraw){day_c++;innerTMP+="&nbsp;";}
	innerTMP+="</td>\n";}
innerTMP+="</tr>\n";
}
innerTMP+="</table>";
//if (fname!=fieldname){alert(innerTMP);}
layer.innerHTML=innerTMP;
fname=fieldname;
}

function compareDates(formname,field1,field2){
f1=1*document.forms[formname].elements[field1].value;
f2=1*document.forms[formname].elements[field2].value;
if (f1==0 || f2==0){alert("Ambas fechas deben estar establecidas!");return false;}
if (f1>f2){alert("La segunda fecha debe ser más grande que la primera!");return false;}else{return true;}
}
