<?php require_once('connections/conexion_usuarios.php'); ?>

<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "ingreso.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>


<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

//Guardar imagen

if ($_FILES["Imagen"]["error"] > 0){

	echo "<script>alert('NO HAY IMAGEN DE DETENIDO ADJUNTADA')</script>";
	
} else {

		$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png","image/JPG", "image/JPEG", "image/GIF", "image/PNG");
	$limite_kb = 4096;

	if (in_array($_FILES['Imagen']['type'], $permitidos) && $_FILES['Imagen']['size'] <= $limite_kb * 1024){

		$ruta = "vendedores/" . $_FILES['Imagen']['name'];
		
		if (!file_exists($ruta)){
			
			$resultado = @move_uploaded_file($_FILES["Imagen"]["tmp_name"], $ruta);
			if ($resultado){
				$nombre = $_FILES['Imagen']['name'];
				
	
  $insertSQL = sprintf("INSERT INTO supervision_ruta (nom_vendedor, num_vendedor, ruta, marca, ceve,direccion, Latitude, Longitude, cierra_puertas, conoce_zonas, retira_llaves, dinero_fuera, exhibe_dinero, deposita_continuamente, dentro_ruta, dispositivos_seguridad, paradas_inecesarias, numero_cm, llamativos, personas_ajenas, observaciones, comentarios_vendedor, dinero, hora_inicio, fecha_elaboracion,atendio,Imagen) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s, %s,%s,%s)",
                       GetSQLValueString($_POST['nom_vendedor'], "text"),
                       GetSQLValueString($_POST['num_vendedor'], "text"),
                       GetSQLValueString($_POST['ruta'], "text"),
                       GetSQLValueString($_POST['marca'], "text"),
                       GetSQLValueString($_POST['ceve'], "text"),
					   GetSQLValueString($_POST['direccion'], "text"),
					   GetSQLValueString($_POST['Latitude'], "text"),
					   GetSQLValueString($_POST['Longitude'], "text"),
                       GetSQLValueString($_POST['cierra_puertas'], "text"),
                       GetSQLValueString($_POST['conoce_zonas'], "text"),
                       GetSQLValueString($_POST['retira_llaves'], "text"),
                       GetSQLValueString($_POST['dinero_fuera'], "text"),
                       GetSQLValueString($_POST['exhibe_dinero'], "text"),
                       GetSQLValueString($_POST['deposita_continuamente'], "text"),
                       GetSQLValueString($_POST['dentro_ruta'], "text"),
                       GetSQLValueString($_POST['dispositivos_seguridad'], "text"),
                       GetSQLValueString($_POST['paradas_inecesarias'], "text"),
                       GetSQLValueString($_POST['numero_cm'], "text"),
                       GetSQLValueString($_POST['llamativos'], "text"),
                       GetSQLValueString($_POST['personas_ajenas'], "text"),
                       GetSQLValueString($_POST['observaciones'], "text"),
                       GetSQLValueString($_POST['comentarios_vendedor'], "text"),
					   GetSQLValueString($_POST['dinero'], "text"),
					   GetSQLValueString($_POST['hora_inicio'], "text"),
					   GetSQLValueString($_POST['fecha_elaboracion'], "text"),
					   GetSQLValueString($_POST['atendio'], "text"),
					   GetSQLValueString($nombre, "text"));

  mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
  $vendedores = mysql_query($insertSQL, $conexion_usuarios) or die(mysql_error());
  $insertGoTo = "firma2.php";

				echo " window.location.href=\"firma2.php\"</script>";
			  
			   
			   } else {
				echo "<script>alert('HA OCURRIDO UN ERROR AL CARGAR')</script>";
			  
					    
			}
		} else {
		
		$text = $_FILES['Imagen']['name'];

			echo "<script type='text/javascript'>alert('Imagen {$text} ya existe en la base de datos, renombre archivo');</script>";
			
		}
	} else {


print "<script>alert('¡FORMATO DE IMAGEN INVALIDO! SELECCIONE ARCHIVO CON EXTENSION .png, .jpg, .gif, .jpeg')</script>";


		}
	}

}


mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_ceve = "SELECT * FROM ceve ORDER BY ceve ASC";
$consulta_ceve = mysql_query($query_consulta_ceve, $conexion_usuarios) or die(mysql_error());
$row_consulta_ceve = mysql_fetch_assoc($consulta_ceve);
$totalRows_consulta_ceve = mysql_num_rows($consulta_ceve);


mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_supervision = "SELECT * FROM supervision_ruta ORDER BY id DESC";
$consulta_supervision= mysql_query($query_supervision, $conexion_usuarios) or die(mysql_error());
$row_supervision = mysql_fetch_assoc($consulta_supervision);
$totalRows_supervision = mysql_num_rows($consulta_supervision);
?>



<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "ingreso.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>

<?php
$colname_consulta_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_consulta_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_usuario = sprintf("SELECT Nombre, turno FROM supervisores WHERE username = '%s'", $colname_consulta_usuario);
$consulta_usuario = mysql_query($query_consulta_usuario, $conexion_usuarios) or die(mysql_error());
$row_consulta_usuario = mysql_fetch_assoc($consulta_usuario);
$totalRows_consulta_usuario = mysql_num_rows($consulta_usuario);

$colname_consulta_supervisores = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_consulta_supervisores = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}

mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_supervisores = "SELECT Nombre FROM supervisores WHERE categoria = 'SUPERVISOR' AND zona='ASALTO' ORDER BY nombre ASC";
$consulta_supervisores = mysql_query($query_consulta_supervisores, $conexion_usuarios) or die(mysql_error());
$row_consulta_supervisores = mysql_fetch_assoc($consulta_supervisores);
$totalRows_consulta_supervisores = mysql_num_rows($consulta_supervisores);
?>


<?php
   //Define la zona horaria exacta para cada pais.
   date_default_timezone_set('America/Mexico_City');
  
  //Hora Automatica
   $currentTime = date("H:i"); 
   
   // Fecha Automatica
   $fechaT = date("d/m/Y");
   
?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script language="javascript" src="jquery/popcalendar.js"></script>
<script src="http://j.maxmind.com/app/geoip.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<link rel="stylesheet" href="tabla.css" />
<script>
jQuery(function($){
   $("#dateArrival").mask("99/99/9999");
   $("#inicio").mask("99:99"); 
   

});
</script>


<script>
function readURL(input) {
if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function (e) {
$('#img_prev')
.attr('src', e.target.result)
.width(120)
.height(150);
};

reader.readAsDataURL(input.files[0]);
}
}
</script>

<script>

function OnChangeOption(opc) {
if (opc.value=="SI"){



divTCancelacion = document.getElementById("TDinero");
divTCancelacion.style.display = "";

divTFechaCancelacion = document.getElementById("dinero");
divTFechaCancelacion.style.display = "";

}else{

divTCancelacion = document.getElementById("TDinero");
divTCancelacion.style.display = "none";

divTFechaCancelacion = document.getElementById("dinero");
divTFechaCancelacion.style.display = "none";
   }
}
</script>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css' />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?sensor=true'></script>
<script language='JavaScript' type='text/javascript'>
	function informacion (coordenadas) {
		$('#latitude').html(coordenadas.Lat);
		$('#longitude').html(coordenadas.Lng)
	}
	function initialize() {
		var coordenadas = {
			Lat: 0,
			Lng: 0
		};
		
		function localizacion (posicion) {
			coordenadas = {
				Lat: posicion.coords.latitude,
				Lng: posicion.coords.longitude
			}
			informacion(coordenadas);
			var mapOptions = {
				zoom: 17,
				center: new google.maps.LatLng(coordenadas.Lat, coordenadas.Lng),
				disableDefaultUI: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
			var infowindow = new google.maps.InfoWindow({
				map: map,
				position: new google.maps.LatLng(coordenadas.Lat, coordenadas.Lng),
				content: 'Ubicación de emergencia'
            });
		}
		function errores (error) {
			alert('Ha ocurrido un error al obtener la información');
		}
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(localizacion,errores);
		} else {
			alert('Tu navegador no soporta la Geolocalización');
		}
	}
</script>

<script type="text/javascript">

  function getLocationConstant() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(onGeoSuccess, onGeoError);
        } else {
            alert("Your browser or device doesn't support Geolocation");
        }
    }
    function onGeoSuccess(event) {
        document.getElementById("Latitude").value = event.coords.latitude;
        document.getElementById("Longitude").value = event.coords.longitude;

    }
    function onGeoError(event) {
        alert("Error code " + event.code + ". " + event.message);
    }
