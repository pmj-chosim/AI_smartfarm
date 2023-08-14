#include <ESP8266WiFi.h>
#include <SparkFun_RHT03.h>
#include <WiFiUdp.h>
#include <NTPClient.h>
#include <EEPROM.h>

//****Weather***//
#include <WiFiClient.h>
#include <ArduinoJson.h>

//****json전송방법****//
#include <ESP8266HTTPClient.h>

//***Max Degree***
#define MAX_DEGREE 10

// **********Defining a Multinomial Regression Model Structure**********
typedef struct {
    int a;
    int b;
    int c;
} PolynomialModel;

const char* ssid     = "songchae"; // Name of Wi-Fi in use
const char* password = "20020903"; // Wi-Fi password
WiFiServer server(80); // Server Port

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
const char* city = "1642588"; // changed to Jember

String formattedTime;
int year, month, day, hour, minute, second;

// Decision Pin 
int motorPin[] = {5, 4};
int ledPin = 0;
int submotorPin = 2;
int soilPin[] = {14, 12};
int rhtPin = 13;

// default => 1(off), then We put 0(on) to relay module
int isLedOn = 1; 
int motorOn[] = {1, 1};
int isSubmotorOn = 1;

// temp, humidity 
RHT03 rht;
float tempC;
float humidity;

// soil humidity chart 
byte soilHumidity1[24] = {0};
byte soilHumidity2[24] = {0};

StaticJsonDocument<200> doc; // JSON document created (possible to change memory size)
JsonArray humJsonArr1 = doc.createNestedArray("hum1");
JsonArray humJsonArr2 = doc.createNestedArray("hum2");

// ***stack for soil humidity prediction, value of y-axis***
int humForPred1[24] = {0};
int humForPred2[24] = {0};
int cnt = 0;

// ***value of x-axis***
int x[24] = {0};
// ***Predictive Results Storage Variables***
byte prediction1 = 0;
byte prediction2 = 0;

// ***Indonesia's Current Weather Storage Variables*** //
float jemb_temperature;
float jemb_humidity;
float jemb_windSpeed;

bool doItJustOnce = false;

// Array for storing Log
String logs[1000];
int logcount = 0;

int soilValue(int pin){
    for(int i = 0; i < 2; i++){
        if(i == pin){
            digitalWrite(soilPin[i], HIGH);
        }else{
            digitalWrite(soilPin[i], LOW);
        }
    }
    return analogRead(A0);
}

void writeLog(String text){
    if(logcount > 999) logcount = 0; // Return to zero when the log array is full
    String currentTime = String(year) + "-" + String(month) + "-" + String(day) + " " + formattedTime;
    logs[logcount] = currentTime + " | " + text;
    Serial.print(currentTime);
    Serial.print(" | ");
    Serial.println(text);
    logcount += 1;
}

// **********Multinomial regression model generation and training functions**********
void create_and_train_polynomial_regression(int x[], int y[], int n, PolynomialModel *model) {//n => 배열에 들어있는 원소의 개수
    int sum_x = 0, sum_x2 = 0, sum_x3 = 0, sum_x4 = 0;
    int sum_y = 0, sum_xy = 0, sum_x2y = 0;

    for (int i = 0; i < n; i++) {
        int x2 = x[i] * x[i];
        int x3 = x2 * x[i];
        int x4 = x3 * x[i];
        
        sum_x += x[i];
        sum_x2 += x2;
        sum_x3 += x3;
        sum_x4 += x4;
        
        sum_y += y[i];
        sum_xy += x[i] * y[i];
        sum_x2y += x2 * y[i];
    }

    int denominator = n * sum_x2 * sum_x4 - n * sum_x3 * sum_x3 + sum_x * sum_x * sum_x2;
    model->a = (sum_y * sum_x2 * sum_x4 - sum_xy * sum_x3 * sum_x3 + sum_x * sum_x2y * sum_x2) / denominator;
    model->b = (n * sum_xy * sum_x4 - sum_y * sum_x3 * sum_x3 + sum_x * sum_x2y * sum_x) / denominator;
    model->c = (n * sum_x2y * sum_x2 - sum_xy * sum_x3 * sum_x + sum_x * sum_y * sum_x4) / denominator;
}

