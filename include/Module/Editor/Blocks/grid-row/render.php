<?php

?>

<div class="row" <?= implode(array_map(function($attr) use ($attributes) { return $attr . "=\"" . $attributes[$attr] . "\""; }, array_keys($attributes)))?>>
<?= renderComponents($components) ?>
</div>