/////
function getGeo(){

if (navigator && navigator.geolocation) {
navigator.geolocation.getCurrentPosition(geoOK, geoKO);
} else {
geoMaxmind();
}

}

function geoOK(position) {
showLatLong(position.coords.latitude, position.coords.longitude);
}
function geoMaxmind() {
showLatLong(geoip_latitude(), geoip_longitude());
}

function geoKO(err) {
if (err.code == 1) {
error('El usuario ha denegado el permiso para obtener informacion de ubicacion.');
} else if (err.code == 2) {
error('Tu ubicacion no se puede determinar.');
} else if (err.code == 3) {
error('TimeOut.')
} else {
error('No sabemos que pasó pero ocurrio un error.');
}
}

function showLatLong(lat, longi) {
var geocoder = new google.maps.Geocoder();
var yourLocation = new google.maps.LatLng(lat, longi);
geocoder.geocode({ 'latLng': yourLocation },processGeocoder);

}
function processGeocoder(results, status){

if (status == google.maps.GeocoderStatus.OK) {
if (results[0]) {
document.forms[0].direccion.value=results[0].formatted_address;
} else {
error('Google no retorno resultado alguno.');
}
} else {
error("Geocoding fallo debido a : " + status);
}
}
function error(msg) {
alert(msg);
}

<!--
var statSend = false;

function checkSubmit() {

	if (!statSend) {

		statSend = true;

		return true;

	} else {

		alert("REGISTRO EN PROCESO... ESPERE!");

		return false;

	}

}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' Debe de contener numero.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' - campo requerido.\n'; }
  } if (errors) alert('Validar los datos:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body onload='initialize()'>

<div class="datagrid"><form method="post" name="form1" enctype="multipart/form-data" onsubmit="return checkSubmit();" action="<?php echo $editFormAction; ?>">
<table>
<thead>
<tr><th> FOLIO: <span >
        <?php 
      $nom ="S";
	  $registros =1+$row_supervision['id'];
	  echo $nom." ".$registros."";
	   ?>
	  </span>   
	  <img src="images/Imagen1.png" width="60%" />
	   </th><th>SUPERVISION A LAS MEDIDAS DE SEGURIDAD A VENDEDORES EN RUTA</th>
<th>HORA DE INICIO: <input type="text" id="hora_inicio" name="hora_inicio" value="<?php echo $currentTime; ?>"  readonly="readonly"/> </th><th>ELABORACI&Oacute;N: <input type="text" id="fecha_elaboracion" name="fecha_elaboracion" value="<?php echo $fechaT; ?>" readonly="readonly"  /></th></tr></thead>
<tbody>
<tr class="alt"><td>NOMBRE DEL VENDEDOR <br />
NUMERO DE EMPLEADO<br>RUTA<br />CEVE<br />MARCA</td>
  <td><input type="text" id="nom_vendedor" name="nom_vendedor" onchange="javascript:this.value=this.value.toUpperCase();" style="width:100%"/><br /><input type="text" id="num_vendedor" name="num_vendedor" onchange="javascript:this.value=this.value.toUpperCase();" style="width:100%"/><br />
    <input type="text" id="ruta" name="ruta" onchange="javascript:this.value=this.value.toUpperCase();" style="width:100%"/>
    <br />
  <select name="ceve" id="ceve">
          <option value="">- ELIGE -</option>
          <?php
do {  
?>
          <option value="<?php echo $row_consulta_ceve['ceve']?>"> <?php echo $row_consulta_ceve['ceve']?></option>
          <?php
} while ($row_consulta_ceve = mysql_fetch_assoc($consulta_ceve));
  $rows = mysql_num_rows($consulta_ceve);
  if($rows > 0) {
      mysql_data_seek($consulta_supervisores, 0);
	  $row_consulta_ceve = mysql_fetch_assoc($consulta_ceve);
  }
?>
      </select><br /><select id="marca" name="marca" >
	  <option value="- ELIGE -">- ELIGE -</option>
<option value="BIMBO">BIMBO</option>
<option value="BARCEL">BARCEL</option>
</select></td>
<td>FOTO </td>
      <td><p><img id="Imagen" src="#" width="1" height="1" alt="IMAGEN PREVIA" title="VISTA PREVIA"/>
		</p>
            <input type="file" name="Imagen" id="Imagen" onchange="readURL(this);"/>
		<!--accept="image/jpeg, image/png, image/gif" ></td> --> </tr>
<tr><td colspan="4"><center>UBICACION</center></td></tr>
<tr class="alt">
<td>DOMICILIO<br /> <br /><br /><br />LATITUD <br /><br /><br /><br />LONGITUD</td>

<td><br /><input type="text" name="direccion" id="direccion" style="width:95%"  onchange="javascript:this.value=this.value.toUpperCase();"/> <br /><br /><br /><br /><input type="text" id="Latitude" name="Latitude" value="" style="width:95%" readonly="readonly" onchange="javascript:this.value=this.value.toUpperCase();"><br /><br /><br /><br /><input type="text" id="Longitude" name="Longitude" value="" style="width:95%" readonly="readonly" onchange="javascript:this.value=this.value.toUpperCase();"><br /><center><input type="button" value="UBICAR" onclick="getLocationConstant(); getGeo()" /></center></td>
<td colspan="2">
<!--  
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>  
  
<script>  
function success(position) {  
 var status2 = document.querySelector('#status2');  
 
  
 var mapcanvas = document.createElement('div');  
 mapcanvas.id = 'mapcanvas';  
 mapcanvas.style.height = '300px';  
 mapcanvas.style.width = '380px';  
  
 document.querySelector('#map').appendChild(mapcanvas);  
  
 var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);  
 var myOptions = {  
 zoom: 16,  
 center: latlng,  
 mapTypeControl: false,  
 navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},  
 mapTypeId: google.maps.MapTypeId.ROADMAP  
 };  
 var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);  
  
 var marker = new google.maps.Marker({  
 position: latlng,  
 map: map,  
 title:"Usted está aquí."  
 });  
}  
  
