<?php
$dsn = 'mysql:host=localhost;dbname=crud';
$db_user = 'root';
$db_password = 'root';
try {                                                                           // подключение перехвата исключений
    $pdo = new PDO($dsn, $db_user, $db_password);                               // инициализация объекта PDO и вставка данных для подключения
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // настройка обработки ошибок
} catch (PDOException $e) {                                                     // вывод исключений
    print '= Error connection: = ' . $e->getMessage();                              // вывод ошибок подключения
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
    SELECT id, active, role, full_name, login, password FROM users WHERE login = :login
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
    SELECT id, active, role, full_name, login, email, password FROM users WHERE login = :login
';

const SQL_GET_USERS = '
    SELECT * FROM users
';

const SQL_UPDATE_USER = '
    UPDATE users SET
      full_name = :full_name,
      email = :email
    WHERE
      login = :login
';
const SQL_UPDATE_USER_EXTENDED = '
    UPDATE users SET
      full_name = :full_name,
      email = :email,
      password = :password
    WHERE
      login = :login
';
const SQL_ACTIVATE_USER = '
    UPDATE users SET
      active = :active
    WHERE
      login = :login
';

const SQL_DELETE_USER = 'DELETE FROM users WHERE login = :login';
