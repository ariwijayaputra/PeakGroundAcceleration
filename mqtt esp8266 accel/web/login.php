
<!DOCTYPE html>
<html style=" height: 100%;">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet"></head>
<body style="
    background-image: url('./blueAbstract.jpg'); 
    height: 100%; 
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;">
    <div class="d-flex justify-content-center container-fluid h-100" >
        <div class="col-lg-6 my-auto mx-auto">
            <div class="card w-75 mx-auto border-0">
                <div class="card-header">
                    <h3 class="py-0 text-center mb-0">Log in</h3>
                </div>
                <div class="card-body">
                    <form action="action.php?action=auth" method="POST">
                        <div class="form-group">
                          <label for="admin">Username Admin</label><br>
                          <input type="text" class= "form-control" id="admin" name="admin" value="">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label><br>
                            <input type="password" class= "form-control" id="password" name="password" value="">
                        </div>
                    </div> 
                <input type="submit" class="btn btn-dark bg-dark card-footer" name="Login" value="Login">
                </form>
            </div>
        </div>
    </div>
</body>
</html>