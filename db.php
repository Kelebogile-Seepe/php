<?php
try {
    $serverName = "KELEBOGILEPC";
    $database = "Disaster_Allivaition";

    // Use Windows Authentication (no username/password)
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database");

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connected successfully!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
