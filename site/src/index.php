<?php
session_start();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>є252У - Наша Спільнота</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include "assets/navbar.php";?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <section id="home" class="mb-16">
            <h2 class="text-4xl font-bold mb-6">Ласкаво просимо до є252У</h2>
            <p class="text-gray-300 mb-8 text-lg">є252У - це передова технологічна розробка Народної Республіки Хотина (ХНР), яка об'єднує всі аспекти нашої цифрової держави в одному інтерфейсі. Тут ви знайдете все необхідне для життя та розвитку в нашій інноваційній країні.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <i class="fas fa-globe text-3xl text-yellow-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Єдина Платформа</h3>
                    <p class="text-gray-400">Всі державні послуги, економічні інструменти та соціальні взаємодії в одному місці.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <i class="fas fa-shield-alt text-3xl text-blue-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Безпека</h3>
                    <p class="text-gray-400">Найсучасніші технології захисту даних та особистої інформації громадян ХНР.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <i class="fas fa-rocket text-3xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Інновації</h3>
                    <p class="text-gray-400">Постійний розвиток та впровадження нових технологій для покращення життя громадян.</p>
                </div>
            </div>
        </section>

        <section id="about" class="mb-16">
            <h2 class="text-3xl font-bold mb-6">Про є252У</h2>
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
                <p class="text-gray-300 mb-4">є252У - це унікальна розробка ХНР, яка об'єднує всі аспекти життя нашої цифрової держави. Це не просто сайт, а повноцінна екосистема для громадян, бізнесу та державних структур.</p>
                <p class="text-gray-300 mb-4">Тут ви можете брати участь у виборах, керувати своїми фінансами, взаємодіяти з державними органами, слідкувати за новинами та розвивати свій бізнес - все в одному місці.</p>
                <p class="text-gray-300">Наша мета - створити найбільш ефективну та зручну систему управління державою, де кожен громадянин має голос та можливості для розвитку.</p>
            </div>
        </section>

        <section id="features" class="mb-16">
            <h2 class="text-3xl font-bold mb-6">Основні Функції</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">Політична Система</h3>
                    <p class="text-gray-400 mb-4">Участь у виборах, створення партій та голосування за важливі рішення.</p>
                    <a href="political_parties.php" class="text-blue-400 hover:text-blue-300">Дізнатися більше <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">Економіка та Фінанси</h3>
                    <p class="text-gray-400 mb-4">Управління криптовалютою Y-франк, торгівля та інвестиції.</p>
                    <a href="currency.php" class="text-blue-400 hover:text-blue-300">Дізнатися більше <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">Новини та Медіа</h3>
                    <p class="text-gray-400 mb-4">Актуальна інформація про події в ХНР та світі.</p>
                    <a href="news.php" class="text-blue-400 hover:text-blue-300">Дізнатися більше <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">Особистий Кабінет</h3>
                    <p class="text-gray-400 mb-4">Управління особистими даними та доступ до всіх сервісів є252У.</p>
                    <a href="dashboard.php" class="text-blue-400 hover:text-blue-300">Дізнатися більше <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'assets/footer.php';?>
</body>
</html>