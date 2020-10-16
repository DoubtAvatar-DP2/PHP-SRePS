function DrawChart(labels, pastSales, futureSales) {
    // delete previous chart
    document.getElementById("chart").innerHTML = `<canvas id="canvas"></canvas>`;

    var mix = document.getElementById("canvas").getContext('2d');
    var mixChart = new Chart(mix, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Past revenues',
                    data: [...pastSales, ...new Array(futureSales.length).fill(null)],
                    borderColor: '#00A878',
                    fill: false,
                    yAxisID: 'revenues',
                },
                {
                    type: 'line',
                    label: "Future revenues",
                    data: [...new Array(pastSales.length).fill(null), ...futureSales],
                    borderColor: '#0078A8',
                    fill: false,
                    yAxisID: 'revenues',
                    borderDash: [10,5]
                },
            ]
        },
        options: {
            scales: {
                yAxes: [
                    // {
                    //     id: "items",
                    //     ticks: {
                    //         beginAtZero: true,
                    //     },
                    //     scaleLabel: {
                    //         display: true,
                    //         labelString: 'items'
                    //     }
                    // },
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
                text: `Total sales and number of records`,
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
                align: 'center',
            },

            annotation: {
                annotations: [{
                  type: 'line',
                  mode: 'horizontal',
                  scaleID: 'revenues',
                  value: 50000,
                  endValue: 0,
                  borderColor: 'rgb(75, 75, 75)',
                  borderWidth: 4,
                  borderDash: [8,4],
                  label: {
                    enabled: true,
                    content: 'Trendline',
                    yAdjust: -16,
                  }
                }]
              }
        }
    });

}