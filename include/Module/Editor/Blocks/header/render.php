<?php
$class = " class=\"" . implode(" ", $classes ?? []) . "\"";
?>

<header<?= $class ?> <?= isset($attributes) ? implode(" ", array_map(function($attr) use ($attributes) { return $attr . "=\"" . $attributes[$attr] . "\""; }, array_keys($attributes))) : ""?> >
    <?= renderComponents($components) ?>
</header>