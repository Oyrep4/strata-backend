<?php
header("Content-Type: application/json");

$db = pg_connect(getenv('DATABASE_URL'));

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($name) || empty($email)) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and email required']);
    exit;
}

$result = pg_query_params($db, "INSERT INTO members (name, email) VALUES ($1, $2)", [$name, $email]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to insert member']);
}
?>
