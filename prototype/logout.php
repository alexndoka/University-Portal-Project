<?php
session_start();

// θέτουμε το $_SESSION ως κενό πίνακα, διαγράφουμε όλα τα δεδομένα
$_SESSION = array();

// καταστροφή του session στον server
session_destroy();

// ανακατεύθυνση πίσω στη σελίδα της σύνδεσης
header("Location: login.php");

// τερματισμός εκτέλεσης του script
exit;
?>
