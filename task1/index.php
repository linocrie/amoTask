<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Загрузка файла</title>
</head>
<body>

<div class="container">
    <h2>Загрузите текстовый файл (.txt)</h2>
    
    <form action="file_upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" accept=".txt" required>
        <button type="submit">Загрузить</button>
    </form>

    <?php if (isset($_GET['status'])): ?>
        <div class="status <?= $_GET['status'] == 'success' ? 'success' : ($_GET['status'] == 'invalid_file' ? 'error' : 'error') ?>"></div>
        <?php if ($_GET['status'] == 'invalid_file'): ?>
            <p class="error-message">Ошибка: Можно загружать только файлы .txt</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['content'])): ?>
        <div class="file-content">
            <?php
            foreach ($_SESSION['content'] as $line) {
                preg_match_all('/\d/', $line, $matches);
                echo "<p>$line = " . count($matches[0]) . " цифр</p>";
            }

            // Очищаем сессию после использования
            unset($_SESSION['content']);
            ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
