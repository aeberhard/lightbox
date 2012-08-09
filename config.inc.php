<?php
/**
 * --------------------------------------------------------------------
 *
 * Redaxo Addon: Lightbox
 * Version: 1.9, 10.12.2009
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 * 
 * Verwendet wird das Script von Lokesh Dhakar
 * http://www.huddletogether.com/projects/lightbox2/
 *
 * --------------------------------------------------------------------
 */

	// Name des Addons und Pfade
	unset($rxa_lightbox);
	$rxa_lightbox['name'] = 'lightbox';

	$rxa_lightbox['rexversion'] = isset($REX['VERSION']) ? $REX['VERSION'] . $REX['SUBVERSION'] : $REX['version'] . $REX['subversion'];

	$REX['ADDON']['version'][$rxa_lightbox['name']] = '1.9';
	$REX['ADDON']['author'][$rxa_lightbox['name']] = 'Andreas Eberhard';

	$rxa_lightbox['path'] = $REX['INCLUDE_PATH'].'/addons/'.$rxa_lightbox['name'];
	$rxa_lightbox['basedir'] = dirname(__FILE__);
	$rxa_lightbox['lang_path'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_lightbox['name'] .'/lang';
	$rxa_lightbox['sourcedir'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_lightbox['name'] .'/'. $rxa_lightbox['name'];
	$rxa_lightbox['meldung'] = '';

	$rxa_lightbox['filesdir'] = $REX['HTDOCS_PATH'].'files/'.$rxa_lightbox['name'];
	if ( in_array($rxa_lightbox['rexversion'], array('42', '43')) ) {
		$rxa_lightbox['filesdir'] = $REX['HTDOCS_PATH'].'files/addons/'.$rxa_lightbox['name'];
	}
	$rxa_lightbox['htdocsfilesdir'] = $rxa_lightbox['filesdir'];

	// für Kompatibilität REDAXO 3.1, 3.2.x, 4.0.x
	include($rxa_lightbox['basedir'] . '/functions/functions.compat.inc.php');	
	
/**
 * --------------------------------------------------------------------
 * Nur im Backend
 * --------------------------------------------------------------------
 */
	if (!$REX['GG']) {
		// Sprachobjekt anlegen
		$rxa_lightbox['i18n'] = new i18n($REX['LANG'],$rxa_lightbox['lang_path']);

		// Anlegen eines Navigationspunktes im REDAXO Hauptmenu
		$REX['ADDON']['page'][$rxa_lightbox['name']] = $rxa_lightbox['name'];
		// Namensgebung für den Navigationspunkt
		$REX['ADDON']['name'][$rxa_lightbox['name']] = $rxa_lightbox['i18n']->msg('menu_link');

		// Berechtigung für das Addon
		$REX['ADDON']['perm'][$rxa_lightbox['name']] = $rxa_lightbox['name'].'[]';
		// Berechtigung in die Benutzerverwaltung einfügen
		$REX['PERM'][] = $rxa_lightbox['name'].'[]';		
	}

/**
 * --------------------------------------------------------------------
 * Outputfilter für das Frontend
 * --------------------------------------------------------------------
 */
	if ($REX['GG'])
	{
		rex_register_extension('OUTPUT_FILTER', 'lightbox_opf');

		// Prüfen ob die aktuelle Kategorie mit der Auswahl übereinstimmt
		function lightbox_check_cat($acat, $aart, $subcats, $lightbox_cats)
		{

			// prüfen ob Kategorien ausgewählt
			if (!is_array($lightbox_cats)) return false;

			// aktuelle Kategorie in den ausgewählten dabei?
			if (in_array($acat, $lightbox_cats)) return true;

			// Prüfen ob Parent der aktuellen Kategorie ausgewählt wurde
			if ( ($acat > 0) and ($subcats == 1) )
			{
				$cat = OOCategory::getCategoryById($acat);
				while($cat = $cat->getParent())
				{
					if (in_array($cat->_id, $lightbox_cats)) return true;
				}
			}

			// evtl. noch Root-Artikel prüfen
			if (strstr(implode('',$lightbox_cats), 'r'))
			{
				if (in_array($aart.'r', $lightbox_cats)) return true;
			}

			// ansonsten keine Ausgabe!
			return false;
		}

		// Output-Filter
		function lightbox_opf($params)
		{
			global $REX, $REX_ARTICLE;
			global $rxa_lightbox;

			// Für REDAXO < 4.2
			if (isset($REX_ARTICLE))
			{
				$REX['ARTICLE'] = $REX_ARTICLE;
			}
			
			$content = $params['subject'];
			
			if ( !strstr($content,'</head>') or !file_exists($rxa_lightbox['path'].'/'.$rxa_lightbox['name'].'.ini')
			 or ( strstr($content,'<script type="text/javascript" src="'.$rxa_lightbox['htdocsfilesdir'].'/lightbox.js"></script>') and strstr($content,'<link rel="stylesheet" href="'.$rxa_lightbox['htdocsfilesdir'].'/lightbox.css" type="text/css" media="screen" />') ) ) {
				return $content;
			}

			// Einstellungen aus ini-Datei laden
			if (($lines = file($rxa_lightbox['path'].'/'.$rxa_lightbox['name'].'.ini')) === FALSE) {
				return $content;
			} else {
				$va = explode(',', trim($lines[0]));
				$allcats = trim($va[0]);
				$subcats = trim($va[1]);
				$lightbox_cats = array();
				$lightbox_cats = unserialize(trim($lines[1]));
				$rxa_lightbox['excludeids'] = unserialize(trim($lines[2]));
			}

			// aktuellen Artikel ermitteln
			$artid = isset($_GET['article_id']) ? $_GET['article_id']+0 : 0;
			if ($artid==0) {
				$artid = $REX['ARTICLE']->getValue('article_id')+0;
			}
			if ($artid==0) { $artid = $REX['START_ARTICLE_ID']; }

			if (!$artid) { return $content; }

			$article = OOArticle::getArticleById($artid);
			if (!$article) { return $content; }

			// Exclude ID?
			if (in_array($artid, explode(',', $rxa_lightbox['excludeids']))) { return $content; }
			
			// aktuelle Kategorie ermitteln
			if ( in_array($rxa_lightbox['rexversion'], array('3.11')) ) {
				$acat = $article->getCategoryId();
			}
			if ( in_array($rxa_lightbox['rexversion'], array('32', '40', '41', '42', '43')) ) {
				$cat = $article->getCategory();
				if ($cat) {
					$acat = $cat->getId();
				}
			}
			// Wenn keine Kategorie ermittelt wurde auf -1 setzen für Prüfung in lightbox_check_cat, Prüfung auf Artikel im Root
			if (!isset($acat) or !$acat) { $acat = -1; }

			// Array anlegen falls keine Kategorien ausgewählt wurden
			if (!is_array($lightbox_cats)){
				$lightbox_cats = array();
			}

			// Code für Lightbox im head-Bereich ausgeben
			if ( ($allcats==1) or (lightbox_check_cat($acat, $artid, $subcats, $lightbox_cats) == true) )
			{
				$rxa_lightbox['output'] = '	<!-- Addon Lightbox '.$REX['ADDON']['version'][$rxa_lightbox['name']].' -->'."\n";
				$rxa_lightbox['output'] .= '	<link rel="stylesheet" href="'.$rxa_lightbox['htdocsfilesdir'].'/lightbox.css" type="text/css" media="screen" />'."\n";
				$rxa_lightbox['output'] .= '	<script type="text/javascript" src="'.$rxa_lightbox['htdocsfilesdir'].'/prototype.js"></script>'."\n";
				$rxa_lightbox['output'] .= '	<script type="text/javascript" src="'.$rxa_lightbox['htdocsfilesdir'].'/scriptaculous.js?load=effects"></script>'."\n";
				$rxa_lightbox['output'] .= '	<script type="text/javascript" src="'.$rxa_lightbox['htdocsfilesdir'].'/builder.js"></script>'."\n";
				$rxa_lightbox['output'] .= '	<script type="text/javascript">'."\n";
				$rxa_lightbox['output'] .= '	LightboxOptions = Object.extend({'."\n";
				$rxa_lightbox['output'] .= '		fileLoadingImage: \''.$rxa_lightbox['htdocsfilesdir'].'/loading.gif\','."\n";
				$rxa_lightbox['output'] .= '		fileBottomNavCloseImage: \''.$rxa_lightbox['htdocsfilesdir'].'/closelabel.gif\','."\n";
				$rxa_lightbox['output'] .= '	}, window.LightboxOptions || {});'."\n";
				$rxa_lightbox['output'] .= '	</script>'."\n";
				$rxa_lightbox['output'] .= '	<script type="text/javascript" src="'.$rxa_lightbox['htdocsfilesdir'].'/lightbox.js"></script>'."\n";
	
				$content = str_replace('</head>', $rxa_lightbox['output'].'</head>', $content);
			}

			return $content;
		}

	}
?>