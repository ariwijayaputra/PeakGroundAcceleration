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
		$pga_data->add($timestamp, $intensity, $pga, $velocity, $shake,$damage);
	}
?>