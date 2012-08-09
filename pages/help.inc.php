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
<h2 class="rex-hl2"><?php echo $rxa_lightbox['i18n']->msg('menu_information'); ?></h2>
<div class="rex-addon-content">

<?php
	include_once ($rxa_lightbox['path'].'/help.inc.php');
?>

</div>
</div>

<?php echo $rxa_compat['backendsuffix']; ?>