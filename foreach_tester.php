<?php
function tester1($a)
{
    if ($a) {
        echo "<pre>";
        print_r($a);
        echo '</pre>';
    } else echo '== UNDEFINED ==';
}

function clean($value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    return $value;
}

$form_data = array(
    'full_name' => 'full_name\n',
    'login' => '  ccc    ',
    'email' => '',
    'password' => 'password'
);

tester1($form_data);

foreach ($form_data as &$value) {
    $value = clean($value);
}

tester1($form_data);

if(empty($form_data['full_name']) OR empty($form_data['login']) OR empty($form_data['email']) OR empty($form_data['password'])) {
    exit('Заполните все значения.');
}
echo 'Форма заполнена';