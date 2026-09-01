<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$full_name = trim($_POST["full_name"] ?? "");
$gender    = trim($_POST["gender"] ?? "");
$dob       = trim($_POST["dob"] ?? "");
$class     = trim($_POST["class"] ?? "");
$phone     = trim($_POST["phone"] ?? "");
$email     = trim($_POST["email"] ?? "");
$address   = trim($_POST["address"] ?? "");

if ($full_name === "" || $gender === "" || $dob === "" || $class === "") {
    die("Please fill in all required fields.");
}

$sql = "INSERT INTO students
        (full_name, gender, dob, `class`, phone, email, address)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "sssssss",
    $full_name,
    $gender,
    $dob,
    $class,
    $phone,
    $email,
    $address
);

if ($stmt->execute()) {
    header("Location: index.php?success=1#students");
    exit;
}

die("Error adding student: " . $stmt->error);
?>
