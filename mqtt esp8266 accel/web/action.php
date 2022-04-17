<?php
	require_once 'database.php';
	$action = $_GET['action'];
	if ($action == 'insertData') {
		$pga_data = new pga_data;
		$timestamp = $_POST["dt_timestamp"];
		$pga = $_POST["dt_pga"];
        $intensity = $_POST["dt_intensity"];
		$velocity = $_POST["dt_v"];
		$shake = $_POST["dt_shake"];
		$damage = $_POST["dt_damage"];
		$pga_data->add($timestamp, $intensity, $pga, $velocity,$shake,$damage);
	}
	if ($action == 'download') {
		$pga_data = new pga_data;
		$pga_data->download();
	}
	if ($action == 'auth') {
		$admin = new admin();
		$username=$_POST["admin"];
		$password=$_POST["password"];
		$admin->auth($username, $password);
	}
	if ($action == 'edit') {
		$admin = new admin();
		$username=$_POST["username"];
		$current=$_POST["current"];
		$new=$_POST["new"];
		$confirm=$_POST["confirm"];
		$admin->edit($username, $current, $new, $confirm);
	}
?>