<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος ασφαλείας, πρόσβαση μόνο σε φοιτητές (role_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    die("Πρόσβαση μόνο για φοιτητές.");
}

$student_id = $_SESSION['user_id'];

// ανάκτηση βαθμολογιών του φοιτητή
$sql = "SELECT s.grade, s.submitted_at, a.title AS assignment_title, c.title AS course_title
        FROM submissions s
        JOIN assignments a ON s.assignment_id = a.assignment_id
        JOIN courses c ON a.course_id = c.course_id
        WHERE s.student_id = ?
        ORDER BY s.submitted_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Οι Βαθμολογίες μου</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1><i class="fas fa-graduation-cap"></i> Η Πρόοδός μου</h1>
        <nav>
            <a href="dashboard.php" class="btn"><i class="fas fa-arrow-left"></i> Πίσω στο Dashboard</a>
            <a href="logout.php" class="btn" style="border-color: #ff4d4d; color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Αποσύνδεση</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2 style="margin-top: 20px;">Αναλυτική Βαθμολογία</h2>

    <?php if ($result->num_rows > 0): ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <thead>
            <tr style="background: #94d2bd; color: #000;">
                <th style="padding: 10px; text-align: left;">Μάθημα</th>
                <th style="padding: 10px; text-align: left;">Εργασία</th>
                <th style="padding: 10px; text-align: left;">Ημερομηνία Υποβολής</th>
                <th style="padding: 10px; text-align: center;">Βαθμός</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;"><i class="fas fa-book"></i> <?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($row['assignment_title']); ?></td>
                    <td style="padding: 10px;"><?php echo date("d/m/Y", strtotime($row['submitted_at'])); ?></td>
                    <td style="padding: 10px; text-align: center; font-weight: bold; font-size: 1.1em;">
                        <?php
                        if ($row['grade'] === NULL) {
                            // Προσθήκη εικονιδίου κλεψύδρας
                            echo "<span style='color: orange;'><i class='fas fa-hourglass-half'></i> Εκκρεμεί</span>";
                        } else {
                            // Προσθήκη εικονιδίου Check
                            echo "<span style='color: #28a745;'><i class='fas fa-check'></i> " . $row['grade'] . " / 100</span>";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top: 20px;">Δεν έχετε υποβάλει εργασίες ακόμα.</p>
    <?php endif; ?>
</div>

</body>
</html>