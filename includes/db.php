<?php
$dsn = 'mysql:dbname=crud;host=localhost';
$db_user = 'root';
$db_password = 'root';
try {                                                                           // подключение перехвата исключений
    $pdo = new PDO($dsn, $db_user, $db_password);                               // инициализация объекта PDO и вставка данных для подключения
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // настройка обработки ошибок
    function db_select($sql, $var) {
        try {
            $stmt = $pdo->prepare(SQL_LOGIN);                         // prepare — Подготавливает SQL-запрос к выполнению
            $stmt->bindParam(':login', $form_data['login']); // bindParam — Привязывает параметр SQL-запроса к POST-переменной
            $result = $stmt->execute();                                       // execute — Запускает подготовленный запрос на выполнение, сохраняем результат
            $user_count = $stmt->rowCount();                                  // rowCount() - возвращает количество строк, нужно для проверки.
        } catch (PDOException $e) {                                           // выводим ошибки PDO (работы с БД)
            echo '====CATCH=====: ' . $e->getMessage();
        }
        if ($user_count > 0) {                                                // найдена ли строка с искомым login в БД
            $user = $stmt->fetch(PDO::FETCH_ASSOC);                  // rowCount() - возвращает массив данных.
            if (password_verify($form_data['password'], $user['password'])) { // если пароли сопадают то выполняем авторизацию
                $_SESSION['user'] = $user;                                    // создаём сессию с именем 'logged_user' и сохраняем там данные пользователя
                header('Location: user.php?user_id=' . $user['id']);    // перенаправляем на страницу пользователя
            } else echo "Не правильный пароль.";                              // если не совпадает пароль то вывести сообщение
        } else echo "Не правильный логин.";                                   // если пользователь не найден то выводим сообщение
    }
} catch (PDOException $e) {                                                     // вывод исключений
    print 'Error connection: ' . $e->getMessage();                              // вывод ошибок подключения
}

// Тестировщики
function tester1($a)
{
    if ($a) {
        echo "<pre>";
        print_r($a);
        echo '</pre>';
    } else echo '== UNDEFINED ==';
}
function tester2($a)
{
    if ($a) {
        echo "<pre>";
        var_dump($a);
        echo '</pre>';
    } else echo '== UNDEFINED ==';
}

// Константы с SQL выражениями
const SQL_LOGIN = '
    SELECT id, role, full_name, login, password FROM users WHERE login = :login
';

// Константы с SQL выражениями
const SQL_VERIFY_CODE = '
    SELECT id, role, full_name, login, password, verify_code FROM users WHERE verify_code = :verify_code
';

const SQL_CREATE_USERS_TABLE = '
	CREATE TABLE IF NOT EXISTS users (
		id INT UNSIGNED AUTO_INCREMENT NOT NULL,
		active BOOLEAN NOT NULL DEFAULT \'0\',
		role VARCHAR(50) NOT NULL DEFAULT \'user\',
		full_name VARCHAR(255) NOT NULL,
		login VARCHAR(255) NOT NULL UNIQUE,
		email VARCHAR(255) NOT NULL,
		password VARCHAR(255) NOT NULL,
		verify_code CHAR(32) NOT NULL,
		PRIMARY KEY (id)
	)
';

const SQL_INSERT_USER = '
    INSERT INTO users (full_name, login, email, password, verify_code) VALUE (?,?,?,?,?)
';

const SQL_GET_USER = '
    SELECT id, role, full_name, login, email, password FROM users WHERE id = :id
';

const SQL_GET_USERS = '
    SELECT * FROM users
';

const SQL_UPDATE_USER_BY_ID = '
    UPDATE users SET
      role = :role,
      full_name = :full_name,
      login = :login, 
      password = :password
    WHERE
      id = :id
';
const SQL_ACTIVE_USER_BY_ID = '
    UPDATE users SET
      active = :active
    WHERE
      id = :id
';

const SQL_DELETE_USER = 'DELETE FROM users WHERE id = :id';
