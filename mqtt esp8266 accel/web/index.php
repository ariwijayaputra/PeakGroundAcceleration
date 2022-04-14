<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PGA</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js" integrity="sha512-Lii3WMtgA0C0qmmkdCpsG0Gjr6M0ajRyQRQSbTF6BsrVh/nhZdHpVZ76iMIPvQwz1eoXC3DmAg9K51qT5/dEVg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
	
</head>
<body>
    <h1 class = "mx-auto my-3 text-primary text-center">PGA</h1>
    <div class="d-flex flex-row bd-highlight mb-3">
        <table class="w-75 mx-auto table table-striped text-center">
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
            <tr>
                <td id="date">dd/mm/yy - hh:mm:ss</td>
                <td id = "intensity">I</td>
                <td id="pga">0.000000</td>
                <td id="v">0.000000</td>
                <td id="shake">Not Felt</td>
                <td id="damage">None</td>
            </tr>
        </tbody>
        </table>
        
    </div>
    <div class="mx-auto w-75">
        <canvas id="graph" class="w-75 mw-100 h-25 mx-auto" style = ""></canvas>

    </div>
    <script src="./grafik.js"></script>

    <h1 class= "mx-auto text-center my-3 mt-4">History</h1>
    <table id = "mytable" class="table table-striped table-responsive text-center mx-auto w-75">
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
        	 include 'database.php';
  			  $db = new database;
              $dt = new pga_data;
              $i=0;
              foreach ($dt->select() as $x) { 
                  if($i<10){
                      echo "
                      <script>
                        dataPGA[9-".$i."]='".$x['pga']."';
                        dataLabel[9-".$i."]='".$x['timestamp']."';
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
                    echo "<script>reverseData()</script>"
                ?>
          	</tr>
        </tbody>
    </table>
</body>
<script src="./websocket.js"></script>
<script src="./config.js"></script>

</html>