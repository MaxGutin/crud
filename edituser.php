<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
// todo add validation
try {
    if (isset($_POST['update_user'])) {

        // password was not edit
        if ($_POST['password'] == '') {

            $form_data = array(
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email']
            );

            // update DB
            $stmt = $pdo->prepare(SQL_UPDATE_USER);
            $stmt->bindParam(':full_name',  $form_data['full_name']);
            $stmt->bindParam(':email',  $form_data['email']);
            $stmt->bindParam(':login',      $_SESSION['user']['login']);
            $stmt->execute();
            header('Location: ./user.php?user=' . $_SESSION['user']['login']);

        } else {
            // password was edit
            $form_data = array(
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            );

            if ($form_data['password'] == $_POST['password_confirm']) {
                // password hashing
                try {
                    $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
                } catch (Exception $e) {
                    echo '== HASH ERROR: == ' . $e->getMessage();
                }
                $_SESSION['user']['password'] = $form_data['password']; // update password in session

                // update DB
                $stmt = $pdo->prepare(SQL_UPDATE_USER_EXTENDED);
                $stmt->bindParam(':full_name',  $form_data['full_name']);
                $stmt->bindParam(':email',  $form_data['email']);
                $stmt->bindParam(':password',   $form_data['password']);
                $stmt->bindParam(':login',      $_SESSION['user']['login']);
                $stmt->execute();
                header('Location: ./user.php?user=' . $_SESSION['user']['login']);
            }

        }


    }
    $stmt = $pdo->prepare(SQL_GET_USER);
    $stmt->execute([':login' => $_SESSION['user']['login']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( isset($_POST['abort']) ){
        header('Location: user.php?user=' . $_REQUEST['user']);
    }
} catch (PDOException $e) {
    echo '=== PDO EXCEPTION ===: ' . $e->getMessage();
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
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                form="edit_user" type="submit" name="update_user">
            Сохранить
        </button>
        <button class="mdl-button mdl-js-button mdl-button--raised" form="edit_user" type="submit" name="abort">
            Отмена
        </button>
    </div>
</div>
<article class="mdl-grid main-content">
    <div class="mdl-cell mdl-cell--12-col">
        <form action="" method="post" id="edit_user">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="full_name"
                       name="full_name" value="<?php echo $user['full_name'] ?>">
                <label class="mdl-textfield__label" for="full_name">Name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="email"
                       name="email" value="<?php echo $user['email'] ?>">
                <label class="mdl-textfield__label" for="email">E-mail</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="password"
                       name="password">
                <label class="mdl-textfield__label" for="password">Password</label>
            </div>
            <br>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="password_confirm"
                       name="password_confirm">
                <label class="mdl-textfield__label" for="password_confirm">Password confirm</label>
            </div>
            <br>
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
