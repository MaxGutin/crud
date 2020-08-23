<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
try {
    if ( isset($_GET['delete_user']) ) {
        $stmt = $pdo->prepare(SQL_DELETE_USER);
        $stmt->bindParam(':id', $_GET['user_id']);
        $stmt->execute();
        header('Location: ./users.php?msg=user_deleted');
    }
    if ( isset($_GET['abort']) ){
        header('Location: ./users.php');
    }
    $stmt = $pdo->prepare(SQL_GET_USER);
    $result = $stmt->execute([':id' => $_REQUEST['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Пользователь | <?php echo $user['full_name'] ?></title>
    <?php include_once 'includes/statistics.html' ?>
    <link rel="stylesheet" href="css/print.css" media="print">
    <?php include_once 'includes/menu.html' ?>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1><?php echo($user['full_name']); ?></h1>
    </div>
</div>
<div class="mdl-grid" id="buttons">
    <div class="mdl-cell mdl-cell--12-col">
        <a href="edituser.php?user_id=<? echo $user['id'] ?>"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Редактировать</button></a>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onclick="window.print();">Печать</button>
        <a href="<?php echo $_SERVER['PHP_SELF'] . '?abort'?>" ><button class="mdl-button mdl-js-button mdl-button--raised" type="submit" name="abort">Отмена</button></a>
        <a href="<?php echo $_SERVER['PHP_SELF'] . '?delete_user&user_id=' . $user['id']; ?>"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Удалить</button></a>
    </div>
</div>
<article class="main-content">
    <article class="mdl-grid">
        <div class="mdl-cell mdl-cell--2-col to-center">
            <p><span>Роль: </span><span><?php echo $user['role'] ?></span></p>
            <p><span>Имя: </span><span><?php echo $user['full_name'] ?></span></p>
            <p><span>Логин: </span><span><?php echo $user['login'] ?></span></p>
            <p><span>эл. почта: </span><span><?php echo $user['email'] ?></span></p>
        </div>
    </article>
</article>
<?php include_once 'includes/footer.html' ?>
