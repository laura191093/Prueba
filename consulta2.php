<?php require_once('connections/conexion_usuarios.php'); ?>
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
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
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
$colname_consulta_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_consulta_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_usuario = sprintf("SELECT Nombre FROM supervisores WHERE username = '%s'", $colname_consulta_usuario);
$consulta_usuario = mysql_query($query_consulta_usuario, $conexion_usuarios) or die(mysql_error());
$row_consulta_usuario = mysql_fetch_assoc($consulta_usuario);
$totalRows_consulta_usuario = mysql_num_rows($consulta_usuario);

$colname_consulta_supervisores = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_consulta_supervisores = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_supervisores = sprintf("SELECT Nombre FROM supervisores WHERE username = '%s'", $colname_consulta_supervisores);
$consulta_supervisores = mysql_query($query_consulta_supervisores, $conexion_usuarios) or die(mysql_error());
$row_consulta_supervisores = mysql_fetch_assoc($consulta_supervisores);
$totalRows_consulta_supervisores = mysql_num_rows($consulta_supervisores);

$colname_consulta_r_s = "-1";
if (isset($_POST['id'])) {
  $colname_consulta_r_s = (get_magic_quotes_gpc()) ? $_POST['id'] : addslashes($_POST['id']);
}
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_r_s = sprintf("SELECT s.id, s.nom_vendedor,s.num_vendedor, s.ruta, s.marca, s.ceve, s.direccion, s.Latitude, s.Longitude, s.cierra_puertas, s.conoce_zonas, s.retira_llaves, s.dentro_ruta, s.dispositivos_seguridad, s.paradas_inecesarias, s.numero_cm, llamativos, s.personas_ajenas, observaciones, s.comentarios_vendedor,s.dinero, s.hora_inicio, s.fecha_elaboracion, s.atendio from supervision_ruta s where s.num_vendedor=%s", $colname_consulta_r_s);
$consulta_r_s = mysql_query($query_consulta_r_s, $conexion_usuarios) or die(mysql_error());
$row_consulta_r_s = mysql_fetch_assoc($consulta_r_s);
$totalRows_consulta_r_s = mysql_num_rows($consulta_r_s);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="tabla.css" />
<style type="text/css">
<!--
.Estilo95 {
	color: #CC0000;
	font-weight: bold;
	font-size: 14px;
}
.Estilo97 {color: #FFFFFF}
-->
</style>
<head>
<title>Consulta</title>
<link rel="stylesheet" href="tabla.css" />

<style type="text/css">
<!--

.Estilo93 {color: #000066; font-weight: bold; }
.Estilo94 {font-size: 18px}
.Estilo96 {
	color: #990000;
	font-weight: bold;
}
-->
</style>

<script type="text/javascript">

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

<body>
<div class="datagrid">
<table >
<thead>
<tr><th><h4>.::. CONSULTA SUPERVISION .::.</h4></th></tr></thead></table></div>
<div class="datagrid">
<form id="form1" name="form1" method="post" action="">
  <p><strong>BUSCAR </strong>
    <input name="id" type="text" id="id" required placeholder="NUM. DE COLABORADOR"/>
  <strong>Presionar la tecla &quot;Enter&quot;</strong></p>

</form>
</br>
<table >
<thead>
<tr><th><div align="center">
      <p align="center"><strong>FOLIO</strong></p>
    </div></th>
	<!--<th><div align="center">
      <p align="center"><strong>ACTUALIZAR</strong></p>
    </div></th> -->
	<th><div align="center">
      <p align="center"><strong>FECHA</strong></p>
    </div></th>
	 <th><div align="center">
      <p align="center"><strong>NOMBRE DEL VENDEDOR</strong></p>
    </div></th>
<!--    <th><div align="center">
      <p align="center"><strong>NUMERO DE COLABORADOR</strong></p>
    </div></th>
	 -->
	<th><div align="center">
      <p align="center"><strong>HORA</strong></p>
    </div></th>
	
    <th><div align="center">
      <p align="center"><strong>CENTRO DE VENTAS</strong></p>
    </div></th>
	
    <th><div align="center">
      <p align="center"><strong>MARCA</strong></p>
    </div></th>
	
	<th><div align="center">
      <p align="center"><strong>DIRECCION</strong></p>
    </div></th></tr></thead>


  </tr>
  <?php do { ?>
    <tr>
	  <?php
   if(isset($row_consulta_r_s['id'])) 
   {
     ?>
      <td><div align="center"><a href="detalle.php?recordID=<?php echo $row_consulta_r_s['id']; ?>" class="Estilo94"> S<?php echo $row_consulta_r_s['id']; ?> </a></div>	  </td>
	 <?php
	}
	?>
<!--	<td>	  <div align="center"><a target="_blank" href="actualiza_supervision.php?recordID=<?php echo $row_consulta_r_s['id']; ?>"><img src="imagenes/edit.png" title="EDITAR REGISTRO" width="15" height="30" border="0"></a></div></td> -->
	<td><div align="center"><?php echo $row_consulta_r_s['fecha_elaboracion']; ?></div></td>
	  <td><div align="center"><?php echo $row_consulta_r_s['nom_vendedor']; ?></div></td>
<!--      <td><div align="center"><?php echo $row_consulta_r_s['num_vendedor']; ?></div></td> -->
	  <td><div align="center"><?php echo $row_consulta_r_s['hora_inicio']; ?></div></td>
      <td><div align="center"><?php echo $row_consulta_r_s['ceve']; ?></div></td>
      <td><div align="center"><?php echo $row_consulta_r_s['marca']; ?></div></td>
	  <td><div align="center"><?php echo $row_consulta_r_s['direccion']; ?></div></td>  
    </tr>
    <?php } while ($row_consulta_r_s = mysql_fetch_assoc($consulta_r_s)); ?>
</table>
</div>

<br/>
</body>
</html>
<?php
mysql_free_result($consulta_usuario);

mysql_free_result($consulta_supervisores);

mysql_free_result($consulta_r_s);
?>
