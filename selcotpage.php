<?php
require_once("functions.php");
require_once("screen-detector.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Vos Résultats de compétitions en temps réel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
    // Enregistrer la largeur dze l'écran dans un cookie
    document.cookie = "screen_width=" + window.innerWidth;
    
    // Fonction pour mettre à jour la bannière lors du redimensionnement
    function updateBanner() {
        document.cookie = "screen_width=" + window.innerWidth;
        location.reload();
    }
    
    // Écouter l'événement de redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        // Utiliser un délai pour éviter un trop grand nombre d'appels
        clearTimeout(window.resizeTimeout);
        window.resizeTimeout = setTimeout(updateBanner, 500);
    });
    </script>
</head>
<body class="selection-page">
    <div class="selection-header">
        <div class="header-content">
            <img src="logo/logo.svg" alt="Logo" class="header-logo">
            <h1 class="page-title">Sélection de la compétition</h1>
        </div>
    </div>

    <main class="competition-grid">
        <?php
        require_once("selcot.php");
        $competitions = scancot();
        
        foreach ($competitions as $comp) {
            $date = new DateTime($comp['date']);
            echo '<div class="competition-card" onclick="window.location=\'index.php?file=' . urlencode($comp['file']) . '&item=lst&tabStart=256&tabEnd=16&zoom=0.8\'">';
            echo '<h3>' . htmlspecialchars($comp['name']) . '</h3>';
            echo '<div class="competition-info"><i class="far fa-calendar-alt"></i>' . $date->format('d/m/Y') . '</div>';
            echo '<div class="competition-info"><i class="fas fa-map-marker-alt"></i>' . htmlspecialchars($comp['lieu']) . '</div>';
            echo '</div>';
        }
        ?>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> Système de gestion des compétitions d'escrime BellePoule officiellement approuvé par la FFE, licence GNU v3 </p>
    </footer>
</body>
</html>
