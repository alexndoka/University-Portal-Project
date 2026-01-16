<?php
// έναρξη session στην αρχή του αρχείου
// ελέγχουμε αν ο χρήστης είναι συνδεδεμένος και ανακτούμε τα στοιχεία του
session_start(); ?> <!DOCTYPE html>
<html lang="el"> <head>
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Πανεπιστήμιο - Αρχική</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>

<header>
    <div class="container header-content">
        <div class="logo">
            <h1>Πύλη Πανεπιστημίου</h1>
        </div>

        <nav>
            <?php
            // έλεγχος έαν υπάρχει συνδεδεμένος χρήστης
            if (isset($_SESSION['user_id'])): ?>
                <span style="color: white; margin-right: 15px;">
                    Καλώς ήρθατε, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    (
                    <?php
                    // δυναμική εμφάνιση ρόλου ανάλογα με το role_id στη βάση δεδομένων
                    echo ($_SESSION['role_id'] == 1) ? 'Φοιτητής' : 'Καθηγητής'; ?>)
                </span>
            <a href="logout.php" class="btn" style="border-color: #ff4d4d; color: #ff4d4d;">Αποσύνδεση</a>
        <?php else: ?>
            <a href="login.php" class="btn">Σύνδεση</a>
            <a href="register.php" class="btn">Εγγραφή</a>
        <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">

    <section class="campus-info">
        <div class="text-content">
            <h2>Καλώς ήρθατε στο Campus μας</h2>
            <p>Καλώς ήρθατε στο πανεπιστήμιο. Οι εγκαταστάσεις μας προσφέρουν σύγχρονες αίθουσες διδασκαλίας και χώρους μελέτης.</p>
            <br>

            <h3>Οι Εγκαταστάσεις μας</h3>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>Σύγχρονη Βιβλιοθήκη:</strong> Ανοιχτή 24/7 για μελέτη.</li>
                <li><strong>Κέντρο Τεχνολογίας:</strong> Εργαστήρια με τον πιο πρόσφατο εξοπλισμό.</li>
                <li><strong>Αθλητικό Κέντρο:</strong> Γυμναστήριο και γήπεδα για όλους τους φοιτητές.</li>
                <li><strong>Φοιτητική Λέσχη:</strong> Χώρος εστίασης και χαλάρωσης.</li>
            </ul>
            <br>

            <p>Στόχος μας είναι να παρέχουμε ένα περιβάλλον που εμπνέει την ακαδημαϊκή αριστεία και την προσωπική ανάπτυξη.</p>
        </div>

        <div class="image-content">
            <img src="pictures/campus.jpg" alt="Άποψη του Campus">
        </div>
    </section>

    <section class="map-section">
        <h2>Η Τοποθεσία μας</h2>
        <p>Επισκεφθείτε μας στις κεντρικές εγκαταστάσεις.</p>
        <div id="map"></div>
    </section>

</main>

<footer>
    <p>&copy; 2025 Πανεπιστήμιο Θεσσαλονίκης</p>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/script.js"></script>
</body>
</html>