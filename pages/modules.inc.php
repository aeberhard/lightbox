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
?>

<?php echo $rxa_compat['backendprefix']; ?>

<div class="rex-addon-output">
<h2 class="rex-hl2"><?php echo $rxa_lightbox['i18n']->msg('menu_modules'); ?></h2>
<div class="rex-addon-content">

<p>
<strong>Modul-Input:</strong><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_lightbox['path'].'/modul-input.txt'));
?>
</textarea>
<br /><br />
<strong>Modul-Output:</strong><br /><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_lightbox['path'].'/modul-output.txt'));
?>
</textarea>
</p>

</div>
</div>

<?php echo $rxa_compat['backendsuffix']; ?>