<?php
/*
 * Requested data:
 *      login, password
 * Loaded data:
 *      id, login, password, role, full_name, login, email, password, verify_code
 * Constants:
 *      SQL_LOGIN
 * Features:
 *      Logout, Redirect if session is active,
 *      Validation, Login, Session initialize,
 *      Redirect.
 */

session_start();
// Logout function
function logout()
{
    $_SESSION = array();
    unset($_SESSION[session_name()]);
    session_destroy();
    unset($_COOKIE['login']);
    unset($_COOKIE['token']);
    setcookie('login', '', time()-3600);
    setcookie('token', '', time()-3600);
    header('Location: index.php?left');
}

if (isset($_REQUEST['logout'])) {
    logout();
}

// Redirect if session is active
if (isset($_SESSION['user']['login']) OR isset($_COOKIE['login'])) {
    header('Location: tasks.php');
}

require_once 'includes/db.php';
require_once 'includes/validate.php';

if (isset($_POST['do-login'])) {

    $form_data = array(
        'login' => $_POST['login'],
        'password' => $_POST['password']
    );

    // Validation
    $form_data = clean($form_data); // clean() locate in validate.php

    // Login
    try {
        $stmt = $pdo->prepare(SQL_LOGIN);                         // prepare — preparation SQL-request to perform.
        $stmt->bindParam(':login', $form_data['login']); // bindParam — associate SQL-request and variable.
        $result = $stmt->execute();                                       // execute — perform prepare request.
        $user_count = $stmt->rowCount();                                  // rowCount() - returns the number of rows.

        // Check
        if ($user_count > 0) {                                             // if count of found rows more than one,
            $user = $stmt->fetch(PDO::FETCH_ASSOC);               // extract data,

            if (password_verify($form_data['password'], $user['password'])) { // and check password.

                // Cookie - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                // make token
                $user['token'] = md5(random_bytes(20));
                // Addition new user's token to DB
                $stmt = $pdo->prepare(SQL_NEW_TOKEN);
                $stmt ->bindParam(':login', $user['login']);
                $stmt ->bindParam(':token', $user['token']);
                $result = $stmt->execute();
                // make cookie
                setcookie('login', $user['login'], time()+60*60*24*7); // time to leave login - 7 days
                setcookie('token', $user['token'], time()+60*60*24*7); // time to live token - 7 days
                // Cookie end - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

                // Make session
                $_SESSION['user'] = $user;              // If all right, create session with user data,
                header('Location: tasks.php');    // and redirect to task list page.

            } else echo "Wrong login or password.";

        } else echo "Wrong login or password.";

    } catch (PDOException $e) {
        echo '= PDO EXCEPTION: =' . $e->getMessage();
    } catch (Exception $e) {
        echo '= EXCEPTION: =' . $e->getMessage(); // for random_bytes()
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sing In</title>
<?php include_once 'includes/statistics.html' ?>
<?php include_once 'includes/menu.php' ?>
        <article class="mdl-grid main-content">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Login</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="login" name="login" autofocus>
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