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

mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_Recordset1 = "SELECT * FROM supervision_ruta";
$Recordset1 = mysql_query($query_Recordset1, $conexion_usuarios) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_DetailRS1 = "-1";
if (isset($_POST['id'])) {
  $colname_DetailRS1 = (get_magic_quotes_gpc()) ? $_POST['id'] : addslashes($_POST['id']);
}
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$recordID = $_GET['recordID'];
$query_DetailRS1 = sprintf("SELECT s.id, s.nom_vendedor,s.num_vendedor, s.ruta, s.marca, s.ceve, s.direccion, s.Latitude, s.Longitude, s.cierra_puertas, s.conoce_zonas, s.retira_llaves, s.dentro_ruta, s.dispositivos_seguridad, s.paradas_inecesarias, s.numero_cm, s.llamativos, s.personas_ajenas, s.observaciones, s.comentarios_vendedor,s.dinero, s.hora_inicio, s.fecha_elaboracion, s.atendio,s.dinero_fuera,s.deposita_continuamente, s.exhibe_dinero, s.Imagen from supervision_ruta s  WHERE s.id = $recordID", $recordID);
$DetailRS1 = mysql_query($query_DetailRS1, $conexion_usuarios) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);

$colname_DetailRS1 = "-1";
if (isset($_POST['region'])) {
  $colname_DetailRS1 = (get_magic_quotes_gpc()) ? $_POST['region'] : addslashes($_POST['region']);
}
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$recordID = $_GET['recordID'];
$query_DetailRS1 = sprintf("SELECT s.id, s.nom_vendedor,s.num_vendedor, s.ruta, s.marca, s.ceve, s.direccion, s.Latitude, s.Longitude, s.cierra_puertas, s.conoce_zonas, s.retira_llaves, s.dentro_ruta, s.dispositivos_seguridad, s.paradas_inecesarias, s.numero_cm, s.llamativos, s.personas_ajenas, s.observaciones, s.comentarios_vendedor,s.dinero, s.hora_inicio, s.fecha_elaboracion, s.deposita_continuamente,s.atendio,s.dinero_fuera, s.exhibe_dinero, s.Imagen from supervision_ruta s  WHERE s.id = $recordID", $recordID);
$DetailRS1 = mysql_query($query_DetailRS1, $conexion_usuarios) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="tabla.css" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<div class="datagrid">
<table width="93%" border="1" align="center">
<thead>
<tr>
<th colspan="2" ><img src="images/Imagen1.png" width="60%" /></th>
<th colspan="2"><center><strong><h2>SUPERVISION A LAS MEDIDAS DE SEGURIDAD A VENDEDORES EN RUTA</h2></strong></center></th>
</tr>
</thead>
  <tr class="alt">
    <td colspan="2"><strong>FOLIO </strong> S<?php echo $row_DetailRS1['id']; ?><br /> <div align="right">
        FECHA: <strong>  <?php echo $row_DetailRS1['fecha_elaboracion']; ?>   </strong> | HORA DE INICIO: <strong><?php echo $row_DetailRS1['hora_inicio']; ?></strong></div></td>
    <td colspan="2"><div align="center"><strong>DATOS GENERALES</strong></div></td>
  </tr>
  <tr>
    <td rowspan="9" colspan="2"><div id="content" align="center"><a href="vendedores/<?php echo $row_DetailRS1['Imagen']; ?>"><img src="vendedores/<?php echo $row_DetailRS1['Imagen']; ?>" alt="VENDEDOR" width="160" height="260" title="CLIC PARA AMPLIAR" /></a></div></td>
    <td colspan="2">NOMBRE:<strong> <?php echo $row_DetailRS1['nom_vendedor']; ?></strong></td>
  </tr>
  <tr>
    <td colspan="2">NUM. EMPLEADO:<strong> <?php echo $row_DetailRS1['num_vendedor']; ?></strong></td>
    </tr>
  <tr>
    <td colspan="2">CENTRO DE VENTAS:<strong> <?php echo $row_DetailRS1['ceve']; ?></strong></td>
    </tr>
  <tr>
    <td colspan="2">MARCA:<strong> <?php echo $row_DetailRS1['marca']; ?></strong></td>
    </tr>
  <tr>
    <td colspan="2">RUTA:<strong> <?php echo $row_DetailRS1['ruta']; ?></strong></td>
    </tr>
  <tr>
    <td colspan="2"><center><strong>UBICACI&Oacute;N</strong></center></td>
    </tr>
  <tr>
    <td colspan="2">DIRECCION:<strong> <?php echo $row_DetailRS1['direccion']; ?></strong></td>
  </tr>
  <tr><td colspan="2">LATITUD:<strong>  <?php echo $row_DetailRS1['Latitude']; ?></strong></td></tr>
  <tr><td colspan="2">LONGITUD:<strong> <?php echo $row_DetailRS1['Longitude']; ?></strong></td> </tr>
  <tr class="alt">
    <td colspan="4"><center><strong>EVALUACI&Oacute;N</strong></center></td>
  </tr>
  <tr>
    <td>CIERRA LAS PUERTAS DEL VEH&Iacute;CULO CON LLAVE: </td><td><strong><?php echo $row_DetailRS1['cierra_puertas']; ?></strong></td>
    <td>CONOCE LAS ZONAS PELIGROSAS EN SU RUTA:</td><td><strong><?php echo $row_DetailRS1['conoce_zonas']; ?></strong></td>
  </tr>
  <tr>
    <td>RETIRA LAS LLAVES DEL SWITCH: </td><td><strong><?php echo $row_DetailRS1['retira_llaves']; ?></strong></td>
    <td>DINERO FUERA DE LA CAJA DE SEGURIDAD $: </td><td><strong><?php echo $row_DetailRS1['dinero_fuera']; ?></strong></td>
  </tr>
  <tr>
    <td>EXHIBE EL DINERO: </td><td><strong><?php echo $row_DetailRS1['exhibe_dinero']; ?></strong></td>
    <td>DEPOSITA CONTUNUAMENTE: </td><td><strong><?php echo $row_DetailRS1['deposita_continuamente']; ?></strong></td>
  </tr>
  <tr>
    <td>SE ENCUENTRA DENTRO DE SU RUTA: </td><td><strong><?php echo $row_DetailRS1['dentro_ruta']; ?></strong></td>
    <td>CUENTA CON TODOS LOS DISPOSITIVOS DE SEGURIDAD:</td><td><strong><?php echo $row_DetailRS1['dispositivos_seguridad']; ?></strong></td>
  </tr>
  <tr>
    <td>HACE PARADAS INECESARIAS: </td><td><strong><?php echo $row_DetailRS1['paradas_inecesarias']; ?></strong></td>
    <td>SABE LOS # DE EMERGENCIA REPORTE DE ROBO (CM): </td>
    <td><strong><?php echo $row_DetailRS1['numero_cm']; ?></strong></td>
  </tr>
  <tr>
    <td>PORTA ALHAJAS U OBJETOS LLAMATIVOS: </td>
    <td><strong><?php echo $row_DetailRS1['llamativos']; ?></strong></td>
    <td>TRAE PERSONAS AJENAS ABORDO DEL VEHICULO: </td><td><strong><?php echo $row_DetailRS1['personas_ajenas']; ?></strong></td>
  </tr>
  <tr>  </tr>
  <tr>  </tr>
  <tr>  </tr>
  <tr class="alt">
    <td colspan="4"><center><strong>OBSERVACIONES </strong></center>	</td>
    </tr>
  <tr>
    <td colspan="4" ><?php echo $row_DetailRS1['observaciones']; ?></td>
  </tr>

  <tr class="alt">
    <td colspan="4"><center><strong>COMENTARIOS</strong></center></td>
  </tr>
  <tr>
  <td colspan="4"><?php echo $row_DetailRS1['comentarios_vendedor']; ?></td>
  </tr>
 
  <tr>
    <td colspan="2"><center><strong>SUPERVISOR DE SEGURIDAD</strong></center></td>
    <td colspan="2"><center><strong>VENDEDOR</strong></center></td>
  </tr>
  <tr>
    <td colspan="2"><center><img src="images/firmas/firma1.png" style="width:50%"/><br /><strong><?php echo $row_DetailRS1['atendio']; ?></strong></center></td>
    <td colspan="2"><center><strong><?php echo $row_DetailRS1['nom_vendedor']; ?></strong></center></td>
  </tr>
</table>
</div>
</body>
</html>
