<?php
/* Requested data:
 *      full_name, login, email, password, password_confirm
 * Loaded data:
 *      login, email
 * Constants:
 *      SQL_LOGIN, SQL_EMAIL, SQL_INSET_USER
 * Features:
 *      Redirect if session is active, Validation,
 *      Checking an existing login and e-mail,
 *      Password hashing, Verification code generation,
 *      Addition new user data to DB, Session initialize,
 *      Redirect to script of send verification code to user's e-mail
 */

session_start();
// Redirect if session is active
if (isset($_SESSION['user']['login'])) {
    header('Location: user.php?user=' . $_SESSION['user']['login']);
}

require_once 'includes/db.php';
require_once 'includes/validate.php';

if (isset($_POST['add-user'])) {

    // Validation  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $form_data = [
        'full_name' => $_POST['full_name'],
        'login' => $_POST['login'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    // Checking if the entered passwords match
    if ($form_data['password'] !== $_POST['password_confirm']) exit('Введённые пароли не совпали.');

    $form_data = clean($form_data); // clean() locate in validate.php

    // Checking for empty values
    if(empty($form_data['full_name']) OR empty($form_data['login'])
        OR empty($form_data['email']) OR empty($form_data['password'])) {
        exit('Заполните все значения.');
    }

    // E-mail validation
    $email_validate = filter_var($form_data['email'], FILTER_VALIDATE_EMAIL);

    // Data length check
    if (!check_length($form_data['full_name'], 2, 255)) {
        exit('Name long must be between 2 and 255 characters.');
    }
    if (!check_length($form_data['login'], 2, 64)) {
        exit('Login long must be between 2 and 64 characters.');
    }
    if (!check_length($form_data['password'], 2, 64)) {
        exit('Password long must be between 2 and 255 characters.');
    }
    if (!$email_validate) {
        exit('Enter correct e-mail.');
    }
    // End Validation - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    try {
        // Checking an existing login
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $form_data['login']);
        $stmt->execute();
        $user_count = $stmt->rowCount();
        if ($user_count > 0 ) {
            exit('Login already exists!');
        }

        // Checking an existing e-mail
        $stmt = $pdo->prepare(SQL_EMAIL);
        $stmt->bindParam(':email', $form_data['email']);
        $stmt->execute();
        $user_count = $stmt->rowCount();
        if ($user_count > 0 ) {
            exit('E-mail already exists.');
        }

        // Password hashing
        try {
            $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
        } catch (Exception $e) {
            echo 'HASHING ERROR: ' . $e->getMessage();
        }

        // Verification code generation
        $verify_code = md5(random_bytes(20));
        $form_data['verify_code'] = $verify_code;

        // Addition new user data to DB
        $stmt = $pdo->prepare(SQL_INSERT_USER);
        $stmt->execute(array_values($form_data));

        // Cookie - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // make token
        $token = md5(random_bytes(20));
        // Addition new user's token to DB
        $stmt = $pdo->prepare(SQL_NEW_TOKEN);
        $stmt ->bindParam(':login', $form_data['login']);
        $stmt ->bindParam(':token', $token);
        $result = $stmt->execute();
        // make cookie
        setcookie('login', $form_data['login'], time()+60*60*24*7); // time to leave login - 7 days
        setcookie('token', $token, time()+60*60*24*7); // time to live token - 7 days
        // Make session
        // Session initialize
        $_SESSION['user'] = $form_data;

        // Send verification code to user's e-mail
        header('Location: code_sender.php');


    } catch (PDOException $e) {
        echo '= PDO EXCEPTION: =' . $e->getMessage();
    } catch (Exception $e) {
        echo '= EXCEPTION: =' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
<?php include_once 'includes/statistics.html' ?>
<?php include_once 'includes/menu.php' ?>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--12-col">
                    <h1>Registration</h1>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--12-col">
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit" form="add_user" name="add-user">Sing Up</button>
                    <button class="mdl-button mdl-js-button mdl-button--raised" type="reset" form="add_user">Clean</button>
                    <a class="mdl-button mdl-js-button mdl-button--raised" href="index.php">Cancel</a>
                </div>
            </div>

            <article class="mdl-grid main-content">
                <div class="mdl-cell mdl-cell--12-col">

                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="add_user">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="full_name" name="full_name" required>
                            <label class="mdl-textfield__label" for="full_name">Name</label>
                        </div>
                        <br>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="login" name="login" required>
                            <label class="mdl-textfield__label" for="login">Login</label>
                        </div>
                        <br>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="email" id="email" name="email" required>
                            <label class="mdl-textfield__label" for="email">E-mail</label>
                        </div>
                        <br>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="password" name="password" required>
                            <label class="mdl-textfield__label" for="password">Password</label>
                        </div>
                        <br>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="password_confirm" name="password_confirm" required>
                            <label class="mdl-textfield__label" for="password_confirm">Password confirm</label>
                        </div>
                        <br>
                    </form>

                </div>
            </article>
<?php include_once 'includes/footer.html' ?>
