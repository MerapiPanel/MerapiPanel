<?php

$link = "/contact/link/redirect/";
if (isset($attributes['use_template']) || isset($attributes['contact'])) {
    if ($attributes['use_template']) {
        $link .= $attributes['template'];
    } else {
        $link .= $attributes['contact'];
    }
} else {
    $link = "#";
}

?>
<a
    <?= !empty($className) ? "class='$className'" : "" ?> href="<?= $link ?>"><?= renderComponents($components) ?>
</a>

