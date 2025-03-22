<?php

/********************************************************/
/*                         POULES                       */
/********************************************************/
/*
   define( 'RANK_INIT',     0 );
   define( 'NO_IN_POULE',   1 );
   define( 'NB_VICT',       2 );
   define( 'NB_MATCH',      3 );
   define( 'TD',            4 );
   define( 'TR',            5 );
   define( 'RANK_IN_POULE', 6 );
   define( 'RANK_FIN',      7 );
   define( 'STATUT',        8 );
   define( 'FIRST_RES',     9 );
 */

// Be kind to var_dump!
define( 'RANK_INIT',    'RANK_INIT');
define( 'NO_IN_POULE',  'NO_IN_POULE');
define( 'NB_VICT',      'NB_VICT');
define( 'NB_MATCH',     'NB_MATCH');
define( 'TD',           'TD');
define( 'TR',           'TR');
define( 'RANK_IN_POULE','RANK_IN_POULE');
define( 'RANK_FIN',     'RANK_FIN');
define( 'STATUT',       'STATUT');
define( 'FIRST_RES',    9);



function getTireurList( $domXml )
{
    $tireurList = array();
    
    $equipesListXml = $domXml->getElementsByTagName( 'Equipes' );
    foreach( $equipesListXml as $equipesXml ) 
    {
	foreach( $equipesXml->childNodes as $equipeXml ) 
	{
	    if( get_class( $equipeXml ) == 'DOMElement' )
		$tireurList[ getAttribut( $equipeXml, 'ID' ) ] = $equipeXml;
	}
    }
    
    if( count( $tireurList ) == 0 )
    {
	$tireursXml	= $domXml->getElementsByTagName( 'Tireurs' );
	foreach ($tireursXml as $tireurs) 
	{
	    $tireurXml = $tireurs->getElementsByTagName( 'Tireur' );
	    foreach ($tireurXml as $tireur) 
	    {
		$tireurList[ getAttribut( $tireur, 'ID' ) ] = $tireur;
	    }
	}
    }
    return $tireurList;
}

