<?php
$title = "Log in";
include "../layout/top.php";
include "../db.php";

function handle_login_post(): void
{
    global $conn, $err;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            $err = "Please fill in all fields.";
            return;
        }

        $sql = "SELECT `id`, `password` FROM `users` WHERE `username` = ?";
        $user = $conn->execute_query($sql, [$username])->fetch_assoc();

        if ($user == null || count($user) == 0) {
            $err = "Invalid username or password.";
            return;
        }

        $is_match = password_verify($password, $user["password"]);
        if (!$is_match) {
            $err = "Invalid username or password.";
            return;
        }

        $_SESSION["auth"] = true;
        $_SESSION["user_id"] = $user["id"];
        header("Location: /", true, 303);
    }
}

handle_login_post();
?>

<?php
if (isset($err)) {
    echo $err;
}
?>

<h2 class="page-title">Log in</h2>
<form method="POST" class="form">
    <label class="form__label">
        Username
        <input type="text" name="username" maxlength="50" required class="form__input">
    </label>
    <label class="form__label">
        Password
        <input type="password" name="password" maxlength="71" required class="form__input">
    </label>
    <button class="button button--primary">Log in</button>
</form>

<?php include "../layout/bottom.php" ?>
