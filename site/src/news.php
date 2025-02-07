<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новини 252У</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="styles/news.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .article-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .article-body {
            flex-grow: 1;
        }
        .details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .details.expanded {
            max-height: 1000px; /* Adjust this value as needed */
            transition: max-height 0.5s ease-in;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include 'assets/navbar.php';?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Новини 252У</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Новина 1 -->
            <article class="bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col">
                <img src="https://assets-global.website-files.com/6257adef93867e50d84d30e2/636e0a6a49cf127bf92de1e2_icon_clyde_blurple_RGB.png" alt="Зображення новини" class="w-full h-48 object-cover">
                <div class="p-6 flex-grow flex flex-col">
                    <div class="article-content">
                        <div class="article-body">
                            <h2 class="text-xl font-semibold mb-2">Партія Ярих Нациків видалила верифікацію на Discord сервері</h2>
                            <p class="text-gray-400 mb-4">Несподіване рішення викликало хвилю обговорень серед користувачів...</p>
                            <p class="text-sm text-gray-500 mb-4">Опубліковано: Сьогодні</p>
                            <div class="details">
                                <p class="text-gray-300 mb-4">
                                    Сьогодні Партія Ярих Нациків прийняла несподіване рішення про видалення системи верифікації на офіційному Discord сервері країни. 
                                    Це рішення викликало бурхливу реакцію серед користувачів та членів партії ЛДПУ. Лідер партії, Костянтин (farsh228), пояснив це рішення 
                                    "необхідністю спростити доступ до інформації та збільшити відкритість партії".
                                </p>
                                <p class="text-gray-300 mb-4">
                                    Критики цього кроку висловлюють занепокоєння щодо можливого збільшення кількості тролів та провокаторів на сервері. 
                                    Прихильники ж вважають, що це допоможе залучити нових членів та підвищити активність на сервері. 
                                    Експерти з кібербезпеки рекомендують партії впровадити альтернативні методи модерації для підтримки порядку на сервері.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>


            <!-- Додайте інші новини за потреби -->

        </div>

        <div class="text-center mt-8">
            <button onclick="toggleAllDetails()" id="toggleButton" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                Розгорнути всі новини
            </button>
        </div>
    </main>

    <?php include 'assets/footer.php';?>

    <script>
        function toggleAllDetails() {
            const details = document.querySelectorAll('.details');
            const button = document.getElementById('toggleButton');
            const isExpanded = button.textContent === 'Згорнути всі новини';

            details.forEach(detail => {
                if (isExpanded) {
                    detail.classList.remove('expanded');
                } else {
                    detail.classList.add('expanded');
                }
            });

            button.textContent = isExpanded ? 'Розгорнути всі новини' : 'Згорнути всі новини';
        }
    </script>
</body>
</html>