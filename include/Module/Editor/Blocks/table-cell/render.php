<td class="<?= $className ?>" <?= implode(array_map(function($attr) use ($attributes) { return $attr . "=\"" . $attributes[$attr] . "\""; }, array_keys($attributes)))?>>
<?= renderComponents($components) ?>
</td>
