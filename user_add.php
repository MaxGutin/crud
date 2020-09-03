<?php
/*
 * Check session
 * Ask user's full_name, login, email, password
 * update user data, redirect to profile
 * */
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/validate.php';

if (isset($_REQUEST['add-user'])) {

    $form_data = [
        'full_name' => $_POST['full_name'],
        'login' => $_POST['login'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    // Validation
    if ($form_data['password'] !== $_POST['password_confirm']) exit('The entered passwords do not match.');

    $form_data = clean($form_data); // clean() locate in validate.php

    // проверка на пустые значения
    if(empty($form_data['full_name']) OR empty($form_data['login']) OR empty($form_data['email']) OR empty($form_data['password'])) {
        exit('Заполните все значения.');
    }

    // валидация эл. почты
    $email_validate = filter_var($form_data['email'], FILTER_VALIDATE_EMAIL);


    // проверка длинны данных
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
    // Validation end

    // Finding matches in DB
    try {
        // Preparation
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $_POST['login']);
        $result = $stmt->execute();
        $user_count = $stmt->rowCount();


        if ($user_count > 0 ) {                                                 // если логин найден
            exit('Пользователь с таким логином уже существует!');               // завершаем работу скрипта
        }


        $stmt = $pdo->prepare(SQL_INSERT_USER);
        $stmt->execute(array_values($form_data));
        header('Location: ./users.php?msg=user_saved');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>New User</title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.php' ?>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1>New User</h1>
    </div>
</div>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit" form="add_user" name="add-user">Save</button>
        <button class="mdl-button mdl-js-button mdl-button--raised" type="reset" form="add_user">Clean</button>
        <a      class="mdl-button mdl-js-button mdl-button--raised" href="users.php">Cancel</a>
    </div>
</div>
<article class="mdl-grid main-content">
    <div class="mdl-cell mdl-cell--12-col">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="add_user">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <select  class="mdl-textfield__input" name="role" id="role" required>
                    <option value="admin">admin</option>
                    <option value="manager">manager</option>
                    <option value="user">user</option>
                </select>
                <label class="mdl-textfield__label" for="role">role</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="full_name" name="full_name" required>
                <label class="mdl-textfield__label" for="full_name">name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="email" id="email" name="email" required>
                <label class="mdl-textfield__label" for="email">e-mail</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="login" name="login" required>
                <label class="mdl-textfield__label" for="login">login</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" id="password" name="password" required>
                <label class="mdl-textfield__label" for="password">password</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" id="password_confirm" name="password_confirm" required>
                <label class="mdl-textfield__label" for="password_confirm">password</label>
            </div>
            <br>
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
