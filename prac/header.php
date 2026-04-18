<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="page-header">
    <div class="container header-inner">
        <div class="logo-area">
            <div class="logo-placeholder" aria-label="Логотип">Л</div>
            <div class="logo-text">Пример<span style="font-weight:400">сайта</span></div>
        </div>
        <nav class="main-nav" aria-label="Главная навигация">
            <ul>
                <li><a href="index.php" id="nav-home">Главная</a></li>
                <li><a href="catalog.php" id="nav-catalog">Каталог</a></li>
                <li><a href="contacts.php" id="nav-contacts">Контакты</a></li>

                <?php if(isset($_SESSION['user'])): ?>
                    <!-- Пользователь авторизован -->
                    <li>
                        <a href="profile.php" id="nav-profile">
                            <?php echo htmlspecialchars($_SESSION['user']); ?>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" id="nav-logout">Выйти</a>
                    </li>
                <?php else: ?>
                    <!-- Пользователь не авторизован -->
                    <li><a href="login.php" id="nav-login">Авторизация</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>