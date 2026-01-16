<?php

// έναρξη του session
session_start();

// συμπερίληψη του αρχείου σύνδεσης με τη βάση δεδομένων
require 'includes/db_connect.php';

// μεταβλητή για την αποθήκευση μηνυμάτων λάθους
$message = "";

// έλεγχος αν η φόρμα υποβλήθηκε με τη μέθοδο POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // λήψη δεδομένων από την φόρμα
    $email = $_POST["email"];
    $password = $_POST["password"];

    // προετοιμασία sql ερωτήματος για την αναζήτηση του χρήστη βάσει του email
    // επιλογή user_id, usename, password, role_id
    $stmt = $conn->prepare("SELECT user_id, username, password, role_id FROM users WHERE email = ?");

    // σύνδεση παραμέτρου email για προστασία από sql injection
    $stmt->bind_param("s", $email);

    // εκτέλεση ερωτήματος
    $stmt->execute();

    // λήψη αποτελεσμάτων από τη βάση
    $result = $stmt->get_result();

    // έλεγχος αν βρέθηκε ένας χρήστης με αυτό το email
    if ($result->num_rows === 1) {

        // μετατροπή των δεδομένων του χρήστη σε συσχετιστικό πίνακα
        $user = $result->fetch_assoc();

        // έλεγχος εγκυρότητας κωδικού
        if (password_verify($password, $user['password'])) {

            // αποθήκευση στοιχείων του χρήστη στο session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];

            // ανακατεύθυνση του χρήστη στο dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Λάθος κωδικός πρόσβασης!";
        }
        } else {
            $message = "Δεν βρέθηκε χρήστης με αυτό το email.";
        }

        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <title>Σύνδεση</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <h1>Πύλη Πανεπιστημίου</h1>
            <nav>
                <a href="index.php" class="btn">Αρχική</a>
                <a href="register.php" class="btn">Εγγραφή</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Σύνδεση Χρήστη</h2>

            <p style="color: #d9534f; text-align: center; font-weight: bold;"><?php echo $message; ?></p>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <input type="submit" value="Σύνδεση" class="btn" style="width: 100%; border:none; background-color: #333; cursor:pointer;">
            </form>
        </div>
    </div>
</body>
</html>
