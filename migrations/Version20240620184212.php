<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240620184212 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // SQL statements to create or modify tables, add indexes, etc.
        $this->addSql('
            CREATE TABLE `borrow` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint unsigned NOT NULL,
            `book_id` bigint unsigned NOT NULL,
            `checkout_date` date NOT NULL DEFAULT (curdate()),
            `checkin_date` date DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `borrow_IK_user` (`user_id`),
            KEY `borrow_IK_book` (`book_id`),
            KEY `borrow_IK_checkout` (`checkout_date`),
            KEY `borrow_IK_checkin` (`checkin_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ');
    }

    public function down(Schema $schema) : void
    {
        // Actions to reverse the above changes
        $this->addSql('DROP TABLE borrow');
    }
}
