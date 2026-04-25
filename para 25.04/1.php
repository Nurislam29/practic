<?php
// Массив с 5 пользователями
$users = [
    [
        "name" => "Алексей Иванов",
        "age" => 28,
        "email" => "alex@example.com",
        "city" => "Москва"
    ],
    [
        "name" => "Мария Петрова",
        "age" => 32,
        "email" => "maria@example.com",
        "city" => "Санкт-Петербург"
    ],
    [
        "name" => "Иван Сидоров",
        "age" => 24,
        "email" => "ivan@example.com",
        "city" => "Новосибирск"
    ],
    [
        "name" => "Екатерина Смирнова",
        "age" => 29,
        "email" => "ekaterina@example.com",
        "city" => "Екатеринбург"
    ],
    [
        "name" => "Дмитрий Кузнецов",
        "age" => 35,
        "email" => "dmitry@example.com",
        "city" => "Казань"
    ]
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список пользователей</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .user-card {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .user-card h2 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
        }
        .user-card a {
            color: #007BFF;
            text-decoration: none;
        }
        .user-card a:hover {
            text-decoration: underline;
        }
        .user-card p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<h1>Список пользователей</h1>

<?php
// Вывод пользователей в карточках
foreach ($users as $user) {
    echo '<div class="user-card">';
    echo '<h2>' . $user['name'] . ', ' . $user['age'] . '</h2>';
    echo '<p>Email: <a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a></p>';
    echo '<p>Город: ' . $user['city'] . '</p>';
    echo '</div>';
}
?>

</body>
</html>