<?php
session_start();

// Подключение к базе данных
$host = 'localhost';
$db   = 'demo';
$user = 'root';  // поменяй при необходимости
$pass = '';      // пароль MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Ошибка подключения к базе: " . $e->getMessage());
}

$loginMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $loginMessage = "Пожалуйста, заполните все поля!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Для демо: plain text пароль
            if ($password === ($user['password'] ?? '')) {
                $_SESSION['user'] = $user['username'];
                $loginMessage = "✅ Добро пожаловать, " . htmlspecialchars($user['username']) . "!";
            } else {
                $loginMessage = "❌ Неверный пароль!";
            }
        } else {
            $loginMessage = "❌ Пользователь с таким email не найден!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Авторизация | Демо-проект</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="page-main">
        <div class="container">
            <section class="form-section" aria-label="Авторизация">
                <h2 style="margin-bottom: 1rem;">Вход на сайт</h2>
                <p style="margin-bottom: 1.2rem; color: #334155;">Введите свой email и пароль для доступа к личному кабинету.</p>

                <?php if ($loginMessage): ?>
                    <div class="info-note"><?php echo $loginMessage; ?></div>
                <?php endif; ?>

                <form id="loginForm" action="" method="post">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="example@mail.ru" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль *</label>
                        <input type="password" id="password" name="password" placeholder="Введите пароль" required>
                    </div>
                    <button type="submit" class="btn">Войти</button>
                    <div class="info-note">
                        Если у вас нет аккаунта, <a href="register.php" style="color:#2563eb;">зарегистрируйтесь</a>.
                    </div>
                </form>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>