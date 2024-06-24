<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240620190223 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // SQL statements to create or modify tables, add indexes, etc.
        $this->addSql("
            CREATE TABLE `user` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
            `email` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
            `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
            `role` enum('ADMIN','MEMBER') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'MEMBER',
            `deleted_date` date DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `user_UK_email` (`email`),
            KEY `user_IK_role` (`role`),
            KEY `user_IK_deleted` (`deleted_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down(Schema $schema) : void
    {
        // Actions to reverse the above changes
        $this->addSql('DROP TABLE user');
    }
}
