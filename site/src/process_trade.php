<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Помилка підключення до бази даних']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Користувач не авторизований']);
    exit;
}

$user_id = $_SESSION['user_id'];
$trade_type = $_POST['tradeType'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);
$resultAmount = floatval($_POST['resultAmount'] ?? 0);

if ($amount <= 0 || $resultAmount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Некоректна сума операції']);
    exit;
}

$transactions_file = 'json/transactions.json';
$rates_file = 'json/rates.json';

if (!file_exists($transactions_file)) {
    file_put_contents($transactions_file, json_encode([]));
}

if (!file_exists($rates_file)) {
    file_put_contents($rates_file, json_encode([
        ['time' => time() * 1000, 'open' => 100, 'high' => 100, 'low' => 100, 'close' => 100, 'volume' => 0]
    ]));
}

$transactions_data = json_decode(file_get_contents($transactions_file), true) ?: [];
$rates_data = json_decode(file_get_contents($rates_file), true) ?: [];
$current_rate = empty($rates_data) ? 100 : end($rates_data)['close'];

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("SELECT balance_usd, balance_y FROM users WHERE id = ? FOR UPDATE");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        throw new Exception('Користувача не знайдено');
    }

    $usd_balance = (string) $user['balance_usd'];
    $y_balance = (string) $user['balance_y'];

    if ($trade_type === 'buy') {
        $usd_amount = (string) $amount;
        $yfrank_amount = (string) $resultAmount;
        
        // Перевірка на дуже великі числа
        if (strpos($usd_amount, 'E') !== false || $usd_amount > $usd_balance) {
            throw new Exception('Недостатньо коштів для покупки');
        }
        
        if (bccomp($usd_balance, $usd_amount, 8) < 0) {
            throw new Exception('Недостатньо коштів для покупки');
        }
        
        $new_usd_balance = bcsub($usd_balance, $usd_amount, 8);
        $new_yfrank_balance = bcadd($y_balance, $yfrank_amount, 8);
    } elseif ($trade_type === 'sell') {
        $yfrank_amount = (string) $amount;
        $usd_amount = (string) $resultAmount;
        
        // Перевірка на дуже великі числа
        if (strpos($yfrank_amount, 'E') !== false || $yfrank_amount > $y_balance) {
            throw new Exception('Недостатньо Y-франків для продажу');
        }
        
        if (bccomp($y_balance, $yfrank_amount, 8) < 0) {
            throw new Exception('Недостатньо Y-франків для продажу');
        }
        
        $new_usd_balance = bcadd($usd_balance, $usd_amount, 8);
        $new_yfrank_balance = bcsub($y_balance, $yfrank_amount, 8);
    } else {
        throw new Exception('Невірний тип операції');
    }
    
    if (bccomp($new_usd_balance, '0', 8) < 0 || bccomp($new_yfrank_balance, '0', 8) < 0) {
        throw new Exception('Операція призведе до від\'ємного балансу');
    }

    $update_stmt = $conn->prepare("UPDATE users SET balance_usd = ?, balance_y = ?, updated_at = NOW() WHERE id = ?");
    $update_stmt->bind_param("ddi", $new_usd_balance, $new_yfrank_balance, $user_id);
    if (!$update_stmt->execute()) {
        throw new Exception('Помилка при оновленні балансу користувача');
    }
    $update_stmt->close();

    $transaction_time = time() * 1000;
    $transactions_data[] = [
        'user_id' => $user_id,
        'trade_type' => $trade_type,
        'usd_amount' => $usd_amount,
        'yfrank_amount' => $yfrank_amount,
        'rate' => $current_rate,
        'timestamp' => $transaction_time
    ];

    $price_change = ($yfrank_amount / 1000) * 0.001;
    $new_rate = $current_rate * (1 + ($trade_type === 'buy' ? $price_change : -$price_change));
    $last_candle = end($rates_data);

    if ($transaction_time - $last_candle['time'] >= 60000) {
        $rates_data[] = [
            'time' => $transaction_time,
            'open' => $current_rate,
            'high' => max($current_rate, $new_rate),
            'low' => min($current_rate, $new_rate),
            'close' => $new_rate,
            'volume' => $yfrank_amount
        ];
    } else {
        $last_candle['high'] = max($last_candle['high'], $new_rate);
        $last_candle['low'] = min($last_candle['low'], $new_rate);
        $last_candle['close'] = $new_rate;
        $last_candle['volume'] += $yfrank_amount;
        $rates_data[count($rates_data) - 1] = $last_candle;
    }

    file_put_contents($transactions_file, json_encode($transactions_data, JSON_PRETTY_PRINT));
    file_put_contents($rates_file, json_encode($rates_data, JSON_PRETTY_PRINT));

    $conn->commit();

    header('Location: currency.php');
    exit;
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error in process_trade.php: " . $e->getMessage());
    $_SESSION['trade_error'] = $e->getMessage();
    header('Location: currency.php');
    exit;
}
