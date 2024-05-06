<?php
// write logic here
$data = [
    "labels" => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    "data" => [65, 59, 80, 81, 56, 55, 40, 30, 20, 15, 10, 5],
];
?>
<script>window._data = <?php echo json_encode($data) ?></script>
<div class="w-100 h-100 d-flex flex-column pb-5 text-start">
    <h4 class="fs-2 fw-semibold">Website Analytics</h4>
    <canvas class="widget-website-analytic-visitor flex-grow-1"
        style="position: relative; height:40vh; width:80vw"></canvas>
</div>