<?php
// Supabase Postgres connection using PDO
$host = "aws-0-eu-north-1.pooler.supabase.com"; // Supabase host
$port = "6543";                                // Supabase port
$dbname = "postgres";                          // Supabase DB name
$user = "postgres.idgrfypntnjlphmqqgnp";       // Supabase DB user
$password = "2brhUakI42BbmuwG";                // Supabase password

try {
    // DSN (Data Source Name)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

    // Create PDO instance
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch as associative array
    ]);

    echo "✅ Connected successfully with PDO!<br>";

    // Test query
    $stmt = $pdo->query("SELECT version()");
    $row = $stmt->fetch();
    echo "Postgres version: " . $row['version'];

} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
