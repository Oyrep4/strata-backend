<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://strata-management-orcin.vercel.app");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

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
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$added_by = $_COOKIE['user_id'] ?? null;

error_log("Adding member: $name, $email, added_by=$added_by");

if (!$name || !$email) {
    echo json_encode(['success' => false, 'error' => 'Missing name or email']);
    exit;
}

$result = pg_query_params(
    $db,
    "INSERT INTO members (name, email, added_by) VALUES ($1, $2, $3)",
    [$name, $email, $added_by]
);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => pg_last_error($db)]);
}
