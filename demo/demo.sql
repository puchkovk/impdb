SET NAMES utf8;

CREATE TABLE `impdb_demo1` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` int unsigned NOT NULL,
  `title` tinytext COLLATE 'utf8mb4_general_ci' NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8mb4_general_ci';

INSERT INTO `impdb_demo1` (`id`, `category_id`, `title`) VALUES
(1,	1,	'Тест 1'),
(2,	1,	'Тест 2'),
(3,	2,	'Тест 3');