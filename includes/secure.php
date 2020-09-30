<?php
/* This file contains:
 * Session and Cookies authentication,  Activate account check.
 */

session_start();

// Session and Cookies authentication - - - - - - - - - - - - - - - - - - - - -

// if session is empty
if (empty($_SESSION['user']['login']) OR $_SESSION['user']['login'] == null) {

    // if cookie is empty
    if (empty($_COOKIE['login']) OR empty($_COOKIE['token'])) {
        header('Location: index.php?empty_cookie');
    } else {

        // Make session
        try {
            $stmt = $pdo->prepare(SQL_MAKE_SESSION);
            $stmt->bindParam(':login', $_COOKIE['login']);
            $stmt->bindParam(':token', $_COOKIE['token']);
            $result = $stmt->execute();
            $user_count = $stmt->rowCount();

            if ($user_count > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['user'] = $user;  // Make session
            } else {
                // When cookies not find in DB
                header('Location: index.php?logout&wrong_cookie');
            }
        } catch (PDOException $e) {
            echo '= PDO EXCEPTION: =' . $e->getMessage();
        }
    }
    // todo Проверку соответствия токена на девайсе с токеном в БД. Для работы с разных девайсов. Наверно нужна ещё одна таблица для хранения токенов с привязкой к учётке.
} // End Session and Cookies authentication - - - - - - - - - - - - - - - - - -

// Activate account check
if ($_SESSION['user']['active'] == 0) {
    require_once 'includes/menu.php';
    ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1>Please activate your account</h1>
            <p><a href="./code_sender.php">Resend e-mail confirmation.</a></p>
        </div>
    </div>
    <?php
    require_once 'includes/footer.html';
    exit;
}