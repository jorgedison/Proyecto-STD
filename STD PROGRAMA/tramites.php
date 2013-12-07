<?php
require 'libs/Smarty.class.php';
require 'generales/classes.php';

  include('config.php');
  if (!$login->logged())
  {
    header("location: index.php");
    die();
  }

$idcur=$_REQUEST[idcur];
$idu=$_REQUEST[idu];

$idu=$_SESSION['cf_login_id'];
$cu=$_SESSION['cf_login_username']; 
$nu=$_SESSION['cf_login_privilege'];
$tu=$_SESSION['cf_login_lastactive'];
$gu=$_SESSION['cf_login_grupousuario'];

$codigofac=$_REQUEST['codigofac'];
$codigoope=$_REQUEST['codigoope'];

$smarty = new Smarty; 
$smarty->compile_check =true; 
#$smarty->debugging = true;

$creportes=new creportes;

$consultax= $creportes->creportestockope();
$x=0;
while($row = mysql_fetch_assoc($consultax)){
	$nombreope[]=$row['nombreope'];
	$io[]=$row['io'];
	//if($row['cont']==0)
	$conti[]=$row['conti'];
}

$smarty->assign ("idu", $idu);
$smarty->assign ("cu", $cu);
$smarty->assign ("nu", $nu);
$smarty->assign ("tu", $tu);
$smarty->assign ("gu", $gu);

$smarty->assign ("nombreope", $nombreope);
$smarty->assign ("io", $io);
$smarty->assign ("conti", $conti);
$smarty->assign ("nf", $nf);
$smarty->assign ("na", $na);

$smarty->assign ("hea","header.tpl");
$smarty->assign ("menusu","menusuperior.tpl");
$smarty->assign ("menu","menutramites.tpl");
$smarty->assign ("inc","tramites.tpl");
$smarty->assign ("foo","footer.tpl");
$smarty->display('interface.tpl');
?>