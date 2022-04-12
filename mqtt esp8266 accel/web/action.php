<?php
	require_once 'database.php';
	$action = $_GET['action'];
	if ($action == 'insertData') {
		$pga_data = new pga_data;
		$timestamp = $_POST["dt_timestamp"];
		$pga = $_POST["dt_pga"];
        $x = $_POST["dt_x"];
		$y = $_POST["dt_y"];
		$shake = $_POST["dt_shake"];
		$damage = $_POST["dt_damage"];
		$pga_data->add($timestamp, $pga, $x,$y,$shake,$damage);
	}
?>