<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/validate.php';
    if (isset($_POST['update_task'])) {

        if (empty($_POST['done'])) $_POST['done'] = 0;  // checkbox adaptation

        $form_data = array(
            'done' => $_POST['done'],
            'header' => $_POST['header'],
            'description' => $_POST['description']
        );

        // Validation
        $form_data = clean($form_data); // clean() locate in validate.php
        // Validation end

        // update DB
        $stmt = $pdo->prepare(SQL_UPDATE_TASK);
        $stmt->bindParam(':done',  $form_data['done']);
        $stmt->bindParam(':header',  $form_data['header']);
        $stmt->bindParam(':description',  $form_data['description']);
        $stmt->bindParam(':task_id',   $_REQUEST['task_id']);
        $stmt->execute();
        header('Location: ./tasks.php');
    }
    try {
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
    <title><?= $task['header'] ?> | <?= $_SESSION['user']['full_name'] ?></title>
<?php //include_once 'includes/statistics.html' ?>
<?php include_once 'includes/menu.php' ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1><?= $task['header'] ?></h1>
        </div>
    </div>

    <div class="mdl-grid" id="buttons">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                    form="edit_task" type="submit" name="update_task">Save</button>
            <a class="mdl-button mdl-js-button mdl-button--raised" href="tasks.php">Cancel</a>
        </div>
    </div>
    <article class="mdl-grid main-content">
        <div class="mdl-cell mdl-cell--12-col">

            <form action="" method="post" id="edit_task">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" type="checkbox" id="done"
                           name="done" value="1" <?php if ($task['done'] == 1) echo 'checked' ?>>
                    <label class="mdl-textfield__label" for="done">DONE</label>
                </div>
                <br>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="header"
                           name="header" value="<?= $task['header'] ?>">
                    <label class="mdl-textfield__label" for="header">Header</label>
                </div>
                <br>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" rows="3" type="text" id="description"
                              name="description"><?= $task['description'] ?></textarea>
                    <label class="mdl-textfield__label" for="description">Description</label>
                </div>
                <br>

            </form>
        </div>
    </article>
<?php include_once 'includes/footer.html' ?>
