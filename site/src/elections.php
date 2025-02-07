<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вибори 252У</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include 'assets/navbar.php';?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Вибори 252У</h1>

        <section class="mb-12 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Інформація про вибори</h2>
            <p class="text-xl mb-2">Дата виборів: <span class="font-bold text-yellow-400">9 лютого 2025 року</span></p>
            <p class="text-gray-300">До виборів залишилось:</p>
            <div id="countdown" class="text-3xl font-bold text-green-400 my-4"></div>
            <p class="text-gray-300">Не забудьте взяти участь у голосуванні та зробити свій вибір!</p>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Партії-учасники виборів</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-2 text-blue-400">Партія Ярих Нациків</h3>
                    <p class="text-gray-300 mb-2">Гасло: "Нацисти - понад усе!"</p>
                    <p class="text-gray-400 mb-4">Фокус на автократію та централізацію влади.</p>
                    <p class="text-gray-300">Лідер: Костянтин (farsh228)</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-2 text-green-400">Ліберал-Демократична Партія 252У</h3>
                    <p class="text-gray-300 mb-2">Гасло: "Свобода, рівність, справедливість!"</p>
                    <p class="text-gray-400 mb-4">Зосереджена на соціальній справедливості та демократії.</p>
                    <p class="text-gray-300">Лідер: Юрій (ia_ne_toffex)</p>
                </div>
            </div>
        </section>

        <section class="mb-12 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Важливість вашого голосу</h2>
            <p class="text-gray-300 mb-4">Ваш голос має значення! Вибори - це можливість вплинути на майбутнє 252У. Кожен голос важливий для формування справедливого та демократичного суспільства.</p>
            <p class="text-gray-300">Не забудьте ознайомитися з програмами партій та зробити свідомий вибір.</p>
        </section>
    </main>

    <?php include 'assets/footer.php';?>

    <script>
        // Countdown timer
        function updateCountdown() {
            const electionDate = new Date("2025-02-09T20:30:00").getTime();
            const now = new Date().getTime();
            const timeLeft = electionDate - now;

            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = `${days} днів ${hours} годин ${minutes} хвилин ${seconds} секунд`;
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</body>
</html>