function error(msg) {  
 var status = document.getElementById('status2');  
    
}  
  
if (navigator.geolocation) {  
 navigator.geolocation.getCurrentPosition(success, error,{maximumAge:60000, timeout: 4000});  
} else {  
 error('Su navegador no tiene soporte para su geolocalización');  
}  
  
</script>  
  
<p id="status2"></p>  
<center>
<div id="map"></div> 
</center> --></td>
</tr>
<tr><td colspan="4"><center>EVALUACION</center></td></tr>
<tr class="alt">
<td>CIERRA LAS PUERTAS DEL VEH&Iacute;CULO CON LLAVE</td>
<td><input type="radio" name="cierra_puertas" id="cierra_puertas" value="SI"/>SI <input type="radio" name="cierra_puertas" id="cierra_puertas" value="NO" />NO</td>  
<td>CONOCE LAS ZONAS PELIGROSAS EN SU RUTA</td>
<td><input type="radio" name="conoce_zonas" id="conoce_zonas" value="SI"/>SI <input type="radio" name="conoce_zonas" id="conoce_zonas" value="NO" />NO</td>
</tr>
<tr>
<td>RETIRA LAS LLAVES DEL SWITCH</td>
<td><input type="radio" name="retira_llaves" id="retira_llaves" value="SI"/>SI <input type="radio" name="retira_llaves" id="retira_llaves" value="NO" />NO </td>
<td>DINERO FUERA DE LA CAJA DE SEGURIDAD $</td>
<td><input type="radio" name="dinero_fuera" id="dinero_fuera" value="SI" onchange="OnChangeOption(this);"/>SI <input type="radio" name="dinero_fuera" id="dinero_fuera" value="NO" onchange="OnChangeOption(this);"/>NO</td>
</tr>
<tr class="alt">
<td>EXHIBE EL DINERO</td>
<td><input type="radio" name="exhibe_dinero" id="exhibe_dinero" value="SI"/>SI <input type="radio" name="exhibe_dinero" id="exhibe_dinero" value="NO" />NO </td>
<td>DEPOSITA CONTUNUAMENTE</td>
<td>
<input type="radio" name="deposita_continuamente" id="deposita_continuamente" value="SI" />SI <input type="radio" name="deposita_continuamente" id="deposita_continuamente" value="NO" />NO </td>
</tr>
<tr>
<td>SE ENCUENTRA DENTRO DE SU RUTA</td>
<td>
<input type="radio" name="dentro_ruta" id="dentro_ruta" value="SI"/>SI <input type="radio" name="dentro_ruta" id="dentro_ruta" value="NO" />NO</td>
<td>CUENTA CON TODOS LOS DISPOSITIVOS DE SEGURIDAD</td>
<td>
<input type="radio" name="dispositivos_seguridad" id="dispositivos_seguridad" value="SI"/>SI <input type="radio" name="dispositivos_seguridad" id="dispositivos_seguridad" value="NO" />NO</td>
</tr>
<tr class="alt">
<td >HACE PARADAS INECESARIAS</td>
<td>
<input type="radio" name="paradas_inecesarias" id="paradas_inecesarias" value="SI"/>SI <input type="radio" name="paradas_inecesarias" id="paradas_inecesarias" value="NO" />NO </td>
<td>SABE LOS # DE EMEPRGENCIA REPORTE DE ROBO (CM)</td>
<td>
<input type="radio" name="numero_cm" id="numero_cm" value="SI"/>SI <input type="radio" name="numero_cm" id="numero_cm" value="NO" />NO</td>
</tr>
<tr>
<td>PORTA ALAHJAS U OBJETOS LLAMATIVOS</td>
<td><input type="radio" name="llamativos" id="llamativos" value="SI"/>SI <input type="radio" name="llamativos" id="llamativos" value="NO" />NO</td>
<td>TRAE PERSONAS AJENAS ABORDO DEL VEHICULO</td>
<td><input type="radio" name="personas_ajenas" id="personas_ajenas" value="SI"/>SI <input type="radio" name="personas_ajenas" id="personas_ajenas" value="NO" />NO </td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><div id="TDinero" style="display: none;">CANTIDAD DE DINERO FUERA</div></td>
      <td><div id="dinero" style="display: none;"><input name="dinero" type="text" id="dinero" value="0" size="42" onchange="javascript:this.value=this.value.toUpperCase();" onclick="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"/></div></td>
