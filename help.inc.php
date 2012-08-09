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

	include('config.inc.php');
	if (!isset($rxa_lightbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}
		
	echo $rxa_lightbox['i18n']->msg('text_help_title', $REX['ADDON']['version'][$rxa_lightbox['name']]);
	$i=1;
	while ($rxa_lightbox['i18n']->msg('text_help_'.$i)<>'[translate:text_help_'.$i.']') {
		echo $rxa_lightbox['i18n']->msg('text_help_'.$i);
		$i++;
		if ($i>10) { break; }
	}
?>
