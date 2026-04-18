<?php
session_start();

// Подключение к базе данных
$host = 'localhost';
$db   = 'demo';
$user = 'root'; // поменяй при необходимости
$pass = '';     // пароль MySQL
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

$registerMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    if (!$username || !$email || !$password || !$password_confirm) {
        $registerMessage = "Пожалуйста, заполните все поля!";
    } elseif ($password !== $password_confirm) {
        $registerMessage = "Пароли не совпадают!";
    } else {
        // Проверка существующего email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $registerMessage = "Пользователь с таким email уже существует!";
        } else {
            // Для демо: plain text пароль
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
            $registerMessage = "✅ Регистрация прошла успешно! Теперь можете <a href='login.php' style='color:#2563eb;'>войти</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Регистрация | Демо-проект</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="page-main">
        <div class="container">
            <section class="form-section" aria-label="Регистрация">
                <h2 style="margin-bottom: 1rem;">Регистрация нового пользователя</h2>
                <p style="margin-bottom: 1.2rem; color: #334155;">Заполните форму для создания аккаунта.</p>

                <?php if ($registerMessage): ?>
                    <div class="info-note"><?php echo $registerMessage; ?></div>
                <?php endif; ?>

                <form id="registerForm" action="" method="post">
                    <div class="form-group">
                        <label for="username">Имя *</label>
                        <input type="text" id="username" name="username" placeholder="Иван Петров" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="example@mail.ru" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль *</label>
                        <input type="password" id="password" name="password" placeholder="Введите пароль" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Подтвердите пароль *</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Повторите пароль" required>
                    </div>
                    <button type="submit" class="btn">Зарегистрироваться</button>

                    <div class="info-note">
                        Уже есть аккаунт? <a href="login.php" style="color:#2563eb;">Войти</a>.
                    </div>
                </form>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>