window.onload = () => {

    const body = document.body;
    const wrapperComputedStyle = window.getComputedStyle(body, null)
    const width = body.clientWidth - ((parseFloat(wrapperComputedStyle.paddingLeft) + parseFloat(wrapperComputedStyle.paddingRight)) / 2)
    const height = body.clientHeight - ((parseFloat(wrapperComputedStyle.paddingTop) + parseFloat(wrapperComputedStyle.paddingBottom)) / 2)


    const ctx = document.getElementById('myChart');

    document.body.classList.add("loaded")


    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'My First Dataset',
            data: [65, 59, 80, 81, 56, 55, 40],
            fill: false,
            borderColor: '#99cdff',
            pointBackgroundColor: "#3492eb",
            tension: 0.1,
            pointRadius: 5,
            pointHoverRadius: 5
        }]
    };




    const chart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            elements: {
                point: {
                    radius: 5,
                    hoverRadius: 10, // ex.: to make it bigger when user hovers put larger number than radius.
                }
            },
            scales: {
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        display: false,
                        drawBorder: false,
                    }
                },
                y: {
                    border: {
                        display: false,
                    },
                    grid: {
                        drawBorder: false,
                    },
                    beginAtZero: true,
                },
            }
        }
    });


    // let h = ctx.height - ctx.clientHeight;

    chart.canvas.parentNode.style.height = (height - ctx.parentElement.offsetTop) + 'px';
    chart.canvas.parentNode.style.width = (width - ctx.parentElement.offsetLeft) + 'px';
}
