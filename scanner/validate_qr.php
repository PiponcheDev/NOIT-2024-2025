<?php
$encryption_key = hash('sha256', "NOIT", true);

// Decrypt function
function decryptData($encrypted, $key) {
    $data = base64_decode($encrypted);
    $iv = substr($data, 0, 16); // Extract IV
    $encrypted_data = substr($data, 16);
    return openssl_decrypt($encrypted_data, 'AES-256-CBC', $key, 0, $iv);
}

// Get the encrypted QR data from the request
$encrypted_token = $_POST['token'] ?? '';

$decrypted_token = decryptData($encrypted_token, $encryption_key);

if (strpos($decrypted_token, "Valid card!") === 0) {
    echo "✅ Valid card detected!";
} elseif (strpos($decrypted_token, "Valid ticket!") === 0) {
    echo "✅ Valid ticket detected!";
} else {
    echo "❌ Invalid QR code!";
}
?>