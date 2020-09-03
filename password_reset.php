<?php
/*
 *
*/

require_once 'includes/db.php';
require_once 'includes/validate.php';

if (isset($_POST['do-reset-pass'])) {    // проверка нажатия кнопки ВХОД

    // Validation
    $_POST['email'] = clean($_POST['email']); // clean() locate in validate.php

    // Finding matches in DB
    try {
        // Preparation
        $stmt = $pdo->prepare(SQL_EMAIL);
        $stmt->bindParam(':email', $_POST['email']);
        $result = $stmt->execute();
        $user_count = $stmt->rowCount();

        // Check
        if ($user_count > 0) {                                                // if count of found rows more than one,
            $user = $stmt->fetch(PDO::FETCH_ASSOC);                  // extract data

            try {
                $verify_code = $user['password'];
                $verify_url = $_SERVER['HTTP_HOST'] . '/password_changer.php?token=' . $verify_code;

                $to = $user['email'];
                $subject = 'Reset Password';
                $message = 'Для создания нового пароля перейдите по ссылке — ' . $verify_url;
                $headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=utf-8\r\n" . "From: webmaster@gutin.com\r\n" . "Reply-To: webmaster@gutin.com\r\n";
                $mail_result = mail($to, $subject, $message, iconv ('utf-8', 'windows-1251', $headers));
            } catch (Exception $e) {
                echo '=== EMAIL VERIFICATION ERROR: (password_reset.php) === ' . $e->getMessage();
            }

        } else echo "Wrong e-mail.";

    } catch (PDOException $e) {
        echo '====CATCH=====: ' . $e->getMessage();
    }

    // проверка отправки письма
    if ($mail_result) {
        echo 'E-mail was send.';
    } else echo 'Send error.';


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset password</title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.php' ?>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <h1 class="mdl-typography--text-center">Reset password</h1>
            </div>
        </div>
        <article class="mdl-grid main-content">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Enter your e-mail</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="email" id="email" name="email">
                                <label class="mdl-textfield__label" for="email">e-mail</label>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                                        type="submit" name="do-reset-pass">submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
<?php include_once 'includes/footer.html' ?>