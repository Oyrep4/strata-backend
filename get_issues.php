<?php
header("Content-Type: application/json");

$db = pg_connect(getenv('DATABASE_URL'));

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$result = pg_query($db, "SELECT id, title, description, submitted_at FROM issues ORDER BY submitted_at DESC");

$issues = [];

while ($row = pg_fetch_assoc($result)) {
    $issues[] = $row;
}

echo json_encode($issues);
?>
