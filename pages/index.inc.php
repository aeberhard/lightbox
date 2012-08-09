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

	// Include Header and Navigation
	include $REX['INCLUDE_PATH'].'/layout/top.php';

	// Für REDAXO < 4.2
	if (isset($REX_USER))
	{
		$REX['USER'] = $REX_USER;
	}

	// Addon-Subnavigation
	$subpages = array(
		array('',$rxa_lightbox['i18n']->msg('menu_settings')),
		array('info',$rxa_lightbox['i18n']->msg('menu_information')),
		array('log',$rxa_lightbox['i18n']->msg('menu_changelog')),
		array('mod',$rxa_lightbox['i18n']->msg('menu_modules')),
	);

	// Titel
	if ( in_array($rxa_lightbox['rexversion'], array('3.11')) ) {
		title($rxa_lightbox['i18n']->msg('title'), $subpages);
	} else {
		rex_title($rxa_lightbox['i18n']->msg('title'), $subpages);
	}

	// Include der angeforderten Seite
	if (isset($_GET['subpage'])) {
		$subpage = $_GET['subpage'];
	} else {
		$subpage = '';
	}
	switch($subpage) {
		case 'info':
			include ($rxa_lightbox['path'] .'/pages/help.inc.php');
		break;
		case 'log':
			include ($rxa_lightbox['path'] .'/pages/changelog.inc.php');
		break;
		case 'mod':
			include ($rxa_lightbox['path'] .'/pages/modules.inc.php');
		break;
		default:
			include ($rxa_lightbox['path'] .'/pages/default_page.inc.php');
		break;		
	}
 
	// Include Footer
	include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>