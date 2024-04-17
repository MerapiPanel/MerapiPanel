<?php
$class = " class=\"" . implode(" ", $classes ?? []) . "\"";
?>

<div<?= $class ?>><?= renderComponents($components) ?></div>
