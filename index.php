<?php

require_once( "tools.php" );
require_once( "my_phase_pointage.php" );
require_once( "my6.php" ); // my3
require_once( "functions.php" );
require_once( "pays.php" );
require_once( "selcot.php");
require_once( "screen-detector.php");


if (isset($_GET['file']))
{
    $filename = $_GET['file']; 
    
    if (!check_filename ($filename))
    {
        echo "Invalid file name [$filename]";
//        header("Location: selcotpage.php");
        die();
    }
}
else
{
//  Missing file name. Redirecting to cotocot selection; 
    header("Location: selcotpage.php");
    die();
}

$fileqry="?file=" . urlencode($filename);
$fileqry = preg_replace('/^http:\/\//', '//', $fileqry);

$xml = new DOMDocument( "1.0", "utf-8" );
$xml->load( $filename);
      
$head_title = 'Cotcot';
$good_zoom  = 1;
$item   = isset($_GET['item']) ? $_GET['item'] : 'lst';
$tour   = isset($_GET['tour']) ? $_GET['tour'] : 1;
$ncol   = isset($_GET['ncol']) ? intval($_GET['ncol']) : 1;
$detail = isset($_GET['pack']) ? 0 : 1;
$fold   = isset($_GET['fold']) ? intval($_GET['fold']) : 0;
$abc    = isset($_GET['ABC']) ? intval($_GET['ABC']) : 0;
$scroll = isset($_GET['scroll']) ? $_GET['scroll'] : 0;
$class = '';
switch( $item )
{
    case 'lst' : $head_title = 'Liste de Présence';
        $good_zoom = ($ncol==1) ? 2.5 : 0.75;
    break;

    case 'poudet':
    case 'pou': $head_title = 'Poules';
        $good_zoom = 0.5;
    break;

    case 'clapou': $head_title = 'Classement Poules';
        $good_zoom = ($ncol==1) ? 2 : 1;
    break;

    case 'clatab': $head_title = 'Classement Tableau';
        $good_zoom = ($ncol==1) ? 2 : 1;
    break;

    case 'tab': $head_title = 'Tableau';
        $good_zoom = 0.5;
    break;

    case 'flag':
        $class = 'flag_page';
        break;
    
    case 'menu':
        $head_title = getTitre($xml);
    break;

    case 'clasgen': 
        $head_title = 'Classement Général';
        $good_zoom = ($ncol==1) ? 2 : 1;
    break;
}   

