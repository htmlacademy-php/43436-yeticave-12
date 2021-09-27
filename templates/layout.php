<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>
        <?= htmlspecialchars($title) ?>
    </title>
    <link href="./css/normalize.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper">

        <!-- page content from templates/header.php -->
        <?= $pageHeader ?>

        <!-- page content from templates/main.php -->
        <?= $pageContent ?>

    </div>

    <!-- page content from templates/footer.php -->
    <?= $pageFooter ?>

    <script src="flatpickr.js"></script>
    <script src="script.js"></script>
</body>
</html>
