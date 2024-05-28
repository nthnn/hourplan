CREATE TABLE users (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `theme` int(11) NOT NULL
);

CREATE TABLE session (
  `user_id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL
);

CREATE TABLE task (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `desc` varchar(1024) NOT NULL,
  `start` int(10) NOT NULL,
  `end` int(10) NOT NULL,
  `repeat` int(1) NOT NULL,
  `ends` int(10) NOT NULL,
  `type` int(1) NOT NULL
);