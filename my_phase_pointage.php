<?php

if (!function_exists('renderMyTableau')) {
    require_once('my6.php');
}

function statut_present_absent ($statut)
{
        $r = 'Erreur';
        switch($statut)
        {
            case 'F':
                $r = 'absent';
                break;
            case 'Q':
            case 'N':
            case 'A':
                $r = 'présent';
                break;
            case 'E':
                $r = 'exclu';
                break;
        }
    return $r;
}

function ordonneListeParticipants ( $tireurs)
{
    $order = array();
    foreach ($tireurs as $ref=>$val)
    {
	$order[$ref] = $val['ACCU']['Nom'] . ' ' . $val['ACCU']['Prenom'] . ' ' . $val['ACCU']['Club'] ; 
    }
    asort($order);     
    return array_keys($order);
}

function afficheListeParticipants( $xml )
{
    $tireurs = suitTireurs ( $xml );
    $ordre   = ordonneListeParticipants ( $tireurs);
    
    $head = "";
    
    // Récupérer la taille actuelle du tableau
    $currentTabStart = isset($_GET['tabStart']) ? $_GET['tabStart'] : 256;
    
    // Créer un menu déroulant pour la sélection de la taille du tableau si nous sommes sur la page tab
    if (isset($_GET['item']) && $_GET['item'] == 'tab') {
        $head .= "<div class='tblhd_top' onclick='mickey()'>";
        $head .= "<div class='tableau-size-selector'>";
        $head .= "<select id='tableauSizeSelector' onchange='changeTableauSize(this.value)'>";
        $tableauSizes = [512, 256, 128, 64, 32, 16, 8, 4];
        foreach ($tableauSizes as $size) {
            $selected = ($size == $currentTabStart) ? 'selected' : '';
            $head .= "<option value='$size' $selected>Tableau de $size</option>";
        }
        $head .= "</select>";
        $head .= "</div><br>";
    } else {
        $head .= "<div class='tblhd_top' onclick='mickey()'><span class='tbl_banner'>&#9776; $titre</span><br>";
    }
    
    $head .= "<table id='head' class='listeTireur'>\n";
    
    $r = "<table class='lst_nom'>";
    
    foreach ($ordre as $ref)
    {
	$r .= "<tr><td class='inscription_nom'>";

	$nom = $tireurs[$ref]['ACCU']['Nom'] . ' ' . $tireurs[$ref]['ACCU']['Prenom'];
	$nat = $tireurs[$ref]['ACCU']['Nation'];
	$ran = $tireurs[$ref]['ACCU']['Ranking'];
	$clu = $tireurs[$ref]['ACCU']['Club'];
        $pre = statut_present_absent ($tireurs[$ref]['ACCU']['St']);
	$r .= ' '.flag_icon($nat,'small').' ';
	$r .= $ran . ' ';
	$r .= (strlen($nom)>30)?fractureNom($nom):$nom;
	$r .= "</td>";
	$r .=  "<td class='inscription_status'> $pre </td>";
	$r .=  "<td class='inscription_rank'>   $ran </td>";
	$r .=  "<td class='inscription_club'>   $clu </td>";
	$r .= "</tr>\n";
    }
    $r .= "</table>"; // Modifier cette fonction pour créer un titre avec le menu déroulant pour les tableaux
    
    return $r;
}

function createTableauHeader($titre, $currentTabStart = 256)
{
    global $fileqry;
    $head = "";
    
    // Créer un menu déroulant pour la sélection de la taille du tableau
    $head .= "<div class='tblhd_top' onclick='event.stopPropagation();'>"; // Empêcher la propagation du clic
    
    // Pour la page tab, utiliser le sélecteur déroulant
    if (isset($_GET['item']) && $_GET['item'] == 'tab') {
        // Récupérer l'URL exacte du fichier courant
        $currentFile = isset($_GET['file']) ? $_GET['file'] : '';
        
        $head .= "<div class='tableau-size-selector'>";
        $head .= "<form method='GET' action='index.php' id='tableau-size-form'>";
        
        // Ajouter le champ caché pour le fichier avec son encodage exact original
        $head .= "<input type='hidden' name='file' value='" . htmlspecialchars($currentFile, ENT_QUOTES) . "'>";
        $head .= "<input type='hidden' name='item' value='tab'>";
        
        // Préserver les autres paramètres d'URL
        $head .= "<input type='hidden' name='tableau' value='" . (isset($_GET['tableau']) ? htmlspecialchars($_GET['tableau']) : '0') . "'>";
        $head .= "<input type='hidden' name='tabEnd' value='" . (isset($_GET['tabEnd']) ? htmlspecialchars($_GET['tabEnd']) : '2') . "'>";
        $head .= "<input type='hidden' name='zoom' value='" . (isset($_GET['zoom']) ? htmlspecialchars($_GET['zoom']) : '0.5') . "'>";
        $head .= "<input type='hidden' name='scroll' value='" . (isset($_GET['scroll']) ? htmlspecialchars($_GET['scroll']) : '0') . "'>";
        
        $head .= "<select id='tableauSizeSelector' name='tabStart' onchange='document.getElementById(\"tableau-size-form\").submit();'>";
        $tableauSizes = [512, 256, 128, 64, 32, 16, 8, 4];
        foreach ($tableauSizes as $size) {
            $selected = ($size == $currentTabStart) ? 'selected' : '';
            $head .= "<option value='$size' $selected>Affichage Tableau de $size</option>";
        }
        $head .= "</select>";
        $head .= "</form>";
        $head .= "</div>";
        
        // Afficher le titre adaptatif avec la classe tbl_banner
        $head .= "<span class='tbl_banner'>$titre</span>";
    } else {
        $head .= "<span class='tbl_banner'>&#9776; $titre</span>";
    }
    
    $head .= "<br></div>";
    
    return $head;
}
?>
