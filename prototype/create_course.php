<?php
session_start();
require 'includes/db_connect.php';

// έλεγχος, ο χρήστης πρέπει να είναι συνδεδεμένος και να είναι Καθηγητής (role_id = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    // αν δεν είναι καθηγητής, τον πετάμε έξω, access denied
    die("Δεν έχετε δικαίωμα πρόσβασης σε αυτή τη σελίδα.");
}

$message = "";

// επεξεργασία φόρμας όταν πατηθεί το κουμπί "Δημιουργία"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $professor_id = $_SESSION['user_id']; // το ID του καθηγητή που το φτιάχνει

    // προετοιμασία ερωτήματος SQL για εισαγωγή
    $stmt = $conn->prepare("INSERT INTO courses (title, description, professor_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $professor_id);

    if ($stmt->execute()) {
        $message = "Το μάθημα δημιουργήθηκε επιτυχώς!";
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
    <title>Δημιουργία Μαθήματος</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1>Πύλη Πανεπιστημίου</h1>
        <nav>
            <a href="dashboard.php" class="btn"><i class="fas fa-arrow-left"></i> Πίσω στο Dashboard</a>
            <a href="logout.php" class="btn" style="border-color: #ff4d4d; color: #ff4d4d;">Αποσύνδεση</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="form-container">
        <h2>Δημιουργία Νέου Μαθήματος</h2>

        <?php if ($message != ""): ?>
            <p style="text-align: center; font-weight: bold; color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="create_course.php">
            <div class="form-group">
                <label>Τίτλος Μαθήματος:</label>
                <input type="text" name="title" required placeholder="π.χ. Προγραμματισμός Web">
            </div>

            <div class="form-group">
                <label>Περιγραφή:</label>
                <textarea name="description" rows="5" style="width:100%; padding:10px;" required placeholder="Περιγράψτε το μάθημα..."></textarea>
            </div>

            <input type="submit" value="Δημιουργία" class="btn" style="width: 100%; border:none; background-color: #333; cursor:pointer;">
        </form>
    </div>
</div>

</body>
</html>