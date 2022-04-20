CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `username` varchar(32),
  `role` varchar(8),
  `image_url` varchar(128),
  PRIMARY KEY (id),
  UNIQUE(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `title` varchar(32),
  `content` text NOT NULL,
  `file_url` varchar(128),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(32) NOT NULL,
  `value` varchar(32),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO configs (`key`, `value`)
SELECT * FROM (SELECT 'title', 'Web Security') AS tmp
WHERE NOT EXISTS (
    SELECT `key` FROM configs WHERE `key` = 'title'
) LIMIT 1;
