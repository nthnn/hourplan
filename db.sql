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