<?php
$config = include("config.php");

function db_connect(): mysqli|false
{
    global $config;

    mysqli_report(MYSQLI_REPORT_ERROR);
    $conn = new mysqli(
        $config["db_url"],
        $config["db_user"],
        $config["db_password"],
        $config["db_name"],
        $config["db_port"]
    );
    return $conn;
}

$conn = db_connect();

if ($conn->connect_errno) {
    error_log("Failed to connect to db: {$conn->connect_error}");
    die("Could not initiate database connection.");
}

$schema = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE DEFAULT '',
    `password` VARCHAR(255) NOT NULL DEFAULT '',
    `is_admin` BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `topics` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `creator_id` INT NOT NULL,
    `title` VARCHAR(500) NOT NULL DEFAULT '',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(`id`),
    FOREIGN KEY(`creator_id`) REFERENCES `users`(`id`)
);

CREATE TABLE IF NOT EXISTS `replies` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `topic_id` INT NOT NULL,
    `creator_id` INT NOT NULL,
    `content` MEDIUMTEXT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(`id`),
    FOREIGN KEY(`topic_id`) REFERENCES `topics`(`id`),
    FOREIGN KEY(`creator_id`) REFERENCES `users`(`id`)
);";

if ($conn->multi_query($schema)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }

        if (!$conn->more_results()) {
            break;
        }
    } while ($conn->next_result());
} else {
    error_log("Failed to execute schema: {$conn->error}");
    die("Could not execute database schema");
}
