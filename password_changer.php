<?php
/*
 * Check token, ask new password, update password, start session, redirect to profile
 * */
session_start();
require_once 'includes/db.php';
require_once 'includes/validate.php';

if ($_REQUEST['token']) {

    try {
        // Preparation
        $stmt = $pdo->prepare(SQL_PASSWORD);
        $stmt->bindParam(':password', $_REQUEST['token']);
        $result = $stmt->execute();
        $user_count = $stmt->rowCount();

        // Check
        if ($user_count < 1) {
            exit('Wrong token!');
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // extract data
        $_SESSION['user'] = $user;
    } catch (PDOException $e) {
        echo '====CATCH=====: ' . $e->getMessage();
    }
}

if (isset($_POST['update_password'])) {

    // Validation
    $_POST['password'] = clean($_POST['password']); // clean() locate in validate.php

    if ($_POST['password'] == $_POST['password_confirm']) {
        // password hashing
        try {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } catch (Exception $e) {
            echo '== HASH ERROR: == ' . $e->getMessage();
        }

        $_SESSION['user']['password'] = $_POST['password'];

        // update DB
        $stmt = $pdo->prepare(SQL_UPDATE_UPASSWORD);
        $stmt->bindParam(':password',   $_POST['password']);
        $stmt->bindParam(':login',      $_SESSION['user']['login']);
        $stmt->execute();
        header('Location: ./user.php?user=' . $_SESSION['user']['login']);
    }

}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include_once 'includes/statistics.html' ?>
    <title><?php echo($user['full_name']); ?></title>
    <?php include_once 'includes/menu.php' ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1><?php echo($user['full_name']); ?></h1>
        </div>
    </div>

    <div class="mdl-grid" id="buttons">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                form="edit_password" type="submit" name="update_password">Save</button>
            <a class="mdl-button mdl-js-button mdl-button--raised"
                href="user.php?user=<?php echo($user['login'])?>">Cancel</a>
        </div>
    </div>
    <article class="mdl-grid main-content">
        <div class="mdl-cell mdl-cell--12-col">
            <form action="" method="post" id="edit_password">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="password"
                           name="password">
                    <label class="mdl-textfield__label" for="password">Password</label>
                </div>
                <br>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="password_confirm"
                           name="password_confirm">
                    <label class="mdl-textfield__label" for="password_confirm">Password confirm</label>
                </div>
                <br>
            </form>
        </div>
    </article>
    <?php include_once 'includes/footer.html' ?>
