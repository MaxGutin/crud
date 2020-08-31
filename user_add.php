<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/validate.php';
// todo add validation
if (isset($_REQUEST['add-user'])) {

    // переносим данные формы в массив
    $form_data = [
        'full_name' => $_POST['full_name'],
        'login' => $_POST['login'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];


// Validation
    $form_data = clean($form_data); // clean() locate in validate.php
// Validation end

    // Finding matches in DB
    try {
        // Preparation
        $stmt = $pdo->prepare(SQL_LOGIN);                              // prepare — Подготавливает SQL-запрос к выполнению
        $stmt->bindParam(':login', $_POST['login']);      // bindParam — Привязывает значение переменной к параметру SQL-запроса
        $result = $stmt->execute();                                             // execute — выполняет подготовленный запрос и возвращает результат
        $user_count = $stmt->rowCount();


        if ($user_count > 0 ) {                                                 // если логин не найден
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
    <title>Новый пользователь</title>
    <?php include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.html' ?>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1>Новый пользователь</h1>
    </div>
</div>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit" form="add_user" name="add-user">Сохранить</button>
        <button class="mdl-button mdl-js-button mdl-button--raised" type="reset" form="add_user">Очистить</button>
        <a      class="mdl-button mdl-js-button mdl-button--raised" href="users.php">Отмена</a>
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
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
