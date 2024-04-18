<?php

?>

<ul class="navbar-nav me-auto mb-2 mb-lg-0" <?= implode(" ", array_map(function ($key, $value) {
    return $key . '="' . $value . '"'; }, array_keys($attributes), array_values($attributes))) ?>>
    <?= renderComponents($components) ?>
</ul>

