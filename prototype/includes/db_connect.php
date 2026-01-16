<?php
// ρυθμίσεις παραμέτρων για τη σύνδεση στη βάση δεδομένων
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_db";

// δημιουργία νέας σύνδεσης με τη μέθοδο MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// αν υπάρχει σφάλμα κατά τη σύνδεση, τερματίζεται το script και εμφανίζεται μήνυμα λάθους
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ορισμός χαρακτηρών σε utf-8 για να εμφανίζονται τα ελληνικά
$conn->set_charset("utf8");
?>