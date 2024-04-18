<?php

?>

<button class="navbar-toggler" <?= implode(" ", array_map(function ($key, $value) {
    return $key . '="' . $value . '"'; }, array_keys($attributes), array_values($attributes))) ?>>
    <span class="navbar-toggler-icon"></span>
</button>

