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

		public function download(){
			// Load the database configuration file 
			$db = $this->connect();

			// Fetch records from database 
			$query = $db->query("SELECT * FROM tbPGA ORDER BY id DESC")or die($conn->error); 
			
			if($query->num_rows > 0){ 
				$delimiter = ","; 
				$filename = "PGA-data_" . date('Y-m-d') . ".csv"; 
				
				// Create a file pointer 
				$f = fopen('php://memory', 'w'); 
				
				// Set column headers 
				$fields = array('ID', 'TIMESTAMP', 'INTENSITY', 'PGA', 'VELOCITY', 'SHAKING', 'DAMAGE'); 
				fputcsv($f, $fields, $delimiter); 
				
				// Output each row of the data, format line as csv and write to file pointer 
				while($row = $query->fetch_assoc()){ 
					$lineData = array($row['id'], $row['timestamp'], $row['intensity'], $row['pga'], $row['velocity'], $row['shaking'], $row['damage']); 
					fputcsv($f, $lineData, $delimiter); 
				} 
				
				// Move back to beginning of file 
				fseek($f, 0); 
				
				// Set headers to download file rather than displayed 
				header('Content-Type: text/csv'); 
				header('Content-Disposition: attachment; filename="' . $filename . '";'); 
				
				//output all remaining data on a file pointer 
				fpassthru($f); 
			} 
			exit; 

		}
	}

	class admin extends database
	{
		public function auth($username, $password)
		{
			session_start();
			$conn = $this->connect();
			$sql = "SELECT * FROM admin WHERE username=
			'".$username."' AND password= '".$password."'";
			$result = $conn->query($sql)
			or die($conn->error);
			if($d = $result->fetch_assoc()){
				$_SESSION["username"]=$username;
				header('location:index.php');
			}
			else{
				echo "
				<script>
					alert('Akun tidak ditemukan!');
					window.location.href='login.php';
				</script>
				";
			}
			return $result;
		}
		public function edit($newUsername, $current, $newPass, $confirm)
		{
			session_start();
			$username = $_SESSION["username"];
			$flag = 0;
			$conn = $this->connect();
			$sql = "SELECT * FROM admin WHERE username=
			'".$username."' AND password= '".$current."'";
			$result = $conn->query($sql)
			or die($conn->error);
			if($d = $result->fetch_assoc()){
				$flag = 1;
			}
			if($newPass == $confirm && $flag == 1 && $newPass!=""){
				$sql = "UPDATE admin SET password = ?, username = ?  WHERE username = ?";
				$result=$conn->prepare($sql);
				$result->bind_param("sss",$newPass,$newUsername,$username);
				$result->execute();
				mysqli_stmt_store_result($result);
				if ($result) {
					echo "
						<script>
							alert('Data berhasil diubah');
							window.location.href='login.php';
						</script>
					";
				}
				else{
					echo "
						<script>
							alert('Data Gagal Disimpan');
							window.location.href='changePassword.php';
						</script>
					";
				}
				$flag = 0;
			}
			if($newPass == ""){
				$sql = "UPDATE admin SET username = ?  WHERE username = ?";
				$result=$conn->prepare($sql);
				$result->bind_param("ss",$newUsername,$username);
				$result->execute();
				mysqli_stmt_store_result($result);
				if ($result) {
					echo "
						<script>
							alert('Data berhasil diubah');
							window.location.href='login.php';
						</script>
					";
				}
				else{
					echo "
						<script>
							alert('Data Gagal Disimpan');
							window.location.href='changePassword.php';
						</script>
					";
				}
				$flag = 0;
			}
			else if($newPass!=$confirm){
				echo "
						<script>
							alert('Konfirmasi Password Salah');
							window.location.href='changePassword.php';
						</script>
					";
			}
			else if ($flag!=1){
				echo "
				<script>
					alert('Password Saat Ini Salah');
					window.location.href='changePassword.php';
				</script>
				";
			}
			
		}
	}
?>