<header class="py-6 px-4 bg-gray-800 sticky top-0 z-50">
        <nav class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">252У</h1>
            <div class="space-x-4">
                <a href="../index.php" class="hover:text-gray-300">Головна</a>
                <a href="../political_parties.php" class="hover:text-gray-300">Партія</a>
                <a href="../elections.php" class="hover:text-gray-300">Вибори</a>
                <a href="../news.php" class="hover:text-gray-300">Новини</a>
                <a href="../currency.php" class="hover:text-gray-300">Курс</a>
                <?php
                
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="../dashboard.php" class="hover:text-gray-300">Панель керування</a>';
                    echo '<a href="../auth/logout.php" class="hover:text-gray-300">Вихід</a>';
                } else {
                    echo '<a href="../auth/login.php" class="hover:text-gray-300">Вхід</a>';
                }
                ?>
            </div>
        </nav>
    </header>
