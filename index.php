<?php require_once('conexion_usuarios.php'); ?>
<?php error_reporting (0);?>
<?php
mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
$query_consulta_usuarios = "SELECT * FROM supervisores";
$consulta_usuarios = mysql_query($query_consulta_usuarios, $conexion_usuarios) or die('ERROR AL SELECCIONAR BASE DE DATOS: '.mysql_error());
$row_consulta_usuarios = mysql_fetch_assoc($consulta_usuarios);
$totalRows_consulta_usuarios = mysql_num_rows($consulta_usuarios);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "nivel";
  $MM_redirectLoginSuccessAdm= "principal1.html";
  $MM_redirectLoginSuccessSup = "principal1.html";
  $MM_redirectLoginFailed = "ingreso1.php"; 
  
  

  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conexion_usuarios, $conexion_usuarios);
  	
  $LoginRS__query=sprintf("SELECT username, password, nivel FROM supervisores WHERE username='%s' AND password='%s' AND estatus='ACTIVO'",
  get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $conexion_usuarios) or die('FALLO CONEXIï¿½N CON LA BASE DE DATOS: '.mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  
  if ($loginFoundUser) {  
  $loginStrGroup  = mysql_result($LoginRS,0,'nivel');
 

    //declare two session variables and assign them
	
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccessAdm= $_SESSION['PrevUrl'];
	  	
    }
	
  	if($loginStrGroup==1) { 
	header("Location: " . $MM_redirectLoginSuccessAdm);  
	}
	
	if($loginStrGroup==2) { 
	header("Location: " . $MM_redirectLoginSuccessSup  );
	}  
	   
	 
	if($loginStrGroup==3) {	
	header("Location: " . $MM_redirectLoginSuccessTec  ); 
	}
	
	if($loginStrGroup==4) {	
	header("Location: " . $MM_redirectLoginSuccessInv ); 
	}		
	if($loginStrGroup==5) {	
	header("Location: " . $MM_redirectLoginSuccessON  ); 
	}		
	if($loginStrGroup==6) {	
	header("Location: " . $MM_redirectLoginSuccessLAC  ); 
	}	
	
	
  }
  else {
		echo"<script>alert('USUARIO O CONTRASEÑA INCORRECTA VERIFIQUE!');
               window.location.href=\"index.html\"</script>"; 
			   
			   
  	}
}

?>