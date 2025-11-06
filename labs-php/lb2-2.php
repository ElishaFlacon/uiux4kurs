<?php
// samost13.php — Самостоятельная, вариант 13
// Формула: a = tan( (x + y)^2 - sqrt( cos^2(z) / tan^2(z) ) )

function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function parse_num(?string $raw): array
{
    if ($raw === null || $raw === '') return [null, 'Значение не передано'];
    $s = str_replace(',', '.', trim($raw));
    if (!is_numeric($s)) return [null, 'Введите число'];
    return [floatval($s), null];
}

$submitted = ($_SERVER['REQUEST_METHOD'] === 'GET') && (isset($_GET['go']) || isset($_GET['preset']));
if (isset($_GET['preset'])) {
    $_GET['x'] = '5';
    $_GET['y'] = '6';
    $_GET['z'] = '7';
    $submitted = true;
}
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>ЛР2 Вариант 13</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 24px;
            line-height: 1.5;
        }

        form {
            max-width: 820px;
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fafafa;
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
        }

        legend {
            padding: 0 6px;
        }

        label {
            display: block;
            margin: 8px 0 4px;
        }

        input[type=text] {
            width: 100%;
            padding: 8px;
            border: 1px solid #bbb;
            border-radius: 6px;
        }

        .row {
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
    <h1>ЛР2 Вариант 13</h1>

    <form method="get" action="">
        <fieldset>
            <legend>Ввод данных</legend>
            <div class="row">
                <div>
                    <label for="x">x</label>
                    <input type="text" id="x" name="x" value="<?php echo h($_GET['x'] ?? ''); ?>">
                </div>
                <div>
                    <label for="y">y</label>
                    <input type="text" id="y" name="y" value="<?php echo h($_GET['y'] ?? ''); ?>">
                </div>
                <div>
                    <label for="z">z</label>
                    <input type="text" id="z" name="z" value="<?php echo h($_GET['z'] ?? ''); ?>">
                </div>
            </div>
            <div class="actions">
                <button type="submit" name="go" value="1">Вычислить</button>
                <button type="submit" name="preset" value="1">Подставить x=5, y=6, z=7</button>
            </div>
        </fieldset>

        <?php if ($submitted): ?>
            <div class="result">
                <?php
                [$x, $ex] = parse_num($_GET['x'] ?? null);
                [$y, $ey] = parse_num($_GET['y'] ?? null);
                [$z, $ez] = parse_num($_GET['z'] ?? null);

                $errs = [];
                if ($ex) $errs[] = "x: $ex";
                if ($ey) $errs[] = "y: $ey";
                if ($ez) $errs[] = "z: $ez";

                if (!$errs) {
                    // ODЗ:
                    // 1) tan(z) в знаменателе: tan(z) ≠ 0
                    // 2) tan(z) определена: cos(z) ≠ 0 (исключаем точки π/2 + kπ)
                    $cosZ = cos($z);
                    $tanZ = tan($z);
                    if (abs($cosZ) < 1e-12) $errs[] = 'ODЗ: tan(z) не определена при cos(z)=0';
                    if (abs($tanZ) < 1e-12) $errs[] = 'ODЗ: деление на ноль — tan(z)=0';

                    // 3) Внешняя tan(...) определена: cos(inner) ≠ 0 (по желанию можно ослабить)
                }

                if ($errs) {
                    echo '<ul class="err">';
                    foreach ($errs as $er) echo '<li>' . h($er) . '</li>';
                    echo '</ul>';
                } else {
                    $cosZ = cos($z);
                    $tanZ = tan($z);
                    $ratio = (pow($cosZ, 2)) / (pow($tanZ, 2));         // cos^2(z) / tan^2(z)
                    $inner = pow($x + $y, 2) - sqrt($ratio);            // (x+y)^2 - sqrt(...)
                    if (abs(cos($inner)) < 1e-12) {
                        echo '<p class="err">ODЗ: tan(inner) не определена, cos(inner)=0</p>';
                    } else {
                        $a = tan($inner);                                  // tan(...)
                        $res = round($a, 2);                               // округление до 2 знаков
                        echo '<p>Результат a: ' . h((string)$res) . '</p>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </form>
</body>

</html>