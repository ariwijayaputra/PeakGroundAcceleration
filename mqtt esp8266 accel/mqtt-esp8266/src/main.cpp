#include <Arduino.h>
#include <elapsedMillis.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <MPU6050.h>
#include <CircularBuffer.h>
#include <ThreeWire.h>  
#include <RtcDS1302.h>
#include "SPI.h"
#include "SD.h"

#include "I2Cdev.h"


#if I2CDEV_IMPLEMENTATION == I2CDEV_ARDUINO_WIRE
    #include "Wire.h"
#endif

// variabel WiFi credential
const char* ssid     = "A4Y Family";
const char* password = "DK1623GC";

// variabel MQTT Broker/server
const char *mqtt_broker = "maqiatto.com"; //domain atau ip server mqtt
const char *topic = "peakgroundaccelapp@gmail.com/accel";
const char *mqtt_username = "peakgroundaccelapp@gmail.com";
const char *mqtt_password = "acceleration";
const int mqtt_port = 1883;

// variable accel MPU6050
MPU6050 accel;
int16_t ax, ay, az; //raw data
float gX, gY, gZ = 0.000000; // acceleration in g
CircularBuffer<int,5> bufferX; // array dalam proses reduce noise x
CircularBuffer<int,5> bufferY; // array dalam proses reduce noise y
CircularBuffer<int,5> bufferZ; // array dalam proses reduce noise z
long AccX,AccY,AccZ = 0; //variabel menyimpan raw accel yang telah di reduce noise
float mX, mY, mZ = 0.000000; // variabel menyimpan acceleration dalam cm/s^2
float pga; //variabel menyimpan pga
float v; //variable menyimapn velocity

// Variabel sdcard
const int chipSelect = D8; //pin sdcard

// variable RTC DS1302
ThreeWire myWire(D3,D4,D0); // IO, SCLK, CE
RtcDS1302<ThreeWire> Rtc(myWire);
#define countof(a) (sizeof(a) / sizeof(a[0]))

// elasedMillis sebagai timer untuk publish data
elapsedMillis publishTimer;
elapsedMillis accelTimer;

// buat client untuk interaksi dengan wifi dan broker
WiFiClient espClient;
PubSubClient client(espClient);

// --- start of connectWifi() ---
// fungsi untuk connect ke wifi
void connectWifi(){
  // mulai koneksi
  WiFi.begin(ssid,password);
  // tunggu sampai status connected
  while (WiFi.status() != WL_CONNECTED) 
  {
     delay(500);
     Serial.print("*");
  }
  Serial.println();
  Serial.println("-- WiFi Connected --");
}
// --- end of connectWifi() ---


// --- start of callback() ---
// fungsi untuk menerima data dari server mqtt.
// dijalankan otomatis setiap menerima data pada topic.
void callback(char *topic, byte *payload, unsigned int length) {
  Serial.print("Message arrived in topic: ");
  Serial.println(topic);
  Serial.print("Message:");
  for (int i = 0; i < length; i++) {
    Serial.print((char) payload[i]);
  }
  Serial.println("-----------------------");
}
// --- end of callback() ---


// --- start of connectBroker() ---
// fungsi untuk connect ke mqtt server/broker
void connectBroker(){
  client.setServer(mqtt_broker, mqtt_port);
  client.setCallback(callback);
  // ulang koneksi hingga status client connected
  while (!client.connected()) {
    String client_id = "esp8266-client-";
    client_id += String(WiFi.macAddress());
    Serial.printf("The client %s is connecting to mqtt broker\n", client_id.c_str());
    if (client.connect(client_id.c_str(), mqtt_username, mqtt_password)) {
      Serial.println("MQTT broker/server connected");
    } else {
      Serial.print("failed with state ");
      Serial.print(client.state());
      delay(2000);
    }
  }
  Serial.println();
  // subscribe untuk menerima data dari topic mqtt
  client.subscribe(topic);
  if (client.subscribe(topic))
  {
    Serial.println("subscribe success");
  }
  // publish untuk mengirim pesan "ESP32 Connected" ke topic mqtt
  client.publish(topic, "ESP32 Connected");  
}
// --- end of connectBroker() ---