function dump_UI($tabl) {
    $tabStart = isset($_GET['tabStart']) ? intval($_GET['tabStart']) : 256;
    $tabEnd = isset($_GET['tabEnd']) ? intval($_GET['tabEnd']) : 2;
    $scale = isset($_GET['zoom']) ? floatval($_GET['zoom']) : 1;
    $item   = isset($_GET['item']) ? $_GET['item'] : 'menu';

    echo "<div id='autohide' class='autohide'>";
    
    if(!IE())
    {
        echo "<div class='slider-zoom' id='slider-zoom'></div><br>";
        echo "<br>";
    }
    else
    {
        echo "<h1>Because of its outdated design, Internet Explorer cannot fully render this site.<br>";
        echo "Try to visit us again with a more recent browser.</h1><br>";
    }
    echo "<button class='buttonicon' onClick='scro(0)'>&check;</button>";
    echo "<button class='buttonicon' onClick='scro(1)'>&udarr;</button>";
    echo "<button class='buttonicon' onClick='scro(2)'>&olarr;</button>";

    $T = '"lst"'; $S = ($item=='lst') ? '_dis' : '';
    echo "<button class='buttonicon$S'  onClick='item($T)'> <img class='buttoniconi$S' src='liste.svg'/> </button>\n";
    $T = '"pou"'; $S = ($item=='pou') ? '_dis' : '';
    echo "<button class='buttonicon$S'  onClick='item($T)'> <img class='buttoniconi$S' src='pou.svg'/>   </button>\n";
    $T = '"poudet"'; $S = ($item=='poudet') ? '_dis' : '';
    echo "<button class='buttonicon$S'  onClick='item($T)'> <img class='buttoniconi$S' src='poudet.svg'/>   </button>\n";
    $T = '"clapou"'; $S = ($item=='clapou') ? '_dis' : '';
    echo "<button class='buttonicon$S'  onClick='item($T)'> <img class='buttoniconi$S' src='clapou.svg'/></button>\n";

    $T = '"tab"'; $S = ($item=='tab') ? '_dis' : '';
    echo "<button class='buttonicon$S'  onClick='item($T)'> <img class='buttoniconi$S' src='tab1.svg'/>  </button>\n";

$T = '"clatab"'; $S = ($item=='clatab') ? '_dis' : '';
    echo "<button class='buttonicon$S'  onClick='item($T)'> <img class='buttoniconi$S' src='clatab.svg'/></button>\n";

        if ($tabl)
    {
        echo "<div class='slider-tableau' id='slider-tableau'></div>";  
    }

    echo "</div>";
}

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
    <meta charset="UTF-8">
    <!--<meta http-equiv="refresh" content="57"> <!-- Rafraîchit la page toutes les 30 secondes -->
        <title> <?php echo $head_title ?> </title>
        <!--        <meta http-equiv="refresh" content="5" >-->
        <style>
         :root{
	     --rescale : <?php
			 $scale = isset($_GET['zoom']) ? floatval($_GET['zoom']) : $good_zoom;
			 printf("%5.3f",$scale);
			 ?> ;
	 }
         .spu23hp
	 {
             height:3vh;
	     font-size:3vh;
             font-weight:bolder;
             color:red;
	     max-width:85vw;
	     max-height:50px;
	     margin-left:2vw;
	     margin-right:2vw;
	     margin-top:2vh;
	     margin-bottom:2vh;
	 }
         .u23hp
	 {
	     height:8vh;
	     max-width:85vw;
	     max-height:50px;
	     margin-left:2vw;
	     margin-right:2vw;
	     margin-top:2vh;
	     margin-bottom:2vh;
	 }
	 .button_div
	 {
	     margin-left:2vw;
	     margin-right:2vw;
	     margin-top:2vh;
	     margin-bottom:2vh;
	     text-decoration: none;
	     background-color: #EEEBEE;
	     color: #333333;
	     padding:0.5vh;
	 }
	 .button
	 {
	     margin:auto auto;
	     font: bold 18px Arial;
	     text-decoration: none;
	 }
         .refresh-button {
             position: fixed;
             bottom: 20px;
             left: 50%;
             transform: translateX(-50%);
             background-color: #ff0000;
             color: white;
             border: none;
             border-radius: 50%;
             width: 60px;
             height: 60px;
             cursor: pointer;
             box-shadow: 0 2px 5px rgba(0,0,0,0.2);
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 24px;
             z-index: 1000;
         }
         
         .refresh-button i {
             transition: transform 0.3s ease;
         }
         
         .refresh-button:hover i {
             transform: rotate(180deg);
         }
         
         @keyframes spinning {
             from { transform: rotate(0deg); }
             to { transform: rotate(360deg); }
         }
         
         .spinning {
             animation: spinning 1s linear infinite;
         }
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        if (!IE())
        {
           echo '<link rel="stylesheet"  type="text/css" title="Design de base" href="css/const.css" />';
           echo '<link rel="stylesheet" type="text/css" title="Design de base" href="css/flag_icons.css" />';
           outputScreenDetectionScript(); // Ajoute le script de détection d'écran
        }
        else        
        {
            echo '<link rel="stylesheet"  type="text/css" title="Design de base" href="css/ie.css" />';
            echo '<link rel="stylesheet" type="text/css" title="Design de base" href="css/flag_icons_ie.css" />';
        }
        ?>
        <link href="css/nouislider.css" rel="stylesheet">
    </head>
    <!-- 
         <meta http-equiv="refresh" content="15" />
    -->
    <body class="<?php echo getScreenClasses(); ?>">
    
