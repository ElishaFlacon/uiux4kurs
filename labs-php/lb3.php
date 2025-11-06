<?php
// index.php — ЛР3: все упражнения в одном файле (POST)
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function parse_num(?string $raw, bool $as_int = false): array
{
    if ($raw === null) return [null, 'Значение не передано'];
    $s = str_replace(',', '.', trim($raw));
    if ($s === '') return [null, 'Значение не передано'];
    if (!is_numeric($s)) return [null, 'Введите число'];
    $val = $as_int ? (int)round((float)$s) : (float)$s;
    return [$val, null];
}
$which = $_POST['form'] ?? '';
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>ЛР3</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 24px;
            line-height: 1.5;
        }

        form {
            max-width: 960px;
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: #fafafa;
            margin: 0 0 22px;
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 12px;
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
            border-radius: 8px;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
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
            border-radius: 10px;
            background: #fff;
        }

        .err {
            color: #b00020;
        }

        table.mul {
            border-collapse: collapse;
            margin: 8px 0;
        }

        table.mul td,
        table.mul th {
            border: 1px solid #888;
            padding: 6px 8px;
            text-align: center;
        }

        .muted {
            color: #666;
            font-size: .95rem;
        }
    </style> -->
</head>

<body>
    <h1>ЛР3</h1>

    <!-- 3.1.1: Максимум из трех -->
    <form method="post" action="">
        <input type="hidden" name="form" value="311">
        <fieldset>
            <legend>Максимум из трёх чисел</legend>
            <div class="row-3">
                <div>
                    <label for="a311">a</label>
                    <input type="text" id="a311" name="a" value="<?php echo h($_POST['a'] ?? ''); ?>">
                </div>
                <div>
                    <label for="b311">b</label>
                    <input type="text" id="b311" name="b" value="<?php echo h($_POST['b'] ?? ''); ?>">
                </div>
                <div>
                    <label for="c311">c</label>
                    <input type="text" id="c311" name="c" value="<?php echo h($_POST['c'] ?? ''); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Вычислить</button>
                <button type="reset">Сбросить</button>
            </div>

            <?php if ($which === '311'): ?>
                <div class="result">
                    <?php
                    [$a, $ea] = parse_num($_POST['a'] ?? null);
                    [$b, $eb] = parse_num($_POST['b'] ?? null);
                    [$c, $ec] = parse_num($_POST['c'] ?? null);
                    $errs = [];
                    if ($ea) $errs[] = "a: $ea";
                    if ($eb) $errs[] = "b: $eb";
                    if ($ec) $errs[] = "c: $ec";
                    if ($errs) {
                        echo '<ul class="err">';
                        foreach ($errs as $er) echo '<li>' . h($er) . '</li>';
                        echo '</ul>';
                    } else {
                        // Классический if/else
                        $max = $a;
                        if ($b > $max) $max = $b;
                        if ($c > $max) $max = $c;
                        // Тернарная запись
                        $max2 = ($a >= $b ? ($a >= $c ? $a : $c) : ($b >= $c ? $b : $c));
                        echo '<p>Максимальное (if/else): ' . h((string)$max) . '</p>';
                        echo '<p>Максимальное (тернарный): ' . h((string)$max2) . '</p>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- 3.1.2: Знак числа -->
    <form method="post" action="">
        <input type="hidden" name="form" value="312">
        <fieldset>
            <legend>Логические выражения (знак числа)</legend>
            <div class="row-2">
                <div>
                    <label for="x312">Число x</label>
                    <input type="text" id="x312" name="x" value="<?php echo h($_POST['x'] ?? ''); ?>">
                </div>
                <div>
                    <label for="res312">Результат</label>
                    <input type="text" id="res312" readonly value="<?php
                                                                    if ($which === '312') {
                                                                        [$x, $ex] = parse_num($_POST['x'] ?? null);
                                                                        if ($ex) echo 'Ошибка ввода';
                                                                        else {
                                                                            $sign = ($x > 0 ? 'положительное' : ($x < 0 ? 'отрицательное' : 'ноль'));
                                                                            echo h($sign);
                                                                        }
                                                                    }
                                                                    ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Проверить</button>
                <button type="reset">Сбросить</button>
            </div>
        </fieldset>
    </form>

    <!-- 3.2: Викторина -->
    <form method="post" action="">
        <input type="hidden" name="form" value="32">
        <fieldset>
            <legend>Простая викторина</legend>

            <label for="q1">1) Какой язык обрабатывается только на сервере?</label>
            <select id="q1" name="q1">
                <option value="">— выберите —</option>
                <option value="js" <?php echo (($_POST['q1'] ?? '') === 'js') ? 'selected' : ''; ?>>JavaScript</option>
                <option value="php" <?php echo (($_POST['q1'] ?? '') === 'php') ? 'selected' : ''; ?>>PHP</option>
                <option value="css" <?php echo (($_POST['q1'] ?? '') === 'css') ? 'selected' : ''; ?>>CSS</option>
            </select>
            <br>
            <label for="q2">2) Другое название сокращённой записи условного оператора</label>
            <select id="q2" name="q2">
                <option value="">— выберите —</option>
                <option value="ternary" <?php echo (($_POST['q2'] ?? '') === 'ternary') ? 'selected' : ''; ?>>Тернарный оператор</option>
                <option value="inc" <?php echo (($_POST['q2'] ?? '') === 'inc') ? 'selected' : ''; ?>>Оператор инкремента</option>
                <option value="logic" <?php echo (($_POST['q2'] ?? '') === 'logic') ? 'selected' : ''; ?>>Логический оператор</option>
            </select>
            <br>
            <label for="q3">3) Как обозначается целочисленный тип данных?</label>
            <select id="q3" name="q3">
                <option value="">— выберите —</option>
                <option value="bool" <?php echo (($_POST['q3'] ?? '') === 'bool') ? 'selected' : ''; ?>>Bool</option>
                <option value="float" <?php echo (($_POST['q3'] ?? '') === 'float') ? 'selected' : ''; ?>>Float</option>
                <option value="int" <?php echo (($_POST['q3'] ?? '') === 'int') ? 'selected' : ''; ?>>Int</option>
            </select>
            <br>
            <div class="row-2">
                <div>
                    <label for="q4">4) Сколько основных логических операторов в PHP?</label>
                    <input type="text" id="q4" name="q4" value="<?php echo h($_POST['q4'] ?? ''); ?>" placeholder="Число">
                </div>
                <div>
                    <label for="q5">5) Альтернативный способ возвести число в степень (a ** b)?</label>
                    <input type="text" id="q5" name="q5" value="<?php echo h($_POST['q5'] ?? ''); ?>" placeholder="Например: pow">
                </div>
            </div>

            <div class="actions">
                <button type="submit">Проверить ответы</button>
                <button type="reset">Сбросить</button>
            </div>

            <?php if ($which === '32'): ?>
                <div class="result">
                    <?php
                    $score = 0;
                    $details = [];
                    $ans1 = ($_POST['q1'] ?? '') === 'php';
                    $score += $ans1 ? 1 : 0;
                    $details[] = '1) ' . ($ans1 ? 'верно' : 'неверно');
                    $ans2 = ($_POST['q2'] ?? '') === 'ternary';
                    $score += $ans2 ? 1 : 0;
                    $details[] = '2) ' . ($ans2 ? 'верно' : 'неверно');
                    $ans3 = ($_POST['q3'] ?? '') === 'int';
                    $score += $ans3 ? 1 : 0;
                    $details[] = '3) ' . ($ans3 ? 'верно' : 'неверно');
                    $ans4 = trim((string)($_POST['q4'] ?? ''));
                    $ans4ok = ($ans4 !== '' && (string)(int)$ans4 === (string)$ans4 && (int)$ans4 === 4);
                    $score += $ans4ok ? 1 : 0;
                    $details[] = '4) ' . ($ans4ok ? 'верно' : 'неверно');
                    $ans5 = mb_strtolower(trim((string)($_POST['q5'] ?? '')), 'UTF-8');
                    $ans5ok = ($ans5 === 'pow');
                    $score += $ans5ok ? 1 : 0;
                    $details[] = '5) ' . ($ans5ok ? 'верно' : 'неверно');
                    echo '<p>Итоговый балл: ' . h((string)$score) . ' из 5</p>';
                    echo '<ul>';
                    foreach ($details as $d) echo '<li>' . h($d) . '</li>';
                    echo '</ul>';
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- 3.3.1: Факториал (while) -->
    <form method="post" action="">
        <input type="hidden" name="form" value="331">
        <fieldset>
            <legend>Факториал с циклом while</legend>
            <div class="row-2">
                <div>
                    <label for="n331">n (целое, 0–20)</label>
                    <input type="text" id="n331" name="n" value="<?php echo h($_POST['n'] ?? ''); ?>">
                </div>
                <div>
                    <label for="out331">Результат</label>
                    <input type="text" id="out331" readonly value="<?php
                                                                    if ($which === '331') {
                                                                        [$n, $en] = parse_num($_POST['n'] ?? null, true);
                                                                        if ($en) {
                                                                            echo 'Ошибка ввода';
                                                                        } else if ($n < 0) {
                                                                            echo 'n ≥ 0';
                                                                        } else if ($n > 20) {
                                                                            echo 'слишком большое n';
                                                                        } else {
                                                                            $p = 1;
                                                                            $i = 1;
                                                                            while ($i <= $n) {
                                                                                $p *= $i;
                                                                                $i += 1;
                                                                            }
                                                                            echo $p;
                                                                        }
                                                                    }
                                                                    ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Посчитать</button>
                <button type="reset">Сбросить</button>
            </div>
        </fieldset>
    </form>

    <!-- 3.3.2: Таблица Пифагора -->
    <form method="post" action="">
        <input type="hidden" name="form" value="332">
        <fieldset>
            <legend>3.3.2 — Таблица Пифагора (for × for)</legend>
            <div class="row-2">
                <div>
                    <label for="s332">Размер таблицы (2–20, квадрат)</label>
                    <input type="text" id="s332" name="size" placeholder="По умолчанию 10" value="<?php echo h($_POST['size'] ?? ''); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Построить</button>
                <button type="reset">Сбросить</button>
            </div>

            <?php if ($which === '332'): ?>
                <div class="result">
                    <?php
                    $size = 10;
                    if (isset($_POST['size']) && trim($_POST['size']) !== '') {
                        [$s, $es] = parse_num($_POST['size'], true);
                        if ($es) {
                            echo '<p class="err">Введите целое число</p>';
                        } else {
                            if ($s < 2) $s = 2;
                            if ($s > 20) $s = 20;
                            $size = $s;
                        }
                    }
                    echo '<table class="mul"><tbody>';
                    for ($tr = 1; $tr <= $size; $tr++) {
                        echo '<tr>';
                        for ($td = 1; $td <= $size; $td++) {
                            echo '<td>' . ($tr * $td) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- 3.3.3: Чётные квадраты, break/continue -->
    <form method="post" action="">
        <input type="hidden" name="form" value="333">
        <fieldset>
            <legend>Чётные квадраты с ограничением</legend>
            <div class="row-2">
                <div>
                    <label for="n333">Предел n (целое ≥ 1)</label>
                    <input type="text" id="n333" name="n2" value="<?php echo h($_POST['n2'] ?? ''); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Показать квадраты</button>
                <button type="reset">Сбросить</button>
            </div>

            <?php if ($which === '333'): ?>
                <div class="result">
                    <?php
                    [$n2, $e2] = parse_num($_POST['n2'] ?? null, true);
                    if ($e2) {
                        echo '<p class="err">Введите целое число</p>';
                    } else if ($n2 < 1) {
                        echo '<p class="err">n должно быть ≥ 1</p>';
                    } else {
                        $out = [];
                        for ($i = 1; $i <= $n2; $i++) {
                            if ($i % 2 != 0) continue;           // пропуск нечётных
                            $sq = $i * $i;
                            if ($sq > 99) {                      // больше двух цифр
                                echo '<p><span class="err">Переполнение!</span></p>';
                                break;
                            }
                            $out[] = (string)$sq;
                        }
                        if (!empty($out)) {
                            echo '<p>Ряды: ' . h(implode(' ', $out)) . '</p>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

</body>

</html>