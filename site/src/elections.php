<?php
session_start();
require_once 'config/db.php';

$isAuthorized = isset($_SESSION['user_id']);
$hasVoted = false;
$voteResults = [
    'blue' => 0,
    'red' => 0
];

// Отримання результатів голосування (перенесено за межі перевірки авторизації)
$stmt = $pdo->query("SELECT vote, COUNT(*) as count FROM votes GROUP BY vote");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $voteResults[$row['vote']] = $row['count'];
}

if ($isAuthorized) {
    // Перевірка, чи користувач вже голосував
    $stmt = $pdo->prepare("SELECT vote FROM votes WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $vote = $stmt->fetch(PDO::FETCH_ASSOC);
    $hasVoted = $vote !== false;
}

$totalVotes = array_sum($voteResults);
$bluePercentage = $totalVotes > 0 ? ($voteResults['blue'] / $totalVotes) * 100 : 0;
$redPercentage = $totalVotes > 0 ? ($voteResults['red'] / $totalVotes) * 100 : 0;
?>

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
            <div id="votingSection" class="hidden">
                <?php if ($isAuthorized): ?>
                    <?php if (!$hasVoted): ?>
                        <h3 class="text-xl font-semibold mb-4">Проголосуйте за свого кандидата:</h3>
                        <div class="flex space-x-4">
                            <button onclick="vote('blue')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                Голосувати за Ліберал-Демократичну Партію
                            </button>
                            <button onclick="vote('red')" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Голосувати за Партію Ярих Нациків
                            </button>
                        </div>
                    <?php else: ?>
                        <p class="text-xl font-semibold mb-4">Ви вже проголосували. Дякуємо за участь!</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-xl font-semibold mb-4">Увійдіть в систему, щоб проголосувати.</p>
                <?php endif; ?>
            </div>
            <div id="results" class="mt-8">
                <h3 class="text-xl font-semibold mb-4">Результати голосування:</h3>
                <div class="flex w-full h-8 bg-gray-700 rounded-full overflow-hidden">
                    <div id="blueProgress" class="bg-green-400 h-full" style="width: <?php echo $bluePercentage; ?>%"></div>
                    <div id="redProgress" class="bg-blue-500 h-full" style="width: <?php echo $redPercentage; ?>%"></div>
                </div>
                <div class="flex justify-between mt-2">
                    <p class="text-green-400">Ліберал-Демократична Партія: <span id="bluePercentage"><?php echo number_format($bluePercentage, 2); ?>%</span></p>
                    <p class="text-blue-400">Партія Ярих Нациків: <span id="redPercentage"><?php echo number_format($redPercentage, 2); ?>%</span></p>
                </div>
            </div>
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
        const electionDate = new Date("2025-02-09T20:30:00").getTime();
        const countdownElement = document.getElementById("countdown");
        const votingSection = document.getElementById("votingSection");
        const resultsSection = document.getElementById("results");
    
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = electionDate - now;
    
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
            countdownElement.innerHTML = days + "д " + hours + "г "
            + minutes + "х " + seconds + "с ";
    
            if (distance < 0) {
                clearInterval(x);
                countdownElement.innerHTML = "Вибори розпочалися!";
                votingSection.classList.remove("hidden");
            }
        }
    
        const x = setInterval(updateCountdown, 1000);
        updateCountdown();
    
        function vote(party) {
            fetch('vote.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `party=${party}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Дякуємо за ваш голос!');
                    location.reload();
                } else {
                    alert('Помилка: ' + data.message);
                }
            });
        }
    </script>
</body>
</html>