<?php

$item = !empty($attributes['item']) ? $attributes['item'] : "item";
$arrr = !empty($attributes['arrr']) ? $attributes['arrr'] : "array";

?>

{% for <?= $item ?> in <?= $arrr ?> %}
    <?= renderComponents($components) ?>
{% endfor %}

