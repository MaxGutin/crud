<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';

try {
    // get task from DB
    $stmt = $pdo->prepare(SQL_GET_TASK);
    $result = $stmt->execute([':task_id' => $_REQUEST['task_id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '== PDO EXCEPTION (task.php) ==: <pre>' . $e->getMessage() . '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $_SESSION['user']['full_name'] ?> | <?php echo $task['header'] ?></title>
    <?php //include_once 'includes/statistics.html' ?>
    <link rel="stylesheet" href="css/print.css" media="print">
    <?php include_once 'includes/menu.html' ?>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1><?php echo($task['header']); ?></h1>
        </div>
    </div>
    <div class="mdl-grid" id="buttons">
        <div class="mdl-cell mdl-cell--12-col">
            <a href="./tasks.php?task_id=<?php echo $task['id'] ?>"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Edit</button></a>
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onclick="window.print();">Print</button>
            <a class="mdl-button mdl-js-button mdl-button--raised"  href="./tasks.php">Abort</a>
            <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent"
               href="tasks.php?delete_task&task_id=<?= $task['id']; ?>">Delete</a>
        </div>
    </div>
    <article class="main-content">
        <article class="mdl-grid">
            <div class="mdl-cell mdl-cell--2-col to-center">
                <p><span><b>ID: </b></span><span><?php echo $task['id'] ?></span></p>
                <p><span><b>DONE: </b></span><span><?php echo $task['done'] ?></span></p>
                <p><span><b>Header: </b></span><span><?php echo $task['header'] ?></span></p>
                <p><span><b>Description: </b></span><pre><?php echo $task['description'] ?></pre></p>
            </div>
        </article>
    </article>
    <?php include_once 'includes/footer.html' ?>
