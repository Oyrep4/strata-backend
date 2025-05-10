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
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

if (empty($title) || empty($content)) {
    http_response_code(400);
    echo json_encode(['error' => 'Title and content required']);
    exit;
}

$result = pg_query_params($db, "INSERT INTO announcements (title, content) VALUES ($1, $2)", [$title, $content]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to insert announcement']);
}
?>
