<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Перевірка часу останньої винагороди
$stmt = $pdo->prepare("SELECT last_reward_claim FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$current_time = time();
$last_claim = strtotime($result['last_reward_claim']);

if ($last_claim && ($current_time - $last_claim) < 1800) { // 1800 секунд = 30 хвилин
    $_SESSION['reward_message'] = "Ви вже отримали винагороду. Спробуйте пізніше.";
    $_SESSION['reward_success'] = false;
    header('Location: dashboard.php');
    exit();
}

// Оновлення балансу та часу останньої винагороди
$reward_amount = 5; // Наприклад, 10 Y-франків
$stmt = $pdo->prepare("UPDATE users SET balance_y = balance_y + ?, last_reward_claim = NOW() WHERE id = ?");
$stmt->execute([$reward_amount, $user_id]);

if ($stmt->rowCount() > 0) {
    $_SESSION['reward_message'] = "Ви успішно отримали {$reward_amount} Y-франк!";
    $_SESSION['reward_success'] = true;
} else {
    $_SESSION['reward_message'] = "Виникла помилка при отриманні винагороди. Спробуйте пізніше.";
    $_SESSION['reward_success'] = false;
}

header('Location: dashboard.php');
exit();