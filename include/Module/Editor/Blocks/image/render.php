<?php
$class = " class=\"" . implode(" ", $classes ?? []) . "\"";
?>

<img<?= $class ?> <?= implode(" ", array_map(function($attr) use ($attributes) { return $attr . "=\"" . $attributes[$attr] . "\""; }, array_keys($attributes))) ?> />