</tr>
<tr class="alt">
<td colspan="4">OBSERVACIONES</td>
</tr>
<tr>
<td colspan="4"><textarea id="observaciones" name="observaciones" style="width:100%" onchange="javascript:this.value=this.value.toUpperCase();"></textarea></td>
</tr>
<tr class="alt">
<td colspan="4">EN CASO DE TENER UNA O MAS DESVIACIONES QUE PUEDO HACER PARA AYUDARLE A SOLUCIONAR ESTE ASPECTO</td>
</tr>
<tr>
<td colspan="4"><textarea id="comentarios_vendedor" name="comentarios_vendedor" style="width:100%" onchange="javascript:this.value=this.value.toUpperCase();"></textarea></td>
</tr>
<tr class="alt">
<td>ATENDIO</td>
<td><select name="atendio" id="atendio">
          <?php
do {  
?>
          <option value="<?php echo $row_consulta_usuario['Nombre']?>"<?php if (!(strcmp($row_consulta_usuario['Nombre'], $row_consulta_usuario['Nombre']))) {echo "selected=\"selected\"";} ?>><?php echo $row_consulta_usuario['Nombre']?></option>
          <?php
} while ($row_consulta_usuario = mysql_fetch_assoc($consulta_usuario));
  $rows = mysql_num_rows($consulta_usuario);
  if($rows > 0) {
      mysql_data_seek($consulta_usuario, 0);
	  $row_consulta_usuario = mysql_fetch_assoc($consulta_usuario);
  }
?>
        </select></td>
<td colspan="3"><div align="center">
<input name="submit" type="submit" class="button themed" onclick="MM_validateForm(
		  'nom_vendedor','','R',
		  'num_vendedor','','R',
		  'ruta','','R',
		  'marca','','R',
		  'ceve','','R',
		  'direccion','','R',
		  'Latitude','','R',
		  'Longitude','','R',
		  'cierra_puertas','','R',
		  'conoce_zonas','','R',
		  'retira_llaves','','R',
		  'dinero_fuera','','R',
		  'deposita_continuamente','','R',
		  'dentro_ruta','','R',
		  'dispositivos_seguridad','','R',
		  'paradas_inecesarias','','R',
		  'numero_cm','','R',
		  'llamativos','','R',
		  'personas_ajenas','','R',
		  'observaciones','','R',
		  'comentarios_vendedor','','R',
		  'fecha_elaboracion','','R',
		  'hora_inicio','','R',
		  'atendio','','R');return document.MM_returnValue;$sql;return document.MM_returnValue" value="SIGUENTE" />
</div></td>
</tr>
</table>
 <input type="hidden" name="MM_insert" value="form1">
 </form>
 </div>
</body>
</html>
