<?php
$class = " class=\"" . implode(" ", $classes ?? []) . "\"";
?>

<p<?= $class ?>><?= renderComponents($components) ?></p>
