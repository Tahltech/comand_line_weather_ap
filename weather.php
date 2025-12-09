<?php

function getWeather($location) {
    $url = "https://wttr.in/" . urlencode($location) . "?format=j1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'WeatherCLI/1.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return null;
    }
    
    return json_decode($response, true);
}

function displayWeather($data, $location) {
    if (!$data || !isset($data['current_condition'][0])) {
        echo "Could not fetch weather data for '$location'\n";
        echo "Please check your internet connection or try a different location.\n";
        return;
    }
    
    $current = $data['current_condition'][0];
    $area = $data['nearest_area'][0] ?? [];
    
    echo "\n";
    echo "═══════════════════════════════════════════\n";
    echo "          WEATHER INFORMATION\n";
    echo "═══════════════════════════════════════════\n\n";
    
    if (!empty($area)) {
        $areaName = $area['areaName'][0]['value'] ?? 'Unknown';
        $country = $area['country'][0]['value'] ?? 'Unknown';
        $region = $area['region'][0]['value'] ?? '';
        
        echo "Location: $areaName";
        if ($region) echo ", $region";
        echo ", $country\n\n";
    }
    
    $desc = $current['weatherDesc'][0]['value'] ?? 'N/A';
    echo "Condition: $desc\n";

    $tempC = $current['temp_C'] ?? 'N/A';
    $tempF = $current['temp_F'] ?? 'N/A';
    $feelsLikeC = $current['FeelsLikeC'] ?? 'N/A';
    $feelsLikeF = $current['FeelsLikeF'] ?? 'N/A';
    
    echo "Temperature: {$tempC}°C ({$tempF}°F)\n";
    echo "Feels Like: {$feelsLikeC}°C ({$feelsLikeF}°F)\n";
    
    $humidity = $current['humidity'] ?? 'N/A';
    echo "Humidity: {$humidity}%\n";
    
    $windSpeed = $current['windspeedKmph'] ?? 'N/A';
    $windDir = $current['winddir16Point'] ?? 'N/A';
    echo "Wind: {$windSpeed} km/h {$windDir}\n";

    $pressure = $current['pressure'] ?? 'N/A';
    echo "Pressure: {$pressure} mb\n";

    $visibility = $current['visibility'] ?? 'N/A';
    echo "Visibility: {$visibility} km\n";
    
    $uvIndex = $current['uvIndex'] ?? 'N/A';
    echo "UV Index: {$uvIndex}\n";
    
    echo "\n═══════════════════════════════════════════\n\n";
}


echo "\n Check Your Current Weather Condition\n";
echo "─────────────────────────────\n\n";

echo "Enter your country (or city/location): ";
$location = trim(fgets(STDIN));

if (empty($location)) {
    echo "Location cannot be empty!\n";
    exit(1);
}

echo "\n Fetching weather data for '$location'...\n";


$weatherData = getWeather($location);
displayWeather($weatherData, $location);

//this is just a test to see if its commited 
