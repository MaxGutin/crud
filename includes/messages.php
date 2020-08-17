<?php
if (isset($_REQUEST['msg'])) {
    switch ($_REQUEST['msg']) {
        case 'user_saved' :
            echo '<div class="message">Данные пользователя сохранены!</div>';
            break;
        case 'user_updated' :
            echo '<div class="message">Данные пользователя обновленны!</div>';
            break;
        case 'user_deleted' :
            echo '<div class="message">Пользователь удалён!</div>';
            break;
    }
}