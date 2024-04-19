<?php

$title = "Start a discussion";
include("../db.php");
include("../layout/top.php");

if (!boolval($_SESSION["auth"])) {
    header("Location: /auth/login.php");
    return;
}

function handle_create_post(): void
{
    global $conn, $err;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST["title"];
        if (empty($title) || strlen($title) > 500) {
            $err = "The title should be from 1 to 500 characters.";
            return;
        }

        $sql = "INSERT INTO `topics` (`creator_id`, `title`) VALUES (?, ?)";
        $user_id = $_SESSION["user_id"];

        $result = $conn->execute_query($sql, [$user_id, $title]);

        if (!$result) {
            error_log("Could not create topic: {$conn->error}");
            $err = "A system error occurred. Please try again later.";
            return;
        }

        $url = "/topics/view.php?id={$conn->insert_id}";
        header("Location: $url");
    }
}

handle_create_post();
?>

<?php
if (isset($err)) {
    echo $err;
}
?>

<h2>Create a topic</h2>
<form method="POST">
    <label>
        Title
    </label>
    <input type="text" maxlength="500" required name="title">
    <button>Submit</button>
</form>

<?php include("../layout/bottom.php") ?>
