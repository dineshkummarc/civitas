<?php
$title = "Log in";
include "../layout/top.php";
include "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT `id`, `password` FROM `users` WHERE `username` = ?";
    $user = $conn->execute_query($sql, [$username])->fetch_assoc();

    if ($user == null || count($user) == 0) {
        $err = "Invalid username or password.";
    } else {
        $is_match = password_verify($password, $user["password"]);

        if (!$is_match) {
            $err = "Invalid username or password.";
        } else {
            $_SESSION["auth"] = true;
            $_SESSION["user_id"] = $user["id"];
            header("Location: /", true, 303);
        }
    }
}
?>

<?php
if (isset($err)) {
    echo $err;
}
?>

<h1>Log in</h1>
<form method="POST">
    <label>
        Username
        <input type="text" name="username">
    </label>
    <label>
        Password
        <input type="password" name="password">
    </label>
    <button>Log in</button>
</form>

<?php include "../layout/bottom.php" ?>
