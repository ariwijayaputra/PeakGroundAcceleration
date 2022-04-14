<?php
	class database
	{
		var $servername="localhost";
		var $username="root";
		var $password="";
		var $dbname="pga";
		
		public function connect(){
			$conn="";
			$this->$conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname) or die("Couldn't connect");
			return $this->$conn;
		}
	}
	class pga_data extends database{
		public function add($timestamp, $intensity, $pga, $velocity, $shake,$damage)
		{
			$conn = $this->connect();
			$sql = "INSERT INTO tbPGA(`timestamp`,`intensity`, `pga`, `velocity`, `shaking`, `damage`) VALUES(?,?,?,?,?,?)";
			$result=$conn->prepare($sql);
			$result->bind_param("ssssss", $timestamp, $intensity, $pga, $velocity, $shake,$damage);
			$result->execute();
			mysqli_stmt_store_result($result);
			if ($result) {
				echo $timestamp;
			}
			else{
				echo "
					<script>
						alert('Data Gagal Disimpan');
						window.location.href='index.php';
					</script>
				";
			}
		}
		public function select()
		{
			$conn = $this->connect();
			$sql = "SELECT * FROM `tbPGA` ORDER BY `tbPGA`.`id` DESC LIMIT 20";
			$data=$conn->query($sql)or die($conn->error);
			while($d = $data->fetch_assoc()){
				$result[] = $d;
			}
			return $result;
		}
	}
?>