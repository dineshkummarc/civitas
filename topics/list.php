<?php
$title = "Browsing posts";
include "../layout/top.php";
include "../db.php";

$sql = "SELECT `topics`.*, `users`.`username` FROM `topics`
JOIN `users` ON `topics`.`creator_id` = `users`.`id`
ORDER BY `last_reply` DESC";

$topics = $conn->execute_query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<?php foreach($topics as $topic): ?>
    <div class="post-summary">
        <header class="post-summary__header">
            <a class="post-summary__link" href="/topics/view.php?id=<?= $topic["id"] ?>">
                <?= htmlspecialchars($topic["title"]) ?>
            </a>
        </header>
        <div class="post-summary__details">
            By @<?= htmlspecialchars($topic["username"]) ?>
            • Last reply: <?= htmlspecialchars($topic["last_reply"]) ?>
        </div>
    </div>
<?php endforeach ?>

<?php include "../layout/bottom.php" ?>
