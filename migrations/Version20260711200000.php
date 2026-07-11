<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260711200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert seed user and set category.user_id for existing nulls, then make column NOT NULL';
    }

    public function up(Schema $schema): void
    {
        // insert seed user if not exists
        $this->addSql('INSERT INTO `user` (username, roles, password, name) '
            . "SELECT 'seed_user', JSON_ARRAY(), '\$2y\$12\$z1nDePg2fqBjOWX7SNbMsORVSGC49riWm3.5XUYL6CUAcy6IcASCe', 'Seed User' "
            . "FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `user` u WHERE u.username = 'seed_user')");

        // set category.user_id to the seed user for existing null categories
        $this->addSql("UPDATE `category` SET user_id = (SELECT id FROM `user` WHERE username = 'seed_user' LIMIT 1) WHERE user_id IS NULL");

        // make user_id NOT NULL
        $this->addSql('ALTER TABLE `category` MODIFY user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // revert: make nullable again
        $this->addSql('ALTER TABLE `category` MODIFY user_id INT DEFAULT NULL');
        // optionally remove seed user (skip to avoid deleting real users)
    }
}
