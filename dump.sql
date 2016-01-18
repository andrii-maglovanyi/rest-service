DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The unique post ID.',
  `post` varchar(140) NOT NULL COMMENT 'The post message, length should not exceed 140 chars.',
  `date` datetime NOT NULL COMMENT 'The date, on which the post was created/modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;