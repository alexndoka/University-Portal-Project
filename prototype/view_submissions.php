<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος ασφαλείας, πρόσβαση μόνο σε καθηγητές (role_id = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Πρόσβαση μόνο για καθηγητές.");
}

$professor_id = $_SESSION['user_id'];
$message = "";

// λογική βαθμολόγησης, όταν ο καθηγητής πατήσει το κουμπί
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];


    $stmt = $conn->prepare("UPDATE submissions SET grade = ? WHERE submission_id = ?");
    $stmt->bind_param("ii", $grade, $submission_id);

    if ($stmt->execute()) {
        $message = "Ο βαθμός καταχωρήθηκε επιτυχώς!";
    } else {
        $message = "Σφάλμα: " . $stmt->error;
    }
    $stmt->close();
}

// SQL έρώτημα, ανάκτηση όλων των υποβολών για τα μαθήματα του καθηγητή
$sql = "SELECT s.submission_id, s.submission_text, s.submitted_at, s.grade,
               a.title AS assignment_title, 
               u.username AS student_name,
               c.title AS course_title
        FROM submissions s
        JOIN assignments a ON s.assignment_id = a.assignment_id
        JOIN courses c ON a.course_id = c.course_id
        JOIN users u ON s.student_id = u.user_id
        WHERE c.professor_id = ?
        ORDER BY s.submitted_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Βαθμολόγηση Υποβολών</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1><i class="fas fa-check-double"></i> Υποβολές Φοιτητών</h1>
        <nav>
            <a href="dashboard.php" class="btn"><i class="fas fa-arrow-left"></i> Πίσω στο Dashboard</a>
            <a href="logout.php" class="btn" style="border-color: #ff4d4d; color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Αποσύνδεση</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2 style="margin-top: 20px;">Λίστα Υποβολών</h2>

    <?php if ($message != ""): ?>
        <p style="color: green; font-weight: bold; background: #e6ffe6; padding: 10px; border-radius: 5px;">
            <i class="fas fa-check-circle"></i> <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <thead>
                <tr style="background: #005f73; color: white;">
                    <th style="padding: 10px;">Φοιτητής</th>
                    <th style="padding: 10px;">Μάθημα / Εργασία</th>
                    <th style="padding: 10px;">Απάντηση</th>
                    <th style="padding: 10px;">Ημερομηνία</th>
                    <th style="padding: 10px;">Βαθμός (0-100)</th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px; font-weight: bold;">
                            <i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($row['student_name']); ?>
                        </td>
                        <td style="padding: 10px;">
                            <small style="color: #666;"><?php echo htmlspecialchars($row['course_title']); ?></small><br>
                            <strong><?php echo htmlspecialchars($row['assignment_title']); ?></strong>
                        </td>
                        <td style="padding: 10px; background: #f9f9f9; font-style: italic; color: #555;">
                            "<?php echo htmlspecialchars($row['submission_text']); ?>"
                        </td>
                        <td style="padding: 10px;">
                            <?php echo date("d/m/Y H:i", strtotime($row['submitted_at'])); ?>
                        </td>
                        <td style="padding: 10px;">
                            <form method="POST" action="view_submissions.php" style="display: flex; align-items: center;">
                                <input type="hidden" name="submission_id" value="<?php echo $row['submission_id']; ?>">

                                <input type="number" name="grade" min="0" max="100" required
                                       value="<?php echo $row['grade']; ?>"
                                       placeholder="-"
                                       style="width: 60px; padding: 5px; margin-right: 5px; text-align: center; border: 1px solid #ccc; border-radius: 4px;">

                                <button type="submit" title="Αποθήκευση Βαθμού"
                                        style="background: #28a745; color: white; border: none; padding: 6px 10px; cursor: pointer; border-radius: 4px;">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="margin-top: 20px; font-style: italic;">Δεν υπάρχουν υποβολές για βαθμολόγηση ακόμα.</p>
    <?php endif; ?>
</div>

</body>
</html>