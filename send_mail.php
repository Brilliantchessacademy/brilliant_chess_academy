<?php
// Simple mail endpoint for local XAMPP testing.
// Place this file in your XAMPP htdocs folder alongside index.html or adjust fetch path accordingly.
// WARNING: This is a minimal example and not production-ready. Add sanitization, rate-limiting and CSRF protections before public use.

header('Content-Type: application/json');

// only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Only POST allowed']);
    exit;
}

// read POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Name, email and message are required']);
    exit;
}

// basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid email']);
    exit;
}

$to = 'harshini107218@gmail.com'; // destination email as requested (updated)
$subject = 'Website contact from ' . $name;
$body = "Name: $name\nEmail: $email\n\nMessage:\n$message\n";
$headers = 'From: ' . $email . "\r\n" . 'Reply-To: ' . $email . "\r\n" . 'X-Mailer: PHP/' . phpversion();

// try to send
$sent = false;
try {
    $sent = mail($to, $subject, $body, $headers);
} catch (Exception $e) {
    $sent = false;
}

if ($sent) {
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Mail sending failed (check server configuration)']);
}
