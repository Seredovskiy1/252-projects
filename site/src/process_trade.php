<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Підключення до бази даних
require_once 'config/db.php';

// Перевірка з'єднання з базою даних
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

if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Некоректна сума операції']);
    exit;
}

// Перевірка та створення файлів JSON, якщо вони не існують
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

// Читаємо дані транзакцій та курсу
$transactions_data = json_decode(file_get_contents($transactions_file), true) ?: [];
$rates_data = json_decode(file_get_contents($rates_file), true) ?: [];

// Отримуємо поточний курс
$current_rate = empty($rates_data) ? 100 : end($rates_data)['close'];

// Отримуємо поточний курс
$current_rate = empty($rates_data) ? 100 : end($rates_data)['close'];

try {
    // Отримання даних користувача з бази даних
    $stmt = $conn->prepare("SELECT balance_usd, balance_y FROM users WHERE id = ? FOR UPDATE");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        throw new Exception('Користувача не знайдено');
    }

    // Розрахунок сум для операції
    if ($trade_type === 'buy') {
        $usd_amount = $amount;
        $yfrank_amount = $usd_amount / $current_rate;
        if ($user['balance_usd'] < $usd_amount) {
            throw new Exception('Недостатньо коштів для покупки');
        }
        $new_usd_balance = $user['balance_usd'] - $usd_amount;
        $new_yfrank_balance = $user['balance_y'] + $yfrank_amount;
    } elseif ($trade_type === 'sell') {
        $yfrank_amount = $amount;
        $usd_amount = $yfrank_amount * $current_rate;
        if ($user['balance_y'] < $yfrank_amount) {
            throw new Exception('Недостатньо Y-франків для продажу');
        }
        $new_usd_balance = $user['balance_usd'] + $usd_amount;
        $new_yfrank_balance = $user['balance_y'] - $yfrank_amount;
    } else {
        throw new Exception('Невірний тип операції');
    }

    // Оновлення балансу користувача в базі даних
    $update_stmt = $conn->prepare("UPDATE users SET balance_usd = ?, balance_y = ?, updated_at = NOW() WHERE id = ?");
    $update_stmt->bind_param("ddi", $new_usd_balance, $new_yfrank_balance, $user_id);
    if (!$update_stmt->execute()) {
        throw new Exception('Помилка при оновленні балансу користувача');
    }
    $update_stmt->close();

    // Додавання транзакції
    $transaction_time = time() * 1000;
    $transactions_data[] = [
        'user_id' => $user_id,
        'trade_type' => $trade_type,
        'usd_amount' => $usd_amount,
        'yfrank_amount' => $yfrank_amount,
        'rate' => $current_rate,
        'timestamp' => $transaction_time
    ];

    // Оновлення курсу та об'єму
    $price_change = ($yfrank_amount / 1000) * 0.001; // 0.1% від суми операції
    $new_rate = $current_rate * (1 + ($trade_type === 'buy' ? $price_change : -$price_change));
    $last_candle = end($rates_data);
    if ($transaction_time - $last_candle['time'] >= 60000) {
        // Створюємо нову свічку
        $rates_data[] = [
            'time' => $transaction_time,
            'open' => $current_rate,
            'high' => max($current_rate, $new_rate),
            'low' => min($current_rate, $new_rate),
            'close' => $new_rate,
            'volume' => $yfrank_amount
        ];
    } else {
        // Оновлюємо поточну свічку
        $last_candle['high'] = max($last_candle['high'], $new_rate);
        $last_candle['low'] = min($last_candle['low'], $new_rate);
        $last_candle['close'] = $new_rate;
        $last_candle['volume'] += $yfrank_amount;
        $rates_data[count($rates_data) - 1] = $last_candle;
    }

    // Зберігаємо оновлені дані в JSON файлах
    file_put_contents($transactions_file, json_encode($transactions_data, JSON_PRETTY_PRINT));
    file_put_contents($rates_file, json_encode($rates_data, JSON_PRETTY_PRINT));

    $_SESSION['trade_message'] = 'Операція успішна';
    $_SESSION['new_balance'] = [
        'usd' => $new_usd_balance,
        'yfrank' => $new_yfrank_balance
    ];
    $_SESSION['new_rate'] = $new_rate;
    $_SESSION['new_volume'] = $yfrank_amount;

    $conn->commit(); // Завершуємо транзакцію

    echo json_encode(['success' => true, 'message' => 'Операція успішна']);
    exit;

} catch (Exception $e) {
    $conn->rollback(); // Відкатуємо транзакцію у випадку помилки
    $error_response = ['success' => false, 'message' => $e->getMessage()];
    error_log("Error in process_trade.php: " . $e->getMessage());
    echo json_encode($error_response);
}