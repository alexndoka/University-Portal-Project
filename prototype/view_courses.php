<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος ασφαλείας, μόνο φοιτητές, role_id = 1
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    die("Πρόσβαση μόνο για φοιτητές.");
}

// ανάκτηση όλων των μαθημάτων μαζί με το όνομα του καθηγητή
$sql = "SELECT courses.course_id, courses.title, courses.description, users.username as professor_name 
        FROM courses 
        JOIN users ON courses.professor_id = users.user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Διαθέσιμα Μαθήματα</title>
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
    <h2 style="margin-top: 20px;">Διαθέσιμα Μαθήματα</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p style="font-style: italic; color: #555;">Διδάσκων: <?php echo htmlspecialchars($row['professor_name']); ?></p>
                    <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">
                    <p><?php echo htmlspecialchars($row['description']); ?></p>

                    <a href="student_assignments.php?course_id=<?php echo $row['course_id']; ?>"
                       class="btn" style="background: #333; color: white; display: block; text-align: center; margin-top: 15px;">
                        Προβολή Εργασιών
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Δεν υπάρχουν διαθέσιμα μαθήματα ακόμα.</p>
        <?php endif; ?>

    </div>
</div>

</body>
</html>