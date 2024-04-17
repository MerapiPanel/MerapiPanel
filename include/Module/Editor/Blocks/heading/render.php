<?php
// logic here
$class = " class=\"" . implode(" ", $classes ?? []) . "\"";

?>


<?php if (!isset($tagName) || $tagName == "h1"): ?>
    <h1<?= $class ?>><?= renderComponents($components) ?></h1>
<?php else: ?>

    <?php if ($tagName == "h2"): ?>
        <h2<?= $class ?>><?= renderComponents($components) ?></h2>
    <?php elseif ($tagName == "h3"): ?>
        <h3<?= $class ?>><?= renderComponents($components) ?></h3>
    <?php elseif ($tagName == "h4"): ?>
        <h4<?= $class ?>><?= renderComponents($components) ?></h4>
    <?php elseif ($tagName == "h5"): ?>
        <h5<?= $class ?>><?= renderComponents($components) ?></h5>
    <?php elseif ($tagName == "h6"): ?>
        <h6<?= $class ?>><?= renderComponents($components) ?></h6>
    <?php else: ?>
        <div class="alert alert-danger">
            <h4 class="alert-title">
                <i class="fa fa-exclamation-triangle"></i>
                Unknown heading</h4>
            <p class="alert-text">Please use one of the following tags: h1, h2, h3, h4, h5, h6</p>
        </div>
    <?php endif; ?>

<?php endif; ?>

