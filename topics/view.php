<?php
include "../db.php";

$id = $_GET["id"];
if (!isset($id) || intval($id) < 1) {
    header("HTTP/1.1 400 Bad Request");
    echo "Please specify a valid topic ID";
    return;
}

$sql = "SELECT `topics`.*, `users`.`username` FROM `topics`
JOIN `users` ON `topics`.`creator_id` = `users`.`id`
WHERE `topics`.`id` = ?";

$topic = $conn->execute_query($sql, [intval($id)])->fetch_assoc();
if ($topic == null || count($topic) == 0) {
    header("HTTP/1.1 404 Not Found");
    echo "Topic not found";
    return;
}

$sql = "SELECT `replies`.*, `users`.`username` FROM `replies`
JOIN `users` ON `replies`.`creator_id` = `users`.`id`
WHERE `replies`.`topic_id` = ?
ORDER BY `created_at`";

$replies = $conn->execute_query($sql, [intval($id)])->fetch_all(MYSQLI_ASSOC);

$title = "Topic \"{$topic["title"]}\" by {$topic["username"]}";
include "../layout/top.php";

?>

<header>
    <h2><?= htmlspecialchars($topic["title"]) ?></h2>
    <p>
        Discussion started by
        <?= htmlspecialchars($topic["username"]) ?>
        at
        <?= htmlspecialchars($topic["created_at"]) ?>
    </p>
</header>

<form action="/topics/reply.php" method="POST">
    <input type="hidden" name="topic_id" value="<?= $topic["id"] ?>">
    <label>
        Content
        <textarea name="content" cols="30" rows="10" required></textarea>
    </label>
    <button>Submit</button>
</form>

<?php foreach($replies as $reply): ?>
    <article id="reply-<?= $reply["id"] ?>">
        <header>
            <strong>@<?= htmlspecialchars($reply["username"]) ?></strong>
            <?= htmlspecialchars($reply["created_at"]) ?>
        </header>
        <?= htmlspecialchars($reply["content"]) ?>
    </article>
<?php endforeach ?>

<?php include "../layout/bottom.php" ?>
