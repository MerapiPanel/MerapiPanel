<?php

$link = "/contact/link/redirect/";
if ($attributes['use_template']) {
    $link .= $attributes['template'];
} else {
    $link .= $attributes['contact'];
}

?>
<a
    <?= !empty($className) ? "class='$className'" : "" ?> href="<?= $link ?>"><?= renderComponents($components) ?>
</a>

