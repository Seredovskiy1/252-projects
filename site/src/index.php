<?php
session_start();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>252У - Наша Спільнота</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include "assets/navbar.php";?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <section id="home" class="mb-16">
            <h2 class="text-4xl font-bold mb-6">Ласкаво просимо до 252У</h2>
            <p class="text-gray-300 mb-8 text-lg">252У - це інноваційна спільнота, яка об'єднує талановитих людей для створення майбутнього. Ми прагнемо до розвитку технологій, освіти та культури.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <i class="fas fa-lightbulb text-3xl text-yellow-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Інновації</h3>
                    <p class="text-gray-400">Ми постійно шукаємо нові ідеї та рішення для покращення нашого суспільства.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <i class="fas fa-users text-3xl text-blue-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Спільнота</h3>
                    <p class="text-gray-400">Наша сила в єдності. Ми підтримуємо один одного та разом досягаємо більшого.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <i class="fas fa-rocket text-3xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Розвиток</h3>
                    <p class="text-gray-400">Ми віримо в постійне навчання та особистісний ріст кожного члена нашої спільноти.</p>
                </div>
            </div>
        </section>

        <section id="about" class="mb-16">
            <h2 class="text-3xl font-bold mb-6">Про 252У</h2>
            <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
                <p class="text-gray-300 mb-4">252У - це не просто номер, це символ нашої унікальності та прагнення до досконалості. Наша спільнота заснована на принципах взаємоповаги, інновацій та постійного розвитку.</p>
                <p class="text-gray-300 mb-4">Ми об'єднуємо фахівців з різних галузей: програмістів, дизайнерів, інженерів, науковців та митців. Разом ми створюємо проекти, які змінюють світ на краще.</p>
                <p class="text-gray-300">Наша місія - розкрити потенціал кожного учасника та створити середовище, де ідеї перетворюються на реальність.</p>
            </div>
        </section>

        <section id="projects" class="mb-16">
            <h2 class="text-3xl font-bold mb-6">Наші Проекти</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">252У - Network</h3>
                    <p class="text-gray-400 mb-4">Віртуальні Сервери за бесплатно для ваших потреб!</p>
                    <a href="#" class="text-blue-400 hover:text-blue-300">Дізнатися більше <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'assets/footer.php';?>
</body>
</html>