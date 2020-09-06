<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/validate.php';

if (isset($_POST['add-user'])) {

    if (empty($_POST['done'])) $_POST['done'] = 0;  // checkbox adaptation

    $form_data = array(
        'done' => $_POST['done'],
        'header' => $_POST['header'],
        'description' => $_POST['description']
    );

    // Validation
    $form_data = clean($form_data); // clean() locate in validate.php
    // Validation end

    // Add task to DB
    try {
        $stmt = $pdo->prepare(SQL_INSERT_TASK);
        $stmt->bindParam(':user_id', $_SESSION['user']['id']);
        $stmt->bindParam(':header', $form_data['header']);
        $stmt->bindParam(':done', $form_data['done']);
        $stmt->bindParam(':description', $form_data['description']);
        $stmt->execute();
        header('Location: ./tasks.php?msg=task_added');
    } catch (PDOException $e) {
        echo '== PDO EXCEPTION (task_add.php): == <pre>' . $e->getMessage() . '</pre>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Task</title>
    <?php //include_once 'includes/statistics.html' ?>
    <?php include_once 'includes/menu.php' ?>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h1>New Task</h1>
        </div>
    </div>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit" form="add-user" name="add-user">Save</button>
            <button class="mdl-button mdl-js-button mdl-button--raised" type="reset" form="add_user">Clean</button>
            <a      class="mdl-button mdl-js-button mdl-button--raised" href="tasks.php">Cancel</a>
        </div>
    </div>
    <article class="mdl-grid main-content">
        <div class="mdl-cell mdl-cell--12-col">

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="add-user">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" type="checkbox" id="done"
                           name="done" value="1">
                    <label class="mdl-textfield__label" for="done">DONE</label>
                </div>
                <br>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" id="header" name="header" autofocus>
                    <label class="mdl-textfield__label" for="header">name</label>
                </div>
                <br>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" rows="3" type="text" id="description"
                              name="description"></textarea>
                    <label class="mdl-textfield__label" for="description">description</label>
                </div>
                <br>

            </form>
        </div>
    </article>
    <?php include_once 'includes/footer.html' ?>
