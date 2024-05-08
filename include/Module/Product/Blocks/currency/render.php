<?php

use MerapiPanel\Box;
$config = Box::module("Product")->getConfig();
$currency_symbol = $config->get("currency_symbol") ?? "$";
?>

<span class="fw-semibold"><?= $currency_symbol ?></span>
