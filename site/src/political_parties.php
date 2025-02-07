<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Політичні партії 252У</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include 'assets/navbar.php';?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-6">Політичні партії 252У</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Розподіл місць у раді</h3>
                <canvas id="councilChart" width="400" height="200"></canvas>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Інформація про партії</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-medium text-blue-400">Партія Ярих Нациків</h4>
                        <p class="text-gray-300">Місць у раді: 8</p>
                        <p class="text-gray-400">Фокус на Автократію, Диктатуру.</p>
                        <p class="text-gray-300 mt-2">Голова партії: Костянтин (farsh228)</p>
                        <p class="text-gray-400">Депутати: пусто</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-green-400">Ліберал-Демократична Партія 252У</h4>
                        <p class="text-gray-300">Місць у раді: 2</p>
                        <p class="text-gray-400">Зосереджена на Соціальній Справедливості, Свободі Слова та Демократії,.</p>
                        <p class="text-gray-300 mt-2">Голова партії: Юрій (ia_ne_toffex)</p>
                        <p class="text-gray-400">Депутати: Даня (chilen_bobra)</p>
                    </div>
                </div>
            </div>

        </div>

        <section class="mb-12">
            <h3 class="text-2xl font-bold mb-4">Детальна інформація про партії</h3>
            <div class="space-y-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h4 class="text-xl font-semibold mb-2 text-blue-400">Партія Ярих Нациків</h4>
                    <p class="text-gray-300 mb-4">Партія Ярих Нациків є домінуючою силою в раді 252У, займаючи 8 з 10 місць. Їх платформа включає:</p>
                    <ul class="list-disc list-inside text-gray-400 space-y-2">
                        <li>Фокус на встановлення автократичного режиму</li>
                        <li>Прагнення до диктатури як форми правління</li>
                        <li>Централізація влади в руках партійного керівництва</li>
                        <li>Обмеження демократичних свобод на користь "сильної руки"</li>
                    </ul>
                    <div class="mt-4">
                        <p class="text-gray-300">Голова партії: <span class="font-semibold">Костянтин (farsh228)</span></p>
                        <p class="text-gray-400">Депутати: На даний момент інші депутатські місця не заповнені</p>
                    </div>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h4 class="text-xl font-semibold mb-2 text-green-400">Ліберал-Демократична Партія 252У</h4>
                    <p class="text-gray-300 mb-4">Ліберал-Демократична Партія 252У представлена 2 місцями в раді. Їх ключові цілі:</p>
                    <ul class="list-disc list-inside text-gray-400 space-y-2">
                        <li>Забезпечення соціальної справедливості для всіх громадян</li>
                        <li>Захист та просування свободи слова</li>
                        <li>Зміцнення демократичних інститутів та процесів</li>
                        <li>Розвиток громадянського суспільства та підтримка громадських ініціатив</li>
                    </ul>
                    <div class="mt-4">
                        <p class="text-gray-300">Голова партії: <span class="font-semibold">Юрій (ia_ne_toffex)</span></p>
                        <p class="text-gray-400">Депутати: Даня (chilen_bobra)</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'assets/footer.php';?>

    <script>
        const ctx = document.getElementById('councilChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Ліва сторона', 'Майже Ліва сторона', 'Центр', 'Майже Права сторона', 'Права сторона'],
                datasets: [{
                    data: [2, 2, 2, 2, 2],
                    backgroundColor: ['#4ade80', '#3B82F6', '#3B82F6', '#3B82F6', '#3B82F6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                circumference: 180,
                rotation: -90,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 16
                            },
                            color: '#fff'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Розподіл місць у раді',
                        color: '#fff',
                        font: {
                            size: 20
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 20
                    }
                }
            }
        });
    </script>
</body>
</html>