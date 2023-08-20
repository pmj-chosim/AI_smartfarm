#include <ESP8266WiFi.h>
#include <SparkFun_RHT03.h>
#include <WiFiUdp.h>
#include <NTPClient.h>
#include <EEPROM.h>
#include <ArduinoJson.h>
//****Weather***//
#include <WiFiClient.h>

//****json전송방법****//
#include <ESP8266HTTPClient.h>

const char* ssid = "JTI-3.01";  // Name of Wi-Fi in use
const char* password = "";      // Wi-Fi password
WiFiServer server(80);          // Server Pohjkhjyhj8987rt

const char* serverIP = "10.10.183.134"; // available to change
const int serverPort = 5000;

// NTP Server Time
const char* ntpServer = "pool.ntp.org";
uint8_t timeZone = 9;
uint8_t summerTime = 0;

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, ntpServer);

// Weather
// OpenWeatherMap API Server Information
const char* weatherServer = "api.openweathermap.org";
const int weatherPort = 80;
const char* apiKey = "a4e873808077c72854f9549953b758af";
const char* city = "1642588";  // changed to Jember

String formattedTime;
int year, month, day, hour, minute, second;

// Decision Pin
int motorPin[] = { 5, 4 };
int ledPin = 0;
int submotorPin = 2;
int soilPin[] = { 14, 12 };
int rhtPin = 13;

// default => 1(off), then We put 0(on) to relay module
int isLedOn = 1;
int motorOn[] = { 1, 1 };
int isSubmotorOn = 1;

// temp, humidity
RHT03 rht;
float tempC;
float humidity;

// soil humidity chart
byte soilHumidity1[24] = { 0 };
byte soilHumidity2[24] = { 0 };

// ***stack for soil humidity prediction, value of y-axis***
int humForPred1[24] = { 0 };
int humForPred2[24] = { 0 };
int cnt = 0;

// ***value of x-axis***
int x[24] = { 0 };
// ***Predictive Results Storage Variables***
byte prediction1 = 0;
byte prediction2 = 0;

double potPred1 = 0;
double potPred2 = 0;

// ***Indonesia's Current Weather Storage Variables*** //
float jemb_temperature;
float jemb_humidity;
float jemb_windSpeed;

bool doItJustOnce = false;

int soilValue(int pin) {
  for (int i = 0; i < 2; i++) {
    if (i == pin) {
      digitalWrite(soilPin[i], HIGH);
    } else {
      digitalWrite(soilPin[i], LOW);
    }
  }
  return analogRead(A0);
}

void writeLog(String text) {
  String currentTime = String(year) + "-" + String(month) + "-" + String(day) + " " + formattedTime;
  Serial.print(currentTime);
  Serial.print(" | ");
  Serial.println(text);
}

//********Weather API***********//
void getWeather() {

  // Create Wi-Fi Client Object for Weather
  WiFiClient client;
  
  // Send request to OpenWeatherMap API
  if (client.connect(weatherServer, 80)) {
    client.print("GET /data/2.5/weather?id=");
    client.print(city);
    client.print("&appid=");
    client.print(apiKey);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(weatherServer);
    client.println("Connection: close");
    client.println();

    // Receive and process responses
    while (client.connected()) {
      if (client.available()) {
        String line = client.readStringUntil('\n');
        if (line == "\r") {
          break;
        }
      }
    }

    // JSON parsing and weather information output
    DynamicJsonDocument weatherDoc(1024);
    DeserializationError error = deserializeJson(weatherDoc, client);
    if (error) {
      Serial.println("Error parsing JSON");
      return;
    }

    const char* description = weatherDoc["weather"][0]["description"];
    jemb_temperature = weatherDoc["main"]["temp"];
    jemb_temperature -= 273.15;
    jemb_humidity = weatherDoc["main"]["humidity"];
    jemb_windSpeed = weatherDoc["wind"]["speed"];

    Serial.println("Current Weather in Jember, Indonesia:");
    Serial.print("Description: ");
    Serial.println(description);
    Serial.print("Temperature: ");
    Serial.print(jemb_temperature);
    Serial.println(" °C");
    Serial.print("Humidity: ");
    Serial.print(jemb_humidity);
    Serial.println(" %");
    Serial.print("Wind Speed: ");
    Serial.print(jemb_windSpeed);
    Serial.println(" m/s");

  } else {
    Serial.println("Failed to connect to OpenWeatherMap API");
  }
  client.stop();
}