function getTireurRankingList( $phaseXml )
{
    $tireurList = array();
    
    foreach( $phaseXml->childNodes as $phaseChild )
    {
	if( isset( $phaseChild->localName ) )
	{
	    if( $phaseChild->localName == 'Tireur' || $phaseChild->localName == 'Equipe' )
	    {
		$tireurList[ getAttribut( $phaseChild, 'REF' ) ] = array_fill( 0, 15, "" );
		$tireurList[ getAttribut( $phaseChild, 'REF' ) ][ RANK_INIT ] = getAttribut( $phaseChild, 'RangInitial' );
		$tireurList[ getAttribut( $phaseChild, 'REF' ) ][ RANK_FIN ] = getAttribut( $phaseChild, 'RangFinal' );
		$tireurList[ getAttribut( $phaseChild, 'REF' ) ][ STATUT ] = getAttribut( $phaseChild, 'Statut' );
	    }
	    else if( $phaseChild->localName == 'Poule' )
	    {
		foreach( $phaseChild->childNodes as $pouleChild )
		{

		    if( isset( $pouleChild->localName ) )
		    {

			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ NO_IN_POULE ] = getAttribut( $pouleChild, 'NoDansLaPoule' );
			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ NB_VICT ] = getAttribut( $pouleChild, 'NbVictoires' );

			//			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ NB_MATCH ] = getAttribut( $pouleChild, 'NbMatches' );
			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ NB_MATCH ] = 0; //LPo
			// When BellePoule writes NbMatches, it still counts Exclusion and Abandon in
			// This leads to wrong V/M indices. Instead, we will increment this field for each valid match we see
			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ TD ] = getAttribut( $pouleChild, 'TD' );
			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ TR ] = getAttribut( $pouleChild, 'TR' );
			$tireurList[ getAttribut( $pouleChild, 'REF' ) ][ RANK_IN_POULE ] = getAttribut( $pouleChild, 'RangPoule' );
		    }
		}
	    }
	}
    }
    
    $matchXml = $phaseXml->getElementsByTagName( 'Match' );
    foreach( $matchXml as $match ) 
    {
	//*** 2 tireurs par match
	$tireur1Ref = -1;
	$tireur1Pos = -1;
	$tireur1Mark = -1;
	$tireur2Ref = -1;
	$tireur2Pos = -1;
	$tireur2Mark = -1;
	
	$k = 1;
	foreach( $match->childNodes as $tireur )
	{
	    if( isset( $tireur->tagName ) )
	    {
		if( $k == 1 )
		{
		    $tireur1Ref = getAttribut( $tireur, 'REF' );
		    $tireur1Pos = $tireurList[ $tireur1Ref ][ NO_IN_POULE ];
		    if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_VICTOIRE )
		    {
			$tireur1Mark = getAttribut( $tireur, 'Statut' );
			$tireurList[ $tireur1Ref ][ NB_MATCH ] ++; //LPo
			
			if( getAttribut( $tireur, 'Score' ) != getAttribut( $phaseXml, 'ScoreMax' ) )
			    $tireur1Mark .= getAttribut( $tireur, 'Score' );
		    }
		    else if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_ABANDON )
		    {
			$tireur1Mark = POULE_STATUT_ABANDON;
			$tireur2Mark = POULE_STATUT_ABANDON;
		    }
		    else if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_EXPULSION )
		    {
			$tireur1Mark = POULE_STATUT_EXPULSION;
			$tireur2Mark = POULE_STATUT_EXPULSION;
		    }
		    else
		    {
			if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_DEFAITE )
		    	    $tireurList[ $tireur1Ref ][ NB_MATCH ] ++; //LPo
			$tireur1Mark = getAttribut( $tireur, 'Score' );
		    }
		}
		else // $k==2
		{
		    $tireur2Ref = getAttribut( $tireur, 'REF' );
		    $tireur2Pos = $tireurList[ $tireur2Ref ][ NO_IN_POULE ];
		    if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_VICTOIRE )
		    {
			$tireur2Mark = getAttribut( $tireur, 'Statut' );
			$tireurList[ $tireur2Ref ][ NB_MATCH ] ++; //LPo
			if( getAttribut( $tireur, 'Score' ) != getAttribut( $phaseXml, 'ScoreMax' ) )
			    $tireur2Mark .= getAttribut( $tireur, 'Score' );
		    }
		    else if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_ABANDON )
		    {	
			$tireur1Mark = POULE_STATUT_ABANDON;
			$tireur2Mark = POULE_STATUT_ABANDON;
		    }
		    else if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_EXPULSION )
		    {	
			$tireur1Mark = POULE_STATUT_EXPULSION;
			$tireur2Mark = POULE_STATUT_EXPULSION;
		    }
		    else if( $tireur2Mark != POULE_STATUT_ABANDON && $tireur2Mark != POULE_STATUT_EXPULSION )
		    {
			if( getAttribut( $tireur, 'Statut' ) == POULE_STATUT_DEFAITE )
			    $tireurList[ $tireur2Ref ][ NB_MATCH ] ++; //LPo
			$tireur2Mark = getAttribut( $tireur, 'Score' );
		    }
		}
		
		$k++;
	    }
	}
	
	$tireurList[ $tireur1Ref ][ FIRST_RES + $tireur2Pos - 1 ] = $tireur1Mark;
	$tireurList[ $tireur2Ref ][ FIRST_RES + $tireur1Pos - 1 ] = $tireur2Mark;
	
	//	echo $tireur1Ref . ' Vs ' . $tireur2Ref . ' -> ' . $tireur1Mark . ' (' . $tireur2Pos . ') ' . ' / ' . $tireur2Mark . ' (' . $tireur1Pos . ')<br/> ';
    }
    
    return $tireurList;
}



/********************************************************/

/*                   CLASSEMENT GENERAL                 */

/********************************************************/
function renderClassement($domXml) {
    $list = '';
    
    $tireurCount = 0;
    
    $searchLabelParent = ( $domXml->documentElement->localName == 'CompetitionParEquipes' ) ? 'Equipes' : 'Tireurs';
    $searchLabelChildren = ( $domXml->documentElement->localName == 'CompetitionParEquipes' ) ? 'Equipe' : 'Tireur';
    
    $tireursXml	= $domXml->getElementsByTagName( $searchLabelParent );
    foreach ($tireursXml as $tireurs) 
    {
	$tireurXml = $tireurs->getElementsByTagName( $searchLabelChildren );
	$tireurCount = 0;
	
	foreach ($tireurXml as $tireur) 
	{
	    if( getAttribut( $tireur, 'Statut' ) != STATUT_ABSENT )
		$tireurCount++;
	}
    }
    
    $list .= '
	<table class="listeTireur">
		<tr>
			<th>Rang</th>
			<th>Nom</th>';
    
    if( $searchLabelChildren == 'Tireur' )
    {
	$list .= '
				<th>Prénom</th>';
    }
    
    $list .= '
			<th>Club</th>
		</tr>';
    
    $i = 1;
    $pair = "pair";
    while($i <= $tireurCount) {
        foreach ($tireursXml as $tireurs) {
            $tireurXml = $tireurs->getElementsByTagName($searchLabelChildren);
            
            foreach ($tireurXml as $tireur) {
                // Utiliser RangFinal au lieu de Classement
                if (getAttribut($tireur, 'RangFinal') == $i) {
                    $list .= '
                        <tr class="'. $pair . '">
                            <td>' . getAttribut($tireur, 'RangFinal') . '</td>
                            <td>' . getAttribut($tireur, 'Nom') . '</td>';
                    
                    if ($searchLabelChildren == 'Tireur') {
                        $list .= '<td>' . getAttribut($tireur, 'Prenom') . '</td>';
                    }
                    
                    $list .= '<td>' . getAttribut($tireur, 'Club') . '</td>
                        </tr>';
                    
                    $pair = $pair == "pair" ? "impair" : "pair";
                }
            }
        }
        $i++;
    }

    $list .= '
	</table>';

    return $list;

} 

