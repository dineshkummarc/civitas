<?php
session_start();

$config = include("../config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/global.css">
    <link rel="stylesheet" href="/static/css/controls.css">
    <link rel="stylesheet" href="/static/css/summary.css">
    <link rel="stylesheet" href="/static/css/post_view.css">
    <title><?= $title ?> - <?= $config["forum_title"] ?></title>
</head>
<body>
    <header class="page-header">
        <h1 class="page-header__title"><?= $config["forum_title"] ?></h1>
        <a class="page-header__link" href="/topics/list.php">Home</a>
        <?php if (isset($_SESSION["auth"]) && boolval($_SESSION["auth"])): ?>
            <a class="page-header__link" href="/topics/create.php">Create a topic</a>
            <a class="page-header__link" href="/auth/logout.php">Log out</a>
        <?php else: ?>
            <a class="page-header__link" href="/auth/login.php">Log in</a>
        <?php endif ?>
    </header>
