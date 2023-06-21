#include <iostream>
#include <string>
#include <cstring>
#include <fstream>
#include <curl/curl.h>
#include <unistd.h> // Pour la fonction sleep
#include <cstdlib> // Pour la fonction rand et system
#include <iomanip> // Pour setprecision
#include <vector>  // Pour std::vector

#define N_PIXEL 16

double ptat;
double pix_data[N_PIXEL];
bool flip = false;

std::vector<std::string> salles = {"101", "105_(HG)", "106", "110", "111", "120", "122", "123", "124", "126", "127", "128"};

// Callback function to write response data
static size_t WriteCallback(void* contents, size_t size, size_t nmemb, std::string* response) {
    size_t totalSize = size * nmemb;
    response->append(static_cast<char*>(contents), totalSize);
    return totalSize;
}

// Function to send data to server
void sendDataToServer(const std::string& url, double temperature) {
    // Initialization of a curl handle
    CURL *curl;
    CURLcode res;
    std::string response;

    curl_global_init(CURL_GLOBAL_DEFAULT);
    curl = curl_easy_init();
    if(curl) {
        // Set the URL for the GET request
        std::string completeUrl = url + std::to_string(temperature);
        curl_easy_setopt(curl, CURLOPT_URL, completeUrl.c_str());
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteCallback);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response);

        // Perform the request and check for errors
        res = curl_easy_perform(curl);
        if(res != CURLE_OK) {
            std::cerr << "curl_easy_perform() failed: " << curl_easy_strerror(res) << std::endl;
        }

        curl_easy_cleanup(curl);
    }
    curl_global_cleanup();
}

// Function to read data from the sensor
void readSensorData() {
    double lowerLimit = flip ? 19.1 : 10.0;
    double upperLimit = flip ? 40.0 : 19.0;
    flip = !flip;

    ptat = lowerLimit + static_cast<double>(rand()) / (static_cast<double>(RAND_MAX / (upperLimit - lowerLimit)));
    for (int i = 0; i < N_PIXEL; i++) {
        pix_data[i] = lowerLimit + static_cast<double>(rand()) / (static_cast<double>(RAND_MAX / (upperLimit - lowerLimit)));
    }
}

// Function to format sensor data as a string
std::string formatSensorDataToString(double ptat, double* pix_data, int num_pixels) {
    std::string sensorData = "{";

    // Add PTAT
    sensorData += "\"ptat\":" + std::to_string(ptat) + ",";

    // Add pixel data
    sensorData += "\"pixels\":[";
    for (int i = 0; i < num_pixels; i++) {
        sensorData += std::to_string(pix_data[i]);
        if (i < num_pixels - 1) {
            sensorData += ",";
        }
    }
    sensorData += "]";

    sensorData += "}";

    return sensorData;
}

int main() {
    srand(time(NULL)); // Initialize random seed

    // Infinite loop to continuously send data every 5 minutes
    while (true) {
        
        for (auto& no_salle : salles) {
            // Set the URL for sending data
            std::string url = "http://172.16.108.120/projet_sn_bts_anthony/Projet_bts2023/insert_mesure.php?no_salle=" + no_salle + "&temperature=";

            // Read data from the sensor
            readSensorData();

            // Check presence based on temperature threshold
            bool presence = ptat > 15.0;

            // Format the sensor data as a string
            std::string sensorData = formatSensorDataToString(ptat, pix_data, N_PIXEL);

            // Send the sensor data to the server
            sendDataToServer(url, ptat);
            //log in console
            std::cout << "Salle " << no_salle << " : " << std::setprecision(2) << ptat << "°C envoyé au serveur" << std::endl;
        }
        // Delay between readings
        std::cout << "Attente de 5 minutes..." << std::endl;
        sleep(60 * 5); // 300 seconds = 5 minutes
    }

    return 0;
}
