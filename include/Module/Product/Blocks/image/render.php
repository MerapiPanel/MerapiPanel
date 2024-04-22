<div class="carousel-item<?= $__index == 0 ? ' active' : '' ?>" <?= implode(" ", array_map(function($attr) use ($attributes) { return $attr . "=\"" . $attributes[$attr] . "\""; }, array_keys($attributes))) ?>>
    <?= renderComponents($components) ?>
</div>
