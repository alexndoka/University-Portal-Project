<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος ασφαλείας
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    die("Πρόσβαση μόνο για φοιτητές.");
}

// ελέγχουμε αν μας δόθηκε ID μαθήματος
if (!isset($_GET['course_id'])) {
    die("Δεν επιλέχθηκε μάθημα.");
}

$course_id = $_GET['course_id'];

// ανάκτηση των εργασιών για αυτό το μάθημα
$stmt = $conn->prepare("SELECT * FROM assignments WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Εργασίες Μαθήματος</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1>Εργασίες</h1>
        <nav>
            <a href="view_courses.php" class="btn">Πίσω στα Μαθήματα</a>
            <a href="logout.php" class="btn" style="border-color: #ff4d4d; color: #ff4d4d;">Αποσύνδεση</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2 style="margin-top: 20px;">Λίστα Εργασιών</h2>

    <?php if ($result->num_rows > 0): ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white;">
            <thead>
            <tr style="background: #333; color: white;">
                <th style="padding: 10px; text-align: left;">Τίτλος</th>
                <th style="padding: 10px; text-align: left;">Προθεσμία</th>
                <th style="padding: 10px; text-align: left;">Ενέργεια</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid #ccc;">
                    <td style="padding: 10px;">
                        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                        <small><?php echo htmlspecialchars($row['description']); ?></small>
                    </td>
                    <td style="padding: 10px; color: #d9534f;">
                        <?php echo date("d/m/Y H:i", strtotime($row['deadline'])); ?>
                    </td>
                    <td style="padding: 10px;">
                        <a href="submit_assignment.php?assignment_id=<?php echo $row['assignment_id']; ?>"
                           style="color: blue; font-weight: bold;">Υποβολή &rarr;</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="margin-top: 20px;">Δεν υπάρχουν ενεργές εργασίες για αυτό το μάθημα.</p>
    <?php endif; ?>
</div>

</body>
</html>