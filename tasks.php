<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/messages.php';
try {
    // delete user
    if (isset($_GET['delete_user'])) {
        $stmt = $pdo->prepare(SQL_DELETE_USER);
        $stmt->bindParam(':login', $_SESSION['user']['login']);
        $stmt->execute();
        logout();
    }
    // get user list
    $stmt = $pdo->query(SQL_GET_TASKS);
    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '== PDO EXCEPTION (tasks.php): == ' . $e->getMessage();
}
?>
    <!DOCTYPE html>
    <html lang="en">
<head>
    <title>Tasks List</title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.html' ?>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1>Tasks List</h1>
        </div>
    </div>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <a href="add_user.php">
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
                    <th class="mdl-data-table__cell--non-numeric">DONE</th>
                    <th class="mdl-data-table__cell--non-numeric">Header</th>
                    <th class="mdl-data-table__cell--non-numeric"></th>
                    <th class="mdl-data-table__cell--non-numeric"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tasks as $key => $value) { ?>
                    <tr>
                        <td><a href="task.php?user=<?php echo $value['id'] ?>"><?php echo $value['id'] ?></a></td> <!-- todo make for admin -->
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $value['done'] ?></td>
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $value['header'] ?></td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="edit_user.php?user=<?php echo $value['id'] ?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?delete_user=&user=' . $value['id']; ?>" >
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