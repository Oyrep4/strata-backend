<?php
header("Content-Type: application/json");

$db = pg_connect(getenv('DATABASE_URL'));

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
