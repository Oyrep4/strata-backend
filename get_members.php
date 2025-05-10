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

$result = pg_query($db, "SELECT id, name, email, joined_at FROM members ORDER BY joined_at DESC");

$members = [];

while ($row = pg_fetch_assoc($result)) {
    $members[] = $row;
}

echo json_encode($members);
?>
