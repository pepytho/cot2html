<?php

define( "STATUT_PRESENT", "Q" );
define( "STATUT_ABSENT", "F" );
define( "STATUT_ELIMINE", "N" );

define( "POULE_STATUT_VICTOIRE", "V" );
define( "POULE_STATUT_DEFAITE", "D" );
define( "POULE_STATUT_ABANDON", "A" );
define( "POULE_STATUT_EXPULSION", "E" );

define( "SEXE_MALE", "M" );
define( "SEXE_FEMALE", "F" );

function endsWith( $str, $sub ) 
{
    return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}

function explorer($dir, $extension = '') {
    $files = array();
    
    // Vérifier si le dossier existe
    if (!is_dir($dir)) {
        error_log("Dossier non trouvé: $dir");
        return $files;
    }

    try {
        // Scan le dossier
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if ($item != "." && $item != "..") {
                $fullpath = $dir . DIRECTORY_SEPARATOR . $item;
                
                // Vérifier si c'est un fichier et si l'extension correspond
                if (is_file($fullpath) && 
                    (empty($extension) || strtolower(substr($item, -strlen($extension))) === strtolower($extension))) {
                    $files[] = $fullpath;
                }
            }
        }
    } catch (Exception $e) {
        error_log("Erreur lors du scan du dossier $dir: " . $e->getMessage());
    }

    return $files;
}

function selectedCompetition()
{
    if( isset( $_GET[ 'idCompetition' ] ) )
	return $_GET[ 'idCompetition' ];

    return -1;
}

function selectedPhase()
{
    if( isset( $_GET[ 'phaseId' ] ) )
	return $_GET[ 'phaseId' ];

    return -1;
}

function selectedPhaseName( $domXml )
{
    if( isset( $_GET[ 'phaseId' ] ) )
    {
	$phase = getPhaseXmlElement( $domXml, $_GET[ 'phaseId' ] );

	if( $phase != null )
	    return $phase->tagName;
    }

    return 'ListeInscrit';
}

function getPhaseXmlElement( $docXml, $idPhase )
{
    $phasesXml = $docXml->getElementsByTagName( 'Phases' );

    foreach( $phasesXml as $phases ) 
    {
	foreach($phases->childNodes as $phase)
	{
	    if( isset( $phase->tagName ) && getAttribut( $phase, 'PhaseID' ) == $idPhase )
		return $phase;
	}
    }

    return null;
}

function makeUrl($paramsOverlay, $clear=0) 
{
    $url = 'index.php?action=affichage';

    if ($clear == 0)
    {
	if( isset( $_GET[ 'refresh' ] ) )
	    unset( $_GET[ 'refresh' ] );

	$params = array_merge($_GET, $paramsOverlay);
    }
    else 
	$params = $paramsOverlay;

    foreach ($params as $k=>$v) if (! is_null($v))
    {
	$url.= '&'.$k.'='.$v;
    }
    return $url;
}

/*************
   XML
 *************/
function getAttribut( $xmlElement, $attributName )
{
    return $xmlElement->getAttribute( $attributName );
}

function fillSessionWithTitreLong()
{
    if( isset( $_SESSION[ 'cotcotFiles' ] ) )
    {
	$DOMxml = new DOMDocument( '1.0', 'utf-8' );
	$titre = array();

	foreach( $_SESSION[ 'cotcotFiles' ] as $file )
	{
	    $DOMxml->load( $file );

	    $competXml = $DOMxml->documentElement;
	    $titre[$file] = getAttribut( $competXml, 'TitreLong' );
	}

	$_SESSION[ 'titreList'] = $titre;
    }
}

function getCategorieLibelle( $categorie )
{
    switch( $categorie )
    {
	case 'B':
	return 'Benjamin';
	case 'M':
	return 'Minime';
	case 'C':
	return 'Cadet';
	case 'J':
	return 'Junior';
	case 'S':
	return 'Sénior';
	case 'V':
	return 'Vétéran';
	default:
	return $categorie;
    }
}

function getArmeLibelle( $arme )
{
    switch( $arme )
    {
	case 'F':
	return 'Fleuret';
	case 'E':
	return '&Eacute;p&eacute;e';
	case 'S':
	return 'Sabre';
    }
}

function getSexeLabel( $sexe )
{
    switch( $sexe )
    {
	case SEXE_MALE:
	return 'Homme';
	case SEXE_FEMALE:
	return 'Dame';
    }
}
?>
