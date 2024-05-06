import "./style.scss";
import Chart from 'chart.js/auto';

const ctx = document.querySelector('.widget-website-analytic-visitor').getContext('2d');
const data = {
    labels: window._data.labels,
    datasets: [
        {
            label: 'Visitors',
            data: window._data.data,
            fill: true,
            borderColor: "rgb(255, 61, 77)",
            backgroundColor: 'rgba(255, 61, 77, 0.2)',
            borderColor: 'rgb(255, 61, 77)',
        }
    ]
};

const chart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        animation: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});