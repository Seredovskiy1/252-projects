document.addEventListener('DOMContentLoaded', function() {
    var options = {
        series: [{
            data: []
        }],
        chart: {
            type: 'candlestick',
            height: 500,
            background: '#111827', // Темніший фон
            animations: {
                enabled: false
            },
            toolbar: {
                show: true,
                tools: {
                    download: false,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                },
                autoSelected: 'zoom'
            },
        },
        grid: {
            show: true,
            borderColor: '#1f2937',
            strokeDashArray: 1,
            position: 'back',
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
        },
        title: {
            text: 'Курс Y-франка (хвилинні свічки)',
            align: 'left',
            style: {
                fontSize: '16px',
                color: '#d1d5db'
            }
        },
        xaxis: {
            type: 'datetime',
            axisBorder: {
                color: '#374151'
            },
            axisTicks: {
                color: '#374151'
            },
            labels: {
                style: {
                    colors: '#9ca3af'
                },
                datetimeFormatter: {
                    year: 'yyyy',
                    month: 'MMM \'yy',
                    day: 'dd MMM',
                    hour: 'HH:mm'
                }
            },
            tickAmount: 10,
            range: 60 * 60 * 1000, // 60 minutes in milliseconds
        },
        yaxis: {
            tooltip: {
                enabled: true
            },
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        theme: {
            mode: 'dark',
            palette: 'palette1'
        },
        plotOptions: {
            candlestick: {
                colors: {
                    upward: '#10B981',
                    downward: '#EF4444'
                },
                wick: {
                    useFillColor: true,
                }
            }
        },
        tooltip: {
            theme: 'dark',
            x: {
                format: 'dd MMM HH:mm'
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    function updateChart() {
        fetch('data/get_latest_data.php')
            .then(response => response.json())
            .then(data => {
                console.log("Received data:", data);
                if (data.chartData && Array.isArray(data.chartData)) {
                    chart.updateSeries([{
                        data: data.chartData
                    }]);
                }
                if (data.currentRate !== undefined) {
                    document.getElementById('currentRate').textContent = data.currentRate.toFixed(2);
                }
                if (data.change1m !== undefined) {
                    const change1mElement = document.getElementById('change1m');
                    change1mElement.textContent = data.change1m.toFixed(2) + '%';
                    change1mElement.classList.remove('text-green-500', 'text-red-500');
                    change1mElement.classList.add(data.change1m >= 0 ? 'text-green-500' : 'text-red-500');
                }
                if (data.volume !== undefined) {
                    document.getElementById('volume').textContent = data.volume.toFixed(2);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Оновлюємо дані кожні 5 секунд
    setInterval(updateChart, 5000);
    // Викликаємо функцію одразу для першого завантаження даних
    updateChart();
});



// Додайте цей код в кінець файлу js/currency.js

document.addEventListener('DOMContentLoaded', function() {
    const tradeType = document.getElementById('tradeType');
    const buySection = document.getElementById('buySection');
    const sellSection = document.getElementById('sellSection');
    const usdAmount = document.getElementById('usdAmount');
    const yfrankAmountToSell = document.getElementById('yfrankAmountToSell');
    const resultAmount = document.getElementById('resultAmount');
    const resultCurrency = document.getElementById('resultCurrency');

    tradeType.addEventListener('change', function() {
        if (this.value === 'buy') {
            buySection.style.display = 'block';
            sellSection.style.display = 'none';
            resultCurrency.textContent = 'Y';
        } else {
            buySection.style.display = 'none';
            sellSection.style.display = 'block';
            resultCurrency.textContent = '$';
        }
        calculateResult();
    });

    [usdAmount, yfrankAmountToSell].forEach(input => {
        input.addEventListener('input', calculateResult);
    });

    function calculateResult() {
        const currentRate = parseFloat(document.getElementById('currentRate').textContent);
        if (tradeType.value === 'buy') {
            const usd = parseFloat(usdAmount.value) || 0;
            resultAmount.value = (usd / currentRate).toFixed(2);
        } else {
            const yfrank = parseFloat(yfrankAmountToSell.value) || 0;
            resultAmount.value = (yfrank * currentRate).toFixed(2);
        }
    }
});