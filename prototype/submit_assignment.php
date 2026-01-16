<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος ασφαλείας
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    die("Πρόσβαση μόνο για φοιτητές.");
}

$assignment_id = $_GET['assignment_id'] ?? null;
$message = "";

// αν ο φοιτητής πάτησε "υποβολή" (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assignment_id = $_POST['assignment_id'];
    $submission_text = $_POST['submission_text'];
    $student_id = $_SESSION['user_id'];

    // εισαγωγή στη βάση δεδομένων
    $stmt = $conn->prepare("INSERT INTO submissions (assignment_id, student_id, submission_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $assignment_id, $student_id, $submission_text);

    if ($stmt->execute()) {
        $message = "Η εργασία υποβλήθηκε επιτυχώς!";
    } else {
        $message = "Σφάλμα: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Υποβολή Εργασίας</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1>Υποβολή Εργασίας</h1>
        <nav>
            <a href="view_courses.php" class="btn">Πίσω</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="form-container">
        <h2>Απάντηση Εργασίας</h2>

        <?php if ($message != ""): ?>
            <p style="color: green; font-weight: bold; text-align: center;"><?php echo $message; ?></p>
            <p style="text-align: center;"><a href="view_courses.php">Επιστροφή στα μαθήματα</a></p>
        <?php else: ?>

            <form method="POST" action="submit_assignment.php">
                <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment_id); ?>">

                <div class="form-group">
                    <label>Κείμενο Υποβολής / Link:</label>
                    <textarea name="submission_text" rows="8" required
                              style="width: 100%; padding: 10px;"
                              placeholder="Γράψτε την απάντησή σας εδώ ή επικολλήστε ένα link (Google Drive/GitHub)..."></textarea>
                </div>

                <input type="submit" value="Οριστική Υποβολή" class="btn" style="width: 100%; background: #28a745; border: none; cursor: pointer;">
            </form>

        <?php endif; ?>
    </div>
</div>

</body>
</html>