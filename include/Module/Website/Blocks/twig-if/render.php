<?php
$condition = !empty($attributes['condition']) ? $attributes['condition'] : "null";

?>

{% if <?= $condition ?> %}
    <?= renderComponents($components) ?>
{% endif %}

