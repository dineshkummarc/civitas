<?php
session_start();

$config = include("../config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - <?= $config["forum_title"] ?></title>
</head>
<body>
    <header>
        <h1><?= $config["forum_title"] ?></h1>
        <a href="/topics/list.php">Home</a>
        <?php if (isset($_SESSION["auth"]) && boolval($_SESSION["auth"])): ?>
            <a href="/topics/create.php">Create a topic</a>
            <a href="/auth/logout.php">Log out</a>
        <?php else: ?>
            <a href="/auth/login.php">Log in</a>
        <?php endif ?>
    </header>
