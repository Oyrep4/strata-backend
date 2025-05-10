<?php
header("Content-Type: application/json");

$url = parse_url(getenv('DATABASE_URL'));

$host = $url["host"];
$port = isset($url["port"]) ? $url["port"] : 5432;

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

$result = pg_query($db, "SELECT id, title, content, posted_at FROM announcements ORDER BY posted_at DESC");

$announcements = [];

while ($row = pg_fetch_assoc($result)) {
    $announcements[] = $row;
}

echo json_encode($announcements);
?>
