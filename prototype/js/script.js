document.addEventListener('DOMContentLoaded', function() {

    // ορισμός συντεταγμένων
    var campusLat = 40.6291108;
    var campusLng = 22.9501173;

    // αρχικοποίηση χάρτη
    var map = L.map('map').setView([campusLat, campusLng], 17);

    // φόρτωση υποβάθρου, χρησιμοποιείται το openstreetmap για να εμφανιστεί η εικόνα του χάρτη
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap' // αναφορά πνευματικών δικαιωμάτων
    }).addTo(map);

    // προσθήκη δείκτη, πινέζα στις συντεταγμένες του πανεπιστημίου
    var marker = L.marker([campusLat, campusLng]).addTo(map);

    // αναδυόμενο παράθυρο, ανοίγει αυτόματα κατά την φόρτωση
    marker.bindPopup("<b>Πανεπιστήμιο Θεσσαλονίκης</b><br>Καλώς ήρθατε στο Campus μας.").openPopup();
});