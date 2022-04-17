<?php 
session_start();
if (!isset($_SESSION["username"])) {
		//header('location:login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Password</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>
<body>
	<div class="container p-0 row mx-auto mt-5 mb-3">
		<div class="col p-0">
			<h3><?php echo $_SESSION["username"] ?></h3>
            <h5>Edit Username and Password</h5>
		</div>
	</div>
	<div class="container card mx-auto p-3">
		<form action="action.php?action=edit" method="POST">
            <div class="form-group">
			  <label for="username">Username:</label><br>
			  <input type="text" class= "form-control" id="username" name="username" value="<?php echo $_SESSION["username"]?>">
			</div>
			<div class="form-group">
			  <label for="current">Password saat ini:</label><br>
			  <input type="password" class= "form-control" id="current" name="current" >
			</div>
			<div class="form-group mt-5">
			  <label for="new">Password baru:</label><br>
			  <input type="password" class= "form-control" id="new" name="new" placeholder="Kosongkan jika tidak mengubah password" >
			</div>
			<div class="form-group">
			  <label for="confirm">Konfirmasi Password baru</label><br>
			  <input type="password" class= "form-control" id="confirm" name="confirm" placeholder="Kosongkan jika tidak mengubah password" >
			</div>
			<div class="d-flex justify-content-center mt-5">
				<a class="btn btn-danger m-2"  href="index.php" role="button">Cancel</a>	
				<input type="submit" class="btn btn-success m-2" name="Simpan" value="Simpan">
			</div>
		</form>
	<div>
	
</body>
</html>