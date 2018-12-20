<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/messages.php';
try {
    if ( isset($_GET['delete_user']) ) {
/*        $stmt = $pdo->prepare(SQL_DELETE_USER);
        $stmt->bindParam(':id', $_GET['user_id']);
        $stmt->execute();
        echo "<p>Пользователь удалён из базы данных!</p><hr>";*/
        echo 'Вы не можете удалять пользователей!';
    }
    $stmt = $pdo->query(SQL_GET_USERS);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Список пользователей</title>
    <?php include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.html' ?>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1>Список пользователей</h1>
    </div>
</div>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <a href="adduser.php">
            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                <i class="material-icons">add</i>
            </button>
        </a>
    </div>
</div>
    <article class="mdl-grid main-content">
        <div class="mdl-cell mdl-cell--12-col to-center">
            <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">

                <thead>
                <tr>
                    <th>ID</th>
                    <th class="mdl-data-table__cell--non-numeric">Имя</th>
                    <th class="mdl-data-table__cell--non-numeric">Роль</th>
                    <th class="mdl-data-table__cell--non-numeric"></th>
                    <th class="mdl-data-table__cell--non-numeric"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $key => $value) { ?>
                    <tr>
                        <td><a href="./user.php?user_id=<?php echo $value['id'] ?>"><?php echo $value['id'] ?></a></td>
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $value['full_name'] ?></td>
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $value['role'] ?></td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="edituser.php?user_id=<?php echo $value['id'] ?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?delete_user=&user_id=' . $value['id']; ?>" >
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </article>
<?php include_once 'includes/footer.html' ?>
