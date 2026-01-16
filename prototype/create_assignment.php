<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος ασφαλείας, μόνο Καθηγητές
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Δεν έχετε δικαίωμα πρόσβασης.");
}

$professor_id = $_SESSION['user_id'];
$message = "";

// επεξεργασία φόρμας, αποθήκευση εργασίας
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    $stmt = $conn->prepare("INSERT INTO assignments (course_id, title, description, deadline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $course_id, $title, $description, $deadline);

    if ($stmt->execute()) {
        $message = "Η εργασία αναρτήθηκε επιτυχώς!";
    } else {
        $message = "Σφάλμα: " . $stmt->error;
    }
    $stmt->close();
}

// ανάκτηση μαθημάτων του καθηγητή για το dropdown μενού
// πρέπει να βρούμε μόνο τα μαθήματα που έφτιαξε ο συγκεκριμένος καθηγητής
$courses_result = $conn->query("SELECT course_id, title FROM courses WHERE professor_id = $professor_id");
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Ανάρτηση Εργασίας</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1>Πύλη Πανεπιστημίου</h1>
        <nav>
            <a href="dashboard.php" class="btn">Πίσω στο Dashboard</a>
            <a href="logout.php" class="btn" style="border-color: #ff4d4d; color: #ff4d4d;">Αποσύνδεση</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="form-container">
        <h2>Ανάρτηση Νέας Εργασίας</h2>

        <?php if ($message != ""): ?>
            <p style="text-align: center; font-weight: bold; color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="create_assignment.php">

            <div class="form-group">
                <label>Επιλογή Μαθήματος:</label>
                <select name="course_id" required>
                    <option value="">-- Διαλέξτε Μάθημα --</option>
                    <?php
                    // εμφάνιση των μαθημάτων στη λίστα
                    if ($courses_result->num_rows > 0) {
                        while($row = $courses_result->fetch_assoc()) {
                            echo "<option value='" . $row['course_id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>Δεν βρέθηκαν μαθήματα. Φτιάξτε ένα πρώτα!</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Τίτλος Εργασίας:</label>
                <input type="text" name="title" required placeholder="π.χ. Εργασία 1: HTML Basics">
            </div>

            <div class="form-group">
                <label>Προθεσμία Υποβολής (Deadline):</label>
                <input type="datetime-local" name="deadline" required>
            </div>

            <div class="form-group">
                <label>Περιγραφή / Οδηγίες:</label>
                <textarea name="description" rows="5" style="width:100%; padding:10px;" required placeholder="Γράψτε τις οδηγίες της εργασίας..."></textarea>
            </div>

            <input type="submit" value="Ανάρτηση" class="btn" style="width: 100%; border:none; background-color: #333; cursor:pointer;">
        </form>
    </div>
</div>

</body>
</html>