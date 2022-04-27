# PeakGroundAcceleration

# Setup Web
1. download code dalam zip
2. extract ke folder htdocs xampp
3. buka phpmyadmin, buat database dengan nama pga
4. import pga.sql ke database yang baru dibuat
5. buka http://localhost:8080/PeakGroundAcceleration-main/mqtt%20esp8266%20accel/web/login.php

# Setup alat
1. buka PeakGroundAcceleration-main\Arduino\PeakGroundAccelESP8266
2. download library yang dibutuhkan
3. setup esp8266 for arduino ide (jika belum) -> https://randomnerdtutorials.com/how-to-install-esp8266-board-arduino-ide/
4. pilih board NodeMCU 1.0(ESP-12e Module), setting port.
5. compile & upload

# Download
1. download csv melalui tombol download pada website
2. buka https://docs.google.com/spreadsheets/u/0/
3. klik blank
4. klik file>import>upload, pilih file yang telah di download atau drag and drop, import data
5. klik file>download>(pilih format)
