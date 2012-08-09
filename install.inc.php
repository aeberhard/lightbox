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
 
	if (!function_exists("is__writable")) {
	function is__writable($path)
	{
		if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
			return is__writable($path.uniqid(mt_rand()).'.tmp');
		else if (is_dir($path))
			return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
		// check tmp file for read/write capabilities
		$rm = file_exists($path);
		$f = @fopen($path, 'a');
		if ($f===false)
			return false;
		fclose($f);
		if (!$rm)
			unlink($path);
		return true;
	}
	} // End function_exists 

	unset($rxa_lightbox);
	include('config.inc.php');

	if (!isset($rxa_lightbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		$REX['ADDON']['install'][$rxa_lightbox['name']] = 0;
		return;
	}

	// Gültige REDAXO-Version abfragen
	if ( !in_array($rxa_lightbox['rexversion'], array('3.11', '32', '40', '41', '42', '43')) ) {
		echo '<font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_lightbox['rexversion'].'</strong></font>';
		$REX['ADDON']['installmsg'][$rxa_lightbox['name']] = '<br /><br /><font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_lightbox['rexversion'].'</strong></font>';
		$REX['ADDON']['install'][$rxa_lightbox['name']] = 0;
		return;
	}
	
	// Schreibrechte für ini-Datei setzen
	@chmod($rxa_lightbox['basedir'] . '/'. $rxa_lightbox['name'] . '.ini', 0755);
	
	// Verzeichnis files/lightbox anlegen
	if ( !@is_dir($rxa_lightbox['filesdir']) ) {
		if ( !@mkdir($rxa_lightbox['filesdir']) ) {
			$rxa_lightbox['meldung'] .= $rxa_lightbox['i18n']->msg('error_createdir', $rxa_lightbox['filesdir']);
		}
	}
	@chmod($rxa_lightbox['filesdir'], 0755);
	if (!is__writable($rxa_lightbox['filesdir'].'/')) {
		$rxa_lightbox['meldung'] .= $rxa_lightbox['i18n']->msg('error_writedir', $rxa_lightbox['filesdir']);
	}	

	// Dateien ins Verzeichnis files/lightbox kopieren
	if ($dh = opendir($rxa_lightbox['sourcedir'])) {
		while ($el = readdir($dh)) {
			$rxa_lightbox['file'] = $rxa_lightbox['sourcedir'].'/'.$el;
			if ($el != '.' && $el != '..' && is_file($rxa_lightbox['file'])) {
				if ( !@copy($rxa_lightbox['file'], $rxa_lightbox['filesdir'].'/'.$el) ) {
					$rxa_lightbox['meldung'] .= $rxa_lightbox['i18n']->msg('error_copyfile', $el, $rxa_lightbox['filesdir'].'/');
				}
			}
		}
	} else {
		$rxa_lightbox['meldung'] .= $rxa_lightbox['i18n']->msg('error_readdir',$rxa_lightbox['sourcedir']);
	}
	
	// Evtl Ausgabe einer Meldung
	// $rxa_lightbox['meldung'] = 'Das Addon wurde nicht installiert, weil...';
	if ( $rxa_lightbox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_lightbox['name']] = '<br /><br />'.$rxa_lightbox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_lightbox['name']] = 0;
	} else {
	// Installation erfolgreich
		$REX['ADDON']['install'][$rxa_lightbox['name']] = 1;
	}
?>