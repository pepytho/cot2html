<?php

require_once("tools.php");

function check_filename($fn) {
    $files = explorer('cotcot', '.cotcot'); // Ajout de l'extension
    return in_array($fn, $files);
}

/* Select a COTCOT from the list */
function scancot() {
    // Vérifier si le dossier existe
    $cotcot_dir = 'cotcot';
    if (!is_dir($cotcot_dir)) {
        error_log("Le dossier 'cotcot' n'existe pas");
        return array();
    }

    // Explorer le dossier avec l'extension .cotcot
    $files = explorer($cotcot_dir, '.cotcot');
    $liste = array();
    
    foreach ($files as $filename) {
        // Vérifier si le fichier existe et est lisible
        if (!file_exists($filename) || !is_readable($filename)) {
            error_log("Fichier non accessible: $filename");
            continue;
        }

        try {
            $DOMxml = new DOMDocument('1.0', 'utf-8');
            if ($DOMxml->load($filename)) {
                $competXml = $DOMxml->documentElement;
                
                // Récupérer les attributs avec vérification
                $titreLong = $competXml->hasAttribute('TitreLong') ? $competXml->getAttribute('TitreLong') : '';
                $categorie = $competXml->hasAttribute('Categorie') ? $competXml->getAttribute('Categorie') : '';
                $arme = $competXml->hasAttribute('Arme') ? $competXml->getAttribute('Arme') : '';
                $sexe = $competXml->hasAttribute('Sexe') ? $competXml->getAttribute('Sexe') : '';
                $date = $competXml->hasAttribute('Date') ? $competXml->getAttribute('Date') : date('Y-m-d');
                $lieu = $competXml->hasAttribute('Lieu') ? $competXml->getAttribute('Lieu') : '';

                $liste[$filename] = array(
                    'file' => $filename,
                    'name' => $titreLong,
                    'categorie' => $categorie,
                    'arme' => $arme,
                    'sexe' => $sexe,
                    'date' => $date,
                    'lieu' => $lieu
                );
            } else {
                error_log("Erreur de chargement du fichier XML: $filename");
            }
        } catch (Exception $e) {
            error_log("Erreur lors du traitement du fichier $filename: " . $e->getMessage());
        }
    }

    // Trier les compétitions par date décroissante
    uasort($liste, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });
    
    return $liste;
}

function selcot_table($liste) {
    if (empty($liste)) {
        return '<div class="alert">Aucune compétition trouvée dans le dossier cotcot/</div>';
    }

    $tbl = '<table class="competition-list">';
    $tbl .= '<thead>
                <tr>
                    <th>Date</th>
                    <th>Compétition</th>
                    <th>Lieu</th>
                    <th>Catégorie</th>
                    <th>Arme</th>
                    <th>Sexe</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($liste as $data) {
        $href = "index.php?file=" . urlencode($data['file']);
        $date = new DateTime($data['date']);
        
        $tbl .= "<tr onclick=\"window.location='" . $href . "'\">";
        $tbl .= '<td>' . $date->format('d/m/Y') . '</td>';
        $tbl .= '<td>' . htmlspecialchars($data['name']) . '</td>';
        $tbl .= '<td>' . htmlspecialchars($data['lieu']) . '</td>';
        $tbl .= '<td>' . htmlspecialchars($data['categorie']) . '</td>';
        $tbl .= '<td>' . htmlspecialchars($data['arme']) . '</td>';
        $tbl .= '<td>' . htmlspecialchars($data['sexe']) . '</td>';
        $tbl .= '</tr>';
    }
    
    $tbl .= '</tbody></table>';
    
    return $tbl;
}
?>
