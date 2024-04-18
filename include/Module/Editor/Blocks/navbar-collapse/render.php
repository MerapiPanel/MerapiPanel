<?php

?>

<div class="collapse navbar-collapse"  <?= implode(" ", array_map(function ($key, $value) {
    return $key . '="' . $value . '"'; }, array_keys($attributes), array_values($attributes))) ?>>
    <?= renderComponents($components) ?>
</div>

