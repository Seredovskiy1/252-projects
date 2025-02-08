<?php
session_start();
require_once 'config/db.php'; // Додаємо підключення до бази даних

// Перевірка авторизації користувача
$isAuthorized = isset($_SESSION['user_id']);

// Отримання балансу користувача, якщо він авторизований
$userBalance = null;
if ($isAuthorized) {
    $stmt = $pdo->prepare("SELECT balance_usd, balance_y FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userBalance = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Курс Y-франка</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <?php include 'assets/navbar.php';?>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Курс Y-франка (Y)</h1>

        <?php if ($isAuthorized && $userBalance): ?>
        <div class="bg-gray-800 rounded-lg p-6 mb-8 shadow-lg">
            <h2 class="text-2xl font-bold mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Ваш баланс
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-700 p-4 rounded-lg shadow">
                    <p class="text-xl flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        USD: <span class="font-bold text-green-500 ml-2">$<?php echo number_format($userBalance['balance_usd'], 2); ?></span>
                    </p>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg shadow">
                    <p class="text-xl flex items-center">
                        <span class="text-yellow-500 text-2xl font-bold mr-2">Y</span>
                        франк: <span class="font-bold text-yellow-500 ml-2"><?php echo number_format($userBalance['balance_y'], 2); ?> Y</span>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>


        <div class="bg-gray-800 rounded-lg p-4 mb-8">
            <div id="chart" class="w-full h-96"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-800 rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">Поточний курс</h2>
                <p class="text-2xl">$1 - <span class="text-3xl font-bold text-green-500" id="currentRate">Завантаження...</span></p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">Зміна за хвилину</h2>
                <p class="text-3xl font-bold" id="change1m">Завантаження...</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">Об'єм торгів за свічку</h2>
                <p class="text-3xl font-bold" id="volume">Завантаження...</p>
            </div>
        </div>

        <?php
        // Додайте цей код перед формою торгівлі
        if (isset($_SESSION['trade_error'])) {
            echo '<div class="bg-red-500 text-white p-4 rounded-lg mt-4">';
            echo htmlspecialchars($_SESSION['trade_error']);
            echo '</div>';
            unset($_SESSION['trade_error']);
        }
        ?>

        <?php if ($isAuthorized): ?>
            <div class="mt-8 bg-gray-800 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Торгівля Y-франком</h2>
                <form action="process_trade.php" id="tradeForm" class="space-y-4" method="POST">
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="tradeType" class="block text-sm font-medium text-gray-400 mb-1">Операція</label>
                            <select id="tradeType" name="tradeType" class="w-full px-3 py-2 text-sm rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="buy">Купити Y-франк за $</option>
                                <option value="sell">Продати Y-франк за $</option>
                            </select>
                        </div>
                    </div>
                    <div id="amountSection">
                        <label for="amount" class="block text-sm font-medium text-gray-400 mb-1">Сума для обміну</label>
                        <div class="relative">
                            <input type="number" id="amount" name="amount" min="0" step="0.01" class="w-full px-3 py-2 text-sm rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Введіть суму">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 text-sm" id="amountCurrency">$</span>
                        </div>
                    </div>
                    <div>
                        <label for="resultAmount" class="block text-sm font-medium text-gray-400 mb-1">Сума до отримання</label>
                        <div class="relative">
                            <input type="number" id="resultAmount" name="resultAmount" class="w-full px-3 py-2 text-sm rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Результат операції" readonly>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 text-sm" id="resultCurrency">Y</span>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Підтвердити операцію
                        </button>
                    </div>
                    
                </form>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'assets/footer.php';?>

    <script>
        var isAuthorized = <?php echo $isAuthorized ? 'true' : 'false'; ?>;
    </script>
    <script src="js/currency.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const tradeType = document.getElementById('tradeType');
        const amount = document.getElementById('amount');
        const amountCurrency = document.getElementById('amountCurrency');
        const resultAmount = document.getElementById('resultAmount');
        const resultCurrency = document.getElementById('resultCurrency');

        function updateCurrencies() {
            if (tradeType.value === 'buy') {
                amountCurrency.textContent = '$';
                resultCurrency.textContent = 'Y';
            } else {
                amountCurrency.textContent = 'Y';
                resultCurrency.textContent = '$';
            }
        }

        function calculateResult() {
            const rate = parseFloat(document.getElementById('currentRate').textContent);
            const inputAmount = parseFloat(amount.value);
            if (!isNaN(inputAmount) && !isNaN(rate)) {
                if (tradeType.value === 'buy') {
                    resultAmount.value = (inputAmount * rate).toFixed(2);
                } else {
                    resultAmount.value = (inputAmount / rate).toFixed(2);
                }
            } else {
                resultAmount.value = '';
            }
        }

        tradeType.addEventListener('change', function() {
            updateCurrencies();
            calculateResult();
        });

        amount.addEventListener('input', calculateResult);

        // Initial setup
        updateCurrencies();
    });
    </script>
</body>
</html>