<?php
session_start();
if (isset($_SESSION['user']['id'])) {
    header('Location: user.php?user_id=' . $_SESSION['user']['id']);
}

require_once 'includes/db.php';


// если нажали кнопку Отмена то отправляем на страницу входа
if (isset($_POST['abort'])) header('Location: ./index.php');


// если нажали кнопку Зарегистрировать, то ...
if (isset($_REQUEST['add_user'])) {
    try { // перехват исключений



        // Проверка совпадения введёных паролей
        if ($_POST['password'] !== $_POST['password_confirm']) { // Пароли совпадают
            exit('Введённые пароли не совпали.');
        }

        // переносим данные формы в массив
        $form_data = array(
            'full_name' => $_POST['full_name'],
            'login' => $_POST['login'],
            'email' => $_POST['email'],
            'password' => $_POST['password']
        );


// Валидация

        // функция очистки данных от тегов
        function clean($value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            return $value;
        }

        // пропускаем массив через функцию очистки
        foreach ($form_data as &$value) {
            $form_data[$value] = clean($form_data[$value]);
        }

        // проверка на пустые значения
        if(empty($form_data['full_name']) OR empty($form_data['login']) OR empty($form_data['email']) OR empty($form_data['password'])) {
            exit('Заполните все значения.');
        }

        // валидация эл. почты
        $email_validate = filter_var($form_data['email'], FILTER_VALIDATE_EMAIL);

        // Проверка длинны
        function check_length($value, $min, $max) {
            $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
            return !$result;
        }

        if (check_length($form_data['full_name'], 1, 255) OR check_length($form_data['login'], 1, 50) OR check_length($form_data['password'], 1, 64) OR $email_validate) {
            exit('Длинна введённых данных не соответствует требованиям.');
        }
        // Если валидация пройдена выполняем ется код ниже


        // проверка на занят ли логин
        $stmt = $pdo->prepare(SQL_LOGIN);                            // prepare — Подготавливает SQL-запрос к выполнению
        $stmt->bindParam(':login', $form_data['login']);    // bindParam — Привязывает значение переменной к параметру SQL-запроса
        $result = $stmt->execute();                                           // execute — выполняет подготовленный запрос и возвращает результат
        $user_count = $stmt->rowCount();


        if ($user_count < 1) {                                                // если логин не найден
            $user = $stmt->fetch(PDO::FETCH_ASSOC);                  // fetch() - возвращает массив данных.


            // солёное хеширование пароля
            try {
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
            } catch (Exception $e) {
                echo 'HASH ERROR: ' . $e->getMessage();
            }


            // Добавить данные в БД
            $stmt = $pdo->prepare(SQL_INSERT_USER);        // подготавливаем запрос с данными
            $stmt->execute(array_values($form_data));               // и отправляем его на выполтениние MySQL серверу


            // открыть сессию
            $_SESSION['user'] = $form_data;                         // ... и сохраняем данные пользователя в сессию


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


        } else echo 'Пользователь с таким логином уже существует!';




    } catch (PDOException $e) {
        echo 'PDO ERROR: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Новый пользователь</title>
    <?php //include_once 'includes/statistics.html' ?>
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

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="add_user" enctype="multipart/form-data">
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="full_name" name="full_name" required>
                <label class="mdl-textfield__label" for="full_name">Имя...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="login" name="login" required>
                <label class="mdl-textfield__label" for="login">Логин...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="email" id="email" name="email" required>
                <label class="mdl-textfield__label" for="email">Почта...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" id="password" name="password" required>
                <label class="mdl-textfield__label" for="password">Пароль...</label>
            </div>
            </p>
            <p>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" id="password_confirm" name="password_confirm" required>
                <label class="mdl-textfield__label" for="password_confirm">Подтверждение пароля...</label>
            </div>
            </p>
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
