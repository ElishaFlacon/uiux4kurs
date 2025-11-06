<?php
// index.php — ЛР4: массивы/строки, функции/процедуры, работа с файлами (всё в одном файле)

function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function posted($k, $d = '')
{
    return isset($_POST[$k]) ? (string)$_POST[$k] : $d;
}
$which = $_POST['form'] ?? '';

?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>ЛР4</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 24px;
            line-height: 1.5;
        }

        form {
            max-width: 980px;
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
        input[type=number],
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

        table.tbl,
        table.palette {
            border-collapse: collapse;
            margin: 8px 0;
        }

        table.tbl td,
        table.tbl th {
            border: 1px solid #888;
            padding: 6px 8px;
            text-align: center;
        }

        table.palette td {
            border: 1px solid #888;
            width: 64px;
            height: 36px;
            font: 12px/1 monospace;
            text-align: center;
        }

        ul {
            margin: 6px 0 0 20px;
        }

        code {
            background: #f0f0f0;
            padding: 1px 4px;
            border-radius: 4px;
        }
    </style> -->
</head>

<body>
    <h1>ЛР4</h1>

    <!-- 4.1.1 Опросник о погоде (массивы + html, checkbox v2) -->
    <form method="post" action="">
        <input type="hidden" name="form" value="411">
        <fieldset>
            <legend>Погода за месяц</legend>
            <div class="row-3">
                <div>
                    <label for="city">Город</label>
                    <input type="text" id="city" name="city" value="<?php echo h(posted('city')); ?>">
                </div>
                <div>
                    <label for="month">Месяц</label>
                    <input type="text" id="month" name="month" value="<?php echo h(posted('month')); ?>">
                </div>
                <div>
                    <label for="year">Год</label>
                    <input type="text" id="year" name="year" value="<?php echo h(posted('year')); ?>">
                </div>
            </div>

            <p>Отметьте погодные условия (можно несколько):</p>
            <?php
            $opts = ['Солнце', 'Облака', 'Дождь', 'Снег', 'Ветер', 'Холодно', 'Тепло'];
            $chosen = isset($_POST['weather']) && is_array($_POST['weather']) ? $_POST['weather'] : [];
            foreach ($opts as $o) {
                $chk = in_array($o, $chosen, true) ? 'checked' : '';
                echo '<label><input type="checkbox" name="weather[]" value="' . h($o) . '" ' . $chk . '> ' . h($o) . '</label>';
            }
            ?>

            <div class="actions">
                <button type="submit">Показать отчёт</button>
            </div>

            <?php if ($which === '411'): ?>
                <div class="result">
                    <?php
                    $city = trim((string)posted('city'));
                    $month = trim((string)posted('month'));
                    $year = trim((string)posted('year'));
                    echo '<p>В городе ' . h($city) . ' в месяце ' . h($month) . ' в году ' . h($year) . ' вы наблюдали следующую погоду:</p>';
                    echo '<ul>';
                    if (!empty($chosen)) {
                        foreach ($chosen as $w) echo '<li>' . h($w) . '</li>';
                    } else echo '<li>ничего не выбрано</li>';
                    echo '</ul>';
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- 4.1.2 Быстрая палитра (hex) -->
    <form method="post" action="">
        <input type="hidden" name="form" value="412">
        <fieldset>
            <legend>Быстрая палитра HEX («#RRGGBB»)</legend>
            <div class="actions">
                <button type="submit">Сгенерировать палитру</button>
            </div>

            <?php if ($which === '412'): ?>
                <div class="result">
                    <?php
                    $hex = str_split('0123456789ABCDEF');
                    $template = '#000000';
                    echo '<table class="palette"><tbody>';
                    for ($i = 1; $i <= 6; $i++) {
                        echo '<tr>';
                        foreach ($hex as $c) {
                            $col = $template;
                            $col[$i] = $c; // меняем один символ в #RRGGBB
                            // Цвет текста для читаемости
                            $rgb = hexdec(substr($col, 1, 2)) + hexdec(substr($col, 3, 2)) + hexdec(substr($col, 5, 2));
                            $txt = ($rgb > 3 * 128) ? '#000' : '#fff';
                            echo '<td style="background: ' . h($col) . '; color: ' . h($txt) . '">' . h($col) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- 4.2.1 Процедура вывода таблицы -->
    <form method="post" action="">
        <input type="hidden" name="form" value="421">
        <fieldset>
            <legend>Процедура построения таблицы</legend>
            <div class="row-2">
                <div>
                    <label for="cols">Столбцы</label>
                    <input type="number" id="cols" name="cols" min="1" max="50" value="<?php echo h(posted('cols', '4')); ?>">
                </div>
                <div>
                    <label for="rows">Строки</label>
                    <input type="number" id="rows" name="rows" min="1" max="50" value="<?php echo h(posted('rows', '3')); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit" name="make" value="1">Построить</button>
                <button type="submit" name="demo" value="1">Показать 2×2, 4×3, 6×4</button>
            </div>

            <?php
            function table_out(int $cols, int $rows)
            {
                echo '<table class="tbl"><tbody>';
                for ($r = 1; $r <= $rows; $r++) {
                    echo '<tr>';
                    for ($c = 1; $c <= $cols; $c++) {
                        echo '<td>' . ($r) . '×' . ($c) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }
            ?>

            <?php if ($which === '421'): ?>
                <div class="result">
                    <?php
                    if (isset($_POST['demo'])) {
                        table_out(2, 2);
                        table_out(4, 3);
                        table_out(6, 4);
                    } else {
                        $cols = max(1, min(50, (int)posted('cols', '4')));
                        $rows = max(1, min(50, (int)posted('rows', '3')));
                        table_out($cols, $rows);
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- 4.2.2 Рекурсивный факториал -->
    <form method="post" action="">
        <input type="hidden" name="form" value="422">
        <fieldset>
            <legend>Рекурсивная функция факториала</legend>
            <div class="row-2">
                <div>
                    <label for="n422">n (целое 0–20)</label>
                    <input type="number" id="n422" name="n" min="0" max="20" value="<?php echo h(posted('n', '5')); ?>">
                </div>
                <div>
                    <label for="out422">Результат</label>
                    <input type="text" id="out422" readonly value="<?php
                                                                    if ($which === '422') {
                                                                        $n = (int)posted('n', '5');
                                                                        if ($n < 0) echo 'n ≥ 0';
                                                                        else {
                                                                            function factorial_rec($k)
                                                                            {
                                                                                return $k === 0 ? 1 : $k * factorial_rec($k - 1);
                                                                            }
                                                                            echo (string)factorial_rec($n);
                                                                        }
                                                                    }
                                                                    ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit">Посчитать</button>
            </div>
        </fieldset>
    </form>

    <!-- 4.3 Работа с файлами (основная) -->
    <form method="post" action="">
        <input type="hidden" name="form" value="43">
        <fieldset>
            <legend>Работа с файлами (папка texts)</legend>
            <div class="row-2">
                <div>
                    <label for="dir">Папка</label>
                    <input type="text" id="dir" name="dir" value="<?php echo h(posted('dir', __DIR__ . DIRECTORY_SEPARATOR . 'texts')); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit" name="do" value="list">Показать файлы</button>
                <button type="submit" name="do" value="run">Выполнить</button>
            </div>

            <?php if ($which === '43'): ?>
                <div class="result">
                    <?php
                    $dir = posted('dir', __DIR__ . DIRECTORY_SEPARATOR . 'texts');
                    if (!is_dir($dir)) {
                        echo '<p class="err">Папка не найдена: ' . h($dir) . '</p>';
                    } else {
                        $names = array_values(array_filter(scandir($dir), fn($n) => $n !== '.' && $n !== '..'));
                        echo '<p>Найдено файлов: ' . count($names) . '</p>';
                        if (!empty($names)) {
                            echo '<ul>';
                            foreach ($names as $n) echo '<li>' . h($n) . '</li>';
                            echo '</ul>';
                        }

                        if (($_POST['do'] ?? '') === 'run') {
                            // Проходим по файлам один раз, собирая действия
                            $log = [];
                            foreach ($names as $n) {
                                $path = $dir . DIRECTORY_SEPARATOR . $n;
                                if (!is_file($path)) {
                                    $log[] = "пропущено (не файл): $n";
                                    continue;
                                }

                                // 2) удалить <10 КБ
                                $sz = @filesize($path);
                                if ($sz !== false && $sz < 10 * 1024) {
                                    if (@unlink($path)) {
                                        $log[] = "удалён (меньше 10 КБ): $n";
                                    } else {
                                        $log[] = "ошибка удаления (<10 КБ): $n";
                                    }
                                    continue; // уже удалён
                                }

                                // 3) удалить, если первая строка "to delete"
                                $fh = @fopen($path, 'r');
                                if ($fh) {
                                    $first = fgets($fh, 4096);
                                    fclose($fh);
                                    if ($first !== false && trim(mb_strtolower($first, 'UTF-8')) === 'to delete') {
                                        if (@unlink($path)) {
                                            $log[] = "удалён (первая строка 'to delete'): $n";
                                        } else {
                                            $log[] = "ошибка удаления по первой строке: $n";
                                        }
                                        continue;
                                    }
                                }

                                // 4) дописать "approved"
                                if (@file_put_contents($path, PHP_EOL . 'approved', FILE_APPEND) !== false) {
                                    $log[] = "дописано 'approved': $n";
                                } else {
                                    $log[] = "ошибка записи 'approved': $n";
                                }
                            }

                            echo '<p>Действия:</p><ul>';
                            foreach ($log as $line) echo '<li>' . h($line) . '</li>';
                            echo '</ul>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

    <!-- Самостоятельная: фильтрация/переименование файлов -->
    <form method="post" action="">
        <input type="hidden" name="form" value="43s">
        <fieldset>
            <legend>Самостоятельная — удаление и переименование</legend>
            <div class="row-2">
                <div>
                    <label for="dirS">Папка</label>
                    <input type="text" id="dirS" name="dirS" value="<?php echo h(posted('dirS', __DIR__ . DIRECTORY_SEPARATOR . 'texts')); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit" name="go" value="1">Выполнить</button>
            </div>

            <?php if ($which === '43s'): ?>
                <div class="result">
                    <?php
                    $dir = posted('dirS', __DIR__ . DIRECTORY_SEPARATOR . 'texts');
                    if (!is_dir($dir)) {
                        echo '<p class="err">Папка не найдена: ' . h($dir) . '</p>';
                    } else {
                        $names = array_values(array_filter(scandir($dir), fn($n) => $n !== '.' && $n !== '..'));
                        $log = [];
                        foreach ($names as $n) {
                            $path = $dir . DIRECTORY_SEPARATOR . $n;
                            if (!is_file($path)) {
                                $log[] = "пропущено (не файл): $n";
                                continue;
                            }

                            $startsDigit = preg_match('/^[0-9]/u', $n) === 1;
                            $sz = @filesize($path);
                            if ($startsDigit && $sz !== false && $sz > 20 * 1024) {
                                if (@unlink($path)) {
                                    $log[] = "удалён (имя с цифры и >20КБ): $n";
                                } else {
                                    $log[] = "ошибка удаления: $n";
                                }
                                continue;
                            }

                            // Переименование: добавить '+' в начало имени, не трогая расширение
                            $pi = pathinfo($path);
                            $base = $pi['filename'] ?? $n;
                            $ext  = isset($pi['extension']) && $pi['extension'] !== '' ? '.' . $pi['extension'] : '';
                            if (str_starts_with($base, '+')) {
                                $log[] = "пропущено (уже имеет '+'): $n";
                                continue;
                            }
                            $newBase = '+' . $base;
                            $newPath = $pi['dirname'] . DIRECTORY_SEPARATOR . $newBase . $ext;
                            // Избежать коллизии
                            $suffix = 1;
                            while (file_exists($newPath)) {
                                $newPath = $pi['dirname'] . DIRECTORY_SEPARATOR . $newBase . "_$suffix" . $ext;
                                $suffix++;
                            }
                            if (@rename($path, $newPath)) {
                                $log[] = "переименован: $n → " . basename($newPath);
                            } else {
                                $log[] = "ошибка переименования: $n";
                            }
                        }

                        echo '<p>Действия:</p><ul>';
                        foreach ($log as $line) echo '<li>' . h($line) . '</li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>

</body>

</html>