// Supprimer la fonction renderMyTableau() qui est déjà définie dans my6.php
// Renommer la nouvelle fonction pour éviter le conflit

function renderMyTableauWithTabs($domXml, $detail, $fold, $titre, $tableauId = '0')
{
    $tableaux = '';
    $phaseXml = $domXml->getElementsByTagName('PhaseDeTableaux')->item(0);

    // Récupérer les valeurs tabStart et tabEnd de l'URL actuelle
    $tabStart = isset($_GET['tabStart']) ? $_GET['tabStart'] : '512';
    $tabEnd = isset($_GET['tabEnd']) ? $_GET['tabEnd'] : '2';

    if ($phaseXml != null) {
        $suiteDeTableaux = $phaseXml->getElementsByTagName('SuiteDeTableaux');
        
        // Afficher les onglets de navigation
        $tableaux .= '<div class="tableaux-tabs">';
        foreach ($suiteDeTableaux as $suite) {
            $id = $suite->getAttribute('ID');
            $titre_tab = $suite->getAttribute('Titre');
            $activeClass = ($id === $tableauId) ? 'active' : '';
            $zoom = isset($_GET['zoom']) ? $_GET['zoom'] : '1';
            // Conserver tabStart et tabEnd dans les liens
            $tableaux .= "<a href='index.php{$GLOBALS['fileqry']}&item=tab&tableau={$id}&zoom={$zoom}&tabStart={$tabStart}&tabEnd={$tabEnd}' 
                      class='tableau-tab {$activeClass}'>";
            $tableaux .= "<i class='fas fa-table'></i> {$titre_tab}</a>";
        }
        $tableaux .= '</div>';

        // Chercher la suite de tableaux correspondant à l'ID demandé
        foreach ($suiteDeTableaux as $suite) {
            if ($suite->getAttribute('ID') === $tableauId) {
                // Créer un nouveau DOMDocument temporaire
                $tempDoc = new DOMDocument('1.0', 'utf-8');
                
                // Créer et ajouter le nœud racine CompetitionIndividuelle
                $rootNode = $tempDoc->createElement('CompetitionIndividuelle');
                $tempDoc->appendChild($rootNode);
                
                // Créer et ajouter le nœud Phases
                $phasesNode = $tempDoc->createElement('Phases');
                $rootNode->appendChild($phasesNode);
                
                // Créer et ajouter le nœud PhaseDeTableaux
                $phaseNode = $tempDoc->createElement('PhaseDeTableaux');
                foreach ($phaseXml->attributes as $attr) {
                    $phaseNode->setAttribute($attr->name, $attr->value);
                }
                $phasesNode->appendChild($phaseNode);
                
                // Cloner le nœud SuiteDeTableaux actuel
                $importedTableau = $tempDoc->importNode($suite, true);
                $phaseNode->appendChild($importedTableau);
                
                // Cloner les nœuds Tireurs et Arbitres nécessaires
                $tireurs = $domXml->getElementsByTagName('Tireurs');
                if ($tireurs->length > 0) {
                    $tireursNode = $tempDoc->importNode($tireurs->item(0), true);
                    $rootNode->appendChild($tireursNode);
                }
                
                $arbitres = $domXml->getElementsByTagName('Arbitres');
                if ($arbitres->length > 0) {
                    $arbitresNode = $tempDoc->importNode($arbitres->item(0), true);
                    $rootNode->appendChild($arbitresNode);
                }

                // Utiliser la fonction existante renderMyTableau avec le document filtré
                $tableaux .= renderMyTableau($tempDoc, $detail, $fold, $titre);
                break;
            }
        }
    }
    
    return $tableaux;
}

