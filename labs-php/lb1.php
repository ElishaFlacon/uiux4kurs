<?php

declare(strict_types=1);

// Вкладка по умолчанию
$tab = $_GET['tab'] ?? 'hello';

// Для вкладки nocache отправим заголовки до вывода тела
if ($tab === 'nocache') {
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

// Утилита безопасного html-экранирования
function h(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Upr1 — единая страница</title>
    <style>
        body {
            font-family: system-ui, Arial, sans-serif;
            margin: 20px;
        }

        nav a {
            margin-right: 10px;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            color: #222;
        }

        nav a.active {
            background: #eef;
            border-color: #99c;
        }

        form {
            margin: 12px 0;
        }

        label {
            display: inline-block;
            margin: 4px 8px 4px 0;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"] {
            padding: 6px 8px;
        }

        pre {
            background: #f7f7f7;
            padding: 10px;
            border-radius: 6px;
            overflow: auto;
        }

        .row {
            margin: 8px 0;
        }
    </style>
</head>

<body>

    <nav>
        <a href="?tab=hello" class="<?= $tab === 'hello' ? 'active' : '' ?>">1) Привет, PHP</a>
        <a href="?tab=vars" class="<?= $tab === 'vars' ? 'active' : '' ?>">2) Переменные/константы</a>
        <a href="?tab=get" class="<?= $tab === 'get' ? 'active' : '' ?>">3) GET форма</a>
        <a href="?tab=post" class="<?= $tab === 'post' ? 'active' : '' ?>">4) POST форма</a>
        <a href="?tab=summary" class="<?= $tab === 'summary' ? 'active' : '' ?>">5) REQUEST/GET/POST/SERVER</a>
        <a href="?tab=hidden" class="<?= $tab === 'hidden' ? 'active' : '' ?>">6) Hidden шаги</a>
        <a href="?tab=nocache" class="<?= $tab === 'nocache' ? 'active' : '' ?>">7) No‑cache</a>
    </nav>

    <hr>

    <?php if ($tab === 'hello'): ?>
        <h2>Привет, PHP!</h2>
        <?php echo "<p>Привет, PHP!</p>"; ?>

    <?php elseif ($tab === 'vars'): ?>
        <h2>Переменные и константы</h2>
        <?php
        define('APP_NAME', 'Upr1Demo');

        $bool = true;
        $int = 2025;
        $float = 3.1415926535;
        $string = "Строка";
        $array = [1, "a", 2.5, false];
        $obj = (object)["x" => 10, "y" => 20];
        $null = null;
        ?>
        <p>APP_NAME = <?= h(APP_NAME) ?></p>
        <pre><?php var_dump($bool, $int, $float, $string, $array, $obj, $null); ?></pre>

    <?php elseif ($tab === 'get'): ?>
        <h2>GET форма</h2>
        <form method="get">
            <input type="hidden" name="tab" value="get">
            <label>Имя: <input type="text" name="name" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>"></label>
            <label>Возраст: <input type="number" name="age" value="<?= isset($_GET['age']) ? h((string)$_GET['age']) : '' ?>"></label>
            <button type="submit">Отправить</button>
        </form>

        <?php if (count($_GET) > 1): // кроме tab 
        ?>
            <h3>Результат</h3>
            <p>Имя: <?= h((string)($_GET['name'] ?? '')) ?></p>
            <p>Возраст: <?= h((string)($_GET['age'] ?? '')) ?></p>
            <pre>GET=<?php print_r($_GET); ?></pre>
        <?php endif; ?>

    <?php elseif ($tab === 'post'): ?>
        <h2>POST форма</h2>
        <form method="post">
            <input type="hidden" name="tab" value="post">
            <label>E-mail: <input type="email" name="email" value=""></label>
            <label>Сообщение: <input type="text" name="message" value=""></label>
            <button type="submit">Отправить</button>
        </form>

        <?php if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['tab'] ?? '') === 'post'): ?>
            <h3>Результат</h3>
            <p>E-mail: <?= h((string)($_POST['email'] ?? '')) ?></p>
            <p>Сообщение: <?= h((string)($_POST['message'] ?? '')) ?></p>
            <pre>POST=<?php print_r($_POST); ?></pre>
        <?php endif; ?>

    <?php elseif ($tab === 'summary'): ?>
        <h2>REQUEST, GET, POST и SERVER</h2>

        <div class="row"><strong>Форма GET</strong></div>
        <form method="get">
            <input type="hidden" name="tab" value="summary">
            <input type="text" name="g1" placeholder="g1">
            <input type="text" name="g2" placeholder="g2">
            <button type="submit">Отправить GET</button>
        </form>

        <div class="row"><strong>Форма POST</strong></div>
        <form method="post">
            <input type="hidden" name="tab" value="summary">
            <input type="text" name="p1" placeholder="p1">
            <input type="text" name="p2" placeholder="p2">
            <button type="submit">Отправить POST</button>
        </form>

        <h3>Данные</h3>
        <pre>REQUEST:
<?php print_r($_REQUEST); ?></pre>

        <pre>GET:
<?php print_r($_GET); ?></pre>

        <pre>POST:
<?php print_r($_POST); ?></pre>

        <h3>SERVER</h3>
        <pre><?=
                "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? '') . PHP_EOL .
                    "REMOTE_ADDR: "    . ($_SERVER['REMOTE_ADDR'] ?? '')    . PHP_EOL .
                    "HTTP_USER_AGENT: " . ($_SERVER['HTTP_USER_AGENT'] ?? '') . PHP_EOL .
                    "SCRIPT_NAME: "    . ($_SERVER['SCRIPT_NAME'] ?? '')    . PHP_EOL
                ?></pre>

    <?php elseif ($tab === 'hidden'): ?>
        <h2>Hidden поле — шаги</h2>
        <?php
        $step = 1;
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['tab'] ?? '') === 'hidden') {
            $step = (int)($_POST['step'] ?? 1);
        } else {
            $step = (int)($_GET['step'] ?? 1);
        }
        if ($step < 1) $step = 1;
        ?>
        <p>Текущий шаг: <?= $step ?></p>

        <form method="post" style="display:inline-block;margin-right:8px">
            <input type="hidden" name="tab" value="hidden">
            <input type="hidden" name="step" value="<?= $step + 1 ?>">
            <button type="submit">Далее</button>
        </form>

        <?php if ($step > 1): ?>
            <form method="post" style="display:inline-block">
                <input type="hidden" name="tab" value="hidden">
                <input type="hidden" name="step" value="<?= max(1, $step - 1) ?>">
                <button type="submit">Назад</button>
            </form>
        <?php endif; ?>

        <div class="row">
            <form method="get" style="margin-top:10px">
                <input type="hidden" name="tab" value="hidden">
                <button type="submit">Сбросить</button>
            </form>
        </div>

    <?php elseif ($tab === 'nocache'): ?>
        <h2>No‑cache заголовки</h2>
        <p>Эта страница не кэшируется. Текущее время: <?= h(gmdate('c')) ?></p>

    <?php else: ?>
        <p>Неизвестная вкладка.</p>
    <?php endif; ?>

    <hr>
    <p style="color:#666">Upr1 — единый индекс. Переключайтесь по вкладкам сверху.</p>

</body>

</html>