<a <?= !empty($className) ? ' class="' . $className . '"' : '' ?> <?= implode(" ", array_map(fn($k, $v) => "$k=\"$v\"", array_keys($attributes), array_values($attributes))) ?>>
<?= renderComponents($components) ?>
</a>