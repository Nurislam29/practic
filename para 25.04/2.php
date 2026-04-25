<?php
$result = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operation = $_POST['operation'] ?? '';

    // Проверка на пустые поля
    if ($num1 === '' || $num2 === '') {
        $error = 'Пожалуйста, заполните оба поля.';
    }
    // Проверка на числа
    elseif (!is_numeric($num1) || !is_numeric($num2)) {
        $error = 'Введите корректные числа.';
    } else {
        $num1 = (float)$num1;
        $num2 = (float)$num2;

        switch ($operation) {
            case '+':
                $result = $num1 + $num2;
                break;
            case '-':
                $result = $num1 - $num2;
                break;
            case '*':
                $result = $num1 * $num2;
                break;
            case '/':
                if ($num2 == 0) {
                    $error = 'Ошибка: деление на 0!';
                } else {
                    $result = $num1 / $num2;
                }
                break;
            case '%':
                if ($num2 == 0) {
                    $error = 'Ошибка: деление на 0!';
                } else {
                    $result = $num1 % $num2;
                }
                break;
            case '^':
                $result = pow($num1, $num2);
                break;
            default:
                $error = 'Выберите корректную операцию.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Микро-калькулятор</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px;
        }
        .calculator {
            background-color: #fff;
            padding: 25px 35px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 320px;
        }
        .calculator h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .calculator input[type="text"], .calculator select {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .calculator button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007BFF;
            color: #fff;
            font-size: 18px;
            border-radius: 6px;
            cursor: pointer;
        }
        .calculator button:hover {
            background-color: #0056b3;
        }
        .result, .error {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            font-size: 16px;
        }
        .result {
            background-color: #e6ffed;
            border: 1px solid #2ecc71;
        }
        .error {
            background-color: #ffe6e6;
            border: 1px solid #e74c3c;
        }
    </style>
</head>
<body>

<div class="calculator">
    <h2>Микро-калькулятор</h2>
    <form method="post">
        <input type="text" name="num1" placeholder="Первое число" value="<?php echo htmlspecialchars($_POST['num1'] ?? '') ?>">
        <input type="text" name="num2" placeholder="Второе число" value="<?php echo htmlspecialchars($_POST['num2'] ?? '') ?>">
        <select name="operation">
            <option value="">Выберите операцию</option>
            <option value="+" <?php if(($_POST['operation'] ?? '') === '+') echo 'selected'; ?>>Сложение (+)</option>
            <option value="-" <?php if(($_POST['operation'] ?? '') === '-') echo 'selected'; ?>>Вычитание (-)</option>
            <option value="*" <?php if(($_POST['operation'] ?? '') === '*') echo 'selected'; ?>>Умножение (*)</option>
            <option value="/" <?php if(($_POST['operation'] ?? '') === '/') echo 'selected'; ?>>Деление (/)</option>
            <option value="%" <?php if(($_POST['operation'] ?? '') === '%') echo 'selected'; ?>>Остаток (%)</option>
            <option value="^" <?php if(($_POST['operation'] ?? '') === '^') echo 'selected'; ?>>Степень (^)</option>
        </select>
        <button type="submit">Вычислить</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php elseif ($result !== ''): ?>
        <div class="result">Результат: <?php echo $result; ?></div>
    <?php endif; ?>
</div>

</body>
</html>