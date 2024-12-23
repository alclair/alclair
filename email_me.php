<?php
// Recipient email address
// $to = "tyler.folsom@gmail.com";
$to = "tyler@alclair..com";

// Get visitor's IP address
$ip = $_SERVER['REMOTE_ADDR'];

// Get geolocation info (optional)
$geoData = file_get_contents("https://ipinfo.io/$ip/json");
$geoInfo = json_decode($geoData, true);
$city = $geoInfo['city'] ?? 'Unknown';
$region = $geoInfo['region'] ?? 'Unknown';
$country = $geoInfo['country'] ?? 'Unknown';

// Email subject and body
$subject = "Tracking Info: New Click Detected";
$message = "A user clicked the link in your PDF.\n\n";
$message .= "IP Address: $ip\n";
$message .= "Location: $city, $region, $country\n";
$message .= "Time: " . date("Y-m-d H:i:s") . "\n";

// Send the email
mail($to, $subject, $message);

// Redirect to a thank-you page or content
// header("Location: https://example.com/thank-you");
header("Location: https://google.com");
exit();
?>