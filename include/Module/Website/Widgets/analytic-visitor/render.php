<?php
use MerapiPanel\Box;

$raw_data_visitor = Box::module('Website')->Logs->readRange(date('Y-m-d', strtotime('-7 days')), date('Y-m-d'));
$data_visitor =[
    "labels" => array_map(fn($date) => date('M d', strtotime($date)), array_keys($raw_data_visitor)),
    "values" => array_map(fn($data) => count($data), $raw_data_visitor)
];

?>
<script>
    const data_visitor = <?= json_encode($data_visitor) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="w-100 h-100 d-flex flex-column pb-5 text-start">
    <h4 class="fs-2 fw-semibold">Visitor Analytics</h4>
    <canvas id="chart-diagram-visitor"></canvas>
</div>