void setup() {
  // Default Settings
  Serial.begin(115200);  // Serial communication, speed 115200
  delay(10);

  writeLog("Server begin...");
  pinMode(A0, INPUT);
  pinMode(ledPin, OUTPUT);
  pinMode(submotorPin, OUTPUT);
  for (int i = 0; i < 2; i++) {
    pinMode(motorPin[i], OUTPUT);
    pinMode(soilPin[i], OUTPUT);
  }
  rht.begin(rhtPin);
  EEPROM.begin(48);

  writeLog("Fin setting and RHT, EEPROM start complete");

  // Get Humidity Values
  for (int i = 0; i < EEPROM.length(); i++) {
    if (EEPROM.read(i) > 100) continue;  // The initial value of the initialized EEPROM was 255.
    if (i < 24) {
      soilHumidity1[i] = EEPROM.read(i);
      //EEPROMArr1.add(soilHumidity1[i]);
      writeLog(String(i) + " " + String(soilHumidity1[i]));
    } else {
      soilHumidity2[i - 24] = EEPROM.read(i);
      //EEPROMArr2.add(soilHumidity2[i-24]);
      writeLog(String(i) + " " + String(soilHumidity1[i]));
    }
  }
  writeLog("Completed loading humidity values stored in EEPROM");
  //serializeJson(EEPROMDoc, jsonData);

  // Turn off the whole thing
  digitalWrite(ledPin, isLedOn);            // Turn on the LED
  digitalWrite(submotorPin, isSubmotorOn);  // Turn on the water dripping motor
  for (int i = 0; i < 2; i++) {
    digitalWrite(motorPin[i], motorOn[i]);
  }

  // Wi-Fi connection
  writeLog("Connecting to Wi-Fi");
  WiFi.mode(WIFI_STA);

  writeLog("wifi connnected to " + String(ssid));

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(100);
    Serial.print("!");
  }

  writeLog("Wi-Fi connection complete");
  Serial.println("Wi-Fi connection complete");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  // Server begin
  server.begin();
  writeLog("Server begin...");

  // Get server time
  timeClient.begin();
  timeClient.setTimeOffset(3600 * timeZone);
  timeClient.update();
  formattedTime = timeClient.getFormattedTime();
  hour = timeClient.getHours();
  minute = timeClient.getMinutes();
  second = timeClient.getSeconds();
  writeLog("NTP Time Recall Complete");
}

