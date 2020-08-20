<?php
require_once 'includes/login.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <?php //include_once 'includes/statistics.html' ?>
    <title>Авторизация</title>
    <meta charset="UTF-8">
    <meta name="author" content="Максим Гутин">
    <meta name="description" content="Портфолио веб-разработчика. HTML, CSS, PHP, MySQL.">
    <meta name="keywords" content="максим гутин, веб-разработчик, html, css, php, mysql">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/material.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title"></span>
            <div class="mdl-layout-spacer"></div>
            <nav class="mdl-navigation mdl-layout--large-screen-only">
                <a class="mdl-navigation__link" href="/">На главную</a>
                <a class="mdl-navigation__link" href="users.php">Пользователи</a>
            </nav>
        </div>
    </header>

    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">PHP CRUD DEMO</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="../..">На главную</a>
            <a class="mdl-navigation__link" href="users.php">Пользователи</a>
        </nav>
    </div>
    <main class="mdl-layout__content">
    <div class="page-content">
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <h1 class="mdl-typography--text-center">PHP CRUD DEMO</h1>
            </div>
        </div>
        <article class="mdl-grid main-content">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Авторизация</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="login" name="login">
                                <label class="mdl-textfield__label" for="login">Логин...</label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="password" id="password" name="password">
                                <label class="mdl-textfield__label" for="password">Пароль...</label>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                                        type="submit" name="do-login">
                                    ВХОД
                                </button>
                                <a href="singup.php" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                    регистрация
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
<?php include_once 'includes/footer.html' ?>