function afficheClassementGeneral($xml, $ncol, $abc, $titre) {
    $tireurs = array();
    
    // Récupérer tous les tireurs et leur classement
    $tireursXml = $xml->getElementsByTagName('Tireurs');
    foreach ($tireursXml as $tireursList) {
        foreach ($tireursList->getElementsByTagName('Tireur') as $tireur) {
            $id = $tireur->getAttribute('ID');
            $classement = $tireur->getAttribute('Classement');
            
            if ($classement != '') { // Ne prendre que les tireurs qui ont un classement
                $tireurs[$id] = array(
                    'Classement' => intval($classement),
                    'Nom' => $tireur->getAttribute('Nom'),
                    'Prenom' => $tireur->getAttribute('Prenom'),
                    'Club' => $tireur->getAttribute('Club'),
                    'Nation' => $tireur->getAttribute('Nation'),
                    'Departement' => $tireur->getAttribute('Departement'),
                    'Region' => $tireur->getAttribute('Region')
                );
            }
        }
    }
    
    // Trier les tireurs par classement
    uasort($tireurs, function($a, $b) {
        return $a['Classement'] - $b['Classement'];
    });

    // Préparer l'affichage
    $out = "";
    $head = "";
    $head .= "<div class='tblhd_top' onclick='mickey()'><span class='tbl_banner'>&#9776; $titre</span><br>";
    $head .= "<div class='tblhd'><div></div>\n";
    $fixed_height = isset($_GET['scroll'])?'fh':'';
    
    $head .= "<table id='scrollme' class='listeTireur $fixed_height'>\n";
    $head .= "<thead><tr>
        <th class='RIG B VR'>Place</th>
        <th class='VR'>Nation</th>
        <th class='VR'>Tireur</th>
        <th class='VR'>Club</th>
        <th class='VR'>Dép.</th>
        <th class='VR'>Région</th>
        </tr></thead><tbody>\n";
    
    $body = "";
    $pair = "pair";
    
    foreach ($tireurs as $id => $data) {
        $pair = $pair == "pair" ? "impair" : "pair";
        $body .= "<tr class='{$pair}QC'>";
        $body .= "<td class='RIG B VR'>" . $data['Classement'] . "</td>";
        $body .= "<td class='VR'>" . flag_icon($data['Nation'],'') . " " . $data['Nation'] . "</td>";
        $body .= "<td class='VR'>" . $data['Nom'] . ' ' . $data['Prenom'] . "</td>";
        $body .= "<td class='VR'>" . $data['Club'] . "</td>";
        $body .= "<td class='VR'>" . $data['Departement'] . "</td>";
        $body .= "<td class='VR'>" . $data['Region'] . "</td>";
        $body .= "</tr>\n";
    }
    
    $foot = "</tbody></table></div></div>";
    
    return $head . $body . $foot;
}

function getCompetitionInfo($xml) {
    $comp = $xml->getElementsByTagName('CompetitionIndividuelle')->item(0);
    
    // Conversion de l'arme
    $weaponCode = $comp->getAttribute('Arme');
    $weapon = [
        'E' => 'Épée',
        'F' => 'Fleuret',
        'S' => 'Sabre'
    ][$weaponCode] ?? $weaponCode;
    
    // Conversion du sexe
    $genderCode = $comp->getAttribute('Sexe');
    $gender = [
        'M' => 'Hommes',
        'F' => 'Femmes', 
        'X' => 'Mixte'
    ][$genderCode] ?? $genderCode;
    
    return [
        'date' => $comp->getAttribute('Date'),
        'titre' => $comp->getAttribute('TitreLong'),
        'organisateur' => $comp->getAttribute('Organisateur'),
        'arme' => $weapon,
        'sexe' => $gender,
        'categorie' => $comp->getAttribute('Categorie'),
        'appel' => $comp->getAttribute('Appel'),
        'scratch' => $comp->getAttribute('Scratch'), 
        'debut' => $comp->getAttribute('Debut')
    ];
}

/* ...existing code... */

function getBannerPath() {
    $bannerDir = 'banner/';
    $screenWidth = isset($_COOKIE['screen_width']) ? intval($_COOKIE['screen_width']) : 1920;
    
    // Définir les breakpoints et leurs fichiers correspondants
    $breakpoints = [
        320 => 'banner_320.png',
        480 => 'banner_480.png',
        768 => 'banner_768.png',
        1024 => 'banner_1024.png',
        1440 => 'banner_1440.png',
        1920 => 'banner_1920.png'
    ];
    
    // Trouver la bannière appropriée pour la largeur d'écran
    $selectedBanner = 'banner_1920.png'; // Par défaut
    foreach ($breakpoints as $width => $banner) {
        if ($screenWidth <= $width) {
            $selectedBanner = $banner;
            break;
        }
    }
    
    // Vérifier si le fichier existe
    if (file_exists($bannerDir . $selectedBanner)) {
        return $bannerDir . $selectedBanner;
    }
    
    // Chercher une bannière alternative si celle sélectionnée n'existe pas
    foreach (glob($bannerDir . "banner_*.png") as $banner) {
        return $banner; // Retourne la première bannière trouvée
    }
    
    return ''; // Retourne une chaîne vide si aucune bannière n'est trouvée
}

/* ...existing code... */