// --- start of printDateTime ---
// format data rtc dan print ke serial monitor
void printDateTime(const RtcDateTime& dt)
{
    char datestring[20];

    snprintf_P(datestring, 
            countof(datestring),
            PSTR("%02u/%02u/%04u %02u:%02u:%02u"),
            dt.Month(),
            dt.Day(),
            dt.Year(),
            dt.Hour(),
            dt.Minute(),
            dt.Second() );
    Serial.print(datestring);
}
// --- end of printDateTime ---


// --- start of setupSD() ---
void setupSD(){
  Serial.print("Initializing SD card...");
  // make sure that the default chip select pin is set to
  // output, even if you don't use it:
  pinMode(SS, OUTPUT);
  
  // cek apakah ada kartu sd
  if (!SD.begin(chipSelect)) {
    Serial.println("Card failed, or not present");
    // don't do anything more:
    return;
  }
  Serial.println("card initialized.");
  
  // open the file. note that only one file can be open at a time,
  // so you have to close this one before opening another.
  File dataFile = SD.open("datalog.txt");

}
// --- end  of setupSD() ---


// --- start of setupRTC() ---
// fungsi untuk konfigurasi awal dan menyalakan RTC
void setupRTC(){
  Serial.print("compiled: ");
    Serial.print(__DATE__);
    Serial.println(__TIME__);

    Rtc.Begin();

    RtcDateTime compiled = RtcDateTime(__DATE__, __TIME__);
    printDateTime(compiled);
    Serial.println();

    if (!Rtc.IsDateTimeValid()) 
    {
        // Common Causes:
        //    1) first time you ran and the device wasn't running yet
        //    2) the battery on the device is low or even missing

        Serial.println("RTC lost confidence in the DateTime!");
        Rtc.SetDateTime(compiled);
    }

    if (Rtc.GetIsWriteProtected())
    {
        Serial.println("RTC was write protected, enabling writing now");
        Rtc.SetIsWriteProtected(false);
    }

    if (!Rtc.GetIsRunning())
    {
        Serial.println("RTC was not actively running, starting now");
        Rtc.SetIsRunning(true);
    }

    RtcDateTime now = Rtc.GetDateTime();
    if (now < compiled) 
    {
        Serial.println("RTC is older than compile time!  (Updating DateTime)");
        Rtc.SetDateTime(compiled);
    }
    else if (now > compiled) 
    {
        Serial.println("RTC is newer than compile time. (this is expected)");
    }
    else if (now == compiled) 
    {
        Serial.println("RTC is the same as compile time! (not expected but all is fine)");
    }
}
// --- end of setupRTC() ---



void getRTCData(RtcDateTime now){
  
  printDateTime(now);
  Serial.println();

  if (!now.IsValid())
  {
      // Common Causes:
      //    1) the battery on the device is low or even missing and the power line was disconnected
      Serial.println("RTC lost confidence in the DateTime!");
  }
}

// --- start of setupMPU6050() ---
// fungsi untuk konfigurasi awal dan menyalakan sensor mpu 6050
void setupMPU6050(){
  // join I2C bus (I2Cdev library doesn't do this automatically)
  #if I2CDEV_IMPLEMENTATION == I2CDEV_ARDUINO_WIRE
      Wire.begin();
  #elif I2CDEV_IMPLEMENTATION == I2CDEV_BUILTIN_FASTWIRE
      Fastwire::setup(400, true);
  #endif
  // inisiasi dan menyalakan MPU6050. diatur melalui pin D3
  Serial.println("Initializing I2C devices...");
  pinMode(D3,OUTPUT);
  digitalWrite(D3, HIGH);
  delay(1000);
  accel.initialize();
  delay(1000);
  // cek koneksi
  Serial.println("Testing device connections...");
  Serial.println(accel.testConnection() ? "MPU6050 connection successful" : "MPU6050 connection failed");
}


// --- start of convert() ---
// fungsi untuk convert MPU 6050 raw data to g
void convert(int x, int y, int z){
  // berdasarkan datasheet mpu6050, 16384 lsb = 1g
  gX = float(x) / 16384.000000;
  gY = float(y) / 16384.000000;
  gZ = float(z) / 16384.000000;

  // convert g -> m/s^2. 1g = 980cm/s^2
  mX = gX * 980;
  mY = gY * 980;
  mZ = gZ * 980;

  // pga (g) = sqrt(gx^2 + gY^2). pga dihitung dengan pitagoras
  // untuk mengkombinasikan hasil akselerasi sumbu x dan y
  pga = sqrt((gX*gX)+(gY*gY));
  v = pga*980; 
}
// --- end of convert() ---