<?php 
// Ajouter la barre d'outils sur toutes les pages sauf menu
if ($item != 'menu') { ?>
    <div class="toolbar">
        <a href="selcotpage.php" class="toolbar-button home">
            <i class="fas fa-home"></i> 
            Accueil
        </a>
        <a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php<?php echo $fileqry; ?>&item=lst&tabStart=512&tabEnd=16&zoom=0.8&scroll=<?php echo $scroll; ?>" 
           class="toolbar-button <?php echo ($item=='lst') ? 'active' : ''; ?>">
            <i class="fas fa-list"></i>
            Liste
        </a>
        <a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php<?php echo $fileqry; ?>&item=poudet&tabStart=512&tabEnd=16&zoom=0.6&scroll=<?php echo $scroll; ?>" 
           class="toolbar-button <?php echo ($item=='poudet') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            Poules
        </a>
        <a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php<?php echo $fileqry; ?>&item=clapou&tabStart=512&tabEnd=16&zoom=0.8&scroll=<?php echo $scroll; ?>" 
           class="toolbar-button <?php echo ($item=='clapou') ? 'active' : ''; ?>">
            <i class="fas fa-sort-numeric-down"></i>
            Classement Poules
        </a>
        <a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php<?php echo $fileqry; ?>&item=tab&tabStart=512&tabEnd=2&zoom=0.5&scroll=<?php echo $scroll; ?>" 
           class="toolbar-button <?php echo ($item=='tab') ? 'active' : ''; ?>">
            <i class="fas fa-sitemap"></i>
            Tableau
        </a>
        <a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php<?php echo $fileqry; ?>&item=clatab&tabStart=512&tabEnd=2&zoom=0.8&scroll=<?php echo $scroll; ?>" 
           class="toolbar-button <?php echo ($item=='clatab') ? 'active' : ''; ?>">
            <i class="fas fa-trophy"></i>
            Résultats Tableaux Provisoires
        </a>
        <a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php<?php echo $fileqry; ?>&item=clasgen&tabStart=512&tabEnd=2&zoom=0.8&scroll=<?php echo $scroll; ?>" 
           class="toolbar-button <?php echo ($item=='clasgen') ? 'active' : ''; ?>">
            <i class="fas fa-medal"></i>
            Classement Général
        </a>
    </div>
    <!-- Après la barre d'outils mais avant le contenu principal -->
    <?php 
        $competitionInfo = getCompetitionInfo($xml);
    ?>
    <div class="competition-info">
        <div class="competition-info-content">
            <?php if ($item == 'lst') { ?>
                <div class="info-item">
                    <i class="far fa-bell"></i>
                    Appel: <?php echo $competitionInfo['appel']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-ban"></i>
                    Scratch: <?php echo $competitionInfo['scratch']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-play"></i>
                    Début: <?php echo $competitionInfo['debut']; ?>
                </div>
                <div class="info-item competition-title">
                    <i class="fas fa-trophy"></i>
                    <?php echo $competitionInfo['titre']; ?>
                </div>
            <?php } else { ?>
                <div class="info-item">
                    <i class="far fa-calendar"></i>
                    <?php echo $competitionInfo['date']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-trophy"></i>
                    <?php echo $competitionInfo['titre']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo $competitionInfo['organisateur']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-crosshairs"></i>
                    <?php echo $competitionInfo['arme']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-venus-mars"></i>
                    <?php echo $competitionInfo['sexe']; ?>
                </div>
                <div class="info-item">
                    <i class="fas fa-users"></i>
                    <?php echo $competitionInfo['categorie']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<div class="content-wrapper">
    <?php if ($item == 'menu') {
        // Menu principal
        ?>
        <div class="spu23hp"><?php echo $head_title; ?></div>
        <body>
                <div  class="spu23hp"><?php echo "$head_title </div>
	<a href='index.php$fileqry&item=lst&tabStart=512&tabEnd=16&zoom=0.8&scroll=$scroll'>
	    <div class='button_div'>
		<img src='ban_listel.svg' class='u23hp'>
	    </div>
	</a>
	<a href='index.php$fileqry&item=poudet&tabStart=512&tabEnd=16&zoom=0.6&scroll=$scroll'>
	    <div class='button_div'>
		<img src='ban_pou.svg' class='u23hp'>
	    </div>
	</a>
	<a href='index.php$fileqry&item=clapou&tabStart=512&tabEnd=16&zoom=0.8&scroll=$scroll'>
	    <div class='button_div'>
		<img src='ban_clapou.svg' class='u23hp'>
	    </div>
	</a>
	<a href='index.php$fileqry&item=tab&tabStart=512&tabEnd=2&zoom=0.5&scroll=$scroll'>
	    <div class='button_div'>
		<img src='ban_tab.svg' class='u23hp'>
	    </div>
	</a>
	<a href='index.php$fileqry&item=clatab&tabStart=512&tabEnd=2&zoom=0.8&scroll=$scroll'>
	    <div class='button_div'>
		<img src='ban_clatab.svg' class='u23hp'>
	    </div>
	</a>";
?>
	    <div class="button_div">
		<img src="pointe.svg" class="u23hp">
	    </div>

    </body>
  </html>

<?php     
exit();
    } else {
        // Autres pages
        switch($item) {
            case 'lst' :
                dump_UI(0);
                $etape = 0;
                echo afficheClassementPoules($xml, $ncol, 1, $etape, 'LISTE DE PRÉSENCE');
                break;

            case 'pou':
                dump_UI(0);
                echo affichePoules($xml, $tour, 0);
                break;
            
            case 'poudet':
                dump_UI(0);
                echo affichePoules($xml, $tour, 1);
                break;

            case 'clapou':
                dump_UI(0);

                $etape = 1;
    //            $burst_speed = 4;
                echo afficheClassementPoules($xml, $ncol, $abc, $etape, 'CLASSEMENT POULES');
                break;

            case 'clatab':
                dump_UI(0);
                $etape = -1;
                repairTableau($xml);
                echo afficheClassementPoules($xml, $ncol, $abc, $etape, 'Résultats Tableaux');
                break;

            case 'flag':
                repairTableau($xml);
                echo drapeauxPodium($xml);
                break;

            case 'tab':
                dump_UI(1);
                $burst_end_delay = 10;
                repairTableau($xml);
                
                // Récupérer l'ID du tableau sélectionné
                $currentTableau = isset($_GET['tableau']) ? $_GET['tableau'] : '0';
                
                // Utiliser la nouvelle fonction qui gère les onglets
                echo renderMyTableauWithTabs($xml, $detail, $fold, 'TABLEAU', $currentTableau);
                break;

            case 'clafin':
                echo renderClassement($xml);
                break;

            case 'clasgen':
                dump_UI(0);
                echo afficheClassementGeneral($xml, $ncol, $abc, 'CLASSEMENT GÉNÉRAL'); 
                break;

            case 'menu':
            default:
                echo "Fichier en cours $filename <br>";
                
    $te = IE() ? 2 : 16;

    $mixte= mixteMaleFemale ($xml);
    switch ($mixte)
    {
        case 'F':
        echo "<a class='home' href='index.php$fileqry&item=lst&tabStart=512&tabEnd=$te'>Tireuses</a><br>";
        break;  
        case 'FM':
        echo "<a class='home' href='index.php$fileqry&item=lst&tabStart=512&tabEnd=$te'>Tireuses et tireurs</a><br>";
        break;  
        case 'M':
        echo "<a class='home' href='index.php$fileqry&item=lst&tabStart=512&tabEnd=$te'>Tireurs</a><br>";
        break;  
    }

    if ($mixte != 'E')
    {
    echo "<a class='home' href='index.php$fileqry&item=pou&tabStart=512&tabEnd=$te'>Poules</a><br>";
    echo "<a class='home' href='index.php$fileqry&item=clapou&tabStart=512&tabEnd=$te'>Classement poules</a><br>";
    echo "<a class='home' href='index.php$fileqry&item=tab&tabStart=512&tabEnd=$te'>Tableau</a><br>";
    echo "<a class='home' href='index.php$fileqry&item=clatab&tabStart=512&tabEnd=$te'>Classement tableau</a><br>";
    }
    ?>
            <?php
            break;
        }
    }
    //	    echo '</div>';
    
    
    echo '</div>';

    ?>
    
    <?php if ($filename !== 'selcot.php' && $item != 'menu') { ?>
    <!-- Barre de recherche des compétiteurs -->
    <div class="search-bar-container">
        <div class="search-controls">
            <!-- Bouton de rafraîchissement intégré à la barre de recherche -->
            <button onclick="refreshPage()" class="refresh-button">
                <i class="fas fa-sync-alt"></i>
            </button>
            <div class="search-bar">
                <input type="text" id="competitor-search" placeholder="Rechercher un compétiteur..." 
                    value="<?php echo isset($_COOKIE['competitor_search']) ? htmlspecialchars($_COOKIE['competitor_search']) : ''; ?>">
                <button id="search-button">
                    <i class="fas fa-search"></i>
                </button>
                <div class="navigation-buttons">
                    <button id="prev-result" class="nav-button" disabled>
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button id="next-result" class="nav-button" disabled>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="search-results"></div>
    </div>
    <?php } ?>
    
    </body>
    <script src="nouislider.js"></script>
    <script language="javascript" src="functions.js" type="text/javascript"></script>

    <?php
    
    echo "<script>\n";
    echo "var intra_burst_delay=3;\n";
    echo "var speed=$burst_speed;\n";
    echo "var burst_timer=$burst_timer;\n";
    echo "var glob_burst_length=$burst_length;\n";
    echo "var intra_burst_delay=3;\n";
    echo "var extra_burst_delay=$burst_extra_delay;\n";
    echo "var end_delay=$burst_end_delay;\n";
    echo "</script>\n";
 
    function IE()
    {
        $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0; rv:11.0') !== false)) {
            // do stuff for IE
            return 1;
        }
        return 0;
    }
    
    
    function Tsvg($l1,$l2,$alpha)
    {
       return "<svg
   viewBox='-6 -6 134 134'
   height='100%'
   width='100%'
   id='tab1'
   version='1.1'>
  <path
     visibility='hidden'
     d='m 0,0 0,128 128,0 0,-128 z'
     id='path7'
     style='visibility:hidden;' />
  <text
     id='text3366'
     y='45.669491'
     x='62.703388'
     style='font-style:normal;font-variant:normal;font-weight:900;font-stretch:normal;font-size:40px;line-height:125%;font-family:\"Arial Black\";text-align:center;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:$alpha;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1'
     xml:space='preserve'><tspan
       y='45.669491'
       x='62.703388'
       id='tspan3368'>$l1</tspan></text>
  <text
     id='text3370'
     y='105.85595'
     x='62.614101'
     style='font-style:normal;font-variant:normal;font-weight:900;font-stretch:normal;font-size:67.37400055px;line-height:125%;font-family:\"Arial Black\";text-align:center;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#000000;fill-opacity:$alpha;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1'
     xml:space='preserve'><tspan
       y='105.85595'
       x='62.614101'
       id='tspan3372'>$l2</tspan></text>
</svg>";


    }
    ?>
    <script>
    function refreshPage() {
        const icon = document.querySelector('.refresh-button i');
        icon.classList.add('spinning');
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }

    // Script pour la recherche des compétiteurs
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('competitor-search');
        const searchButton = document.getElementById('search-button');
        const searchResults = document.getElementById('search-results');
        const prevButton = document.getElementById('prev-result');
        const nextButton = document.getElementById('next-result');
        
        // Variables pour suivre les résultats de recherche
        let currentResults = [];
        let currentResultIndex = -1;

        // Fonction pour enregistrer la recherche dans un cookie
        function saveSearchToCookie(searchText) {
            document.cookie = "competitor_search=" + encodeURIComponent(searchText) + "; path=/; max-age=86400"; // 24 heures
        }

        // Fonction pour mettre en surbrillance les résultats de recherche
        function highlightSearchResults(searchText) {
            if (!searchText) {
                resetSearchResults();
                return;
            }
            
            // Réinitialiser les surlignages précédents et les compteurs
            resetSearchResults();
            
            const tableRows = document.querySelectorAll('tr, .poule_nom, .Tableau_wto_lef, .Tableau_wbo_lef');
            
            // Rechercher le texte dans les éléments td, .poule_nom, et dans les tableaux
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchText.toLowerCase())) {
                    // Mettre en évidence le texte dans les cellules
                    const walkNode = document.createTreeWalker(row, NodeFilter.SHOW_TEXT, null, false);
                    let node;
                    let found = false;
                    
                    while (node = walkNode.nextNode()) {
                        const text = node.nodeValue.toLowerCase();
                        if (text.includes(searchText.toLowerCase())) {
                            const start = text.indexOf(searchText.toLowerCase());
                            const end = start + searchText.length;
                            
                            const wrapper = document.createElement('span');
                            wrapper.className = 'competitor-highlight';
                            wrapper.innerHTML = node.nodeValue.substring(0, start) +
                                '<span class="highlight-text">' + node.nodeValue.substring(start, end) + '</span>' +
                                node.nodeValue.substring(end);
                            
                            node.parentNode.replaceChild(wrapper, node);
                            found = true;
                        }
                    }
                    
                    // Si trouvé, ajouter à la liste des résultats
                    if (found) {
                        currentResults.push(row);
                    }
                }
            });
            
            // Mettre à jour les boutons de navigation et l'indicateur de résultats
            updateNavigationButtons();
            
            // Si des résultats ont été trouvés, naviguer vers le premier
            if (currentResults.length > 0) {
                navigateToResult(0);
            } else {
                searchResults.innerHTML = '<div class="search-count">Aucun résultat trouvé</div>';
            }
        }
        
        // Fonction pour réinitialiser les résultats de recherche
        function resetSearchResults() {
            const previousHighlights = document.querySelectorAll('.competitor-highlight');
            previousHighlights.forEach(el => {
                el.outerHTML = el.innerHTML;
            });
            
            currentResults = [];
            currentResultIndex = -1;
            updateNavigationButtons();
        }
        
        // Fonction pour mettre à jour l'état des boutons de navigation
        function updateNavigationButtons() {
            if (currentResults.length <= 1) {
                prevButton.disabled = true;
                nextButton.disabled = true;
            } else {
                prevButton.disabled = currentResultIndex <= 0;
                nextButton.disabled = currentResultIndex >= currentResults.length - 1;
            }
            
            // Mettre à jour le compteur de résultats
            if (currentResults.length > 0) {
                searchResults.innerHTML = `<div class="search-count">${currentResultIndex + 1}/${currentResults.length} résultat(s)</div>`;
            } else {
                searchResults.innerHTML = '';
            }
        }
        
        // Fonction pour naviguer vers un résultat spécifique
        function navigateToResult(index) {
            if (index >= 0 && index < currentResults.length) {
                currentResultIndex = index;
                const element = currentResults[index];
                
                // Faire défiler vers l'élément
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                
                // Mettre à jour les boutons et le compteur
                updateNavigationButtons();
            }
        }
        
        // Naviguer au résultat suivant
        nextButton.addEventListener('click', function() {
            if (currentResultIndex < currentResults.length - 1) {
                navigateToResult(currentResultIndex + 1);
            }
        });
        
        // Naviguer au résultat précédent
        prevButton.addEventListener('click', function() {
            if (currentResultIndex > 0) {
                navigateToResult(currentResultIndex - 1);
            }
        });

        // Effectuer la recherche lorsqu'on clique sur le bouton
        searchButton.addEventListener('click', function() {
            const searchText = searchInput.value.trim();
            saveSearchToCookie(searchText);
            highlightSearchResults(searchText);
        });

        // Effectuer également la recherche lorsqu'on appuie sur la touche "Entrée"
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const searchText = searchInput.value.trim();
                saveSearchToCookie(searchText);
                highlightSearchResults(searchText);
            }
        });

        // Exécuter la recherche au chargement de la page si un terme est stocké
        const storedSearch = searchInput.value.trim();
        if (storedSearch) {
            highlightSearchResults(storedSearch);
        }
    });
    </script>
</html>
