<?php
/* This file contains:
 * Session authentication,  Activate account check.
 */

session_start();

// проверяем что сессия принадлежит пользователю
if ($_SERVER['PHP_SELF'] != '/index.php' AND $_SERVER['PHP_SELF'] != '/sing_up.php') {  // проверяем что мы не на странице авторизации или регистрации

    if (empty($_SESSION['user']['login']) OR $_SESSION['user']['login'] == null) header('Location: index.php?empty_session');     // если сессия отсутствует

    // Finding matches in DB
    try {
        // Preparation
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $_SESSION['user']['login']);
        $result = $stmt->execute();
        $user_count = $stmt->rowCount();

        // Check
        if ($user_count > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);                // если пользователь найден
            if ($_SESSION['user']['password'] !== $user['password']) {      // если пароли не совпадают, то ...
                header('Location: index.php?log-err');                // отправляем на авторизацию
            }

            // Activate account check
            if ($user['active'] == 0) {
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
        }

    } catch (PDOException $e) {
        echo '=== SESSION EXCEPTION ===  ' . $e->getMessage();
    }
}
