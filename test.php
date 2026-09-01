<?php
require "db.php";

echo "<h2>Database Connection Test</h2>";
echo "<p style='color:green;font-weight:bold'>Database connected successfully!</p>";

$check = $conn->query("SHOW TABLES LIKE 'students'");

if ($check && $check->num_rows > 0) {
    echo "<p style='color:green'>Table <b>students</b> exists.</p>";

    $count = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()["total"];
    echo "<p>Total students: <b>" . (int)$count . "</b></p>";
} else {
    echo "<p style='color:red'>Table <b>students</b> was not found. Import setup.sql in phpMyAdmin.</p>";
}

$conn->close();
?>
