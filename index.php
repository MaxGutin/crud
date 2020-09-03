<?php
/* Logout function,
 *
 */
session_start();
// Logout function
function logout()
{
    $_SESSION = array();
    unset($_SESSION[session_name()]);
    unset($_COOKIE[session_name()]);
    session_destroy();
    header('Location: index.php?left');
}
if (isset($_REQUEST['logout'])) {
    logout();
}

// перенаправление на страницу профиля при активной сессии
if (isset($_SESSION['user']['login'])) {
    header('Location: user.php?user=' . $_SESSION['user']['login']);
}

require_once 'includes/db.php';
require_once 'includes/validate.php';

if (isset($_POST['do-login'])) {    // проверка нажатия кнопки ВХОД

    // переносим данные формы в массив
    $form_data = array(
        'login' => $_POST['login'],
        'password' => $_POST['password']
    );

    // Validation
    $form_data = clean($form_data); // clean() locate in validate.php



    // Finding matches in DB
    try {
        // Preparation
        $stmt = $pdo->prepare(SQL_LOGIN);                         // prepare — preparation SQL-request to perform.
        $stmt->bindParam(':login', $form_data['login']); // bindParam — associate SQL-request and POST-variable.
        $result = $stmt->execute();                                       // execute — perform prepare request.
        $user_count = $stmt->rowCount();                                  // rowCount() - returns the number of rows.

        // Check
        if ($user_count > 0) {                                                // if count of found rows more than one,
            $user = $stmt->fetch(PDO::FETCH_ASSOC);                  // extract data,

            if (password_verify($form_data['password'], $user['password'])) { // and check password.
                $_SESSION['user'] = $user;                                    // If all right create session with user data,
                header('Location: tasks.php');    // and redirect to profile page.
            } else echo "Wrong password.";
        } else echo "Wrong login.";

    } catch (PDOException $e) {
        echo '====CATCH=====: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sing In</title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.php' ?>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <h1 class="mdl-typography--text-center">PHP CRUD</h1>
            </div>
        </div>
        <article class="mdl-grid main-content">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Login</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="login" name="login">
                                <label class="mdl-textfield__label" for="login">login</label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="password" id="password" name="password">
                                <label class="mdl-textfield__label" for="password">password</label>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                                        type="submit" name="do-login">LOG IN</button>
                                <a href="sing_up.php" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                    SING UP</a>
                                <br>
                                <br>
                                <a href="password_reset.php">Password recovery</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
<?php include_once 'includes/footer.html' ?>