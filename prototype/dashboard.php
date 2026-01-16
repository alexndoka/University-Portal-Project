<?php
session_start();
require 'includes/db_connect.php';

// αν ο χρήστης δεν είναι συνδεδεμένος, ανακατεύθυνση στη σελίδα log in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// απενεργοποίηση της cache του browser
// η εντολή λέει στον browser να μην κρατάει αντίγραφο της σελίδας
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


// ανάκτηση ρόλου και ονόματος από το session
$user_role = $_SESSION['role_id']; // 1 = φοιτητής, 2 = καθηγητής
$user_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Πανεπιστήμιο</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-container { padding: 30px; }
        /* χρώμα φόντου ανάλογα με τον ρόλο */
        .welcome-banner {
            background: <?php echo ($user_role == 2) ? '#005f73' : '#94d2bd'; ?>;
            color: <?php echo ($user_role == 2) ? '#fff' : '#000'; ?>;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        /* grid layout για τις κάρτες επιλογών */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h3 { margin-bottom: 10px; color: #333; }
        .btn-dash {
            display: inline-block;
            background: #333;
            color: #fff;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1>Πύλη Πανεπιστημίου</h1>
        <nav>
            <span style="color: white; margin-right: 15px;">
                <?php echo htmlspecialchars($user_name); ?>
                (<?php echo ($user_role == 1) ? 'Φοιτητής' : 'Καθηγητής'; ?>)
            </span>
            <a href="logout.php" class="btn" ...><i class="fas fa-sign-out-alt"></i> Αποσύνδεση</a>
        </nav>
    </div>
</header>

<main class="container dashboard-container">

    <div class="welcome-banner">
        <h2>Καλώς ήρθατε στο Dashboard, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p>Ρόλος: <?php echo ($user_role == 1) ? 'Φοιτητής' : 'Καθηγητής'; ?></p>
    </div>

    <?php if ($user_role == 2): ?>
        <h2 style="margin-bottom: 15px; border-bottom: 2px solid #333;">Πίνακας Ελέγχου Καθηγητή</h2>

        <div class="action-grid">
            <div class="card">
                <h3><i class="fas fa-plus-circle"></i> Δημιουργία Μαθήματος</h3>
                <p>Δημιουργήστε νέο μάθημα για τους φοιτητές.</p>
                <a href="create_course.php" class="btn-dash">Νέο Μάθημα &rarr;</a>
            </div>

            <div class="card">
                <h3>Ανάρτηση Εργασίας</h3>
                <p>Αναθέστε εργασίες στα μαθήματά σας.</p>
                <a href="create_assignment.php" class="btn-dash">Νέα Εργασία &rarr;</a>
            </div>

            <div class="card">
                <h3>Βαθμολόγηση</h3>
                <p>Δείτε τις υποβολές και βαθμολογήστε.</p>
                <a href="view_submissions.php" class="btn-dash">Προβολή Υποβολών &rarr;</a>
            </div>
        </div>

    <?php elseif ($user_role == 1): ?>
        <h2 style="margin-bottom: 15px; border-bottom: 2px solid #333;">Πίνακας Ελέγχου Φοιτητή</h2>

        <div class="action-grid">
            <div class="card">
                <h3><i class="fas fa-book"></i> Τα Μαθήματά μου</h3>
                <p>Δείτε όλα τα διαθέσιμα μαθήματα.</p>
                <a href="view_courses.php" class="btn-dash">Προβολή Μαθημάτων &rarr;</a>
            </div>

            <div class="card">
                <h3><i class="fas fa-graduation-cap"></i> Βαθμολογίες</h3>
                <p>Δείτε τους βαθμούς σας στις εργασίες.</p>
                <a href="my_grades.php" class="btn-dash">Οι Βαθμοί μου &rarr;</a>
            </div>
        </div>

    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Πανεπιστήμιο Θεσσαλονίκης</p>
</footer>

</body>
</html>