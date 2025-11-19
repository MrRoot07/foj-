<?php
/**
 * QR Code Helper Functions
 * Uses Google Charts API for QR code generation (simple, no library needed)
 */

function generateQRCode($data, $size = 200) {
    // Use Google Charts API to generate QR code
    $encoded_data = urlencode($data);
    $qr_url = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl={$encoded_data}";
    return $qr_url;
}

function saveQRCodeImage($data, $filename) {
    // Create directory if it doesn't exist
    $upload_dir = dirname(__FILE__) . '/../uploads/qr_codes/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Use a simple QR code API (alternative: use a PHP QR code library)
    // Using api.qrserver.com as a reliable free service
    $size = 300;
    $encoded_data = urlencode($data);
    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded_data}";
    
    // Download and save the QR code image
    $image_data = @file_get_contents($qr_url);
    
    if ($image_data !== false && strlen($image_data) > 0) {
        $file_path = $upload_dir . $filename;
        if (file_put_contents($file_path, $image_data)) {
            return 'server/uploads/qr_codes/' . $filename;
        }
    }
    
    return null;
}

?>

