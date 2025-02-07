<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

function logError($message) {
    error_log($message, 3, "../error.log");
}

function loadDataFromJson($filename) {
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        if ($content === false) {
            throw new Exception("Unable to read $filename");
        }
        $data = json_decode($content, true);
        if ($data === null) {
            throw new Exception("Invalid JSON in $filename: " . json_last_error_msg());
        }
        return $data;
    }
    return [];
}

function saveDataToJson($filename, $data) {
    $jsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $result = file_put_contents($filename, $jsonString);
    if ($result === false) {
        throw new Exception("Unable to write to $filename");
    }
}



function generateNewCandle($lastCandle, $transactions) {
    $currentTime = time() * 1000;
    $open = $lastCandle['close'];
    $high = $open;
    $low = $open;
    $close = $open;

    $volume = 0;
    $buyVolume = 0;
    $sellVolume = 0;

    foreach ($transactions as $transaction) {
        if ($transaction['timestamp'] > $lastCandle['time'] && $transaction['timestamp'] <= $currentTime) {
            $volume += $transaction['amount'];
            if ($transaction['action'] === 'buy') {
                $buyVolume += $transaction['amount'];
            } else {
                $sellVolume += $transaction['amount'];
            }
        }
    }

    $priceImpact = ($buyVolume - $sellVolume) * 0.0001;
    $randomFactor = (mt_rand(-10, 10) / 10000);
    $totalChange = $priceImpact + $randomFactor;

    // Обмеження зміни ціни до ±0.1%
    $totalChange = max(-0.001, min(0.001, $totalChange));

    $close = $open * (1 + $totalChange);
    $high = max($open, $close);
    $low = min($open, $close);

    return [
        'time' => $currentTime,
        'open' => $open,
        'high' => $high,
        'low' => $low,
        'close' => $close,
        'volume' => $volume
    ];
}


try {
    $ratesData = loadDataFromJson('../json/rates.json');
    $transactions = loadDataFromJson('../json/transactions.json');

    $currentTime = time() * 1000;
    $lastCandle = end($ratesData);

    if (empty($ratesData) || ($currentTime - $lastCandle['time']) >= 60000) {
        $newCandle = generateNewCandle($lastCandle ?? ['close' => 100], $transactions);
        $ratesData[] = $newCandle;

        if (count($ratesData) > 60) {
            $ratesData = array_slice($ratesData, -60);
        }

        saveDataToJson('../json/rates.json', $ratesData);
    }

    $chartData = array_map(function($candle) {
        return [
            'x' => $candle['time'],
            'y' => [$candle['open'], $candle['high'], $candle['low'], $candle['close']]
        ];
    }, $ratesData);

    $lastCandle = end($ratesData);
    $response = [
        'chartData' => $chartData,
        'currentRate' => $lastCandle['close'],
        'change1m' => ($lastCandle['close'] - $lastCandle['open']) / $lastCandle['open'] * 100,
        'volume' => $lastCandle['volume']
    ];

    error_log("Response: " . json_encode($response));
    echo json_encode($response);

} catch (Exception $e) {
    logError("Error in get_latest_data.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
}