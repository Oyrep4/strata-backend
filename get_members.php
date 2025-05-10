<?php
header("Content-Type: application/json");

$db = pg_connect(getenv('DATABASE_URL'));

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
