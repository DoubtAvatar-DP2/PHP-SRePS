function DrawChart(labels, itemsSold, revenues) {

    // delete previous chart
    document.getElementById("chart").innerHTML = `<canvas id="canvas"></canvas>`;
    var mix = document.getElementById("canvas").getContext('2d');
    
    // draw chart
    var mixChart = new Chart(mix, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'line',
                    label: "itemsSold",
                    data: itemsSold,
                    borderColor: '#FE5E41',
                    backgroundColor: 'rgba(52, 89, 149, 0)',
                    yAxisID: 'itemsSold',
                },
                {
                    type: 'bar',
                    label: "Revenues",
                    data: revenues,
                    borderColor: 'rgba(0, 0, 0, 0)',
                    backgroundColor: '#00A878',
                    fill: true,
                    yAxisID: 'revenues',
                },
            ]
        },
        options: {
            scales: {
                yAxes: [
                    {
                        id: "itemsSold",
                        ticks: {
                            beginAtZero: true,
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'itemsSold'
                        }
                    },
                    {
                        id: "revenues",
                        position: 'right',
                        ticks: {
                            beginAtZero: true,
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'revenues ($)'
                        }
                    },
                ]
            },
    
            responsive: true,
    
            title: {
                display: true,
                text: `Total items sold and revenue for each day`,
                position: 'bottom'
            },
            
            tooltips: 
            {
                mode: 'index',
                intersect: true
            },
            
            legend: 
            {
                display: true,
                position: 'right',
                align: 'center'
            }
        }
    });
}
