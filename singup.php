<?php
session_start();
if (isset($_SESSION['user']['id'])) {
    header('Location: user.php?user_id=' . $_SESSION['user']['id']);
}

require_once 'includes/db.php';


// если нажали кнопку Отмена то отправляем на страницу входа
if (isset($_POST['abort'])) header('Location: ./singin.php');


// если нажали кнопку Зарегистрировать, то ...
if (isset($_REQUEST['add_user'])) {
    try { // перехват исключений


        // переносим данные формы в массив
        $form_data = array(
            'role' => $_POST['role'],
            'full_name' => $_POST['full_name'],
            'login' => $_POST['login'],
            'email' => $_POST['email'],
            'password' => $_POST['password']
        );


        // солёное хеширование пароля
        try {
            $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
        } catch (Exception $e) {
            echo 'HASH ERROR: ' . $e->getMessage();
        }


        // Валидация
        $mail_verify = filter_var($form_data['email'], FILTER_VALIDATE_EMAIL);
        if ($mail_verify == false) header('Location: ./users.php?msg=email_err');;

        $stmt = $pdo->prepare(SQL_LOGIN); // prepare — Подготавливает SQL-запрос к выполнению
        $stmt->bindParam(':login', $form_data['login']); // bindParam — Привязывает параметр SQL-запроса к POST-переменной
        $result = $stmt->execute(); // execute — Запускает подготовленный запрос на выполнение, сохраняем результат
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // fetch() - возвращает массив данных.
        if (!$user) header('Location: ./users.php?msg=login_err');

        // Добавить данные в БД
        $stmt = $pdo->prepare(SQL_INSERT_USER); // подготавливаем запрос с данными
        $stmt->execute(array_values($form_data)); // и отправляем его на выполтениние MySQL серверу

        // открыть сессию
        $_SESSION['logged_user'] = $form_data; // ... и сохраняем данные пользователя в куки

        // отправка письма подтверждения
        try {
            $verify_code = random_bytes(20);
            $to = $form_data['email'];
            $subject = 'Подтверждение регистрации';
            $message = $verify_code;
            $headers = 'From: webmaster@goodman.com' . "\r\n" . 'Reply-To: webmaster@example.com';

            $mail_result = mail($to, $subject, $message, $headers);

        } catch (Exception $e) {
            echo 'EMAIL VERIFICATION ERROR:';
        }

        // проверка отправки письма
        if ($mail_result) {
            // перенаправить на список пользователей
            header('Location: ./users.php?msg=user_saved'); // перенаправляем на список пользователей
        } else echo 'Ошибка отправки письма с кодом подтверждения.';


    } catch (PDOException $e) {
        echo 'PDO ERROR: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
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
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit" form="add_user" name="add_user">Зарегистрировать</button>
        <button class="mdl-button mdl-js-button mdl-button--raised" type="reset" form="add_user">Очистить</button>
<!--        <button class="mdl-button mdl-js-button mdl-button--raised" type="submit" form="add_user" name="abort">Отмена</button>-->
        <a class="mdl-button mdl-js-button mdl-button--raised" href="users.php" name="abort">Отмена</a>
    </div>
</div>
<article class="mdl-grid main-content">
    <div class="mdl-cell mdl-cell--12-col">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="add_user" enctype="multipart/form-data">
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <select  class="mdl-textfield__input" name="role" id="role" required>
                    <option value="user" selected>user</option>
                    <option value="manager">manager</option>
                </select>
                <label class="mdl-textfield__label" for="role">Роль...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="full_name" name="full_name"
                       placeholder="<?= $form_data['full_name'] ?>" required>
                <label class="mdl-textfield__label" for="full_name">Имя...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="login" name="login"
                       placeholder="<?= $form_data['login'] ?>" required>
                <label class="mdl-textfield__label" for="login">Логин...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="email" name="email"
                       placeholder="<?= $form_data['email'] ?>"  required>
                <label class="mdl-textfield__label" for="email">Почта...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" id="password" name="password"
                       placeholder="<?= $form_data['password'] ?>"  required>
                <label class="mdl-textfield__label" for="password">Пароль...</label>
            </div>
            </p>
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
