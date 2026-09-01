<?php
require "db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

    $sql = "UPDATE students
            SET full_name=?, gender=?, dob=?, `class`=?, phone=?, email=?, address=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssi",
        $full_name,
        $gender,
        $dob,
        $class,
        $phone,
        $email,
        $address,
        $id
    );

    if ($stmt->execute()) {
        header("Location: index.php?updated=1#students");
        exit;
    }

    die("Error updating student: " . $stmt->error);
}

$stmt = $conn->prepare("SELECT id, full_name, gender, dob, `class`, phone, email, address FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student | Iyunga Secondary School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">
        <h2>IYUNGA SECONDARY SCHOOL</h2>
        <p>Education for Discipline and Excellence</p>
    </div>
    <nav><a href="index.php">← Back to Students</a></nav>
</header>

<section class="container edit-page">
    <div class="section-title">
        <p>DATABASE MANAGEMENT</p>
        <h2>Update Student</h2>
    </div>

    <form method="POST" class="student-form">
        <input type="hidden" name="id" value="<?= (int)$student["id"] ?>">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= e($student["full_name"]) ?>" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="Male" <?= $student["gender"] === "Male" ? "selected" : "" ?>>Male</option>
                <option value="Female" <?= $student["gender"] === "Female" ? "selected" : "" ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= e($student["dob"]) ?>" required>
        </div>

        <div class="form-group">
            <label>Class</label>
            <select name="class" required>
                <?php foreach (["Form 1","Form 2","Form 3","Form 4","Form 5","Form 6"] as $class): ?>
                    <option <?= $student["class"] === $class ? "selected" : "" ?>><?= e($class) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= e($student["phone"]) ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($student["email"]) ?>">
        </div>

        <div class="form-group full">
            <label>Address</label>
            <input type="text" name="address" value="<?= e($student["address"]) ?>">
        </div>

        <div class="form-actions full">
            <a href="index.php#students" class="cancel-btn">CANCEL</a>
            <button type="submit" class="save-btn">UPDATE STUDENT</button>
        </div>
    </form>
</section>

<footer>
    <p>© 2026 Iyunga Secondary School. All Rights Reserved.</p>
</footer>
</body>
</html>
