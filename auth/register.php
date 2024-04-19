<?php
$title = "Register";
include "../layout/top.php";
include "../db.php";

function handle_register_post(): void
{
    global $conn, $err;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            $err = "There are missing fields.";
            return;
        }

        if (strlen($username) > 50) {
            $err = "Your username must be 50 characters or shorter.";
            return;
        }

        if (strlen($password) > 72) {
            $err = "Your password must be 72 characters or shorter.";
            return;
        }

        $sql = "INSERT INTO `users` (`username`, `password`) VALUES (?, ?)";

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $result = $conn->execute_query($sql, [$username, $hashed_password]);

        if (!$result) {
            // MySQL database error code 1062: Unique constraint violation
            // Please refer to https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
            if ($conn->errno == 1062) {
                $err = "A user with this username already exists.";
            } else {
                error_log("Could not create user: {$conn->error}");
                $err = "A system error occurred when creating your profile.";
            }
            return;
        }

        $_SESSION["auth"] = true;
        $_SESSION["user_id"] = $conn->insert_id;
        header("Location: /", true, 303);
    }
}

handle_register_post();
?>

<?php
if (isset($err)) {
    echo $err;
}
?>

<h2>Register</h2>
<form method="POST">
    <label>
        Username
        <input type="text" name="username" maxlength="50">
    </label>
    <label>
        Password
        <input type="password" name="password" maxlength="72">
    </label>
    <button>Register</button>
</form>

<?php include "../layout/bottom.php" ?>