//--- start of getReducedNoiseAccel() ---
// fungsi untuk mengurangi noice yang didapat dari sensor
void getReducedNoiseAccel(){
  
  if(accelTimer>200){
    accel.getAcceleration(&ax, &ay, &az);
    AccX = 0;
    AccY = 0;
    AccZ = 0;
    bufferX.push(ax);
    bufferY.push(ay);
    bufferZ.push(az);
    if(bufferZ.isFull()){
      for (size_t i = 0; i < 5; i++)
      {
        AccX += bufferX[i];
        AccY += bufferY[i];
        AccZ += bufferZ[i];
      }
      
      AccX = AccX/5;
      AccY = AccY/5;
      AccZ = AccZ/5;
      convert(AccX, AccY, AccZ);
    }
    accelTimer = 0;
  }
}
//--- end of getReducedNoiseAccel() ---


// --- start of calibrateAccel ---
// fungsi untuk mengkalibrasi sensor acceleration,
// letakan alat di bidang datar dan stabil selama kalibrasi 
void calibrateAccel(){
  Serial.println("Calibrating... dont move the device");
  delay(5000);
  accel.CalibrateAccel(6);
  accel.CalibrateGyro(6);
  accel.PrintActiveOffsets();
  Serial.println();
  accel.CalibrateAccel(1);
  accel.CalibrateGyro(1);
  accel.PrintActiveOffsets();
  Serial.println();
  accel.CalibrateAccel(1);
  accel.CalibrateGyro(1);
  accel.PrintActiveOffsets();
  Serial.println();
  accel.CalibrateAccel(1);
  accel.CalibrateGyro(1);
  accel.PrintActiveOffsets();
  Serial.println();    
  accel.CalibrateAccel(1);
  accel.CalibrateGyro(1);
  accel.setXAccelOffset(accel.getXAccelOffset());
  accel.setYAccelOffset(accel.getYAccelOffset());
  accel.setZAccelOffset(accel.getZAccelOffset());
  accel.PrintActiveOffsets();
  Serial.println("Calibrating done");
}
// --- end of callibrateAccel() ---


// --- start of monitorData() ---
// mencetak data accelerometer ke serial monitor 9600
// gx | gy | gz | mx | my | mz | pga | v |
void monitorData(){
  Serial.print(gX,6); Serial.print("\t");
  Serial.print(gY,6); Serial.print("\t");
  Serial.print(gZ,6); Serial.print("\t");
  Serial.print(mX,6); Serial.print("\t");
  Serial.print(mY,6); Serial.print("\t");
  Serial.print(mZ,6); Serial.print("\t"); 
  Serial.print(pga,6); Serial.print("\t"); 
  Serial.println();
}
// --- end of monitorData() ---


// --- start of publishData() ---
// fungsi untuk memformat data dan
// mengirim data ke mqtt 
void publishData(const RtcDateTime& dt, float pga, float v){
  char publishStr[52];
  sprintf(
    publishStr, "%02u/%02u/%04u %02u:%02u:%02u|%.6f|%.6f|",
    dt.Month(),
    dt.Day(),
    dt.Year(),
    dt.Hour(),
    dt.Minute(),
    dt.Second(),
    pga, 
    v
  
  );
  //Serial.println(publishStr);
  if (!client.publish(topic,publishStr))
  {
    Serial.println("failed publish. Reconnecting");
    connectBroker();
  }
  
}
// --- end of publishData() ---



// --- start of setup() ---
// fungsi setup dijalankan pertama kali
void setup() {
  Serial.begin(9600);
  connectWifi();
  connectBroker();
  setupRTC();
  // mpu6050 Setup
  setupMPU6050();
  // kalibrasi sensor
  calibrateAccel();
} 
// --- end of setup() ---


// --- start of loop() ---
// fungsi loop dijalankan berulang
void loop() {
  // membuka koneksi mqtt untuk menerima data pada topic
  //client.loop();
  
  //mengambil data dari accelerometer MPU6050
  getReducedNoiseAccel();


  // Publish data setiap detik (1000ms) ke mqtt
  if(publishTimer > 1000){
    //ambil data rtc
    RtcDateTime now = Rtc.GetDateTime();
    getRTCData(now);
    //print data ke serial monitor
    monitorData();
    //publish data
    publishData(now, pga, v);
    //reset timer
    publishTimer = 0; 
  }
}
// --- end of loop() ---
