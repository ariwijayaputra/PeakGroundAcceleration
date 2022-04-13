
const ctx = document.getElementById("graph").getContext("2d");

const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        datasets: [{
            label: '# of PGA',
            data: [0, 0,0,0,0,0,0,0,0,0],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        transition: {
            duration: 100
        }
    }
});

function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data);
    });
    chart.update();
}

function removeData(chart) {
    chart.data.labels.shift();
    chart.data.datasets.forEach((dataset) => {
        dataset.data.shift();
    });
    chart.update();
}