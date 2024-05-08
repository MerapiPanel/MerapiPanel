<?php
use MerapiPanel\Box;

$module = Box::module("Contact");
$logs_service = $module->Logs;
// write logic here
$list_data = $logs_service->readRange(date("Y-m-d", strtotime("-7 day")), date("Y-m-d"));
$data = [
    "labels" => array_map(function($item) {
        return date("M d", strtotime($item));
    }, array_keys($list_data)),
    "values" => array_map(function($item) {
        return count($item);
    }, array_values($list_data))
];

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="w-100 h-100 d-flex flex-column pb-5 text-start">
    <h4 class="fs-2 fw-semibold">Contact Analytics</h4>
    <canvas class="widget-contact-analytic flex-grow-1"
        style="position: relative; height:40vh; width:80vw"></canvas>
</div>

<script>
    const data = JSON.parse(`<?= json_encode($data) ?>`);
    const ctx = document.querySelector('.widget-contact-analytic').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                fill: true,
                label: 'Calls',
                data: data.values,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)'
                    ]
            }]
        },
        options: {
            animation: false,
            tooltips: {
        callbacks: {
           label: function(tooltipItem) {
                  return tooltipItem.yLabel;
           }
        }
    },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        display: false
                    }
                }],
                y: {
                    beginAtZero: true,
                    suggestedMax: 10,
                    min: 0,
                    grid: {
                        //drawOnChartArea:false,
                        drawBorder:false,
                        //drawTicks: false
                    }
                },
                x: {
                    grid: {
                        drawOnChartArea:false,
                        drawBorder:false
                    }
                }

            }
        }  
    });
</script>
