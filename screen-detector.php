<?php
/**
 * Utilitaire pour détecter la taille de l'écran et adapter l'interface
 */

// Fonction pour déterminer les classes CSS à appliquer en fonction de la taille d'écran
function getScreenClasses() {
    $screenWidth = isset($_COOKIE['screen_width']) ? intval($_COOKIE['screen_width']) : 1920;
    $screenHeight = isset($_COOKIE['screen_height']) ? intval($_COOKIE['screen_height']) : 1080;
    
    $classes = array();
    
    // Détecter la taille d'écran
    if ($screenWidth <= 480) {
        $classes[] = 'xs-screen'; // Très petits écrans
    } else if ($screenWidth <= 767) {
        $classes[] = 'small-screen'; // Petits écrans
    } else if ($screenWidth <= 991) {
        $classes[] = 'medium-screen'; // Écrans moyens
    } else if ($screenWidth <= 1199) {
        $classes[] = 'large-screen'; // Grands écrans
    } else {
        $classes[] = 'xl-screen'; // Très grands écrans
    }
    
    // Détecter l'orientation
    if ($screenWidth > $screenHeight) {
        $classes[] = 'landscape';
        
        // Classification plus fine des modes paysage
        if ($screenHeight <= 450) {
            $classes[] = 'short-landscape'; // Paysage court (téléphones)
        } else if ($screenHeight <= 768) {
            $classes[] = 'medium-landscape'; // Paysage moyen (petites tablettes)
        } else {
            $classes[] = 'tall-landscape'; // Paysage haut (grandes tablettes/ordinateurs)
        }
    } else {
        $classes[] = 'portrait';
        
        // Classification plus fine des modes portrait
        if ($screenWidth <= 360) {
            $classes[] = 'narrow-portrait'; // Portrait étroit
        } else if ($screenWidth <= 768) {
            $classes[] = 'medium-portrait'; // Portrait moyen
        } else {
            $classes[] = 'wide-portrait'; // Portrait large
        }
    }
    
    // Détection de ratio d'aspect
    $ratio = $screenWidth / $screenHeight;
    if ($ratio >= 2) {
        $classes[] = 'ultra-wide'; // Ultra-wide (16:9 ou plus large)
    } else if ($ratio >= 1.7) {
        $classes[] = 'wide'; // Wide (16:9)
    } else if ($ratio >= 1.3) {
        $classes[] = 'standard'; // Standard (4:3)
    } else {
        $classes[] = 'tall'; // Carré ou portrait
    }
    
    return implode(' ', $classes);
}

// Fonction pour ajouter le script de détection dans la partie head d'une page
function outputScreenDetectionScript() {
    echo '<script>
        // Fonction pour détecter la taille de l\'écran et mettre à jour les cookies
        function detectScreenSize() {
            var width = window.innerWidth;
            var height = window.innerHeight;
            document.cookie = "screen_width=" + width + "; path=/";
            document.cookie = "screen_height=" + height + "; path=/";
        }
        
        // Détecter la taille au chargement
        detectScreenSize();
        
        // Détecter la taille lors du redimensionnement et de l\'orientation
        window.addEventListener("resize", detectScreenSize);
        window.addEventListener("orientationchange", function() {
            // Attendre que le changement d\'orientation soit terminé
            setTimeout(detectScreenSize, 100);
        });
    </script>';
}
?>
