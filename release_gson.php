<?php 
include("mysql.class.php");
include("mysql.info.php");
$db = new MySQL();
$dbf = new MySQL();
if (! $db->Open($mysql_db_xzdr, $mysql_server, $mysql_user, $mysql_password)) $db->Kill();
if (! $dbf->Open($mysql_db_xzdr, $mysql_server, $mysql_user, $mysql_password)) $dbf->Kill();
$where=' WHERE';
if(isset($_GET["device"])){
	$where .= ' device="'.$_GET["device"].'" AND (status="stable" ';
	if(isset($_GET["beta"])){
		$where .= ' OR status="beta"';
	}
	if(isset($_GET["acc"])){
		if (! $db->Query('SELECT * FROM '.$mysql_tb_accounts.' WHERE account="'.$_GET["acc"].'"')) $db->Kill();
		if ($db->RowCount() > 0){
			$where .= ' OR status="alpha"';
		}
	}
	$where .= ')';
	if (! $db->Query('SELECT * FROM '.$mysql_tb_recoveries.$where)) $db->Kill();
	for ($index = 0; $index < $db->RowCount(); $index++) {
		$row = $db->Row($index);
		$version = array('title'=>$row->title, 'release_version'=>$row->release_version, 'compile_version'=>$row->compile_version, 'status'=>$row->status, 'changelog'=>$row->changelog);
		if (! $dbf->Query('SELECT * FROM '.$mysql_tb_files.' WHERE release_id="'.$row->PID.'"')) $dbf->Kill();
		for ($indexf = 0; $indexf < $dbf->RowCount(); $indexf++) {
			$rowf = $dbf->Row($indexf);
			$files[]=array('filename'=>$rowf->filename, 'fileurl'=>$rowf->fileurl, 'instpath'=>$rowf->instpath, 'chmod'=>$rowf->chmod);
		}
		$result['result'][]=array('version'=>$version, 'files'=>$files);
	}
}
if(!empty($result)){
	echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}
if (! $db->Close()) $db->Kill();
if (! $dbf->Close()) $dbf->Kill();
?>
