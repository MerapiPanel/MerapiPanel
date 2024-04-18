<li class="nav-item" <?= implode(" ", array_map(function ($key, $value) {
    return $key . '="' . $value . '"'; }, array_keys($attributes), array_values($attributes))) ?>>
    <?= renderComponents($components) ?>
</li>
