<?php
require "db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$result = $conn->query("SELECT id, full_name, gender, dob, class, phone, email, address, created_at
                       FROM students ORDER BY id DESC");

$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()["total"];
$maleStudents = $conn->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'Male'")->fetch_assoc()["total"];
$femaleStudents = $conn->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'Female'")->fetch_assoc()["total"];

$message = "";
if (isset($_GET["success"])) $message = "Student added successfully.";
if (isset($_GET["updated"])) $message = "Student updated successfully.";
if (isset($_GET["deleted"])) $message = "Student deleted successfully.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iyunga Secondary School | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">
        <h2>IYUNGA SECONDARY SCHOOL</h2>
        <p>Education for Discipline and Excellence</p>
    </div>

    <nav>
        <a href="#home">Home</a>
        <a href="#register">Add Student</a>
        <a href="#students">Students</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </nav>
</header>

<?php if ($message): ?>
    <div class="alert success"><?= e($message) ?></div>
<?php endif; ?>

<section class="hero" id="home">
    <div class="hero-text">
        <p class="welcome">WELCOME TO</p>
        <h1>Iyunga Secondary School</h1>
        <h3>Student Information Management System</h3>
        <p>Manage student information easily, quickly and securely.</p>

        <div class="buttons">
            <a href="#students" class="btn">VIEW STUDENTS</a>
            <a href="#register" class="btn secondary">+ ADD STUDENT</a>
        </div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <strong><?= e($totalStudents) ?></strong>
            <span>Total Students</span>
        </div>
        <div class="stat-card">
            <strong><?= e($maleStudents) ?></strong>
            <span>Male</span>
        </div>
        <div class="stat-card">
            <strong><?= e($femaleStudents) ?></strong>
            <span>Female</span>
        </div>
    </div>
</section>

<section class="container" id="register">
    <div class="section-title">
        <p>STUDENT MANAGEMENT</p>
        <h2>Student Registration</h2>
    </div>

    <form action="add_student.php" method="POST" class="student-form">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" required>
        </div>

        <div class="form-group">
            <label>Class</label>
            <select name="class" required>
                <option value="">Select Class</option>
                <option>Form 1</option>
                <option>Form 2</option>
                <option>Form 3</option>
                <option>Form 4</option>
                <option>Form 5</option>
                <option>Form 6</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" placeholder="e.g. 0712345678">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="student@email.com">
        </div>

        <div class="form-group full">
            <label>Address</label>
            <input type="text" name="address" placeholder="Enter address">
        </div>

        <button type="submit" class="save-btn">SAVE STUDENT</button>
    </form>
</section>

<section class="container" id="students">
    <div class="table-header">
        <div class="section-title">
            <p>DATABASE RECORDS</p>
            <h2>Student Records</h2>
        </div>
        <input type="text" id="search" placeholder="Search by name, class or email...">
    </div>

    <div class="table-box">
        <table id="studentTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Class</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $number = 1; ?>
                <?php while ($student = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $number++ ?></td>
                        <td><?= e($student["full_name"]) ?></td>
                        <td><?= e($student["gender"]) ?></td>
                        <td><?= e($student["dob"]) ?></td>
                        <td><?= e($student["class"]) ?></td>
                        <td><?= e($student["phone"]) ?></td>
                        <td><?= e($student["email"]) ?></td>
                        <td><?= e($student["address"]) ?></td>
                        <td class="actions">
                            <a class="edit" href="edit_student.php?id=<?= (int)$student["id"] ?>">Update</a>
                            <form action="delete_student.php" method="POST" onsubmit="return confirm('Delete this student?');">
                                <input type="hidden" name="id" value="<?= (int)$student["id"] ?>">
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="empty">No student records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="about" id="about">
    <div class="section-title center">
        <p>ABOUT US</p>
        <h2>Iyunga Secondary School</h2>
    </div>
    <p>Iyunga Secondary School is committed to providing quality education, discipline and excellence to students.</p>
</section>

<footer id="contact">
    <p>© 2026 Iyunga Secondary School. All Rights Reserved.</p>
    <p>Student Information Management System</p>
</footer>

<script src="script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
