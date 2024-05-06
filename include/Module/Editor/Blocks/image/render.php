<?php

?>

<img<?= !empty($className) ? ' class="' . $className . '"' : '' ?> <?= implode(" ", array_map(function($attr) use ($attributes) { return $attr . "=\"" . $attributes[$attr] . "\""; }, array_keys($attributes))) ?>  loading="lazy"/>