//********** Predictive function with quadratic polynomial regression model **********
int predict_with_polynomial_regression(PolynomialModel model, int test_x) {
    return model.a * test_x * test_x + model.b * test_x + model.c;
}


//********Weather API***********//
void getWeather() {
  // Create Wi-Fi Client Object
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
    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, client);
    if (error) {
      Serial.println("Error parsing JSON");
      return;
    }

    const char* description = doc["weather"][0]["description"];
    jemb_temperature = doc["main"]["temp"];
    jemb_humidity = doc["main"]["humidity"];
    jemb_windSpeed = doc["wind"]["speed"];

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



void setup(){
    // Default Settings
    writeLog("Server begin...");
    pinMode(A0,INPUT);
    pinMode(ledPin, OUTPUT);
    pinMode(submotorPin, OUTPUT);
    for(int i = 0; i < 2; i++){
        pinMode(motorPin[i], OUTPUT);
        pinMode(soilPin[i], OUTPUT);
    }
    rht.begin(rhtPin);
    EEPROM.begin(48);
    
    writeLog("Fin setting and RHT, EEPROM start complete");

    // Get Humidity Values
    for(int i = 0; i < EEPROM.length(); i++){
        if(EEPROM.read(i) > 100) continue; // The initial value of the initialized EEPROM was 255.
        if(i < 24){
            soilHumidity1[i] = EEPROM.read(i);
        }else{
            soilHumidity2[i - 24] = EEPROM.read(i);
        }
    }
    writeLog("Completed loading humidity values stored in EEPROM");


    // Turn off the whole thing
    digitalWrite(ledPin, isLedOn); // Turn on the LED
    digitalWrite(submotorPin, isSubmotorOn); // Turn on the water dripping motor
    for(int i = 0; i < 2; i++){
        digitalWrite(motorPin[i], motorOn[i]);
    }


    Serial.begin(115200); // Serial communication, speed 115200
    delay(10);

  // Wi-Fi connection
    writeLog("Connecting to Wi-Fi");
    WiFi.mode(WIFI_STA);
    
    writeLog("wifi connnected to" + String(ssid));

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

    timeClient.update(); // Update Time

    time_t epochTime = timeClient.getEpochTime();
    struct tm *ptm = gmtime ((time_t *)&epochTime); 

    formattedTime = timeClient.getFormattedTime();
    hour = timeClient.getHours();
    minute = timeClient.getMinutes();
    second = timeClient.getSeconds();

    year = ptm->tm_year+1900; // It adds 1900 to match the current year.
    month = ptm->tm_mon+1;
    day = ptm->tm_mday;

    // GET REQUEST
    String req = client.readStringUntil('\r');
    Serial.println(client.readStringUntil('\r'));
    client.flush();

    if(req.indexOf("led/on") != -1){
        isLedOn = 0;
        writeLog("LED on due to user");
    } else if (req.indexOf("led/off") != -1){
        isLedOn = 1;
        writeLog("led off due to user");
    } else if (req.indexOf("motor/sub/on") != -1){
        isSubmotorOn = 0;
        writeLog("Drain motor on due to user");
    }else if(req.indexOf("motor/sub/off") != -1){
        isSubmotorOn = 1;
        writeLog("Drain motor off due to user");
    }

    

    digitalWrite(ledPin, isLedOn); // Turn on the LED
    digitalWrite(submotorPin, isSubmotorOn); // Turn on the dripping motor (0) Off (1)
    
    int soilValues[2] = {0};
    int soilPercents[2] = {0};
    
    for(int i = 0; i < 2; i++){
        soilValues[i] = soilValue(i);
        delay(100);
    }

    // Use simple thresholds instead of predictions
    for(int i = 0; i < 2; i++){
        if(soilValues[i] > 500){ // 물주는 
            writeLog("Due to water supply, No." + String(i+1) + " Motor On");
            motorOn[i] = 0; // Motor On
        }else{
            if(!motorOn[i]) writeLog("Due to water supply, No." + String(i+1) + "Motor Off");
            motorOn[i] = 1; // Motor Off
        }
        //digitalWrite(motorPin[i], motorOn[i]);
    }
    
    for(int i = 0; i < 2; i++){
        soilPercents[i] = map(soilValues[i], 1024, 0, 0, 100);
    }

    if(timeClient.getMinutes() == 15 && !doItJustOnce){ // Run only once on time (run every minute to meet the run time difference)
        if(!hour){ // If it's on time (when it's 0(24):00) => EEPROM initialization!
            // EEPROM initialization
            for(int i = 0; i < EEPROM.length(); i++){
                EEPROM.write(i, 0);
            }
            writeLog("EEPROM initialization");
            
            // Initialize the water value
            for(int i = 0; i < 24; i++){
                soilHumidity1[i] = 0;
                soilHumidity2[i] = 0;
            }
            writeLog("Initialize soil moisture value");
        }

        if(hour >= 6 && hour <= 19){ // On during the day
            if(isLedOn){
                writeLog("LED on due to time");
                isLedOn = 0;
            }
        }else{
            if(!isLedOn){ // Off during the night
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
        doItJustOnce = true; // false -> true

        writeLog("Moisture of No.1 " + String(soilPercents[0]) + " EEPROM recorded");
        writeLog("Moisture of No.2 " + String(soilPercents[1]) + " EEPROM recorded");
    }else if(timeClient.getMinutes() == 2 && doItJustOnce){ // Change the value so that it can run again on time
        doItJustOnce = false;
    }

    /*******Save to the list of moisture measurements every 15 minutes (test: 1 minute)*******/
    if(timeClient.getMinutes() % 1 == 0 && timeClient.getSeconds() <= 10){
      if(cnt < 24){
        humForPred1[cnt] = soilPercents[0];
        humForPred2[cnt] = soilPercents[1];
        cnt++;//number of elements in the current array
      }else{
        for(int i = 0; i < 23; i++){//stack structure
          humForPred1[i] = humForPred1[i+1];
          humForPred2[i] = humForPred2[i+1];
        }
        humForPred1[23] = soilPercents[0];
        humForPred2[23] = soilPercents[1];
      }
      Serial.println(cnt);//test

      for(int i = 0; i < cnt; i++){//The index role of a common x-value, i.e. y-value (water arrangement by pot every 15 minutes)
        x[i] = 1*(i+1);//15 -> 1(1,2,3...)
        // If the number of elements in the x array is less than 24, the number of elements in the array => cnt (1 incremented state) + 1 to hand over test_x to the prediction
        // If the number of elements in the x array is 24, test_x that is handed over to the prediction is cnt (1 increased state) => 25
      }

      for(int i = 0; i < cnt; i++){
        writeLog("accumulated x[" + String(i) + "] : " + String(x[i]) + " after upload");
      }
      
      //*********Prediction for Pot 1*********//
      for(int i = 0; i < cnt; i++){
        writeLog("humForPred1[" + String(i) + "] : " + String(humForPred1[i]));
        writeLog("humForPred2[" + String(i) + "] : " + String(humForPred2[i]));
      }

      PolynomialModel model1; //Create Coef Storing Structures
      create_and_train_polynomial_regression(x, humForPred1, cnt, &model1);//Model creation and training
      writeLog("x2 of Pot 1: " + String(model1.a) + ", x1: " + String(model1.b) + ", x0: " + String(model1.c));
      
      //prediction test
      int test_x1 = 0;
      if(cnt < 25) {test_x1 = cnt + 1;}
      else {test_x1 = 25;}
      prediction1 = predict_with_polynomial_regression(model1, test_x1);
      writeLog("Pot 1 Prediction at x = " + String(test_x1) + ": " + prediction1); //

      
      // //*********Prediction for Pot 2*********//
      PolynomialModel model2; // Create Coef Storing Structures
      create_and_train_polynomial_regression(x, humForPred2, cnt, &model2);//Model creation and training
      writeLog("x2 of Pot 2: " + String(model2.a) + ", x1: " + String(model2.b) + ", x0: " + String(model2.c));

      //prediction test
      int test_x2 = 0;
      if(cnt < 25) {test_x2 = cnt + 1;}
      else {test_x2 = 25;}
      prediction2 = predict_with_polynomial_regression(model2, test_x2);
      writeLog("[Pot2] Prediction at x = " + String(test_x2) + ": " + prediction2); //

      //*********Morton motion using predicted values*********//
      byte prediction[2] = {prediction1, prediction2};
      for(int i = 0; i < 2; i++){
        if(prediction[i] < 40){
          writeLog("물주는값으로 인해 " + String(i+1) + "번 모터 켜짐");
        }
        else{
          if(!motorOn[i]) writeLog("물주는값으로 인해 " + String(i+1) + "번 모터 꺼짐");
            motorOn[i] = 1; // 모터꺼짐
        }
        //digitalWrite(motorPin[i], motorOn[i]);
      }
    //*******************************************//
    }
    

    // Checking the temperature and humidity
    int updateRht = rht.update(); // On via RHT, returns 1 if humidity is called

    //**************************************/
    if(updateRht == 1){
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

        writeLog("[Pot1] Current moisture hour: " + String(hour) + ", " + String(soilPercents[0]) + " EEPROM 기록");
        writeLog("[Pot2] Current moisture hour: " + String(hour) + ", " + String(soilPercents[1]) + " EEPROM 기록");

        // //(EEPROM) 지금까지 누적된 수분 데이터를 가지는 배열
        // for(int i = 0; i < sizeof(soilHumidity1)/sizeof(byte); i++){
        //   if(soilHumidity1[i] != 0 && soilHumidity2[i] != 0)
        //   {
        //     writeLog("1번 화분의 " + String(i) + "시의 수분: " + soilHumidity1[i]);
        //     writeLog("2번 화분의 " + String(i) + "시의 수분: " + soilHumidity2[i]);
        //   }
        // }
    }


  //******Create Json FILE******//
  doc["cnt"] = cnt;
  for (int i = 0; i < cnt; i++) {
      humJsonArr1.add(soilHumidity1[i]);
      //humJsonArr2.add(soilHumidity2[i]);
  }
  
  //*****json 데이터 플라스크로 전송 *****//
    // JSON 데이터를 String 형태로 변환
  String jsonString;
  serializeJson(doc, jsonString);
  
  // 변환된 JSON 데이터를 시리얼 모니터에 출력
  Serial.println(jsonString);
//    const char* json_data = //"{\"key1\":\"value1\",\"key2\":\"value2\"}";

    // HTTP 클라이언트 초기화
    HTTPClient http;
    http.begin(client, "http://127.0.0.1:5000/");

    // 헤더 설정 (application/json 형식으로 보내기 위함)
    http.addHeader("Content-Type", "application/json");

    // POST 요청 보내기
    int httpCode = http.POST(jsonString);

    // 응답 받기
    String payload = http.getString();
    Serial.println(httpCode);
    Serial.println(payload);

    // 연결 해제
    http.end();

    // 전송 확인
    Serial.println(jsonString);

    //******Weather******//
    getWeather();


    /*
    ====== HTML 선언부 ======
    */
    client.println("HTTP/1.1 200 OK");
    client.println("Content-Type: text/html");
    client.println("Connection: close");
    client.println();
    client.println("<!DOCTYPE html>");
    client.println("<html xmlns='http://www.w3.org/1999/xhtml'>");
    client.println("<head>\n<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />");
    client.println("<script src=\"https://cdn.tailwindcss.com\"></script>");
    client.println("<title>Smart Farm</title>"); // 웹 서버 페이지 제목 설정
    client.println("</head>");

    // body 태그 선언부
    client.println("<body>");
    client.println("<div class=\"text-8xl p-8 font-bold\"><a href=\"/\">TeamA Farm</a></div>");
    client.println("<div class=\"border-4 border-gray-800 rounded m-2 p-2\">");
    client.println("<div class=\"flex flex-row mb-6\">");
    client.println("<div class=\"basis-1/2 relative mb-6\">");

     // LED >> 수정했음! 반영 바람
    client.print("<span class=\"text-2xl\">LED: ");
    !isLedOn
    ? client.println("<span class=\"text-2xl font-bold\">OFF</span>")
    : client.println("<span class=\"text-2xl font-bold text-green-400\">ON</span>");
    client.println("</span>");
    client.println("<div class=\"flex\">\
                    <div class=\"inline-flex shadow-md hover:shadow-lg focus:shadow-lg\" role=\"group\">\
                        <button type=\"button\" class=\"rounded-l inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out\" onclick=\"location.href='/led/off'\">On</button>\
                        <button type=\"button\" class=\" rounded-r inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out\" onclick=\"location.href='/led/on'\">Off</button></div></div>"); // LED 끄고켜기 버튼
    client.println("</div>"); // <div class="basis-1/2 relative mb-6">
    
    
    client.println("<div class=\"basis-1/2 relative\">");

    // 물빼기 모터
    client.print("<span class=\"text-2xl\">drain motor: "); 
    isSubmotorOn
    ? client.println("<span class=\"text-2xl font-bold\">OFF</span>")
    : client.println("<span class=\"text-2xl font-bold text-green-400\">ON</span>");
    client.println("</span>");
    client.println("<div class=\"flex\">\
                    <div class=\"inline-flex shadow-md hover:shadow-lg focus:shadow-lg\" role=\"group\">\
                        <button type=\"button\" class=\"rounded-l inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out\" onclick=\"location.href='/motor/sub/on'\">On</button>\
                        <button type=\"button\" class=\" rounded-r inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out\" onclick=\"location.href='/motor/sub/off'\">Off</button></div></div>");
    client.println("</div>"); // <div class="basis-1/2 relative">

    // 온,습도
    client.println("<div class=\"basis-1/4\">");
    client.println("<div><span class=\"text-2xl\">temp: </span>");
    client.print("<span class=\"text-2xl font-bold\">");
    client.print(tempC); // 온도
    client.println("<span class=\"text-red-600\">°C</span></span>");
    client.println("</div>");
    client.println("<div><span class=\"text-2xl\">humi: </span>");
    client.print("<span class=\"text-2xl font-bold\">");
    client.print(humidity); // 습도
    client.println("<span>%</span></span>");
    client.println("</div>");

    client.println("<div><span class=\"text-2xl\">current humi: </span>");
    client.print("<span class=\"text-2xl font-bold\">");
    client.print(jemb_humidity); // 습도
    client.println("<span>%</span></span>");
    client.println("</div>");

    client.println("<div><span class=\"text-2xl\">current temp: </span>");
    client.print("<span class=\"text-2xl font-bold\">");
    client.print(jemb_temperature); 
    client.println("<span> °C</span></span>");
    client.println("</div>");

    client.println("<div><span class=\"text-2xl\">current wind speed: </span>");
    client.print("<span class=\"text-2xl font-bold\">");
    client.print(jemb_windSpeed); 
    client.println("<span>m/s</span></span>");
    client.println("</div>");

    client.println("</div>"); // <div class="basis-1/4">
    client.println("</div>"); // <div class="flex flex-row mb-6">

    // 화분카드 선언부
    client.println("<div class=\"h-fit grid grid-cols-2 gap-4 text-center\">");
    client.println("<div class=\"border-2 border-violet-400 rounded\">");
    client.println("<span class=\"mb-1 text-2xl font-bold\">Pot 1</span><hr/>");
    client.print("<div class=\"mb-3 mt-3 text-3xl font-bold\">Motor ");
    motorOn[0]
    ? client.println("<span class=\"text-3xl font-bold\">OFF</span>")
    : client.println("<span class=\"text-3xl font-bold text-green-400\">ON</span>");
    client.println("</div>"); // <div class="mb-3 mt-3 text-3xl font-bold">

    client.println("<div class=\"mb-1 text-lg font-bold\">Moisture</div>");
    client.println("<div class=\"mx-auto w-9/12 h-6 bg-gray-200 rounded-full dark:bg-gray-700\">");
    client.println("<div class=\"h-6 bg-gradient-to-r from-cyan-500 to-indigo-500 rounded-full font-bold text-slate-200\" style=\"width:");
    client.print(soilPercents[0]);
    client.print("%\">");
    client.print(soilPercents[0]);
    client.println("%</div></div>");
    client.print("<div class=\"p-5 shadow-lg rounded-lg overflow-hidden\">\
                    <div class=\"py-3 px-5 bg-gray-50 font-bold\">");
    client.print(String(year) + "-" + String(month) + "-" + String(day));
    client.println(" Moisture of pot1</div>\
                    <canvas class=\"p-1\" id=\"chartLine1\"></canvas>\
                </div>");
    client.println("</div>");
    
    client.println("<div class=\"border-2 border-violet-400 rounded\">");
    client.println("<span class=\"mb-1 text-2xl font-bold\">Pot 2</span><hr/>");
    client.print("<div class=\"mb-3 mt-3 text-3xl font-bold\">Motor ");
    motorOn[1]
    ? client.println("<span class=\"text-3xl font-bold\">OFF</span>")
    : client.println("<span class=\"text-3xl font-bold text-green-400\">ON</span>");
    client.println("</div>");

    client.println("<div class=\"mb-1 text-lg font-bold\">Moisture</div>");
    client.println("<div class=\"mx-auto w-9/12 h-6 bg-gray-200 rounded-full dark:bg-gray-700\">");
    client.println("<div class=\"h-6 bg-gradient-to-r from-cyan-500 to-indigo-500 rounded-full font-bold text-slate-200\" style=\"width:");
    client.print(soilPercents[1]);
    client.print("%\">");
    client.print(soilPercents[1]);
    client.println("%</div></div>");
    
    // 차트
    client.print("<div class=\"p-5 shadow-lg rounded-lg overflow-hidden\">\
                    <div class=\"py-3 px-5 bg-gray-50 font-bold\">");
    client.print(String(year) + "-" + String(month) + "-" + String(day));
    client.println(" Moisture of pot2</div>\
                    <canvas class=\"p-1\" id=\"chartLine2\"></canvas>\
                </div>");

    client.println("</div></div></div>");

    // Log창
    client.println("<div class=\"m-2 mb-0 p-2 pl-4 rounded-t-lg bg-slate-400\">\
        <span class=\"text-4xl font-bold\">Log</span>\
    </div>");
    client.println("<div class=\"m-2 mt-0 p-2 pl-4 h-full bg-slate-600 font-bold text-white overflow-scroll\" style=\"height: 30vh;\">");
    for(int i = logcount; i >= 0; i--){
        client.print("<div>");
        client.print(logs[i]);
        client.println("</div>");
    }
    client.println("</div>");
    client.println("</body>");

    // Line 차트
    client.println("<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>");
    client.println("<script>");


    client.print("const humidity = ");
    client.print("[[");
    for(int i = 0; i < 24; i++){
        client.print(soilHumidity1[i]);
        client.print(",");
    }
    client.print("],[");
    for(int i = 0; i < 24; i++){
        client.print(soilHumidity2[i]);
        client.print(",");
    }
    client.println("]]");

    client.println("const labels = [\"0\", \"1\", \"2\", \"3\", \"4\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"12\", \"13\", \"14\", \"15\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\", \"23\"]");
    client.println("const data = [{");
    client.println("labels: labels,");
    client.println("datasets: [{");
    client.println("label: \"Moisture\",");
    client.println("backgroundColor: \"hsl(252, 82.9%, 67.8%)\",");
    client.println("borderColor: \"hsl(252, 82.9%, 67.8%)\",");
    client.println("data: humidity[0],},");
    client.println("],},{");
    client.println("labels: labels,");
    client.println("datasets: [{");
    client.println("label: \"Moisture\",");
    client.println("backgroundColor: \"hsl(252, 82.9%, 67.8%)\",");
    client.println("borderColor: \"hsl(252, 82.9%, 67.8%)\",");
    client.println("data: humidity[1],},],}]");
    client.println("var chartLine = new Chart(");
    client.println("document.getElementById(\"chartLine1\"),");
    client.println("{type: \"line\",");
    client.println("data:data[0],");
    client.println("options: {},})");
    client.println("var chartLine2 = new Chart(");
    client.println("document.getElementById(\"chartLine2\"),");
    client.println("{type: \"line\",");
    client.println("data:data[1],");
    client.println("options: {},})");
    
    client.println("</script>");

    // html 닫기
    client.println("</html>");
}