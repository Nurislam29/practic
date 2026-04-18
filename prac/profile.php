<?php
session_start();

// Проверка авторизации
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

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
    die("Ошибка подключения к базе: " . $e->getMessage());
}

// Получаем данные пользователя
$stmtUser = $pdo->prepare("SELECT id, message FROM users WHERE username = ?");
$stmtUser->execute([$_SESSION['user']]);
$user = $stmtUser->fetch();
$user_id = $user['id'];
$user_message = $user['message'];

// Получаем курсы пользователя
$stmtMyCourses = $pdo->prepare("
    SELECT c.* 
    FROM courses c
    JOIN user_courses uc ON c.id = uc.course_id
    WHERE uc.user_id = ?
    ORDER BY c.id ASC
");
$stmtMyCourses->execute([$user_id]);
$myCourses = $stmtMyCourses->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Профиль | Демо-проект</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'header.php'; ?>

<main class="page-main">
<div class="container">
    <!-- Приветствие -->
    <section class="form-section">
        <h2>Привет, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
        <p>Добро пожаловать в личный кабинет. Здесь вы можете просмотреть свои курсы и историю сообщений.</p>
    </section>

    <!-- Мои курсы -->
    <section class="form-section">
        <h3 style="margin-bottom: 1rem;">Мои курсы</h3>
        <?php if($myCourses): ?>
            <div class="cards-grid">
                <?php foreach($myCourses as $course): ?>
                    <a href="course.php?id=<?php echo $course['id']; ?>" class="demo-card-link" style="text-decoration: none; color: inherit;">
                        <article class="demo-card">
                            <div class="image-placeholder">
                                <?php echo $course['image'] 
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
        <?php else: ?>
            <p>Вы пока не записаны на курсы.</p>
        <?php endif; ?>
    </section>

    <!-- Мои сообщения -->
    <section class="form-section">
        <h3 style="margin-bottom: 1rem;">Мои сообщения</h3>
        <?php if($user_message): ?>
            <div class="info-note"><?php echo nl2br(htmlspecialchars($user_message)); ?></div>
        <?php else: ?>
            <p style="color:#334155;">Вы ещё не отправляли сообщений.</p>
        <?php endif; ?>
    </section>

    <!-- Кнопка выхода -->
    <section class="form-section">
        <a href="logout.php" class="btn">Выйти из аккаунта</a>
    </section>
</div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>