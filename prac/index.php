<?php
session_start();

// Подключение к базе
$host = 'localhost';
$db   = 'demo';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Получаем все курсы
$stmt = $pdo->query("SELECT * FROM courses ORDER BY id ASC");
$courses = $stmt->fetchAll();

// Обработка формы обратной связи
$formError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'])) {
    if (!isset($_SESSION['user'])) {
        $formError = "❌ Вы должны войти, чтобы отправить сообщение.";
    } else {
        $name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);

        if ($name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmtUser = $pdo->prepare("UPDATE users SET message = ? WHERE username = ?");
            $stmtUser->execute([$message, $_SESSION['user']]);
            // Перенаправляем в профиль после успешной отправки
            header("Location: profile.php");
            exit;
        } else {
            $formError = "❌ Пожалуйста, корректно заполните имя и email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="description" content="Демо-проект с курсами и формой обратной связи">
<title>Главная | Демо-проект</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

<main class="page-main">
<div class="container">
    <!-- Hero блок -->
    <section class="hero" aria-label="Вступление">
        <h1>Правильная структура веб-сайта</h1>
        <p>Семантическая вёрстка, адаптивный дизайн. Все элементы — наглядные примеры для обучения.</p>
    </section>

    <!-- Курсы -->
    <h2 class="section-title">Популярные курсы / товары</h2>
    <div class="cards-grid" id="productsGrid">
        <?php foreach ($courses as $course): ?>
            <a href="course.php?id=<?php echo $course['id']; ?>" class="demo-card-link" style="text-decoration: none; color: inherit;">
                <article class="demo-card">
                    <div class="image-placeholder">
                        <?php 
                        echo $course['image'] 
                            ? "<img src='" . htmlspecialchars($course['image']) . "' alt='" . htmlspecialchars($course['title']) . "'>" 
                            : "(Изображение)"; 
                        ?>
                    </div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p><?php echo htmlspecialchars($course['description']); ?></p>
                        <?php if(!empty($course['duration'])): ?>
                            <span class="badge"><?php echo htmlspecialchars($course['duration']); ?></span>
                        <?php endif; ?>
                    </div>
                </article>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Форма обратной связи -->
    <section class="form-section" id="feedbackFormBlock">
        <h2 style="margin-bottom: 1rem;">Форма обратной связи</h2>
        <?php if($formError): ?>
            <div class="info-note" style="border-left: 4px solid #b91c1c; background:#fde2e2;">
                <?php echo htmlspecialchars($formError); ?>
            </div>
        <?php endif; ?>
        <form id="demoForm" action="" method="post">
            <div class="form-group">
                <label for="userName">Имя *</label>
                <input type="text" id="userName" name="username" placeholder="Иван Петров" required>
            </div>
            <div class="form-group">
                <label for="userEmail">Email *</label>
                <input type="email" id="userEmail" name="email" placeholder="example@mail.ru" required>
            </div>
            <div class="form-group">
                <label for="message">Сообщение / вопрос</label>
                <textarea id="message" name="message" rows="3" placeholder="Ваш отзыв или пожелание..."></textarea>
            </div>
            <button type="submit" class="btn" id="submitBtn">✉️ Отправить</button>
            <div class="info-note">
                Ваше сообщение будет сохранено и доступно в вашем профиле.
            </div>
        </form>
    </section>
</div>
</main>

<?php include __DIR__ . '/footer.php'; ?>
<script src="script.js"></script>
</body>
</html>