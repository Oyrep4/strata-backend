<?php
header("Content-Type: application/json");

$url = parse_url(getenv('DATABASE_URL'));

$host = $url["host"];
$port = $url["port"];
$user = $url["user"];
$pass = $url["pass"];
$dbname = ltrim($url["path"], "/");

$conn_str = "host=$host port=$port dbname=$dbname user=$user password=$pass sslmode=require";

$db = pg_connect($conn_str);

if (!$db) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Existing insert logic below
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
    echo json_encode(['error' => 'Insert failed']);
}
?>
