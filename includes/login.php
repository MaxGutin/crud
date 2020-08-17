<?php
session_start();
if (isset($_SESSION['user']['id'])) {
    header('Location: user.php?user_id=' . $_SESSION['user']['id']);
}

require_once 'includes/db.php';     // подключение к БД


if (isset($_POST['do-login'])) {    // проверка нажатия кнопки ВХОД

// Вход
    $form_data = array(                 // Вытаскиваем данные из формы в массив, для валидации
        'login' => $_POST['login'],
        'password' => $_POST['password']
    );
// ищем совпадение данных формы и БД
    try {
        $stmt = $pdo->prepare(SQL_LOGIN);                        // prepare — Подготавливает SQL-запрос к выполнению
        $stmt->bindParam(':login', $form_data['login']); // bindParam — Привязывает параметр SQL-запроса к POST-переменной
        $result = $stmt->execute();                                       // execute — Запускает подготовленный запрос на выполнение, сохраняем результат
        $user_count = $stmt->rowCount();                                  // rowCount() - возвращает количество строк, нужно для проверки.
        $user = $stmt->fetch(PDO::FETCH_ASSOC);                  // rowCount() - возвращает массив данных.
    } catch (PDOException $e) {                                           // выводим ошибки PDO (работы с БД)
        echo '====CATCH=====: ' . $e->getMessage();
    }
    if ($user_count > 0) {                                                // найдена ли строка с искомым login в БД
        if (password_verify($form_data['password'], $user['password'])) { // если пароли сопадают то выполняем авторизацию
            $_SESSION['user'] = $user;                                    // создаём сессию с именем 'logged_user' и сохраняем там данные пользователя
            header('Location: user.php?user_id=' . $user['id']);    // перенаправляем на страницу пользователя
        } else echo "Не правильный пароль.";                              // если не совпадает пароль то вывести сообщение
    } else echo "Не правильный логин.";                                   // если пользователь не найден то выводим сообщение
}