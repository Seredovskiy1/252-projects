<?php
function loadDataFromJson() {
    $lastRateFile = 'rates.json';
    if (file_exists($lastRateFile)) {
        $ratesData = json_decode(file_get_contents($lastRateFile), true);
        if (empty($ratesData)) {
            $ratesData = generateInitialData();
            file_put_contents($lastRateFile, json_encode($ratesData));
        }
    } else {
        $ratesData = generateInitialData();
        file_put_contents($lastRateFile, json_encode($ratesData));
    }
    return array_map(function($rate) {
        return [
            'x' => $rate['time'],
            'y' => [$rate['open'], $rate['high'], $rate['low'], $rate['close']]
        ];
    }, $ratesData);
}

function generateInitialData() {
    $data = [];
    $currentTime = time() * 1000; // Поточний час у мілісекундах
    $initialPrice = 100;

    for ($i = 0; $i < 60; $i++) {
        $time = $currentTime - (59 - $i) * 60000; // 60 хвилин назад до поточного часу
        $open = $initialPrice * (1 + (mt_rand(-100, 100) / 10000));
        $close = $open * (1 + (mt_rand(-100, 100) / 10000));
        $high = max($open, $close) * (1 + (mt_rand(0, 50) / 10000));
        $low = min($open, $close) * (1 - (mt_rand(0, 50) / 10000));

        $data[] = [
            'time' => $time,
            'open' => $open,
            'high' => $high,
            'low' => $low,
            'close' => $close
        ];

        $initialPrice = $close; // Використовуємо закриття як відкриття для наступної свічки
    }

    return $data;
}

$data = loadDataFromJson();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Курс Y-франка</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include 'assets/navbar.php';?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Курс Y-франка (Y)</h1>

        <div class="bg-gray-800 rounded-lg p-4 mb-8">
            <div id="chart" class="w-full h-96"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-800 rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">Поточний курс</h2>
                <p class="text-3xl font-bold text-green-500" id="currentRate">Завантаження...</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">Зміна за хвилину</h2>
                <p class="text-3xl font-bold" id="change1m">Завантаження...</p>
            </div>
        </div>
    </main>

    <?php include 'assets/footer.php';?>

    <script>
        var data = <?php echo json_encode($data); ?>;
        
        var options = {
            series: [{
                data: data
            }],
            chart: {
                type: 'candlestick',
                height: 350,
                background: '#1f2937',
                animations: {
                    enabled: false
                }
            },
            title: {
                text: 'Курс Y-франка',
                align: 'left',
                style: {
                    color: '#ffffff'
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    style: {
                        colors: '#ffffff'
                    }
                }
            },
            yaxis: {
                tooltip: {
                    enabled: true
                },
                labels: {
                    style: {
                        colors: '#ffffff'
                    }
                }
            },
            theme: {
                mode: 'dark'
            },
            plotOptions: {
                candlestick: {
                    colors: {
                        upward: '#00B746',
                        downward: '#EF403C'
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        function updateChart() {
            fetch('rates.json')
                .then(response => response.json())
                .then(ratesData => {
                    const lastRate = ratesData[ratesData.length - 1];
                    const currentTime = new Date().getTime();
                    const prevClose = lastRate.close;
                    const change = (Math.random() - 0.5) * 2; // Зменшено діапазон зміни
                    const close = Math.max(1, prevClose * (1 + change / 100));

                    const open = prevClose;
                    const high = Math.max(open, close) * (1 + Math.random() * 0.001);
                    const low = Math.min(open, close) * (1 - Math.random() * 0.001);

                    const newRate = {
                        time: currentTime,
                        open: open,
                        high: high,
                        low: low,
                        close: close
                    };

                    ratesData.push(newRate);
                    if (ratesData.length > 60) {
                        ratesData.shift();
                    }

                    data = ratesData.map(rate => ({
                        x: rate.time,
                        y: [rate.open, rate.high, rate.low, rate.close]
                    }));

                    chart.updateSeries([{data: data}]);

                    document.getElementById('currentRate').textContent = `${close.toFixed(2)} Y`;
                    const change1mElement = document.getElementById('change1m');
                    const change1m = ((close - prevClose) / prevClose) * 100;
                    change1mElement.textContent = `${change1m.toFixed(2)}%`;
                    change1mElement.classList.remove('text-green-500', 'text-red-500');
                    change1mElement.classList.add(change1m >= 0 ? 'text-green-500' : 'text-red-500');

                    return fetch('update_rates.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(ratesData),
                    });
                })
                .then(response => response.json())
                .then(data => console.log('Success:', data))
                .catch(error => console.error('Error:', error));
        }

        setInterval(updateChart, 60000); // Оновлення кожну хвилину
    </script>
</body>
</html>