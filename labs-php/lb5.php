<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');

/*
========================================================================
Единый шаблон КР (варианты 1–5) — всё в одном PHP-файле.
Как быстро адаптировать:
 - ВЫБЕРИТЕ вариант в интерфейсе (сверху страницы).
 - ПРАВЬТЕ параметры в блоках CONFIG ниже (викторина, табулирование 2.4).
 - Для 2.4 замените формулу и/или диапазоны по таблице варианта (TODO пометки).
========================================================================
*/

// ---------- CONFIG: Варианты → состав заданий ----------
$VARIANTS = [
    1 => ['1', '2.1', '2.4', '3.1'],
    2 => ['1', '2.2', '2.5', '3.2'],
    3 => ['1', '2.3', '2.4', '3.2'],
    4 => ['1', '2.4', '2.5', '3.1'],
    5 => ['1', '2.2', '2.4', '3.1'],
];

// ---------- CONFIG: Викторина (Задание 1) ----------
// Требование: не менее 10 вопросов. Замените на свои уникальные вопросы.
// type: 'single' (один ответ), 'multi' (несколько), 'text' (свободный).
$QUIZ = [
    [
        'q' => 'Выберите корректные способы задания стилей в веб-странице',
        'type' => 'multi',
        'options' => [
            'Внутри тега через style="..."',
            'Отдельный тег <style>',
            'Отдельный CSS-файл *.css',
            'Редактирование объекта в Word',
            'Отдельные устаревшие атрибуты оформления',
        ],
        'correct' => [0, 1, 2], // индексы верных
    ],
    [
        'q' => 'Сколько дней в високосном году?',
        'type' => 'text',
        'correct' => '366',
    ],
    [
        'q' => 'Когда будет завтра? (введите: завтра)',
        'type' => 'text',
        'correct' => 'завтра',
    ],
    // добавьте ещё минимум 7 своих вопросов
];

// ---------- CONFIG: Табулирование (Задание 2.4) ----------
// По варианту замените формулу и диапазоны согласно таблице задания.
// step всегда 0.1 (требование). Формула задаётся PHP-функцией f($x): float.
// TODO: замените 'caption' и код формулы f() под ваш вариант и условия таблицы.
$TABULATIONS = [
    1 => [
        'a' => -2.0,
        'b' => 2.0,
        'step' => 0.1,
        'caption' => 'y = 2x + 1 (пример, замените по таблице варианта 1)',
        'f' => function (float $x): float {
            return 2 * $x + 1;
        },
    ],
    2 => [
        'a' => 0.0,
        'b' => 3.0,
        'step' => 0.1,
        'caption' => 'y = x^2 (пример, замените по таблице варианта 2)',
        'f' => function (float $x): float {
            return $x * $x;
        },
    ],
    3 => [
        'a' => -1.0,
        'b' => 1.0,
        'step' => 0.1,
        'caption' => 'y = sin(x) (пример, замените по таблице варианта 3)',
        'f' => function (float $x): float {
            return sin($x);
        },
    ],
    4 => [
        'a' => 1.0,
        'b' => 5.0,
        'step' => 0.1,
        'caption' => 'y = ln(x) (пример, замените по таблице варианта 4)',
        'f' => function (float $x): float {
            return log($x);
        },
    ],
    5 => [
        'a' => -3.0,
        'b' => 0.0,
        'step' => 0.1,
        'caption' => 'y = |x| (пример, замените по таблице варианта 5)',
        'f' => function (float $x): float {
            return abs($x);
        },
    ],
];

