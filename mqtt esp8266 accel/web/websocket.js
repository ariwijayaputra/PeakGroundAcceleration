var mqtt;
var stringData;
var date, pga, x, y, shake, damage;

//fungsi untuk melakukan koneksi dengan broker
//berdasarkan identitas dari broker seperti host, port, path, username dan password
$( document ).ready(function() {
  var reconnectTimeout = 2000;
  function MQTTconnect() {
  if (typeof path == "undefined") {
      path = '/mqtt';
  }
  mqtt = new Paho.MQTT.Client(
    host,
    port,
    path,
    "web_" + parseInt(Math.random() * 100, 10)
  );
      var options = {
          timeout: 3,
          useSSL: useTLS,
          cleanSession: cleansession,
          onSuccess: onConnect,
          onFailure: function (message) {
              $('#status').val("Connection failed: " + message.errorMessage + "Retrying");
              setTimeout(MQTTconnect, reconnectTimeout);
          }
      };
      mqtt.onConnectionLost = onConnectionLost;
      mqtt.onMessageArrived = onMessageArrived;

      if (username != null) {
          options.userName = username;
          options.password = password;
      }
      mqtt.connect(options);
  }
  
  //fungsi untuk mengecek koneksi dengan broker
  function onConnect() {
		$('#status').html('Host: ' + host + ':' + port + path);
		mqtt.subscribe(topic, {qos: 0});
		console.log("onConnect");
  }
  function postData() {
      $.post('action.php?action=insertData',{
          dt_timestamp: date,
          dt_pga: pga, 
          dt_x: x,
          dt_y: y,
          dt_shake: shake, 
          dt_damage: damage
        })
          .done(function(data){
          console.log(data);
        });
  }
  //fungsi jika koneksi putus dengan broker
  function onConnectionLost(responseObject) {
    if (responseObject.errorCode !== 0) {
      console.log("onConnectionLost:"+responseObject.errorMessage);
    }
  }
  //fungsi untuk memecah data payload dari topic
  function splitString(payload){
      stringData = payload.split("|");
      date = stringData[0];
      pga = stringData[1];
      x = stringData[2];
      y = stringData[3];
      //shake = stringData[4];
      //damage = stringData[5];
  }

  //fungsi untuk menerima data dari broker
  function onMessageArrived(message) {
		var topic = message.destinationName;
		var payload = message.payloadString;
    if (topic) {
      console.log(payload);
      splitString(payload);
      $("#date").html(date); 
      $("#pga").html(pga); 
      $("#x").html(x); 
      $("#y").html(y);
      if(pga < 0.000464){
        shake = "Not Felt";
        damage = "None";
      } 
      else if(pga > 0.000464 && pga<=0.00297){
        shake = "Weak";
        damage = "None";
      }
      else if(pga > 0.00297 && pga<=0.0276){
        shake = "Light";
        damage = "None";
      }
      else if(pga > 0.0276 && pga<=0.115){
        shake = "Moderate";
        damage = "Very Light";
      }
      else if(pga > 0.115 && pga<=0.215){
        shake = "Strong";
        damage = "Light";
      }
      else if(pga > 0.215 && pga<=0.401){
        shake = "Very Strong";
        damage = "Moderate";
      }
      else if(pga > 0.401 && pga<=0.747){
        shake = "Severe";
        damage = "Moderate to Heavy";
      }
      else if(pga > 0.747 && pga<=1.39){
        shake = "Violent";
        damage = "Heavy";
      }
      else if(pga > 1.39){
        shake = "Extreme";
        damage = "Very Heavy";
      }
      $("#shake").html(shake); 
      $("#damage").html(damage);
      postData();
    }
  };

  function RefreshTable() {
    $( "#mytable" ).load( "index.html #mytable" );
  }

  $(document).ready(function() {
      MQTTconnect();  
  });

});