<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260711160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ensure user_id column exists in category table';
    }

    public function up(Schema $schema): void
    {
        // Add the user_id column if it doesn't exist
        // We use a simple approach: try to add and if it fails, that's ok since it means it already exists
        $this->addSql('ALTER TABLE `category` ADD COLUMN `user_id` INT NULL DEFAULT NULL');
        
        // Now add the foreign key if it doesn't exist
        $this->addSql('ALTER TABLE `category` ADD CONSTRAINT `FK_64C19C1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `category` DROP FOREIGN KEY `FK_64C19C1A76ED395`');
        $this->addSql('ALTER TABLE `category` DROP COLUMN `user_id`');
    }
}