void loop() {
  // put your main code here, to run repeatedly:
  delay(50);
  WiFiClient client = server.available();

  timeClient.update();  // Update Time

  time_t epochTime = timeClient.getEpochTime();
  struct tm* ptm = gmtime((time_t*)&epochTime);

  formattedTime = timeClient.getFormattedTime();
  hour = timeClient.getHours();
  minute = timeClient.getMinutes();
  second = timeClient.getSeconds();

  year = ptm->tm_year + 1900;  // It adds 1900 to match the current year.
  month = ptm->tm_mon + 1;
  day = ptm->tm_mday;

  // GET REQUEST
  String req = client.readStringUntil('\r');
  Serial.println(client.readStringUntil('\r'));
  client.flush();

  if (req.indexOf("led/on") != -1) {
    isLedOn = 0;
    writeLog("LED on due to user");
  } else if (req.indexOf("led/off") != -1) {
    isLedOn = 1;
    writeLog("led off due to user");
  } else if (req.indexOf("motor/sub/on") != -1) {
    isSubmotorOn = 0;
    writeLog("Drain motor on due to user");
  } else if (req.indexOf("motor/sub/off") != -1) {
    isSubmotorOn = 1;
    writeLog("Drain motor off due to user");
  }
  DynamicJsonDocument EEPROMDoc(1024);  // JSON document created
  JsonArray EEPROMArr1 = EEPROMDoc.createNestedArray("POT1");
  JsonArray EEPROMArr2 = EEPROMDoc.createNestedArray("POT2");
  String jsonData;

  for (int i = 0; i < 24; i++) {
    EEPROMArr1.add(soilHumidity1[i]);
    EEPROMArr2.add(soilHumidity2[i]);
  }
  serializeJson(EEPROMDoc, jsonData);
  Serial.println(jsonData);

  digitalWrite(ledPin, isLedOn);            // Turn on the LED
  digitalWrite(submotorPin, isSubmotorOn);  // Turn on the dripping motor (0) Off (1)

  int soilValues[2] = { 0 };
  int soilPercents[2] = { 0 };

  for (int i = 0; i < 2; i++) {
    soilValues[i] = soilValue(i);
    delay(100);
  }

  for (int i = 0; i < 2; i++) {
    soilPercents[i] = map(soilValues[i], 1024, 0, 0, 100);
  }

  if (timeClient.getMinutes() == 15 && !doItJustOnce) {  // Run only once on time (run every minute to meet the run time difference)
    if (!hour) {                                         // If it's on time (when it's 0(24):00) => EEPROM initialization!
      // EEPROM initialization
      for (int i = 0; i < EEPROM.length(); i++) {
        EEPROM.write(i, 0);
      }
      writeLog("EEPROM initialization");

      // Initialize the water value
      for (int i = 0; i < 24; i++) {
        soilHumidity1[i] = 0;
        soilHumidity2[i] = 0;
      }
      writeLog("Initialize soil moisture value");
    }

    if (hour >= 6 && hour <= 19) {  // On during the day
      if (isLedOn) {
        writeLog("LED on due to time");
        isLedOn = 0;
      }
    } else {
      if (!isLedOn) {  // Off during the night
        writeLog("led off due to time");
        isLedOn = 1;
      }
    }

    // Full initialized state (when 0:00) or on time (when not 0:00)
    // Save to Humidity EEPROM
    // 0 to 23 are the water in the pot 1
    EEPROM.write(hour, soilPercents[0]);
    soilHumidity1[hour] = soilPercents[0];
    // 24 to 47 are the water in the pot 2
    EEPROM.write(hour + 24, soilPercents[1]);
    soilHumidity2[hour] = soilPercents[1];
    EEPROM.commit();
    doItJustOnce = true;  // false -> true

    writeLog("Moisture of No.1 " + String(soilPercents[0]) + " EEPROM recorded");
    writeLog("Moisture of No.2 " + String(soilPercents[1]) + " EEPROM recorded");
  } else if (timeClient.getMinutes() == 2 && doItJustOnce) {  // Change the value so that it can run again on time
    doItJustOnce = false;
  }

  /*******Save to the list of moisture measurements every 15 minutes (test: 1 minute)*******/
  if (timeClient.getMinutes() % 1 == 0 && timeClient.getSeconds() <= 10) {
    if (cnt < 24) {
      humForPred1[cnt] = soilPercents[0];
      humForPred2[cnt] = soilPercents[1];
      cnt++;  //number of elements in the current array
    } else {
      for (int i = 0; i < 23; i++) {  //stack structure
        humForPred1[i] = humForPred1[i + 1];
        humForPred2[i] = humForPred2[i + 1];
      }
      humForPred1[23] = soilPercents[0];
      humForPred2[23] = soilPercents[1];
    }
    Serial.println(cnt);  //test

    for (int i = 0; i < cnt; i++) {  //The index role of a common x-value, i.e. y-value (water arrangement by pot every 15 minutes)
      x[i] = 1 * (i + 1);            //15 -> 1(1,2,3...)
      // If the number of elements in the x array is less than 24, the number of elements in the array => cnt (1 incremented state) + 1 to hand over test_x to the prediction
      // If the number of elements in the x array is 24, test_x that is handed over to the prediction is cnt (1 increased state) => 25
      writeLog(String(i) + "일때: x = " + String(x[i]));
    }

    Serial.println();

    for (int i = 0; i < cnt; i++) {
      writeLog("accumulated x[" + String(i) + "] : " + String(x[i]) + " after upload");
    }

    //*********Morton motion using predicted values*********//
    double prediction[2] = { potPred1, potPred2 };
    for (int i = 0; i < 2; i++) {
      if (prediction[i] < 50) {
        writeLog("pot" + String(i+1) + "의 수분 예측값 " + String(prediction[i]) +" 에 따라" + String(i + 1) + "번 모터 켜짐");
        motorOn[i] = 0;
      } else {
        if (!motorOn[i]) writeLog("pot" + String(i+1) + "의 수분 예측값 " + String(prediction[i]) +" 에 따라" + String(i + 1) + "번 모터 꺼짐");
        motorOn[i] = 1;  // 모터꺼짐
      }
      digitalWrite(motorPin[i], motorOn[i]);
    }
    //*******************************************//
  }


  // Checking the temperature and humidity
  int updateRht = rht.update();  // On via RHT, returns 1 if humidity is called

  //**************************************/
  if (updateRht == 1) {
    humidity = rht.humidity();
    tempC = rht.tempC();
    writeLog("Temperature and humidity updated, Temperature: " + String(tempC) + "°C, Humidity: " + String(humidity) + "%");

    // Store humidity in EEPROM every hour, but it just seems to run every delay (50)
    // 0 to 23 is 1 moisture
    EEPROM.write(hour, soilPercents[0]);
    soilHumidity1[hour] = soilPercents[0];
    // 24 to 47 is water of Pot 2
    EEPROM.write(hour + 24, soilPercents[1]);
    soilHumidity2[hour] = soilPercents[1];
    EEPROM.commit();
    doItJustOnce = true;

    writeLog("[Pot1] Current moisture hour: " + String(hour) + ", " + String(soilPercents[0]) + " EEPROM recording");
    writeLog("[Pot2] Current moisture hour: " + String(hour) + ", " + String(soilPercents[1]) + " EEPROM recording");

  }


  //******Create Json FILE******//
  DynamicJsonDocument dataDoc(1024);  // JSON document created
  JsonArray xJsonArr = dataDoc.createNestedArray("x");
  JsonArray humJsonArr1 = dataDoc.createNestedArray("hum1");
  JsonArray humJsonArr2 = dataDoc.createNestedArray("hum2");

  for (int i = 0; i < cnt; i++) {
    writeLog(String(x[i]) + ", y: " + String(humForPred1[i]));
    xJsonArr.add(x[i]);
    humJsonArr1.add(humForPred1[i]);
    humJsonArr2.add(humForPred2[i]);
  }

  //*****json 데이터 플라스크로 전송 *****//
  String jsonString;
  serializeJson(dataDoc, jsonString);
  Serial.println(jsonString);

  // HTTP 클라이언트 초기화
  HTTPClient http;
  http.begin(client, serverIP, serverPort, "/postjson");
  http.addHeader("Content-Type", "application/json");

  // POST 요청 보내기
  int httpCode = http.POST(jsonString);
  
  
  DynamicJsonDocument predDoc(1024);

  if (httpCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpCode);
    String payload = http.getString();
    Serial.println(payload);

    //********parsing code 넣기********
    DeserializationError error = deserializeJson(predDoc, payload);

    if (error) {
      Serial.print("JSON 파싱 오류: ");
      Serial.println(error.c_str());
      return;
    }

    potPred1 = predDoc["y1_pred_plus"][0];
    potPred2 = predDoc["y2_pred_plus"][0];

    Serial.print("potPred1: ");
    Serial.println(potPred1);
    Serial.print("potPred2: ");
    Serial.println(potPred2);

  } else {
    Serial.print("Error code: ");
    Serial.println(httpCode);
  }

  // 연결 해제
  http.end();

  //******Weather******//
  getWeather();

}