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
	<script src="./websocket.js"></script>
    <script src="./config.js"></script>
</head>
<body>
    <h1 class = "mx-auto my-3 text-primary text-center">PGA</h1>
    <table id = "mytable" class="table table-striped table-responsive text-center mx-auto w-75">
        <thead>
            <tr>
                <th>Date Time</th>
                <th>PGA (g)</th>
                <th>X Accel (g)</th>
                <th>Y Accel (g)</th>
                <th>Perceived Shaking</th>
                <th>Potensial Damage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="date">12/07/21 - 11:30:11</td>
                <td id="pga">3.00</td>
                <td id="x">2.00</td>
                <td id="y">2.00</td>
                <td id="shake">Not Felt</td>
                <td id="damage">None</td>
            </tr>
            <tr>
                <td>12/07/21 - 11:30:11</td>
                <td>2.00</td>
                <td>2.00</td>
                <td>0.00</td>
                <td>Not Felt</td>
                <td>None</td>
            </tr>
            <tr>
                <td>12/07/21 - 11:30:11</td>
                <td>2.00</td>
                <td>2.00</td>
                <td>0.00</td>
                <td>Not Felt</td>
                <td>None</td>
            </tr>
        </tbody>
    </table>
</body>
</html>