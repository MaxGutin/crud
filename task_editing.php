<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/validate.php';
try {
    if (isset($_POST['update_task'])) {

        $form_data = array(
            'header' => $_POST['header'],
            'description' => $_POST['description']
        );

// Validation
        $form_data = clean($form_data); // clean() locate in validate.php
// Validation end

        // update DB
        $stmt = $pdo->prepare(SQL_UPDATE_TASK);
        $stmt->bindParam(':header',  $form_data['header']);
        $stmt->bindParam(':description',  $form_data['description']);
        $stmt->bindParam(':task_id',   $_REQUEST['task_id']);
        $stmt->execute();
        header('Location: ./tasks.php');
    }
    $stmt = $pdo->prepare(SQL_GET_TASK);
    $stmt->bindParam(':task_id', $_REQUEST['task_id']);
    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt->execute();
    $task_count = $stmt->rowCount();
    if ($task_count > 0) {
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        exit('Access denied!');
    }

} catch (PDOException $e) {
    echo '== PDO EXCEPTION (task_editing.php) == : <pre>' . $e->getMessage() . '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $_SESSION['user']['header'] ?> | <?php echo $task['header'] ?></title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.html' ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1><?php echo($task['header']); ?></h1>
        </div>
    </div>

    <div class="mdl-grid" id="buttons">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                    form="edit_task" type="submit" name="update_task">
                Сохранить
            </button>
            <a class="mdl-button mdl-js-button mdl-button--raised" href="tasks.php">Отмена</a>
        </div>
    </div>
    <article class="mdl-grid main-content">
        <div class="mdl-cell mdl-cell--12-col">
            <form action="" method="post" id="edit_task">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="header"
                           name="header" value="<?php echo $task['header'] ?>">
                    <label class="mdl-textfield__label" for="header">Header</label>
                </div>
                <br>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="description"
                           name="description" value="<?php echo $task['description'] ?>">
                    <label class="mdl-textfield__label" for="description">Description</label>
                </div>
                <br>
            </form>
        </div>
    </article>
    <?php include_once 'includes/footer.html' ?>
