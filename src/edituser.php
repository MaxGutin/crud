<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
try {
    if ( isset($_POST['update_user']) ) {
        $stmt = $pdo->prepare(SQL_UPDATE_USER_BY_ID);
        $stmt->bindParam(':role',       $_POST['role']);
        $stmt->bindParam(':full_name',  $_POST['full_name']);
        $stmt->bindParam(':login',      $_POST['login']);
        $stmt->bindParam(':password',   $_POST['password']);
        $stmt->bindParam(':id',         $_POST['id']);
        $stmt->execute();
        header('Location: ./users.php?msg=user_saved');
    }
    $stmt = $pdo->prepare(SQL_GET_USER);
    $stmt->execute([':id' => $_REQUEST['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( isset($_POST['delete_user']) ) {
/*        $stmt = $pdo->prepare(SQL_DELETE_USER);
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();*/
        header('Location: ./users.php?msg=user_deleted');
    }
    if ( isset($_POST['abort']) ){
        header('Location: ./users.php');
    }
} catch (PDOException $e) {
        echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include_once 'includes/statistics.html' ?>
    <title><?php echo($user['full_name']); ?></title>
    <?php include_once 'includes/menu.html' ?>
<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1><?php echo($user['full_name']); ?></h1>
    </div>
</div>

<div class="mdl-grid" id="buttons">
    <div class="mdl-cell mdl-cell--12-col">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" form="edit_user" type="submit" name="update_user">
            Сохранить
        </button>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" form="edit_user" type="submit" name="delete_user">
            Удалить
        </button>
        <button class="mdl-button mdl-js-button mdl-button--raised" form="edit_user" type="submit" name="abort">
            Отмена
        </button>
    </div>
</div>
<article class="mdl-grid main-content">
    <div class="mdl-cell mdl-cell--12-col">
        <form action="" method="post" id="edit_user">
            <?php
            foreach ($user as $key => $value) {
                ?>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $value ?>">
                    <label class="mdl-textfield__label" for="<?php echo $key ?>"><?php echo $key ?></label>
                </div>
                <br>
            <?php } ?>
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
