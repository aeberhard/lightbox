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

	unset($rxa_lightbox); 
	include('config.inc.php');
	
	if (!isset($rxa_lightbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}

	// Dateien aus dem Ordner files/lightbox löschen
	if ( !in_array($rxa_lightbox['rexversion'], array('42', '43')) ) {
		if (isset($rxa_lightbox['filesdir']) and ($rxa_lightbox['filesdir']<>'') and ($rxa_lightbox['name']<>'') ) {
			if ($dh = opendir($rxa_lightbox['filesdir'])) {
				while ($el = readdir($dh)) {
					$path = $rxa_lightbox['filesdir'].'/'.$el;
					if ($el != '.' && $el != '..' && is_file($path)) {
						@unlink($path);
					}
				}
			}
		}
		@closedir($dh);
		@rmdir($rxa_lightbox['filesdir']);	
	}
	
	// Evtl Ausgabe einer Meldung
	// De-Installation nicht erfolgreich
	if ( $rxa_lightbox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_lightbox['name']] = '<br /><br />'.$rxa_lightbox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_lightbox['name']] = 1;
	// De-Installation erfolgreich
	} else {
		$REX['ADDON']['install'][$rxa_lightbox['name']] = 0;
	}
?>