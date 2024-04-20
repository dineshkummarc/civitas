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
    <div>
        <header>
            <a href="/topics/view.php?id=<?= $topic["id"] ?>">
                <?= htmlspecialchars($topic["title"]) ?>
            </a>
        </header>
        By @<?= htmlspecialchars($topic["username"]) ?>
        • Last reply: <?= htmlspecialchars($topic["last_reply"]) ?>
    </div>
<?php endforeach ?>

<?php include "../layout/bottom.php" ?>
