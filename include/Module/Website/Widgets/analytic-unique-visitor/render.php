<?php
use MerapiPanel\Box;

$raw_data_unique_visitor = Box::module('Website')->Logs->readRange(date('Y-m-d', strtotime('-1 days')), date('Y-m-d'));
$data_unique_visitor =[
    "labels" => ['Yesterday', 'Today'],
    "values" => array_map(function($data) {
        // filter client_ip
        $clients_ip = [];
        foreach ($data as $key => $value) {
            if (isset($value['client_ip'])) {
                $clients_ip[] = $value['client_ip'];
            }
        }
        return count(array_unique($clients_ip));

    }, array_values($raw_data_unique_visitor))
];
?>
<script>
    const data_unique_visitor = <?= json_encode($data_unique_visitor) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="w-100 h-100 d-flex flex-column pb-5 text-start">
    <h4 class="fs-2 fw-semibold">Unique Visitor</h4>
    <canvas height="100%"  id="chart-unique-visitor"></canvas>
</div>
