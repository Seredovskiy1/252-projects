<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Перевірка, чи всі поля заповнені
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=" . urlencode("Всі поля повинні бути заповнені"));
        exit();
    }

    // Перевірка мінімальної довжини пароля
    if (strlen($password) < 6) {
        header("Location: register.php?error=" . urlencode("Пароль повинен містити мінімум 6 символів"));
        exit();
    }

    // Перевірка, чи паролі співпадають
    if ($password !== $confirm_password) {
        header("Location: register.php?error=" . urlencode("Паролі не співпадають"));
        exit();
    }

    // Перевірка, чи користувач з таким ім'ям або email вже існує
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        header("Location: register.php?error=" . urlencode("Користувач з таким ім'ям або email вже існує"));
        exit();
    }

    // Хешування пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Підготовка та виконання SQL-запиту для вставки нового користувача
    $sql = "INSERT INTO users (username, password, email, balance_usd, balance_y, created_at, updated_at) 
            VALUES (?, ?, ?, 100, 50000, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$username, $hashed_password, $email])) {
        header("Location: login.php?success=" . urlencode("Реєстрація успішна! Тепер ви можете увійти."));
        exit();
    } else {
        header("Location: register.php?error=" . urlencode("Виникла помилка при реєстрації. Спробуйте ще раз."));
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>