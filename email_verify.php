<?php
// проверка наличия кода в ссылке
// сравнение ссылки с БД
// смена значения активации аккаунта
// авторизация
// перенаправление на профиль

session_start();
require_once 'includes/db.php';
if ($_REQUEST) {
    $verify_code = array_key_first($_REQUEST);

    try {
        $stmt = $pdo->prepare(SQL_VERIFY_CODE);                         // prepare — Подготавливает SQL-запрос к выполнению
        $stmt->bindParam(':verify_code', $verify_code); // bindParam — Привязывает параметр SQL-запроса к POST-переменной
        $result = $stmt->execute();                                       // execute — Запускает подготовленный запрос на выполнение, сохраняем результат
        $user_count = $stmt->rowCount();                                  // rowCount() - возвращает количество строк, нужно для проверки.

        if ($user_count > 0) {                                                // найдена ли строка с искомым login в БД
            $user = $stmt->fetch(PDO::FETCH_ASSOC);                  // rowCount() - возвращает массив данных.

            $active = 1;
            $stmt = $pdo->prepare(SQL_ACTIVE_USER_BY_ID);
            $stmt->bindParam(':active', $active);
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();

            $_SESSION['user'] = $user;                                    // создаём сессию с именем 'logged_user' и сохраняем там данные пользователя
            header('Location: user.php?user_id=' . $user['id']);    // перенаправляем на страницу пользователя
        } else echo "Не правильный код активации аккаунта.";
    } catch (PDOException $e) {                                           // выводим ошибки PDO (работы с БД)
        echo '==== PDO Exception =====: ' . $e->getMessage();
    }
} else header('Location: ./');    // перенаправление
