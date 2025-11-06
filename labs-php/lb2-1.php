<?php
// index.php — ЛР2: все формы и обработка в одном файле (GET)
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function parse_num(?string $raw): array
{
    if ($raw === null) return [null, 'Значение не передано'];
    $s = str_replace(',', '.', trim($raw));
    if (!is_numeric($s)) return [null, 'Введите число'];
    return [floatval($s), null];
}
$which = $_GET['form'] ?? '';
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>ЛР2</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 24px;
            line-height: 1.5;
        }

        form {
            max-width: 860px;
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fafafa;
            margin-bottom: 20px;
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            margin: 0;
        }

        legend {
            padding: 0 6px;
        }

        label {
            display: block;
            margin: 8px 0 4px;
        }

        input[type=text],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #bbb;
            border-radius: 6px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .result {
            margin-top: 12px;
            padding: 12px;
            border: 1px dashed #aaa;
            border-radius: 8px;
            background: #fff;
        }

        .err {
            color: #b00020;
        }

        .muted {
            color: #666;
            font-size: .95rem;
        }
    </style> -->
</head>

<body>
    <h1>ЛР2</h1>

    <!-- Упражнение 2.1: Простые арифметические операции -->
    <form method="get" action="">
        <input type="hidden" name="form" value="calc1">
        <fieldset>
            <legend>Простые операции</legend>
            <div class="row">
                <div>
                    <label for="a1">Число A</label>
                    <input type="text" id="a1" name="a1" value="<?php echo h($_GET['a1'] ?? ''); ?>">
                </div>
                <div>
                    <label for="b1">Число B</label>
                    <input type="text" id="b1" name="b1" value="<?php echo h($_GET['b1'] ?? ''); ?>">
                </div>
            </div>
            <label for="op1">Операция</label>
            <select id="op1" name="op1">
                <option value="add" <?php echo (($_GET['op1'] ?? '') === 'add') ? 'selected' : ''; ?>>Сложение (A + B)</option>
                <option value="sub" <?php echo (($_GET['op1'] ?? '') === 'sub') ? 'selected' : ''; ?>>Вычитание (A - B)</option>
                <option value="mul" <?php echo (($_GET['op1'] ?? '') === 'mul') ? 'selected' : ''; ?>>Произведение (A * B)</option>
                <option value="div" <?php echo (($_GET['op1'] ?? '') === 'div') ? 'selected' : ''; ?>>Деление (A / B)</option>
                <option value="pow" <?php echo (($_GET['op1'] ?? '') === 'pow') ? 'selected' : ''; ?>>Степень (A ** B)</option>
                <option value="mod" <?php echo (($_GET['op1'] ?? '') === 'mod') ? 'selected' : ''; ?>>Остаток (A mod B)</option>
            </select>
            <div class="actions">
                <button type="submit">Вычислить</button>
            </div>

            <?php if ($which === 'calc1'): ?>
                <div class="result">
                    <?php
                    [$a, $e1] = parse_num($_GET['a1'] ?? null);
                    [$b, $e2] = parse_num($_GET['b1'] ?? null);
                    $op = $_GET['op1'] ?? 'add';
                    $errs = [];
                    if ($e1) $errs[] = "A: $e1";
                    if ($e2) $errs[] = "B: $e2";

                    if (!$errs) {
                        $ok = true;
                        $msg = '';
                        $val = null;
                        switch ($op) {
                            case 'add':
                                $val = $a + $b;
                                break;
                            case 'sub':
                                $val = $a - $b;
                                break;
                            case 'mul':
                                $val = $a * $b;
                                break;
                            case 'div':
                                if ($b == 0.0) {
                                    $ok = false;
                                    $msg = 'Деление на ноль недопустимо';
                                } else $val = $a / $b;
                                break;
                            case 'pow':
                                // Потенциальные ограничения по области могут зависеть от значения A и B
                                $val = pow($a, $b);
                                break;
                            case 'mod':
                                if ($b == 0.0) {
                                    $ok = false;
                                    $msg = 'Остаток по модулю на ноль недопустим';
                                } else $val = fmod($a, $b);
                                break;
                            default:
                                $ok = false;
                                $msg = 'Неизвестная операция';
                        }
                        if ($ok) {
                            $rounded = round($val, 2);
                            echo '<p>Результат: ' . h((string)$rounded) . '</p>';
                        } else {
                            echo '<p class="err">' . h($msg) . '</p>';
                        }
                    } else {
                        echo '<ul class="err">';
                        foreach ($errs as $er) echo '<li>' . h($er) . '</li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- Упражнение 2.2: Сложное выражение -->
    <form method="get" action="">
        <input type="hidden" name="form" value="calc2">
        <fieldset>
            <legend>Сложное выражение</legend>
            <div class="row">
                <div>
                    <label for="x2">x</label>
                    <input type="text" id="x2" name="x2" value="<?php echo h($_GET['x2'] ?? ''); ?>">
                </div>
                <div>
                    <label for="t2">t</label>
                    <input type="text" id="t2" name="t2" value="<?php echo h($_GET['t2'] ?? ''); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Вычислить</button>
            </div>

            <?php if ($which === 'calc2'): ?>
                <div class="result">
                    <?php
                    [$x, $ex] = parse_num($_GET['x2'] ?? null);
                    [$t, $et] = parse_num($_GET['t2'] ?? null);
                    $errs = [];
                    if ($ex) $errs[] = "x: $ex";
                    if ($et) $errs[] = "t: $et";
                    if (!$errs) {
                        if ($t < 0) $errs[] = 'Область определения: t должно быть ≥ 0 для sqrt(t)';
                        $den = sqrt($t) - abs(sin($t));
                        if ($den == 0.0) $errs[] = 'Знаменатель равен нулю: sqrt(t) − |sin(t)| ≠ 0';
                    }
                    if ($errs) {
                        echo '<ul class="err">';
                        foreach ($errs as $er) echo '<li>' . h($er) . '</li>';
                        echo '</ul>';
                    } else {
                        $num = 9 * pi() * $t + 10 * cos($x);
                        $den = sqrt($t) - abs(sin($t));
                        $val = ($num / $den) * exp($x);
                        $res = round($val, 2);
                        echo '<p>Числитель: ' . h((string)round($num, 6)) . '</p>';
                        echo '<p>Знаменатель: ' . h((string)round($den, 6)) . '</p>';
                        echo '<p>Результат: ' . h((string)$res) . '</p>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

</body>

</html>