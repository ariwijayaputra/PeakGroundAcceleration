<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGA | Dashboard </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js" integrity="sha512-Lii3WMtgA0C0qmmkdCpsG0Gjr6M0ajRyQRQSbTF6BsrVh/nhZdHpVZ76iMIPvQwz1eoXC3DmAg9K51qT5/dEVg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
    <?php
        session_start();
        include 'database.php';
        $db = new database;
        $dt = new pga_data;
		if(!isset($_SESSION["username"])){
            header('Location: login.php');  
		}
    ?>
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Dashboard</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <h6 class="mt-2">
                <span id="date" class="float-sm-right mr-3">dd/mm/yy hh:mm:ss</span>
        </h6>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">PGA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="pages/changePassword.php" class="d-block"><?php echo $_SESSION["username"] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/changePassword.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
              <p>
                User
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-door-open"></i>
              <p>
                Log Out
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper pt-3">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 ">
            <!-- Intensity -->
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-exclamation"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Intensity</span>
                    <span id="intensity" class="info-box-number">I</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- Acceleration (g) -->
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-chart-line"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Acceleration (g)</span>
                    <span id="pga" class="info-box-number">0.000000</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- Velocity (cm/s) -->
            <div class="info-box">
                <span class="info-box-icon bg-primary "><i class="material-icons" style="font-size:36px;">speed</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Velocity (cm/s)</span>
                    <span id="v" class="info-box-number">0.000000</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- Perceived Shaking -->
            <div class="info-box">
                <span class="info-box-icon bg-primary "><i class="material-icons" style="font-size:36px;">broken_image</i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Perceived Shaking</span>
                    <span id="shake" class="info-box-number">Not Felt</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- Potensial Damage -->
            <div class="info-box">
                <span class="info-box-icon bg-primary "><i class="material-icons" style="font-size:36px;">landslide</i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Potential Damage</span>
                    <span id="damage" class="info-box-number">None</span>
                </div>
                <!-- /.info-box-content -->
            </div>
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-8 ">
            <!-- chart -->
            <div class="card">
              <div class="card-header border-0">
                  <h3 class="card-title">Peak Ground Acceleration (g)</h3>
              </div>
              <div class="card-body">

                <div class="position-relative mb-4">
                  <canvas id="graph"></canvas>
                </div>
                <script src="./grafik.js"></script>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">History</h3>
                        <div class="card-tools">
                            <a href="action.php?action=download" class="btn btn-tool btn-success btn-sm">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id = "mytable" class="table table-striped text-center mx-auto">
                            <thead>
                                <tr>
                                    <th>Date Time</th>
                                    <th>Instrumental Intensity</th>
                                    <th>Acceleration (g)</th>
                                    <th>Velocity (cm/s)</th>
                                    <th>Perceived Shaking</th>
                                    <th>Potensial Damage</th>
                                </tr>
                            </thead>  
                            <tbody>
                            <?php
                                $i=0;
                                foreach ($dt->select() as $x) { 
                                    if($i<10){
                                        echo "
                                        <script>
                                            timestamp ='".$x['timestamp']."';
                                            stringData = timestamp.split(' ');
                                            time = stringData[1];
                                            dataPGA[9-".$i."]='".$x['pga']."';
                                            dataLabel[9-".$i."]=time;
                                        </script>";
                                        $i++;
                                        }
                            ?>
                                <tr>
                                <td><?php echo $x['timestamp']; ?></td>
                                <td><?php echo $x['intensity']; ?></td>
                                <td><?php echo $x['pga']; ?></td>
                                <td><?php echo $x['velocity']; ?></td>
                                <td><?php echo $x['shaking']; ?></td>
                                <td><?php echo $x['damage'];} ?></td>
                                    <?php
                                        echo '
                                        <script>
                                            reverseData();
                                            $("#date").html("'.$x['timestamp'].'"); 
                                            $("#pga").html("'.$x['pga'].'"); 
                                            $("#v").html("'.$x['velocity'].'"); 
                                            $("#shake").html("'.$x['shaking'].'"); 
                                            $("#damage").html("'.$x['damage'].'");
                                            $("#intensity").html("'.$x['intensity'].'");
                                        </script>'
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            
            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
 
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard3.js"></script>
<script src="./websocket.js"></script>
<script src="./config.js"></script>
</body>
</html>
