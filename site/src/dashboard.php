<?php
session_start();

// Перевірка авторизації
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Підключення до бази даних
require_once 'config/db.php';

// Отримання даних користувача з бази даних
$stmt = $pdo->prepare("SELECT username, email, balance_usd, balance_y, updated_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Якщо користувача не знайдено, перенаправляємо на сторінку входу
    header('Location: auth/login.php');
    exit();
}

$rates_json = file_get_contents('json/rates.json');
$rates = json_decode($rates_json, true);
$latest_rate = end($rates);
$exchange_rate = $latest_rate['close'];



$can_claim_reward = true;

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Особистий кабінет - 252У</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include 'assets/navbar.php';?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Особистий кабінет</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Вітаємо, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                <a href="logout.php" class="text-blue-400 hover:text-blue-300">Вийти з аккаунту</a>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Ваш баланс</h2>
                <p class="text-xl mb-2">USD: <span class="font-bold text-green-400">$<?php echo number_format($user['balance_usd'], 2); ?></span></p>
                <p class="text-xl mb-4">Y-франк: <span class="font-bold text-yellow-400"><?php echo number_format($user['balance_y'], 2); ?> Y</span></p>
                <p class="text-gray-400">Курс обміну: 1 USD = <?php echo number_format($exchange_rate, 2); ?> Y-франк</p>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Щоденна винагорода</h2>
                <form action="claim_reward.php" method="POST">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105 shadow-lg">
                        <i class="fas fa-gift mr-2"></i> Отримати винагороду
                    </button>
                </form>
                <?php
                if (isset($_SESSION['reward_message'])) {
                    echo '<p class="mt-2 text-sm ' . ($_SESSION['reward_success'] ? 'text-green-500' : 'text-red-500') . '">' . $_SESSION['reward_message'] . '</p>';
                    unset($_SESSION['reward_message']);
                    unset($_SESSION['reward_success']);
                }
                ?>
            </div>
        </div>
    </main>

    <?php include 'assets/footer.php';?>
    
</body>
</html>