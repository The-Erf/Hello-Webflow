<?php

/// Developed with ♥ By The.Erf

$target_ip = 'example.webflow.io'; //// Webflow Destination Site
$target_port = '443';

$cache_dir = 'cache/';
$log_file = 'proxy_errors.log';

function cleanOldCache($cache_dir) {
    $files = glob($cache_dir . '*.cache');
    $expiration_time = 24 * 60 * 60; // 24 hours
    foreach ($files as $file) {
        if (time() - filemtime($file) > $expiration_time) {
            unlink($file);
        }
    }
}

function logError($message, $log_file) {
    if (!file_exists($log_file)) {
        file_put_contents($log_file, '');
        chmod($log_file, 0644);
    }
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, $log_file);
}

if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

cleanOldCache($cache_dir);
$cache_file = $cache_dir . md5($_SERVER['REQUEST_URI']) . '.cache';

if (file_exists($cache_file) && (time() - filemtime($cache_file) < 3600)) {
    header('X-Cache: HIT');
    echo file_get_contents($cache_file);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$target_ip:$target_port" . $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $params = $_GET;
} else {
    $params = $_POST;
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
}

$headers = array();
foreach (getallheaders() as $name => $value) {
    if (!in_array($name, ['Host', 'Content-Length', 'Transfer-Encoding', 'Accept-Encoding'])) {
        $headers[] = "$name: $value";
    }
}
$headers[] = 'Accept-Encoding: gzip, deflate';
$headers[] = 'Cache-Control: no-store'; 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    logError('cURL Error: ' . curl_error($ch), $log_file);
    die('An error occurred while processing your request. Please try again later.');
}

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

if (stripos($header, 'Content-Encoding: gzip') !== false) {
    $body = gzinflate(substr($body, 10));
} elseif (stripos($header, 'Content-Encoding: deflate') !== false) {
    $body = gzinflate($body);
}

if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && $_SERVER['REQUEST_METHOD'] == 'GET') {
    if (file_put_contents($cache_file, $body) === false) {
        logError('Failed to write cache file: ' . $cache_file, $log_file);
    }
}

curl_close($ch);

foreach (explode("\r\n", $header) as $header_line) {
    if (stripos($header_line, 'Transfer-Encoding') !== false || stripos($header_line, 'Content-Encoding') !== false) continue;
    header($header_line);
}
header('X-Cache: MISS');

echo $body;
?>
