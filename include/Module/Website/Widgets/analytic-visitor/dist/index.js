
const chartDiagramVisitor = document.querySelector('#chart-diagram-visitor');

const chartVisitor = new Chart(chartDiagramVisitor.getContext('2d'), {
    type: 'bar',
    data: {
        labels: data_visitor.labels,
        datasets: [{
            fill: true,
            label: 'Visitor',
            data: data_visitor.values,
            backgroundColor: [
                'rgb(219, 39, 126)',
            ]
        }]
    },
    options: {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
            callbacks: {
                label: function (tooltipItem) {
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
                    drawBorder: false,
                    //drawTicks: false
                }
            },
            x: {
                grid: {
                    drawOnChartArea: false,
                    drawBorder: false
                }
            }

        }
    }
});
