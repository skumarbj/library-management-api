<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240620181711 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // SQL statements to create or modify tables, add indexes, etc.
        $this->addSql("
            CREATE TABLE `book` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `title` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
            `author` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
            `genre` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
            `isbn` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
            `published_date` date NOT NULL,
            `status` enum('AVAILABLE','BORROWED') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'AVAILABLE',
            `deleted_date` date DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `book_IK_title` (`title`),
            KEY `book_IK_author` (`author`),
            KEY `book_IK_genre` (`genre`),
            KEY `book_IK_isbn` (`isbn`),
            KEY `book_IK_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down(Schema $schema) : void
    {
        // Actions to reverse the above changes
        $this->addSql('DROP TABLE book');
    }
}
