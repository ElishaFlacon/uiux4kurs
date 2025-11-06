<?php
// index.php — единый файл с формой и обработкой (GET)
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$isSubmitted = ($_SERVER['REQUEST_METHOD'] === 'GET') && !empty($_GET);
$errors = [];

if ($isSubmitted) {
    $name = trim($_GET['name'] ?? '');
    $last_name = trim($_GET['last_name'] ?? '');
    $patron = trim($_GET['patron'] ?? '');

    // Проверка: только буквы (русские и латинские)
    $pattern = '/^[a-zA-Zа-яА-ЯёЁ]+$/u';

    if (!preg_match($pattern, $name)) {
        $errors[] = 'Поле "Имя" не должно содержать цифры или специальные символы.';
    }
    if (!preg_match($pattern, $last_name)) {
        $errors[] = 'Поле "Фамилия" не должно содержать цифры или специальные символы.';
    }
    if (!preg_match($pattern, $patron)) {
        $errors[] = 'Поле "Отчество" не должно содержать цифры или специальные символы.';
    }
}
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>ЛР1</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 2rem;
            line-height: 1.5;
        }

        form {
            max-width: 720px;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
        }

        fieldset {
            border: 1px solid #ccc;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        legend {
            padding: 0 .5rem;
        }

        label {
            display: block;
            margin: .4rem 0 .2rem;
        }

        input[type=text],
        select {
            width: 100%;
            padding: .5rem;
            border: 1px solid #bbb;
            border-radius: 4px;
        }

        input.error {
            border-color: red;
            background: #ffe6e6;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }

        .actions {
            margin-top: 1rem;
            display: flex;
            gap: .5rem;
        }

        .result {
            margin-top: 2rem;
            padding: 1rem;
            border: 1px dashed #aaa;
            border-radius: 8px;
            background: #fff;
        }

        .muted {
            color: #666;
            font-size: .95rem;
        }

        .error-message {
            color: red;
            margin-bottom: 1rem;
        }
    </style> -->
</head>

<body>
    <h1>ЛР1</h1>
    <form method="get" action="">
        <fieldset>
            <legend>Персональные данные</legend>
            <div class="row">
                <div>
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" required
                        value="<?php echo isset($_GET['name']) ? h($_GET['name']) : ''; ?>"
                        class="<?php echo ($isSubmitted && isset($_GET['name']) && !preg_match('/^[a-zA-Zа-яА-ЯёЁ]+$/u', $_GET['name'])) ? 'error' : ''; ?>">
                </div>
                <div>
                    <label for="last_name">Фамилия</label>
                    <input type="text" id="last_name" name="last_name" required
                        value="<?php echo isset($_GET['last_name']) ? h($_GET['last_name']) : ''; ?>"
                        class="<?php echo ($isSubmitted && isset($_GET['last_name']) && !preg_match('/^[a-zA-Zа-яА-ЯёЁ]+$/u', $_GET['last_name'])) ? 'error' : ''; ?>">
                </div>
                <div>
                    <label for="patron">Отчество</label>
                    <input type="text" id="patron" name="patron" required
                        value="<?php echo isset($_GET['patron']) ? h($_GET['patron']) : ''; ?>"
                        class="<?php echo ($isSubmitted && isset($_GET['patron']) && !preg_match('/^[a-zA-Zа-яА-ЯёЁ]+$/u', $_GET['patron'])) ? 'error' : ''; ?>">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Программы</legend>
            <label>
                <input type="checkbox" name="prog1" value="IE" <?php echo (isset($_GET['prog1']) && $_GET['prog1'] === 'IE') ? 'checked' : ''; ?>>
                IE
            </label>
            <label>
                <input type="checkbox" name="prog2" value="Note" <?php echo (isset($_GET['prog2']) && $_GET['prog2'] === 'Note') ? 'checked' : ''; ?>>
                Note
            </label>
        </fieldset>

        <fieldset>
            <legend>Информатика</legend>
            <label>
                <input type="radio" name="inf" value="Y" <?php echo (isset($_GET['inf']) && $_GET['inf'] === 'Y') ? 'checked' : ''; ?>>
                Нравится
            </label>
            <label>
                <input type="radio" name="inf" value="N" <?php echo (isset($_GET['inf']) && $_GET['inf'] === 'N') ? 'checked' : ''; ?>>
                Не нравится
            </label>
        </fieldset>

        <fieldset>
            <legend>Дополнительные вопросы</legend>
            <label for="group">Группа</label>
            <input type="text" id="group" name="group" placeholder="Например: ИВТ-101" value="<?php echo isset($_GET['group']) ? h($_GET['group']) : ''; ?>">

            <label for="level">Уровень подготовки</label>
            <select id="level" name="level">
                <option value="">— выберите —</option>
                <option value="beginner" <?php echo (isset($_GET['level']) && $_GET['level'] === 'beginner') ? 'selected' : ''; ?>>Начальный</option>
                <option value="intermediate" <?php echo (isset($_GET['level']) && $_GET['level'] === 'intermediate') ? 'selected' : ''; ?>>Средний</option>
                <option value="advanced" <?php echo (isset($_GET['level']) && $_GET['level'] === 'advanced') ? 'selected' : ''; ?>>Продвинутый</option>
            </select>
        </fieldset>

        <div class="actions">
            <button type="submit">Отправить</button>
        </div>
    </form>

    <?php if ($isSubmitted): ?>
        <?php if (!empty($errors)): ?>
            <div class="result error-message">
                <h2>Ошибки:</h2>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo h($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="result">
                <h2>Результат обработки</h2>

                <p><strong>ФИО:</strong>
                    <?php echo h($_GET['last_name'] ?? ''), ' ', h($_GET['name'] ?? ''), ' ', h($_GET['patron'] ?? ''); ?>
                </p>

                <p><strong>Программы:</strong>
                    <?php
                    $out = '';
                    $hasP1 = isset($_GET['prog1']) && $_GET['prog1'] === 'IE';
                    $hasP2 = isset($_GET['prog2']) && $_GET['prog2'] === 'Note';
                    if ($hasP1) {
                        $out .= 'IE, Chrome, Yandex';
                    }
                    if ($hasP1 && $hasP2) {
                        $out .= ', plus ';
                    }
                    if ($hasP2) {
                        $out .= 'Notepad++, WebStorm, VSCode';
                    }
                    echo $out !== '' ? h($out) : 'ничего не выбрано';
                    ?>
                </p>

                <p><strong>Отношение к информатике:</strong>
                    <?php
                    if (isset($_GET['inf']) && $_GET['inf'] === 'Y') {
                        echo 'Вам нравится информатика';
                    } else {
                        echo 'Вам не нравится информатика';
                    }
                    ?>
                </p>

                <p><strong>Группа:</strong> <?php echo h($_GET['group'] ?? '—'); ?></p>

                <p><strong>Уровень подготовки:</strong>
                    <?php
                    $levels = ['beginner' => 'Начальный', 'intermediate' => 'Средний', 'advanced' => 'Продвинутый'];
                    $lvl = $_GET['level'] ?? '';
                    echo isset($levels[$lvl]) ? $levels[$lvl] : '—';
                    ?>
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>