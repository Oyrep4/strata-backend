<?php
header("Content-Type: application/json");

$db = pg_connect(getenv('DATABASE_URL'));

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($title) || empty($description)) {
    http_response_code(400);
    echo json_encode(['error' => 'Title and description required']);
    exit;
}

$result = pg_query_params($db, "INSERT INTO issues (title, description) VALUES ($1, $2)", [$title, $description]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to insert issue']);
}
?>
