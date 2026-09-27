<?php
session_start();

// Supabase Postgres connection using PDO with environment variables
$host = getenv('DB_HOST') ?: "aws-0-eu-north-1.pooler.supabase.com";
$port = getenv('DB_PORT') ?: 6543;
$dbname = getenv('DB_NAME') ?: "postgres";
$user = getenv('DB_USER') ?: "postgres.idgrfypntnjlphmqqgnp";
$password = getenv('DB_PASSWORD') ?: "2brhUakI42BbmuwG";

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", 
        $user, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    // Uncomment this to test connection
    // echo "✅ Connected successfully with PDO!";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
