<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/messages.php';
try {
    // delete task
    if (isset($_REQUEST['delete_task'])) {
        $stmt = $pdo->prepare(SQL_DELETE_TASK);
        $stmt->bindParam(':task_id', $_REQUEST['task_id']);
        $stmt->execute();
    }

    // get tasks of user
    $stmt = $pdo->prepare(SQL_GET_TASKS);
    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '== PDO EXCEPTION (tasks.php): == <pre>' . $e->getMessage() . '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tasks List</title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.php' ?>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1>Tasks List</h1>
        </div>
    </div>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <a href="task_add.php">
                <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                    <i class="material-icons">add</i>
                </button>
            </a>
        </div>
    </div>
    <article class="mdl-grid main-content">
        <div class="mdl-cell mdl-cell--12-col to-center">
            <table class="mdl-data-table mdl-js-data-table<!-- mdl-data-table--selectable--> mdl-shadow--2dp">

                <thead>
<!--                <tr>-->
<!--                    <th class="mdl-data-table__cell--non-numeric">DONE</th>-->
<!--                    <th class="mdl-data-table__cell--non-numeric">Header</th>-->
<!--                    <th class="mdl-data-table__cell--non-numeric">edit</th>-->
<!--                    <th class="mdl-data-table__cell--non-numeric">Delete</th>-->
<!--                </tr>-->
                </thead>
                <tbody>
                <?php foreach ($tasks as $key => $value) { ?>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">
                            <?php
                            switch ($value['done']) {
                                case 0;
                                echo '<i class="material-icons">panorama_fish_eye</i>';
                                break;
                                case 1;
                                echo '<i class="material-icons">check_circle_outline</i>';
                            }?>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="task.php?task_id=<?php echo $value['id'] ?>"><?php echo $value['header'] ?></a>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="task_editing.php?task_id=<?php echo $value['id'] ?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric">
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?delete_task=&task_id=' . $value['id']; ?>" >
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