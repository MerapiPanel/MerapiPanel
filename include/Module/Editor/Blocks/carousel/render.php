<?php
$class = " class=\"" . implode(" ", $classes ?? []) . "\"";
?>

<div class="carousel slide" <?= isset($attributes['id']) ? 'id="' . $attributes['id'] . '"' : '' ?>>
<?= renderComponents($components) ?>
</div>