// ---------- Вспомогательное ----------
function h(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function req_str(string $key, string $default = ''): string
{
    return isset($_REQUEST[$key]) ? trim((string)$_REQUEST[$key]) : $default;
}
function req_int(string $key, int $default = 0): int
{
    return isset($_REQUEST[$key]) ? (int)$_REQUEST[$key] : $default;
}
function req_float(string $key, float $default = 0.0): float
{
    return isset($_REQUEST[$key]) ? (float)str_replace(',', '.', (string)$_REQUEST[$key]) : $default;
}

// ---------- Логика задач ----------

// 2.1: сумма чисел Фибоначчи, меньших N (N натуральное, защита от отрицательных)
function fib_sum_less_than(int $N): int
{
    if ($N <= 0) return 0;
    $a = 1;
    $b = 1;
    $sum = 0;
    while ($a < $N) {
        $sum += $a;
        [$a, $b] = [$b, $a + $b];
    }
    return $sum;
}

// 2.2: числа Армстронга в диапазоне [0..M] (по условию до 100000)
function is_armstrong(int $n): bool
{
    if ($n < 0) return false;
    $s = (string)$n;
    $p = strlen($s);
    $sum = 0;
    for ($i = 0; $i < $p; $i++) {
        $d = (int)$s[$i];
        $sum += (int)($d ** $p);
        if ($sum > $n) return false;
    }
    return $sum === $n;
}
function armstrong_list(int $max): array
{
    $out = [];
    for ($i = 0; $i <= $max; $i++) {
        if (is_armstrong($i)) $out[] = $i;
    }
    return $out;
}

// 2.3: автоморфные числа ≤ N
function is_automorphic(int $n): bool
{
    if ($n < 0) return false;
    $sq = (string)($n * $n);
    $s  = (string)$n;
    return substr($sq, -strlen($s)) === $s;
}
function automorphic_list(int $N): array
{
    $out = [];
    for ($i = 0; $i <= $N; $i++) {
        if (is_automorphic($i)) $out[] = $i;
    }
    return $out;
}

// 2.4: Табулирование по конфигу варианта
function tabulate(callable $f, float $a, float $b, float $step = 0.1): array
{
    $res = [];
    if ($step <= 0) $step = 0.1;
    // цикл с предусловием
    $x = $a;
    // поправка на накопление погрешности
    for ($k = 0; $x <= $b + 1e-12; $k++, $x = $a + $k * $step) {
        $y = $f($x);
        $res[] = ['x' => round($x, 6), 'y' => $y];
        if ($k > 1000000) break; // защита
    }
    return $res;
}

// 2.5: високосный год (по условию: делится на 4)
function is_leap_simple(int $year): bool
{
    return $year % 4 === 0;
    // Полное правило (если понадобится):
    // return ($year % 400 === 0) || ($year % 4 === 0 && $year % 100 !== 0);
}

// 3.1: «Тевирп!» — зеркальное отображение слов, с сохранением пунктуации и регистра первой буквы (ASCII/латиница)
function tevirp(string $input): string
{
    // Разбиваем, сохраняя пробелы
    $tokens = preg_split('/(\s+)/u', $input, -1, PREG_SPLIT_DELIM_CAPTURE);
    if ($tokens === false) $tokens = [$input];
    $punct = [',', '.', ':', ';', '!', '?', '"', "'", '«', '»', '—', '-'];
    foreach ($tokens as &$tok) {
        if ($tok === '' || preg_match('/^\s+$/u', $tok)) continue;
        $word = $tok;

        // Проверка наличия конечного знака
        $endP = '';
        $lastChar = mb_substr($word, -1, 1, 'UTF-8');
        if (in_array($lastChar, $punct, true)) {
            $endP = $lastChar;
            $word = mb_substr($word, 0, mb_strlen($word, 'UTF-8') - 1, 'UTF-8');
        }

        if ($word === '') {
            $tok = $endP;
            continue;
        }

        // Фиксируем, была ли первая буква заглавной (ASCII)
        $first = mb_substr($word, 0, 1, 'UTF-8');
        $wasUpper = ctype_upper($first);

        // Приводим к нижнему, переворачиваем
        $lower = mb_strtolower($word, 'UTF-8');
        // strrev с латиницей ок, для много-байтовых нужен mb_* (по условию — латиница)
        $rev = strrev($lower);

        // Восстанавливаем первую букву при необходимости
        if ($wasUpper && $rev !== '') {
            $rev = ucfirst($rev);
        }

        // Возвращаем знак препинания
        $tok = $rev . $endP;
    }
    unset($tok);
    return implode('', $tokens);
}

// 3.2: палиндром (ASCII/латиница и цифры)
function is_palindrome_str(string $s): bool
{
    $norm = strtolower(preg_replace('/[^a-z0-9]+/i', '', $s) ?? '');
    return $norm === strrev($norm);
}

// ----------------------- Контроллер -----------------------
$variant = req_int('variant', 1);
if (!isset($VARIANTS[$variant])) $variant = 1;
$activeTasks = $VARIANTS[$variant];

// Результаты выполнения форм
$results = [];

// Викторина
if (req_str('action') === 'quiz_submit') {
    $correct = 0;
    $wrong = 0;
    $skipped = 0;
    foreach ($QUIZ as $i => $q) {
        $key = "q_$i";
        if (!isset($_POST[$key]) || $_POST[$key] === '' || (is_array($_POST[$key]) && count($_POST[$key]) === 0)) {
            $skipped++;
            continue;
        }
        if ($q['type'] === 'text') {
            $ans = trim((string)$_POST[$key]);
            $isOk = mb_strtolower($ans, 'UTF-8') === mb_strtolower((string)$q['correct'], 'UTF-8');
            $isOk ? $correct++ : $wrong++;
        } elseif ($q['type'] === 'single') {
            $ans = (int)$_POST[$key];
            $isOk = isset($q['correct']) && $ans === (int)$q['correct'];
            $isOk ? $correct++ : $wrong++;
        } elseif ($q['type'] === 'multi') {
            $ans = array_map('intval', (array)$_POST[$key]);
            sort($ans);
            $corr = array_map('intval', (array)$q['correct']);
            sort($corr);
            $isOk = ($ans === $corr);
            $isOk ? $correct++ : $wrong++;
        } else {
            $skipped++;
        }
    }
    $results['quiz'] = compact('correct', 'wrong', 'skipped');
}

// 2.1
if (req_str('action') === 't21') {
    $N = max(0, req_int('n21', 0));
    $results['t21'] = ['N' => $N, 'sum' => fib_sum_less_than($N)];
}

// 2.2
if (req_str('action') === 't22') {
    $M = min(100000, max(0, req_int('m22', 100000)));
    $list = armstrong_list($M);
    $results['t22'] = ['M' => $M, 'list' => $list];
}

// 2.3
if (req_str('action') === 't23') {
    $N = max(0, req_int('n23', 0));
    $list = automorphic_list($N);
    $results['t23'] = ['N' => $N, 'list' => $list];
}

// 2.4
if (req_str('action') === 't24') {
    $cfg = $TABULATIONS[$variant] ?? reset($TABULATIONS);
    // Разрешаем быстро переопределять a/b из формы (шаг фиксирован 0.1)
    $a = req_float('a24', $cfg['a']);
    $b = req_float('b24', $cfg['b']);
    $rows = tabulate($cfg['f'], $a, $b, 0.1);
    $results['t24'] = ['a' => $a, 'b' => $b, 'rows' => $rows, 'caption' => $cfg['caption']];
}

// 2.5
if (req_str('action') === 't25') {
    $year = req_int('y25', (int)date('Y'));
    $results['t25'] = ['year' => $year, 'isLeap' => is_leap_simple($year)];
}

// 3.1
if (req_str('action') === 't31') {
    $s = req_str('s31', '');
    $results['t31'] = ['in' => $s, 'out' => tevirp($s)];
}

// 3.2
if (req_str('action') === 't32') {
    $s = req_str('s32', '');
    $results['t32'] = ['in' => $s, 'isPal' => is_palindrome_str($s)];
}

// ----------------------- UI -----------------------
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>КР PHP — единый файл</title>
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 980px;
            margin: 0 auto;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 12px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin: 6px 0 4px;
        }

        input[type="number"],
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 6px 8px;
            box-sizing: border-box;
        }

        button {
            padding: 8px 12px;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            background: #f0f0f0;
            margin-left: 6px;
            font-size: 12px;
        }

        .ok {
            color: #087f23;
        }

        .err {
            color: #b00020;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        .small {
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>КР PHP — шаблон (единый файл)</h1>

        <form method="get" class="card">
            <label>Выберите вариант</label>
            <select name="variant" onchange="this.form.submit()">
                <?php foreach ($VARIANTS as $v => $_): ?>
                    <option value="<?= $v ?>" <?= $v === $variant ? 'selected' : '' ?>>Вариант <?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <div class="small">Задания для варианта: <?= h(implode(', ', $activeTasks)) ?></div>
            <noscript><button type="submit">Показать</button></noscript>
        </form>

        <?php if (in_array('1', $activeTasks, true)): ?>
            <div class="card" id="task1">
                <h2>Задание 1 — Моя викторина</h2>
                <form method="post">
                    <?php foreach ($QUIZ as $i => $q): ?>
                        <div style="margin-bottom:12px;">
                            <div><strong><?= ($i + 1) ?>.</strong> <?= h($q['q']) ?></div>
                            <?php if ($q['type'] === 'text'): ?>
                                <input type="text" name="q_<?= $i ?>" placeholder="Ваш ответ">
                            <?php elseif ($q['type'] === 'single'): ?>
                                <?php foreach ($q['options'] as $k => $opt): ?>
                                    <label><input type="radio" name="q_<?= $i ?>" value="<?= $k ?>"> <?= h($opt) ?></label>
                                <?php endforeach; ?>
                            <?php elseif ($q['type'] === 'multi'): ?>
                                <?php foreach ($q['options'] as $k => $opt): ?>
                                    <label><input type="checkbox" name="q_<?= $i ?>[]" value="<?= $k ?>"> <?= h($opt) ?></label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="quiz_submit">
                    <button type="submit">Проверить</button>
                </form>
                <?php if (isset($results['quiz'])): $r = $results['quiz']; ?>
                    <p>Верных: <span class="ok"><?= $r['correct'] ?></span>,
                        Неверных: <span class="err"><?= $r['wrong'] ?></span>,
                        Пропущено: <?= $r['skipped'] ?></p>
                    <div class="small">Совет: замените вопросы и ключи ответов в блоке $QUIZ вверху файла.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('2.1', $activeTasks, true)): ?>
            <div class="card" id="t21">
                <h2>Задание 2.1 — Сумма чисел Фибоначчи, меньших N</h2>
                <form method="post">
                    <label>N (натуральное)</label>
                    <input type="number" name="n21" min="1" value="<?= isset($_POST['n21']) ? h((string)$_POST['n21']) : '10' ?>">
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t21">
                    <button type="submit">Вычислить</button>
                </form>
                <?php if (isset($results['t21'])): $r = $results['t21']; ?>
                    <p>N = <span class="mono"><?= h((string)$r['N']) ?></span>, сумма = <span class="mono"><?= h((string)$r['sum']) ?></span></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('2.2', $activeTasks, true)): ?>
            <div class="card" id="t22">
                <h2>Задание 2.2 — Числа Армстронга (0..100000)</h2>
                <form method="post">
                    <label>Верхняя граница (≤ 100000)</label>
                    <input type="number" name="m22" min="0" max="100000" value="<?= isset($_POST['m22']) ? h((string)$_POST['m22']) : '100000' ?>">
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t22">
                    <button type="submit">Найти</button>
                </form>
                <?php if (isset($results['t22'])): $r = $results['t22']; ?>
                    <p>Найдено: <?= count($r['list']) ?></p>
                    <?php if ($r['list']): ?>
                        <div class="mono"><?= h(implode(', ', $r['list'])) ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('2.3', $activeTasks, true)): ?>
            <div class="card" id="t23">
                <h2>Задание 2.3 — Автоморфные числа ≤ N</h2>
                <form method="post">
                    <label>N</label>
                    <input type="number" name="n23" min="0" value="<?= isset($_POST['n23']) ? h((string)$_POST['n23']) : '1000' ?>">
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t23">
                    <button type="submit">Показать</button>
                </form>
                <?php if (isset($results['t23'])): $r = $results['t23']; ?>
                    <p>Найдено: <?= count($r['list']) ?></p>
                    <?php if ($r['list']): ?>
                        <div class="mono"><?= h(implode(', ', $r['list'])) ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('2.4', $activeTasks, true)): $cfg = $TABULATIONS[$variant] ?? reset($TABULATIONS); ?>
            <div class="card" id="t24">
                <h2>Задание 2.4 — Табулирование (шаг 0.1)</h2>
                <p class="small">Текущая формула: <span class="mono"><?= h($cfg['caption']) ?></span></p>
                <form method="post">
                    <div style="display:flex; gap:12px;">
                        <div style="flex:1;">
                            <label>a</label>
                            <input type="text" name="a24" value="<?= isset($_POST['a24']) ? h((string)$_POST['a24']) : h((string)$cfg['a']) ?>">
                        </div>
                        <div style="flex:1;">
                            <label>b</label>
                            <input type="text" name="b24" value="<?= isset($_POST['b24']) ? h((string)$_POST['b24']) : h((string)$cfg['b']) ?>">
                        </div>
                    </div>
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t24">
                    <button type="submit">Табулировать</button>
                </form>
                <?php if (isset($results['t24'])): $r = $results['t24']; ?>
                    <p>Диапазон: <span class="mono">[<?= h((string)$r['a']) ?>; <?= h((string)$r['b']) ?>]</span>, шаг 0.1</p>
                    <div style="max-height:320px; overflow:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>x</th>
                                    <th>y</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($r['rows'] as $row): ?>
                                    <tr>
                                        <td class="mono"><?= h((string)$row['x']) ?></td>
                                        <td class="mono"><?= h((string)$row['y']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="small">Подсказка: формулу/диапазоны изменяйте в $TABULATIONS вверху файла под ваш вариант.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('2.5', $activeTasks, true)): ?>
            <div class="card" id="t25">
                <h2>Задание 2.5 — Високосный год</h2>
                <form method="post">
                    <label>Год</label>
                    <input type="number" name="y25" value="<?= isset($_POST['y25']) ? h((string)$_POST['y25']) : date('Y') ?>">
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t25">
                    <button type="submit">Проверить</button>
                </form>
                <?php if (isset($results['t25'])): $r = $results['t25']; ?>
                    <p>Год <?= h((string)$r['year']) ?> — <?= $r['isLeap'] ? '<span class="ok">високосный</span>' : '<span class="err">не високосный</span>' ?></p>
                    <div class="small">Правило по условию: год делится без остатка на 4.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('3.1', $activeTasks, true)): ?>
            <div class="card" id="t31">
                <h2>Задание 3.1 — «Тевирп!» (латиница)</h2>
                <form method="post">
                    <label>Строка</label>
                    <input type="text" name="s31" value="<?= isset($_POST['s31']) ? h((string)$_POST['s31']) : 'Madam, I am Adam!' ?>">
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t31">
                    <button type="submit">Преобразовать</button>
                </form>
                <?php if (isset($results['t31'])): $r = $results['t31']; ?>
                    <p>Ввод: <span class="mono"><?= h($r['in']) ?></span></p>
                    <p>Вывод: <span class="mono"><?= h($r['out']) ?></span></p>
                    <div class="small">Замечание: проверка ориентирована на латиницу (ASCII); для кириллицы допускается упрощение.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array('3.2', $activeTasks, true)): ?>
            <div class="card" id="t32">
                <h2>Задание 3.2 — Палиндром строки</h2>
                <form method="post">
                    <label>Строка</label>
                    <input type="text" name="s32" value="<?= isset($_POST['s32']) ? h((string)$_POST['s32']) : 'madam' ?>">
                    <input type="hidden" name="variant" value="<?= $variant ?>">
                    <input type="hidden" name="action" value="t32">
                    <button type="submit">Проверить</button>
                </form>
                <?php if (isset($results['t32'])): $r = $results['t32']; ?>
                    <p>Ввод: <span class="mono"><?= h($r['in']) ?></span></p>
                    <p>Палиндром: <?= $r['isPal'] ? '<span class="ok">да</span>' : '<span class="err">нет</span>' ?></p>
                    <div class="small">Проверка ориентирована на латиницу/цифры; знаки и пробелы игнорируются.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>