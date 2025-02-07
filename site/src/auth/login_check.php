<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['email']);
    $password = $_POST['password'];

    // Перевірка, чи всі поля заповнені
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Всі поля повинні бути заповнені"));
        exit();
    }

    // Підготовка SQL-запиту для пошуку користувача
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Перевірка, чи користувач існує і пароль правильний
    if ($user && password_verify($password, $user['password'])) {
        // Авторизація успішна
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['email'];
        

        header("Location: ../index.php"); // Перенаправлення на головну сторінку після успішного входу
        exit();
    } else {
        // Авторизація не вдалася
        header("Location: login.php?error=" . urlencode("Невірне ім'я користувача або пароль"));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>