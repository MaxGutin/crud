<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/messages.php';
try {
    // delete user
    if (isset($_GET['delete_user'])) {
        $stmt = $pdo->prepare(SQL_DELETE_USER);
        $stmt->bindParam(':login', $_REQUEST['user']);
        $stmt->execute();
//        header('Location: index.php?left');
    }
    // get user list
    $stmt = $pdo->query(SQL_GET_USERS);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '== PDO EXCEPTION (users.php): == <pre>' . $e->getMessage() . '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Список пользователей</title>
    <?php include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.php' ?>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1>Список пользователей</h1>
    </div>
</div>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col remove-margins">
        <a href="user_add.php">
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
                    <th>Login</th>
                    <th class="mdl-data-table__cell--non-numeric">Имя</th>
                    <th class="mdl-data-table__cell--non-numeric">Роль</th>
                    <th class="mdl-data-table__cell--non-numeric"></th>
                    <th class="mdl-data-table__cell--non-numeric"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $key => $value) { ?>
                    <tr>
                        <td><a href="user.php?user=<?php echo $value['login'] ?>"><?php echo $value['login'] ?></a></td> <!-- todo make for admin -->
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $value['full_name'] ?></td>
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $value['role'] ?></td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="user_edit.php?user=<?php echo $value['login'] ?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?delete_user=&user=' . $value['login']; ?>" >
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