CREATE TABLE users (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `theme` int(11) NOT NULL
);

CREATE TABLE session (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `hash` varchar(255) NOT NULL,
    `addr` varchar(255) NOT NULL,
    `user_agent` varchar(255) NOT NULL
);

CREATE TABLE task (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `desc` varchar(1024) NOT NULL,
    `start` int(10) NOT NULL,
    `end` int(10) NOT NULL,
    `repeat` int(1) NOT NULL,
    `color` varchar(7) NOT NULL,
    `ends` int(10) NOT NULL,
    `type` int(1) NOT NULL,
    `is_finished` int(1) NOT NULL,
    `categories` varchar(255) NOT NULL
);

CREATE TABLE finished_tasks (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `date_finished` int(10) NOT NULL
);