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
    <title>Вхід - 252У</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include "../assets/navbar.php";?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <section id="login" class="mb-16 mt-24">
            <h2 class="text-4xl font-bold mb-6 text-center">Вхід до системи</h2>
            <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-lg p-8">
                <form action="login_check.php" method="POST">
                    <div class="mb-6 relative">
                        <label class="block text-gray-300 text-sm font-bold mb-2" for="email">
                            <i class="fas fa-envelope mr-2"></i>Електронна пошта
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 bg-gray-700 border-gray-600 placeholder-gray-400 text-white pl-10" id="email" type="email" name="email" required placeholder="your@email.com">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                            <i class="fas fa-user text-gray-400"></i>
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
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pt-6 cursor-pointer" onclick="togglePassword()">
                            <i id="toggleIcon" class="fas fa-eye text-gray-400"></i>
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-300">
                                Запам'ятати мене
                            </label>
                        </div>
                        <a class="inline-block align-baseline font-bold text-sm text-blue-400 hover:text-blue-300 transition-colors duration-300" href="#">
                            Забули пароль?
                        </a>
                    </div>
                    <div class="flex flex-col space-y-4">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105" type="submit">
                            <i class="fas fa-sign-in-alt mr-2"></i>Увійти
                        </button>
                        <?php
                        if (isset($_GET['error'])) {
                            echo '<div class="error text-red-500 text-center mt-4 -mb-4">' . htmlspecialchars($_GET['error']) . '</div>';
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div class="text-center mt-6">
                <p class="text-gray-400">
                    Немає облікового запису? <a href="register.php" class="text-blue-400 hover:text-blue-300">Зареєструватися</a>
                </p>
            </div>
        </section>
    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
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

    <?php include '../assets/footer.php';?>
</body>
</html>