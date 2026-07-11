<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260711150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id column to category table';
    }

    public function up(Schema $schema): void
    {
        // Add user_id column with index and foreign key in one statement
        $this->addSql('ALTER TABLE `category` ADD user_id INT DEFAULT NULL, ADD INDEX IDX_64C19C1A76ED395 (user_id)');
        $this->addSql('ALTER TABLE `category` ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `category` DROP FOREIGN KEY FK_64C19C1A76ED395');
        $this->addSql('ALTER TABLE `category` DROP COLUMN user_id');
    }
}
