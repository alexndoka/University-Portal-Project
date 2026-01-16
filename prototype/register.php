<?php
// συμπερίληψη του αρχείου σύνδεσης με τη βάση δεδομένων.
include 'includes/db_connect.php';

// μεταβλητή για την αποθήκευση μηνυμάτων προς τον χρήστη
$message = "";

// έλεγχος αν η φόρμα υποβλήθηκε
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // λήψη των δεδομένων από τη φόρμα.
    $username = $_POST["username"];

    // καθαρισμός του email για λόγους ασφαλείας.
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // κρυπτογράφηση του κωδικού πρόσβασης πριν την αποθήκευση.
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $role = $_POST["role"]; // ο ρόλος που επέλεξε ο χρήστης (student/professor)
    $secret_code = $_POST["secret_code"]; // ο μυστικός κωδικός που έδωσε η γραμματεία

    // επαλήθευση μυστικών κωδικών
    // ο φοιτητής πρέπει να δώσει "STUD2025" και ο καθηγητής "PROF2025"
    if (($role == "student" && $secret_code !== "STUD2025") || ($role === "professor" && $secret_code !== "PROF2025")) {
        $message = "Μη έγκυρος κωδικός εγγραφής για τον επιλεγμένο ρόλο!";
    } else {
        // αντιστοίχιση ρόλου σε ID για τη βάση δεδομένων
        // 1 = Φοιτητής, 2 = Καθηγητής
        if ($role === "student") {
            $role_id = 1;
        } else {
            $role_id = 2;
        }

        // εισαγωγή στη βάση δεδομένων
        // χρησιμοποιούμε '?' για προστασία από sql injection.
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");

        // σύνδεση παραμέτρων με τα ερωτηματικά.
        // "sssi" σημαίνει: String (username), String (email), String (password), Integer (role_id).
        $stmt->bind_param("sssi", $username, $email, $password, $role_id);

        if ($stmt->execute()) {
            // αν η εγγραφή πετύχει, εμφανίζουμε μήνυμα και link για σύνδεση.
            $message = "Η εγγραφή ολοκληρώθηκε! <a href='login.php' style='color:green; font-weight:bold;'>Συνδεθείτε εδώ</a>.";
        } else {
            // αν αποτύχει, εμφανίζουμε το σφάλμα.
            $message = "Σφάλμα: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <title>Εγγραφή χρήστη</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-content">
        <h1>Πύλη Πανεπιστημίου</h1>
        <nav>
            <a href="index.php" class="btn">Αρχική</a>
            <a href="login.php" class="btn">Σύνδεση</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="form-container">
        <h2>Εγγραφή Χρήστη</h2>

        <p style="text-align: center; font-weight: bold; padding: 10px; color: #d9534f;"><?php echo $message; ?></p>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label>Όνομα Χρήστη:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Επιλογή ρόλου:</label>
                <select name="role" required>
                    <option value="">--Επιλέξτε--</option>
                    <option value="student">Φοιτητής</option>
                    <option value="professor">Καθηγητής</option>
                </select>
            </div>

            <div class="form-group">
                <label>Κωδικός εγγραφής: (Παρέχεται από την γραμματεία)</label>
                <input type="text" name="secret_code" required>
            </div>

            <input type="submit" value="Εγγραφή" class="btn" style="width: 100%; border:none; background-color: #333; cursor:pointer;">
        </form>
    </div>
</div>
</body>
</html>