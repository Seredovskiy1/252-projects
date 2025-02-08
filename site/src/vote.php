<?php
session_start();
require_once 'config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Ви повинні увійти в систему, щоб голосувати']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Невірний метод запиту']);
    exit;
}

$party = $_POST['party'] ?? '';

if (!in_array($party, ['blue', 'red'])) {
    echo json_encode(['success' => false, 'message' => 'Невірний вибір партії']);
    exit;
}

try {
    // Перевірка, чи користувач вже голосував
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Ви вже проголосували']);
        exit;
    }

    // Додавання голосу
    $stmt = $pdo->prepare("INSERT INTO votes (user_id, vote) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $party]);

    echo json_encode(['success' => true, 'message' => 'Ваш голос зараховано']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Помилка бази даних: ' . $e->getMessage()]);
}