
INSERT INTO `book` (`id`, `title`, `author`, `genre`, `isbn`, `published_date`, `status`, `deleted_date`)
VALUES
	(1,'ThiruKural','ThiruValluvar','Treatise','8796498734545','1983-03-18','AVAILABLE',NULL),
	(2,'Symfony 5: The Fast Track','Fabien Potencier','Programming Language','978-2918390374','2019-11-01','AVAILABLE',NULL),
	(3,'Python Crash Course, 3rd Edition: A Hands-On, Project-Based Introduction to Programming 3rd Edition','Eric Matthes','Programming Language','978-1718502703','2023-01-10','AVAILABLE',NULL);


INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `deleted_date`)
VALUES
	(1,'Admin','Admin@example.com','$2y$10$1MUQNPbgWMThQyYjkyQOEu1egEXrXzUkwHevuX9TKbK1OqQ4fq6Bq','ADMIN',NULL),
	(2,'User','User@example.com','$2y$10$c.K05/gzyPt5IMsfDVDO3.rYuQRtXCCpwBigAnm9Fkp.ecpqzWhEC','MEMBER',NULL),
	(3,'Reader','Reader@example.com','$2y$10$LyhjkaQgcTopQf0Zs7oQBuQA2x.v/zNwEjSIGREmZwqvl3y8AaMKq','MEMBER',NULL);


INSERT INTO `borrow` (`id`, `user_id`, `book_id`, `checkout_date`, `checkin_date`)
VALUES
	(1,2,1,'2024-06-20','2024-06-21');