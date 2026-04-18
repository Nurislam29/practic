<?php
session_start();

if (!isset($_SESSION['user'])) {
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
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Ошибка подключения к базе: " . $e->getMessage());
}

// Получаем id курса из GET
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Получаем данные курса
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) die("Курс не найден.");

// Получаем id пользователя
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmtUser->execute([$_SESSION['user']]);
$user = $stmtUser->fetch();
$user_id = $user['id'];

// Проверяем, записан ли пользователь
$stmtCheck = $pdo->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
$stmtCheck->execute([$user_id, $course_id]);
$enrolled = $stmtCheck->rowCount() > 0;

// Обработка записи
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$enrolled) {
    $stmtInsert = $pdo->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (?, ?)");
    $stmtInsert->execute([$user_id, $course_id]);
    $message = "✅ Вы успешно записались на курс!";
    $enrolled = true;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($course['title']); ?> | Демо-проект</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'header.php'; ?>

<main class="page-main">
<div class="container">
    <section class="form-section">
        <h2><?php echo htmlspecialchars($course['title']); ?></h2>
        <p><?php echo htmlspecialchars($course['description']); ?></p>
        <?php if (!empty($course['duration'])): ?>
            <span class="badge"><?php echo htmlspecialchars($course['duration']); ?></span>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="info-note" style="margin-top:1rem;"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!$enrolled): ?>
            <form method="post" style="margin-top:1.5rem;">
                <button type="submit" class="btn">Записаться на курс</button>
            </form>
        <?php else: ?>
            <section style="margin-top:2rem;">
                <h3>Учебный материал</h3>
                <div class="course-content" style="line-height:1.6; color:#1e293b;">
                    <?php echo $course['content']; ?>
                </div>
            </section>
        <?php endif; ?>

        <a href="profile.php" style="display:inline-block; margin-top:1rem;">← Вернуться в профиль</a>
    </section>
</div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>