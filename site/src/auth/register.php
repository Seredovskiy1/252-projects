<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація - 252У</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include "../assets/navbar.php";?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <section id="register" class="mb-16">
            <h2 class="text-4xl font-bold mb-6 text-center">Реєстрація в 252У</h2>
            <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-lg p-8">
                <form action="register_check.php" method="POST">
                    <div class="mb-6 relative">
                        <label class="block text-gray-300 text-sm font-bold mb-2" for="username">
                            <i class="fas fa-user mr-2"></i>Ім'я користувача
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 bg-gray-700 border-gray-600 placeholder-gray-400 text-white pl-10" id="username" type="text" name="username" required placeholder="Ваше ім'я">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                            <i class="fas fa-user text-gray-400"></i>
                        </span>
                    </div>
                    <div class="mb-6 relative">
                        <label class="block text-gray-300 text-sm font-bold mb-2" for="email">
                            <i class="fas fa-envelope mr-2"></i>Електронна пошта
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 bg-gray-700 border-gray-600 placeholder-gray-400 text-white pl-10" id="email" type="email" name="email" required placeholder="your@email.com">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                    </div>
                    <div class="mb-6 relative">
                        <label class="block text-gray-300 text-sm font-bold mb-2" for="password">
                            <i class="fas fa-lock mr-2"></i>Пароль
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 bg-gray-700 border-gray-600 placeholder-gray-400 text-white pl-10" id="password" type="password" name="password" required placeholder="••••••••">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pt-6 cursor-pointer" onclick="togglePassword('password', 'toggleIcon')">
                            <i id="toggleIcon" class="fas fa-eye text-gray-400"></i>
                        </span>
                    </div>
                    <div class="mb-6 relative">
                        <label class="block text-gray-300 text-sm font-bold mb-2" for="confirm_password">
                            <i class="fas fa-lock mr-2"></i>Підтвердження пароля
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 bg-gray-700 border-gray-600 placeholder-gray-400 text-white pl-10" id="confirm_password" type="password" name="confirm_password" required placeholder="••••••••">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pt-6 cursor-pointer" onclick="togglePassword('confirm_password', 'toggleIconConfirm')">
                            <i id="toggleIconConfirm" class="fas fa-eye text-gray-400"></i>
                        </span>
                    </div>
                    <div class="flex items-center mb-6">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-300">
                            Я погоджуюсь з <a href="#" class="text-blue-400 hover:text-blue-300">умовами використання</a>
                        </label>
                    </div>
                    <div class="flex flex-col space-y-4">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105" type="submit">
                            <i class="fas fa-user-plus mr-2"></i>Зареєструватися
                        </button>
                    </div>
                    <?php
                        if (isset($_GET['error'])) {
                            echo '<div class="error text-red-500 text-center mt-4 -mb-4">' . htmlspecialchars($_GET['error']) . '</div>';
                        }
                    ?>
                </form>
            </div>
            <div class="text-center mt-6">
                <p class="text-gray-400">
                    Вже маєте обліковий запис? <a href="login.php" class="text-blue-400 hover:text-blue-300">Увійти</a>
                </p>
            </div>
        </section>
    </main>

    <?php include '../assets/footer.php';?>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>