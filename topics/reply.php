<?php
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
        $topic_id = $_POST["topic_id"];
        $content = $_POST["content"];
        if (empty($content)) {
            $err = "There are missing fields.";
            return;
        }

        $sql = "INSERT INTO `replies` (`topic_id`, `creator_id`, `content`) VALUES (?, ?, ?)";
        $user_id = $_SESSION["user_id"];

        $result = $conn->execute_query($sql, [$topic_id, $user_id, $content]);

        if (!$result) {
            error_log("Could not create reply: {$conn->error}");
            $err = "A system error occurred. Please try again later.";
            return;
        }

        $url = "/topics/view.php?id=$topic_id#reply-{$conn->insert_id}";
        header("Location: $url");
    }
}

handle_create_post();
?>
